<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once dirname(__FILE__)."/config.php";

/**
 * Get a path and return an array that hold directories.
 * 
 * @param string $path eg: /var/www/html/
 * 
 * @return array [www, cgi-bin]
 */
function getDirList($path){
    $_res = [];
    $files = scandir($path);
    foreach($files as $file){
        if($file == "." || $file == '..'){continue;}
        $fullPath = $path."/".$file;
        if(is_dir($fullPath)){
            $_res[] = $fullPath;
        }
    }
    return $_res;
}

/**
 * Get a path and return an array that hold files.
 * 
 * @param string $path eg: /var/www/html/
 * 
 * @return array [index.php, .htaccess]
 */
function getFilelist($path){
    $_res = [];
    $files = scandir($path);
    foreach($files as $file){
        if($file == "." || $file == '..'){continue;}
        $fullPath = $path."/".$file;
        if(is_file($fullPath) && explode(".", $fullPath)[count(explode(".", $fullPath))-1] == "unl"){
            $_res[] = $fullPath;
        }
    }
    return $_res;
}


/**
 * Get file name and remove file extension.
 * 
 * @param string $fileName eg: man-az-khodame-hayede.mp3
 * 
 * @return string eg: man-az-khodame-hayede
 */
function removeFileExtension($fileName){
    $fileNameParts = explode(".", $fileName);
    unset($fileNameParts[count($fileNameParts)-1]);
    $_fileName = "";
    foreach($fileNameParts as $fileNamePart){
        $_fileName .= $fileNamePart.".";
    }
    $_fileName = substr($_fileName, 0, -1);
    return $_fileName;
}

/**
 * Get a file full path and remove path.
 * 
 * @param string $file eg: /var/www/html/index.php
 * 
 * @return string eg: index.php
 */
function removePathFromFilePath($file){
    $fileParts = explode("/", $file);
    $fileName = $fileParts[count($fileParts)-1];
    return $fileName;
}

/**
 * Get an array that hold unl files, and manage index and other stuff about it.
 * 
 * @param array $files
 * 
 * @return array tempFiles
 */
function updateIndex($files){
    // To hold results.
    $result = [];

    // Iterate on files.
    foreach($files as $file){
        /**
         * Get pure file name.
         * To make related temp and index files.
         * These files have a related name with main file but with another 
         * File extension, for index file use .ndx and temp file use .tmp.
         */
        $fileName = removePathFromFilePath($file);
        $fileNameWithoutExtension = removeFileExtension($fileName);
        
        // Get file lines.
        $fileLineList = file($file);

        /**
         * $Currentsetting variable hold detail of file that calculated in current run.
         * 
         * 0 Datetime of last read.
         * 1 Last file size per byte.
         * 2 Last Readed line number.
         * 3 Last line count in file.
         * 4 Finish process flag.
         */
        clearstatcache();
        $currentSetting = [
            date('Y-m-d H:i:s'),
            filesize($file),
            0,
            count($fileLineList),
            0
        ];
        
        /**
         * Check that index file existed or not.
         */
        if(file_exists(FEED_LOG_DIR_TEMP.$fileNameWithoutExtension.".ndx")){ 
            $storedSetting = explode(",", file(FEED_LOG_DIR_TEMP.$fileNameWithoutExtension.".ndx")[0]);

            
            // Check that process is finished.
            $storedFinishProcessFlag = (int) $storedSetting[4];
            if(!$storedFinishProcessFlag){
                $result[] = null;
                //echo "Notic: The insert process on file '$fileNameWithoutExtension' not finished yet. \n";
                continue;
            }

            // Check that file changed.
            $storedLastLineIndex = (int) $storedSetting[3];
            if($storedLastLineIndex == $currentSetting[3]){
                $result[] = null;
                //echo "Notic: The size of file '$fileNameWithoutExtension' not changed yet. \n";
                continue;
            }

            /**
             * To update file setting.
             */
            clearstatcache();
            $currentSetting[0] = date('Y-m-d H:i:s');
            $currentSetting[1] = filesize($file);
            $currentSetting[2] = $storedLastLineIndex;
            $currentSetting[3] = count($fileLineList);
            $currentSetting[4] = 0;
        }

        // Write file setting in index file.
        $fp = fopen(FEED_LOG_DIR_TEMP.$fileNameWithoutExtension.".ndx", 'wr');
        fwrite($fp, TypeConvertorHelper::arrayToCSV($currentSetting));
        fclose($fp);

        /**
         * Make a new temp file.
         * In case that old file exist, delete old file.
         */
        $currentReadedLineIndex = (int) $currentSetting[2];
        $currentLastLineIndex = (int) $currentSetting[3];

        $tempFilePath = FEED_LOG_DIR_TEMP.$fileNameWithoutExtension.".tmp";
        if(file_exists($tempFilePath)){
            unlink($tempFilePath);
        }
        
        $startLineNumber = $currentReadedLineIndex+1;
        $endLineNumber = $currentLastLineIndex; 
        exec("cat $file | sed -n '".$startLineNumber.",".$endLineNumber."p' >> $tempFilePath");

        $result[] = $tempFilePath;
    }
    return $result;
}

