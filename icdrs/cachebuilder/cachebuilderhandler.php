<?php
// Load libs.
require_once dirname(__DIR__, 1)."/config/localconfig.php";

require_once FullCommonPath."/amchart/chart/cachechart.php";
require_once FullCommonPath."/amchart/chart/charthelperfunctions.php";
require_once dirname(__DIR__, 1)."/config/chartaveragetype.php";
require_once dirname(__DIR__, 1)."/config/chartaveragetypeconfig.php";
require_once dirname(__DIR__, 1)."/config/groupcachebuilder.php";

// Get this directory path.
$thisDirectoryPath = dirname(__FILE__);
$trafficName = "cachebuilderscript.php";
// Get all configed chart.
$chartNames = array_keys($ChartAverageTypeConfig);
//print_r($chartNames);
//echo "\n\n";
 // Check for previous script in background.
 $previousPID = exec("ps -ef | grep \"$trafficName\"| grep -v grep | awk '{print $2}' | paste -s -d ' '");
 $previousPID = trim($previousPID);
 echo "previousPID: $previousPID \n";
 echo "\n\n";
 if(!empty($previousPID)){
    // It's run, so kill them.
    exec("kill -9 $previousPID");
    echo "Kill: $trafficName PID: $previousPID \n";
    echo "\n\n";
    
 }
 $rmPreviousFiles = $thisDirectoryPath."/CBSCache*";
    exec("rm -rf $rmPreviousFiles");
    echo "rmPreviousFiles: $rmPreviousFiles \n";
    echo "\n\n";
    
// Iterate on each pack to run concurrent.
foreach($ChartGroups as $chartNames){
    // Make param for run script.
    $param = "";
    $keyParam = "";
    $chartCount = 0;
     // Make log file for script result.
     $logFilePath = $thisDirectoryPath."/";
     foreach($chartNames as $chartName){
         $chartCount += 1;
         $param .= " ".$chartName;
         $keyParam .= $chartName;
         //$logFilePath = $chartName."-".$randNum;
     }
     //$logFilePath = rtrim($logFilePath, "-");
    $randNum = rand(100,999);
    $logFilePath = "CBSCache"."_".$chartCount."_".$randNum.".log";
    //$param = $trafficName."_".$chartCount."_".$randNum;
    //$keyParam = $trafficName."_".$chartCount."_".$randNum;
    
    //echo "$param \n";
    //echo "$keyParam \n";
    //echo "$logFilePath \n";
    
        

    // Make command to run. 
    $command = "nohup php $thisDirectoryPath/cachebuilderscript.php $keyParam $param 1>>$logFilePath 2>&1 & echo $!";
    echo "$command \n";
    
    // Run
    $pid = (int) exec($command);

    echo "Run script -> log:$logFilePath PID:$pid \n";
    echo "\n";
}