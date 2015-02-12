<?php

/**
 * This class abstracts access to the GeoIP service
 *
 * @package     Nails
 * @subpackage  module-geo-ip
 * @category    Library
 * @author      Nails Dev Team
 * @link
 * @todo        Update this library to be a little mroe comprehensive, like the CDN library
 */

class Geo_ip
{
    private $driver;

    // --------------------------------------------------------------------------

    public function __construct($config = array())
    {
        $driver = ! empty($config['driver']) ? $config['driver'] : 'Nails_ip_services';
        $driver = ucfirst(strtolower($driver));

        $driverPath  = ! empty($config['driver_path']) ? $config['driver_path'] : FCPATH . 'vendor/nailsapp/module-geo-ip/geo_ip/_resources/drivers/';
        $driverPath .= substr($driverPath, -1) != '/' ? '/' : '';

        $driverConfig = ! empty($config['driver_config']) ? (array) $config['driver_config'] : array();

        if (file_exists($driverPath . $driver . '.php')) {

            require_once $driverPath . $driver . '.php';

            $class = 'Geo_ip_driver_' . $driver;
            $this->driver = new $class($driverConfig);

        } else {

            showFatalError($driverPath . $driver . '.php is not a valid Geo_ip driver');
        }
    }

    // --------------------------------------------------------------------------

    public function __call($method, $arguments)
    {
        if (method_exists($this->driver, $method)) :

            return call_user_func_array(array($this->driver, $method), $arguments);

        else :

            throw new Exception('<strong>Fatal error</strong>: Call to undefined method Geo_ip::' . $method . '()');

        endif;
    }
}
