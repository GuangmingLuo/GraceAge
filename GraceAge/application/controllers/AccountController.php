<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AccountController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->helper('url');
        $this->load->database();
        $this->load->library('session');
        $this->load->model('Account_model');
    }

    public function change_language() {
        $language = $_GET["language"];
        if ($language == "english") {
            $language = "dutch";
        } else
            $language = "english";
        $this->lang->load('login', $language);
        redirect(base_url() . 'AccountController/login?language=' . $language);
    }

    private function common_data() {
        $data['show_navbar'] = false;
        $data['username'] = lang('username');
        $data['password'] = lang('password');
        $data['confirm'] = lang('confirm');
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        return $data;
    }
    
    private function login_data() {
        $data['page_title'] = lang('LOG_IN') . " Grace Age";
        $data['BAGDE'] = lang('BAGDE');
        $data['LOG_IN'] = lang('LOG_IN');
        $data['show_your_badge'] = lang('show_your_badge');
        $data['no_camera'] = lang('no_camera');
        $data['credentials'] = lang('credentials');
        return $data;
    }
    
    private function register_data() {
        $data['page_title'] = lang('create_account');
        $data['user_type'] = lang('user_type');
        $data['patient'] = lang('patient');
        $data['caregiver'] = lang('caregiver');
        $data['language'] = lang('language');
        return $data;
    }

    public function login() {
        $language = "dutch";
        if (isset($_GET["language"])) {
            $language = $_GET["language"];
        }
        $this->lang->load('login', $language);
        $data['language'] = $language;
        $data['loggedin'] = lang('not_logged_in');
        $data = array_merge($data, $this->common_data(), $this->login_data());
        $data['page_content'] = 'Account/login.html';
        $this->parser->parse('master.php', $data);
    }

    function login_valid(){
        $language = "dutch";
        if (isset($_POST["language"])) {
            $language = $_POST["language"];
        }
        $this->lang->load('login', $language);
        $data['language'] = $language;
        $data['loggedin'] = lang('wrong_credentials');
        $data = array_merge($data, $this->common_data(), $this->login_data());
        if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) { // check if input is set
            $username = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');
            $result = $this->Account_model->getUser($username);
            $data2['valid_user'] = ($result != null);
            $data2['correct_password'] = password_verify($password, $result["password"]);
            if ($result != NULL) {
                if (password_verify($password, $result["password"])) {
                    $data2['usertype'] = $result['userType'];
                    $result["password"] = NULL;
                    $this->session->set_userdata($result);
                }
            
            }
            $this->output->set_content_type("application/json")->append_output(json_encode($data2));
        }
        else{
            $this->output->set_content_type("application/json")->append_output(json_encode(array(
                'valid_user' => false,
                'correct_password' => false
            )));
        }
    }
    
    function loginPost() {
        if ($this->session->userType == "Patient") {
            redirect(base_url() . 'ElderlyController/index');
        } else if($this->session->userType == "Caregiver") { // userType = Caregiver
            redirect(base_url() . 'CaregiverController/index');
        }
    }

    public function register() {
        $this->lang->load('login', 'english');
        $data['register_state'] = lang('not_created');
        $data = array_merge($data, $this->common_data(), $this->register_data());
        $data['page_content'] = 'Account/register.html';
        $this->parser->parse('master.php', $data);
    }

    public function registerPost() {
        $this->lang->load('login', 'english');
        $data['register_state'] = lang('not_created');
        $data = array_merge($data, $this->common_data(), $this->register_data());
        if (!empty($_POST["username"]) && !empty($_POST["password1"]) && !empty($_POST["password2"]) && !empty($_POST["usertype"])) { // check if none of the input is empty
            $usertype = filter_input(INPUT_POST, 'usertype');
            $language = filter_input(INPUT_POST, 'language');
            $username = filter_input(INPUT_POST, 'username');
            $password1 = filter_input(INPUT_POST, 'password1');
            $password2 = filter_input(INPUT_POST, 'password2');
            $this->load->database();
            if ($password1 === $password2) {
                $password = password_hash($password1, PASSWORD_DEFAULT);
                if ($this->Account_model->addUser($usertype, $language, $username, $password)) {
                    $data['register_state'] = lang('account_created');
                } else {
                    $data['register_state'] = lang('user_exists');
                }
            } else {
                $data['register_state'] = lang('different_passwords');
            }
        }
        $data['page_content'] = 'Account/register.html';
        $this->parser->parse('master.php', $data);
    }

    public function logOut() { //destroy current session and goto login page
        session_destroy();
        redirect(base_url() . 'AccountController/login');
    }

}
