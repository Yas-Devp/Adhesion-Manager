<?php
    function checherDB($type, $donnee){
        global $conn;

        $sql = $type === "r_s" ? "SELECT * FROM enterprise WHERE raison_social LIKE ?" : ($type === "ice" ? "SELECT * FROM enterprise WHERE ice=?" : "SELECT * FROM enterprise WHERE nom_prenom LIKE ?");
        if($type !== "ice") $donnee = "%$donnee%";
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


        //lol , I am debigging here - by yassine 0-0
        //print_r($data);
        $stmt->close();
        return $data;
    }
?>