<!-- Server -->
<?php
    require('./fx/connection.php');

    if(isset($_GET['verify']) && isset($_GET['email'])){
        $stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE email=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $_GET['email']);
        mysqli_stmt_execute($stmt);
        
        $query = mysqli_stmt_get_result($stmt);
        if($query && mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)) {
                $data = $row;
            }
        }
        if($data['activated'] == 0){
            if(password_verify($data['activation_code'],$_GET['verify'])){
                $stmt = mysqli_prepare($connection, "UPDATE users SET activated=1, activation_code=null WHERE email=?");
                mysqli_stmt_bind_param($stmt, 's', $_GET['email']);
                mysqli_stmt_execute($stmt);
                $activationInfo = 'Twoje konto zostało właśnie aktywowane!';
            } else {
                $activationInfo = 'Twoje konto nie może być aktywowane!';
            }
        } else {
            $activationInfo = 'Twoje konto jest już aktywne!';
        }
        
    } else {
        $activationInfo = 'Błędny URL.';
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
    <!-- Main -->
        <section class="row main">
            <!-- Left spacer -->
            <div class="row">

            </div>
            <!-- Memes -->
            <div class="row">
                <h1>
                    <?php
                    if(isset($activationInfo)){
                        echo $activationInfo;
                    }
                    ?>
                </h1>
                <form method="post" action="index.php">
                    <input type="submit" value="Powrót na strone główną"/>
                </form>
            </div>
            <!-- Right spacer -->
            <div class="row">
               
            </div>
        </section>
    <!-- Footer -->
    <?php
        require('addon-footer.php');
    ?>
</body>
</html>