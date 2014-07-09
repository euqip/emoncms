<?php

$schema['users'] = array(
    'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
    'orgid' => array('type' => 'int(11)', 'Null'=>'NO','default'=>'0'),
    'username' => array('type' => 'varchar(30)'),
    'email' => array('type' => 'varchar(30)'),
    'password' => array('type' => 'varchar(64)'),
    'salt' => array('type' => 'varchar(3)'),
    'apikey_write' => array('type' => 'varchar(64)'),
    'apikey_read' => array('type' => 'varchar(64)'),
    'lastlogin' => array('type' => 'datetime'),
    'admin' => array('type' => 'int(11)', 'Null'=>'NO', 'comment'=>'set to 1 to define system administrator'),

    // User profile fields
    'gravatar' => array('type' => 'varchar(30)', 'default'=>''),
    'name'=>array('type'=>'varchar(30)', 'default'=>''),
    'location'=>array('type'=>'varchar(30)', 'default'=>''),
    'timezone' => array('type' => 'int(11)', 'default'=>0),
    'language' => array('type' => 'varchar(5)', 'default'=>'en_EN'),
    'bio' => array('type' => 'text', 'default'=>''),
    'index' => array(
        'orgid'=> array('unique'=>false),
        'username'=> array('unique'=>true)
        )

);


$schema['rememberme'] = array(
    'userid' => array('type' => 'int(11)'),
    'token' => array('type' => 'varchar(40)'),
    'persistentToken' => array('type' => 'varchar(40)'),
    'expire' => array('type' => 'datetime')
);
