<?php
$availModule = ['disburse', 'all-data'];
$module = $_GET['module'] ?? "disburse";

switch ($module){
    case "all-data": include "all-data.php";
    break;
    default: include "disburse.php"; 
    break;
}