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
        
        $this->load->library('session');
        $this->load->library('parser'); //This will allow us to use the parser in function index.
        $this->load->helper('url'); //This allows to use the base_url function for loading the css.
        $this->load->model('Menu_model');
        $this->load->model('Question_model');
        $this->load->model('Tip_model');
        $this->load->model('Account_model');
        $this->load->model('Caregiver_Home_model'); // In order to call the function: get_topics_with_lowest_scores()
        $this->lang->load('elderly', $this->session->Language);
        $this->lang->load('caregiver',$this->session->Language);
        //$this->session->set_userdata('patient_id', 2); // Assume user 2 for now!
    }
    
    private function loadComonData() { // place the common $data[] here
        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        
        return $data;
    }
    
    private function is_logged_in() {// returns true if valid user is logged in, else returns false and redirects to login page
        if ($this->session->userType == "Patient")
            return true;
        else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url=' . base_url("AccountController/login"));
            return false;
        }
    }
    
    private function  loadIndexData(){
        $data = $this->loadComonData();
        $data['show_navbar'] = false;
        $data['settings_button'] = $this->lang->line('elderly_settings_button');
        $data['stop_button'] = $this->lang->line('elderly_stop_button');
        $data['questionnaire_button'] = $this->lang->line('elderly_questionnaire_button');
        $data['tips_button'] = $this->lang->line('elderly_tips_button');
        $data['score_button'] = $this->lang->line('elderly_score_button');
        
        $data['page_title'] = 'Elderly Home';
        $data['header1'] = 'Welcome to Elderly Home';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Home');
        $data['content'] = "This is the home page! welcome " . $this->session->Name;
        $data['page_content'] = 'Elderly/index.html';
        return $data;
    }
    function index() {
        if (!$this->is_logged_in()) return;  // if session exists
            $data =$this->loadIndexData();
            $this->parser->parse('master.php', $data);
        
    }

    /*     * ************* All Questionnaire page functions *********************** */
    private function loadQuestionnaireData() {
        $data = $this->loadComonData();
        $data['questionNumber'] = ($this->session->question_id);                // set initial questionNumber for progressbar
        $data['pbQuestionText'] = lang('question_text');                        // set label of progressbar
        $data['initial_pbWidth'] = (($this->session->question_id) / 52) * 100;      // set initial width of progressbar
        $data['page_title'] = 'Questionnaire';
        $data['header1'] = 'Questionnaire';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Questionnaire');
        $data['answers'] = $this->Question_model->get_answerbuttons();
        $data['navigationbuttons'] = $this->Question_model->get_navigationbuttons();
        $data['questions'] = $this->Question_model->get_question($this->session->question_id, $this->session->Language);
        $data['page_content'] = 'Elderly/questionnaire.php';
        return $data;
    }
    function questionnaire() {
        if (!$this->is_logged_in()) return;  // if session exists
            //Go fetch necessary data from database to setup the correct question.
            $this->Question_model->get_initial_state();            
            $data = $this->loadQuestionnaireData();

            $this->parser->parse('master.php', $data);
       
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

    private function loadTipsData(){
        $data = $this->loadComonData();
        $data['page_title'] = $this->lang->line('tips');
        $data['header'] = $this->lang->line('overview_of_tips');
        $data['menu_items'] = $this->Menu_model->get_menuitems('Tips');
        $data['navigationbuttons'] = $this->Tip_model->get_navigationbuttons();
        $topics = $this->Caregiver_Home_model->get_topics_with_lowest_scores('4');
        $data['tip_1'] = $this->Tip_model->get_tip($topics[0]);
        $data['tip_2'] = $this->Tip_model->get_tip($topics[1]);
        $data['tip_3'] = $this->Tip_model->get_tip($topics[2]);
        $data['tip_4'] = $this->Tip_model->get_tip($topics[3]);
        $data['page_content'] = 'Elderly/tips.html';
        return $data;
    }
    
    function tips() {
        if (!$this->is_logged_in()) return; // if session exists
            
            $data = $this->loadTipsData();

            $this->parser->parse('master.php', $data);
        
    }
    
    private function loadScoreData(){
        $data = $this->loadComonData();
            $data['score_text'] = lang('score_text');
            $data['score'] = $this->Question_model->getPatientScore($this->session->idPatient);
            $data['exchange_score'] = lang('exchange_score');
            $data['buy_reward'] = lang('buy_reward');
            $data['reward_text'] = lang('reward_text');
            $data['rewards_bought_text'] = lang('rewards_bought_text');
            $data['page_title'] = 'Score';
            $data['header1'] = 'Your score';
            $data['rewards'] = $this->Question_model->getRewards($this->session->Language);
            $data['rewards_bought'] = $this->Question_model->getRewardsBought($this->session->idPatient);
            $data['menu_items'] = $this->Menu_model->get_menuitems('Score');
            $data['page_content'] = 'Elderly/score.html';
            return $data;
        
    }
    function score() {
        if (!$this->is_logged_in()) return;
            $data = $this->loadScoreData();
            $rewardBought = "false";
            $data['reward_message'] = lang('buy_the_reward');
            
            if (isset($_GET["rewardBought"])) {
                $rewardBought = $_GET["rewardBought"];
                if ($rewardBought=="true") {
                    $data['reward_message'] = lang('you_bought_reward');
                }
                if ($rewardBought=="false") {
                    $data['reward_message'] = lang('too_low_score');
                }      
            }
            
            
            $this->parser->parse('master.php', $data);
      
    }

    function buyReward(){
        if (!$this->is_logged_in()) return;
        $reward = $_GET["reward"];
        $idPatient = $this->session->idPatient;
        $rewardBought = ($this->Question_model->buyReward($reward, $idPatient)) ? 'true' : 'false';
        redirect(base_url() . 'ElderlyController/score?rewardBought=' . $rewardBought);
    }
    
    private function loadCongratulationsData(){
        $data = $this->loadComonData();
        $data['page_title'] = 'Congratulations';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Questionnaire');
        $data['content'] = "congratulations!!!";
        $data['page_content'] = 'Elderly/congratulations.html';
        $data['congrats_message']  = $this->lang->line('congrats_congrats_message');
        $data['your_score_is'] = $this->lang->line('congrats_your_score_is');
        $data['these_can_be_exchanged'] = $this->lang->line('congrats_these_can_be_exchanged');
        $data['button_text'] = $this->lang->line('congrats_button_text');
        $data['score'] = $this->Question_model->getPatientScore($this->session->idPatient);
        return $data;
    }

    function congratulations() {
        if (!$this->is_logged_in()) return;
        $this->lang->load('congratulations', $this->session->Language);
       $data = $this->loadCongratulationsData();
        //$this->Question_model->updatePatientScore($this->session->idPatient, 1);
        $this->parser->parse('master.php', $data);
    }

    private function loadProfileData() {
        $data = $this->loadComonData();
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
        return $data;
    }

    function profile(){
        if (!$this->is_logged_in()) return;
            $data = $this->loadProfileData();
            $this->parser->parse('master.php', $data);
        
    }
    
    function logout(){
        session_destroy();
        redirect(base_url() . 'AccountController/login');
    }
    
    function change_language() {
        if (!$this->is_logged_in()) return;
        $newlang = $this->input->post('language');
        if (isset($newlang)) {
            $this->session->set_userdata('Language', $newlang);
            $this->Account_model->changeLanguage($this->session->userType,$newlang,$this->session->idPatient);
        }
        redirect(base_url() . 'ElderlyController/profile');
    }
    
    function change_password() {
        if (!$this->is_logged_in()) return;
        $verif = $this->Account_model->getUser($this->session->Name);
        $old = filter_input(INPUT_POST, 'old_password');
        $new = $this->input->post('new_password');
        $conf = $this->input->post('conf_password');
        if ($conf === $new) {
            if (password_verify($old, $verif["password"])) {
                $password = password_hash($new, PASSWORD_DEFAULT);
                $this->Account_model->changePassword($this->session->userType,$password, $this->session->idPatient);
            }
        }
        $this->profile();
    }
}

