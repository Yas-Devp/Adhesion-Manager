<?php
    function checherDB($type, $donnee){
        $sql = $type === "r_s" ? "SELECT * FROM enterprise WHERE raison_social='%$donee%'" : "SELECT * FROM enterprise WHERE ice='%$donee%'";
    }
?>