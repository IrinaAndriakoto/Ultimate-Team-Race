<?php
defined('BASEPATH') or exit('No direct script access allowed');

class csvimport
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        date_default_timezone_set('UTC');
    }

    public function import_data_points($file_path_points)
    {
        if (!file_exists($file_path_points)) {
            return false;
        }
    
        $file_content = file_get_contents($file_path_points);
    
        if (empty($file_content)) {
            return false;
        }

        $csv_lines = explode(PHP_EOL, $file_content);

        for ($i = 1; $i < count($csv_lines); $i++) {
            $line = $csv_lines[$i];

            // Ignorer les lignes vides
            if (!empty($line)) {
                // Exploder chaque ligne en un tableau de valeurs CSV
                $csv_values = str_getcsv($line);

                // Insérer les valeurs dans la table pointss
                $points_data = array(
                    'classement' => $csv_values[0],
                    'points' => $csv_values[1], 
                );

                $this->CI->db->insert('points', $points_data);

                // Insérer les valeurs dunans la table travaux
                // $travaux_data = array(
                //     'id' => $id_tv,
                //     'designation' => $csv_values[4], // Colonne 'type_travaux'
                //     'code_travaux' => $csv_values[3], // Colonne 'code_travaux'
                //     'unite' => $csv_values[5],
                //     'duree' => $csv_values[8]
                // );

                // $this->CI->db->insert('test_import_travaux', $travaux_data);

                // $maison_travaux_data = array(
                //     'id_maison' => $this->CI->db->insert_id(),
                //     'id_travaux' => $this->CI->db->insert_id() 
                // );

                // $this->CI->db->insert('maison_travaux', $maison_travaux_data);
            }
        }

        return true;
    }

    private function convert_date_format($date_str) {
        // Supposons que le format initial soit 'DD/MM/YYYY' et que nous voulons 'YYYY-MM-DD'
        $date = DateTime::createFromFormat('d/m/Y', $date_str);
        return $date ? $date->format('Y-m-d') : null;
    }

    public function convert_datetime_format($date_string){
        if (!empty($date_string)) {
            $datetime = DateTime::createFromFormat('d/m/Y H:i:s', $date_string);
            if ($datetime !== false) {
                return $datetime->format('Y-m-d H:i:s');
            }
        }
        return null;
    }

    public function import_data_etape($file_path_devis)
    {
        if (!file_exists($file_path_devis)) {
            return false;
        }
    
        $file_content = file_get_contents($file_path_devis);
    
        if (empty($file_content)) {
            return false;
        }

        $csv_lines = explode(PHP_EOL, $file_content);

        for ($i = 1; $i < count($csv_lines); $i++) {
            $line = $csv_lines[$i];

            if (!empty($line)) {
                $csv_values = str_getcsv($line);

                $devis_data = array(
                    'id' => $i,
                    'nom' => $csv_values[0],
                    'longueurkm' => $csv_values[1],
                    'nbcoureurparequipe' => $csv_values[2],
                    'rangetape' => $csv_values[3],
                    'datedepart' => $this->convert_date_format($csv_values[4]),
                    'heuredepart' => $csv_values[5]
                );

                $this->CI->db->insert('etapes', $devis_data);
            }
        }
        return true;
    }

    public function import_data_result($file_path_detail_travaux)
    {
        if (!file_exists($file_path_detail_travaux)) {
            return false;
        }
    
        $file_content = file_get_contents($file_path_detail_travaux);
    
        if (empty($file_content)) {
            return false;
        }

        $csv_lines = explode(PHP_EOL, $file_content);

        for ($i = 1; $i < count($csv_lines); $i++) {
            $line = $csv_lines[$i];

            if (!empty($line)) {
                $csv_values = str_getcsv($line);

                $detail_travaux_data = array(
                    'id' => $i,
                    'rangetape' => $csv_values[0],
                    'numero' => $csv_values[1],
                    'coureur' => $csv_values[2],
                    'genre' => $csv_values[3],
                    'datedenaissance' => $this->convert_date_format($csv_values[4]),
                    'equipe' => $csv_values[5],
                    'arrivee' => $this->convert_datetime_format($csv_values[6])
                );

                $data_coureurs = array(
                    'id' => $i,
                    'nom' => $csv_values[2],
                    'numerodossard' => $csv_values[1],
                    'genre' => $csv_values[3],
                    'datedenaissance' => $this->convert_date_format($csv_values[4]),
                    'equipe' => $csv_values[5]
                );

                $sql_coureurs = "INSERT INTO \"coureurs\" (\"id\", \"nom\", \"numerodossard\", \"genre\", \"datedenaissance\", \"equipe\") 
                VALUES (?,?,?,?,?,?) 
                ON CONFLICT (\"nom\", \"numerodossard\") DO UPDATE SET 
                \"id\" = EXCLUDED.\"id\", \"genre\" = EXCLUDED.\"genre\", \"datedenaissance\" = EXCLUDED.\"datedenaissance\", \"equipe\" = EXCLUDED.\"equipe\";";
                                
                
                $data_profils = array(
                    'id' => $i+1,
                    'role' => 'equipe',
                    'nom' => $csv_values[5],
                    'pwd' => $csv_values[5]
                );

                $sql_profils = "INSERT INTO \"profils\" (\"id\", \"role\", \"nom\", \"pwd\") "
                . "VALUES (?,?,?,?) "
                . "ON CONFLICT (\"nom\",\"role\") DO UPDATE SET "
                . "\"nom\" = EXCLUDED.\"nom\", \"role\" = EXCLUDED.\"role\", \"pwd\" = EXCLUDED.\"pwd\" ";
                        

                // Check for duplicates before inserting
                    $this->CI->db->insert('resultats', $detail_travaux_data);
                    $this->CI->db->query($sql_coureurs, array_values($data_coureurs));
                    $this->CI->db->query($sql_profils, array_values($data_profils));
            }
        }
        return true;
    }

    // public function import_data_paiements($file_path_paiements)
    // {
    //     if (!file_exists($file_path_paiements)) {
    //         return false;
    //     }
    
    //     $file_content = file_get_contents($file_path_paiements);
    
    //     if (empty($file_content)) {
    //         return false;
    //     }

    //     $csv_lines = explode(PHP_EOL, $file_content);

    //     for ($i = 1; $i < count($csv_lines); $i++) {
    //         $line = $csv_lines[$i];

    //         if (!empty($line)) {
    //             $csv_values = str_getcsv($line);

    //             $paiements_data = array(
    //                 'date_paiement' => $csv_values[0],
    //                 'montant' => $csv_values[1],
    //                 'client_id' => $csv_values[2],
    //                 'devis_id' => $csv_values[3]
    //             );

    //             $this->CI->db->insert('Paiements', $paiements_data);
    //         }
    //     }
    //     return true;
    // }
}
