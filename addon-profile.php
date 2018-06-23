<?php
require('./fx/connection.php');
//Post handler
if(isset($_POST['logout--submit'])){
    msStop();
    Header('location: index.php');
}

//Data loading
$stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE login=? AND email=? LIMIT 1");
mysqli_stmt_bind_param($stmt, 'ss', $_SESSION['login'], $_SESSION['email']);
mysqli_stmt_execute($stmt);

$query = mysqli_stmt_get_result($stmt);
if($query && mysqli_num_rows($query) > 0){
    while($row = mysqli_fetch_assoc($query)) {
        $data = $row;
    }
}
$data['created'] = date("d-m-Y", strtotime($data['created']));
mysqli_close($connection);
?>
<!-- Profile -->
<div class="profile">
    <h1>
        <?php echo $data['login'];?>
    </h1>
    <h2>
        <span>Zarejestrowany: <?php echo $data['created'];?></span>
    </h2>
    <form method="post" action="index.php">
        <input type="submit" name="logout--submit" value="Wyloguj się"/>
    </form>
</div>