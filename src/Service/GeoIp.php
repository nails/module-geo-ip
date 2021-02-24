<?php

/**
 * This class abstracts access to the GeoIP service
 *
 * @package     Nails
 * @subpackage  module-geo-ip
 * @category    Library
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\GeoIp\Service;

use Nails\Common\Traits\Caching;
use Nails\Factory;
use Nails\GeoIp\Constants;
use Nails\GeoIp\Exception\GeoIpDriverException;
use Nails\GeoIp\Exception\GeoIpException;
use Nails\GeoIp\Result\Ip;

class GeoIp
{
    use Caching;

    // --------------------------------------------------------------------------

    /**
     * The Driver instance
     * @var Nails\GeoIp\Interfaces\Driver
     */
    protected $oDriver;

    // --------------------------------------------------------------------------

    /**
     * Construct the Service, test that the driver is valid
     * @throws GeoIpDriverException
     */
    public function __construct()
    {
        $this->oDriver = $this->getDriverInstance();
    }

    // --------------------------------------------------------------------------

    /**
     * Returns an instance of the driver
     *
     * @param  string $sSlug The driver's slug
     *
     * @throws GeoIpDriverException
     * @return \Nails\GeoIp\Interfaces\Driver
     */
    public function getDriverInstance($sSlug = null)
    {
        $oDriverService = Factory::service('Driver', Constants::MODULE_SLUG);
        $sEnabledDriver = appSetting($oDriverService->getSettingKey(), Constants::MODULE_SLUG);
        $oEnabledDriver = $oDriverService->getEnabled();

        if (empty($sEnabledDriver) && empty($oEnabledDriver)) {
            //  No configured driver, default to first available driver and hope for the best
            $aDrivers       = $oDriverService->getAll();
            $oEnabledDriver = reset($aDrivers);
        }

        if (empty($sEnabledDriver) && empty($oEnabledDriver)) {
            throw new GeoIpDriverException('No Geo-IP drivers are available.');
        } elseif (empty($oEnabledDriver)) {
            throw new GeoIpDriverException('Driver "' . $sEnabledDriver . '" is not installed');
        }

        $oDriver = $oDriverService->getInstance($oEnabledDriver->slug);

        //  Ensure driver implements the correct interface
        $sInterfaceName = 'Nails\GeoIp\Interfaces\Driver';
        if (!classImplements($oDriver, $sInterfaceName)) {
            throw new GeoIpDriverException(
                '"' . get_class($oDriver) . '" must implement "' . $sInterfaceName . '"'
            );
        }

        return $oDriver;
    }

    // --------------------------------------------------------------------------

    /**
     * Return all information about a given IP
     *
     * @param string $sIp The IP to get details for
     *
     * @throws GeoIpException
     * @return \Nails\GeoIp\Result\Ip
     */
    public function lookup($sIp = '')
    {
        $sIp = trim($sIp);

        if (empty($sIp) && !empty($_SERVER['REMOTE_ADDR'])) {
            $sIp = $_SERVER['REMOTE_ADDR'];
        }

        $oCache = $this->getCache($sIp);

        if (!empty($oCache)) {
            return $oCache;
        }

        $oIp = $this->oDriver->lookup($sIp);

        if (!($oIp instanceof Ip)) {
            throw new GeoIpException('Geo IP Driver did not return a \Nails\GeoIp\Result\Ip result');
        }

        $this->setCache($sIp, $oIp);

        return $oIp;
    }

    // --------------------------------------------------------------------------

    /**
     * Return the IP property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string|null
     */
    public function ip($sIp)
    {
        return $this->lookup($sIp)->getIp();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the hostname property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string|null
     */
    public function hostname($sIp = '')
    {
        return $this->lookup($sIp)->getHostname();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the city property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string|null
     */
    public function city($sIp = '')
    {
        return $this->lookup($sIp)->getCity();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the region property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string|null
     */
    public function region($sIp = '')
    {
        return $this->lookup($sIp)->getRegion();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the country property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string|null
     */
    public function country($sIp = '')
    {
        return $this->lookup($sIp)->getCountry();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the latLng property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string|null
     */
    public function latLng($sIp = '')
    {
        return $this->lookup($sIp)->getLatLng();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lat property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string|null
     */
    public function lat($sIp = '')
    {
        return $this->lookup($sIp)->getLat();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lng property of a lookup
     *
     * @param string $sIp The IP to look up
     *
     * @return string|null
     */
    public function lng($sIp = '')
    {
        return $this->lookup($sIp)->getLng();
    }
}
