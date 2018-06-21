<section class="row error">
    <p class="error_positive">
    <?php
        if(isset($ERROR_POSITIVE)){
            echo $ERROR_POSITIVE;
        }
    ?>
    </p>
    <p class="error_negative">
    <?php
        if(isset($ERROR_NEGATIVE)){
            echo $ERROR_NEGATIVE;
        }
    ?>
    </p>
</section>