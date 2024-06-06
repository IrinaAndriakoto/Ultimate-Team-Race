<!DOCTYPE html>
<html>
<head>
    <title>Classement par Équipe</title>
    <!-- <link rel="stylesheet" href="../../assets/main.css" /> -->
</head>
<body>
<h2>Classement des coureurs par étape</h2>

<?php foreach ($classement as $etape => $coureurs): ?>
    <h3>Étape: <?php echo $etape; ?></h3>
    <table border="1">
        <thead>
            <tr>
                <th>Rang</th>
                <th>Coureur</th>
                <th>Équipe</th>
                <th>Catégorie</th>
                <th>Heure d'arrivée</th>
                <th>Points</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i = 1; 
            $prev = ''; 
            $prev_points = null;
            foreach ($coureurs as $index => $coureur): 
                $is_points_ex_aequo = ($index > 0 && $coureur->points == $prev_points);
            ?>
                <tr>
                    <td><?php if ($coureur->arrivee !== $prev) { echo $i++; } else { $i; } $prev = $coureur->arrivee; ?></td>
                    <td><?php echo $coureur->coureur; ?></td>
                    <td><?php echo $coureur->equipe; ?></td>
                    <td><?php echo $coureur->categorie; ?></td>
                    <td><?php echo $coureur->arrivee; ?></td>
                    <td><?php echo $coureur->points; ?></td>
                </tr>
                <?php $prev_points = $coureur->points; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endforeach; ?>

</body>
</html>
