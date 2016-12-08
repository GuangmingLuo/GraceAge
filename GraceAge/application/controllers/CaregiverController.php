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
        
        $this->load->library('session');
        $this->load->library('parser'); //This will allow us to use the parser in function index.
        $this->load->helper('url'); //This allows to use the base_url function for loading the css.
        $this->lang->load('caregiver', $this->session->Language);
        $this->lang->load('caregiver_menu', $this->session->Language);
        $this->lang->load('tip', $this->session->Language);
        $this->lang->load('reward', $this->session->Language);
        $this->load->model('Caregiver_Menu_model');
        $this->load->model('Caregiver_Home_model');
        $this->load->model('Account_model');
        $this->load->model('Tip_model');
        $this->load->model('Reward_model');
        $this->load->model('Account_model');
    }
    
    private function loadCommonData() {
        $data['show_navbar'] = true;
        $data['profile_func'] = base_url() . 'CaregiverController/profile';
        $data['profile'] = $this->lang->line('caregiver_menu_profile');
        $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();
        $data['profile_class'] = $this->Caregiver_Menu_model->get_profile_class();
        $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
        return $data;
    }


    function index() {
        if (!$this->is_logged_in()){ 
            return;
        }
        $data = $this->loadIndexData();
        $this->parser->parse('master.php', $data);
            
     
    }

     function getTitle(){
        $this->output->set_content_type("application/json")->append_output($this->Caregiver_Home_model->get_chart_title());
    }
    
    function getArray() {
        $this->output->set_content_type("application/json")->append_output($this->Caregiver_Home_model->get_topic_with_score());
    }
    

    
    function personal() {
        if (!$this->is_logged_in()) {
            return;
        }
        $data = $this->loadPersonalData();
        $this->parser->parse('master.php', $data);
       
    }


    function tips() {
        if (!$this->is_logged_in()) {
            return;
        }
        $data = $this->loadTipsData();
        $this->parser->parse('master.php', $data);
    }

    function rewards() {
        if (!$this->is_logged_in()) {
            return;
        }
        $data = $this->loadRewardsData();

        $this->parser->parse('master.php', $data);
       
    }
    
    function rewardPost(){
        if (!$this->is_logged_in()) {
            return;
        }
        if (!empty($_POST["new_reward"]) && !empty($_POST["price"])){
            $reward = filter_input(INPUT_POST, 'new_reward');
            $price = filter_input(INPUT_POST, 'price');
            $this->Reward_model ->add_reward($reward,$price);
        }
        else{
            // error message should show in an alert window               
        }
        redirect(base_url() . 'CaregiverController/rewards');
            
        
    }   
    
    function editReward(){
        $reward = $_POST['Reward'];
        if(isset($_POST['available'])) {
            $available = "checked";
        }
        else {
            $available = " ";
        }
        $this->Reward_model ->edit_reward($reward, $available);
        redirect(base_url() . 'CaregiverController/rewards');
    }   
    
    function get_tips(){
        $topic = $this->input->post('topic');
        $this->output->set_content_type("application/json")->append_output(
                $this->Tip_model->get_tips_as_json($topic));
    }
    
    function add_tip(){
        $topic = $this->input->post('topic');
        $tip = $this->input->post('tip');
        $this->Tip_model->add_tip($topic, $tip);
    }
    function delete_tip(){
        $id = $this->input->post('id');        
        $this->Tip_model->remove_tip($id);
    }
    
    function update_tip(){
        $tipId = $this->input->post('id');
        $topic = $this->input->post('topic');
        $tip = $this->input->post('tip');
        
        $this->Tip_model->update_tip($tipId,$topic, $tip);
    }


    function profile() {
        if (!$this->is_logged_in()) {
            return;
        }
        $data = $this->loadProfileData();
            $this->parser->parse('master.php', $data);
        
    }

    function logout() {
        session_destroy();
        redirect(base_url() . 'AccountController/login');
    }

    function change_language() {
        $newlang = $this->input->post('language');
        $data['err_msg'] ="";
        if (isset($newlang)) {
            $this->session->set_userdata('Language', $newlang);
            $this->Account_model->changeLanguage($this->session->userType,$newlang,$this->session->idCaregiver);
            $this->lang->load('login', $this->session->Language);
            $data['err_msg'] = $this->lang->line('language') . $this->lang->line('saved_changes');
        }
        $this->output->set_content_type("application/json")->append_output(json_encode($data));
    }
    
    function register() {
        if(!$this->session->isAdmin){
            session_destroy();
            redirect(base_url() . 'AccountController/login');
        }
        $data = $this->loadRegisterData();
        $this->parser->parse('master.php', $data);
    }
    
    public function registerPost() {
        $this->lang->load('login', $this->session->Language);
        $return_data['err_msg'] = $this->lang->line('register_form_incomplete');
        $return_data['success'] = false;
        if (!empty($_POST["username"]) && !empty($_POST["password1"]) && !empty($_POST["password2"]) && !empty($_POST["usertype"])) { // check if none of the input is empty
            $usertype = filter_input(INPUT_POST, 'usertype');
            $language = filter_input(INPUT_POST, 'language');
            $username = filter_input(INPUT_POST, 'username');
            $password1 = filter_input(INPUT_POST, 'password1');
            $password2 = filter_input(INPUT_POST, 'password2');
            if ($password1 === $password2) {
                $password = password_hash($password1, PASSWORD_DEFAULT);
                if ($this->Account_model->addUser($usertype, $language, $username, $password)) {
                    $return_data['err_msg'] = $this->lang->line('account_created');
                    $return_data['success'] = true;
                } 
                else {
                    $return_data['err_msg'] = $this->lang->line('user_exists');
                }
            } 
            else {
                $return_data['err_msg'] = $this->lang->line('different_passwords');
            }
        }
        $this->output->set_content_type("application/json")->append_output(json_encode($return_data));
    }

    function change_password() {
        if (!$this->is_logged_in()) {
            return;
        }
        $this->lang->load('login', $this->session->Language);
        $data['success'] = false;
        $data['err_msg'] = " ";
        $verif = $this->Account_model->getUser($this->session->Name);
        $old = $this->input->post('old_password');
        $new = $this->input->post('new_password');
        $conf = $this->input->post('conf_password');
        if ($old || $new || $conf){
            $data['err_msg'] = $this->lang->line('errorbox_password').$this->lang->line('register_form_incomplete');
        }
        if($old && $new && $conf){
            $data['err_msg'] = $this->lang->line('errorbox_password').$this->lang->line('different_passwords');
            if ($conf === $new) {
                $data['err_msg'] = $this->lang->line('errorbox_password').$this->lang->line('incorrect_password');
                if (password_verify($old, $verif["password"])) {
                    $password = password_hash($new, PASSWORD_DEFAULT);
                    $this->Account_model->changePassword($this->session->userType,$password, $this->session->idCaregiver);
                    $data['err_msg'] = $this->lang->line('errorbox_password').$this->lang->line('saved_changed');
                    $data['success'] = true;
                }
            }
        }
        $this->output->set_content_type("application/json")->append_output(json_encode($data));
    }
    



    private function is_logged_in() { // returns true if valid user is logged in, else returns false and redirects to login page
        if ($this->session->userType == "Caregiver")
            return true;
        else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url=' . base_url("AccountController/login"));
            return false;
        }
    }
    
    function loadRegisterData(){
        $data = $this->loadCommonData();
        $this->lang->load('login', $this->session->Language);
        $data['confirm'] = $this->lang->line('confirm');
        $data['confirm_ph'] = $this->lang->line('caregiver_confirm_placeholder');
        $data['username_ph'] = $this->lang->line('caregiver_username_placeholder');
        $data['password_ph'] = $this->lang->line('caregiver_password_placeholder');
        $data['user_type'] = $this->lang->line('user_type');
        $data['patient'] = $this->lang->line('patient');
        $data['caregiver'] = $this->lang->line('caregiver');
        $data['language'] = $this->lang->line('language');
        $data['page_title'] = 'Add Profile';
        $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_register'));
        $data['page_content'] = 'Account/register.html';
        return $data;
    }

    private function loadIndexData() {
        $data = $this->loadCommonData();
        $data['page_title'] = 'Caregiver Home';
        $data['header1'] = 'Welcome to Caregiver Home';
        $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_general'));
        $data['topics'] = $this->Caregiver_Home_model->get_topics();
        $data['urgent'] = $this->Caregiver_Home_model->calculate_avg();
        $data['content'] = "";
        $data['page_content'] = 'Caregiver/index.html';
        $data['messages'] = $this->Caregiver_Home_model->add_message($this->input->get('messagesend'));
        $data ['show'] = $this->Caregiver_Home_model->show_messages();
        return $data;
    }

    private function loadPersonalData() {
        $data = $this->loadCommonData();
        $data['page_title'] = 'Personal Patient Information';
        $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_personal'));
        $data['content'] = lang(''); //to check whether internationalization set up works
        $data['patients'] = $this->Caregiver_Home_model->get_patients();
        $data['table'] = $this->Caregiver_Home_model->getJSONtable();
        $data['currentuser'] = $this->Caregiver_Home_model->current_user($this->input->get('username'));
        $data['results'] = $this->Caregiver_Home_model->calculate_topic_eff($this->input->get('username'));
        $data['page_content'] = 'Caregiver/personal.html';
        return $data;
    }

    private function loadProfileData() {
        $data = $this->loadCommonData();
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
        $data['page_title'] = 'Edit Profile';
        $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_profile'));
        $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems($this->lang->line('settings'));
        $data['profile_class'] = $this->Caregiver_Menu_model->get_profile_class();
        $data['page_content'] = 'Account/caregiver_profile.html';
        $data['Person_Name'] = $this->session->Name;
        return $data;
    }

    private function loadTipsData() {
        $data = $this->loadCommonData();
        $data['page_title'] = 'Tips';
        $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_tips'));
        $data['content'] = "Tips to be added.";
        $data['options'] = $this->Caregiver_Home_model->get_topics();
        $data['choose_option'] = $this->lang->line('tip_choose_topic');
        $data['add_new_tip'] = $this->lang->line('tip_add_new_tip');
        $data['page_content'] = 'Caregiver/tips.html';
        $data['write_new_tip'] = $this->lang->line('tip_write_new');
        return $data;
    }

    private function loadRewardsData() {
        $data = $this->loadCommonData();
        $data['profile_func'] = base_url() . 'CaregiverController/rewards';

        $data['page_title'] = 'Rewards';

        $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_reward'));
        $data['write_new_reward'] = $this->lang->line('write_new_reward');
        $data['add_new_reward'] = $this->lang->line('add_new_reward');
        $data['price'] = $this->lang->line('price');
        $data['allrewards'] = $this->Reward_model->get_rewards();



        $data['page_content'] = 'Caregiver/reward.html';
        return $data;
    }

}
