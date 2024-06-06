<!DOCTYPE HTML>
<html>
<head>
    <title>Homepage</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" href="../../assets/main.css" />
    <script src="<?php echo base_url('assets/js/chart.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>
<style>
    .insert {
        display: none;
    }

    .insert.active {
        display: block;
    }

    .insert a {
        display: block;
        padding: 10px;
        text-decoration: none;
        color: #333;
        margin-bottom: 10px;
        border-radius: 5px;
        background-color: #ddd;
    }

    .insert a.active {
        background-color: #007bff;
        color: #ddd;
    }

    .cnv{
        width:300px;
        height: 100px;
    }

    .ambony{
        display: flex;
        height: 50px;
        padding: 5px;
    }
    .ambony_sp{
        margin-left: 150px;
    }

    .img {
    background-image: url('../../assets/img/logorun.png');
    background-size: contain;
    height: 300px; /* Ajustez à la taille souhaitée */
    width: 300px; /* Ajustez à la taille souhaitée */
    position: relative;
    /* margin-bottom:50px ; */

}

.innere {
    width: calc(100% - 330px); /* Adjust based on your sidebar width */
    height: 100%; /* Full height of the viewport */
    box-sizing: border-box;
    padding: 5%;
    margin-left: 325px;
    /* background-col?or: #007bff; */
    /* Include padding and border in the element's total width and height */
}

#sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 330px; /* Adjust based on your design */
    height: 100%; /* Full height of the viewport */
    overflow-y: auto; /* Enable vertical scrolling if content overflows */
}
</style>
<body class="is-preload">
<!-- Wrapper -->
<div id="wrapper">
    <!-- Main -->
    <div id="main">
        <div class="innere">
            <!-- Header -->
            <header id="header">
                <a href="<?php echo site_url('Welcome/admin') ?>" class="logo"><strong>Ultimate Team Race.</strong></a>
            </header>

            <!-- Section --> <br>
            <h4 style='text-decoration: underline;'>Nos Services : </h4>
            <div class="ambony">
                <span class="ambony_sp"><a href="#" class="load-content" data-url="<?php echo site_url('Traitement/classement') ?>">Classement des coureurs par etape</a> </span>
                <span class="ambony_sp"><a href="<?php echo site_url('Traitement/getClassementCategorie') ?>">Classement général par equipe</a> </span>
                <span class="ambony_sp"><a href="<?php echo site_url('Traitement/penalite') ?>">Pénalités</a> </span>
                <span class="ambony_sp"><a href="<?php echo site_url('Map/index') ?>">Map</a> </span>

                
            </div> <hr>
            <div class="canvasss">

                <!-- Autres éléments de la page... -->
                <h2>Gestion des coureurs</h2>
                    <h3>Gérer les catégories</h3>
                    <form action="<?php echo site_url('Traitement/generer_categories'); ?>" method="post">
                        <button type="submit">Générer catégories</button>
                    </form>
                    <h3>La liste des coureurs</h3>
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
                            <td><strong><?php echo $cr->nom; ?></strong></td>
                            <td><?php echo $cr->categorie; ?></td>
                            <td><?php echo $cr->numerodossard; ?></td>
                            <td><?php echo $cr->genre; ?></td>
                            <td><?php echo $cr->datedenaissance; ?></td>
                            <td><?php echo $cr->equipe; ?></td>

                        </tr>
                        <?php endforeach; ?>
                    </table>
            </div>
            <div class="content">
        
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="inner">
            <!-- Menu -->
            <nav id="menu">
                <div class="img"></div>
                <header class="major">
                    <h2>Menu</h2>
                </header>
                <ul>
                    <li><a href="#" class="load-content" data-url="<?php echo site_url('Traitement/getEtapes') ?>">Liste des etapes</a></li>
                    <li><a href="#" class="load-content" data-url="<?php echo site_url('Traitement/assignertemps') ?>">Affectation temps</a></li>
                    <li><a href="#" class="load-content" data-url="<?php echo site_url('Import/index') ?>">Importation</a></li>
                    
<br> <br>
                    <li><a href="#" class="load-content" data-url="<?php echo site_url('DatabaseController/reset_database') ?>">Reinitialiser la base de données</a></li>
                    <li><a href="<?php echo site_url('Welcome/logout') ?>">Déconnexion</a></li>
                </ul>
                
            </nav>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les liens avec la classe load-content
    const loadContentLinks = document.querySelectorAll('.load-content');

    // Sélectionner le div avec la classe canvasss
    const canvasDiv = document.querySelector('.canvasss');

    // Boucler sur chaque lien et ajouter un écouteur d'événement de clic
    loadContentLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            // Empêcher le comportement de lien par défaut
            event.preventDefault();

            // Masquer le div avec la classe canvasss
            canvasDiv.style.display = 'none';

            // Récupérer l'URL de données à charger depuis l'attribut data-url du lien
            const url = link.getAttribute('data-url');

            // Effectuer une requête AJAX pour récupérer le contenu de l'URL
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Mettre à jour le contenu du div content avec le contenu récupéré via AJAX
                    document.querySelector('.content').innerHTML = html;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération du contenu:', error);
                });
        });
    });
});


</script>
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Fev', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aou', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Montant des devis',
            data: [
                <?php if (!empty($m)): ?>
                    <?php foreach ($m as $montant): ?>
                        <?php echo $montant['total']; ?>,
                    <?php endforeach; ?>
                <?php endif; ?>
            ],
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
			<!-- <script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script> -->
</body>
</html>
