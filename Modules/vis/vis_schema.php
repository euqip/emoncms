<?php

$schema['multigraph'] = array(
    'id' => array('type' => 'int(11)', 'Null'=>'NO', 'Key'=>'PRI', 'Extra'=>'auto_increment'),
    'name' => array('type' => 'text'),
    'userid' => array('type' => 'int(11)'),
    'orgid' => array('type' => 'int(11)', 'default'=>'0'),
    'feedlist' => array('type' => 'text')
);