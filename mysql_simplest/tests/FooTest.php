<?php
require_once dirname(__FILE__) . "/../src/Foo.php";

class FooTest extends \PHPUnit_Extensions_Database_TestCase {
    //一定要實做的兩個抽像 getConnection(), getDataSet()

    // PHPUnit_Extensions_Database_TestCase 的抽像
    // 所以一定要實做
    // 建立暫時資料庫的連接
    public function getConnection() {

        // 因為
        //      PHPUnit_Extensions_Database_defaultTester::_construct() 必需實作
        //      介面 -> PHPUnit_Extensions_Database_DB_IDatabaseConnection
        // 所以
        //      使用 createDefaultDBConnection()。
        //
        // 因為 (用來建立與臨時的資料庫)
        //      createDefaultDBConnection() 第一個參數必需是 PDO 的實作
        //      第二個參數是資料表名稱
        // 所以
        //      先建立變數pdo
        //      這裡必需要有 帳密為nimo 的mysql用戶，並且已存在test_db資料庫
        //      並且裡面已有資料表 test_table 格式為：
        //          sn      int(11)         AUTO_INCREMENT
        //          name    varchar(50)
        //          age     int(11)
        //      (你可以自己修改)
        $dbname = "test_db";
        $dsn = "mysql:host=localhost;dbname={$dbname}";
        $account = "nimo";
        $password = "nimo";
        $pdo = new PDO($dsn, $account, $password);
        return $this->createDefaultDBConnection($pdo, $dbname);
    }

    // PHPUnit_Extensions_Database_TestCase 的抽像
    // 所以一定要實做
    // 建立暫時資料庫的初始值
    public function getDataSet() {
        //  must implement interface
        // 因為
        //      PHPUnit_Extensions_Database_AbstractTester::setDataSet()必需實作
        //      介面 -> PHPUnit_Extensions_Database_DataSet_IDataSet
        // 所以
        //      使用 createFlatXMLDataSet()。
        return $this->createXMLDataSet(__DIR__ . '/_files/testSeedData.xml'); // Seed 表示種子(初始)

    }

    // 至少要有一個測試
    public function testSimple() {
        // 為了讓範例看起來很簡單，這邊直接復製了一遍上面的連接方式
        $dbname = "test_db";
        $dsn = "mysql:host=localhost;dbname={$dbname}";
        $account = "nimo";
        $password = "nimo";
        $pdo = new PDO($dsn, $account, $password);
        $db = $this->createDefaultDBConnection($pdo, $dbname);

        $foo = new Foo($pdo);

        // 資料庫初始(見testSeedData.xml)有兩筆資料，這邊插入一筆，所有共有三筆
        $foo->insert(array('name'=>'dong', 'age'=>'23'));

        // 用 createQueryTable() 取出資料庫現在的資料
        $actual = $db->createQueryTable('test_table', 'SELECT * FROM test_table');

        // 拿出先準備好的資料表 ( 他應該要與加完一筆資料後的資料表一樣 )
        $data_set = $this->createXMLDataSet(__DIR__ . '/_files/testExpectedData.xml');
        $expected = $data_set->getTable('test_table');

        $this->assertTablesEqual($expected, $actual);
    }


}
