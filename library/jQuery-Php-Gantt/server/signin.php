<?php

require_once('config.php');


//print_r($logins);
//print_r($_SESSION);
$msg = array();

// check for post
if (isset($_POST['username'])) {

    // get user credential
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';
    //utils::dump($username);
    //utils::dump($pass);

    // validate
    if ($username AND $pass
        AND isset($logins[$username])
        AND $logins[$username]['password'] == $pass
    ) {

        // SUCCESS LOGIN

        // set login
        $_SESSION['signin'] = true;
        $_SESSION['project_name'] = $username;
        $_SESSION['project_title'] = $logins[$username]['title'];
        $_SESSION['emails'] = $logins[$username]['emails'];

        // Wich mode?
        $set_mode = 'read';
        // first check if a global alive file exists, if not, we are in edit mode
        if (!is_file(alive_file())) {
            $set_mode = 'edit';
            dbug('no alive file found');
        } else {
            // not sure yet, does the file is active, or old?
            // load file
            dbug('alive file found');
            include(alive_file());
            dbug($_alive);
            if ($alive_now - alive_file_value('refresh') > $alive_timeout) {
                // file is OLD
                $set_mode = 'edit';
                alive_file_delete();
                dbug('alive file is old, deleting...');
            }
        }
        dbug(array('set_mode' => $set_mode));

        $_SESSION['mode'] = $set_mode;
        $_SESSION['alive'] = $alive_now;
        // redirect
        header('Location: ../gantt.html');

    } else {

        // wrong login
        $msg[] = 'Please Sign IN';

    }
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Gantt Planner</title>
</head>
<body>
<div style="text-align: center; margin-top: 20%;">

    <?php
    if (count($msg)) {
        ?>
        <div><?php echo implode('<hr/>', $msg) ?></div>
        <?php
    }
    ?>
    <h1>Please Sign IN</h1>
    <form action="signin.php" method="post">
        Project<br/>
        <input type="text" id="username" name="username"
               value="<?php echo isset($_GET['project']) ? $_GET['project'] : '' ?>"/>
        <br/>
        Pass<br/>
        <input type="password" id="password" name="password"/><br/>
        <input type="submit" value="Go"/>
    </form>
    <p>To login, use "<b>project1</b>" & password "<b>mypassword</b>"</p>
</div>
</body>
</html>
<?php

?>
