<?php
return [
    [
        'class'      => 'yii\rest\UrlRule',
        'controller' => [
            'v1/user',
        ],
    ],
    'GET v1/user/index' => 'v1/user/index', // api测试
    'GET v1/yar/yar' => 'v1/yar/yar', // RPC调试
    'GET v1/yar/all' => 'v1/yar/all', // RPC all调试
    'POST v1/user/register' => 'v1/user/register', // api RPC用户注册
];