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
        $this->aCache  = array();
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

        if (!empty($this->aCache[$sIp])) {

            return $this->aCache[$sIp];
        }

        $this->aCache[$sIp] = $this->oDriver->lookup($sIp);

        if (!($this->aCache[$sIp] instanceof \Nails\GeoIp\Result\Ip)) {

            throw new GeoIpException(
                'Geo IP Driver did not return a \Nails\GeoIp\Result\Ip result',
                3
            );
        }

        return $this->aCache[$sIp];
    }

    // --------------------------------------------------------------------------

    /**
     * Return the city where an IP address resides
     * @param string $sIp The IP to get the city detail for
     * @return string|null
     */
    public function hostname($sIp = '')
    {
        return $this->lookup($sIp)->getHostname();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the city where an IP address resides
     * @param string $sIp The IP to get the city detail for
     * @return string|null
     */
    public function city($sIp = '')
    {
        return $this->lookup($sIp)->getCity();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the region where an IP address resides
     * @param string $sIp The IP to get the region detail for
     * @return string|null
     */
    public function region($sIp = '')
    {
        return $this->lookup($sIp)->getRegion();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the country where an IP address resides
     * @param string $sIp The IP to get the country detail for
     * @return string|null
     */
    public function country($sIp = '')
    {
        return $this->lookup($sIp)->getCountry();
    }

    // --------------------------------------------------------------------------

    /**
     * Return the lat/long coordinates of where an IP address resides
     * @param string $sIp The IP to get lat/long coordinates for
     * @return array
     */
    public function latLng($sIp = '')
    {
        return $this->lookup($sIp)->getLatLng();
    }
}
