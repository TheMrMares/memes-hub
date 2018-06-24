<!-- Server -->
<?php
    require_once('./fx/fx.php');
    //Login
    if(isset($_POST['login--submit'])){
        sLogin();
    }
    //Register
    if(isset($_POST['register--submit'])){
        sRegister();
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
<body p-id="newest">
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
                <?php
                    require('./fx/connection.php');

                    if(isset($_GET['page'])){
                        $page = $_GET['page'];
                    } else {
                        $page = 1;
                    }
                    $perPage = 2;

                    $stmt = mysqli_prepare($connection, "SELECT memes.*, users.login AS ulogin FROM memes INNER JOIN users ON memes.user_id = users.id ORDER BY memes.created DESC");
                    mysqli_stmt_execute($stmt);

                    $query = mysqli_stmt_get_result($stmt);

                    if($query && mysqli_num_rows($query) > 0){
                        $counter = 0;
                        while($row = mysqli_fetch_assoc($query)) {
                            if($counter >= ($page-1)*$perPage && $counter < ($page-1)*$perPage + $perPage){
                                include('./addon-meme.php');
                            }
                            $counter++;
                        }
                    }

                    mysqli_close($connection);
                ?>

                <div class="sites">
                    <form method="post" action="index.php?page=<?php echo $page+1;?>">
                        <button>Poprzednia strona</button>
                    </form>
                    <h1><?php echo $page;?></h1>
                    <form method="post" action="index.php?page=<?php echo $page+1;?>">
                        <button>Następna strona</button>
                    </form>
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