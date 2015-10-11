<?php

return array(
    'services' => array(
        'GeoIp' => function () {
            return new \Nails\GeoIp\Library\GeoIp();
        }
    )
);
