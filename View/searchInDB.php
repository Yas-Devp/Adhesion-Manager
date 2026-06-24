<?php
    include_once("../DB/db_connection.php");
    include_once("../DB/db_search.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Recherche</title>
    <link rel="stylesheet" href="navBar.css">
    <style>
        body{
            font-family: Arial;
        }
        form{
            margin-top : 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .searchbar{
            width: 75%;
            background-color: aliceblue;
            border-radius: 20px;
        }
        .sb_container{
            padding: 10px;
            display: flex;
            flex-direction: row;
            justify-content: center;
            gap: 8px;
            align-items: center;
        }

        .sb_container input[type="text"]{
            width: 80%;
            border: 1px solid grey;
            padding: 10px;
            border-radius: 10px;
        }

        .search_btn{
            background-color: transparent;
            border: none;
            font-size: larger;
            transition: 0.4s ;
        }

        input[type="submit"]:hover{
            cursor: pointer;
            font-size: 25px;
        }

        span{
            font-size: 30px;
            color: rgb(48, 124, 237);
            font-weight: bold;
            cursor: pointer;
            transform: translateY(-20px)
        }

        #options{
            height: 0px;
        }
        .options_search{
            height: 0px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 20px;
            justify-content: center;
            align-items: center;
            transition: 0.5s;
        }

        table, td{
            border: 2px solid #000;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="insertion.php">Inserer</a></li>
                <li><a href="searchInDB.php">chercher</a></li>
                <li><a href="maj.php">mise a jour</a></li>
                <li><a href="suppression.php">supprimer</a></li>
            </ul>
        </nav>
    </header>
    <form method="post" class="search">
        <div class="searchbar">
            <div class="sb_container">
                <input type="text" id="searchbar" name="sb" placeholder="search by (raison social, ice, nom et prenom)">
                <input type="submit" name="search" value="🔎" class='search_btn'>
            </div>

            <div class="options_search" id="options">
                <label for="type" style="margin: 0 auto;">type de recherche : </label>
                <div class="choices">
                    <input type="radio" name="type" id="type" value="r_s" checked> raison sociale
                    <input type="radio" name="type" id="type" value="ice"> ice
                    <input type="radio" name="type" id="type" value="np"> nom et prenom
                </div>
            </div>
        </div>
        <span onclick="toggleBar()" id="search_btn" style="transition: 0.4s">⮟</span>
    </form>

    <div class="search_results">
        <table>
            <tr>
                <td>ID</td>
                <td>raison sociale</td>
                <td>nom et prenom</td>
                <td>telephone</td>
                <td>email</td>
                <td>forme jurdique</td>
            </tr>
                <?php
                    if(isset($_POST["search"])){
                        $type = $_POST['type'];
                        $donnee = $_POST['sb'];
                        $data = checherDB($type, $donnee);

                        foreach($data as $row){
                            echo "<tr>";
                            echo "<td>".$row['id_enterprise']."</td>";
                            echo "<td>".$row['raison_social']."</td>";
                            echo "<td>".$row['nom_prenom']."</td>";
                            echo "<td>".$row['telephone']."</td>";
                            echo "<td>".$row['email']."</td>";
                            echo "<td>".$row['forme_jurdique']."</td>";
                            echo "</tr>";
                        }
                    }
                ?>
        <table>
    </div>

    <script>
        function toggleBar(){
            const options = document.getElementById("options");
            const toggle_btn = document.getElementById("search_btn");
            if(options.style.height === "0px"){
                options.style.height = "50px";
                toggle_btn.style.transform = "rotate(180deg)";
            }else{
                options.style.height = "0px";
                toggle_btn.style.transform = "rotate(0deg)";
            }
            
        }
    </script>
</body>
</html>