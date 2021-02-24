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
use Nails\GeoIp\Constants;

class Driver extends BaseDriver
{
    protected $sModule         = Constants::MODULE_SLUG;
    protected $bEnableMultiple = false;
}
