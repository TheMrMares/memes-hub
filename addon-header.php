<script src="./js/main.js"></script>
<header class="row header">
    <div class="row logotype">
        <!--<img src="./media/images/fluffy.jpg"/>-->
        <h1>Service name here</h1>
    </div>
    <nav class="row navigation">
        <ul>
            <li><a href="index.php">Topka</a></li>
            <li><a href="newest.php">Najnowsze</a></li>
            <?php
                if(msCheck()){
                    echo '<li class="navigation--right"><a href="add.php">Dodaj mema</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>