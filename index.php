<?php

// Include constants
if(file_exists('constants.php')) require_once 'constants.php';
    else die("Error while loading constants file. Please copy constants-sample.php to constants.php");

// Include AdoDB
require_once 'adodb5/adodb.inc.php';
require_once 'adodb5/adodb-exceptions.inc.php';

// debug
function prd($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die;
}

// Database connection
try {
    $db = ADONewConnection(DB_DRIVER);
    // $db->debug = true; // For developpement
    $db->Connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $db->SetFetchMode(ADODB_FETCH_ASSOC);
} catch(Exception $e) {
    die("Error while database connection");
}

// Includes API classes
include_once 'src/Api.php';
include_once 'src/User.php';
$user = new \Slimdwet\User($db);
$api = new Api($db, $user);

// Get the called method
if(isset($_GET['method']) && method_exists($api, $_GET['method'])) {
    $api->$_GET['method']();
} else exit("Invalid URL");

// Close database connection
$db->Close();
