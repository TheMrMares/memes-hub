<!-- Server -->
<?php
    require_once('./fx/fx.php');

    msCheck();
    if(isset($_POST['comment--submit'])){
        sComment();
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
<body p-id="meme">
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
                    $emergencyId = '%';
                    $stmt = mysqli_prepare($connection, "SELECT memes.*, users.login AS ulogin FROM memes INNER JOIN users ON memes.user_id = users.id WHERE memes.id=? ORDER BY memes.created DESC LIMIT 1");
                    if(isset($_GET['id'])){
                        mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
                    } else {
                        $stmt = mysqli_prepare($connection, "SELECT memes.*, users.login AS ulogin FROM memes INNER JOIN users ON memes.user_id = users.id WHERE memes.id=% ORDER BY memes.created DESC LIMIT 1");
                    }
                    echo gettype($stmt);
                    mysqli_stmt_execute($stmt);
                    $query = mysqli_stmt_get_result($stmt);

                    if($query && mysqli_num_rows($query) > 0){
                        while($row = mysqli_fetch_assoc($query)) {
                            include('./addon-meme.php');
                        }
                    }

                    mysqli_close($connection);
                ?>

                <div class="write_comment">
                    <form method="post" action="meme.php?id=<?php echo $_GET['id'];?>">
                        <h1>Napisz komentarz</h1>
                        <textarea type="text" name="comment--comment" placeholder="Treśc twojego komentarza..."></textarea>
                        <input type="submit" name="comment--submit" value="Dodaj komentarz"/>
                    </form>
                </div>
                <div class="comments">
                    <h1>Komentarze</h1>
                    <?php
                        require('./fx/connection.php');
                        $stmt = mysqli_prepare($connection, "SELECT comments.*, users.login AS ulogin FROM comments INNER JOIN users ON comments.user_id = users.id WHERE meme_id=?");
                        mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
                        mysqli_stmt_execute($stmt);
                        $query = mysqli_stmt_get_result($stmt);

                        if($query && mysqli_num_rows($query) > 0){
                            while($row = mysqli_fetch_assoc($query)) {
                                include('./addon-comment.php');
                            }
                        }
    
                        mysqli_close($connection);
                    ?>
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