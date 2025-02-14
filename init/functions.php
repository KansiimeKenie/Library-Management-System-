<?php
require('init/app_start.php');
function load_page($url = '')
{
    global $sqlConnect, $the_ken, $db, $page;
    $pg = './layout/' . $url . '.phtml';
    if (!file_exists($pg)) {
        $pg = './layout/404.phtml';
    }
    $content = '';
    ob_start();
    require($pg);
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
function exists($table, $condition)
{
    global $sqlConnect;
    $g = mysqli_query($sqlConnect, "SELECT * FROM $table $condition");
    if (mysqli_num_rows($g) > 0) {
        return true;
    }
    return false;
}
function logged_in()
{
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        return true;
    } else if (isset($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
        return true;
    } else {
        return false;
    }
}

function config()
{
    global $sqlConnect;
    $data  = array();
    $query = mysqli_query($sqlConnect, "SELECT * FROM config");
    if (mysqli_num_rows($query)) {
        while ($fetched_data = mysqli_fetch_assoc($query)) {
            $data[$fetched_data['name']] = $fetched_data['value'];
        }
    }
    return $data;
}

function __secure($string)
{
    global $sqlConnect;
    $string = trim($string);
    $string = cleanString($string);
    $string = mysqli_real_escape_string($sqlConnect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = str_replace('&amp;#', '&#', $string);
    return $string;
}

function cleanString($string)
{
    return $string = preg_replace("/&#?[a-z0-9]+;/i", "", $string);
}

function save_data($table, $_data)
{
    global $sqlConnect;
    if (empty($_data)) {
        return false;
    }
    $fields                              = '`' . implode('`,`', array_keys($_data)) . '`';
    $data                                = '\'' . implode('\', \'', $_data) . '\'';
    $query                               = mysqli_query($sqlConnect, "INSERT INTO {$table} ({$fields})  VALUES ({$data})") or die(mysqli_error($sqlConnect));

    if ($query) {
        return true;
    } else {
        return false;
    }
}


function login($email, $password)
{
    global $sqlConnect;
    $query = mysqli_prepare($sqlConnect, "SELECT password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($query, "s", $email);
    mysqli_stmt_execute($query);
    $result = mysqli_stmt_get_result($query);

    if ($row = mysqli_fetch_assoc($result)) {

        if (password_verify($password, $row['password'])) {
            return true;
        }
    }

    return false;
}


function update_data($table, $data, $condition)
{
    global $sqlConnect;
    $table = __secure($table, 0);

    $update = array();
    foreach ($data as $field => $_data) {
        $update[] = '`' . $field . '` = \'' . $_data . '\'';
    }

    $impload   = implode(', ', $update);
    $query_one = " UPDATE $table SET {$impload} $condition ";
    $query1    = mysqli_query($sqlConnect, $query_one) or die(mysqli_error($sqlConnect));

    if ($query1) {
        return true;
    } else {
        return false;
    }
}

function LoadAdminPage($page_url = '')
{
    global $the_ken, $db;
    $page         = './admin/layout/' . $page_url . '.phtml';
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    return $page_content;
}
function getCurrentDate()
{
    date_default_timezone_set("Africa/Kampala");
    $date = date("Y-m-d H:i:s");
    return $date;
}

function share_file($name, $destination)
{
    global $the_ken, $sqlConnect;
    $ext = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
    $filename = md5(time() . $_FILES[$name]['name']) . '.' . $ext;

    if (move_uploaded_file($_FILES[$name]['tmp_name'], $destination . $filename)) {
        return $destination . $filename;
    }
    return '';
}

function random_string($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}
function short_text($text = "", $len = 100)
{
    if (empty($text) || !is_string($text) || !is_numeric($len) || $len < 1) {
        return "****";
    }
    if (strlen($text) > $len) {
        $text = mb_substr($text, 0, $len, "UTF-8") . "..";
    }
    return $text;
}

function slug($text)
{
    return preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', __secure($text))) . '-' . random_string(5);
}
function getCurrentDateOnly()
{
    date_default_timezone_set("Africa/Kampala");
    $date = date("Y-m-d");
    return $date;
}

function getBorrowedBookRemainingDays($returnDate)
{

    // Convert both dates to timestamps
    $currentTimestamp = strtotime(getCurrentDateOnly());
    $returnTimestamp = strtotime($returnDate);

    // Calculate the difference in seconds
    $secondsRemaining = $returnTimestamp - $currentTimestamp;

    // Convert seconds into days
    $daysRemaining = floor($secondsRemaining / (60 * 60 * 24));

    // Return the remaining days
    return $daysRemaining;
}
function record_visitor()
{
    global $db, $sqlConnect;
    // Get the visitor's IP address
    $ip = $_SERVER['REMOTE_ADDR'];
    // Get the visitor's user agent (browser)
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // Get the current date and time
    $datetime = date("Y-m-d H:i:s");

    $check = $db->where('ip', $ip)->getOne('visitors');
    if (empty($check)) {
        // Insert the visitor data into the database
        $sql = "INSERT INTO visitors (ip, user_agent, datetime) VALUES ('$ip', '$user_agent', '$datetime')";
        mysqli_query($sqlConnect, $sql);
        // Close the database connection
        //mysqli_close($sqlConnect);
    }
}
function seo($query = '')
{
    global $the_ken, $config;
    if ($the_ken['config']['seo'] == 1) {
        $query = preg_replace(array(
            '/^index\.php\?page=team-details&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=news_details&slug=([^\/]+)$/i',
            '/^index\.php\?page=service_details&slug=([^\/]+)$/i',
            '/^index\.php\?page=pages&slug=([^\/]+)$/i',
            '/^index\.php\?page=project_details&id=([A-Za-z0-9_]+)$/i',
            '/^index\.php\?page=team$/i',
            '/^index\.php\?page=products$/i',
            '/^index\.php\?page=about$/i',
            '/^index\.php\?page=services$/i',
            '/^index\.php\?page=contact$/i',
            '/^index\.php\?page=projects$/i',
            '/^index\.php\?page=oevents$/i',
            '/^index\.php\?page=pevents$/i',
            '/^index\.php\?page=uevents$/i',
            '/^index\.php\?page=gallery$/i',
            '/^index\.php\?page=investors/i',
            '/^index\.php\?page=careers/i',
            '/^index\.php\?page=404$/i',
            '/^index\.php\?page=news$/i',
            '/^index\.php\?page=index$/i',
        ), array(
            $config['site_url'] . 'staff/$1/',
            $config['site_url'] . 'news_details/$1/',
            $config['site_url'] . 'service/$1/',
            $config['site_url'] . 'pages/$1/',
            $config['site_url'] . 'project_details/$1/',
            $config['site_url'] . 'team/',
            $config['site_url'] . 'products/',
            $config['site_url'] . 'about/',
            $config['site_url'] . 'services/',
            $config['site_url'] . 'contact_us/',
            $config['site_url'] . 'works/',
            $config['site_url'] . 'oevents/',
            $config['site_url'] . 'pevents/',
            $config['site_url'] . 'uevents/',
            $config['site_url'] . 'gallery/',
            $config['site_url'] . 'investors/',
            $config['site_url'] . 'careers/',
            $config['site_url'] . '404/',
            $config['site_url'] . 'news/',
            $config['site_url'],
        ), $query);
    } else {
        $query = $config['site_url'] . $query;
    }
    return $query;
}
