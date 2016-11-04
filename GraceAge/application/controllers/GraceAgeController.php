<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GraceAgeController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->helper('url');
    }
    
    public function index() {
        $this->load->database();
        $this->load->view('home.html');
    }
    
    public function questionnaire() {
        $this->load->database();
        $query = $this->db->query("SELECT Question FROM Question");
        $data['questions'] = $query->result_array();
        $this->parser->parse('questionnaire.php', $data);
    }
    
    public function whoAmI() {
        $this->load->view('whoAmI.html');
    }
    
    public function quit() {
        $this->load->view('authentication.html');
    }
}