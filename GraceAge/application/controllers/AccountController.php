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

    public function login() {
        $data['page_title'] = 'Log In Grace Age';
        $data['show_navbar'] = false;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['loggedin'] = 'not logged in';
        $data['page_content'] = 'Account/login.html';
        $this->parser->parse('master.php', $data);
    }

    public function loginPost() {
        $data['page_title'] = 'Log In Grace Age';
        $data['show_navbar'] = false;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['loggedin'] = 'wrong user';
        if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) { // check if input is set
            $username = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');

            $result = $this->Account_model->getUser($username);

            if ($result != NULL) {
                if (password_verify($password, $result["password"])) {
                    $result["password"] = NULL;

                    $this->session->set_userdata($result); // session contains the colums from the datebase + "userType"
                    if ($result["userType"] == "Patient") {
                        redirect(base_url() . 'ElderlyController/index');
                    } else { // userType = Caregiver
                        redirect(base_url() . 'CaregiverController/index');
                    }
                }
            }
            //the user does not exist or password is incorrect
            $data['page_content'] = 'Account/login.html';
            $this->parser->parse('master.php', $data);
        } else {    // the data from post is empty, do something
            $data['page_content'] = 'Account/login.html';   //reload the login page
            $this->parser->parse('master.php', $data);
        }
    }

    public function register() {
        $data['page_title'] = 'Register to Grace Age';
        $data['show_navbar'] = false;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['register_state'] = 'Ready to log in';
        $data['page_content'] = 'Account/register.html';
        $this->parser->parse('master.php', $data);
    }

    public function registerPost() {
        $data['page_title'] = 'Register to Grace Age';
        $data['show_navbar'] = false;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['register_state'] = 'invalid input, please fill in all fields'; // default case for register_state
        if (!empty($_POST["username"]) && !empty($_POST["password1"]) && !empty($_POST["password2"]) && !empty($_POST["usertype"])) { // check if none of the input is empty
            $usertype = filter_input(INPUT_POST, 'usertype');
            $username = filter_input(INPUT_POST, 'username');
            $password1 = filter_input(INPUT_POST, 'password1');
            $password2 = filter_input(INPUT_POST, 'password2');
            $this->load->database();
            if ($password1 === $password2) {
                $password = password_hash($password1, PASSWORD_DEFAULT);
                if ($this->Account_model->addUser($usertype, $username, $password)){
                    $data['register_state'] = 'Registration succeeds!';
                } else {
                    $data['register_state'] = 'This user has already been registered';
                }
            } 
            else {
                $data['register_state'] = 'The passwords are not the same!';
            }
        }
        $data['page_content'] = 'Account/register.html';
        $this->parser->parse('master.php', $data);
    }
    
    public function logOut() { //destroy current session and goto login page
        session_destroy();
        $this->login();
    }


}
