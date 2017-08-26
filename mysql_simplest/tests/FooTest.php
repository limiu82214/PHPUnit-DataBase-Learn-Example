<?php
require_once dirname(__FILE__) . "/../src/Foo.php";

class FooTest extends \PHPUnit_Extensions_Database_TestCase {

    // 共享連結資料庫的pdo和connection，防止無意義的重覆連結資料庫。
    static private $pdo = null;
    private $connection = null;

    // 把連結資料庫的參數從function分離出來
    private static $db_dbname   = "test_db";
    private static $db_dsn_type = "mysql";
    private static $db_dsn_host = "localhost";
    private static $db_account  = "nimo";
    private static $db_password = "nimo";

    public function getConnection() {
        // 這裡參考了官方手冊的建立連線方式，減少重覆連結的次數
        if ($this->connection === null) {
            if (self::$pdo == null) {
                $dsn = self::$db_dsn_type
                    .":host="  . self::$db_dsn_host
                    .";dbname=". self::$db_dbname
                ;
                $account   = self::$db_account;
                $password  = self::$db_password;
                self::$pdo = new PDO( $dsn, $account, $password);
            }
            $this->connection = $this->createDefaultDBConnection(
                self::$pdo,
                self::$db_dbname
            );
        }
        return $this->connection;
    }

    public function getDataSet() {
        return $this->createXMLDataSet(__DIR__ . '/_files/testSeedData.xml');
    }

    public function testSimple() {
        // 直接使用已經建好的pdo
        $conn = $this->connection;
        $pdo = self::$pdo;

        $foo = new Foo($pdo);

        $foo->insert(array('name'=>'dong', 'age'=>'23'));

        $actual   = $conn->createQueryTable('test_table', 'SELECT * FROM test_table');
        $data_set = $this->createXMLDataSet(__DIR__ . '/_files/testExpectedData.xml');
        $expected = $data_set->getTable('test_table');

        $this->assertTablesEqual($expected, $actual);
    }


}
