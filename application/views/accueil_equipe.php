<!DOCTYPE HTML>
<html>
	<head>
		<title>Homepage</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../../assets/main.css" />
	<link rel="shortcut icon" href="../../assets/img/logorun.png" type="image/x-icon">

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
            background-color: #AF4F41;
            color: #ddd;
        }
        .cnv{
            width:300px;
            height: 100px;
        }

        .img {
    background-image: url('../../assets/img/logorun.png');
    background-size: contain;
    height: 280px; /* Ajustez à la taille souhaitée */
    width: 300px; /* Ajustez à la taille souhaitée */
    position: relative;
    /* margin-top:10px ; */

}
.innere {
    width: calc(100% - 330px); /* Adjust based on your sidebar width */
    height: 100%; /* Full height of the viewport */
    box-sizing: border-box;
    padding: 5%;
    margin-left: 325px;
    /* margin-bottom: 50px; */
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
									<a href="<?php echo site_url('Welcome/equipe') ?>" class="logo"><strong>Ultimate Team Race.</strong></a>
								</header>

							<!-- Section -->
                            <div class="canvasss"> <br> <br>

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
                                            <?php foreach($etapes as $c): ?>
                                                <option value="<?php echo $c->nom ?>"><?php echo $c->nom ?> </option>
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
                            </div> <br> <br> <br>
									<div class="content">
                        
                        </div>
								
						</div>
					</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">
							<!-- Menu -->
								<nav id="menu">
									<header class="major">
										<h2>Menu</h2>
									</header>
									<ul>
                                    <li><a href="#" class="load-content" data-url="<?php echo site_url('Traitement/mescoureurs') ?>">Mes Coureurs</a></li>
                                    <li><a href="#" class="load-content" data-url="<?php echo site_url('Traitement/getEtapes') ?>">La liste des etapes</a></li>
                                    <li><a href="#" class="load-content" data-url="<?php echo site_url('Traitement/affectation') ?>">Affectation de coureur</a></li>
                                    <li><a href="#" class="load-content" data-url="<?php echo site_url('Traitement/classement') ?>">Classement des coureurs par etape</a></li>
                                    <li><a href="#" class="load-content" data-url="<?php echo site_url('Traitement/classement_general') ?>">Classement général par equipe</a></li>
										<li><a href="<?php echo site_url('Welcome/logout') ?>">Déconnexion</a></li>
									</ul>
                                    <div class="img"></div>
								</nav>
						</div>
					</div>

			</div>
                            
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
	</body>
</html>