<?php

/**
 * This class is an IP object which should be returned by IP Drivers
 *
 * @package     Nails
 * @subpackage  module-geo-ip
 * @category    Result
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\GeoIp\Result;

class Ip
{
    private $sIp;
    private $sHostname;
    private $sCity;
    private $sRegion;
    private $sCountry;
    private $sLat;
    private $sLng;
    private $sError;

    // --------------------------------------------------------------------------

    /**
     * Define the IP object
     *
     * @param string $sIp
     * @param string $sHostname
     * @param string $sCity
     * @param string $sRegion
     * @param string $sCountry
     * @param string $sLat
     * @param string $sLng
     */
    public function __construct(
        $sIp = '',
        $sHostname = '',
        $sCity = '',
        $sRegion = '',
        $sCountry = '',
        $sLat = '',
        $sLng = '',
        $sError = ''
    ) {
        $this->sIp       = $sIp;
        $this->sHostname = $sHostname;
        $this->sCity     = $sCity;
        $this->sRegion   = $sRegion;
        $this->sCountry  = $sCountry;
        $this->sLat      = $sLat;
        $this->sLng      = $sLng;
        $this->sError    = $sError;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the IP property
     *
     * @param string $sIp The IP to set
     *
     * @return $this
     */
    public function setIp($sIp)
    {
        $this->sIp = $sIp;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the IP address
     * @return string
     */
    public function getIp()
    {
        return $this->sIp;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the hostname property
     *
     * @param string $sHostname The hostname to set
     *
     * @return $this
     */
    public function setHostname($sHostname)
    {
        $this->sHostname = $sHostname;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the hostname
     * @return string
     */
    public function getHostname()
    {
        return $this->sHostname;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the City property
     *
     * @param string $sCity The city to set
     *
     * @return $this
     */
    public function setCity($sCity)
    {
        $this->sCity = $sCity;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the city
     * @return string
     */
    public function getCity()
    {
        return $this->sCity;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Region property
     *
     * @param string $sRegion The region to set
     *
     * @return $this
     */
    public function setRegion($sRegion)
    {
        $this->sRegion = $sRegion;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the region
     * @return string
     */
    public function getRegion()
    {
        return $this->sRegion;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Country property
     *
     * @param string $sCountry The Country to set
     *
     * @return $this
     */
    public function setCountry($sCountry)
    {
        $this->sCountry = $sCountry;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the country
     * @return string
     */
    public function getCountry()
    {
        return $this->sCountry;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the IP's Latitude and Longitude
     *
     * @param string $sLat The IP's Latitude
     * @param string $sLng The IP's Longitude
     *
     * @return $this
     */
    public function setLatLng($sLat, $sLng)
    {
        $this->setLat($sLat);
        $this->setLng($sLng);
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Latitude property
     *
     * @param string $sLat The Latitude to set
     *
     * @return $this
     */
    public function setLat($sLat)
    {
        $this->sLat = $sLat;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the latitude
     * @return string
     */
    public function getLat()
    {
        return $this->sLat;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Longitude property
     *
     * @param string $sLng The Longitude to set
     *
     * @return $this
     */
    public function setLng($sLng)
    {
        $this->sLng = $sLng;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the longitude
     * @return string
     */
    public function getLng()
    {
        return $this->sLng;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the IP's coordinates
     * @return \stdClass
     */
    public function getLatLng()
    {
        return (object) [
            'lat' => $this->sLat,
            'lng' => $this->sLng,
        ];
    }

    // --------------------------------------------------------------------------

    /**
     * Set the error message
     *
     * @param string $sError The error message to set
     */
    public function setError($sError)
    {
        $this->sError = $sError;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the error message
     *
     * @return string
     */
    public function getError()
    {
        return $this->sError;
    }
}
