<?php

/**
 * Migration:  0
 * Started:    20/05/2021
 *
 * @package    Nails
 * @subpackage module-geo-ip
 * @category   Database Migration
 * @author     Nails Dev Team
 */

namespace Nails\GeoIp\Database\Migration;

use Nails\Common\Console\Migrate\Base;

/**
 * Class Migration0
 *
 * @package Nails\GeoIp\Database\Migration
 */
class Migration0 extends Base
{
    /**
     * Execute the migration
     */
    public function execute()
    {
        $this->query("
            CREATE TABLE `{{NAILS_DB_PREFIX}}geoip_cache` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `ip` varchar(255) NOT NULL DEFAULT '',
                `hostname` varchar(255) NOT NULL DEFAULT '',
                `city` varchar(255) NOT NULL DEFAULT '',
                `region` varchar(255) NOT NULL DEFAULT '',
                `country` varchar(255) NOT NULL DEFAULT '',
                `lat` varchar(255) NOT NULL DEFAULT '',
                `lng` varchar(255) NOT NULL DEFAULT '',
                `created` datetime NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }
}
