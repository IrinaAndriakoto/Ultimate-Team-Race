<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Assigner temps aux coureurs</h2>
    <form action="<?php echo site_url('Traitement/save_times'); ?>" method="post">
    <table>
        <thead>
            <tr>
                <th>Lieu Etape</th>
                <th>Coureur</th>
                <th>Heure d'arrivée</th>
                <!-- <th>Pénalité</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            $lieu_coureurs = [];
            foreach($affectes as $a) {
                if (is_object($a) && isset($a->Lieucourse)) {
                    if (!isset($lieu_coureurs[$a->Lieucourse])) {
                        $lieu_coureurs[$a->Lieucourse] = [];
                    }
                    $lieu_coureurs[$a->Lieucourse][] = $a;
                }
            }

            foreach($lieu_coureurs as $lieucourse => $coureurs) {
                foreach($coureurs as $coureur) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($lieucourse) . '</td>';
                    if (is_object($coureur) && isset($coureur->coureur)) {
                        echo '<td>' . htmlspecialchars($coureur->coureur) . '</td>';
                        echo '<td><input type="datetime-local" name="data[' . htmlspecialchars($lieucourse) . '][' . htmlspecialchars($coureur->coureur) . '][time]" ></td>';
                        // echo '<td><input type="text" name="data[' . htmlspecialchars($lieucourse) . '][' . htmlspecialchars($coureur->coureur) . '][penalite]" ></td>';
                        echo '<input type="hidden" name="data[' . htmlspecialchars($lieucourse) . '][' . htmlspecialchars($coureur->coureur) . '][numerodossard]" value="' . htmlspecialchars($coureur->numerodossard) . '">';
                        echo '<input type="hidden" name="data[' . htmlspecialchars($lieucourse) . '][' . htmlspecialchars($coureur->coureur) . '][datedenaissance]" value="' . htmlspecialchars($coureur->datedenaissance) . '">';
                        echo '<input type="hidden" name="data[' . htmlspecialchars($lieucourse) . '][' . htmlspecialchars($coureur->coureur) . '][equipe]" value="' . htmlspecialchars($coureur->equipe) . '">';
                        echo '<input type="hidden" name="data[' . htmlspecialchars($lieucourse) . '][' . htmlspecialchars($coureur->coureur) . '][rangetape]" value="' . htmlspecialchars($coureur->rangetape) . '">';
                        echo '<input type="hidden" name="data[' . htmlspecialchars($lieucourse) . '][' . htmlspecialchars($coureur->coureur) . '][genre]" value="' . htmlspecialchars($coureur->genre) . '">';

                    }
                    echo '</tr>';
                }
            }
            ?>
        </tbody>

        
    </table>
    <button type="submit">Enregistrer les temps</button>
</form>
<h2>Resultats</h2>
    <table>
        <tr>
            <th>Rang Etape</th>
            <th>Numero dossard</th>
            <th>Coureur</th>
            <th>Equipe</th>
            <th>Arrivée</th>
            <th>Temps Chrono</th>
            <th>Pénalités</th>
        </tr>
        <?php foreach($results as $a): ?>
        <tr>
            <td><?php echo $a->rangetape; ?></td>
            <td><?php echo $a->numero; ?></td>
            <td><?php echo $a->coureur; ?></td>
            <td><?php echo $a->equipe ?></td>
            <td><?php echo $a->arrivee; ?></td>
            <td><?php echo $a->tempschrono; ?></td>
            <td><?php echo $a->penalite; ?></td>
            
        </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>