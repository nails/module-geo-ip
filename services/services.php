<?php

use Nails\GeoIp\Service;
use Nails\GeoIp\Result;

return [
    'services'  => [
        'Driver' => function () {
            if (class_exists('\App\GeoIp\Service\Driver')) {
                return new \App\GeoIp\Service\Driver();
            } else {
                return new Service\Driver();
            }
        },
        'GeoIp'  => function () {
            if (class_exists('\App\GeoIp\Service\GeoIp')) {
                return new \App\GeoIp\Service\GeoIp();
            } else {
                return new Service\GeoIp();
            }
        },
    ],
    'factories' => [
        'Ip' => function (): Result\Ip {
            if (class_exists('\App\GeoIp\Result\Ip')) {
                return new \App\GeoIp\Result\Ip();
            } else {
                return new Result\Ip();
            }
        },
    ],
];
