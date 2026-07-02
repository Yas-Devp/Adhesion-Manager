<?php
    session_start();
    include_once("../DB/db_connection.php");
    include_once("../DB/db_update.php");

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && (!isset($_GET['id']) || empty($_GET['id']))) {
        unset($_SESSION['id']);
        header("Location: searchInDB.php");
        exit();
    }

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $_SESSION['id'] = $_GET['id'];
    }

    $id = $_SESSION['id'] ?? '';

    if (empty($id)) {
        header("Location: searchInDB.php");
        exit();
    }

    if (isset($_POST['submit'])) {
        $f_j   = $_POST['forme_jurdique'] ?? '';
        $r_s   = $_POST['rs'] ?? '';
        $n = $_POST['n'] ?? '';
        $p = $_POST['p'] ?? '';
        $tel   = $_POST['tel'] ?? '';
        $email = $_POST['email'] ?? '';

        $success = updateDB($id, $f_j, $r_s, $n, $p, $tel, $email);

        if ($success) {
            unset($_SESSION['id']);
            header("Location: searchInDB.php?status=success");
            exit();
        } else {
            echo "La mise à jour a échoué.";
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Modification page</title>
</head>
<body>
    <form method="post" class="main_form">
        <h2>Modifier les donnees</h2>
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
            <input type="text" name="rs" id="rs">
        </div>

        <div class="field">
            <label for="n">Nom : </label>
            <input type="text" name="n" id="n">
        </div>

        <div class="field">
            <label for="p">Prenom : </label>
            <input type="text" name="p" id="p">
        </div>

        <div class="field">
            <label for="tel">Telephone: </label>
            <input type="tel" name="tel" id="tel">
        </div>

        <div class="field">
            <label for="rs">Email: </label>
            <input type="email" name="email" id="email" placeholder="exemple123@gmail.com">
        </div>


        <input type="submit" name="submit" id="submit" value="Modifier">
    </form>
</body>
</html>