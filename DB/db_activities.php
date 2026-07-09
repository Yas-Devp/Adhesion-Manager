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
    function getActivities($id){
        global $conn;

        $sql = 'SELECT a.* FROM enterprise_activite ea JOIN activite a ON ea.id_activite=a.id_activite WHERE ea.id_enterprise=?';
        $stmt = $conn->prepare($sql);
        if(!$stmt){
            die("Erreur lors de la preparation de sql");
        }

        $stmt->bind_param("i", $id);
        
        if($stmt->execute()){
            $result = $stmt->get_result();
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[$row['categorie']][] = $row;
            }

        }else{
            die("Erreur : " . $stmt->error);
        }

        $stmt->close();
        return $data;
    }



    function addActivity_new($activity_string, $enterprise_id, $new_activities = []) {
        global $conn;
        
        //update old system (keep it working)
        if (!empty($activity_string)) {
            $sql = "UPDATE gestion_adhesion_entreprise SET secteur_activite = ? WHERE id_entreprise = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $activity_string, $enterprise_id);
            $stmt->execute();
            $stmt->close();
        }
        
        //update old activity system (gestion_adhesion_entreprise_activite)
        if (!empty($activity_string)) {
            // Clear old activities
            $sql = "DELETE FROM gestion_adhesion_entreprise_activite WHERE id_entreprise = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $enterprise_id);
            $stmt->execute();
            $stmt->close();
            
            //convert comma-separated string to array
            $old_activities = array_map('trim', explode(',', $activity_string));
            
            //insert into old activity table
            foreach ($old_activities as $old_activity_id) {
                if (!empty($old_activity_id) && is_numeric($old_activity_id)) {
                    $sql = "INSERT INTO gestion_adhesion_entreprise_activite (id_entreprise, id_activite) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $enterprise_id, $old_activity_id);
                    $stmt->execute();
                    $stmt->close();
                }
            }
        }
        
        //add new hierarchical activity system
        if (!empty($new_activities)) {
            // Clear existing new activities
            $sql = "DELETE FROM gestion_adhesion_entreprise_activites_new WHERE id_entreprise = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $enterprise_id);
            $stmt->execute();
            $stmt->close();
            
            //insert new activities
            foreach ($new_activities as $activite_code) {
                if (!empty($activite_code)) {
                    //verify activity exists in new system
                    $sql = "SELECT activite_code FROM gestion_adhesion_activites_new WHERE activite_code = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $activite_code);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $sql = "INSERT INTO gestion_adhesion_entreprise_activites_new (id_entreprise, activite_code) VALUES (?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("is", $enterprise_id, $activite_code);
                        $stmt->execute();
                        $stmt->close();
                    } else {
                        error_log("New activity code '$activite_code' not found for enterprise $enterprise_id");
                    }
                }
            }
        }
    }

    function getEnterpriseNewActivities($enterprise_id) {
        global $conn;
        
        $sql = "
            SELECT 
                s.section_code,
                s.section_name,
                b.branch_code,
                b.branch_name,
                sb.sous_branch_code,
                sb.sous_branch_name,
                a.activite_code,
                a.description
            FROM entreprise_activites ea
            JOIN activites_new a ON ea.activite_code = a.activite_code
            JOIN sous_branches sb ON a.sous_branch_code = sb.sous_branch_code
            JOIN branches b ON sb.branch_code = b.branch_code
            JOIN sections s ON b.section_code = s.section_code
            WHERE ea.id_entreprise = ?
            ORDER BY s.section_code, b.branch_code, sb.sous_branch_code, a.activite_code
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $enterprise_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $activities = [];
        while ($row = $result->fetch_assoc()) {
            $activities[] = $row;
        }
        
        $stmt->close();
        return $activities;
    }

    function getAllNewActivitiesGrouped() {
        global $conn;
        
        $sql = "
            SELECT 
                s.section_code,
                s.section_name,
                b.branch_code,
                b.branch_name,
                sb.sous_branch_code,
                sb.sous_branch_name,
                a.activite_code,
                a.description
            FROM activites a
            JOIN sous_branches sb ON a.sous_branch_code = sb.sous_branch_code
            JOIN branches b ON sb.branch_code = b.branch_code
            JOIN sections s ON b.section_code = s.section_code
            ORDER BY s.section_code, b.branch_code, sb.sous_branch_code, a.activite_code
        ";
        
        $result = $conn->query($sql);
        
        $hierarchy = [];
        while ($row = $result->fetch_assoc()) {
            $section = $row['section_code'] . ' - ' . $row['section_name'];
            $branch = $row['branch_code'] . ' - ' . $row['branch_name'];
            $sub_branch = $row['sous_branch_code'] . ' - ' . $row['sous_branch_name'];
            
            if (!isset($hierarchy[$section])) {
                $hierarchy[$section] = [];
            }
            if (!isset($hierarchy[$section][$branch])) {
                $hierarchy[$section][$branch] = [];
            }
            if (!isset($hierarchy[$section][$branch][$sub_branch])) {
                $hierarchy[$section][$branch][$sub_branch] = [];
            }
            
            $hierarchy[$section][$branch][$sub_branch][] = [
                'code' => $row['activite_code'],
                'description' => $row['description']
            ];
        }
        
        return $hierarchy;
    }
?>