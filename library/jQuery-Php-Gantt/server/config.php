<?php

/*****************************************
 * these are configs you can edit
 * to suite your needs
 *****************************************/

$alive_timeout = 60; // seconds

// here is the login configs
// for example, to login into the first project,
// the credentials would be:
// - project: project1
// - password: mypassword
$logins = array(
    'project1' =>
        array(
            'password' => 'mypassword',
            'title' => 'My first project',
            'emails' => array(
                'manager@youwebsite.com',
                'worker@youwebsite.com'
            )
        ),
    'project2' =>
        array(
            'password' => 'mypassword2',
            'title' => 'My second project',
            'emails' => array(
                'manager@youwebsite.com',
                'worker@youwebsite.com'
            )
        )
);


/*****************************************
 * functions part
 * do not edit
 *****************************************/

// Suppress DateTime warnings
// provided by R.Cerceau, cerceau@gmail.com, thank you!
date_default_timezone_set(@date_default_timezone_get());

session_start();

$alive_now = date('U');
$debug = false;

function alive_file()
{
    return 'data/' . $_SESSION['project_name'] . '_alive.php';
}

function alive_file_value($name)
{
    global $_alive;
    if (isset($_alive[$name]) and $_alive[$name]) {
        return $_alive[$name];
    }
}

function alive_file_delete()
{
    if (is_file(alive_file())) {
        unlink(alive_file());
    }
}

function session_value($name)
{
    global $_SESSION;
    if (isset($_SESSION[$name]) and $_SESSION[$name]) {
        return $_SESSION[$name];
    }
}

function dbug($v)
{
    global $debug;
    if ($debug) {
        echo '<pre style="background:orange">';
        print_r($v);
        echo '</pre>';
    }
}

?>