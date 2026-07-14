<?php
include_once "../DB/db_connection.php";

$mysqli = $conn;
if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// ==========================================
// GRAPHIQUE 1 : DONNÉES FORME JURIDIQUE
// ==========================================
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

// Configuration SVG 1 (Barres)
$hauteur_ligne = 45;
$largeur_barre_max = 350;
$hauteur_graph_form = count($donnees_form) * $hauteur_ligne;
$valeur_max_form = (!empty($donnees_form)) ? max($donnees_form) : 1;


// ==========================================
// GRAPHIQUE 2 : DONNÉES ÉVOLUTION ANNUELLE
// ==========================================
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

// On peut fermer proprement la connexion maintenant
$mysqli->close();

// Configuration SVG 2 (Courbe)
$largeur_svg_curve = 600;
$hauteur_svg_curve = 300;
$marge_gauche = 50;
$marge_droite = 40;
$marge_haut = 30;
$marge_bas = 40;
$largeur_utile = $largeur_svg_curve - $marge_gauche - $marge_droite;
$hauteur_utile = $hauteur_svg_curve - $marge_haut - $marge_bas;
$nb_points = count($donnees_evolution);
$valeur_max_year = (!empty($donnees_evolution)) ? max($donnees_evolution) : 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <link rel="stylesheet" href="./css/navBar.css">
    <style>
        body{ font-family:Arial;padding:0;margin:0;padding-top:50px; }
    </style>
</head>
<body>
    <?php include("./layout/navBar.php") ?>
    <div style="display: flex; flex-wrap: wrap; gap: 20px; max-width: 1400px; margin: 20px auto; padding: 0 15px; font-family: sans-serif;">

        <!-- BLOC GRAPHIQUE 1 : FORMES JURIDIQUES -->
        <div style="flex: 1; min-width: 450px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="text-align: center; color: #2d3748; margin-bottom: 25px;">Adhésions par Forme Juridique</h3>
            <?php if (empty($donnees_form)): ?>
                <p style="text-align: center; color: #718096;">Aucune donnée disponible.</p>
            <?php else: ?>
                <svg width="100%" height="<?= $hauteur_graph_form ?>" viewBox="0 0 650 <?= $hauteur_graph_form ?>" preserveAspectRatio="xMidYMid meet">
                    <?php $i = 0; ?>
                    <?php foreach ($donnees_form as $label => $valeur): 
                        $largeur_barre = ($valeur / $valeur_max_form) * $largeur_barre_max;
                        $y = $i * $hauteur_ligne;
                    ?>
                        <text x="10" y="<?= $y + 25 ?>" fill="#4a5568" font-size="14" font-weight="600"><?= htmlspecialchars($label) ?></text>
                        <rect x="180" y="<?= $y + 10 ?>" width="<?= $largeur_barre ?>" height="22" fill="#107c41" rx="4" />
                        <text x="<?= 195 + $largeur_barre ?>" y="<?= $y + 26 ?>" fill="#2d3748" font-size="13" font-weight="bold">
                            <?= $valeur ?> <?= $valeur > 1 ? 'adhésions' : 'adhésion' ?>
                        </text>
                    <?php $i++; endforeach; ?>
                </svg>
            <?php endif; ?>
        </div>

        <!-- BLOC GRAPHIQUE 2 : ÉVOLUTION ANNUELLE -->
        <div style="flex: 1; min-width: 450px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="text-align: center; color: #2d3748; margin-bottom: 25px;">Évolution Annuelle des Adhésions</h3>
            <?php if (empty($donnees_evolution)): ?>
                <p style="text-align: center; color: #718096;">Aucune donnée de date disponible.</p>
            <?php else: ?>
                <svg width="100%" height="<?= $hauteur_svg_curve ?>" viewBox="0 0 <?= $largeur_svg_curve ?> <?= $hauteur_svg_curve ?>" preserveAspectRatio="xMidYMid meet">
                    <line x1="<?= $marge_gauche ?>" y1="<?= $marge_haut ?>" x2="<?= $largeur_svg_curve - $marge_droite ?>" y2="<?= $marge_haut ?>" stroke="#f0f0f0" stroke-width="1" />
                    <line x1="<?= $marge_gauche ?>" y1="<?= $marge_haut + ($hauteur_utile/2) ?>" x2="<?= $largeur_svg_curve - $marge_droite ?>" y2="<?= $marge_haut + ($hauteur_utile/2) ?>" stroke="#f0f0f0" stroke-width="1" />
                    <line x1="<?= $marge_gauche ?>" y1="<?= $hauteur_svg_curve - $marge_bas ?>" x2="<?= $largeur_svg_curve - $marge_droite ?>" y2="<?= $hauteur_svg_curve - $marge_bas ?>" stroke="#cbd5e1" stroke-width="2" />
                    <line x1="<?= $marge_gauche ?>" y1="<?= $marge_haut ?>" x2="<?= $marge_gauche ?>" y2="<?= $hauteur_svg_curve - $marge_bas ?>" stroke="#cbd5e1" stroke-width="2" />

                    <?php 
                    $points_path = "";
                    $j = 0;
                    foreach ($donnees_evolution as $annee => $total): 
                        $x = ($nb_points > 1) ? $marge_gauche + ($j * ($largeur_utile / ($nb_points - 1))) : $marge_gauche + ($largeur_utile / 2);
                        $y = ($hauteur_svg_curve - $marge_bas) - (($total / $valeur_max_year) * $hauteur_utile);
                        
                        if ($j === 0) { $points_path .= "M $x $y"; } else { $points_path .= " L $x $y"; }
                    ?>
                        <text x="<?= $x ?>" y="<?= $hauteur_svg_curve - 15 ?>" fill="#64748b" font-size="12" font-weight="600" text-anchor="middle"><?= $annee ?></text>
                        <circle cx="<?= $x ?>" cy="<?= $y ?>" r="5" fill="#107c41" stroke="#fff" stroke-width="2" />
                        <text x="<?= $x ?>" y="<?= $y - 10 ?>" fill="#107c41" font-size="12" font-weight="bold" text-anchor="middle"><?= $total ?></text>
                    <?php $j++; endforeach; ?>
                    <path d="<?= $points_path ?>" fill="none" stroke="#107c41" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            <?php endif; ?>
        </div>

    </div>

</body>
</html>