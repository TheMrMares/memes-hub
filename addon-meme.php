<form method="post" action="meme.php?id=<?php echo $row['id'];?>">
<button class="meme">
    <div class="row meme_title">
        <h1><?php echo $row['title'];?></h1>
    </div>
    <div class="row meme_description">
        <p><?php echo $row['description'];?></p>
    </div>
    <div class="row meme_image">
        <img src="<?php echo $row['path'];?>"/>
    </div>
    <div class="row meme_info">
        <h2>Dodane przez: <?php echo $row['ulogin'];?></h2>
        <h2>Dodano: <?php echo date("d-m-Y", strtotime($row['created']));?></h2>
    </div>
</button>
</form>