<?php
    include_once('../DB/db_activities.php');

    function insererDB($f_j, $r_s, $n, $p, $adr, $vll, $tel, $email, $s_a, $web, $capital, $eff, $d_c, $ice, $rc){
        global $conn;
        $f_j_allowed = ['PP', 'SARL', 'SNC', 'SA'];
        $f_j = strtoupper($f_j);
        $stmt = $conn->prepare(
            "INSERT INTO enterprise
            ( nom, prenom, adresse, ville, telephone, email, site_web, capital, effectif, date_creation, raison_social, secteur_activite, forme_jurdique, ice, rc)
            VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        if (!$stmt) {
            die("Preparation de requet a echoue : " . $conn->error);
        }

        if (!in_array($f_j, $f_j_allowed, true)) {
            die("Invalid forme juridique");
        }

        $stmt->bind_param(
            "sssssssdissssii",
            $n,
            $p,
            $adr,
            $vll,
            $tel,
            $email,
            $web,
            $capital,
            $eff,
            $d_c,
            $r_s,
            $s_a,
            $f_j,
            $ice,
            $rc
        );

        $conn->begin_transaction();

        if ($stmt->execute()) {

            $id = $conn->insert_id;

            $success = addActivity($s_a, $id);

            if (!$success) {
                $conn->rollback();
                die("Error inserting activities");
            }

            $conn->commit();
            echo "<div id=\"overlay\" style=\"position: absolute;width: 100%;height: 100%;z-index: 2;background-color: #0000002c;  display: flex;justify-content: center;display-direction:column; align-items:center;\">
                <div style=\"width:300px;background-color: white; padding: 20px;border-radius: 20px; text-align: center; \">
                    <p style=\"color: black;\">les donnes sont inseres correctement !<p>
                    <div>
                        <button style=\"background-color: red; color: white; padding: 15px 30px; border:none; border-radius: 7px; cursor: pointer;\" onclick=\"document.getElementById('overlay').style.display='none'\">fermer</button>
                    </div>
                </div>
            </div>";

        } else {
            $conn->rollback();
            die("Erreur: " . $stmt->error);
        }

        $stmt->close();
    }
?>