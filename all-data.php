<?php

require_once "./CONFIG.PHP";
require_once "./database.php";
require_once "./curl-helper.php";

$curlHelper = new CurlHelper();
$database = new Database();

$allData = $database->getAll();

echo "<pre>";
print_r($allData);