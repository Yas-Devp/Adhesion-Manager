<?php 
    function getAdhesions($id_enterprise) {
        global $conn ;
        $sql = "SELECT * FROM enterprise LEFT JOIN adhesion ON adhesion.id_enterprise=enterprise.id_enterprise WHERE enterprise.id_enterprise=?";
        $stmt = $conn->prepare($sql);

        if(!$stmt){
            die("Preparation de requet a echoue : " . $conn->error);
        }

        $stmt->bind_param("i", $id_enterprise);

        if($stmt->execute()){
            $result = $stmt->get_result();
            $data = [];

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }

            return $data;
        }else{
            die("Erreur : " . $conn->error);
        }

        $stmt->close();
    }

    function addAdhesion($id_enterprise, $d_a, $d_e, $statut, $montant){
        global $conn ;

        $sql = "INSERT INTO adhesion
        (id_enterprise, date_adhesion, date_expiration, statut, montant)
        VALUES ( ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);

        if(!$stmt){
            die("Preparation de requet a echoue : " . $conn->error);
        }

        $stmt->bind_param("isssi", $id_enterprise, $d_a, $d_e, $statut, $montant);

        if ($stmt->execute()) {

            header("Location: adhesions.php?id=$id_enterprise");
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();

    }

    function deleteAdhesion($id_enterprise, $id_adhesion){
        global $conn;

        $sql = "DELETE FROM adhesion WHERE id_adhesion=?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Preparation de requet a echoue : " . $conn->error);
        }

        $stmt->bind_param("i", $id_adhesion);

        if ($stmt->execute()) {

            header("Location: adhesions.php?id=$id_enterprise");
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();
    }

    function updateAdhesion($id, $a_id, $d_a, $d_e, $statut, $montant) {
        global $conn;

        $sql = "UPDATE adhesion
                SET date_adhesion = ?, 
                    date_expiration = ?, 
                    statut = ?, 
                    montant = ? 
                WHERE id_adhesion = ? AND id_enterprise = ?";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("ssssii", $d_a, $d_e, $statut, $montant, $a_id, $id);
            
            $result = $stmt->execute();
            $stmt->close();
            
            return $result;
        } else {
            die("Erreur de préparation : " . $conn->error);
        }
    }

?>