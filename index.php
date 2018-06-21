<!-- Server -->
<?php
    require('./fx/fx.php');
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
                $dbLogin = $row['login'];
                $dbPassword = $row['password'];
                $dbEmail = $row['email'];
            }
        }
        if(password_verify($password, $dbPassword)){
            session_start();
            echo session_id();
            $_SESSION['login'] = $dbLogin;
            $_SESSION['password'] = $dbPassword;
            $_SESSION['email'] = $dbEmail;
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
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 14]);
                $stmt = mysqli_prepare($connection, "INSERT INTO users (login, password, email) VALUES(?,?,?)");
                mysqli_stmt_bind_param($stmt, 'sss', $login,$hashedPassword,$email);
                mysqli_stmt_execute($stmt);
                $ERROR_POSITIVE = 'Pomyślnie zarejestrowano. ';
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
                <!-- Login -->
                <div class="login">
                    <form method="post" action="index.php">
                        <h1>zaloguj się</h1>
                        <h2>Login</h2>
                        <input type="text" name="login--login" placeholder="Twój login"/>
                        <h2>Hasło</h2>
                        <input type="password" name="login--password" placeholder="Twoje hasło"/>
                        <input type="submit" name="login--submit" value="Zaloguj się"/>
                    </form>
                </div>
                <!-- register -->
                <div class="register">
                    <form method="post" action="index.php">
                        <h1>zaloguj się</h1>
                        <h2>Login</h2>
                        <input type="text" name="register--login" placeholder="Twój login"/>
                        <h2>Hasło</h2>
                        <input type="password" name="register--password" placeholder="Twoje hasło"/>
                        <h2>Hasło</h2>
                        <input type="password" name="register--repeat_password" placeholder="Powtórz twoje hasło"/>
                        <h2>Email</h2>
                        <input type="email" name="register--email" placeholder="Twój email"/>
                        <h2>Powtórz email</h2>
                        <input type="text" name="register--repeat_email" placeholder="Powtórz twój email"/>
                        <input type="submit" name="register--submit" value="Zarejestruj się"/>
                        <p>Rejestrując się akceptujesz regulamin serwisu.</p>
                    </form>
                </div>
            </div>
        </section>
    <!-- Footer -->
    <?php
        require('addon-footer.php');
    ?>
</body>
</html>