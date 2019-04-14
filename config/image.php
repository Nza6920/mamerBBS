<?php
if (env('APP_ENV') == 'local') {
    return [
        'driver' => 'imagick'
    ];
}

return [
    'driver' => 'gd'
];
