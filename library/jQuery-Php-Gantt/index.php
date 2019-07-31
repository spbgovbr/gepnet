<?php

require_once('server/config.php');

if (isset($_SESSION['signin']) and $_SESSION['signin']) {

    header('Location: gantt.html');

} else {

    header('Location: server/signin.php');

}

?>