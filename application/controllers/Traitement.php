    <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traitement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('traitement_md');
        $this->load->library('session');
        $this->load->helper('url');
        date_default_timezone_set('UTC');
    }

    public function mescoureurs(){
        $nom = $this->session->userdata('nom');
        $data['coureurs'] = $this->traitement_md->getCoureurParEquipe($nom);
        $this->load->view('mescoureurs',$data);
    }

    public function affectation(){
        $nom = $this->session->userdata('nom');
        $data['coureurs'] = $this->traitement_md->getCoureurParEquipe($nom);
        $data['etapes'] = $this->traitement_md->getAllEtapes();

        $data['affectes'] = $this->traitement_md->getEtapeCoureurs();
        $this->load->view('affectercoureur',$data);

    }
    public function getEtapes(){
        $data['etapes']=$this->traitement_md->getAllEtapes();
        $this->load->view('etapes',$data);
    }

    public function insertCoureurEtape(){
        $coureur = $this->input->post('coureurs');
        $etape = $this->input->post('etape');

        $rang=$this->traitement_md->getRang($etape);
        $num = $this->traitement_md->getNumero($coureur);
        $genre=$this->traitement_md->getGenre($coureur);
        $datenaissance = $this->traitement_md->get_coureur_date_naissance($coureur);
        $equipe = $this->traitement_md->getEquipeByCoureur($coureur);

        $this->traitement_md->insert_affectation($coureur,$etape);
        $this->traitement_md->insert_resultats($coureur,$num,$rang,$genre,$datenaissance,$equipe);
        redirect('Welcome/equipe');
    }

    
    //COTE ADMIN
    public function generer_categories() {
        $this->load->model('Traitement_md');
        $this->Traitement_md->assigner_categories();
        redirect('Welcome/admin');
    }
    
    public function assignerTemps(){
        $data['results'] = $this->traitement_md->getClassements();
        $data['affectes'] = $this->traitement_md->getEtapeCoureurs();
        $this->load->view('assignertemps',$data);
    }

    public function save_times() {
        // Charge le modèle pour interagir avec la base de données
    
        // Récupère les données du formulaire
        $data = $this->input->post('data');
    
        // Prépare les données pour l'insertion dans les tables 'temps' et 'resultat'
        $time_inserts = [];
        $result_inserts = [];
        foreach($data as $lieucourse => $coureurs) {
            foreach($coureurs as $coureur => $values) {
                // Vérifiez si les valeurs sont définies et non nulles
                if (isset($values['time']) && isset($values['penalite'])) {
                    // Insertion dans la table 'temps'
                    // $time_inserts[] = [
                    //     'lieu' => $lieucourse,
                    //     'coureur' => $coureur,
                    //     'heurearrivee' => $values['time'],
                    //     'penalite' => $values['penalite']
                    // ];
                    // Insertion dans la table 'resultat'

                    

                    $result_inserts[] = [
                        'rangetape' => $values['rangetape'], 
                        'genre'=>$values['genre'],
                        'coureur' => $coureur,
                        'numero' => $values['numerodossard'],
                        'datedenaissance' => $values['datedenaissance'],
                        'equipe' => $values['equipe'],
                        'arrivee' => $values['time'],
                        // 'penalite' => $values['penalite']

                    ];
                }
            }
        }
    
        // Insère les données dans les deux tables
        // if (!empty($time_inserts)) {
        //     $this->traitement_md->insert_times($time_inserts);
        // }
        if (!empty($result_inserts)) {
            $this->traitement_md->insert_results($result_inserts);
        }
    
        // Redirige ou charge une vue de confirmation
        redirect('Welcome/admin'); // Remplacez par votre page de confirmation
    }
    
    public function classement() {
        $data = $this->traitement_md->get_classement_par_etape();
        $classement_data = $data['classement'];
    
        // Vérifiez que $classement_data est un tableau d'objets
        if (!is_array($classement_data) || empty($classement_data) || !is_object($classement_data[0])) {
            error_log('classement_data is not an array of objects');
            $data['classement'] = [];
            $this->load->view('classement', $data);
            return;
        }
    
        // Group the data by 'nom', 'datedepart', and 'heuredepart'
        $classement = [];
        foreach ($classement_data as $row) {
            $etape_key = $row->nom . ' - ' . $row->datedepart . ' - ' . $row->heuredepart;
            $classement[$etape_key][] = $row;
        }
    
        // Detect ex aequo for each étape
        foreach ($classement as &$etape) {
            $points_count = [];
            foreach ($etape as $row) {
                if (!isset($points_count[$row->points])) {
                    $points_count[$row->points] = 0;
                }
                $points_count[$row->points]++;
            }
    
            // Mark ex aequo
            foreach ($etape as $row) {
                $row->is_ex_aequo = $points_count[$row->points] > 1;
            }
        }
    
        $data['classement'] = $classement;
        $this->load->view('classement', $data);
    }
    
    
    

    // public function classement_general() {
    //     $this->load->model('traitement_md');
    //     $classement_data = $this->traitement_md->get_classement_par_equipe();
    
    //     // Vérifiez que $classement_data est bien un tableau
    //     if (!is_array($classement_data)) {
    //         $classement_data = [];
    //     }
    //     $classement_json = $this->traitement_md->get_classement_par_equipe_json();
    // $data['classement_json'] = $classement_json;
    //     $data['classement'] = $classement_data;
    //     $this->load->view('classement_general', $data);
    // }
    
    // public function classement_mety() {
    //     $data = $this->traitement_md->get_classement_mety();
    
    //     if (isset($data['classement']) && is_array($data['classement'])) {
    //         $classement_data = $data['classement'];
    //     } else {
    //         error_log('classement_data is not properly set or not an array.');
    //         $classement_data = [];
    //     }
    
    //     $classement = [];
    //     foreach ($classement_data as $categorie => $coureurs) {
    //         foreach ($coureurs as $coureur => $details) {
    //             $classement[$categorie][] = [
    //                 'nom' => $coureur,
    //                 'equipe' => $details['equipe'],
    //                 'points' => $details['total_points']
    //             ];
    //         }
    //     }
    
    //     $data['classement'] = $classement;
    //     $this->load->view('classement_mety', $data);
    // }
    public function getClassementCategorie() {
    // Appeler la fonction pour obtenir les classements
    $result = $this->traitement_md->get_classement_par_equipe();

    // Points par position
    $points_by_position = [10, 6, 4, 2];
    
    // Variable pour stocker les résultats avec points à mettre à jour
    $resultats_to_update = [];

    // Traitement des points et détection des ex aequo
    $previous_total_temps = null;
    $rank = 0;

    foreach ($result['classement'] as $row) {
        if (!isset($row->total_temps)) {
            $row->total_temps = 0;  // Assurez-vous que total_temps est défini
        }
        
        // Incrémenter le rang seulement si le temps total est différent
        if ($previous_total_temps !== $row->total_temps) {
            $rank++;
        }

        // Attribution des points
        if ($rank <= count($points_by_position)) {
            $row->points = $points_by_position[$rank - 1];
        } else {
            $row->points = 0;
        }

        // Ajouter le résultat à la liste des résultats à mettre à jour
        $resultats_to_update[] = $row;

        // Mettre à jour le temps précédent
        $previous_total_temps = $row->total_temps;
    }

    // Mettre à jour les points dans la base de données
    $this->traitement_md->update_resultats_points($resultats_to_update);

    // Passer les données à la vue
    $data['points_par_equipe'] = $result['points_par_equipe'];
    $data['classement'] = $result['classement'];

    // Charger la vue avec les données
    $this->load->view('classement_general', $data);
}

    
    
    
    

    public function penalite(){
        $data['etapes'] = $this->traitement_md->getAllEtapes();
        $data['ekip'] = $this->traitement_md->getEquipes();
        $data['class'] = $this->traitement_md->getClassementsPenalite();
        $this->load->view('penalite',$data);
    }

    public function insertPenalite() {
        $etape = $this->input->post('etap');
        $ekip = $this->input->post('ekip');
        $pen = $this->input->post('pen');
    
        // Vérifier si $pen est négatif ou ne correspond pas au format de temps attendu "hh:mm:ss"
        if ($pen < 0 ||!preg_match("/^([0-9]{1,4}):[0-5][0-9]:[0-5][0-9]$/", $pen)) {
            // Gérer l'erreur, par exemple en affichant un message d'erreur à l'utilisateur
            echo "Le format de la pénalité est incorrect.";
            return; // Arrêter l'exécution de la fonction
        }
    
        $this->traitement_md->UpdatePenalite($etape, $ekip, $pen);
        redirect('Traitement/penalite');
    }
    
    
    public function remove_penalty() {
        $id = $this->input->post('id');
        // $equipe = $this->input->post('equipe');

        $this->traitement_md->remove_penalty($id);
        redirect('Traitement/penalite');
    }

    public function tempschrono(){
        
    }
    
}