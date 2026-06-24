<?php
    function deleteDB($id) {
        global $conn;

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