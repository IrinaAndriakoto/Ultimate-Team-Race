<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Vos coureurs </h2>
<table>
        <tr>
            <th>Nom</th>
            <th>Categorie </th>
            <th>Numero Dossard</th>
            <th>Sexe</th>
            <th>Date de naissance</th>
            <th>Equipe</th>
        </tr>
        <?php foreach($coureurs as $cr): ?>
        <tr>
            <td><?php echo $cr->nom; ?></td>
            <td><?php echo $cr->categorie; ?></td>
            <td><?php echo $cr->numerodossard; ?></td>
            <td><?php echo $cr->genre; ?></td>
            <td><?php echo $cr->datedenaissance; ?></td>
            <td><?php echo $cr->equipe; ?></td>

        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>