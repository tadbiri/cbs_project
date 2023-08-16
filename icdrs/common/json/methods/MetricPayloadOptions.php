<?php

class MetricPayloadOptions{
    public function index(){

        header('Content-Type: application/json');
        header("HTTP/1.1 200 OK");
 
        echo json_encode([
            ["label"=> "CPUUtilization", "value"=> "CPUUtilization"],
            ["label"=> "CPUUtilization", "value"=> "CPUUtilization"],
            ["label"=> "CPUUtilization", "value"=> "CPUUtilization"],
        ]);          
    }
}