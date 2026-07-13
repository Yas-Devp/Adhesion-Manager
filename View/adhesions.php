<?php

    #note to myself: use session to get back the enterprise data from previous page 
    include_once("../DB/db_connection.php");
    include_once("../DB/db_adhesions.php");
    include_once("../DB/db_activities.php");
    //include("../DB/db_activities.php");


    $data = array();
    $activities = array();
    $id = NULL;

    $is_edit = false;
    $edit_adhesion = ['id_adhesion' => '', 'date_adhesion' => '', 'date_expiration' => '', 'statut' => 'active', 'montant' => ''];

    if(isset($_POST['submit'])){
        $id = intval($_POST['id']);
        $d_a = $_POST['d_a'];
        $d_e = $_POST['d_e'];
        $statut = $_POST['statut'];
        $montant = $_POST['montant'];
        $a_id = isset($_POST['a_id']) ? intval($_POST['a_id']) : null;

        if (!empty($a_id)) {
            // TODO: Créez cette fonction dans votre fichier db_adhesions.php
            updateAdhesion($id, $a_id, $d_a, $d_e, $statut, $montant);
        } else {
            addAdhesion($id, $d_a, $d_e, $statut, $montant);
        }
        
        header("Location: adhesions.php?id=" . $id);
        exit();
    }else if(isset($_GET['id']) && !empty($_GET['id'])){
        $id = intval($_GET['id']);

        if(isset($_GET['delete']) && !empty($_GET['a_id'])){
            $a_id = intval($_GET['a_id']);
            deleteAdhesion($id, $a_id);
        }

        $data = getAdhesions($id);
        $activities = getSavedActivities($id);

        if(isset($_GET['change']) && !empty($_GET['a_id'])){
            $search_aid = intval($_GET['a_id']);
            foreach ($data as $row) {
                if (isset($row['id_adhesion']) && intval($row['id_adhesion']) === $search_aid) {
                    $edit_adhesion = $row;
                    $is_edit = true;
                    break;
                }
            }
        }
        //debugging ici '-'
        //print_r($activities);
    } else{
        die("Erreur: On ne peux pas traiter cette operation !"."<br/>"."<a href=\"javascript:history.back()\">retour en arriere</a>");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Adhesions</title>
    <link rel="stylesheet" href="./css/navBar.css">
    <style>
        body{
            font-family: Arial;
            width: 100%;
            padding: 0;
            margin: 0;
            padding-top: 50px;
        }

        .overlay{
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: #00000067;
            z-index: 2;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        form{
            background-color: white;
            padding: 30px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            border-radius: 15px;
        }

        input[type="submit"]{
            border: none;
            padding: 10px;
            border-radius: 5px;
            background-color: rgb(48, 124, 237);;
            color: white;
            cursor: pointer;
        }

        fieldset{
            margin: 15px;
            border-radius: 7px;
        }

        fieldset legend{
            font-weight: bolder;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <?php include('./layout/navBar.php'); ?>
    <!--here for enterprise data only-->
    <div class="enterprise_data">
        <fieldset>
            <legend>Les Information de l'Entreprise</legend>
            
            <?php

                if (!empty($data)) {
                    $enterprise = $data[0];

                    echo "<span><b>Raison Sociale:</b><p> ".htmlspecialchars($enterprise['raison_social'])."</p></span>";
                    echo "<span><b>Gerant:</b><p> ".htmlspecialchars($enterprise['nom'])." ".htmlspecialchars($enterprise['prenom'])."</p></span>";
                    echo "<span><b>ICE:</b><p> ".htmlspecialchars($enterprise['ICE'])."</p></span>";
                    echo "<span><b>RC:</b><p> ".htmlspecialchars($enterprise['RC'])."</p></span>";
                }
            ?>
        </fieldset>
    </div>

    <!--here for adhesions data inshallah-->
    <div class="enterprise_adhesions">
        <fieldset>
            <legend>Adhesions</legend>
            <?php
                $count = 0;
                foreach($data as $row){
                    if(empty($row['id_adhesion'])) continue;
                    echo "<div style=\"display: flex; flex-direction: row; gap: 20px;align-items: center;\"><p>".htmlspecialchars($row["date_adhesion"])
                    ."<b style=\"font-size: 20px;\"> / </b>"
                    .htmlspecialchars($row["date_expiration"])
                    ."</p><p>status: ".htmlspecialchars($row["statut"])
                    ."</p><p>".htmlspecialchars($row["montant"])."DH"
                    ."</p><div style=\"margin-left: auto;cursor:pointer;\"><a href=\"adhesions.php?id=$id&a_id=".$row['id_adhesion']."&delete\" style=\"color: red\">Delete</a>  <a href=\"adhesions.php?id=$id&a_id=".$row['id_adhesion']."&change\" style=\"color: green;\">Changer</a></div></div>";
                    $count++;
                }

                if($count === 0) echo "Aucune Adhesion existe !";
            ?>

            <div class="btns">
                <button style="margin-top: 20px;padding: 8px 10px;border:none;border-radius:5px;color:white;background-color:rgb(48, 124, 237);cursor:pointer;" onclick="toggleForm()">Ajouter Adhesion</button>
            </div>
        </fieldset>
    </div>

    <div class="enterprise_adhesions">
        <fieldset>
            <legend>Activities</legend>

            <?php
                $count = 0;

                foreach ($activities as $item) {
                    echo '<p>'.htmlspecialchars($item['activite_code']) . ': ' . htmlspecialchars($item['description']) . '</p>';
                    $count++;
                }

                if ($count === 0) {
                    echo "Aucune Activite existe !";
                }

            ?>

            <div class="btns">
                <a
                    style="margin-top:20px;padding:8px 10px;border:none;border-radius:5px;color:white;background-color:rgb(48,124,237);cursor:pointer; text-decoration: none;font-size: 14px;"
                    href="activites.php?id=<?= $id ?>">
                    Ajouter Activite
                </a>
            </div>
        </fieldset>
    </div>

        <!-- Fenêtre Modale (Formulaire d'ajout / modification) -->
    <div class="overlay" id="overlay" style="display: <?= $is_edit ? 'flex' : 'none'; ?>;">
        <form action="adhesions.php?id=<?= $id; ?>" method="post" style="position: relative;">
            <!-- Si on annule la modification, on recharge la page sans les paramètres de modification -->
            <span id="close_btn" style="padding:5px; position:absolute; top: 10px; right:10px; color: white; background-color: red; border-radius: 50%; cursor: pointer; font-size: 12px; line-height: 10px;">✕</span>
            
            <h3 style="margin-top: 0; text-align: center;">
                <?= $is_edit ? "Modifier l'Adhésion" : "Ajouter une Adhésion" ?>
            </h3>

            <div class="field">
                <label for="d_a">Date Adhésion :</label>
                <input type="date" name="d_a" id="d_a" value="<?= htmlspecialchars($edit_adhesion['date_adhesion']) ?>" required>
            </div>

            <div class="field">
                <label for="d_e">Date Expiration :</label>
                <input type="date" name="d_e" id="d_e" value="<?= htmlspecialchars($edit_adhesion['date_expiration']) ?>" required>
            </div>

            <div class="field">
                <label for="montant">Montant (DH) :</label>
                <input type="number" name="montant" id="montant" placeholder="Montant" value="<?= htmlspecialchars($edit_adhesion['montant']) ?>" required min="0">
            </div>

            <div class="field">
                <label>Statut :</label>
                <div class="field-radio">
                    <input type="radio" name="statut" id="statut1" value="active" <?= $edit_adhesion['statut'] === 'active' ? 'checked' : '' ?>> 
                    <label for="statut1">Activée</label>
                    
                    <input type="radio" name="statut" id="statut2" value="expire" <?= $edit_adhesion['statut'] === 'expire' ? 'checked' : '' ?>> 
                    <label for="statut2">Expirée</label>
                    
                    <input type="radio" name="statut" id="statut3" value="suspendu" <?= $edit_adhesion['statut'] === 'suspendu' ? 'checked' : '' ?>> 
                    <label for="statut3">Suspendue</label>
                </div>
            </div>
            
            <!-- Identifiants cachés pour la soumission -->
            <input type="hidden" name="id" value="<?= $id; ?>">
            <?php if ($is_edit): ?>
                <input type="hidden" name="a_id" value="<?= $edit_adhesion['id_adhesion']; ?>">
            <?php endif; ?>

            <input type="submit" value="<?= $is_edit ? 'Enregistrer les modifications' : 'Ajouter' ?>" name="submit">
        </form>
    </div>


    <script>
        const overlay = document.getElementById("overlay");
        function toggleForm(){
            
            if(overlay.style.display === 'none'){
                overlay.style.display = "flex";
            }else{
                overlay.style.display = "none";
            }
        }

        document.getElementById("close_btn").addEventListener("click", ()=>{
            if (window.location.search.includes('&change')) {
                window.location.href = "adhesions.php?id=<?= $id; ?>";
            } else {
                overlay.style.display = "none";
            }
        });

        const startDateInput = document.getElementById('d_a');
        const endDateInput = document.getElementById('d_e');

        //calculating the expiration day automatically(just adding one year of memberships)
        startDateInput.addEventListener('change', function() {
            const selectedDateStr = this.value;
            
            if (selectedDateStr) {
                const date = new Date(selectedDateStr);
                date.setFullYear(date.getFullYear() + 1);

                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const targetDateStr = `${year}-${month}-${day}`;
                
                endDateInput.value = targetDateStr;
                endDateInput.min = targetDateStr;
            }
        });
    </script>
</body>
</html>