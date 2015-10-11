<?php

namespace Nails\GeoIp\Interfaces;

interface Driver
{
    /**
     * Returns information about a particular IP
     * @param string $sIp  The IP address to look up
     * @return \stdClass
     */
    public function lookup($sIp);
}
