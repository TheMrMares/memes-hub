<!-- Server -->
<?php
    //Login
    if(isset($_POST['login--submit'])){

    }
    //Register
    if(isset($_POST['register--submit'])){

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
                        <input type="text" name="register--email" placeholder="Twój email"/>
                        <h2>Powtórz email</h2>
                        <input type="text" name="register--email_repeat" placeholder="Powtórz twój email"/>
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