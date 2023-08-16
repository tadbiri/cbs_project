<?php

/**
 * CONSIDER IT THAT NOT ANY CACHE FILE NAME CONTAIN COMMA :) 
 */

require_once "/cbshome/cdr_analysis/app/cdr_db_inserter/common/config.php";
define('CONCURRENT_LOG_FILE_COUNT_TO_PROCESS', 3);

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
    //$files = scandir($path);
    $files = shell_exec("find $path -name \"*.unl\" -type f -cmin -60  -printf \"%f\n\" ");
    $files = explode("\n", $files);
    //print_r($files);
    //echo "\n\n\n\n";
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
 * @param string directory path of temp files.
 * 
 * @return array tempFiles
 */
function updateIndex($files, $directory){
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
        if(file_exists($directory.$fileNameWithoutExtension.".ndx")){ 
            $storedSetting = explode(",", file($directory.$fileNameWithoutExtension.".ndx")[0]);

            
            // Check that process is finished.
            $storedFinishProcessFlag = (int) $storedSetting[4];
            if(!$storedFinishProcessFlag){
                $result[] = null;
                echo "Notic: The insert process on file '$fileNameWithoutExtension' not finished yet. \n";
                continue;
            }

            // Check that file changed.
            $storedLastLineIndex = (int) $storedSetting[3];
            if($storedLastLineIndex == $currentSetting[3]){
                $result[] = null;
                echo "Notic: The size of file '$fileNameWithoutExtension' not changed yet. \n";
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
        $fp = fopen($directory.$fileNameWithoutExtension.".ndx", 'wr');
        fwrite($fp, TypeConvertorHelper::arrayToCSV($currentSetting));
        fclose($fp);

        /**
         * Make a new temp file.
         * In case that old file exist, delete old file.
         */
        $currentReadedLineIndex = (int) $currentSetting[2];
        $currentLastLineIndex = (int) $currentSetting[3];

        $tempFilePath = $directory.$fileNameWithoutExtension.".tmp";
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

/**
 * Directory address that holds log files,
 * Database feeded from this directory to make data.
 */
//define('FEED_LOG_DIR', "/home/cbshome/failedcdr_analysis/data");
define('FEED_LOG_DIR', "/cbshome/cdr_analysis/data/cbs/cbp/rec");


$cityList = getDirList(FEED_LOG_DIR);
while(true) {
    foreach($cityList as $city){
        //print_r($city);
        //echo "\n";
        $dateDirList = getDirList($city);
        foreach($dateDirList as $dateDir){
            //print_r($dateDir);
            //echo "\n";
            //$dateDir = FEED_LOG_DIR;
            $fileList = getFilelist($dateDir);

            /**
             * Control sub-directories that must be needed in each directory.
             */
            $tempFileDirectoryPath = $city."/"."temp-files/";
            $paramFileDirectoryPath = $city."/"."param-files/";
            $logFileDirectoryPath = $city."/"."logs/";
            //echo "tempFileDirectoryPath: $tempFileDirectoryPath\n";
            //echo "paramFileDirectoryPath: $paramFileDirectoryPath\n";
            //echo "logFileDirectoryPath: $logFileDirectoryPath\n";

            clearstatcache();
            if(!is_dir($tempFileDirectoryPath)){
                mkdir($tempFileDirectoryPath);
            }
            if(!is_dir($paramFileDirectoryPath)){
                mkdir($paramFileDirectoryPath);
            }
            if(!is_dir($logFileDirectoryPath)){
                mkdir($logFileDirectoryPath);
            }


            /**
             * break files to chunks to implement concurrent processing.
             * For example if CONCURRENT_LOG_FILE_COUNT_TO_PROCESS config set 30,
             * 30 files processed with together by a builder script.
             * If 300 file exist the 30 builder script run concurrently at a same time.
             * To decrease ConcurrentlyDegree increase CONCURRENT_LOG_FILE_COUNT_TO_PROCESS config value.
             */
            $fileListChunked = array_chunk($fileList, CONCURRENT_LOG_FILE_COUNT_TO_PROCESS);
            foreach($fileListChunked as $fileList){
                //print_r($fileList);
                //echo "\n";
                // Make indexes and temp, more detail inside the function.
                $files = updateIndex($fileList, $tempFileDirectoryPath);

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
                $paramFilePath = $paramFileDirectoryPath.$paramFileName.".prm";
                $logFilePath = $logFileDirectoryPath.$paramFileName.".log";
                //echo "paramFileName: $paramFileName\n";
                //echo "paramFilePath: $paramFilePath\n";
                //echo "logFilePath: $logFilePath\n";

                $fp = fopen($paramFilePath, 'w');
                fwrite($fp, $filesWithoutExtensionCSV);
                fclose($fp);
        
                // Make command to run. 
                $command = "nohup php ".__DIR__."/cbs_cbp_rec_cell_inserter.php $paramFilePath 1>>$logFilePath 2>&1 & echo $!";
                echo $command."\n";
                // Run
                $pid = (int) exec($command);
                echo "PID: $pid \n";
            }
//            
        }
    }
sleep(1);
}