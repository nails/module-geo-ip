<?php

/**
 * This class abstracts access to the GeoIP service
 *
 * @package    Nails
 * @subpackage module-geo-ip
 * @category   Service
 * @author     Nails Dev Team
 */

namespace Nails\GeoIp\Service;

use Nails\Common\Traits\Caching;
use Nails\Factory;
use Nails\GeoIp\Constants;
use Nails\GeoIp\Exception\GeoIpDriverException;
use Nails\GeoIp\Exception\GeoIpException;
use Nails\GeoIp\Result\Ip;
use Nails\GeoIp\Interfaces;
use Nails\GeoIp\Result;

/**
 * Class GeoIp
 *
 * @package Nails\GeoIp\Service
 */
class GeoIp
{
    use Caching;

    // --------------------------------------------------------------------------

    /** @var Interfaces\Driver */
    protected $oDriver;

    /** @var \Nails\Common\Service\Country */
    protected $oCountryService;

    // --------------------------------------------------------------------------

    /**
     * The name of the table to store cached results
     */
    const DB_CACHE_TABLE = NAILS_DB_PREFIX . 'geoip_cache';

    /**
     * How long a cached item is valid for, MySQL DATE_SUB interval
     */
    const CACHE_PERIOD = '1 HOUR';

    // --------------------------------------------------------------------------

