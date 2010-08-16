<?php

define('APPLICATION_ENV','production');
include 'bootstrap.php';

$config = Zend_Registry::get('config');
$dbParams = $config->resources->db->params;

$options = array();
$options['infoSchema']       = Zend_Db::factory('Pdo_Mysql', array(
    'host'     => $dbParams->host,
    'username' => $dbParams->username,
    'password' => $dbParams->password,
    'dbname'   => 'information_schema'
));
$options['schema']           = $dbParams->dbname;
$options['nameFilter']       = new Zend_Filter_Word_UnderscoreToCamelCase();
$options['tableClassPrefix'] = $dbParams->tableNamespace .  (substr($dbParams->tableNamespace,-1)=='_' ? '' : '_');
$options['rowClassPrefix']   = $options['tableClassPrefix'] . 'Row_';
$options['baseTableClass']   = $options['tableClassPrefix'] . 'Abstract';
$options['baseRowClass']     = $options['rowClassPrefix'] . 'Abstract';

$db = Zend_Db_Table::getDefaultAdapter();
$tables = $db->listTables();
foreach ($tables as $table) {
  $modelName = ucfirst($options['nameFilter']->filter($table));
  $tableClass = $options['tableClassPrefix'] . $modelName;
  $content = "  protected \$_name         = '$table';\n";
  $pk = getPrimaryKeys($table, $options);
  if (count($pk)>1 || !isSequence($table, $pk[0], $options)) {
    $content .= "  protected \$_sequence     = false;\n";
  }
  $pk = "array('" . implode("','",$pk) . "')";
  $content .= "  protected \$_primary      = $pk;\n";
  $ok = getOrderKeys($table, $options);
  if (count($ok)>0) {
    $ok = "array('" . implode("','",$ok) . "')";
    $content .= "  protected \$_defaultOrder = $ok;\n";
  }
  $rowClass = $options['baseRowClass'];
  $fks = getForeignKeys($table,$options);
  if (count($fks)>0) {
    $content .= "  protected \$_referenceMap = array(\n";
    foreach ($fks as $fkID => $fk) {
      $cols = "array('" . implode("','",$fk['columns']) . "')";
      $refCols = "array('" . implode("','",$fk['refColumns']) . "')";
      $content .= "    '$fkID' => array(\n";
      $content .= "      'columns'        => $cols,\n";
      $content .= "      'refTableClass'  => '" . $fk['refTableClass'] . "',\n";
      $content .= "      'refColumns'     => $refCols\n";
      $content .= "    ),\n";
    }
    $content .= "  );\n";
  }
  emitClass($tableClass, $options['baseTableClass'], $content, true);
  $modelClass = $options['rowClassPrefix'] . $modelName;
  $content = "";
  emitClass($modelClass, $rowClass, $content);
}

