<?php
    function getActivityId($libelle){
        global $conn;
        $sql = "SELECT * FROM activite WHERE libelle=?";
        $stmt = $conn->prepare($sql);
        if(!$stmt){
            die("Preparation de requet a echoue : " . $conn->error);
        }

        $stmt->bind_param("s", $libelle);

        if($stmt->execute()){
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                $activiteId = $row['id_activite'];
                //echo $activiteId;
                return $activiteId;
            } else {
                return -1;
            }
        }

        //echoue
        return -1;
    }
    function addActivity($s_a, $id){
        global $conn;
        $activities= explode(",", $s_a);
        $size = count($activities);
        $counter = 0;

        foreach($activities as $act){
            $activiteId = getActivityId($act);
            if($activiteId === -1) return false ;
            $sql = "INSERT INTO `enterprise_activite` (`id_enterprise`, `id_activite`) VALUES (?, ?);";
            $stmt = $conn->prepare($sql);
            if(!$stmt){
                die("Preparation de requet a echoue : " . $conn->error);
            }

            $stmt->bind_param("ii", $id, $activiteId);
            if($stmt->execute()){
                $counter += 1;
            }else{
                echo "Erreur: " . $stmt->error;
            }

            
        }

        return ($counter === $size);
    }
?>