<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GraceAgeController
 *
 * @author orditech
 */
class ElderlyController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('parser'); //This will allow us to use the parser in function index.
        $this->load->helper('url'); //This allows to use the base_url function for loading the css.
        $this->load->model('Menu_model');
        $this->load->model('Question_model');
        $this->lang->load('elderly', $this->session->Language);
        $this->lang->load('caregiver',$this->session->Language);
        $this->session->set_userdata('patient_id', 2); // Assume user 2 for now!
    }

    function index() {
        if ($this->session->userType == "Patient") { // if session exists
            $data['show_navbar'] = false;
            $data['settings_button'] = $this->lang->line('elderly_settings_button');
            $data['stop_button'] = $this->lang->line('elderly_stop_button');
            $data['questionnaire_button'] = $this->lang->line('elderly_questionnaire_button');
            $data['tips_button'] = $this->lang->line('elderly_tips_button');
            $data['score_button'] = $this->lang->line('elderly_score_button');
            $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
            $data['page_title'] = 'Elderly Home';
            $data['header1'] = 'Welcome to Elderly Home';
            $data['menu_items'] = $this->Menu_model->get_menuitems('Home');
            $data['content'] = "This is the home page! welcome " . $this->session->Name;
            $data['page_content'] = 'Elderly/index.html';
            $this->parser->parse('master.php', $data);
        } else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url='.base_url("AccountController/login"));
        }
    }

    /*     * ************* All Questionnaire page functions *********************** */

    function questionnaire() {
        if ($this->session->userType == "Patient") { // if session exists
            //Go fetch necessary data from database to setup the correct question.
            $this->Question_model->get_initial_state();
            $data['show_navbar'] = true;
            $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
            $data['page_title'] = 'Questionnaire';
            $data['header1'] = 'Questionnaire';
            $data['menu_items'] = $this->Menu_model->get_menuitems('Questionnaire');
            $data['answers'] = $this->Question_model->get_answerbuttons();
            $data['navigationbuttons'] = $this->Question_model->get_navigationbuttons();
            $data['questions'] = $this->Question_model->get_question($this->session->question_id, $this->session->Language);
            $data['page_content'] = 'Elderly/questionnaire.php';
            $this->parser->parse('master.php', $data);
        } else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url='.base_url("AccountController/login"));
        }
    }

    function previous() {
        $this->output->set_content_type("application/json")->append_output(
                $this->Question_model->get_previous_question_as_json());
    }

    function next() {
        $this->output->set_content_type("application/json")->append_output(
                $this->Question_model->get_next_question_as_json());
    }

    function answer_clicked() {
        $clicked = $this->input->post('clicked');
        $this->session->set_userdata('selected_answer', $clicked);
    }

    /*     * **********************End of Questionnaire functions*************************** */

    function tips() {
        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['page_title'] = 'Tips';
        $data['header1'] = 'Tip of the day';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Tips');
        $data['content'] = "This is the Tips page!";
        $data['page_content'] = 'Elderly/tips.html';
        $this->parser->parse('master.php', $data);
    }
    
    function score() {
        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['page_title'] = 'Score';
        $data['header1'] = 'Your score';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Score');
        $data['content'] = "This is the Score page!";
        $data['page_content'] = 'Elderly/score.html';
        $this->parser->parse('master.php', $data);
    }
    
    function congratulations() {
        $this->lang->load('congratulations', $this->session->Language);
        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['page_title'] = 'Congratulations';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Questionnaire');
        $data['content'] = "congratulations!!!";
        $data['page_content'] = 'Elderly/congratulations.html';
        $data['congrats_message']  = $this->lang->line('congrats_congrats_message');
        $data['your_score_is'] = $this->lang->line('congrats_your_score_is');
        $data['these_can_be_exchanged'] = $this->lang->line('congrats_these_can_be_exchanged');
        $data['button_text'] = $this->lang->line('congrats_button_text');
        $data['score'] = $this->Question_model->getPatientScore($this->session->idPatient);
        //$this->Question_model->updatePatientScore($this->session->idPatient, 1);
        $this->parser->parse('master.php', $data);
    }

    function profile(){
        if ($this->session->userType == "Patient") {
            $data['show_navbar'] = true;
            $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
            $data['menu_items'] = $this->Menu_model->get_menuitems('Questionnaire');
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
            $data['page_title'] = 'Edit Profile';
            $data['page_content'] = 'Account/elderly_profile.html';
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
            $this->db->query("UPDATE a16_webapps_2.Patient "
                    . "SET Language ='" . $newlang . "' "
                    . "WHERE idPatient = " . $this->session->idPatient . ";");
        }
        redirect(base_url() . 'ElderlyController/profile');
    }
    
    function change_password() {
        $verif = $this->db->query("SELECT password, Name FROM a16_webapps_2.Patient WHERE idPatient = '" . $this->session->idPatient . "';")->row();
        $old = filter_input(INPUT_POST, 'old_password');
        $new = $this->input->post('new_password');
        $conf = $this->input->post('conf_password');
        if ($conf === $new) {
            if (password_verify($old, $verif->password)) {
                $password = password_hash($new, PASSWORD_DEFAULT);
                $this->db->query("UPDATE a16_webapps_2.Patient "
                        . "SET password = '" . $password . "' "
                        . "WHERE idPatient = " . $this->session->idPatient . ";");
            }
        }
        $this->profile();
    }
}

