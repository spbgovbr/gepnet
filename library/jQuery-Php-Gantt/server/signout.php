<?php

require_once('config.php');

// check for logout
if (isset($_SESSION['mode']) and $_SESSION['mode'] == 'edit') {
    // delete alive file
    alive_file_delete();
}

$_SESSION = array();
session_destroy();

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Gantt Planner</title>
</head>
<body>
<div style="text-align: center; margin-top: 20%;">
    <h1>Goodbye</h1>
    <a href="signin.php">&lt; back</a>
</div>
</body>
</html>