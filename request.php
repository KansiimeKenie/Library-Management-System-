<?php
require('init/functions.php');
date_default_timezone_set('Africa/Kampala');
$file = @$_GET['file'];
$action = @$_GET['action'];

$file = secure_data($file);
$action = secure_data($action);


$data = array();

if (file_exists('http/' . $file . '.php')) {
    include_once 'http/' . $file . '.php';
} else {
    $data = array(
        'status' => 404,
        'message' => 'Endpoint not reached'
    );
}

header('Content-Type: application/json');
echo json_encode($data);
