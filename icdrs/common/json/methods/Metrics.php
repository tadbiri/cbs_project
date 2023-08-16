<?php

class Metrics
{

    public function index()
    {

        header('Content-Type: application/json');
        header("HTTP/1.1 200 OK");

        // Get all cache dirs.
        $directories = glob(CACHE_POOL_DIR . '/*', GLOB_ONLYDIR);
        $directories = array_map(function ($directory) {
            return basename($directory);
        }, $directories);

        /**
         * Make metrics.
         * 
         */
        $metrics = [];
        foreach ($directories as $directory) {


            // Get a line and fetch options form it.
            $entities = CacheChart::fetchEntityFromCacheFiles($directory);

            /**
             * Add a default options.
             * Add another option that found.
             */
            $options = [
                [
                    "label" => 'ALL',
                    "value" => '__ALL__',
                ]
            ];
            foreach ($entities as $entity) {
                $options[] = [
                    "label" => explode(":", $entity)[0],
                    "value" => strtolower(explode(":", $entity)[0])
                ];
            }


            $metrics[] = [
                'label' => $directory,
                'value' => $directory,
                'payloads' => [
                    [
                        'name' => 'entities',
                        'label' => 'Entities',
                        'type' => 'select',
                        "width" => 40,
                        'placeholder' => 'please select',
                        'options' => $options,
                    ]
                ]
            ];
        }

        echo json_encode($metrics);
    }
}