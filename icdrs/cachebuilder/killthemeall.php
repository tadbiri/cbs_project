<?php
// Load libs.
require_once dirname(__DIR__, 1)."/config/localconfig.php";

require_once FullCommonPath."/amchart/chart/cachechart.php";
require_once FullCommonPath."/amchart/chart/charthelperfunctions.php";
require_once dirname(__DIR__, 1)."/config/chartaveragetype.php";
require_once dirname(__DIR__, 1)."/config/chartaveragetypeconfig.php";

// Get this directory path.
$thisDirectoryPath = dirname(__FILE__);

$trafficName1 = "cachebuilderscript.php";
//$trafficName2 = "CBSCBPData";
// Check for previous script in background.
 $previousPID = exec("ps -ef | grep \"$trafficName1\"| grep -v grep | awk '{print $2}' | paste -s -d ' '");
 //$previousPID = trim($previousPID);
 //if(is_numeric($previousPID)){
     // It's run, so kill them.
     exec("kill -9 $previousPID");
     echo "Kill: $trafficName1 PID: $previousPID \n";
 //$previousPID = exec("ps -ef | grep \"$trafficName2\"| grep -v grep | awk '{print $2}' | paste -s -d ' '");
 //$previousPID = trim($previousPID);
 //if(is_numeric($previousPID)){
     // It's run, so kill them.
     //exec("kill -9 $previousPID");
     //echo "Kill: $trafficName2 PID: $previousPID \n";
 //}
 $rmPreviousFiles = $thisDirectoryPath."/CBSCache*";
 exec("rm -rf $rmPreviousFiles");
 echo "rmPreviousFiles: $rmPreviousFiles \n";
 