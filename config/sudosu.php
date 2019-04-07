<?php
return [
    // 是否开启
    'enable' => env('SUDOSU_ENABLE', true),

    // 允许使用的顶级域名
    'allowed_tlds' => ['dev', 'local', 'test'],

    // 用户模型
    'user_model' => App\Models\User::class
    
];
