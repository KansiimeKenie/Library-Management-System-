<?php
session_start();
require_once('config.php');

require_once('init/libraries/DB/vendor/autoload.php');

$global_var =  $config  = array();
$sqlConnect   = $global_var['sqlConnect'] = null;

// Connect to SQL Server
$sqlConnect   = $global_var['sqlConnect'] = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, 3306);

// Handling Server Errors 
$ServerErrors = array();
if (mysqli_connect_errno()) {
    $ServerErrors[] = "Failed to connect to MySQL: " . mysqli_connect_error();
}
if (!function_exists('curl_init')) {
    $ServerErrors[] = "PHP CURL is NOT installed on your web server !";
}

$query = mysqli_query($sqlConnect, "SET NAMES utf8mb4");
if (isset($ServerErrors) && !empty($ServerErrors)) {
    foreach ($ServerErrors as $Error) {
        echo "<h3>" . $Error . "</h3>";
    }
    die();
}

$config              = config();
$db                  = new MysqliDb($sqlConnect);


// Get LoggedIn User Data
$global_var['loggedin']           = false;
$global_var['user'] = array();

if (logged_in() == true) {
    $session_id  = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $global_var['user'] = mysqli_fetch_array(mysqli_query($sqlConnect, "SELECT * FROM users WHERE id = '$session_id'"));

    $global_var['loggedin'] = true;
}


$config['site_url']  = $site_url;
$global_var['site_url']  = $site_url;
$global_var['config']    = $config;
