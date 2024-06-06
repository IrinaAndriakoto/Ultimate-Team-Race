<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>La liste des etapes </h2>
    <table>
        <tr>
            <th>Rang Etape</th>
            <th>Lieu</th>
            <th>Longueur </th>
            <th>Nombre de coureur par equipe</th>
        </tr>
        <?php foreach($etapes as $etape): ?>
        <tr>
            <td><?php echo $etape->rangetape; ?></td>
            <td><?php echo $etape->nom; ?></td>
            <td><?php echo $etape->longueurkm; ?> Km</td>
            <td><?php echo $etape->nbcoureurparequipe; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>