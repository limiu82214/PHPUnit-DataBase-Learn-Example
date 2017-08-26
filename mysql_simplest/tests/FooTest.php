<?php
require_once dirname(__FILE__) . "/../src/Foo.php";
require_once dirname(__FILE__) . "/_class/Nimo_Database_TestCase.php";

// getConnection 和 getDataSet 交由 Nimo_Database_TestCase實作，所以這邊只要繼承
//      Nimo_Database_TestCase 就可以了
class FooTest extends Nimo_Database_TestCase {

    static private $pdo = null;
    private $connection = null;

    // 把連結資料庫的參數從function分離出來
    private static $db_dbname   = "test_db";
    private static $db_dsn_type = "mysql";
    private static $db_dsn_host = "localhost";
    private static $db_account  = "nimo";
    private static $db_password = "nimo";

    public function getConnection() {
        $dsn = self::$db_dsn_type
            .":host="  . self::$db_dsn_host
            .";dbname=". self::$db_dbname
        ;
        // 設定登入資料庫資訊
        $this->SetLoginDatabaseInfo($dsn, self::$db_dbname, self::$db_account, self::$db_password);

        // 保存共用的pdo和connectioin
        $this->connection = parent::getConnection();
        self::$pdo = $this->GetPdo();

        return $this->connection;
    }

    protected function getDataSet() {
        return $this->createXMLDataSet(__DIR__ . '/_files/testSeedData.xml');
    }

    public function testSimple() {
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
