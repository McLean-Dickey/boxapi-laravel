<?php

return [
    'dev_mode' => env('BOX_APP_DEV_MODE', false),

    'config_file' => env('BOX_APP_CONFIG_FILE', 'box_app_config.json'),

    'dev_token' => env('BOX_DEV_TOKEN', null),

    'app_user' => [
        "id" => env('BOX_APP_USER_ID', null),
        "login" => env('BOX_APP_USER_LOGIN', null),
    ]
];