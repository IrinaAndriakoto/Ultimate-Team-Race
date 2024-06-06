<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="../../assets/main.css" /> -->
	<link rel="shortcut icon" href="../../assets/img/logorun.png" type="image/x-icon">

</head>
<body>
    <h2>Affecter coureur</h2>

    <form action="<?php echo site_url('Traitement/insertCoureurEtape') ?>" method="post">

        <select name="coureurs" id="">
            <option value="">
                Choisir coureur
            </option>
            <?php foreach($coureurs as $c): ?>
                <option value="<?php echo $c->nom ?>"><?php echo $c->nom ?> </option>
                <?php endforeach; ?>
            </select>
            <br>
            <select name="etape" id="">
                <option value="">Choisir l'etape</option>
                <?php foreach($etapes as $a): ?>
                    <option value="<?php echo $a->nom ?>"><?php echo $c->nom ?> </option>
                    <?php endforeach; ?>
                </select>
<br>
                <input type="submit" value="Ajouter coureur">
    </form>

    <h2>Coureurs affectés</h2>
    <table>
        <tr>
            <th>Lieu Etape</th>
            <th>Coureur</th>
            <th>Temps Chrono</th>
            <!-- <th>Categorie</th>
            <th>Longueur</th>
            <th>Rang Etape</th> -->
        </tr>
        <?php foreach($affectes as $a): ?>
        <tr>
            <td><?php echo $a->Lieucourse; ?></td>
            <td><?php echo $a->coureur; ?></td>
            <td>--</td>

            
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="<?php echo site_url('Welcome/equipe') ?>">Retour</a>
</body>
</html>