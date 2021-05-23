<?php

require_once "./CONFIG.php";
require_once "./database.php";
require_once "./curl-helper.php";

while(true){
    doCheck();
    sleep(30);
}

function doCheck(){
    $curlHelper = new CurlHelper();
    $database = new Database();

    $allData = $database->getAllPending();

    foreach($allData as $data){
        $response = $curlHelper->do(BASE_URL . DISBURSE_PATH."/".$data['id'], [], "GET");
        if(!is_array($response)) continue;
        if($response['status'] == 'PENDING') continue;
        
        $database->saveOrUpdate($response);
        usleep(500);
    }

}