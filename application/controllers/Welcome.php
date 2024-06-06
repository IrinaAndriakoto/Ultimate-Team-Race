<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('login_md'); // Charger le modèle de connexion
        $this->load->helper('url');
        date_default_timezone_set('UTC');
    }

    public function index() {
        $this->load->view('welcome_message');
    }
    public function authenticate() {
        $nom = $this->input->post('nom');
        $pwd = $this->input->post('motdepasse');
        
        $user = $this->login_md->get_user($nom, $pwd);
    
        if ($user) {
            $this->session->set_userdata('id',$user->id);
            $this->session->set_userdata('nom', $user->nom);
            $this->session->set_userdata('role', $user->role);
    
            if ($user->role == 'admin') {
                $data['nom'] = $user->nom;
                $data['role'] = $user->role;
                redirect('Welcome/admin');
            } elseif ($user->role == 'equipe') {
                $data['nom'] = $user->nom;
                $data['role'] = $user->role;
                redirect('Welcome/equipe');

            }
        } else {
			$this->session->set_flashdata('error', 'Nom d\'utilisateur, mot de passe ou numéro de téléphone incorrect.');
			redirect('Welcome/index');
        }
    }
    
	public function admin() {
		// Vérifier si l'utilisateur est connecté et s'il est admin
		$nom = $this->session->userdata('nom');
		$role = $this->session->userdata('role');
        $this->load->model('traitement_md');
        $data['coureurs'] = $this->traitement_md->getAllCoureurs();
		if ($nom && $role == 'admin') {
			$this->load->view('accueil_admin',$data);
		} else {
			// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté ou s'il n'est pas admin
			redirect('Welcome/index');
		}
	}
    public function equipe() {
		// Vérifier si l'utilisateur est connecté et s'il est admin
		$nom = $this->session->userdata('nom');
		$role = $this->session->userdata('role');
        $this->load->model('traitement_md');
        $data['coureurs'] = $this->traitement_md->getCoureurParEquipe($nom);
        $data['etapes'] = $this->traitement_md->getAllEtapes();
        $data['affectes'] = $this->traitement_md->getEtapeCoureurs();
		if ($nom && $role == 'equipe') {
			$this->load->view('accueil_equipe',$data);
		} else {
			// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté ou s'il n'est pas admin
			redirect('Welcome/index');
		}
	}
    public function logout() {
        $this->session->sess_destroy();
        redirect('Welcome/index');
    }
}
