<?php
    function updateDB($id, $f_j, $r_s, $n_p, $tel, $email) {
        global $conn;

        $f_j_allowed = ['PP', 'SARL', 'SA', 'SNC'];
        $f_j = strtoupper($f_j);

        $fields = [];
        $types = "";
        $params = [];

        if (!empty($f_j) && in_array($f_j, $f_j_allowed, true)) {
            $fields[] = "forme_jurdique = ?";
            $types .= "s";
            $params[] = $f_j;
        }
        if (!empty($r_s)) {
            $fields[] = "raison_social = ?";
            $types .= "s";
            $params[] = $r_s;
        }
        if (!empty($n_p)) {
            $fields[] = "nom_prenom = ?";
            $types .= "s";
            $params[] = $n_p;
        }
        if (!empty($tel)) {
            $fields[] = "telephone = ?";
            $types .= "s";
            $params[] = $tel;
        }
        if (!empty($email)) {
            $fields[] = "email = ?";
            $types .= "s";
            $params[] = $email;
        }


        if (empty($fields)) {
            return false; 
        }

        $sql = "UPDATE enterprise SET " . implode(", ", $fields) . " WHERE id_enterprise = ?";
        
        $types .= "i";
        $params[] = $id;

        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            
            $success = mysqli_stmt_execute($stmt);

            if (!$success) {
                echo "Erreur SQL : " . mysqli_stmt_error($stmt);
                exit(); //pour bien lire l'erreur 0-0
            }


            mysqli_stmt_close($stmt);

            return $success;
        }

        return false;
    }
?>