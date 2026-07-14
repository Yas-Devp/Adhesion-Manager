<?php
    function deleteDB($id) {
        global $conn;

        //delete adhesions d'abord avant passer au enterprise
        //I will need to delete activities too if I added them to DB
        //why : bcz they are using id_enterprise as foreign key
        //0-0
        //I fix the problem using CASCADE for adhesion table
        $sql = "DELETE FROM enterprise WHERE id_enterprise=?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Preparation de requet a echoue : " . $conn->error);
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {

            header("Location: searchInDB.php");
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();
    }
?>