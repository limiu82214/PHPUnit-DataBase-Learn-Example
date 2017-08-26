# PHPUnit MySQL Simplest Example

一個MySQL版本的極簡範例。
這個範例將會
1. 連結到真實資料庫中，用始用檔案(testSeedData.xml)初始化資料庫。
2. 執行程式(Foo.php)插入一筆資料到真實資料表中。
3. 從檔案(testExpectedData.xml)中提取出預期的資料表。
4. 比較真實資料表中的資料是否相同於預期的資料表。


### 結構

#### 目錄 src/
##### Foo.php
包含了一個單純的insert方法

#### 目錄 tests/
##### FooTest.php
對應Foo.php的測試範例，有三個方法。
* getConnection() 用於取得資料庫連線
* getDataSet() 用於建立資料庫初始資料
* testSimple() 簡單的範例

#### 目錄 tests/
##### testSeedData.xml
用來初始化真時資料庫。
##### testExpectedData.xml
用來比對執行完成式之後的真實資料庫，是不是等於預期的資料。

