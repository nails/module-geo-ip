<?php

/**
 * This class is an IP object which should be returned by IP Drivers
 *
 * @package    Nails
 * @subpackage module-geo-ip
 * @category   Result
 * @author     Nails Dev Team
 */

namespace Nails\GeoIp\Result;

/**
 * Class Ip
 *
 * @package Nails\GeoIp\Result
 */
class Ip
{
    /** @var string */
    protected $sIp;

    /** @var string */
    protected $sHostname;

    /** @var string */
    protected $sCity;

    /** @var string */
    protected $sRegion;

    /** @var string */
    protected $sCountry;

    /** @var string */
    protected $sCountryCode;

    /** @var string */
    protected $sContinent;

    /** @var string */
    protected $sContinentCode;

    /** @var string */
    protected $sLat;

    /** @var string */
    protected $sLng;

    /** @var string */
    protected $sError;

    // --------------------------------------------------------------------------

    /**
     * Define the IP object
     *
     * @param string $sIp
     * @param string $sHostname
     * @param string $sCity
     * @param string $sRegion
     * @param string $sCountry
     * @param string $sCountryCode
     * @param string $sContinent
     * @param string $sContinentCode
     * @param string $sLat
     * @param string $sLng
     */
    public function __construct(
        string $sIp = '',
        string $sHostname = '',
        string $sCity = '',
        string $sRegion = '',
        string $sCountry = '',
        string $sCountryCode = '',
        string $sContinent = '',
        string $sContinentCode = '',
        string $sLat = '',
        string $sLng = '',
        string $sError = ''
    ) {
        $this->sIp            = $sIp;
        $this->sHostname      = $sHostname;
        $this->sCity          = $sCity;
        $this->sRegion        = $sRegion;
        $this->sCountry       = $sCountry;
        $this->sCountryCode   = $sCountryCode;
        $this->sContinent     = $sContinent;
        $this->sContinentCode = $sContinentCode;
        $this->sLat           = $sLat;
        $this->sLng           = $sLng;
        $this->sError         = $sError;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the IP property
     *
     * @param string $sIp The IP to set
     *
     * @return $this
     */
    public function setIp(string $sIp): self
    {
        $this->sIp = $sIp;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the IP address
     *
     * @return string
     */
    public function getIp(): string
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
    public function setHostname(string $sHostname): self
    {
        $this->sHostname = $sHostname;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the hostname
     *
     * @return string
     */
    public function getHostname(): string
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
    public function setCity(string $sCity): self
    {
        $this->sCity = $sCity;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the city
     *
     * @return string
     */
    public function getCity(): string
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
    public function setRegion(string $sRegion): self
    {
        $this->sRegion = $sRegion;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the region
     *
     * @return string
     */
    public function getRegion(): string
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
    public function setCountry(string $sCountry): self
    {
        $this->sCountry = $sCountry;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the country
     *
     * @return string
     */
    public function getCountry(): string
    {
        return $this->sCountry;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Country Code property
     *
     * @param string $sCountryCode The Country Code to set
     *
     * @return $this
     */
    public function setCountryCode(string $sCountryCode): self
    {
        $this->sCountryCode = $sCountryCode;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the country code
     *
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->sCountryCode;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Continent property
     *
     * @param string $sContinent The Continent to set
     *
     * @return $this
     */
    public function setContinent(string $sContinent): self
    {
        $this->sContinent = $sContinent;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the continent
     *
     * @return string
     */
    public function getContinent(): string
    {
        return $this->sContinent;
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Continent Code property
     *
     * @param string $sContinentCode The Continen tCode to set
     *
     * @return $this
     */
    public function setContinentCode(string $sContinentCode): self
    {
        $this->sContinentCode = $sContinentCode;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the continent code
     *
     * @return string
     */
    public function getContinentCode(): string
    {
        return $this->sContinentCode;
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
    public function setLatLng(string $sLat, string $sLng): self
    {
        return $this
            ->setLat($sLat)
            ->setLng($sLng);
    }

    // --------------------------------------------------------------------------

    /**
     * Set the Latitude property
     *
     * @param string $sLat The Latitude to set
     *
     * @return $this
     */
    public function setLat(string $sLat): self
    {
        $this->sLat = $sLat;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the latitude
     *
     * @return string
     */
    public function getLat(): string
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
    public function setLng(string $sLng): self
    {
        $this->sLng = $sLng;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the longitude
     *
     * @return string
     */
    public function getLng(): string
    {
        return $this->sLng;
    }

    // --------------------------------------------------------------------------

    /**
     * Get the IP's coordinates
     *
     * @return \stdClass
     */
    public function getLatLng(): \stdClass
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
    public function setError(string $sError): self
    {
        $this->sError = $sError;
        return $this;
    }

    // --------------------------------------------------------------------------

    /**
     * Returns the error message
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->sError;
    }
}
