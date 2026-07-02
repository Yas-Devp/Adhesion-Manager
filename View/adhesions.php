<?php

    #note to myself: use session to get back the enterprise data from previous page 
    include_once("../DB/db_connection.php");
    include_once("../DB/db_adhesions.php");
    include("../DB/db_activities.php");


    $data = array();
    $activities = array();
    $id = NULL;
    if(isset($_POST['submit'])){
        $id = intval($_POST['id']);
        $d_a = $_POST['d_a'];
        $d_e = $_POST['d_e'];
        $statut = $_POST['statut'];

        addAdhesion($id, $d_a, $d_e, $statut);
        exit();
    }else if(isset($_GET['id']) && !empty($_GET['id'])){
        $id = intval($_GET['id']);
        $data = getAdhesions($id);
        $activities = getActivities($id);
    } else{
        die("Erreur: On ne peux pas traiter cette operation !"."<br/>"."<a href=\"javascript:history.back()\">retour a l'arrier</a>");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Adhesions</title>
    <style>
        body{
            font-family: Arial;
            width: 100%;
            padding: 0;
            margin: 0;
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
                    echo "<div style=\"display: flex; flex-direction: row; gap: 20px;\"><p>".htmlspecialchars($row["date_adhesion"])."/".htmlspecialchars($row["date_expiration"])."</p><p>".htmlspecialchars($row["statut"])."</p></div>";
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

                foreach ($activities as $category => $items) {

                    if (empty($items)) continue;

                    echo "<div style=\"display:flex; flex-direction:row; gap:20px; margin-bottom:10px;\">";
                    echo "<p><strong>" . htmlspecialchars($category) . " :</strong> ";

                    $labels = [];
                    foreach ($items as $activity) {
                        $labels[] = htmlspecialchars($activity['libelle']);
                        $count++;
                    }

                    echo implode(", ", $labels);
                    echo "</p></div>";
                }

                if ($count === 0) {
                    echo "Aucune Activite existe !";
                }
            ?>

            <div class="btns">
                <button
                    style="margin-top:20px;padding:8px 10px;border:none;border-radius:5px;color:white;background-color:rgb(48,124,237);cursor:pointer;"
                    onclick="toggleForm()">
                    Ajouter Activite
                </button>
            </div>
        </fieldset>
    </div>

    <div class="overlay" id="overlay" style="display: none;">
        <form action="?id=<?php echo $id; ?>" method="post">
            <div class="field">
                <label for="d_a">Date Adhesion : </label>
                <input type="date" name="d_a" id="d_a" required>
            </div>

            <div class="field">
                <label for="d_e">Date Expiration : </label>
                <input type="date" name="d_e" id="d_e" required>
            </div>

            <div class="field">
                <label for="statut">Statut : </label>
                <input type="radio" name="statut" id="statut1" value="active" checked> activee
                <input type="radio" name="statut" id="statut2" value="expire"> expiree
                <input type="radio" name="statut" id="statut3" value="expire"> suspendu
            </div>
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
            <input type="submit" value="Ajouter" name="submit">
        </form>
    </div>

    <script>
        
        function toggleForm(){
            const overlay = document.getElementById("overlay");
            if(overlay.style.display === 'none'){
                overlay.style.display = "flex";
            }else{
                overlay.style.display = "none";
            }
        }
    </script>
</body>
</html>