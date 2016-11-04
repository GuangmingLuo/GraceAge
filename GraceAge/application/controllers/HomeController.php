<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('parser');
    }
    
    public function index() {
        //check the user role and call the home function for that specifc user role
        $this->load->view('Home/index.html');
    }
    
    //go to this url to see the result:
    //http://localhost/GraceAge/index.php/HomeController/elderlyHome
    
    public function elderlyHome() {
        $this->load->view('Home/elderlyHome.html');
    }
    
    public function caregiverHome() {
        $this->load->view('Home/caregiverHome.html');
    }
    
}