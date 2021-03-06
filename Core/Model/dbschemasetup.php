<?php

/*

    All Emoncms code is released under the GNU Affero General Public License.
    See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Emoncms - open source energy visualisation
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

*/

// no direct access
defined('EMONCMS_EXEC') or die('Restricted access');

function db_schema_setup($mysqli, $schema, $apply)
{
    $operations = array();
    while ($table = key($schema))
    {
        // if table exists:
        $result = $mysqli->query("SHOW TABLES LIKE '".$table."'");
        if (($result != null ) && ($result->num_rows==1))
        {
            // $out[] = array('Table',$table,"ok");
            //-----------------------------------------------------
            // Check table fields from schema
            //-----------------------------------------------------
            while ($field = key($schema[$table]))
            {
                if ($field!='index'){
                    $type = $schema[$table][$field]['type'];
                    if (isset($schema[$table][$field]['Null'])) $null = $schema[$table][$field]['Null']; else $null = "YES";
                    if (isset($schema[$table][$field]['Key'])) $key = $schema[$table][$field]['Key']; else $key = null;
                    if (isset($schema[$table][$field]['default'])) $default = $schema[$table][$field]['default']; else unset($default);
                    if (isset($schema[$table][$field]['Extra'])) $extra = $schema[$table][$field]['Extra']; else $extra = null;
                    if (isset($schema[$table][$field]['comments'])) $comment = $schema[$table][$field]['comments']; else $comments = null;

                    // if field exists:
                    $result = $mysqli->query("SHOW COLUMNS FROM `$table` LIKE '$field'");
                    if ($result->num_rows==0)
                    {
                        $query = "ALTER TABLE `$table` ADD `$field` $type";
                        if ($null) $query .= " NOT NULL";
                        if (isset($default)) $query .= " DEFAULT '$default'";
                        if (isset($comment)) $query .= " COMMENT '$comment'";
                        $operations[] = $query;
                        if ($apply) $mysqli->query($query);
                    }
                    else
                    {
                      $result = $mysqli->query("DESCRIBE $table `$field`");
                      $array = $result->fetch_array();
                      $query = "";

                      if ($array['Type']!=$type) $query .= " ";
                      if (isset($default) && $array['Default']!=$default) $query .= " DEFAULT '$default'";
                      if ($array['Null']!=$null && $null=="NO") $query .= " not null";
                      if ($array['Extra']!=$extra && $extra=="auto_increment") $query .= " auto_increment";
                      if ($array['Key']!=$key && $key=="PRI") $query .= " primary key";

                      if ($query) $query = "ALTER TABLE $table MODIFY `$field` $type".$query;
                      if ($query) $operations[] = $query;
                      if ($query && $apply) $mysqli->query($query);
                    }
                } else{
                    while ($index = key($schema[$table][$field]))
                    {
                        $nonunique = 1;
                        $sql = "` ADD INDEX (`".$index."`)";
                        if (isset($schema[$table][$field][$index]['unique']) &&  ($schema[$table][$field][$index]['unique']==true)){
                            $nonunique=0;
                            $sql = "` ADD UNIQUE (`".$index."`)";
                        }
                        $query="SHOW INDEX FROM `".$table."`  WHERE Column_name ='".$index."' AND Non_unique =".$nonunique;
                        $result = $mysqli->query($query);
                        if ($result->num_rows==0)
                        {
                            $query="ALTER TABLE  `".$table.$sql;
                            $operations[] = $query;
                            if ($query && $apply) $mysqli->query($query);
                        }
                next($schema[$table][$field]);
                    }
                }
                next($schema[$table]);
            }
        } else {
            //-----------------------------------------------------
            // Create table from schema
            //-----------------------------------------------------
            //ini_set('max_execution_time', 50);
            $comma= '';
            $query = "CREATE TABLE " . $table . " (";
            while ($field = key($schema[$table]))
            {
                if ($field!='index'){
                    $type = $schema[$table][$field]['type'];

                    if (isset($schema[$table][$field]['Null'])) $null = $schema[$table][$field]['Null']; else $null = "YES";
                    if (isset($schema[$table][$field]['Key'])) $key = $schema[$table][$field]['Key']; else $key = null;
                    if (isset($schema[$table][$field]['default'])) $default = $schema[$table][$field]['default']; else $default = null;
                    if (isset($schema[$table][$field]['Extra'])) $extra = $schema[$table][$field]['Extra']; else $extra = null;
                    if (isset($schema[$table][$field]['comments'])) $comment = $schema[$table][$field]['comments']; else $comment = null;
                    $query .= $comma;

                    $query .= '`'.$field.'`';
                    $query .= " $type";
                    if (isset($default)) $query .= " DEFAULT '$default'";
                    if ($null=="NO") $query .= " not null";
                    if ($extra) $query .= " auto_increment";
                    if ($key) $query .= " primary key";
                    if (isset($comment)) $query .= " COMMENT '$comment'";

                    next($schema[$table]);
                    $comma=', ';
                } else {
                    next($schema[$table]);
                }
            }
            $query .= ")";
            $query .= " ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

            if ($query) $operations[] = $query;
            if ($query && $apply) $mysqli->query($query);
        }
       next($schema);
    }
    return $operations;
}
