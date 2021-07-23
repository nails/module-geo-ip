<?php

/**
 * Migration:  1
 * Started:    23/07/2021
 *
 * @package    Nails
 * @subpackage module-geo-ip
 * @category   Database Migration
 * @author     Nails Dev Team
 */

namespace Nails\GeoIp\Database\Migration;

use Nails\Common\Console\Migrate\Base;

/**
 * Class Migration1
 *
 * @package Nails\GeoIp\Database\Migration
 */
class Migration1 extends Base
{
    /**
     * Execute the migration
     */
    public function execute()
    {
        $this->query('ALTER TABLE `{{NAILS_DB_PREFIX}}geoip_cache` ADD `country_code` CHAR(2) NOT NULL DEFAULT "" AFTER `country`;');
        $this->query('ALTER TABLE `{{NAILS_DB_PREFIX}}geoip_cache` ADD `continent` VARCHAR(150) NOT NULL DEFAULT "" AFTER `country_code`;');
        $this->query('ALTER TABLE `{{NAILS_DB_PREFIX}}geoip_cache` ADD `continent_code` CHAR(2) NOT NULL DEFAULT "" AFTER `continent`;');
    }
}
