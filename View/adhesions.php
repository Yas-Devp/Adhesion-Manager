<?php

    #note to myself: use session to get back the enterprise data from previous page 
    include_once("../DB/db_connection.php");
    include_once("../DB/db_adhesions.php");


    $data = array();
    if(isset($_GET['id']) && !empty($_GET['id'])){
        $id = $_GET['id'];
        $data = getAdhesion($id);
    }else{
        die("Erreur: On ne peux pas traiter cette operation !");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Adhesions</title>
</head>
<body>
    <!--here for enterprise data only-->
    <div class="enterprise_data">
        <fieldset>
            <legend>Les Information de l'Entreprise</legend>
            
            <?php
                foreach($data as $row){
                    echo "<span><b>Raison Sociale: </b><p>".$row['raison_social']."</p></span>";
                    echo "<span><b>Gerant:</b><p>".$row['nom_prenom']."</p></span>";
                    echo "<span><b>ICE: </b><p>".$row['ice']."</p></span>";
                    echo "<span><b>RC: </b><p>".$row['rc']."</p></span>";
                }
            ?>
        </fieldset>
    </div>

    <!--here for adhesions data inshallah-->
</body>
</html>