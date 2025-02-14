<?php
/*ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);*/
header('Cache-Control: max-age=846000');

@ini_set('max_execution_time', 0);
@ini_set("memory_limit", "-1");
@set_time_limit(0);
session_start();
require_once('config.php');

require_once('init/libraries/DB/vendor/autoload.php');

$the_ken =  $config  = array();
$sqlConnect   = $the_ken['sqlConnect'] = null;

// Connect to SQL Server
$sqlConnect   = $the_ken['sqlConnect'] = mysqli_connect($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name, 3306);

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


$http_header           = 'http://';
if (!empty($_SERVER['HTTPS'])) {
    $http_header = 'https://';
}
$the_ken['actual_link']   = $http_header . $_SERVER['HTTP_HOST'] . urlencode($_SERVER['REQUEST_URI']);



// Get LoggedIn User Data
$the_ken['loggedin']           = false;
$the_ken['user'] = array();

if (logged_in() == true) {
    $session_id  = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $the_ken['user'] = mysqli_fetch_array(mysqli_query($sqlConnect, "SELECT * FROM users WHERE id = '$session_id'"));

    $the_ken['loggedin'] = true;
}

// Icons Virables
$error_icon   = '<i class="fa fa-exclamation-circle"></i> ';
$success_icon = '<i class="fa fa-check"></i> ';
//Let us record the unique visitor;
// record_visitor();


$config['site_url']  = $site_url;
$the_ken['site_url']  = $site_url;
$the_ken['config']    = $config;
