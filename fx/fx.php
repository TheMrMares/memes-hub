<?php
    
    function prepareField($conn, $fieldName){
        $field = $_POST[$fieldName];
        $field = strip_tags($field);
        $field = mysqli_real_escape_string($conn, $field);

        return $field;
    }

?>