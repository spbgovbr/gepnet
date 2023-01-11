<?php

include('../../codebase/connector/db_sqlite3.php');
include('../../codebase/connector/gantt_connector.php');

// SQLite
$dbtype = "SQLite3";
$res = new SQLite3(dirname(__FILE__) . "/samples.sqlite");
$res->busyTimeout(1000);
/*$connection = null;

function conexao(){
    $con_string = "host=10.10.10.10 port=1010 dbname=xxxx user=xxxxa password=xxxx";
    print_r(pg_connect($con_string));
    $connection = pg_connect($con_string);
}
print_r($connection);
$dbtype = "Postgre";
$res = new PostgreDBDataWrapper($connection);
print_r($res);*/


// Mysql
// $dbtype = "MySQL";
// $res=mysql_connect("192.168.1.251", "gantt", "gantt");
// mysql_select_db("gantttest");

?>