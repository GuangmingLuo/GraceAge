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
        $this->load->database();
        $this->load->library('session');
        $this->load->library('parser'); //This will allow us to use the parser in function index.
        $this->load->helper('url'); //This allows to use the base_url function for loading the css.
        $this->lang->load('Caregiver', $this->session->Language); // loading dutch, but we need to actually check with db setting
        $this->load->model('Caregiver_Menu_model');
        $this->load->model('Caregiver_Home_model');
    }
    
    function index(){
        if ($this->session->userType == "Caregiver") { // if session exists
            $data['show_navbar'] = true;
            $data['page_title'] = 'Caregiver Home';
            $data['header1'] = 'Welcome to Caregiver Home';
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems('Algemeen');
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['topics'] = $this->Caregiver_Home_model->get_topics();
            $data['urgent'] = $this->Caregiver_Home_model->get_patients();
            //$data['content'] = "This is the home page!";
            $data['content'] = "";
            $data['page_content']='Caregiver/index.html';
            $this->parser->parse('master.php',$data);
        }else {
            echo "You are not allowed to access this page!!!";
        }
    }
    
    function personal(){
        if ($this->session->userType == "Caregiver") { // if session exists
            $data['show_navbar'] = true;
            $data['page_title'] = 'Personal Patient Information';
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems('Persoonlijk');
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['content'] = lang('hello'); //to check whether internationalization set up works
            $data['page_content'] = 'Caregiver/template.html';
            $this->parser->parse('master.php',$data);
        }else {
            echo "You are not allowed to access this page!!!";
        }
    }
    
    function tips(){
        if ($this->session->userType == "Caregiver") { // if session exists
            $data['show_navbar'] = true;
            $data['page_title'] = 'Tips';
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems('Tips');
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['content'] = "Tips to be added.";
            $data['page_content'] = 'Caregiver/template.html';
            $this->parser->parse('master.php',$data);
        }else {
            echo "You are not allowed to access this page!!!";
        }
    }
    
    function profile(){
        if ($this->session->userType == "Caregiver") {
            $data['change_password'] = $this->lang->line('caregiver_change_password');
            $data['old_password'] = $this->lang->line('caregiver_old_password');
            $data['new_password'] = $this->lang->line('caregiver_new_password');
            $data['conf_password'] = $this->lang->line('caregiver_conf_password');
            $data['change_lang'] = $this->lang->line('caregiver_change_lang');
            $data['Apply'] = $this->lang->line('caregiver_apply');
            $data['show_navbar'] = true;
            $data['page_title'] = 'Edit Profile';
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems('Profiel');
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['page_content'] = 'Account/profile.html';
            $data['Person_Name'] = $this->Caregiver_Home_model->get_name($this->session->idCaregiver);
            $this->parser->parse('master.php', $data);
        }
        else {
            echo "You are not allowed to access this page!!!";
        }
    }
    
    function change_language(){
        $newlang = $this->input->post('language');
        if(isset($newlang)){
            $this->session->set_userdata('Language', $newlang);
            $this->db->query("UPDATE a16_webapps_2.Caregiver "
                        . "SET Language ='".$newlang."' "
                        . "WHERE idCaregiver = " .$this->session->idCaregiver .";");
        }
        redirect(base_url() . 'CaregiverController/profile');
    }
    
    function change_password(){
        $username = $this->Caregiver_Home_model->get_name($this->session->idCaregiver);
        //echo $username;
        $verif = $this->db->query("SELECT password, Name FROM a16_webapps_2.Caregiver WHERE Name = '" . $username."';")->row();
        $old = filter_input(INPUT_POST, 'old_password');
        $new = $this->input->post('new_password');
        $conf = $this->input->post('conf_password');
        if($conf === $new){
            echo "passwords equal";
            if(password_verify($old, $verif->password)){
                $password = password_hash($new, PASSWORD_DEFAULT);
                echo " -> should update";
                $this->db->query("UPDATE a16_webapps_2.Caregiver "
                        . "SET Name ='".$username."' , password = '".$password."' "
                        . "WHERE idCaregiver = " .$this->session->caregiver_id .";");
            }
        }
        $this->profile();
    }
}
