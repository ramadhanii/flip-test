<?php
define("DISBURSE_SERVICE", "http://localhost:8000/");

require_once "./curl-helper.php";

$curl = new CurlHelper(false);
$data = [
    "bank_code"      => "bni",
    "account_number" => "1234567890",
    "amount"         => "1000000",
    "remark"         => "test remark",
];
$response = $curl->do(DISBURSE_SERVICE, $data);
echo json_encode($response);