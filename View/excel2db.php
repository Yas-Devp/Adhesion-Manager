<?php
    include_once("../DB/db_connection.php");
    //include_once("../DB/db_excel.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel & DB Exchange</title>
    <style>
        body{
            font-family: Arial;
        }
        form{
            width: 70%;
            border: 2px solid black;
            border-radius: 20px;
            padding: 40px;
        }
    </style>
</head>
<body>
    <form method="post">
        <h2>Excel To Database</h2>
        <div class="field">
            <label for="excel_file">Choisez votre fichier Excel: </label>
            <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
        </div>
        <div class="field">
            <label for="type">Table : </label><br/>
            <input type="radio" name="table" id="table" checked> Enterprise
            <input type="radio" name="table" id="table" > Adhesion
            <input type="radio" name="table" id="table" > Activities
        </div>
    </form>
</body>
</html>