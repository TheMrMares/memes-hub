<!-- Server -->
<?php
    require_once('./fx/fx.php');

    if(!msCheck()){
        Header('location: index.php');
    }

    //Add
    if(isset($_POST['add--submit'])){
        sAdd();
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
<body p-id="add">
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
            <!-- Add Meme -->
            <div class="row">
                <div class="add_meme">
                    <h1>Dodaj własnego mema</h1>
                    <form method="post" action="add.php" enctype="multipart/form-data">
                        <h2>Tytuł</h2>
                        <input type="text" name="add--title" placeholder="Tytuł mema"/>
                        <h2>Opis</h2>
                        <input type="text" name="add--description" placeholder="Twój opis"/>
                        <h2>Wybierz obrazek</h2>
                        <input type="file" name="add--files[]"></input>
                        <input type="submit" name="add--submit" value="Dodaj mema"/>
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