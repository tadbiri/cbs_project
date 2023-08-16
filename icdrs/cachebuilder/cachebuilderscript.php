<?php
// Load libs.
require_once dirname(__DIR__, 1)."/config/localconfig.php";

require_once FullCommonPath."/amchart/chart/cachechart.php";
require_once dirname(__DIR__, 1)."/config/chartaveragetype.php";
require_once dirname(__DIR__, 1)."/config/chartaveragetypeconfig.php";
require_once FullCommonPath."/amchart/chart/charthelperfunctions.php";


// Get Cli params, chart names received as cli params.
$chartNames = array_slice($argv, 2, count($argv)-1);

// To hold instance of chart class.
$chartInstanceList = [];

// Load chart libs and get instance of chart class.
foreach($chartNames as $chartName){
    ChartHelperFunctions::logPrinter("Trying to load $chartName lib.");

    require_once dirname(__DIR__, 1)."/api/".$chartName.".php";
    $chartInstanceList[] = new $chartName();
}


// Iterate for always.
while(true){
    foreach($chartNames as $i => $chartName){
        // Get instance and run buildCache method.
        $chartClassObject = $chartInstanceList[$i];
        $chartClassObject->buildCache();
    }
}