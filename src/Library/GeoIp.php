<?php

/**
 * This class abstracts access to the GeoIP service
 *
 * @package     Nails
 * @subpackage  module-geo-ip
 * @category    Library
 * @author      Nails Dev Team
 * @link
 * @todo        Update this library to be a little more comprehensive, like the CDN library
 */

namespace Nails\GeoIp\Library;

use Nails\GeoIp\Exception\GeoIpException;

class GeoIp
{
    use \Nails\Common\Traits\Caching;

    // --------------------------------------------------------------------------

    private $oDriver;
    private $aCache;

    // --------------------------------------------------------------------------

    /**
     * Construct the Library, test that the driver is valid
     * @throws GeoIpException
     */
    public function __construct()
    {
        //  Load the storage driver
        $sDriverClassName = defined('APP_GEOIP_DRIVER') ? ucfirst(strtolower(APP_GEOIP_DRIVER)) : 'Nails';
        $sDriverClassName = '\Nails\GeoIp\Driver\\' . $sDriverClassName;

        //  Test if class exists
        if (!class_exists($sDriverClassName)) {

            throw new GeoIpException('"' . $sDriverClassName . '" is not a valid GeoIP Driver.', 1);
        }

        //  Ensure driver implements the correct interface
        if (!in_array('Nails\GeoIp\Interfaces\Driver', class_implements($sDriverClassName))) {

            throw new GeoIpException(
                '"' . $sDriverClassName . '" must implement the Nails\GeoIp\Interfaces\Driver interface.',
                2
            );
        }

        $this->oDriver = new $sDriverClassName($this);
    }

    // --------------------------------------------------------------------------

    /**
     * Return all information about a given IP
     * @param string $sIp The IP to get details for
     * @return \stdClass
     */
    public function lookup($sIp = '')
    {
        $sIp = trim($sIp);

        if (empty($sIp) && !empty($_SERVER['REMOTE_ADDR'])) {

            $sIp = $_SERVER['REMOTE_ADDR'];
        }

        $oCache = $this->_get_cache($sIp);

        if (!empty($oCache)) {

            return $oCache;
        }

        $oIp = $this->oDriver->lookup($sIp);

        if (!($oIp instanceof \Nails\GeoIp\Result\Ip)) {

            throw new GeoIpException(
                'Geo IP Driver did not return a \Nails\GeoIp\Result\Ip result',
                3
            );
        }

        $this->_set_cache($sIp, $oIp);

        return $oIp;
    }

    // --------------------------------------------------------------------------

    /**
     * Return the IP property of a lookup
     * @param string $sIp The IP to look up
     * @return string|null
     */
    public function ip($sIp)
    {
        return $this->lookup($sIp)->getIp();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the hostname property of a lookup
     * @param string $sIp The IP to look up
     * @return string|null
     */
    public function hostname($sIp = '')
    {
        return $this->lookup($sIp)->getHostname();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the city property of a lookup
     * @param string $sIp The IP to look up
     * @return string|null
     */
    public function city($sIp = '')
    {
        return $this->lookup($sIp)->getCity();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the region property of a lookup
     * @param string $sIp The IP to look up
     * @return string|null
     */
    public function region($sIp = '')
    {
        return $this->lookup($sIp)->getRegion();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the country property of a lookup
     * @param string $sIp The IP to look up
     * @return string|null
     */
    public function country($sIp = '')
    {
        return $this->lookup($sIp)->getCountry();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the latLng property of a lookup
     * @param string $sIp The IP to look up
     * @return string|null
     */
    public function latLng($sIp = '')
    {
        return $this->lookup($sIp)->getLatLng();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lat property of a lookup
     * @param string $sIp The IP to look up
     * @return string|null
     */
    public function lat($sIp = '')
    {
        return $this->lookup($sIp)->getLat();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lng property of a lookup
     * @param string $sIp The IP to look up
     * @return string|null
     */
    public function lng($sIp = '')
    {
        return $this->lookup($sIp)->getLng();
    }
}
