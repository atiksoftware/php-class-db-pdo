# PHP PDO Connector Library

PDOModel Class provides set of functions for interacting database using PDO extension.
You don't need to write any query to perform insert, update, delete and select operations(CRUD operations).
You need to call these functions with appropriate parameters and these functions will perform required 
Database operations. 

---

### Installation
```sh
composer require atiksoftware/php-class-db-pdo
```

### Usage example
```php
include "../vendor/autoload.php";
use Atiksoftware\Database\PDOModel;

$pdomodel = new PDOModel();
$pdomodel->connect("localhost", "pdomodel_user", "J]GgvNr9UCUT", "pdomodel");
```


### Database Connection
```php
// Connect to mysql database
$pdomodel->connect("localhost", "pdomodel_user", "J]GgvNr9UCUT", "pdomodel");//connect to database
$pdomodel->connect("localhost", "root", "", "pdocrud" ,"mysql","utf8");//connect to database       

// Connect to pgsql database
$pdomodel->connect("localhost", "root", "", "pdocrud","pgsql");//connect to database
$pdomodel->connect("localhost", "root", "", "pdocrud" ,"pgsql","utf8");//connect to database    

// Connect to sqlite database
$pdomodel->connect("", "", "", "path-of-sqlite-file","sqlite");//connect to database
// another way
$pdomodel->dbSQLitePath = "path-of-sqlite-file";
$pdomodel->connect("", "", "", "" ,"sqlite","");//connect to database   
```


### Select
```php
//Example 1 - (query generated -> SELECT `empId`,`firstName`,`lastName` FROM `emp` )
//Specify column names
$pdomodel->columns = array("empId", "firstName", "lastName");
$result =  $pdomodel->select("emp");
 
//Example 2 - (query generated -> SELECT * FROM `emp` WHERE `age`>= ? )
//Specify where condition
$pdomodel->where("age",30,">=");
$result =  $pdomodel->select("emp");
 
//Example 3 - (query generated -> SELECT * FROM `emp` WHERE `status`= ? AND `age`>= ? AND ( `firstName`= ? OR `firstName`= ? )  )
// Example of use of multiple "and" and "or" with brackets
$pdomodel->where("status", 1);
$pdomodel->where("age",30,">=");
$pdomodel->openBrackets = "(";
$pdomodel->where("firstName", 'John');
$pdomodel->andOrOperator = "OR";
$pdomodel->where("firstName", 'bob');
$pdomodel->closedBrackets = ")";
$result =  $pdomodel->select("emp");
 
//Example 4 - (query generated ->SELECT * FROM `emp` GROUP BY `age` ORDER BY `firstName` LIMIT 0,5  )
//groupby, order by and limit example
$pdomodel->groupByCols = array("age");
$pdomodel->orderByCols = array("firstName");
$pdomodel->limit = "0,5";
$result =  $pdomodel->select("emp");
 
//Example 5 - (query generated ->SELECT * FROM `emp` WHERE `firstName`LIKE ? AND `age` BETWEEN ? AND ? AND `empId`IN (?,?) ORDER BY `firstName` LIMIT 0,5 )
// LIKE, BETWEEN, IN, NOT IN example
$pdomodel->andOrOperator = "AND";
$pdomodel->where("firstName", "Jo%", "LIKE");
$pdomodel->where("age", array(10,50), "BETWEEN");
$pdomodel->where("empId", array(30,50), "IN");
$pdomodel->limit = "0,5";
$result =  $pdomodel->select("emp");
 
//Example 6 - (query generated ->SELECT count(*), concat(firstname, ' ' , lastname) AS `fullname` FROM `emp` ORDER BY `firstName` )
// Aggregate functions like count example
$pdomodel->columns = array("count(*), concat(firstName, ' ' , lastName) as fullName");
$result =  $pdomodel->select("emp");
 
//Example 7 - (query generated ->SELECT * FROM `emp` GROUP BY `firstName` HAVING sum(age)>10 ORDER BY `firstName` )
$pdomodel->groupByCols = array("firstName");
$pdomodel->havingCondition = array("sum(age)>10");
$result =  $pdomodel->select("emp");
 
//Example 8 - (query generated ->SELECT * FROM `wp_postmeta` WHERE `post_id` IN (select post_id from wp_posts where post_id>?) )
//use of subquery with where condition 
$pdomodel->where_subquery("post_id", "select post_id from wp_posts where post_id>?", "IN", array(1));
$result =  $pdomodel->select("wp_postmeta");
 
//Example 9 - (query generated ->SELECT * FROM `emp` ORDER BY `firstName` LIMIT 0,3 )
$pdomodel->fetchType = "OBJ";
$pdomodel->limit = "0,3";
$result =  $pdomodel->select("emp");
```


### Insert 
```php
$pdomodel->insert("order", array("orderNumber"=>1001, "customerName"=>"John Cena", "address"=>"140 B South Jercy");
//Example 1
$insertData = array("orderNumber"=>1001, "customerName"=>"John Cena", "address"=>"140 B South Jercy");
$pdomodel->insert("order", $insertData);
 
//Example 2
$insertEmpData["firstName"] = "Simon"; 
$insertEmpData["lastName"] = "jason";
$pdomodel->insert("emp", $insertEmpData);
```


