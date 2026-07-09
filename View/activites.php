<?php
    require_once '../DB/db_connection.php';
    require_once '../DB/db_activities.php';

    if(!isset($_GET['id']) || empty($_GET['id'])){
        header("Location: insertion.php");
        exit();
    }
    $id = $_GET['id'];
    if(isset($_POST['submit'])){

    }
    $activities_hierarchy = getAllNewActivitiesGrouped();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activites : </title>

    <style>
        body{
            font-family: Arial;
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
    </style>
</head>
<body>
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
        <small>/!\ ctrl + click droit pour choisir multiple ativites</small>
    </div>

    <div class="selected_acts">
        <h3>Selected Activities : </h3>
        <div class="acts_cont" id="container">

        </div>
    </div>

    <script>
        let selectedActivities = [];
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


            console.log(selectedActivities);
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