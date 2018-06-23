<?php
//Def
    DEFINE('DB_HOST', 'localhost');
    DEFINE('DB_USERNAME', 'root');
    DEFINE('DB_PASSWORD', '');
    DEFINE('DB_DATABASE', 'memes-hub');
//Fxs
    // -- Db connection
    function dbConnect(){
        $connection = mysqli_connect(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        if (!$connection) {
            die('Connection error occured: '.mysqli_connect_error());
        }
        mysqli_set_charset($connection, "utf8");
    }
    // -- Get form field
    function prepareField($conn, $fieldName){
        $field = $_POST[$fieldName];
        $field = strip_tags($field);
        $field = mysqli_real_escape_string($conn, $field);

        return $field;
    }
    // -- Session
    function msCheck(){
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
        if(!isset($_SESSION['login']) || !isset($_SESSION['email']) || !isset($_SESSION['password'])){
            return false;
        } else {
            return true;
        }
    }
    function msStart($name, $limit = 0, $path = '/', $domain = null, $secure = null){
        session_name($name . '_Session');
        $domain = isset($domain) ? $domain : isset($_SERVER['SERVER_NAME']);
        $https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);
        session_set_cookie_params($limit, $path, $domain, $secure, true);
        session_start();
    }
    function msStop(){
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
        $_SESSION = array();
        session_destroy();
    }

?>