<?php


require_once dirname(__DIR__, 1) ."/common/libs/trafficanalytic.php";

require_once dirname(__DIR__, 1)."/config/localconfig.php";


require_once FullCommonPath."/amchart/chart/cachechart.php";
require_once FullCommonPath."/amchart/chart/charthelperfunctions.php";

$Builds = [
    totalSuccessCBSSEEVoiceMOMT::class,
    //totalSuccessCBSCBPData::class,
    //totalCAPSCBSCBPAVoice::class,
    //totalCAPSCBSCBPAData::class,
    //totalCAPSCBSCBPASMS::class,
];

while (true) {
    // Iterate on each chart.
    foreach ($Builds as $chartName) {
        // Ge instance of analytic service.
        $traficAnalytic = new TrafficAnalytic($chartName);

        // Logs
        ChartHelperFunctions::logPrinter($chartName . " Start.");

        $chartname = strtolower($chartName);

        $cacheFiles = [];
        $StartFileIndex = null;

        // Check that file maked to store cache.
        $path = dirname(__DIR__, 1) . "/cachepool/analytics/" . $chartname . ".cbsc";
        if (!file_exists($path)) {
            /**
             * In case that not any file created,
             * Load all cache files to make report.
             * 
             * Create file.
             */
            $cacheFiles = CacheChart::getCacheFileList(strtolower($chartName));

            mkdir(dirname(__DIR__, 1) . "/cachepool/analytics/");
            file_put_contents($path, "", FILE_APPEND);
        } else {
            /**
             * In case that file is exist,
             * Get last point of file and fetch next file index and cache file name to 
             * Load new data. 
             */

            // Get last line.
            $data = file($path);
            $line = $data[count($data) - 1];
            $lastTimestamp = explode(',', $line)[0];

            // Set file to fetch data.
            $cacheFile = "$chartname" . "-" . ChartHelperFunctions::getDate_Ymd_ByTimestamp($lastTimestamp) . ".cbsc";
            $cacheFiles[] = $cacheFile;

            // Set last line index.
            $lastLineIndex = CacheChart::getFileLineIndexByTimestamp($lastTimestamp);
            $StartFileIndex = $lastLineIndex + 1;
        }

        foreach ($cacheFiles as $cacheFile) {
            $fileLines = file(CacheChart::getFullPath($cacheFile));

            $indexes = CacheChart::getLineIndexes($cacheFile);

            if ($StartFileIndex == null) {
                $startFileIndex = $indexes->startFileIndex;
            } else {
                $startFileIndex = $StartFileIndex;
            }

            if ($startFileIndex > $indexes->endFileIndex) {
                //ChartHelperFunctions::logPrinter($chartName . " Not found anything to build.");
                sleep(1);
                continue;
            }

            //ChartHelperFunctions::logPrinter($chartName . " Load $cacheFile for fetch data.");

            //ChartHelperFunctions::logPrinter($chartName . " Indexes: $startFileIndex to $indexes->endFileIndex");

            // Open file handler.
            $fp = fopen($path, 'a');

            for ($i = $startFileIndex; $i <= $indexes->endFileIndex; $i++) {
                $fileLine = $fileLines[$i];
                $fileLinePart = explode(',', $fileLine);
                $filestartTimestamp = $fileLinePart[FILEINDEX_TIMESTAMP_INDEX];
                $filestartDatetime = date('Y-m-d H:i:s', $filestartTimestamp);
                $entities = $traficAnalytic->getCurrentPercent($filestartTimestamp);

                // Scape empty lines.
                if (count($entities) == 0) {
                    //print_r($entities);
                    //ChartHelperFunctions::logPrinter($chartName . "F1");
                    continue;
                }
                if (count($entities[0]->list) == 0) {
                    echo $fileLine."\n";
                    echo "$cacheFile \n";
                    echo "$filestartTimestamp \n";
                    echo "F2 \n\n\n";
                    continue;
                }

                /**
                 * List empty or just one point to calc.
                 * For one point standardDeviation can not be calculated.
                 */
                if ($entities[0]->standardDeviation == 0) {
                    echo $fileLine."\n";
                    echo "$cacheFile \n";
                    echo "$filestartTimestamp \n";
                    echo "F3 \n\n\n";
                    
                    continue;
                }

                // Make final line.
                $line =  $filestartTimestamp . ",";
                $line .= $filestartDatetime . ",";

                // Make entity section.
                $currentPoints = array_slice($fileLinePart, ENTITY_START_INDEX);
                foreach ($entities as $j => $entity) {
                    $line .= $entity->entityName . ":";
                    $line .= (ENTITY_KEY_IS_EXIST) ? trim(explode(':', $currentPoints[$j])[1]) . "<>" : trim($currentPoints[$j]) . "<>";
                    $line .= $entity->standardDeviation . "<>";

                    foreach ($entity->list as $item) {
                        $line .= $item[1] . "'";
                    }
                    $line = rtrim($line, "'");
                    $line .= ",";
                }
                $line = rtrim($line, ",");
                $line .= "\n";
                // Write line in file.
                fwrite($fp, $line);
            }
            fclose($fp);
        }
    }
}