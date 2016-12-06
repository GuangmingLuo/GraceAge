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
        $this->load->model('Tip_model');
        $this->load->model('Reward_model');
        $this->load->model('Account_model');
    }

    function index() {
        if (!$this->is_logged_in()) return; // if session exists
            $data['show_navbar'] = true;
            $data['profile_func'] = base_url() . 'CaregiverController/profile';
            $data['page_title'] = 'Caregiver Home';
            $data['header1'] = 'Welcome to Caregiver Home';
            $data['profile'] = $this->lang->line('caregiver_menu_profile');
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_general'));
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();
            $data['profile_class'] = $this->Caregiver_Menu_model->get_profile_class();
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['topics'] = $this->Caregiver_Home_model->get_topics();
            $data['urgent'] = $this->Caregiver_Home_model->calculate_avg();
            $data['content'] = "";
            $data['page_content'] = 'Caregiver/index.html';
            $data['messages'] = $this->Caregiver_Home_model->add_message($this->input->get('messagesend'));
            $data ['show'] = $this->Caregiver_Home_model->show_messages();
            $this->parser->parse('master.php', $data);
            
     
    }

     function getTitle(){
        $this->output->set_content_type("application/json")->append_output($this->Caregiver_Home_model->get_chart_title());
    }
    
    function getArray() {
        $this->output->set_content_type("application/json")->append_output($this->Caregiver_Home_model->get_topic_with_score());
    }
    
    function personal() {
        if (!$this->is_logged_in()) return; // if session exists
            $data['profile'] = $this->lang->line('caregiver_menu_profile');
            $data['profile_func'] = base_url() . 'CaregiverController/profile';
            $data['show_navbar'] = true;
            $data['page_title'] = 'Personal Patient Information';
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_personal'));
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();
            $data['profile_class'] = $this->Caregiver_Menu_model->get_profile_class();
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['content'] = lang(''); //to check whether internationalization set up works
            $data['patients'] = $this->Caregiver_Home_model->get_patients();
            $data['currentuser'] = $this->Caregiver_Home_model->current_user($this->input->get('username'));
            $data['results'] = $this->Caregiver_Home_model->calculate_topic_eff($this->input->get('username'));

            $data['page_content'] = 'Caregiver/personal.html';
            $this->parser->parse('master.php', $data);
       
    }

    function tips() {
        if (!$this->is_logged_in()) return; // if session exists
            $data['profile_func'] = base_url() . 'CaregiverController/profile';
            $data['show_navbar'] = true;
            $data['page_title'] = 'Tips';
            $data['profile'] = $this->lang->line('caregiver_menu_profile');
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_tips'));
            $data['profile_class'] = $this->Caregiver_Menu_model->get_profile_class();
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['content'] = "Tips to be added.";
            $data['options'] = $this->Caregiver_Home_model->get_topics();
            $data['choose_option'] = $this->lang->line('tip_choose_topic');
            $data['add_new_tip'] = $this->lang->line('tip_add_new_tip');
            $data['page_content'] = 'Caregiver/tips.html';
            $data['write_new_tip'] = $this->lang->line('tip_write_new');
            $this->parser->parse('master.php', $data);
      
    }
    
    function rewards() {
        if (!$this->is_logged_in()) return; // if session exists
            $data['profile_func'] = base_url() . 'CaregiverController/rewards';
            $data['show_navbar'] = true;
            $data['page_title'] = 'Rewards';
            $data['profile'] = $this->lang->line('caregiver_menu_profile');
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_reward'));
            $data['write_new_reward'] = $this->lang->line('write_new_reward');
            $data['add_new_reward'] = $this->lang->line('add_new_reward');
            $data['price'] = $this->lang->line('price');
            $data['allrewards'] = $this->Reward_model ->get_rewards();
            $data['profile_class'] = $this->Caregiver_Menu_model->get_profile_class();
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems();
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['page_content'] = 'Caregiver/reward.html';
           
            $this->parser->parse('master.php', $data);
       
    }
    
    function rewardPost(){
        if (!$this->is_logged_in()) return;// if session exists
            if (!empty($_POST["new_reward"]) && !empty($_POST["price"])){
                $reward = filter_input(INPUT_POST, 'new_reward');
                $price = filter_input(INPUT_POST, 'price');
                $this->Reward_model ->add_reward($reward,$price);
            }else{
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
        if (!$this->is_logged_in()) return;
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
            $data['caregiver_menu_items'] = $this->Caregiver_Menu_model->get_menuitems($this->lang->line('caregiver_menu_profile'));
            $data['caregiver_profile_items'] = $this->Caregiver_Menu_model->get_profileitems($this->lang->line('settings'));
            $data['profile_class'] = $this->Caregiver_Menu_model->get_profile_class();
            $data['navbar_content'] = 'Caregiver/caregiverNavbar.html';
            $data['page_content'] = 'Account/caregiver_profile.html';
            $data['Person_Name'] = $this->session->Name;
            $this->parser->parse('master.php', $data);
        
    }

    function logout() {
        session_destroy();
        redirect(base_url() . 'AccountController/login');
    }

    function change_language() {
        $newlang = $this->input->post('language');
        if (isset($newlang)) {
            $this->session->set_userdata('Language', $newlang);
            $this->Account_model->changeLanguage($this->session->userType,$newlang,$this->session->idCaregiver);
        }
        redirect(base_url() . 'CaregiverController/profile');
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
                $this->Account_model->changePassword($this->session->userType,$password, $this->session->idCaregiver);
            }
        }
        $this->profile();
    }
    
    function register(){
        redirect(base_url()."AccountController/register");
    }

    function debug() {
        //$array = $this->Caregiver_Home_model->calculate_topic_testplsignore("axel");
        //foreach ($array as $k => $v) {
        //    echo "\$a[$k] => $v.\n";
        //}
        $this->Tip_model->remove_tip(42);
    }
        private function is_logged_in() { // returns true if valid user is logged in, else returns false and redirects to login page
        if ($this->session->userType == "Caregiver")  return true;
        else {
            echo "You are not allowed to access this page!!!";
            $this->output->set_header('refresh:3; url=' . base_url("AccountController/login"));
            return false;
        }
    }

}
