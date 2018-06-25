<div class="comment row">
    <div class="row comment_body">
        <p><span><?php echo $row['ulogin'];?>: </span><?php echo $row['comment'];?></p>
    </div>
    <div class="row comment_footer">
        <h2>Dodano: <?php echo $row['created'];?></h2>
        <h2>Przez: <?php echo $row['ulogin'];?></h2>
    </div>
</div>