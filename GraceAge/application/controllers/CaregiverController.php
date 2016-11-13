<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CaregiverController
 *
 * @author orditech
 */
class CaregiverController extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('parser'); //This will allow us to use the parser in function index.
        $this->load->helper('url'); //This allows to use the base_url function for loading the css.
        $this->load->model('Caregiver_Menu_model');
        $this->load->model('Caregiver_Home_model');
    }
    
    function index(){
        $data['show_navbar'] = true;
        $data['page_title'] = 'Caregiver Home';
        $data['header1'] = 'Welcome to Caregiver Home';
        $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems('Algemeen');
        $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
        $data['topics'] = $this->Caregiver_Home_model->get_topics();
        $data['urgent'] = $this->Caregiver_Home_model->get_patients();
        $data['content'] = "This is the home page!";
        $data['page_content']='Caregiver/index.html';
        $this->parser->parse('master.php',$data);
    }
    
    function personal(){
        $data['show_navbar'] = true;
        $data['page_title'] = 'Personal Patient Information';
        $data['menu_items'] = $this->Caregiver_Menu_model->get_menuitems('Persoonlijk');
    }
    
    function tips(){
        $data['show_navbar'] = true;
        $data['page_title'] = 'Tips';
        $data['menu_items'] = $this->Caregiver_Menu_model->get_menuitems('Tips');
    }
    
    function profile(){
        $data['show_navbar'] = true;
        $data['page_title'] = 'Edit Profile';
        $data['menu_items'] = $this->Caregiver_Menu_model->get_menuitems('Profiel');
    }
}