### Insert On Duplicate
```php
//Example 1
$insertData = array("id"=> "1068","first_name" => "bob", "last_name" => "builder");
$updateData  = array("first_name" => "boboo", "last_name" => " the builder");
$pdomodel->insertOnDuplicateUpdate("employee", $insertData, $updateData);
```


### Update
```php
$pdomodel = new PDOModel(); //create object of the PDOModel class
$pdomodel->connect("localhost", "pdomodel_user", "J]GgvNr9UCUT", "pdomodel");//connect to database
/* Update function */
$pdomodel->where("orderId", 7);//setting where condition
$pdomodel->update("order", array("orderNumber"=>"44", "customerName"=>"BKG", "address"=>"140 shakti nagar"));

//Example 1
$updateData = array("orderNumber"=>1001, "customerName"=>"John Cena", "address"=>"140 B South Jercy");
$pdomodel->where("orderId", 7);
$pdomodel->update("order", $updateData);
 
//Example 2
$updateEmpData["firstName"] = "Simon"; 
$updateEmpData["lastName"] = "jason";
$pdomodel->where("empId", 40);
$pdomodel->update("emp", $updateEmpData);
```


### Delete
```php
$pdomodel = new PDOModel(); //create object of the PDOModel class
$pdomodel->connect("localhost", "pdomodel_user", "J]GgvNr9UCUT", "pdomodel");//connect to database
/* Delete function */
$pdomodel->where("orderId", 7);//setting where condition
$pdomodel->delete("order");

//Example 1
$pdomodel->where("orderId", 7);
$pdomodel->delete("order");
 
//Example 2
$pdomodel->where("firstName", 'john');
$pdomodel->where("lastName", 'johny');
$pdomodel->delete("emp");
```


### Join
```php
/* Join function */
$pdomodel->joinTables("wp_postmeta", "wp_posts.ID = wp_postmeta.post_id","INNER JOIN");//specify join
$result =  $pdomodel->select("wp_posts");//call select query

//Example 1 
$pdomodel->columns = array("wp_posts.id", "post_date", "post_title");
$pdomodel->joinTables("wp_postmeta", "wp_posts.ID = wp_postmeta.post_id","INNER JOIN");
$pdomodel->limit = "0,3";
$result =  $pdomodel->select("wp_posts");
 
//Example 2
$pdomodel->columns = array("p.id", "post_date", "post_title");
$pdomodel->where("post_title", "P%", "LIKE");
$pdomodel->where("post_date", array('2016-03-01','2016-03-10'), "BETWEEN");
$pdomodel->joinTables("wp_postmeta as pm", "p.ID = pm.post_id","LEFT OUTER JOIN");
$pdomodel->limit = "0,3";
$result =  $pdomodel->select("wp_posts as p");
```


### Execute Query
```php
/* Execute Query function */
$records = $pdomodel->executeQuery("select * from `emp`"); 

//Example 1
$result =  $pdomodel->executeQuery("select * from emp where empId = ?", array(39));
```


### TABLE
```php

```


### GGGGGGGGGGGGGGGGGGGGG
```php
# Get All Database
/* Delete function */
$pdomodel->where("orderId", 7);//setting where condition
$pdomodel->delete("order");
//Example 1
$pdomodel->where("orderId", 7);
$pdomodel->delete("order");
 
//Example 2
$pdomodel->where("firstName", 'john');
$pdomodel->where("lastName", 'johny');
$pdomodel->delete("emp");

# Drop Table
$pdomodel->dropTable("order_meta");  

# Rename Table
$pdomodel->renameTable("country", "countryTable");   

# Primary key of a table
$primaryKey = $pdomodel->primaryKey("wp_posts");

# Truncate table
$pdomodel->truncateTable("order_meta"); 
```


### Get Columns of a table
```php
$columnNames = $pdomodel->columnNames("wp_posts");     
```


### Transactions Function
```php
/* PDO Transaction block */
$pdomodel->dbTransaction = true;//start transactions
$insertData = array("orderNumber"=>909, "customerName"=>"one", "address"=>"140 shakti nagar");
$pdomodel->insert("orderTable", $insertData);
$insertData = array("orderNumber"=>90099, "customerName"=>"two", "address"=>"140 shakti nagar");
$pdomodel->insert("orderTable", $insertData);
$pdomodel->commitTransaction();//commit transaction   
```


### Sub Query
```php
/* Sub query function */
$pdomodel->subQuery("select post_id from wp_postmeta where meta_id=?","postmeta", array(20));
$result =  $pdomodel->select("wp_posts");  

//Example 1 
//subquery with where and order by etc.
$pdomodel->subQuery("select post_id from wp_postmeta where meta_id=?","postmeta", array(20));
$pdomodel->where("p.id", 10, ">=");
$pdomodel->orderByCols = array("p.id");
$result =  $pdomodel->select("wp_posts as p")
```


