<?php

// Get this directory path.
$thisDirectoryPath = dirname(__FILE__);

$processName = "cbs_cbp_data_inserter";

 // Check for previous script in background.
 $previousPID = exec("ps -ef | grep \"$processName\"| grep -v grep | awk '{print $2}' | paste -s -d ' '");
 //$previousPID = trim($previousPID);
 //if(is_numeric($previousPID)){
     // It's run, so kill them.
     exec("kill -15 $previousPID");
     echo "Kill: $processName PID: $previousPID \n";

 