<?php

$requestUrl = $_SERVER['REQUEST_URI'];

$requestUrlParts = explode("/", $requestUrl);

class REQUEST_METHOD
{
    public const POST = 'POST';
    public const GET = 'GET';
}

class APILog
{
    public static function error($line)
    {
        $dump_file = fopen(dirname(__FILE__, 1) . "/logs/request-error.log", "a");
        $line = date('Y-m-d H:i:s') . "=> " . $line .", PAYLOAD: " . file_get_contents('php://input') . "\n";
        fwrite($dump_file, $line);
        fclose($dump_file);
    }

    public static function log($line)
    {
        $dump_file = fopen(dirname(__FILE__, 1) . "/logs/dump.log", "a");
        $line = date('Y-m-d H:i:s') . "=> " . $line .", PAYLOAD: " . file_get_contents('php://input') . "\n";
        fwrite($dump_file, $line);
        fclose($dump_file);
    }
}

header('Content-Type: application/json');

$routes = [
    ['/', REQUEST_METHOD::GET],
    ['/metrics', REQUEST_METHOD::POST],
    ['/metric-payload-options', REQUEST_METHOD::POST],
    ['/query', REQUEST_METHOD::POST],
    ['/variable', REQUEST_METHOD::POST],
    ['/tag-keys', REQUEST_METHOD::POST],
    ['/tag-values', REQUEST_METHOD::POST],
];

// Check method found.
$routeIndex = array_search('/' . $requestUrlParts[4], array_column($routes, 0));

if (!is_numeric($routeIndex)) {

    header("HTTP/1.1 404 Not Found");
    echo json_encode(['error' => 'Not Found']);

    // Log.
    APILog::error("error: 404 ( Method: " . $requestUrlParts[4] . ")");
    exit;
}

// Check REQUEST_METHOD
if ($_SERVER['REQUEST_METHOD'] != $routes[$routeIndex][1]) {

    header("HTTP/1.1 400 Bad Request");
    echo json_encode(['error' => 'Bad Request']);

    // Log
    APILog::error("error: 400 ( Method: " . $_SERVER['REQUEST_METHOD'] . " Not allowed for this route)");
    exit;
}
 
// Change format of method name.
$methodName = $routes[$routeIndex][0];
$methodName = ($methodName == '/') ? '/main' : $methodName;
$methodName = str_replace(" ", '', ucwords(str_replace('-', ' ',  substr($methodName, 1))));

// Load the method file.
require_once dirname(__DIR__, 2) . "/config/localconfig.php";
require_once dirname(__DIR__, 1) . "/amchart/chart/jsonhelperfunctions.php";
require_once dirname(__DIR__, 1) . "/amchart/chart/cachechart.php";

$methodClassFilePath = dirname(__FILE__) . "/methods/" . $methodName . ".php";

if (!file_exists($methodClassFilePath)) {

    header("HTTP/1.1 500 Sever Error");
    echo json_encode(['error' => 'Error: Class file for requested method not found on server!']);


    // Log
    APILog::error("error: 500 ( Class file for requested method not found on server! )");
    exit;
}


require_once dirname(__FILE__) . "/methods/" . $methodName . ".php";

// Get an instance and execute main function.
if (!class_exists($methodName)) {

    header("HTTP/1.1 500 Sever Error");
    echo json_encode(['error' => 'Error: Class not implemented!']);

    // Log.
    APILog::error("error: 500 ( Class not implemented! )");
    exit;
}

$methodInstance = new $methodName();

if (!method_exists($methodInstance, 'index')) {

    header('Content-Type: application/json');
    header("HTTP/1.1 500 Sever Error");
    echo json_encode(['error' => "Error: Class implemented but 'index' method not defined!"]);

    // LOG
    APILog::error("error: 500 ( Class implemented but 'index' method not defined! )");

    exit;
}
$methodInstance->index();

// Dump logging.
APILog::log("method: " . $methodName);