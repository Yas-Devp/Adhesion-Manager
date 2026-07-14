<?php
include_once "../DB/db_connection.php";

$mysqli = $conn;
if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");


$res_count_ent = $mysqli->query("SELECT COUNT(*) AS total FROM enterprise");
$total_enterprises = $res_count_ent->fetch_assoc()['total'] ?? 0;

$res_count_adh = $mysqli->query("SELECT COUNT(*) AS total FROM adhesion WHERE statut = 'active'");
$total_active_adhesions = $res_count_adh->fetch_assoc()['total'] ?? 0;

$query_form = "SELECT e.forme_jurdique, COUNT(a.id_adhesion) AS total_adhesions
               FROM adhesion a
               JOIN enterprise e ON a.id_enterprise = e.id_enterprise
               GROUP BY e.forme_jurdique
               ORDER BY total_adhesions DESC";

$result_form = $mysqli->query($query_form);
$donnees_form = [];
while ($row = $result_form->fetch_assoc()) {
    $label = !empty($row['forme_jurdique']) ? $row['forme_jurdique'] : "Non spécifié";
    $donnees_form[$label] = (int)$row['total_adhesions'];
}
$result_form->free();


$hauteur_ligne = 45;
$largeur_barre_max = 300;
$hauteur_graph_form = max(count($donnees_form) * $hauteur_ligne, 150);
$valeur_max_form = (!empty($donnees_form)) ? max($donnees_form) : 1;


$query_year = "SELECT YEAR(date_adhesion) AS annee, COUNT(id_adhesion) AS total_adhesions
               FROM adhesion
               WHERE date_adhesion IS NOT NULL
               GROUP BY YEAR(date_adhesion)
               ORDER BY annee ASC";

$result_year = $mysqli->query($query_year);
$donnees_evolution = [];
while ($row = $result_year->fetch_assoc()) {
    $donnees_evolution[$row['annee']] = (int)$row['total_adhesions'];
}
$result_year->free();


$largeur_svg_curve = 550;
$hauteur_svg_curve = 250;
$marge_gauche = 50;
$marge_droite = 40;
$marge_haut = 30;
$marge_bas = 40;
$largeur_utile = $largeur_svg_curve - $marge_gauche - $marge_droite;
$hauteur_utile = $hauteur_svg_curve - $marge_haut - $marge_bas;
$nb_points = count($donnees_evolution);
$valeur_max_year = (!empty($donnees_evolution)) ? max($donnees_evolution) : 1;

$query_recent = "SELECT id_enterprise, raison_social, ICE, forme_jurdique FROM enterprise ORDER BY id_enterprise DESC LIMIT 5";
$result_recent = $mysqli->query($query_recent);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion Adhésion</title>
    <link rel="stylesheet" href="./css/dashboardPage.css">
    <link rel="stylesheet" href="./css/navBar.css">
</head>

<body>
    <?php include('./layout/navBar.php'); ?>
    <div class="dashboard-container">
        <header>
            <div>
                <h1>Tableau de Bord Stratégique</h1>
                <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Suivi de l'application gestion_adhesion</p>
            </div>
            <a href="insertion.php" class="btn-primary">+ Nouvelle Entreprise</a>
        </header>


        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-title">Entreprises Enregistrées</span>
                <span class="stat-value"><?= $total_enterprises ?></span>
            </div>
            <div class="stat-card blue">
                <span class="stat-title">Adhésions Actives</span>
                <span class="stat-value"><?= $total_active_adhesions ?></span>
            </div>
        </div>


        <div class="charts-grid">
            

            <div class="chart-box">
                <h3>Adhésions par Forme Juridique</h3>
                <?php if (empty($donnees_form)): ?>
                    <p style="text-align: center; color: #718096; padding: 40px;">Aucune donnée disponible.</p>
                <?php else: ?>
                    <svg width="100%" height="<?= $hauteur_graph_form ?>" viewBox="0 0 600 <?= $hauteur_graph_form ?>" preserveAspectRatio="xMidYMid meet">
                        <?php $i = 0; foreach ($donnees_form as $label => $valeur): 
                            $largeur_barre = ($valeur / $valeur_max_form) * $largeur_barre_max;
                            $y = $i * $hauteur_ligne;
                        ?>
                            <text x="10" y="<?= $y + 25 ?>" fill="#4a5568" font-size="13" font-weight="600"><?= htmlspecialchars($label) ?></text>
                            <rect x="160" y="<?= $y + 10 ?>" width="<?= $largeur_barre ?>" height="22" fill="#107c41" rx="4" />
                            <text x="<?= 175 + $largeur_barre ?>" y="<?= $y + 26 ?>" fill="#1e293b" font-size="12" font-weight="bold"><?= $valeur ?></text>
                        <?php $i++; endforeach; ?>
                    </svg>
                <?php endif; ?>
            </div>



            <div class="chart-box">
                <h3>Évolution Annuelle des Adhésions</h3>
                <?php if (empty($donnees_evolution)): ?>
                    <p style="text-align: center; color: #718096; padding: 40px;">Aucune donnée chronologique disponible.</p>
                <?php else: ?>
                    <svg width="100%" height="<?= $hauteur_svg_curve ?>" viewBox="0 0 <?= $largeur_svg_curve ?> <?= $hauteur_svg_curve ?>" preserveAspectRatio="xMidYMid meet">

                        <line x1="<?= $marge_gauche ?>" y1="<?= $marge_haut ?>" x2="<?= $largeur_svg_curve - $marge_droite ?>" y2="<?= $marge_haut ?>" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="<?= $marge_gauche ?>" y1="<?= $marge_haut + ($hauteur_utile/2) ?>" x2="<?= $largeur_svg_curve - $marge_droite ?>" y2="<?= $marge_haut + ($hauteur_utile/2) ?>" stroke="#f1f5f9" stroke-width="1" />
                        

                        <line x1="<?= $marge_gauche ?>" y1="<?= $hauteur_svg_curve - $marge_bas ?>" x2="<?= $largeur_svg_curve - $marge_droite ?>" y2="<?= $hauteur_svg_curve - $marge_bas ?>" stroke="#cbd5e1" stroke-width="2" />
                        <line x1="<?= $marge_gauche ?>" y1="<?= $marge_haut ?>" x2="<?= $marge_gauche ?>" y2="<?= $hauteur_svg_curve - $marge_bas ?>" stroke="#cbd5e1" stroke-width="2" />

                        <?php 
                        $points_path = ""; $j = 0;
                        foreach ($donnees_evolution as $annee => $total): 

                            $x = ($nb_points > 1) ? $marge_gauche + ($j * ($largeur_utile / ($nb_points - 1))) : $marge_gauche + ($largeur_utile / 2);
                            $y = ($hauteur_svg_curve - $marge_bas) - (($total / $valeur_max_year) * $hauteur_utile);
                            
                            if ($j === 0) { $points_path .= "M $x $y"; } else { $points_path .= " L $x $y"; }
                        ?>

                            <text x="<?= $x ?>" y="<?= $hauteur_svg_curve - 15 ?>" fill="#64748b" font-size="11" font-weight="600" text-anchor="middle"><?= $annee ?></text>
                            

                            <text x="<?= $x ?>" y="<?= $y - 10 ?>" fill="#107c41" font-size="11" font-weight="bold" text-anchor="middle"><?= $total ?></text>
                            

                            <circle cx="<?= $x ?>" cy="<?= $y ?>" r="4" fill="#107c41" stroke="#fff" stroke-width="2" />
                        <?php $j++; endforeach; ?>
                        

                        <path d="<?= $points_path ?>" fill="none" stroke="#107c41" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </body>
</html>