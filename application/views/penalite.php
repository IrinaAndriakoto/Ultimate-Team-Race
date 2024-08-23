<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une pénalité</title>
    <link rel="stylesheet" href="../../assets/main.css" />

    <style>
        /* Styles pour le bouton "Ajouter une pénalité" */
        #openOverlayBtn {
            position: fixed;
            /* left: 0; */
            /* top: 50%; */
            /* margin-top: 300px; */
            margin-left: 100px;
            /* margin-left: 50px; */
            /* transform: translateY(-50%); */
            z-index: 999; /* Assure que le bouton soit au-dessus du contenu */
        }

        #returnBtn {
            position: fixed;
            /* left: 0; */
            /* top: 50%; */
            margin-top: 70px;
            margin-left: 100px;
            /* margin-left: 50px; */
            /* transform: translateY(-50%); */
            z-index: 999; /* Assure que le bouton soit au-dessus du contenu */
        }

        /* Styles pour le conteneur du tableau */
        .table-container {
            position: fixed;
            right: 0;
            top: 0;
            bottom: 0;
            width: calc(100% - 200px); /* Largeur du conteneur (laisser de l'espace pour le bouton) */
            max-width: 1100px; /* Largeur maximale du conteneur */
            margin: 0 auto; /* Centrage horizontal */
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow-x: auto; /* Ajouter une barre de défilement horizontale si nécessaire */
            padding: 10px;
        }

        /* Styles pour le titre "Pénalités" */
        h2#penalites-title {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Styles pour le tableau */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Styles pour l'overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .overlay-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s ease; /* Animation fadeIn */
        }

        /* Animation pour l'overlay */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        .fadeOut {
            animation: fadeOut 0.3s ease forwards;
        }

        .confirmation {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .overlay-content.adding {
            opacity: 0;
            transform: scale(0.8);
        }

        .button-5 {
  align-items: center;
  background-clip: padding-box;
  /* background-color: #AF4F41; */
  border: 1px solid transparent;
  border-radius: .25rem;
  box-shadow: inset 0 0 0 2px #AF4F41;
  box-sizing: border-box;
  /* color: #fff; */
  cursor: pointer;
  display: inline-flex;
  /* font-family: system-ui,-apple-system,system-ui,"Helvetica Neue",Helvetica,Arial,sans-serif; */
  font-size: 11px;
  font-weight: 600;
  justify-content: center;
  line-height: 1.25;
  margin: 0;
  min-height: 3rem;
  padding: 0 2.25em;
  position: relative;
  text-decoration: none;
  transition: all 250ms;
  user-select: none;
  -webkit-user-select: none;
  touch-action: manipulation;
  vertical-align: baseline;
  width: 170px;
  height: 40px;
  color: #E9118F !important;

}

.button-5:hover,
.button-5:focus {
  background-color: rgba(245, 106, 106, 0.05);
  box-shadow: rgba(0, 0, 0, 0.1) 0 4px 12px;
}

.button-5:hover {
  transform: translateY(-1px);
}

.button-5:active {
  background-color: #c85000;
  box-shadow: rgba(0, 0, 0, .06) 0 2px 4px;
  transform: translateY(0);
}

.img {
    background-image: url('../../assets/img/logorun.png');
    background-size: contain;
    height: 400px; /* Ajustez à la taille souhaitée */
    width: 400px; /* Ajustez à la taille souhaitée */
    position: relative;
    margin-left:25px ;
    margin-top: 60px;

}
    </style>
</head>
<body> 
    <div class="img"></div>
    <!-- Bouton "Ajouter une pénalité" à gauche au milieu de la partie gauche de l'écran -->
    <button id="openOverlayBtn">Ajouter une pénalité</button>
    <button id="returnBtn"><a href="<?php echo site_url('Welcome/admin') ?>">Retour à l'accueil</a></button>

    <!-- Conteneur du tableau à droite -->
    <div class="table-container">
        <!-- Titre "Pénalités" -->
        <h2 id="penalites-title">Pénalités</h2>
        
        <!-- Tableau -->
        
        <table>
                <tr>
                    <th>Lieu Etape</th>
                    <th>Longueur</th>
                    <th>Equipe</th>
                    <th>Temps de penalite</th>
                    <th></th>
                </tr>
                <?php foreach($class as $a): ?>
            <tr>
                <td><?php echo $a->nom; ?></td>
                <td><?php echo $a->longueurkm; ?></td>
                <td><?php echo $a->equipe; ?></td>
                <td><?php echo $a->penalite; ?></td>
                <td>
                    <form action="<?php echo site_url('Traitement/remove_penalty') ?>" method="post">
                        <button class="button-5 remove-penalty-btn" type="submit">Enlever penalite</button></td>
                        <input type="hidden" name="id" value="<?php  echo $a->penalite; ?>">
                        <!-- <input type="hidden" name="equipe"> -->
                    </form>
                <?php endforeach; ?>
            </tr>
        </table>
    </div>
    <!-- Overlay pour le formulaire -->
<!-- Overlay pour le formulaire -->
<div class="overlay" id="overlay">
    <div class="overlay-content">
        <h2>Ajouter une pénalité</h2>
        <form action="<?php echo site_url('Traitement/insertPenalite'); ?>" method="post">
            <label for="lieuEtape">Lieu Etape:</label>
            <select id="lieuEtape" name="etap">
                    <?php foreach($etapes as $a): ?>
                        <option value="<?php echo $a->rangetape ?>">
                            <?php echo $a->nom ?>
                        </option>
                    <?php endforeach; ?>
            </select>
            <br><br>
            <label for="equipe">Équipe:</label>
            <select id="equipe" name="ekip">
                    <?php foreach($ekip as $a): ?>
                        <option value="<?php echo $a->nom ?>">
                            <?php echo $a->nom ?>
                        </option>
                    <?php endforeach; ?>
            </select> <br> <br>
            <label for="penali">Pénalité</label>
            <input type="text" name="pen">
            <br><br>
            <button type="button" id="firstAddBtn">Ajouter</button> <!-- Premier bouton "Ajouter" -->
            <button id="closeOverlayBtn">Retour</button>
        </form>
    </div>
</div>


    <script>
        // Fonction pour ouvrir l'overlay
        document.getElementById('openOverlayBtn').addEventListener('click', function() {
            document.getElementById('overlay').classList.add('fadeIn');
            document.getElementById('overlay').style.display = 'block';
        });

        // Fonction pour fermer l'overlay
        document.getElementById('closeOverlayBtn').addEventListener('click', function() {
            document.getElementById('overlay').classList.add('fadeOut');
            setTimeout(function() {
                document.getElementById('overlay').style.display = 'none';
                document.getElementById('overlay').classList.remove('fadeOut');
            }, 300);
        });

// Fonction pour afficher les boutons de confirmation après le deuxième clic sur "Ajouter"
document.getElementById('firstAddBtn').addEventListener('click', function() {
    // Modify the text and action of the first "Add" button
    const confirmText = "Confirm"; // Text of the "Confirm" button
    this.textContent = confirmText; // Replace the button content with the text "Confirm"
    this.id = "confirmBtn"; // Change the ID of the button
    this.style="background-color:black";
    this.removeEventListener('click', arguments.callee); 

    // Add an event listener for the "Confirm" button

});

// Fonction pour le bouton de confirmation
document.addEventListener('click', function(event) {
    if (event.target.id === 'confirmBtn') {
        // Insérer le code ici pour traiter la confirmation
    }
});
    </script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.remove-penalty-btn').click(function() {
        var nom = $(this).data('nom');
        var equipe = $(this).data('equipe');

        $.ajax({
            url: '',
            type: 'POST',
            data: {
                nom: nom,
                equipe: equipe
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Pénalité enlevée avec succès');
                    location.reload(); // Rafraîchir la page pour voir les changements
                } else {
                    alert('Erreur lors de la suppression de la pénalité : ' + result.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Erreur lors de la requête AJAX : ' + error);
                console.error(xhr);
            }
        });
    });
});
</script> -->



</body>
</html>
