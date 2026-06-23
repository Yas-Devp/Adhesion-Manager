<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Recherche</title>

    <style>
        body{
            font-family: Arial;
        }
        form{
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
    </style>
</head>
<body>
    <form action="search.php" method="post" class="search">
        <div class="searchbar">
            <div class="sb_container">
                <input type="text" id="searchbar" name="sb" placeholder="search by (raison social, ice, nom et prenom)">
                <input type="submit" name="search" value="🔎" class='search_btn'>
            </div>

            <div class="options_search" id="options">
                <label for="type" style="margin: 0 auto;">type de recherche : </label>
                <div class="choices">
                    <input type="radio" name="type" id="type" value="rs" checked> raison sociale
                    <input type="radio" name="type" id="type" value="ice"> ice
                    <input type="radio" name="type" id="type" value="np"> nom et prenom
                </div>
            </div>
        </div>
        <span onclick="toggleBar()" id="search_btn" style="transition: 0.4s">⮟</span>
    </form>

    <div class="search_results">
        <table>
        <?php
            if(isset($_POST["search"])){
                $data = "";
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