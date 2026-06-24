<?php
    include_once("../DB/db_connection.php");
    include_once("../DB/db_delete.php");


    if(isset($_GET['id']) && !empty($_GET['id'])){
        $id = $_GET['id'];
        deleteDB($id);
    }else{
        header("Location: searchInDB.php");
    }
?>