<?php

return [
    'db'=>[
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=management_mysql.db;dbname=sp_pst',
        'username'=>'root',
        'password'=>'root',
        'charset'=>'utf8'
    ],
    //redis
    //passport服务器的redis  //
    'redis' => [
        'class' => 'yii\redis\Connection',
        'hostname'=>'redis.db',
        'port' => 6379,
        'database' => 1,
        'password'=>'123456',
    ],

];
