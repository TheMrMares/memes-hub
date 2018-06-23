<!-- Server -->
<?php
    require_once('./fx/fx.php');

    if(!msCheck()){
        Header('location: index.php');
    }

    //Add
    if(isset($_POST['add--submit'])){
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
            
        } else {
            $stmt = mysqli_prepare($connection, "DELETE FROM memes WHERE id=?");
            mysqli_stmt_bind_param($stmt, 's', $insertedId);
            mysqli_stmt_execute($stmt);
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