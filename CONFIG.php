<?php

if(!defined("WHITELIST_IPS")) define("WHITELIST_IPS", "::1,localhost,127.0.0.1"); // possible value file, mysql


// config for database
if(!defined("DATABASE_TYPE")) define("DATABASE_TYPE", "mysql"); // possible value file, mysql

if(!defined("DB_FILE_PATH")) define("DB_FILE_PATH",  "./databases/");

if(!defined("DB_HOST")) define("DB_HOST",           "127.0.0.1");
if(!defined("DB_PORT")) define("DB_PORT",           "3306");
if(!defined("DB_NAME")) define("DB_NAME",           "flip_dhani");
if(!defined("DB_USERNAME")) define("DB_USERNAME",   "root");
if(!defined("DB_PASSWORD")) define("DB_PASSWORD",   "");



if(!defined("DISBURSE_PATH")) define("DISBURSE_PATH",   "disburse");
if(!defined("SERVER_KEY")) define("SERVER_KEY",         "HyzioY7LP6ZoO7nTYKbG8O4ISkyWnX1JvAEVAhtWKZumooCzqp41");
if(!defined("BASE_URL")) define("BASE_URL",             "https://nextar.flip.id/");