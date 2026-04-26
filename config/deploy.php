<?php
return [
    'options' => [
        'php_fpm_service' => 'php7.1-fpm',
    ],
    'hooks' => [
        'done' => ['fpm:reload'],
    ],
];
