<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DatabaseController extends CI_Controller {

public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
}

public function reset_database() {
    $tables = $this->db->list_tables();

    // Suppression des données de la table 'utilisateur' sauf pour les administrateurs
    $this->db->where('role!=', 'admin');
    $this->db->delete('profils');

    // Suppression des données de toutes les autres tables
    foreach ($tables as $table) {
        if ($table!= 'profils') { // Assurez-vous de ne pas supprimer les données de la table 'utilisateur'
            $this->db->query("TRUNCATE TABLE $table");
        }
    }

    redirect('Welcome/admin');
}
}
