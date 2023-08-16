<?php

class Query
{
    public function index()
    {

        // Set header.
        header('Content-Type: application/json');
        header("HTTP/1.1 200 OK");

        // Get payload of request.
        $payload = json_decode(file_get_contents('php://input'));


        // Check that requested target exist.
        $target = "";
        if(!isset($payload->targets[0]->target)){

            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['error' => 'Bad Request']);
        
            // Log.
            APILog::error("error: 400 ( Method: /query, 'target' property not set!)");
            exit;
        }

        // Check that requested target exist in app.
        $target = $payload->targets[0]->target;        
        $target_path = CACHE_POOL_DIR.$target; 
        if(!is_dir($target_path)){

            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['error' => 'Bad Request']);
        
            // Log.
            APILog::error("error: 400 ( Method: /query, requested target: '".$target." Not exist in app!')");
            exit;
        }


        // Check that entities exist.
        if(!isset($payload->targets[0]->payload->entities)){

            header("HTTP/1.1 400 Bad Request");
            echo json_encode(['error' => 'Bad Request']);
        
            // Log.
            APILog::error("error: 400 ( Method: /query, 'entities' property not set!)");
            exit;
        }
        
        // In case that entity is __ALL__ fetch all of them.
        $foundedEntities = [];
        if($payload->targets[0]->payload->entities == '__ALL__'){
            $foundedEntities = CacheChart::fetchEntityFromCacheFiles($target);
            if(empty($foundedEntities)){
            
                header("HTTP/1.1 400 Bad Request");
                echo json_encode(['error' => 'Bad Request']);
                
                // Log.
                APILog::error("error: 400 ( Method: /query, Cache is empty)");
                exit;
            }
        }else{
            $foundedEntities[] = $payload->targets[0]->payload->entities;
        }

        // Final result.
        $result = [];
        foreach($foundedEntities as $foundedEntity){
            $result[] = [
                'target' => $foundedEntity,
                'datapoints' => [],
            ];
        }

        /**
         * Calculate period.
         * Get requested period times.
         * Set TIME_SHIFT on it.
         * Ignore second from period.
         */
        $timestampFrom = strtotime(HelperFunctions::ISO8601ToDatetime($payload->range->from));
        $timestampTo = strtotime(HelperFunctions::ISO8601ToDatetime($payload->range->to));

        //$timestampFrom = $timestampFrom - TIMESTAMP_SHIFT;
        //$timestampTo = $timestampTo - TIMESTAMP_SHIFT;

        $timestampFrom = HelperFunctions::ignoreSecondInTimestamp($timestampFrom);
        $timestampTo = HelperFunctions::ignoreSecondInTimestamp($timestampTo);


        /**
         * This number show count of minute. 
         */
        $periodBasedMinute = ($timestampTo - $timestampFrom) / 60;

        /**
         * Get all cache file that matched with requested period.
         */
        $dateFrom = date('Y-m-d', $timestampFrom);
        $dateTo = date('Y-m-d', $timestampTo);

        //echo "dateFrom: ".date('Y-m-d H:i:s', $timestampFrom)."\n";
        //echo "dateTo: ".date('Y-m-d H:i:s', $timestampTo)."\n";

        $founedFiles = CacheChart::getCacheFileList($target, $dateFrom, $dateTo);

        // Iterate on each minute.
        for ($i = 1; $i <= $periodBasedMinute; $i++) {
            
            // Calculate current timestamp.
            $currentTimestamp = ($timestampFrom + ($i * 60));
            
            // Get cache file name for this timestamp.
            $curentFileName = $target."-".date('Ymd', $currentTimestamp).".cbsc";
            
            $currentLine = "";
            // Check that is exist file.
            if(in_array($curentFileName, $founedFiles)){

                // Get line index for current timestamp 
                $lineIndex = CacheChart::getFileLineIndexByTimestamp($currentTimestamp);

                // Get current line. 
                $currentCacheFilePath = $target_path."/".$curentFileName;
                $currentCacheFileLines = file($currentCacheFilePath);
                $currentLine = $currentCacheFileLines[$lineIndex];
            }
            
            
            // Iterate on entities.
            for ($j = 0; $j < count($result); $j++) {

                // Consider null as none-data value.
                $value = null;
                
                if($currentLine != ""){
                    
                    // Get entities. 
                    $cacheList = explode(',', $currentLine);
                    $entities = array_slice($cacheList, ENTITY_START_INDEX);
                    
                    // In case that not entity found that mean is none-value.
                    if(!empty($entities)){

                        // Get entity value based current entity.
                        $entityValue = explode(":", $entities[$j])[1];
                        $entityValue = trim(preg_replace('/\s\s+/', ' ', $entityValue));

                        // Ignore NaN.
                        if($entityValue != 'NaN'){
                            $value = (int) $entityValue;
                        }
                    }
                }

                $result[$j]['datapoints'][] = [
                    $value, 
                    $currentTimestamp*1000, // To convert to milisecond. 
                ];
            }

        }

        echo json_encode($result);
    }
}