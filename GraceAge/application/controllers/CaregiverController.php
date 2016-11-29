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
class CaregiverController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('parser'); //This will allow us to use the parser in function index.
        $this->load->helper('url'); //This allows to use the base_url function for loading the css.
        $this->lang->load('caregiver', $this->session->Language);
        $this->lang->load('caregiver_menu', $this->session->Language);
        $this->load->model('Caregiver_Menu_model');
        $this->load->model('Caregiver_Home_model');
    }

    function index() {
        if ($this->session->userType == "Caregiver") { // if session exists
            $data['show_navbar'] = true;
            $data['profile_func'] = base_url() . 'CaregiverController/profile';
            $data['page_title'] = 'Caregiver Home';
            $data['header1'] = 'Welcome to Caregiver Home';
            $data['profile'] = $this->lang->line('caregiver_menu_profile');
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_general'));
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();            
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['topics'] = $this->Caregiver_Home_model->get_topics();
            $data['urgent'] = $this->Caregiver_Home_model->get_patients();
            $data['content'] = "";
            $data['page_content'] = 'Caregiver/index.html';
            $this->parser->parse('master.php', $data);
            //echo $this->Caregiver_Home_model->get_topic_with_score();
            //echo "\xA";
            echo count($this->Caregiver_Home_model->get_answer_array());
        } else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url='.base_url("AccountController/login"));
        }
    }

    function personal() {
        if ($this->session->userType == "Caregiver") { // if session exists
            $data['profile'] = $this->lang->line('caregiver_menu_profile');
            $data['profile_func'] = base_url() . 'CaregiverController/profile';
            $data['show_navbar'] = true;
            $data['page_title'] = 'Personal Patient Information';
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_personal'));
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();            
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['content'] = lang(''); //to check whether internationalization set up works
            $data['patients'] = $this->Caregiver_Home_model->get_patients();
            $data['topics'] = $this->Caregiver_Home_model->get_topics();
            $data['answerss'] = $this->Caregiver_Home_model->print_score('axel');
            $data['privacy'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Privacy');
            $data['food'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Food');
            $data['safety'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Safety');
            $data['comfort'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Comfort');
            $data['autonomie'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Autonomy');
            $data['respect'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Respect');
            $data['staffresp'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'StaffResponse');
            $data['staffbonding'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'StaffBonding');
            $data['activities'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Activities');
            $data['relationships'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Relationships');
            $data['other'] = $this->Caregiver_Home_model->calculate_topic($this->input->get('username'),'Other');
            $data['avg'] = $this->Caregiver_Home_model->average_score($this->input->get('username'));
            $data['currentuser'] = $this->Caregiver_Home_model->current_user($this->input->get('username'));
            
            $data['page_content'] = 'Caregiver/personal.html';
            $this->parser->parse('master.php', $data);
        } else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url='.base_url("AccountController/login"));
        }
    }
    
    function getArray(){
        $this->output->set_content_type("application/json")->append_output($this->Caregiver_Home_model->get_topic_with_score());
    }

    function tips() {
        if ($this->session->userType == "Caregiver") { // if session exists
            $data['profile_func'] = base_url() . 'CaregiverController/profile';
            $data['show_navbar'] = true;
            $data['page_title'] = 'Tips';
            $data['profile'] = $this->lang->line('caregiver_menu_profile');
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_tips'));
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();            
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['content'] = "Tips to be added.";
            $data['page_content'] = 'Caregiver/template.html';
            $this->parser->parse('master.php', $data);
        } else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url='.base_url("AccountController/login"));
        }
    }

    function profile() {
        if ($this->session->userType == "Caregiver") {
            $data['profile_func'] = base_url() . 'CaregiverController/profile';
            $data['log_out'] = $this->lang->line('caregiver_log_out');            
            $data['logout'] = $this->lang->line('caregiver_logout');
            $data['new_placeholder'] = $this->lang->line('caregiver_new_placeholder');
            $data['old_placeholder'] = $this->lang->line('caregiver_old_placeholder');
            $data['conf_placeholder'] = $this->lang->line('caregiver_conf_placeholder');
            $data['change_password'] = $this->lang->line('caregiver_change_password');
            $data['old_password'] = $this->lang->line('caregiver_old_password');
            $data['new_password'] = $this->lang->line('caregiver_new_password');
            $data['conf_password'] = $this->lang->line('caregiver_conf_password');
            $data['change_lang'] = $this->lang->line('caregiver_change_lang');
            $data['Apply'] = $this->lang->line('caregiver_apply');
            $data['profile'] = $this->lang->line('caregiver_menu_profile');
            $data['show_navbar'] = true;
            $data['page_title'] = 'Edit Profile';
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_tips'));
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();            
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['page_content'] = 'Account/caregiver_profile.html';
            $data['Person_Name'] = $this->session->Name;
            $this->parser->parse('master.php', $data);
        } else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url='.base_url("AccountController/login"));
        }
    }
    
    function logout(){
        session_destroy();
        redirect(base_url() . 'AccountController/login');
    }

    function change_language() {
        $newlang = $this->input->post('language');
        if (isset($newlang)) {
            $this->session->set_userdata('Language', $newlang);
            $this->db->query("UPDATE a16_webapps_2.Caregiver "
                    . "SET Language ='" . $newlang . "' "
                    . "WHERE idCaregiver = " . $this->session->idCaregiver . ";");
        }
        redirect(base_url() . 'CaregiverController/profile');
    }

    function change_password() {
        $username = $this->Caregiver_Home_model->get_name($this->session->idCaregiver);
        //echo $username;
        $verif = $this->db->query("SELECT password, Name FROM a16_webapps_2.Caregiver WHERE Name = '" . $username . "';")->row();
        $old = filter_input(INPUT_POST, 'old_password');
        $new = $this->input->post('new_password');
        $conf = $this->input->post('conf_password');
        if ($conf === $new) {
            echo "passwords equal";
            if (password_verify($old, $verif->password)) {
                $password = password_hash($new, PASSWORD_DEFAULT);
                echo " -> should update";
                $this->db->query("UPDATE a16_webapps_2.Caregiver "
                        . "SET Name ='" . $username . "' , password = '" . $password . "' "
                        . "WHERE idCaregiver = " . $this->session->caregiver_id . ";");
            }
        }
        $this->profile();
    }
    
    function debug() {
        $array = $this->Caregiver_Home_model->calculate_topic_testplsignore("axel");
        foreach ($array as $k => $v) {
            echo "\$a[$k] => $v.\n";
        }
    }

}
