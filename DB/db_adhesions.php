<?php
    global $conn ; 
    function getAdhesions($id_enterprise) {
        $sql = "SELECT * FROM adhesion WHERE id_enterprise=?";
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
        $sql = "INSERT INTO adhesion
        (id_enterprise, date_adhesion, date_expiration, status)
        VALUES ( ?, ?, ?, ?)";
        
        $stmt = conn->prepare($sql);

        if(!$stmt){
            die("Preparation de requet a echoue : " . $conn->error);
        }

        $stmt->bind_param("isss", $id_enterprise, $d_a, $d_e, $statut);

        if ($stmt->execute()) {
            echo "<div id=\"overlay\" style=\"position: absolute;width: 100%;height: 100%;z-index: 2;background-color: #0000002c;  display: flex;justify-content: center;display-direction:column; align-items:center;\">
                <div style=\"width:300px;background-color: white; padding: 20px;border-radius: 20px; text-align: center; \">
                    <p style=\"color: black;\">les donnes sont inseres correctement !<p>
                    <div>
                        <button style=\"background-color: red; color: white; padding: 15px 30px; border:none; border-radius: 7px; cursor: pointer;\" onclick=\"document.getElementById('overlay').style.display='none'\">fermer</button>
                    </div>
                </div>
            </div>";
        } else {
            echo "Erreur: " . $stmt->error;
        }

        $stmt->close();

    }
?>