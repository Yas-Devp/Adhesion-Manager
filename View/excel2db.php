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
    <link rel="stylesheet" href="./css/navBar.css">
    <style>
        body{
            font-family: Arial;
            padding:0;
            margin: 0;
            padding-top: 50px;
        }
        form{
            width: 70%;
            border: 2px solid black;
            border-radius: 20px;
            padding: 40px;
            margin: 10px auto;
        }
        
        .custom-file-upload input[type="file"] {
            display: none;
        }


        .custom-file-upload .file-upload-btn {
            background-color: #107c41;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.2s ease;
            display: inline-block;
        }

        .custom-file-upload .file-upload-btn:hover {
            background-color: #0b582d;
        }

        .custom-file-upload .file-name-display {
            margin-left: 12px;
            font-family: sans-serif;
            font-size: 14px;
            color: #555;
        }


        .field {
            margin-top: 25px;
            font-family: sans-serif;
        }

        .field-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 12px;
        }

        .radio-group {
            display: flex;
            gap: 15px;
        }

        .radio-card input[type="radio"] {
            display: none;
        }

        .radio-card {
            flex: 1;
            cursor: pointer;
        }

        .radio-card .card-content {
            background: #f8f9fa;
            border: 2px solid #e2e8f0;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            transition: all 0.2s ease-in-out;
        }

        .radio-card .card-title {
            font-size: 15px;
            font-weight: 500;
            color: #4a5568;
        }

        .radio-card:hover .card-content {
            border-color: #cbd5e1;
            background: #f1f5f9;
        }


        .radio-card input[type="radio"]:checked + .card-content {
            background-color: #e6f4ea;
            border-color: #107c41;
        }

        .radio-card input[type="radio"]:checked + .card-content .card-title {
            color: #107c41;
            font-weight: 600;
        }


    </style>
</head>
<body>
    <?php include("./layout/navBar.php"); ?>
    <form method="post" enctype="multipart/form-data">
        <h2>Excel To Database</h2>

        <div class="custom-file-upload">
            <label for="excel_file" class="file-upload-btn">Sélectionner un fichier Excel</label>
            <input type="file" name="excel_file" id="excel_file" accept=".xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
            <span class="file-name-display">Aucun fichier sélectionné</span>
        </div>

        <div class="field">
            <span class="field-label">Choisir la table de destination :</span>
            <div class="radio-group">
                <label class="radio-card">
                    <input type="radio" name="table" value="enterprise" checked>
                    <div class="card-content">
                        <span class="card-title">Enterprise</span>
                    </div>
                </label>

                <label class="radio-card">
                    <input type="radio" name="table" value="adhesion">
                    <div class="card-content">
                        <span class="card-title">Adhesion</span>
                    </div>
                </label>
            </div>
            <span class="field-label" style="margin-top: 40px;">Ajouter Manuellement : </span>
            <p>au tableau de l'<a href="http://localhost/phpmyadmin/index.php?route=/table/import&db=gestion_adhesion&table=enterprise&format=csv">enterprise</a> ou tableau des <a href="http://localhost/phpmyadmin/index.php?route=/table/import&db=gestion_adhesion&table=adhesion&format=csv"> adhesions</a><p>
        </div>
    </form>


    <script>
        document.getElementById('excel_file').addEventListener('change', function() {
            const fileNameDisplay = document.querySelector('.custom-file-upload .file-name-display');
            
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                fileNameDisplay.textContent = 'Aucun fichier sélectionné';
            }
        });

    </script>
</body>
</html>