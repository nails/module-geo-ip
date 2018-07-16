<?php

return [
    'services'  => [
        'GeoIp' => function () {
            if (class_exists('\App\GeoIp\Service\GeoIp')) {
                return new \App\GeoIp\Service\GeoIp();
            } else {
                return new \Nails\GeoIp\Service\GeoIp();
            }
        },
    ],
    'models'    => [
        'Driver' => function () {
            if (class_exists('\App\GeoIp\Model\Driver')) {
                return new \App\GeoIp\Model\Driver();
            } else {
                return new \Nails\GeoIp\Model\Driver();
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