### Batch
```php
# INSERT
/* Insert function */
$pdomodel->insertBatch("emp", array(array("firstName" => "John", "lastName" => "Jonathan"), array("firstName" => "Simon", "lastName" => "Kane")));

//Example 2
$insertData = array(array("firstName" => "Johnq", "lastName" => "Jonathan"), array("firstName" => "Michal", "lastName" => "Kane"));
$pdomodel->insertBatch("emp", $insertData);
 
//Example 3
$insertEmpData[0]["firstName"] = "Nike"; 
$insertEmpData[0]["lastName"] = "jason";
$insertEmpData[0]["age"] = 25;
$insertEmpData[1]["firstName"] = "Rasaol"; 
$insertEmpData[1]["lastName"] = "jason";
$insertEmpData[1]["age"] = 25;
$pdomodel->insertBatch("emp", $insertEmpData);

# UPDATE
/* Update function */
$updateBatchData = array(array("orderNumber" => "78", "customerName" => "BKG", "address" => "140 shakti nagar"), array("orderNumber" => "99", "customerName" => "BKG", "address" => "140 shakti nagar"));
$where = array(array("orderId", 7), array("orderId", 8));
$pdomodel->updateBatch("orderTable", $updateBatchData, $where);

//Example 1
$updateBatchData = array(array("orderNumber" => "78", "customerName" => "BKG", "address" => "140 shakti nagar"), array("orderNumber" => "99", "customerName" => "BKG", "address" => "140 shakti nagar"));
$where = array(array("orderId", 7), array("orderId", 8));
$pdomodel->updateBatch("orderTable", $updateBatchData, $where);

# DELETE
//Example 1
$where = array(array("empId",70), array("empId",71));
$pdomodel->deleteBatch("emp",$where);
```


### Export 
```php
# CSV Export
/* array to csv function */
$data = array( array("row1col1","row1col2","row1col3","row1col4"),array("row2col1","row2col2","row2col3","row2col4"));
$pdomodel->arrayToCSV($data);

//Example 1 
$records = $pdomodel->select("emp"); //get data from table
$pdomodel->arrayToCSV($records, "emp.csv");//export it to csv


# PDF Export
/* array to pdf function */
$data = array( array("row1col1","row1col2","row1col3","row1col4"),array("row2col1","row2col2","row2col3","row2col4"));
$pdomodel->arrayToPDF($data);

//Example 1 
$records = $pdomodel->select("emp"); //get data from table
$pdomodel->arrayToPDF($records, "emp.pdf");//export it to pdf


# Excel Export 
/* array to excel function */
$data = array( array("row1col1","row1col2","row1col3","row1col4"),array("row2col1","row2col2","row2col3","row2col4"));
$pdomodel->arrayToExcel($data);

//Example 1 
$records = $pdomodel->select("emp"); //get data from table
$pdomodel->arrayToExcel($records, "emp.xlsx");//export it to excel


# HTML Export 
/* array to html function */
$data = array( array("row1col1","row1col2","row1col3","row1col4"),array("row2col1","row2col2","row2col3","row2col4"));
$pdomodel->arrayToHTML($data);
echo $pdomodel->outputHTML; // echo output html  

//Example 1 
$records = $pdomodel->select("emp"); //get data from table
$pdomodel->arrayToHTML($records, "emp.html");//export it to html
echo $pdomodel->outputHTML; // read output html


# XML Export 
/* array to xml function */
$data = array( array("row1col1","row1col2","row1col3","row1col4"),array("row2col1","row2col2","row2col3","row2col4"));
$pdomodel->arrayToXML($data);

//Example 1 
$records = $pdomodel->select("emp"); //get data from table
$pdomodel->arrayToXML($records, "emp.xml");//export it to xml

# JSON Export
/* array to json function */
$data = array( array("row1col1","row1col2","row1col3","row1col4"),array("row2col1","row2col2","row2col3","row2col4"));
$pdomodel->arrayToJson($data); 

//Example 1 
$records = $pdomodel->select("emp"); //get data from table
$pdomodel->arrayToJson($records);//export it to json
```


### Import
```php
# CSV Import
/* csv to array function */
$records = $pdomodel->csvToArray("emp.csv");

//Example 2 
$records = $pdomodel->csvToArray("emp.csv");
$pdomodel->insertBatch("emp", $records);

# Excel Import
/* excel to array function */
$records = $pdomodel->excelToArray("emp.xls");

//Example 2
$records = $pdomodel->excelToArray("emp.xls");
$pdomodel->insertBatch("emp", $records);

# XML Import
/* xml to array function */
$pdomodel->isFile=true;
$records = $pdomodel->xmlToArray("emp.xml");

//Example 2 
$pdomodel->isFile=true;
$records = $pdomodel->xmlToArray("emp.xml");
$pdomodel->insertBatch("emp", $records);
```


### Pagination 
```php
function pagination($page = 1, $totalrecords, $limit = 10, $adjacents = 1);

echo $pdomodel->pagination( 1, 125, 10, 1);

//Example 1
 echo $pdomodel->pagination( 1, 125, 10);
```




