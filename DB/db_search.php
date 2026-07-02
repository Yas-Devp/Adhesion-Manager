<?php
    function checherDB($type, $donnee){
        global $conn;

        if ($type === "r_s") {
            $sql = "SELECT * FROM enterprise WHERE raison_social LIKE ?";
        } elseif ($type === "ice") {
            $sql = "SELECT * FROM enterprise WHERE ice = ?";
        } elseif ($type === "n") {
            $sql = "SELECT * FROM enterprise WHERE nom LIKE ?";
        } elseif ($type === "p") {
            $sql = "SELECT * FROM enterprise WHERE prenom LIKE ?";
        } else {
            $sql = "SELECT * FROM enterprise WHERE CONCAT(prenom, ' ', nom) LIKE ?";
        }

        if ($type !== "ice") $donnee = "%$donnee%";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            die("Preparation de requet a echoue : " . $conn->error);
        }

        $stmt->bind_param("s", $donnee);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        //lol , I am debugging here - by yassine 0-0
        //print_r($data);

        $stmt->close();
        return $data;
    }
?>
