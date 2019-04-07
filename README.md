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


### Database Connection
```php

```




