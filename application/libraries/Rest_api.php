<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rest_api {

    public function __construct()
    {
        header("Content-Type: application/json;");
    }

    public function response($data)
    {
        if(is_array($data)){
            echo json_encode($data);
            return;
        }

        print_r($data);
    }

    public function success($data)
    {
        echo json_encode([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function fail($data)
    {
        echo json_encode([
            'success' => false,
            'data'    => $data
        ]);
    }
}