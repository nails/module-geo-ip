<?php

/**
 * This model manages the Geo-IP drivers
 *
 * @package     Nails
 * @subpackage  module-geo-ip
 * @category    Model
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\GeoIp\Model;

use Nails\Common\Model\BaseDriver;

class Driver extends BaseDriver
{
    protected $sModule         = 'nailsapp/module-geo-ip';
    protected $bEnableMultiple = false;
}
