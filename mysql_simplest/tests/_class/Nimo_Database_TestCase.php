<?php

class Nimo_Database_TestCase extends PHPUnit_Extensions_Database_TestCase{

    static private $pdo = null;
    private $connection = null;

    private $db_dsn;
    private $db_dbname;
    private $db_account = null;
    private $db_password = null;
    private $isSetDatabaseInfo = false;

    // 這裡的登入MySQL資訊由子類別代入，這裡只負責連結資料庫
    // 這樣的形式將形成Nimo_Database_TestCase的一個慣例
    public function SetLoginDatabaseInfo($db_dsn, $db_dbname, $db_account=null, $db_password=null) {
    	$this->db_dsn      = $db_dsn;
    	$this->db_dbname   = $db_dbname;
    	$this->db_account  = $db_account;
    	$this->db_password = $db_password;
    	$this->isSetDatabaseInfo = true;
    }

    public function GetPdo() {
    	return self::$pdo;
    }

    protected function getConnection() {
    	if (!$this->isSetDatabaseInfo) {
    		throw new Exception("should be use SetLoginDatabaseInfo before getConnection");
    	}
    	$db_dsn      = $this->db_dsn;
    	$db_dbname   = $this->db_dbname;
    	$db_account  = $this->db_account;
    	$db_password = $this->db_password;

        if ($this->connection === null) {
            if (self::$pdo == null) {
                $have_account = $this->isVarNull($db_account);
                $have_password = $this->isVarNull($db_password);
                if ($have_account AND $have_password) {
	                self::$pdo = new PDO( $db_dsn, $db_account, $db_password);
                } else {
	                self::$pdo = new PDO( $db_dsn );
                }
            }
            $this->connection = $this->createDefaultDBConnection(
                self::$pdo,
                $db_dbname
            );
        }
        return $this->connection;
    }

    private function isVarNull($var) {
    	return ($var === null);
    }

    protected function getDataSet() {}
}
