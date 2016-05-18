<?php
/* This file is part of a copyrighted work; it is distributed with NO WARRANTY.
 * See the file COPYRIGHT.html for more details.
 */
 
require_once("../shared/common.php");
require_once("../shared/global_constants.php");
require_once("../classes/Queryi.php");

/**
 * This class provides an interface for DB functions unique to OB installation
 * @author Fred LaPlante
 */

class InstallQuery extends Queryi
{
    public function __construct($dbConst) {
        $this->dbConst = $dbConst;
        //echo "in installQuery constructor: host=".$this->dbConst["host"]."; user=".$this->dbConst["username"]."; pw=".$this->dbConst["pwd"]."; db=".$this->dbConst["database"]."<br />\n";
        parent::__construct($this->dbConst);
    }

	public function getDbServerVersion () {
		$sql = $this->mkSQL("SELECT VERSION()");
//echo "sql={$sql}<br/>\n";
        $rslt = parent::select1($sql);
//print_r($rslt);
		//return $rslt['VERSION()'];
		return $rslt;
	}

  public function getSettings($tablePrfx) {
    if (!isset($tablePrfx)) $tablePrfx = $this->dbConst['database']; // needed because default parameters cannot be complex items - FL
    //  first try to determine if setting table even exists
    $sql = $this->mkSQL('SHOW TABLES LIKE %C ', $tablePrfx.'.settings');
    //echo "sql={$sql}<br/>\n";
    $rows = $this->select01($sql);

    if (!$rows || empty($rows) || !isset($rows)) {
      return 'NothingFound';
    }

    // since we have a table, fetch the contents
    $sql = $this->mkSQL('SELECT * FROM %C ', $tablePrfx.'.settings');
    //echo "sql={$sql}<br/>\n";
    $rows = $this->select($sql);

    if (!$rows || empty($rows) || !isset($rows)) {
      return 'NothingFound';
    }
//    $sql = $this->mkSQL('SELECT * FROM %C ', $tablePrfx.'.settings');
//echo "sql={$sql}<br/>\n";
//    return $this->select($sql);
  }

  public function getCurrentDatabaseVersion() {
		## versions 1.0+
    $dbName = $this->$dbConst['database']; // needed because default parameters cannot be complex items - FL
    $sql = $this->mkSQL('SELECT `value` FROM %i WHERE `name`=%Q', $dbName.'.settings','version');
    $row = $this->select01($sql);
		if ($row) {
			$version = $row['value'];
		} else {
			## versions < 1.0
    	$sql = $this->mkSQL('SELECT `version` FROM %i ', $dbName.'.settings');
    	$rslt = $this->select01($sql);
			$version = $rslt['version'];
		}
		return $version;
  }

  public function freshInstall($locale, $sampleDataRequired = false,
                        $version=OBIB_LATEST_DB_VERSION,
                        $dbName = DB_TABLENAME_PREFIX) {
    $rootDir = '../install/' . $version . '/sql';
    $localeDir = '../locale/' . $locale . '/sql/' . $version;
    $this->executeSqlFilesInDir($rootDir, $dbName);
    $this->executeSqlFilesInDir($localeDir . '/domain/', $dbName);
    if($sampleDataRequired) {
      $this->executeSqlFilesInDir($localeDir . '/sample/', $dbName);
    }
  }

  public function createDatabase() {
    $sql = "CREATE DATABASE IF NOT EXISTS ".$this->dbConst['database'];
echo $sql."<br>\n";
    $r = parent::act($sql);
    if ($r === true) {
      echo T("success, Database created");
      //$this->setGrants($dbName, $dbUser); // must be done by db admin person when user created.
    } else {
      return T("Error: Unable to create database").': '.$mysqli->connect_error;
    }
  }

	## ------------------------------------------------------------------------ ##
  protected function  setGrants($dbName, $dbUser) {
    $sql = "GRANT CREATE, INSERT, DROP, UPDATE ON $dbName TO $dbUser ";
echo $sql."<br>\n";
    $r = parent::act($sql);
echo $r."<br>\n";
//     if (strpos($r, 'Error')) {
       return "$r";
//    }
//    }
//    return "success";
  }

