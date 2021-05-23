<?php
// set response content as json
header("content-type:application/json");

require_once "./CONFIG.php";
require_once "./database.php";
require_once "./curl-helper.php";

// collect request data
$bank_code      = strtolower($_POST['bank_code'] ?? "");
$account_number = strtolower($_POST['account_number'] ?? "");
$amount         = strtolower($_POST['amount'] ?? "");
$remark         = strtolower($_POST['remark'] ?? "");

// little bit validation
if(!in_array($bank_code, ["bni", "bca", "mandiri"])) error("Invalid bank code");
if(!preg_match("/(^[0-9]+$)/", $account_number)) error("Invalid account number");
if(!preg_match("/(^[0-9]+$)/", $amount)) error("Invalid amount.");
if($amount < 10000) error("Minimal amount is 10k");
if(!preg_match("/^\w.+$/", $remark)) error("Invalid remark.");


$curl = new CurlHelper();
$data = [
    "bank_code"      => $bank_code,
    "account_number" => $account_number,
    "amount"         => $amount,
    "remark"         => $remark,
];
$response = $curl->do(BASE_URL . DISBURSE_PATH, $data);

if(!is_array($response)) error("Failed while sending to third party.");

$db = new Database();
$db->saveOrUpdate($response);

success($response, $response['status'] ?? "UNKNOWN");


function error($message){
    $res = ["status" => false, "message" => $message];
    die(json_encode($res));
}

function success($data, $message = "Sedang diproses."){
    $res = ["status" => true, "message" => $message, "data" => $data];
    die(json_encode($res));
}