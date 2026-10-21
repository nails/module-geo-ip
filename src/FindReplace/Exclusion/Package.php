<?php

namespace Nails\GeoIp\FindReplace\Exclusion;

use Nails\FindReplace\Interfaces\Exclusion;

class Package implements Exclusion
{
    public function hidden(): array
    {
        return [
            NAILS_DB_PREFIX . 'geoip_cache',
        ];
    }

    public function readOnly(): array
    {
        return [];
    }
}
