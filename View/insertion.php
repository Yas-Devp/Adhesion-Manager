<?php
    include_once("../DB/db_connection.php");
    include_once("../DB/db_insertion.php");

    if(isset($_POST['submit'])){
        $f_j = $_POST['forme_jurdique'];
        $r_s = $_POST['rs'];
        $n_p = $_POST['np'];
        $adr = $_POST['addr'];
        $vll = $_POST['ville'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        $s_a = $_POST['sect_act'];
        $web = $_POST['web'];
        $capital = $_POST['capital'];
        $eff = $_POST['effectif'];
        $d_c = $_POST['date_cre'] ?? date('Y-m-d');
        $ice = $_POST['ice'];
        $rc = $_POST['rc'];

        insererDB($f_j,
                  $r_s,
                  $n_p,
                  $adr,
                  $vll,
                  $tel,
                  $email,
                  $s_a,
                  $web,
                  $capital,
                  $eff,
                  $d_c,
                  $ice,
                  $rc
                );
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navBar.css">
    <title>page d'Insertion (Adhesion)</title>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="insertion.php">Inserer</a></li>
                <li><a href="searchInDB.php">chercher</a></li>
            </ul>
        </nav>
    </header>
    <form method="post">
        <h2>Formulaire d'insertion</h2>
        <div class="field_mc">
            <label for="forme_jurd">Forme Jurdique de l'enterprise : </label>
            <div class="choices">
                <input type="radio" name="forme_jurdique" id="forme_jurd" value="pp"><b>PP</b>
                <input type="radio" name="forme_jurdique" id="forme_jurd" value="sarl" checked><b>SARL</b>
                <input type="radio" name="forme_jurdique" id="forme_jurd" value="snc"><b>SNC</b>
                <input type="radio" name="forme_jurdique" id="forme_jurd" value="sa"><b>SA</b>
            </div>
            
        </div>
        <div class="field">
            <label for="rs">Raison sociale: </label>
            <input type="text" name="rs" id="rs" required>
        </div>

        <div class="field">
            <label for="nc">Nom et Prenom : </label>
            <input type="text" name="np" id="np" required>
        </div>

        <div class="field">
            <label for="adresse">Adresse : </label>
            <input type="text" name="addr" id="addr" required>
        </div>

        <div class="field">
            <label for="ville">ville: </label>
            <input type="text" name="ville" id="ville" required>
        </div>

        <div class="field">
            <label for="tel">Telephone: </label>
            <input type="tel" name="tel" id="tel" value="+212" required>
        </div>

        <div class="field">
            <label for="rs">Email: </label>
            <input type="email" name="email" id="email" placeholder="exemple123@gmail.com">
        </div>

        <div class="field">
            <label for="sect_act">Secteur d'activite : </label>
            <input type="text" name="sect_act" id="sect_act" placeholder="separes par ',' (exemple: BTP, AEP)" required>
        </div>

        <div class="field">
            <label for="web">Site Web: </label>
            <input type="text" name="web" id="web" placeholder="exemple : https://exemple.com">
        </div>

        <div class="field">
            <label for="capital">Capital: </label>
            <input type="number" name="capital" id="capital" value="0" step="1000" required>
        </div>

        <div class="field">
            <label for="effectif">Effectif: </label>
            <input type="number" name="effectif" id="effectif" value="0" required>
        </div>

        <div class="field">
            <label for="date_cre">Date de creation: </label>
            <input type="date" name="date_cre" id="date_cre">
        </div>

        <div class="field double">
            <input type="text" name="ice" placeholder="ICE" required>
            <input type="text" name="rc" placeholder="RC" required>
        </div>

        <input type="submit" name="submit" id="submit" value="Inserer">
    </form> 
</body>
</html>