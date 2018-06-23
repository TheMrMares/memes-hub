<!-- Server -->
<?php
    require_once('./fx/fx.php');
    //Login
    if(isset($_POST['login--submit'])){
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
    //Register
    if(isset($_POST['register--submit'])){
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
                $mail_target = 'For.Each.Mee@gmail.com';
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
?>
<!-- Client -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Memes Hub</title>
    <link rel="stylesheet" href="./css/main.css">
</head>
<body>
    <!-- Error bar -->
    <?php
        require('addon-error_bar.php');
    ?>
    <!-- Header -->
    <?php
        require('addon-header.php');
    ?>
    <!-- Main -->
        <section class="row main">
            <!-- Left spacer -->
            <div class="row">

            </div>
            <!-- Memes -->
            <div class="row">
                <div class="meme">
                    <div class="row meme_title">
                        <h1>Meme title</h1>
                    </div>
                    <div class="row meme_image">
                        <img src="./media/images/fluffy.jpg"/>
                    </div>
                    <div class="row meme_info">
                        <h2>Dodane przez: User</h2>
                        <h2>Data: 05.23.2018 r.</h2>
                    </div>
                </div>

                <div class="meme">
                    <div class="row meme_title">
                        <h1>Meme title</h1>
                    </div>
                    <div class="row meme_image">
                        <img src="./media/images/fluffy.jpg"/>
                    </div>
                    <div class="row meme_info">
                        <h2>Dodane przez: User</h2>
                        <h2>Data: 05.23.2018 r.</h2>
                    </div>
                </div>

                <div class="sites">
                    <button>Poprzednia strona</button>
                    <h1>33</h1>
                    <button>Następna strona</button>
                </div>
            </div>
            <!-- Right spacer -->
            <div class="row">
                <?php
                if(!msCheck()){
                    include_once('addon-login.php');
                    include_once('addon-register.php');
                }
                if(msCheck()){
                    include_once('addon-profile.php');
                }
                ?>
            </div>
        </section>
    <!-- Footer -->
    <?php
        require('addon-footer.php');
    ?>
</body>
</html>