    /**
     * Construct the Service, test that the driver is valid
     *
     * @throws GeoIpDriverException
     */
    public function __construct()
    {
        $this->oDriver = $this->getDriverInstance();

        /** @var \Nails\Common\Service\Country $oCountryService */
        $oCountryService       = Factory::service('Country');
        $this->oCountryService = $oCountryService;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns an instance of the driver
     *
     * @param string|null $sSlug The driver's slug
     *
     * @throws GeoIpDriverException
     * @return Interfaces\Driver
     */
    public function getDriverInstance(string $sSlug = null): Interfaces\Driver
    {
        /** @var \Nails\GeoIp\Service\Driver $oDriverService */
        $oDriverService = Factory::service('Driver', Constants::MODULE_SLUG);
        $sEnabledDriver = appSetting($oDriverService->getSettingKey(), Constants::MODULE_SLUG);
        /** @var \Nails\Common\Factory\Component|null $oEnabledDriver */
        $oEnabledDriver = $oDriverService->getEnabled();

        if (empty($sEnabledDriver) && empty($oEnabledDriver)) {
            //  No configured driver, default to first available driver and hope for the best
            $aDrivers = $oDriverService->getAll();
            /** @var \Nails\Common\Factory\Component $oEnabledDriver */
            $oEnabledDriver = reset($aDrivers);
        }

        if (empty($sEnabledDriver) && empty($oEnabledDriver)) {
            throw new GeoIpDriverException('No Geo-IP drivers are available.');

        } elseif (empty($oEnabledDriver)) {
            throw new GeoIpDriverException('Driver "' . $sEnabledDriver . '" is not installed');
        }

        $oDriver = $oDriverService->getInstance($oEnabledDriver->slug);

        if (!classImplements($oDriver, Interfaces\Driver::class)) {
            throw new GeoIpDriverException(sprintf(
                '"%s" must implement "%s"',
                get_class($oDriver),
                Interfaces\Driver::class
            ));
        }

        return $oDriver;
    }

    // --------------------------------------------------------------------------

    /**
     * Return all information about a given IP
     *
     * @param string|null $sIp The IP to get details for
     *
     * @return Result\Ip
     * @throws GeoIpException
     */
    public function lookup(string $sIp = null): Result\Ip
    {
        $sIp = trim((string) $sIp);

        if (empty($sIp) && !empty($_SERVER['REMOTE_ADDR'])) {
            $sIp = $_SERVER['REMOTE_ADDR'];
        }

        $oCache = $this->getCache($sIp);

        if (!empty($oCache)) {
            return $oCache;
        }

        /** @var \Nails\Common\Service\Database $oDb */
        $oDb = Factory::service('Database');
        $oDb->where('ip', $sIp);
        $oDb->where('created >', 'DATE_SUB(NOW(), INTERVAL ' . static::CACHE_PERIOD . ')', false);
        $oDb->limit(1);
        $oResult = $oDb->get(self::DB_CACHE_TABLE)->row();

        if (!empty($oResult)) {

            /** @var Ip $oIp */
            $oIp = Factory::factory('Ip', Constants::MODULE_SLUG);
            $oIp
                ->setIp($sIp)
                ->setHostname($oResult->hostname)
                ->setCity($oResult->city)
                ->setRegion($oResult->region)
                ->setCountry($oResult->country)
                ->setCountryCode($oResult->country_code)
                ->setContinent($oResult->continent)
                ->setContinentCode($oResult->continent_code)
                ->setLat($oResult->lat)
                ->setLng($oResult->lng);

        } else {

            $oIp = $this->oDriver->lookup($sIp);

            if (!($oIp instanceof Ip)) {
                throw new GeoIpException(sprintf(
                    'Geo IP Driver did not return a %s result',
                    Result\Ip::class
                ));
            }

            $this->populateBlanks($oIp);

            if (empty($oIp->getError())) {

                //  Save to the DB Cache
                $oDb->set('ip', $oIp->getIp());
                $oDb->set('hostname', $oIp->getHostname());
                $oDb->set('city', $oIp->getCity());
                $oDb->set('region', $oIp->getRegion());
                $oDb->set('country', $oIp->getCountry());
                $oDb->set('country_code', $oIp->getCountryCode());
                $oDb->set('continent', $oIp->getContinent());
                $oDb->set('continent_code', $oIp->getContinentCode());
                $oDb->set('lat', $oIp->getLat());
                $oDb->set('lng', $oIp->getLng());
                $oDb->set('created', 'NOW()', false);
                $oDb->insert(self::DB_CACHE_TABLE);
            }
        }

        $this->setCache($sIp, $oIp);

        return $oIp;
    }

    // --------------------------------------------------------------------------

    /**
     * If there's data missing populate what we can given we know the country code
     *
     * @param \Nails\GeoIp\Result\Ip $oIp
     */
    protected function populateBlanks(Ip $oIp): void
    {
        if (!$oIp->getCountry() && $oIp->getCountryCode()) {
            $this->populateCountryFromCountryCode($oIp);
        }

        if (!$oIp->getContinentCode() && $oIp->getCountryCode()) {
            $this->populateContinentCodeFromCountryCode($oIp);
        }

        if (!$oIp->getContinent() && $oIp->getContinentCode()) {
            $this->populateContinentFromCountryCode($oIp);
        }
    }

    // --------------------------------------------------------------------------

    /**
     * Given a known country code, set the country name
     *
     * @param \Nails\GeoIp\Result\Ip $oIp
     */
    protected function populateCountryFromCountryCode(Ip $oIp): void
    {
        $oCountry = $this->oCountryService->getCountry($oIp->getCountryCode());

        $oIp->setCountry($oCountry->name ?? '');
    }

    // --------------------------------------------------------------------------

    /**
     * Given a known country code, set the continent code
     *
     * @param \Nails\GeoIp\Result\Ip $oIp
     */
    protected function populateContinentCodeFromCountryCode(Ip $oIp): void
    {
        $oCountry = $this->oCountryService->getCountry($oIp->getCountryCode());

        $oIp->setContinentCode($oCountry->continent->iso ?? '');
    }

    // --------------------------------------------------------------------------

    /**
     * Given a known continen codet, set the continent name
     *
     * @param \Nails\GeoIp\Result\Ip $oIp
     */
    protected function populateContinentFromCountryCode(Ip $oIp): void
    {
        $oContinent = $this->oCountryService->getContinent($oIp->getContinentCode());

        $oIp->setContinent($oContinent->name ?? '');
    }

    // --------------------------------------------------------------------------

    /**
     * Return the IP property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function ip(string $sIp): string
    {
        return $this->lookup($sIp)->getIp();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the hostname property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function hostname(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getHostname();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the city property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function city(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getCity();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the region property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function region(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getRegion();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the country property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function country(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getCountry();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the country code property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function countryCode(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getCountryCode();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the continent property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function continent(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getContinent();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the continent code property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function continentCode(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getContinentCode();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the latLng property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return \stdClass
     */
    public function latLng(string $sIp = ''): \stdClass
    {
        return $this->lookup($sIp)->getLatLng();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lat property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function lat(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getLat();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lng property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string
     */
    public function lng(string $sIp = ''): string
    {
        return $this->lookup($sIp)->getLng();
    }
}
