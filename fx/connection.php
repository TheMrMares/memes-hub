<?php
if(!defined('DB_HOST')){
    DEFINE('DB_HOST', 'localhost');
    DEFINE('DB_USERNAME', 'root');
    DEFINE('DB_PASSWORD', '');
    DEFINE('DB_DATABASE', 'memes-hub');
}

$connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (!$connection) {
    die('Connection error occured: '.mysqli_connect_error());
}
mysqli_set_charset($connection, "utf8");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>