<?php

$schema['input'] = array(
    'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
    'userid' => array('type' => 'int(11)'),
    'orgid' => array('type' => 'int(11)', 'default'=>'0'),
    'name' => array('type' => 'text'),
    'description' => array('type' => 'text','default'=>''),
    'nodeid' => array('type' => 'text'),
    'processList' => array('type' => 'text'),
    'time' => array('type' => 'datetime'),
    'value' => array('type' => 'float'),
    'location' => array('type' => 'varchar(20)')
);

// location example: 50.641319, 3.062243
