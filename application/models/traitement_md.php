<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Traitement_md extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        // $this->db->query("SET time_zone = '+03:00'");
    }

    public function getMesCoureurs(){
        $query=$this->db->get('v_coureurs');
        return $query->result();
    }

    public function getAllCoureurs(){
        $query=$this->db->get('coureurs');
        return $query->result();
    }

    public function getAllEtapes(){
        $this->db->select('*');
        $this->db->from('etapes');
    $this->db->order_by('rangetape', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }
    public function getEquipes(){
        $this->db->select('nom');
        $this->db->from('profils');
        $this->db->where('nom != ','admin');
        $query = $this->db->get();
        return $query->result();
    }

    public function getGenre($nom){
        $this->db->select('genre');
        $this->db->from('coureurs');
        $this->db->where('nom', $nom);
        $query = $this->db->get();
        return $query->row()->genre;
    }
    public function getNumero($nom){
        $this->db->select('numerodossard');
        $this->db->from('coureurs');
        $this->db->where('nom', $nom);
        $query = $this->db->get();
        return $query->row()->numerodossard;
    }

    public function get_coureur_date_naissance($nom){
        $this->db->select('datedenaissance');
        $this->db->from('coureurs');
        $this->db->where('nom', $nom);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->datedenaissance;
        } else {
            return null; // Aucun résultat trouvé
        }   
    }
    public function getEquipeByCoureur($nom){
        $this->db->select('equipe');
        $this->db->from('v_coureurs');
        $this->db->where('nom', $nom);
        $query = $this->db->get();
        return $query->row()->equipe;
    }

    public function getCoureurParEquipe($equipe){
        $this->db->select('*');
        $this->db->from('coureurs');
        $this->db->where('equipe', $equipe);
        $query = $this->db->get();
        return $query->result();
    }

    public function getRang($etape){
        $this->db->select('rangetape');
        $this->db->from('etapes');
        $this->db->where('nom', $etape);
        $query = $this->db->get();
        return $query->row()->rangetape;
    }

    public function getPointsTable(){
        $query = $this->db->get('points');
        $result = $query->result();
        
        // Transformer la liste d'objets en tableau associatif pour un accès plus facile
        $pointsTable = [];
        foreach ($result as $row) {
            $pointsTable[$row->classement] = intval($row->points); // S'assurer que les points sont des entiers
        }
        
        return $pointsTable;
    }
    

    public function insert_resultats($coureur,$num,$rang,$genre,$date,$equipe) {
        $data = array(
            // 'etape' => $etape,
            'rangetape' => $rang,
            'numero' =>$num,
            'genre' => $genre,
            'coureur' => $coureur,
            'datedenaissance' => $date,
            'equipe' => $equipe,
            'arrivee' => null
        );
        $this->db->insert('resultats', $data);
    }

    public function insert_affectation($coureur,$etape) {
        $data = array(
            'etape' => $etape,
            'coureur' => $coureur
        );
        $this->db->insert('coureuretape', $data);
    }

    public function getEtapeCoureurs(){
        $query=$this->db->get('v_etapecoureurs');
        return $query->result();
    }
    
    //ADMIN/////////////////

    public function getResults(){
        $query=$this->db->get('resultats');
        return $query->result();
    }

    public function getClassementsPenalite()
{
    $this->db->select('id,nom,longueurkm, equipe,penalite');
    $this->db->from('v_classement');
    $this->db->where('penalite !=','00:00:00');
    $this->db->group_by('id , nom , longueurkm,equipe,penalite,rangetape');
    $this->db->order_by('rangetape', 'ASC');

    $query = $this->db->get();
    return $query->result();
}
public function getClassements()
{
    $this->db->select('*');
    $this->db->from('v_classement');
    $this->db->distinct();
    $this->db->order_by('rangetape', 'ASC');

    $query = $this->db->get();
    return $query->result();
}

    public function insert_times($data) {
        // Utilise la fonction insert_batch pour insérer plusieurs lignes en une seule requête
        $this->db->insert_batch('temps', $data); // Remplacez 'nom_de_votre_table' par le nom de votre table
    }
    public function insert_results($data) {
        $this->db->insert_batch('resultats', $data);
    }

    public function get_start_times() {
        $this->db->select('nom, heuredepart');
        $query = $this->db->get('etapes'); // Remplacez 'etapes' par le nom de votre table des étapes
        $result = $query->result_array();

        $start_times = [];
        foreach($result as $row) {
            $start_times[$row['nom']] = $row['heuredepart'];
        }
        return $start_times;
    }

    public function get_all_times() {
        $this->db->select('lieu, coureur, heurearrivee, penalite');
        $query = $this->db->get('temps'); // Remplacez 'nom_de_votre_table' par le nom de votre table
        $result = $query->result_array();

        $times = [];
        foreach($result as $row) {
            $times[$row['lieu']][] = [
                'coureur' => $row['coureur'],
                'heurearrivee' => $row['heurearrivee'],
                'penalite' => $row['penalite']
            ];
        }
        return $times;
    }

    public function get_coureurs() {
        $query = $this->db->get('v_coureurs');
        return $query->result_array();
    }


    public function assigner_categories() {
        // Récupérer tous les coureurs
        $coureurs = $this->db->get('coureurs')->result();
    
        foreach ($coureurs as $coureur) {
            $categories = [];
            
            $date_naissance = new DateTime($coureur->datedenaissance);
            $now = new DateTime();
            $age = $now->diff($date_naissance)->y;
    
            // Assigner la catégorie en fonction du genre
            if ($coureur->genre == 'Homme' || $coureur->genre == 'H' || $coureur->genre == 'M') {
                $categories[] = 'Homme';
            } elseif ($coureur->genre == 'Femme' || $coureur->genre == 'F') {
                $categories[] = 'Femme';
            }
    
            // Assigner la catégorie junior si le coureur a moins de 18 ans
            if ($age < 18) {
                $categories[] = 'Junior';
            }
            
            // Concaténer les catégories en une seule chaîne
            $categorie_str = implode(', ', $categories);
    
            // Mettre à jour la colonne `categorie` dans la table `coureurs`
            $this->db->set('categorie', $categorie_str)
                     ->where('id', $coureur->id)
                     ->update('coureurs');
        }
    }
    public function update_resultats_points($resultats_to_update) {
        // Commencez une transaction pour garantir la cohérence des mises à jour
        $this->db->trans_start();
    
        foreach ($resultats_to_update as $resultat) {
            // Mettre à jour les points pour chaque résultat
            $this->db->where('rangetape', $resultat->rangetape);
            $this->db->where('coureur', $resultat->coureur);
            $this->db->update('resultats', ['points' => $resultat->points]);
        }
    
        // Terminez la transaction
        $this->db->trans_complete();
    
        // Vérifiez si la transaction a réussi
        if ($this->db->trans_status() === FALSE) {
            // Si la transaction a échoué, annulez toutes les mises à jour
            return false;
        } else {
            return true;
        }
    }
    
    public function get_classement_par_equipe_json() {
        $classement = $this->get_classement_par_equipe();
        return json_encode($classement);
    }

    
    public function get_classement_par_etape() {
        // Requête pour récupérer les informations de classement
        $sql = "
        SELECT 
            nom,
            datedepart,
            heuredepart,
            categorie,
            arrivee,
            coureur,
            equipe,
            rangetape
        FROM 
            v_classement
        ORDER BY 
             arrivee";
        
        $query = $this->db->query($sql);
        $classement = $query->result();
    
        // Charger les points depuis la table 'points'
        $points_query = $this->db->query("SELECT * FROM points ORDER BY points DESC");
        $points_data = $points_query->result();
    
        // Mapping des points par idpoint
        $points_by_id = [];
        foreach ($points_data as $point) {
            $points_by_id[] = $point->points;
        }
    
        // Initialiser les variables pour le classement dense
        $points_par_coureur = [];
        $dense_rank = 1;
        $previous_nom = '';
        $previous_arrivee = null;
    
        foreach ($classement as $key => $row) {
            if ($row->nom !== $previous_nom) {
                $dense_rank = 1; // Réinitialiser le rang dense pour chaque étape
            } else {
                // Incrémenter le rang dense seulement si le temps d'arrivée est différent
                if ($row->arrivee !== $previous_arrivee) {
                    $dense_rank++;
                }
            }
    
            // Mettre à jour les variables précédentes pour la prochaine itération
            $previous_nom = $row->nom;
            $previous_arrivee = $row->arrivee;
    
            // Attribution des points en fonction du rang dense
            if ($dense_rank <= count($points_by_id)) {
                $row->points = $points_by_id[$dense_rank - 1];
            } else {
                $row->points = 0;
            }
    
            // Totaliser les points par coureur
            if (!isset($points_par_coureur[$row->coureur])) {
                $points_par_coureur[$row->coureur] = [
                    'points' => 0,
                    'equipe' => $row->equipe,
                    'categorie' => $row->categorie // Assurez-vous d'inclure la catégorie ici
                ];
            }
            $points_par_coureur[$row->coureur]['points'] += $row->points;
        }
    
        return ['classement' => $classement, 'points_par_coureur' => $points_par_coureur];
    }
    
    public function get_classement_par_equipe() {
        $sql = "
        SELECT rangetape, nom, coureur, equipe, categorie, SUM(tempschrono) AS total_temps
        FROM v_classement
        GROUP BY rangetape, nom, coureur, equipe, categorie
        ORDER BY rangetape, total_temps;";
        
        $query = $this->db->query($sql);
        $classement = $query->result();
    
        // Charger les points depuis la table 'points'
        $points_query = $this->db->query("SELECT * FROM points ORDER BY points DESC");
        $points_data = $points_query->result();
    
        // Mapping des points par idpoint
        $points_by_id = [];
        foreach ($points_data as $point) {
            $points_by_id[] = $point->points;
        }
    
        // Initialiser les variables pour le classement dense
        $points_par_coureur = [];
        $points_par_equipe = [];
        $previous_rangetape = null;
        $previous_total_temps = null;
        $dense_rank = 1;
    
        foreach ($classement as $key => $row) {
            if ($row->rangetape !== $previous_rangetape) {
                $dense_rank = 1; // Réinitialiser le rang dense pour chaque étape
            } else {
                // Incrémenter le rang dense seulement si le temps total est différent
                if ($row->total_temps !== $previous_total_temps) {
                    $dense_rank++;
                }
            }
    
            // Mettre à jour les variables précédentes pour la prochaine itération
            $previous_rangetape = $row->rangetape;
            $previous_total_temps = $row->total_temps;
    
            // Attribution des points en fonction du rang dense
            if ($dense_rank <= count($points_by_id)) {
                $row->points = $points_by_id[$dense_rank - 1];
            } else {
                $row->points = 0;
            }
    
            // Totaliser les points par coureur
            if (!isset($points_par_coureur[$row->coureur])) {
                $points_par_coureur[$row->coureur] = [
                    'points' => 0,
                    'equipe' => $row->equipe,
                    'categorie' => $row->categorie
                ];
            }
            $points_par_coureur[$row->coureur]['points'] += $row->points;
    
            // Totaliser les points par équipe et par catégorie
            if (!isset($points_par_equipe[$row->categorie])) {
                $points_par_equipe[$row->categorie] = [];
            }
            if (!isset($points_par_equipe[$row->categorie][$row->equipe])) {
                $points_par_equipe[$row->categorie][$row->equipe] = [
                    'points' => 0,
                    'coureurs' => []
                ];
            }
            $points_par_equipe[$row->categorie][$row->equipe]['points'] += $row->points;
            $points_par_equipe[$row->categorie][$row->equipe]['coureurs'][] = [
                'coureur' => $row->coureur,
                'points' => $row->points
            ];
        }
    
        return ['classement' => $classement, 'points_par_coureur' => $points_par_coureur, 'points_par_equipe' => $points_par_equipe];
    }
    
    
    
    // public function get_classement_mety() {
    //     $result = $this->get_classement_par_etape();
        
    //     if (empty($result['classement']) || empty($result['points_par_coureur'])) {
    //         error_log('get_classement_par_etape returned empty results.');
    //         return ['classement' => [], 'points_par_coureur' => [], 'points_par_equipe' => []];
    //     }
    
    //     $classement_par_etape = $result['classement'];
    //     $points_par_coureur = $result['points_par_coureur'];
    
    //     // Charger les points depuis la table 'points'
    //     $points_query = $this->db->query("SELECT * FROM points ORDER BY points DESC");
    //     $points_data = $points_query->result();
    
    //     // Mapping des points par idpoint
    //     $points_by_id = [];
    //     foreach ($points_data as $point) {
    //         $points_by_id[] = $point->points;
    //     }
    
    //     // Initialiser une table pour stocker les points par catégorie
    //     $points_par_categorie = [];
    //     $points_par_equipe = [];
    
    //     // Parcourir chaque étape et assigner des points par catégorie
    //     foreach ($classement_par_etape as $etape) {
    //         if (!is_object($etape)) {
    //             error_log('etape is not an object');
    //             continue;
    //         }
    
    //         $categorie = $etape->categorie;
    
    //         if (!isset($points_par_categorie[$categorie])) {
    //             $points_par_categorie[$categorie] = [];
    //         }
    
    //         if (!isset($points_par_categorie[$categorie][$etape->nom])) {
    //             $points_par_categorie[$categorie][$etape->nom] = [];
    //         }
    
    //         $points_par_categorie[$categorie][$etape->nom][] = $etape;
    //     }
    
    //     // Calculer les points par catégorie et par coureur
    //     $classement_final = [];
    //     foreach ($points_par_categorie as $categorie => $etapes) {
    //         foreach ($etapes as $etape_nom => $coureurs) {
    //             // Trier les coureurs par temps d'arrivée
    //             usort($coureurs, function($a, $b) {
    //                 return strcmp($a->arrivee, $b->arrivee);
    //             });
    
    //             $dense_rank = 1;
    //             $previous_arrivee = null;
    
    //             foreach ($coureurs as $index => $coureur) {
    //                 if ($index > 0 && $coureur->arrivee !== $previous_arrivee) {
    //                     $dense_rank++;
    //                 }
    
    //                 if (!isset($classement_final[$categorie][$coureur->coureur])) {
    //                     $classement_final[$categorie][$coureur->coureur] = [
    //                         'total_points' => 0,
    //                         'equipe' => $coureur->equipe
    //                     ];
    //                 }
    
    //                 if ($dense_rank <= count($points_by_id)) {
    //                     $classement_final[$categorie][$coureur->coureur]['total_points'] += $points_by_id[$dense_rank - 1];
    //                 } else {
    //                     $classement_final[$categorie][$coureur->coureur]['total_points'] += 0;
    //                 }
    
    //                 // Mettre à jour les points par équipe dans cette catégorie
    //                 if (!isset($points_par_equipe[$categorie][$coureur->equipe])) {
    //                     $points_par_equipe[$categorie][$coureur->equipe] = 0;
    //                 }
    //                 $points_par_equipe[$categorie][$coureur->equipe] += $classement_final[$categorie][$coureur->coureur]['total_points'];
    
    //                 $previous_arrivee = $coureur->arrivee;
    //             }
    //         }
    //     }
    
    //     return ['classement' => $classement_final, 'points_par_coureur' => $points_par_coureur, 'points_par_equipe' => $points_par_equipe];
    // }
        
    public function testCategorie(){
        // Requête pour récupérer les informations de classement
        $sql = "
        select rangetape,nom,coureur,sum(tempschrono) from v_classement group by rangetape,coureur; ";
        
        $query = $this->db->query($sql);
        $classement = $query->result();
    
        // Charger les points depuis la table 'points'
        $points_query = $this->db->query("SELECT * FROM points ORDER BY points DESC");
        $points_data = $points_query->result();
    
        // Mapping des points par idpoint
        $points_by_id = [];
        foreach ($points_data as $point) {
            $points_by_id[] = $point->points;
        }
    
        // Initialiser les variables pour le classement dense
        $points_par_coureur = [];
        $dense_rank = 1;
        $previous_nom = '';
        $previous_arrivee = null;
    
        
        foreach ($classement as $key => $row) {
            if ($row->tempschrono !== $previous_nom) {
                $dense_rank = 1; // Réinitialiser le rang dense pour chaque étape
            } else {
                // Incrémenter le rang dense seulement si le temps d'arrivée est différent
                if ($row->coureur !== $previous_arrivee) {
                    $dense_rank++;
                }
            }
    
            // Mettre à jour les variables précédentes pour la prochaine itération
            $previous_nom = $row->tempschrono;
            $previous_arrivee = $row->coureur;
    
            // Attribution des points en fonction du rang dense
            if ($dense_rank <= count($points_by_id)) {
                $row->points = $points_by_id[$dense_rank - 1];
            } else {
                $row->points = 0;
            }
    
            // Totaliser les points par coureur
            if (!isset($points_par_coureur[$row->coureur])) {
                $points_par_coureur[$row->coureur] = [
                    'points' => 0,
                    'equipe' => $row->equipe,
                    'categorie' => $row->categorie // Assurez-vous d'inclure la catégorie ici
                ];
            }
            $points_par_coureur[$row->coureur]['points'] += $row->points;
        }
    
        return ['classement' => $classement, 'points_par_coureur' => $points_par_coureur];
    }
    
    public function UpdatePenalite($etape, $equipe, $pen){
        $this->db->set('penalite', $pen)
                 ->where('rangetape', $etape)
                 ->where('equipe', $equipe)
                 ->update('resultats');
    }

    public function remove_penalty($id) {
        $this->db->set('penalite', '00:00:00');
        $this->db->where('penalite', $id);
        // $this->db->where('equipe', $equipe);
        return $this->db->update('resultats'); // Assurez-vous d'utiliser la bonne table
    }
    
    public function calculTempsChrono(){
        
    }
}

