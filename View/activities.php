<?php
    include_once('../DB/db_connection.php');
    include_once('../DB/db_activites.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Les activities de L'entreprise</title>
    <style>
        body{
            font-family: Arial;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            justify-content : center;
            align-items: center;
        }
        form{
            background : white;
            min-height: fit-content;
            box-shadow: 0px 0px 5px black;
            padding: 20px;
            border-radius : 10px;
            display: flex;
            flex-direction : column;
            gap: 10px;
            align-items: center;
        }

        .field{
            width: 100%;
        }
        
        input[type="submit"]{
            width: 50%;
            padding: 5px 0px;
            margin-top: 20px;
            font-weight: bold;
            font-size: 14px;
            color: white;
            background-color: rgb(48, 124, 237);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover{
            background-color: rgb(32, 82, 155) ;
        }
    </style>
</head>
<body>
    <form method="post">
        <div class="field">
            <label for="act">Infrastructure & Industry : </label>
            <input type="checkbox" name="act" id="act" value="btp"> BTP
            <input type="checkbox" name="act" id="act" value="aep"> AEP
            <input type="checkbox" name="act" id="act" value="vrd"> VRD
            <input type="checkbox" name="act" id="act" value="ste"> STE
        </div>

        <div class="field">
            <label for="act2">Commerce & Trade : </label>
            <input type="checkbox" name="act" id="act2" value="imp_exp"> IMP/EXP
            <input type="checkbox" name="act" id="act2" value="md_de_biens"> MD DE BIENS
            <input type="checkbox" name="act" id="act2" value="chrh"> CHRH
        </div>

        <div class="field">
            <label for="act3">Services & Technology : </label>
            <input type="checkbox" name="act" id="act3" value="ssii"> SSII
            <input type="checkbox" name="act" id="act3" value="td"> TD
            <input type="checkbox" name="act" id="act3" value="sd"> SD
        </div>

        <input type="submit" value="Valider" name="submit">
    </form>
</body>
</html>