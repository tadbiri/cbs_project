<?php

class Main{
    public function index(){
        header('Content-Type: application/json');
        header("HTTP/1.1 200 OK");
        echo json_encode(['message'=>'Hallo Welt :)']);
    }
}