/**
 * Just make a md5 hash 
 * It's stronger than normal md5 function.
 */
function makeMd5($salt){
    return md5(str_replace(".", "-", uniqid('', true)).$salt.microtime().rand());
}

//$cityList = getDirList(FEED_LOG_DIR);
//while(true) {
//    foreach($cityList as $city){
//        $dateDirList = getDirList($city);
//        foreach($dateDirList as $dateDir){
            $dateDir = FEED_LOG_DIR;
            $fileList = getFilelist($dateDir);

            /**
             * break files to chunks to implement concurrent processing.
             * For example if CONCURRENT_LOG_FILE_COUNT_TO_PROCESS config set 30,
             * 30 files processed with together by a builder script.
             * If 300 file exist the 30 builder script run concurrently at a same time.
             * To decrease ConcurrentlyDegree increase CONCURRENT_LOG_FILE_COUNT_TO_PROCESS config value.
             */
            $fileListChunked = array_chunk($fileList, CONCURRENT_LOG_FILE_COUNT_TO_PROCESS);
            foreach($fileListChunked as $fileList){

                // Make indexes and temp, more detail inside the function.
                $files = updateIndex($fileList);

                /**
                 * Remove null items.
                 * Files that not needed to process for some reason must be filtered in here.
                 * More detail inside updateIndex() method.
                 */
                $files = array_filter($files);

                /**
                 * Remove path and file extension from files
                 * And convert files to CSV to store in prm file.
                 * The rpm file hold params for builder script.
                 * This approach implemented to fix limition problem in set params for a script to in bash.
                 */
                $filesWithoutExtension = array_map(function($file){
                    $fileName = removePathFromFilePath($file);
                    return removeFileExtension($fileName);
                }, $files);
                $filesWithoutExtensionCSV = TypeConvertorHelper::arrayToCSV($filesWithoutExtension);

                /**
                 * Ignore in case that not any file need to process.
                 */
                if($filesWithoutExtensionCSV == ""){
                    continue;
                }

                /**
                 * Make param and log file for builder script.
                 */
                $paramFileName = makeMd5($filesWithoutExtensionCSV);
                $paramFilePath = dirname(__DIR__, 1)."/cbs-log-reader/param-files/".$paramFileName.".prm";
                $logFilePath = dirname(__DIR__, 1)."/cbs-log-reader/logs/".$paramFileName.".log";
                $fp = fopen($paramFilePath, 'w');
                fwrite($fp, $filesWithoutExtensionCSV);
                fclose($fp);
        
                // Make command to run. 
                $command = "nohup php ".dirname(__DIR__, 1)."/cbs-log-reader/builder.php $paramFilePath 1>>$logFilePath 2>&1 & echo $!";

                // Run
                $pid = (int) exec($command);
                echo "PID: $pid \n";
            }
//            
//        }
//    }
//}