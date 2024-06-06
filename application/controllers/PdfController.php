<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'helpers/tcpdf/tcpdf.php'); // Inclure la bibliothèque TCPDF

class PdfController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('traitement_md');
        $this->load->helper('url');
        $this->load->library('tcpdf');
        date_default_timezone_set('UTC');
    }

    public function generate_pdf($encoded_category) {
        // Decode the category name
        $category = urldecode($encoded_category);

        // Créer une nouvelle instance de TCPDF
        $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Définir les informations du document
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Votre Nom');
        $pdf->SetTitle('Vainqueur de la catégorie');
        $pdf->SetSubject('Carte de visite');
        $pdf->SetKeywords('TCPDF, PDF, carte de visite');

        // Supprimer l'en-tête et le pied de page par défaut
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Ajouter une page au document
        $pdf->AddPage();

        // Définir l'image de fond
        // $img_file = base_url('assets/img/logorun.png');
        // $pdf->Image($img_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);

        // Obtenir les données du classement
        $classement_data = $this->traitement_md->get_classement_par_equipe();

        // Vérifier si la catégorie existe et obtenir les informations du vainqueur
        $winner = '';
        $winner_team = '';
        $winner_points = 0;
        if (isset($classement_data[$category]) && !empty($classement_data[$category])) {
            $first_team = reset($classement_data[$category]); // Récupérer la première équipe
            if (!empty($first_team['coureurs'])) {
                $winner_data = reset($first_team['coureurs']); // Récupérer les données du premier coureur
                $winner = $winner_data['nom'];
                $winner_team = key($classement_data[$category]); // Récupérer le nom de l'équipe
                $winner_points = $winner_data['points'];
            }
        }

        // Définir le contenu HTML
        $html = '<style>
                    .card {
                        text-align: center;
                        padding: 30px;
                        border-radius: 15px;
                        margin: 100px auto;
                        width: 350px;
                        background-color: rgba(255, 255, 255, 0.9); /* Couleur de fond blanche semi-transparente */
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Ombre portée */
                        font-family: Arial, sans-serif; /* Police de caractère */
                    }

                    .card h1 {
                        color: #333;
                        font-size: 28px;
                        margin-bottom: 10px;
                    }

                    .card p {
                        color: #666;
                        margin-bottom: 8px;
                        font-size: 16px;
                    }

                    .card .winner-info {
                        margin-top: 20px;
                    }

                    .card .winner-info p {
                        color: #444;
                        font-size: 18px;
                    }
                </style>';

        $html .= '<div class="card">
                    <h1>' . htmlspecialchars($category) . '</h1>
                    <div class="winner-info">
                        <p><strong>Vainqueur :</strong> ' . htmlspecialchars($winner) . '</p>
                        <p><strong>Équipe :</strong> ' . htmlspecialchars($winner_team) . '</p>
                        <p><strong>Points :</strong> ' . htmlspecialchars($winner_points) . '</p>
                        <p><strong>Catégorie :</strong> ' . htmlspecialchars($category) . '</p>
                    </div>
                </div>';

        // Ajouter le contenu HTML au PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Enregistrer le PDF dans un fichier et le télécharger
        $pdf->Output('vainqueur_' . $category . '.pdf', 'D');
    }
}
?>
