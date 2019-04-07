<?php 

	include "../vendor/autoload.php";

	use Atiksoftware\Database\PDOModel;

	/* create object of class */
	$pdomodel = new PDOModel();
	
	/* Connect to the datbase */
	$pdomodel->connect("localhost", "pdomodel_user", "J]GgvNr9UCUT", "pdomodel");
	
	/* call PDOModel function */
	$result =  $pdomodel->select("emp");



	/** SELECT  */
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