  protected function getCurrentLocale($tablePrfx = DB_TABLENAME_PREFIX) {
    $sql = $this->mkSQL('SELECT * FROM %I WHERE `name`=%Q', $tablePrfx.'.settings','locale');
    $row = $this->select01($sql);
		return $row['value'];
  }

  protected function renameTables($fromTablePrfx, $toTablePrfx = DB_TABLENAME_PREFIX) {
    $fromTableNames = $this->getTableNames($fromTablePrfx.'%');
    foreach($fromTableNames as $fromTableName) {
      $toTableName = str_replace($fromTablePrfx, $toTablePrfx, $fromTableName);
      $this->renameTable($fromTableName, $toTableName);
    }
  }

  protected function copyDatabase($fromDb, $toDb) {
    $sql = $this->mkSQL("SHOW TABLES FROM %I ", $fromDb);
    $rslt = $this->select($sql);
    //echo "copy ".$rslt->num_rows." tables from ".$fromDb." to ".$toDb." <br />\n";
    $renaming = true;
    while ($row = $rslt->fetch_array()) {
        $src = $fromDb.'.'.$row[0];
        $dst = $toDb.'.'.$row[0];
    
        if ($renaming){
              $this->dropTable($dst);
              $sql = "rename table ".$src." to ".$dst;
              if ($this->_act($sql) == 0){
                $renaming = false;
              }
        }
        if (!$renaming) {
            $this->dropTable($dst);
            $sql = $this->mkSQL("drop table if exists %i", $dst);
            $res = $this->act($sql);
            $sql = $this->mkSQL("create table %i as select * from %i", $dst, $src);
            $res = $this->act($sql);
            $this->dropTable($src);
        }
    }
  }

	## ------------------------------------------------------------------------ ##
  public function dropTable($tableName) {
    $sql = $this->mkSQL("drop table if exists %q ", $tableName);
    return $this->act($sql);
  }

  public function renameTable($fromTableName, $toTableName) {
      $this->dropTable($toTableName);
      $sql = "rename table ".$fromTableName." to ".$toTableName;
      return $this->act($sql);
  }

  public function getTableNames($pattern = "") {
    if($pattern == "") {
      $pattern = DB_TABLENAME_PREFIX.'%';
    }
    $sql = "show tables like '".$pattern."'";
    $rows = $this->act($sql, OBIB_NUM);

    $tablenames = array();
    foreach ($rows as $row) {
      $tablenames[] = $row[0];
    }
    return $tablenames;
  }
  
  public function executeSqlFilesInDir($dir, $dbName = "") {
    if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
        while (($filename = readdir($dh)) !== false) {
          if(preg_match('/\\.sql$/', $filename)) {
            $this->executeSqlFile($dir.'/'.$filename, $dbName);
          }
        }
        closedir($dh);
      }
    }
  }

  /**
   * Function to read through an sql file executing SQL only when ";" is encountered
   */
  function executeSqlFile($filename, $dbName = DB_TABLENAME_PREFIX) {
    $fp = fopen($filename, "r");
    # show error if file could not be opened
    if ($fp == false) {
      Fatal::error("Error reading file: ".H($filename));
    } else {
        //this code based rom here :
        //http://stackoverflow.com/questions/147821/loading-sql-files-from-within-php
      $sqlStmt = "";
      while (!feof ($fp)) {
        $line = fgets($fp);
        $line = trim($line);
        // Skip it if it's a comment
        if (substr($line, 0, 2) == '--' || $line == '')
            continue;

        $sqlStmt .= $line;
        
        if (substr($line, -1) == ';') {
          //replace table prefix, we're doing it here as the install script may
          //want to override the required prefix (eg. during upgrade / conversion 
          //process)
          $sql = str_replace("%prfx%",$dbName,$sqlStmt);
          $this->act($sql);
          $sqlStmt = "";
        }
      }
      fclose($fp);
    }
  } //executeSqlFile
}
