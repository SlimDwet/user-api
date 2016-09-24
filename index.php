<?php

// Include constants
if(file_exists('constants.php')) require_once 'constants.php';
    else die("Error while loading constants file. Please copy constants-sample.php to constants.php");

// Include AdoDB
require_once 'adodb5/adodb.inc.php';
require_once 'adodb5/adodb-exceptions.inc.php';

// Database connection
try {
    $db = ADONewConnection(DB_DRIVER);
    $db->debug = true; // For developpement
    $db->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
} catch(Exception $e) {
    die("Error while database connection");
}

// Include API classes
include_once 'Api.php';
include_once 'User.php';
$user = new User();
$api = new Api($user);

// Get the called method
if(isset($_GET['method']) && method_exists($api, $_GET['method'])) {
    $api->$_GET['method']();
} else exit("Invalid URL");
