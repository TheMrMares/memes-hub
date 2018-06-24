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
    //Submit
    function sAdd(){
        require('./fx/connection.php');

        $title = prepareField($connection,'add--title');
        $description = prepareField($connection,'add--description');
        if(empty($title)){
            $title = 'Leniwy autor nie wpisał tytułu.';
        }
        if(empty($description)){
            $description = 'Leniwy autor nie ustawił opisu';
        }
        $stmt = mysqli_prepare($connection, "INSERT INTO memes (user_id, title, description) VALUES(?,?,?)");
        mysqli_stmt_bind_param($stmt, 'iss', $_SESSION['uid'],$title, $description);
        mysqli_stmt_execute($stmt);
        $insertedId = mysqli_insert_id($connection);

        $structure = './uploads/memes/'.$_SESSION['uid'].'.'.$_SESSION['login'].'/'.$insertedId.'.'.$title.'/';

        if(!empty($_FILES['add--files'])) {   
            $path = $structure;
            $max_file_size = 5*1024*1024;

            foreach ($_FILES['add--files']['name'] as $f => $name) {
                if ($_FILES['add--files']['error'][$f] == 4) {
                        continue;
                }
                if ($_FILES['add--files']['error'][$f] == 0) {
                        if ($_FILES['add--files']['size'][$f] > $max_file_size) {
                            $ERROR_NEGATIVE = "Plik $name jest za duży!";
                            continue;
                        }
                        else{
                            if(!file_exists($structure)){
                                mkdir($structure, 0777, true);
                            }
                            move_uploaded_file($_FILES["add--files"]["tmp_name"][$f], $path.$name);
                            $ERROR_POSITIVE = 'Plik wysłany pomyślnie.';
                        }
                }
            }

            $fullpath = $path.$name;
            $stmt = mysqli_prepare($connection, "UPDATE memes SET path=? WHERE id=? ");
            mysqli_stmt_bind_param($stmt, 'ss', $fullpath, $insertedId);
            mysqli_stmt_execute($stmt);
            
        }
        if(!file_exists($path.$name)){
            $stmt = mysqli_prepare($connection, "DELETE FROM memes WHERE id=?");
            mysqli_stmt_bind_param($stmt, 's', $insertedId);
            mysqli_stmt_execute($stmt);
        }
        mysqli_close($connection);
    }
    function sLogout(){
        msStop();
        Header('location: index.php');
    }
    function sRegister(){
        require('./fx/connection.php');

        $login = prepareField($connection,'register--login');
        $password = prepareField($connection,'register--password');
        $repeat_password = prepareField($connection,'register--repeat_password');
        $email = prepareField($connection,'register--email');
        $repeat_email = prepareField($connection,'register--repeat_email');
        if($password != $repeat_password || $email != $repeat_email || strlen($login) < 6 || strlen($password) < 6){
            if($password != $repeat_password){
                $ERROR_NEGATIVE = 'Hasła się nie zgadzają. ';
            } else if($email != $repeat_email) {
                $ERROR_NEGATIVE = 'Emaile się nie zgadzają. ';
            } else if(strlen($login) < 6) {
                $ERROR_NEGATIVE = 'Login zbyt krótki, pownien mieć min 6 znaków. ';
            } else if(strlen($password) < 6) {
                $ERROR_NEGATIVE = 'Hasło za krótkie, powninno mieć min 6 znaków. ';
            } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $ERROR_NEGATIVE = 'Email nieprawidłowy. ';
            }
        } else {
            $stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE login=? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 's', $login);
            mysqli_stmt_execute($stmt);
            $isLogin = mysqli_num_rows(mysqli_stmt_get_result($stmt));

            $stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE email=? LIMIT 1");
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            $isEmail = mysqli_num_rows(mysqli_stmt_get_result($stmt));

            if($isLogin != 0){
                $ERROR_NEGATIVE = 'Użytkownik o tej nazwie jest już zarejestrowany. ';
            } else if($isEmail != 0){
                $ERROR_NEGATIVE = 'Ten email jest już w użyciu. ';
            } else {
                $activationString = mt_rand(10000,99999).time().$login.$email;
                $activationHash = password_hash($activationString, PASSWORD_BCRYPT, ['cost' => 12]);;
                $activationLink = 'http://localhost/memes-hub/verification.php?verify='.$activationHash.'&email='.$email;
                $mail_target = $email;
                $mail_topic = 'MEMES-HUB || Potwierdzenie uworzenia konta!';
                $mail_text = '
                Twoje konto na stronie MEMES-HUB zostało utworzone.

                == Aktywacja konta (kliknij link): '.$activationLink.'

                Wiadomośc wygenerowana automatycznie, nie odpowiadaj na nią.
                ';
                $mail_headers = 'Content-type:text/html; charset=UTF-8' . '\r\n';
                $tmp = mail($mail_target, $mail_topic, nl2br($mail_text), $mail_headers);
                if($tmp){
                    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 14]);
                    $stmt = mysqli_prepare($connection, "INSERT INTO users (login, password, email, activation_code) VALUES(?,?,?,?)");
                    mysqli_stmt_bind_param($stmt, 'ssss', $login,$hashedPassword,$email, $activationString);
                    mysqli_stmt_execute($stmt);
                    $ERROR_POSITIVE = 'Pomyślnie zarejestrowano. Aktywuj swoje konto za pomocą linku wysłanego na maila. ';
                } else {
                    $ERROR_NEGATIVE = 'Coś poszło nie tak, mail aktywacyjny nie mógł zostać wysłany więc konto nie zostało utworzone.';
                }
            }
        }
        mysqli_close($connection);
    }
    function sLogin(){
        require('./fx/connection.php');

        $login = prepareField($connection,'login--login');
        $password = prepareField($connection,'login--password');
        
        $stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE login=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $login);
        mysqli_stmt_execute($stmt);
        $query = mysqli_stmt_get_result($stmt);
        if($query && mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)) {
                $dbUid = $row['id'];
                $dbLogin = $row['login'];
                $dbPassword = $row['password'];
                $dbEmail = $row['email'];
                $dbActivated = $row['activated'];
            }
        }
        
        if(password_verify($password, $dbPassword)){
            if(!$dbActivated){
                $ERROR_NEGATIVE = 'Twoje konto nie zostało aktywowane!';
            } else {
                session_start();
                $_SESSION['uid'] = $dbUid;
                $_SESSION['login'] = $dbLogin;
                $_SESSION['password'] = $dbPassword;
                $_SESSION['email'] = $dbEmail;
            }
        }
        mysqli_close($connection);
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