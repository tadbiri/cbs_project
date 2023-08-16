<?php
/**
 * Run SQL query on database.
 * 
 * @param string $query query
 * @param array $items items of query.
 * @Param bool $just_simple_array_first_filed 
 * 
 * @return stdClass {status: show status of execution, 
 * 					 id: inserted id, 
 * 					 result: result of query};
 */
function query($query, $items=array(),$just_simple_array_first_filed=false){
	$result = new stdClass();
	$result->status = false;
	$result->id = null;
	$result->result = null;

	// Set connection to database.
	$config['db']['host'] = "10.15.90.203";
	$config['db']['username'] = "root";
	$config['db']['password'] = "1qaz@WSX";
	$config['db']['dbName'] = "mci_kpi_db";
	$key = "pgsql:host=" . $config['db']['host'] . ";port=5432;dbname=" . $config['db']['dbName'];
	$db = new PDO($key, $config['db']['username'], $config['db']['password'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
	
	// To detect exception.
	$exception = null;
	
	// Run query and detect error in it.
	$statement = $db->prepare($query);
	try{
		$statement->execute($items);
	}catch(Exception $_exception){
		$exception = $_exception;
	}

	// Get error code.
	$_errorCode = $statement->errorCode();
	if($_errorCode != "00000"){
		// If error happend add it to log.
		$logFilePath = dirname(__DIR__, 1)."/app-logs/database-errors.log";
		$logFile = fopen($logFilePath, "a");
		$itemsCsv = TypeConvertorHelper::arrayToCSV($items);
		$date = date("Y-m-d H:i:s");
		$log = "DateTime: $date | ErrorCode: $_errorCode | Query: $query | items: $itemsCsv \n";
		fwrite($logFile, $log);
		fwrite($logFile, print_r($exception, true));
		fwrite($logFile, "\n\n\n");		
		fclose($logFile);

		return $result;
	}

	// Set status flag to true to show that action done success.
	$result->status = true;

    if(!$just_simple_array_first_filed){
        $result->result = $statement->fetchAll(2);
	}
    else{
		$result->result = $statement->fetch();
	}
	// Check that query start with select.
	if(strtoupper(explode(" ",$query)[0]) == "INSERT"){
		// In multi inserts inserted id is not defined.
		// Second statement detect guery for multi insert in postgres.
		if($statement->rowCount() == 1 && !strpos($query, "ON CONFLICT")){
			try{
				$result->id = $db->lastInsertId();
			}catch(Exception $e){}
		}
		/**
		 * In case that action is insert operation,
		 * The result must be null.
		 */
		$result->result = null;
	}

	return $result;
}