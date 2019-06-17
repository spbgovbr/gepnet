<?php

require_once('config.php');

if (!session_value('signin')) {
    echo '{"ok":false,"errorMessages":["Please SIGN IN!"]}';
    exit();
}

//dbug($_POST);

$project_name = session_value('project_name') ? session_value('project_name') : 'not_logged_in';
$action = isset($_POST['action']) ? $_POST['action'] : $_GET['action'];
$file_path = 'data/';
$file_name = $project_name . '.php';
$file = $file_path . $file_name;
$bkp_file = str_replace('.php', date('Y-m-d H\hi U') . '.php', $file);
$lock = '<?php exit(); ?>';

switch ($action) {

    case 'alive':
        // defaults
        $alive = array('mode' => session_value('mode'), 'reload' => false);
        // get the global alive values
        if (is_file(alive_file())) {
            include(alive_file());
        } else {
            // tell readers users, that the edit user just quit
            if (session_value('mode') != 'edit') {
                $alive['take_control'] = 'yea!';
            }
        }
        // execute modes
        if (session_value('mode') == 'edit') {

            // edit mode
            // set global alive file
            file_put_contents(alive_file(), '<?php
			$_alive[\'save\'] = ' . (alive_file_value('save') ? (int)alive_file_value('save') : 0) . ';
			$_alive[\'refresh\'] = ' . $alive_now . ';
			?>');

        } else {

            // read mode
            // is there a new version?
            if (alive_file_value('save') > session_value('alive') - 10) {
                $alive['reload'] = true;
            }

        }
        // set current time
        $_SESSION['alive'] = $alive_now;
        // send to client
        echo json_encode($alive, JSON_NUMERIC_CHECK);
        exit();
        break;

    case 'notif':
        foreach ($_SESSION['emails'] as $email) {
            mail($email,
                $_SESSION['project_title'] . ': Planner update',
                "\r\n" . $_SESSION['project_title'] . "\r\n\r\nAn update to the planner was made:\r\n\r\n" .
                "http://www.yourGantt.com/?project=" . $_SESSION['project_name']
            );
        }
        break;

    case 'save':
        if (!isset($_POST['data'])) {
            echo '{"ok":false,"errorMessages":["No data to save"]}';
            exit();
        }
        if (session_value('mode') != 'edit') {
            echo '{"ok":false,"errorMessages":["You cannot save in read mode"]}';
            exit();
        }
        // save data
        $data = $_POST['data'];
        file_put_contents($file, $lock . $data);
        file_put_contents($bkp_file, $lock . $data);
        // save alive status
        file_put_contents(alive_file(), '<?php
		$_alive[\'save\'] = ' . $alive_now . ';
		$_alive[\'refresh\'] = ' . $alive_now . ';
		?>');
        // send to client
        echo '{"ok":true,"data":' . $data . '}';
        exit();
        break;

    case 'load':
        if (!is_file($file)) {
            echo '{"ok":true,"mode":"' . session_value('mode') . '","data":{"tasks":[],"selectedRow":0,"deletedTaskIds":[],"resources":[{"id":"tmp_1","name":"Resource 1"},{"id":"tmp_2","name":"Resource 2"},{"id":"tmp_3","name":"Resource 3"},{"id":"tmp_4","name":"Resource 4"},{"id":"tmp_5","name":"Resource 5"},{"id":"tmp_6","name":"Resource 6"},{"id":"tmp_7","name":"Resource 7"},{"id":"tmp_8","name":"Resource 8"},{"id":"tmp_9","name":"Resource 9"},{"id":"tmp_10","name":"Resource 10"}],"canWrite":true,"canWriteOnParent":true}}';
            exit();
        }
        //$data = '{"tasks":[{"id":-1,"name":"Gantt editor","code":"","level":0,"status":"STATUS_ACTIVE","start":1363060800000,"duration":21,"end":1365566399999,"startIsMilestone":true,"endIsMilestone":false,"collapsed":false,"assigs":[]},{"id":-2,"name":"coding","code":"","level":1,"status":"STATUS_ACTIVE","start":1363060800000,"duration":10,"end":1364270399999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[],"description":"","progress":0},{"id":-3,"name":"gant part","code":"","level":2,"status":"STATUS_ACTIVE","start":1363060800000,"duration":2,"end":1363233599999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[],"depends":""},{"id":-4,"name":"editor part","code":"","level":2,"status":"STATUS_SUSPENDED","start":1363233600000,"duration":4,"end":1363751999999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[],"depends":"3"},{"id":-5,"name":"testing","code":"","level":1,"status":"STATUS_SUSPENDED","start":1364875200000,"duration":6,"end":1365566399999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[],"depends":"2:5","description":"","progress":0},{"id":-6,"name":"test on safari","code":"","level":2,"status":"STATUS_SUSPENDED","start":1364875200000,"duration":2,"end":1365047999999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[],"depends":""},{"id":-7,"name":"test on ie","code":"","level":2,"status":"STATUS_SUSPENDED","start":1365048000000,"duration":3,"end":1365479999999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[],"depends":"6"},{"id":-8,"name":"test on chrome","code":"","level":2,"status":"STATUS_SUSPENDED","start":1365048000000,"duration":2,"end":1365220799999,"startIsMilestone":false,"endIsMilestone":false,"collapsed":false,"assigs":[],"depends":"6"}],"selectedRow":0,"deletedTaskIds":[],"resources":[{"id":"tmp_1","name":"Resource 1"},{"id":"tmp_2","name":"Resource 2"},{"id":"tmp_3","name":"Resource 3"},{"id":"tmp_4","name":"Resource 4"},{"id":"tmp_5","name":"Resource 5"},{"id":"tmp_6","name":"Resource 6"},{"id":"tmp_7","name":"Resource 7"},{"id":"tmp_8","name":"Resource 8"},{"id":"tmp_9","name":"Resource 9"},{"id":"tmp_10","name":"Resource 10"}],"roles":[{"id":"tmp_1","name":"Project Manager"},{"id":"tmp_2","name":"Worker"},{"id":"tmp_3","name":"Stakeholder/Customer"}],"canWrite":true,"canWriteOnParent":true}';
        $data = file_get_contents($file);
        // remove lock
        $data = str_replace($lock, '', $data);
        echo '{"ok":true,"mode":"' . session_value('mode') . '","data":' . $data . '}';
        exit();
        break;

    case 'export':
        // write to data
        $file_contents = $_POST['data'];
        $file_name = $project_name . '_planner_' . date('Y-m-d_h\hi') . '.js';
        // Set headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$file_name");
        header("Content-Type: application/js");
        header("Content-Transfer-Encoding: binary");
        echo $file_contents;
        exit();
        break;

}

?>