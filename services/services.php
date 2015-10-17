<?php

return array(
    'services' => array(
        'GeoIp' => function () {
            if (class_exists('\App\GeoIp\Library\GeoIp')) {
                return new \App\GeoIp\Library\GeoIp();
            } else {
                return new \Nails\GeoIp\Library\GeoIp();
            }
        }
    ),
    'factories' => array(
        'Ip' => function () {
            if (class_exists('\App\GeoIp\Result\Ip')) {
                return new \App\GeoIp\Result\Ip();
            } else {
                return new \Nails\GeoIp\Result\Ip();
            }
        }
    )
);
