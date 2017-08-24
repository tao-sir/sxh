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
    'register' => 'index/member/register',
    'login' => 'index/member/login',
    'bind' => 'index/member/bind',
    'list/[:id]' => 'index/article/index',
    'article/[:id]' => 'index/article/detail',
    'primary' => 'index/glory/primary',
    'semi' => 'index/glory/semi',
    'finals' => 'index/glory/finals',
    'lover' => 'wap/lover/index',
    'wap/lover/detail/[:id]' => 'wap/lover/detail',
    'wap/lover/loverlist/[:id]' => 'wap/lover/loverlist',
];
