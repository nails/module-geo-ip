<?php

/**
 * This service manages the Geo-IP drivers
 *
 * @package     Nails
 * @subpackage  module-geo-ip
 * @category    Service
 * @author      Nails Dev Team
 * @link
 */

namespace Nails\GeoIp\Service;

use Nails\Common\Model\BaseDriver;

class Driver extends BaseDriver
{
    protected $sModule         = 'nails/module-geo-ip';
    protected $bEnableMultiple = false;
}
