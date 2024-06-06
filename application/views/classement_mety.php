<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/main.css" />

</head>
<body>
<h2>Classement des coureurs par catégorie et par étape</h2>

<?php if (is_array($classement) && !empty($classement)): ?>
    <?php foreach ($classement as $categorie => $coureurs): ?>
        <h3>Catégorie: <?php echo $categorie; ?></h3>
        <table>
            <thead>
                <tr>
                    <th>Rang</th>
                    <th>Coureur</th>
                    <th>Équipe</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($coureurs as $coureur): ?>
                    <?php if (is_array($coureur) && isset($coureur['nom'])): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $coureur['nom']; ?></td>
                            <td><?php echo $coureur['equipe']; ?></td>
                            <td><?php echo $coureur['points']; ?></td>
                        </tr>
                    <?php else: ?>
                        <tr><td colspan="4">Invalid coureur data</td></tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h4>Total des points par équipe pour la catégorie <?php echo $categorie; ?></h4>
        <table>
            <thead>
                <tr>
                    <th>Équipe</th>
                    <th>Total des Points</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($points_par_equipe[$categorie]) && is_array($points_par_equipe[$categorie])): ?>
                    <?php foreach ($points_par_equipe[$categorie] as $equipe => $points): ?>
                        <tr>
                            <td><?php echo $equipe; ?></td>
                            <td><?php echo $points; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2">No team points data available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
<?php else: ?>
    <p>No classement data available.</p>
<?php endif; ?>

</body>
</html>