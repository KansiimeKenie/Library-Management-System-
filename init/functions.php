<?php
require('init/app_start.php');
function load_page($url = '')
{
    global $sqlConnect, $global_var, $db, $page;
    $page_file = './layout/' . $url . '.phtml';
    if (!file_exists($page_file)) {
        $page_file  = './layout/404.phtml';
    }
    $content = '';
    ob_start();
    require($page_file);
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
//getting system configration data
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

function secure_data($string)
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
    $table = secure_data($table, 0);

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
    global $global_var, $db;
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
    global $global_var, $sqlConnect;
    $ext = pathinfo($_FILES[$name]['name'], PATHINFO_EXTENSION);
    $filename = md5(time() . $_FILES[$name]['name']) . '.' . $ext;

    if (move_uploaded_file($_FILES[$name]['tmp_name'], $destination . $filename)) {
        return $destination . $filename;
    }
    return '';
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
