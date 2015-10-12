<?php

return array(
    'services' => array(
        'GeoIp' => function () {
            return new \Nails\GeoIp\Library\GeoIp();
        }
    ),
    'factories' => array(
        'Ip' => function () {
            return new \Nails\GeoIp\Result\Ip();
        }
    )
);