function getForeignKeys($table,$options) {
  extract($options);
  $rows = $infoSchema->fetchAll("
    SELECT    column_name,
              ordinal_position,
              referenced_table_name,
              referenced_column_name 
    FROM      key_column_usage 
    WHERE     table_schema=? AND table_name=? AND referenced_table_name is not null
    ORDER BY  1,2,3
  ",array($schema,$table));
  $foreignKeys = array();
  foreach ($rows as $row) {
    $refTable = $row['referenced_table_name'];
    $refTableKey = ucfirst($nameFilter->filter($refTable));
    $pos = $row['ordinal_position'] - 1;
    if (!isset($foreignKeys[$refTable])) {
      $refTableClass = $tableClassPrefix . $refTableKey;
      $foreignKeys[$refTableKey] = array(
        'columns'       => array(),
        'refTableClass' => $refTableClass,
        'refColumns'    => array()
      );
    }
    $foreignKeys[$refTableKey]['columns'][$pos] = $row['column_name'];
    $foreignKeys[$refTableKey]['refColumns'][$pos] = $row['referenced_column_name'];
  }
  return $foreignKeys;
}

function getPrimaryKeys($table,$options) {
  extract($options);
  $primaryKeys = $infoSchema->fetchCol("
  SELECT    K.column_name,
            K.ordinal_position

  FROM      key_column_usage K 
    JOIN    columns C USING (table_schema,table_name,column_name)

  WHERE     table_schema=? AND table_name=? and constraint_name='PRIMARY' 

  ORDER BY  2
  ",array($schema,$table));
  return $primaryKeys;
}

function isSequence($table, $col, $options) {
  extract($options);
  $extra = $infoSchema->fetchCol("
    SELECT extra
    FROM   columns
    WHERE  table_schema=? AND table_name=? and column_name=?
  ",array($schema,$table,$col));
  return strtolower($extra[0]) == "auto_increment";
}

function getOrderKeys($table,$options) {
  extract($options);
  $rows = $infoSchema->fetchAll("
  SELECT   index_name,
           column_name,
           seq_in_index,
           collation
  FROM     statistics 
  WHERE    table_schema=? AND table_name=? AND index_name=?
  ORDER BY index_name,seq_in_index
  ",array($schema,$table,'ORDER'));
  $orderKeys = array();
  if (count($rows)>0) {
    $indexName = strtoupper($rows[0]['index_name']);
    foreach ($rows as $row) {
      if (strtoupper($row['index_name']) != $indexName) {
        break;
      }
      $orderKeys[] = $row['column_name'] . ($row['collation'] == 'D' ? " DESC" : "");
    }
  }
  return $orderKeys;
}

function emitClass($className, $baseClass, $content, $force = false) {
  $ds = DIRECTORY_SEPARATOR;
  $baseClassPath = str_replace("_",$ds,$baseClass) . ".php";
  $parts = explode("_",$className);
  $mainClass = array_pop($parts);
  $subpackage = implode("_",$parts);
  $description = "An " . strtolower(implode(" ",$parts)) . " class.";
  array_pop($parts);
  $package = implode("_",$parts);
  $category = "Hmd";
  $autoGenNote = $force ? 
    ("\n//---[ THIS FILE WAS AUTO-GENERATED AT " .  gmdate("Y-m-d\TH:i:s\Z") . " ]") : "";
  $header = <<<EOF
<?php$autoGenNote
/**
 * HowManyDead.org
 *
 * LICENSE
 *
 * This source file is subject to the Eclipse Public License that is bundled
 * with this package in the file public/LICENSE.html. It is also available on 
 * the web at http://howmanydead.org/LICENSE.html.
 *
 * If you did not receive a copy of the license and are unable to obtain it 
 * online, please send an email to license@howmanydead.org so we can send you 
 * a copy immediately.
 *
 * @category   $category
 * @package    $package
 * @subpackage $subpackage
 * @copyright  Copyright (c) 2010 Robert F. Lyons (rfl@howmanydead.org)
 * @license    http://howmanydead.org/LICENSE.html Eclipse Public License 
 * @version    \$Id\$
 */

/**
 * @see $baseClass
 */
require_once '$baseClassPath';

/**
 * $className - $description
 *
 * @category   $category
 * @package    $package
 * @subpackage $subpackage
 */
class $className extends $baseClass {

EOF;
  $fileName = APPLICATION_PATH . "/classes/" . str_replace("_",$ds,$className) . ".php";
  if (file_exists($fileName) && !$force) {
    $content = getClassContent($fileName);
  }
  $filePath = dirname($fileName);
  if (!file_exists($filePath)) {
    mkdir($filePath, 0755, true);
  }
  $fh = fopen($fileName, "w");
  fwrite($fh,"$header\n$content\n}\n");
  fclose($fh);
  print "$subpackage - $mainClass\n";
}

function getClassContent($fileName) {
  $fh = fopen($fileName, "r");
  $capture = false;
  $content = "";
  while (!feof($fh)) {
    if ($line = fgets($fh,8192)) {
      if ($capture) {
        $content .= $line;
        continue;
      }
      if (substr(trim($line),0,6) == "class ") {
        $content = preg_replace("/^class [^{]+{?\s*/","",$line);
        $capture = true;
      }
    }
  }
  fclose($fh);
  $content = preg_replace("/^\s*{/","",$content);
  $content = preg_replace("/^\s+/","  ",$content);
  $content = preg_replace("/\s*}\s*$/","\n",$content);
  return $content;
}

