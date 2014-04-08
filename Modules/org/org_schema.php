<?php


$schema['orgs'] = array(
    'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
    'orgname' => array('type' => 'varchar(30)', 'Null'=>'NO'),
    'salt' => array('type' => 'varchar(3)'),
    'apikey_write' => array('type' => 'varchar(64)'),
    'apikey_read' => array('type' => 'varchar(64)'),
    'lastuse' => array('type' => 'datetime'),

    // Organisations profile fields
    'logo' => array('type' => 'varchar(30)', 'default'=>''),
    'longname'=>array('type'=>'varchar(30)', 'default'=>'', 'Null'=>'NO'),
    'address'=>array('type'=>'varchar(30)', 'default'=>''),
    'zip'=>array('type'=>'varchar(30)', 'default'=>''),
    'city'=>array('type'=>'varchar(30)', 'default'=>''),
    'state'=>array('type'=>'varchar(30)', 'default'=>''),
    'country'=>array('type'=>'varchar(30)', 'default'=>''),
    'location'=>array('type'=>'varchar(30)', 'default'=>''),
    'timezone' => array('type' => 'int(11)', 'default'=>0),
    'language' => array('type' => 'varchar(5)', 'default'=>'en_EN'),
    'index' => array(
        'orgname' => array('unique'=>true),
        'longname' => array('unique'=>true)
        )
);