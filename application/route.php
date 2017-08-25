<?php
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    'member' => 'index/member/index',
    'member/seven_sign' => 'index/member/seven_sign',
    'member/seven_detail' => 'index/member/seven_detail',
    'register' => 'index/member/register',
    'login' => 'index/member/login',
    'bind' => 'index/member/bind',
    'list/[:id]' => 'index/article/index',
    'article/[:id]' => 'index/article/detail',
    'wap/list/[:id]' => 'wap/article/index',
    'wap/article/[:id]' => 'wap/article/detail',
    'primary' => 'index/glory/primary',
    'semi' => 'index/glory/semi',
    'finals' => 'index/glory/finals',
    'lover' => 'wap/lover/index',
    'wap/lover/detail/[:id]' => 'wap/lover/detail',
    'wap/lover/loverlist/[:id]' => 'wap/lover/loverlist',
    'index/detail/[:id]' => 'index/lover/detail',
    'index/loverlist/[:id]' => 'index/lover/loverlist',
];
