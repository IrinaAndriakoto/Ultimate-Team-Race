<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {

public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->helper('url');
}

public function index() {
    $this->load->view('importation');
}
}