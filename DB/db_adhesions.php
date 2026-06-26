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

    function addAdhesion($id_enterprise, $d_a, $d_e, $statut){
        global $conn ;

        $sql = "INSERT INTO adhesion
        (id_enterprise, date_adhesion, date_expiration, statut)
        VALUES ( ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);

        if(!$stmt){
            die("Preparation de requet a echoue : " . $conn->error);
        }

        $stmt->bind_param("isss", $id_enterprise, $d_a, $d_e, $statut);

        if ($stmt->execute()) {

            header("Location: adhesions.php?id=$id_enterprise");
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();

    }
?>