<?php

return [
    'services'  => [
        'Driver' => function () {
            if (class_exists('\App\GeoIp\Service\Driver')) {
                return new \App\GeoIp\Service\Driver();
            } else {
                return new \Nails\GeoIp\Service\Driver();
            }
        },
        'GeoIp'  => function () {
            if (class_exists('\App\GeoIp\Service\GeoIp')) {
                return new \App\GeoIp\Service\GeoIp();
            } else {
                return new \Nails\GeoIp\Service\GeoIp();
            }
        },
    ],
    'factories' => [
        'Ip' => function () {
            if (class_exists('\App\GeoIp\Result\Ip')) {
                return new \App\GeoIp\Result\Ip();
            } else {
                return new \Nails\GeoIp\Result\Ip();
            }
        },
    ],
];
