<?php

namespace Nails\GeoIp\Interfaces;

use Nails\GeoIp\Result;

interface Driver
{
    /**
     * Returns information about a particular IP
     *
     * @param string $sIp The IP address to look up
     *
     * @return Result\Ip
     */
    public function lookup(string $sIp): Result\Ip;
}
