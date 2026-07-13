<?php
    require_once '../DB/db_connection.php';
    require_once '../DB/db_activities.php';


    $id = '';
    if((!isset($_GET['id']) || empty($_GET['id'])) && empty($id)){
        header("Location: insertion.php");
        exit();
    }
    $id = $_GET['id'];
    if(isset($_POST['submit']) && !empty($_POST["activities"])){
        $acts = $_POST["activities"];
        $new_activities = explode(",", $acts);
        addActivity_new($id, $new_activities);
    }
    $activities_hierarchy = getAllNewActivitiesGrouped();
    $presaved_activities = getSavedActivities($id);
    //print_r($presaved_activities);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activites : </title>
    <link rel="stylesheet" href="./css/navBar.css">

    <style>
        body{
            font-family: Arial;
            display: flex;
            flex-direction : column ;
            align-items : center;
            padding-top: 50px;
        }
        @media (max-width: 600px){
            .main_form{
                width:90%;
            }

            .activity-selection{
                width:85%;
            }

            .main_form .field{
                width:85%;
                flex-direction:column;
            }

            .main_form .field input{
                width:100%;
                margin-left:0;
            }
        }


        .activity-selection{
            width:80%;
            display:flex;
            flex-direction:column;
            gap:10px;
            margin-top:20px;
        }


        .activity-selection select{
            width:100%;
            padding:8px;
            border:none;
            border-radius:5px;
            background:#d7d6d6;
            font-size:14px;
        }


        .activity-selection select[multiple]{
            height:180px;
        }

        form{
            display: flex;
            justify-content: center;
        }

        form input[type="submit"]{
            padding: 8px;
            font-weight: bolder;
            color: white;
            background-color : rgb(48, 124, 237);
            border : none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include('./layout/navBar.php'); ?>
    
    <div class="activity-selection">

        <label>Section :</label>

        <select id="section_select">
            <option value="">-- Choisir une section --</option>

            <?php foreach($activities_hierarchy as $section => $branches): ?>
                <option value="<?= htmlspecialchars($section) ?>">
                    <?= htmlspecialchars($section) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Branche :</label>

        <select id="branch_select" disabled>
            <option value="">-- Choisir une branche --</option>
        </select>

        <label>Sous-branche :</label>

        <select id="sub_branch_select" disabled>
            <option value="">-- Choisir une sous-branche --</option>
        </select>

        <label>Activités :</label>

        <select 
            id="activity_select" 
            name="new_activities[]" 
            multiple
            disabled
        >
            <option value="">-- Choisir activité(s) --</option>
        </select>
        <!--small>/!\ ctrl + click droit pour choisir multiple ativites</small-->
        
        <form method="post">
            <input type="hidden" name="activities" id="activities_codes">
            <input type="submit" name="submit" value="Enrigestrer">
        </form>
    </div>

    <div class="selected_acts">
        <h3>Les Activities de L'entreprise : </h3>
        <div class="acts_cont" id="container">

        </div>
    </div>

    <script>
        const rawData = <?= json_encode($presaved_activities, JSON_UNESCAPED_UNICODE); ?>;
        let selectedActivities = rawData.map(item => ({
            code: String(item.activite_code),
            text: item.activite_code + " - " + item.description
        }));
        displaySelectedActivities();
        const hierarchy = <?= json_encode($activities_hierarchy); ?>;
        
        const sectionSelect = document.getElementById("section_select");
        const branchSelect = document.getElementById("branch_select");
        const subBranchSelect = document.getElementById("sub_branch_select");
        const activitySelect = document.getElementById("activity_select");
        
        sectionSelect.addEventListener("change", function(){

            branchSelect.innerHTML =
            '<option value="">-- Choisir une branche --</option>';

            subBranchSelect.innerHTML =
            '<option value="">-- Choisir une sous-branche --</option>';

            activitySelect.innerHTML =
            '<option value="">-- Choisir activité(s) --</option>';

            subBranchSelect.disabled = true;
            activitySelect.disabled = true;


            let section = this.value;

            if(section){

                Object.keys(hierarchy[section]).forEach(branch=>{

                    let option=document.createElement("option");

                    option.value=branch;
                    option.textContent=branch;

                    branchSelect.appendChild(option);

                });


                branchSelect.disabled=false;
            }
        });


        branchSelect.addEventListener("change",function(){
            subBranchSelect.innerHTML =
            '<option value="">-- Choisir une sous-branche --</option>';

            activitySelect.innerHTML =
            '<option value="">-- Choisir activité(s) --</option>';

            activitySelect.disabled=true;

            let section=sectionSelect.value;
            let branch=this.value;

            Object.keys(hierarchy[section][branch]).forEach(sub=>{

                let option=document.createElement("option");

                option.value=sub;
                option.textContent=sub;
                subBranchSelect.appendChild(option);
            });
            subBranchSelect.disabled=false;
        });

        subBranchSelect.addEventListener("change",function(){
            activitySelect.innerHTML="";

            let section=sectionSelect.value;
            let branch=branchSelect.value;
            let sub=this.value;

            hierarchy[section][branch][sub].forEach(activity=>{
                let option=document.createElement("option");

                option.value=activity.code;

                option.textContent=
                activity.code+" - "+activity.description;

                activitySelect.appendChild(option);
            });

            activitySelect.disabled=false;
        });

        activitySelect.addEventListener("change", function(){

            selectedActivities = [
                ...selectedActivities,
                ...Array.from(this.selectedOptions).map(option => ({
                    code: option.value,
                    text: option.textContent
                }))
            ];

            // remove duplicates
            selectedActivities = selectedActivities.filter(
                (activity,index,self)=>
                index === self.findIndex(
                    a=>a.code===activity.code
                )
            );


            //console.log(selectedActivities);
            displaySelectedActivities();
            
        });

        function displaySelectedActivities(){

            const container = document.getElementById('container');

            container.innerHTML = "";

            selectedActivities.forEach(activity => {

                const div = document.createElement("div");

                div.innerHTML = `
                    ${activity.text}
                    <button onclick="removeActivity('${activity.code}')">
                        ❌
                    </button>
                `;

                container.appendChild(div);

            });

            const acts_codes = document.getElementById('activities_codes');
            acts_codes.value = selectedActivities.map(item => item.code).join(",");
            console.log(acts_codes.value);

        }


        function removeActivity(code){

            selectedActivities = selectedActivities.filter(
                activity => activity.code !== code
            );

            displaySelectedActivities();

        }
    </script>
</body>
</html>