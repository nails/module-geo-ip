<?php

namespace Nails\GeoIp\Cron\Task\Cache;

use Nails\Cron\Task\Base;

class Clear extends Base
{
    /**
     * The cron expression of when to run
     *
     * @var string
     */
    const CRON_EXPRESSION = '@hourly';

    /**
     * The console command to execute
     *
     * @var string
     */
    const CONSOLE_COMMAND = 'geoip:cache:clear';
}
