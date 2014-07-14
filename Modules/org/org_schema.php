<?php


$schema['orgs'] = array(
    'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
    'orgname' => array('type' => 'varchar(30)', 'Null'=>'NO'),
    'salt' => array('type' => 'varchar(3)'),
    'apikey_write' => array('type' => 'varchar(64)'),
    'apikey_read' => array('type' => 'varchar(64)'),
    'lastuse' => array('type' => 'datetime'),

    // Organisations profile fields
    'logo' => array('type' => 'varchar(255)', 'default'=>''),
    'longname'=>array('type'=>'varchar(255)', 'default'=>'', 'Null'=>'NO'),
    'address'=>array('type'=>'varchar(255)', 'default'=>''),
    'zip'=>array('type'=>'varchar(30)', 'default'=>''),
    'city'=>array('type'=>'varchar(50)', 'default'=>''),
    'state'=>array('type'=>'varchar(50)', 'default'=>''),
    'country'=>array('type'=>'varchar(50)', 'default'=>''),
    'location'=>array('type'=>'varchar(30)', 'default'=>''),
    'timezone' => array('type' => 'int(11)', 'default'=>0),
    'language' => array('type' => 'varchar(5)', 'default'=>'en_EN'),
    'delflag' => array('type' => 'tinyint(1)', 'default'=>0),
    'deldate' => array('type' => 'datetime', 'Null'=>'YES'),
    'delby' => array('type' => 'varchar(30)', 'default'=>'', 'Null'=>'YES'),
    'delbyid' => array('type' => 'int(11)', 'Null'=>'YES'),
    //'createdate' => array('type' => 'datetime','default'=>'CURRENT_TIMESTAMP', 'Null'=>'NO'),
    'createdate' => array('type' => 'datetime', 'Null'=>'YES'),
    'createby' => array('type' => 'varchar(30)', 'default'=>'', 'Null'=>'NO'),
    'createbyid' => array('type' => 'int(11)', 'Null'=>'YES'),
    'index' => array(
        'orgname' => array('unique'=>true),
        'longname' => array('unique'=>true)
        )

);
$schema_ref['orgs'] = array(
    'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
    'orgname' => array('type' => 'varchar(30)', 'Null'=>'NO'),
    'salt' => array('type' => 'varchar(3)'),
    'apikey_write' => array('type' => 'varchar(64)'),
    'apikey_read' => array('type' => 'varchar(64)'),
    'lastuse' => array('type' => 'datetime'),

    // Organisations profile fields
    'logo' => array('type' => 'varchar(255)', 'default'=>''),
    'longname'=>array('type'=>'varchar(255)', 'default'=>'', 'Null'=>'NO'),
    'address'=>array('type'=>'varchar(255)', 'default'=>''),
    'zip'=>array('type'=>'varchar(30)', 'default'=>''),
    'city'=>array('type'=>'varchar(50)', 'default'=>''),
    'state'=>array('type'=>'varchar(50)', 'default'=>''),
    'country'=>array('type'=>'varchar(50)', 'default'=>''),
    'location'=>array('type'=>'varchar(30)', 'default'=>''),
    'timezone' => array('type' => 'int(11)', 'default'=>0),
    'language' => array('type' => 'varchar(5)', 'default'=>'en_EN'),
    'delflag' => array('type' => 'tinyint(1)', 'default'=>0),
    'deldate' => array('type' => 'datetime', 'Null'=>'YES'),
    'delby' => array('type' => 'varchar(30)', 'default'=>'', 'Null'=>'YES'),
    'delbyid' => array('type' => 'int(11)', 'Null'=>'YES'),
    //'createdate' => array('type' => 'datetime','default'=>'CURRENT_TIMESTAMP', 'Null'=>'NO'),
    'createdate' => array('type' => 'datetime', 'Null'=>'YES'),
    'createby' => array('type' => 'varchar(30)', 'default'=>'', 'Null'=>'NO'),
    'createbyid' => array('type' => 'int(11)', 'Null'=>'YES'),
    'index' => array(
        'orgname' => array('unique'=>true),
        'longname' => array('unique'=>true)
        )
);