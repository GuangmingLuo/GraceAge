<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AccountController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('parser');
        $this->load->helper('url');
        $this->load->database();
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
            $query = $this->db->query("SELECT Name, password FROM Patient where Name=?", $username);
            $row = $query->row();

            if (isset($row)) {
                if (password_verify($password, $row->password)) {
                    $data['loggedin'] = 'valid user';
                    redirect(base_url() . 'ElderlyController/index');
                }
            }
            $data['page_content'] = 'Account/login.html';
            $this->parser->parse('master.php', $data);
        } else {
            $data['page_content'] = 'Account/login.html';
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
        $data['register_state'] = $this->Account_model->get_register_state();
        $data['page_content'] = 'Account/register.html';
        $this->parser->parse('master.php', $data);
    }

}
