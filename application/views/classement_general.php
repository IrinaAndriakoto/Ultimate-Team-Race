<!DOCTYPE html>
<html>
<head>
    <title>Classement par Équipe</title>
    <link rel="stylesheet" href="../../assets/main.css" />
    <style>
        .ex-aequo {
            background-color: red;
        }
    </style>
</head>
<body>

<h1>Classement par Équipe</h1>

<?php if (!empty($points_par_equipe)): ?>
    <?php foreach ($points_par_equipe as $categorie => $equipes): ?>
        <h2>Catégorie: <?php echo $categorie; ?></h2>
        <table border="1">
            <tr>
                <th>Équipe</th>
                <th>Points Totaux</th>
                <!-- <th>Coureurs</th> -->
            </tr>
            <?php
            // Sort teams by points in descending order
            uasort($equipes, function($a, $b) {
                return $b['points'] - $a['points'];
            });

            // Detect ex aequos
            $points_seen = []; // To track already seen points
            ?>
            <?php foreach ($equipes as $equipe => $info): ?>
            <?php
            // Check if points are already seen
            $is_ex_aequo = isset($points_seen[$info['points']]);
            $points_seen[$info['points']] = true;
            ?>
            
            <tr class="<?php echo $is_ex_aequo ? 'ex-aequo' : ''; ?>">
                <td><?php echo $equipe; ?></td>
                <td><?php echo $info['points']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun classement disponible.</p>
<?php endif; ?>

</body>
</html>
