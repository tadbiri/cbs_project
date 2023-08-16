<?php
function query($query, $items=array(),$just_simple_array_first_filed=false){
	$config['db']['host'] = "10.15.90.203";
	$config['db']['username'] = "root";
	$config['db']['password'] = "CBScbs12#$%";
	$config['db']['dbName'] = "cbs_failedcdr_db";
	$key = "mysql:host=" . $config['db']['host'] . ";dbname=" . $config['db']['dbName'] . ";charset=utf8";
	$db = new PDO($key, $config['db']['username'], $config['db']['password']);
	//set names utf8
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
	$statement = $db->prepare("SET NAMES UTF8");
	$statement->execute(array());
	$statement = $db->prepare($query);
	$statement->execute($items);
	// Get error code.
	$_errorCode = $statement->errorCode();
	if($_errorCode != "00000"){
		// If error happend add it to log.
			//"ErrorCode: ".$_errorCode,
			//"Query: ".$query,
			//"Params: ***".serialize($items)."***",
	}
    if(!$just_simple_array_first_filed){
        $fetch_data = $statement->fetchAll(2);
	}
    else{
		$fetch_data = $statement->fetch();
	}
	// Check that query start with select.
	if(strtoupper(explode(" ",$query)[0]) == "INSERT"){
		return $db->lastInsertId();
	}else{
		return $fetch_data;
	}
}
