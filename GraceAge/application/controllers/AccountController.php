<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccountController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('parser');
    }
    //go to this url to see the result:
    //http://localhost/GraceAge/index.php/AccountController/login
    
    public function login() {
        $data['loggedin'] = 'not logged in';
        $this->parser->parse('Account/login.html',$data);
    }
    
    public function loginPost() {
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');
        $this->load->database();
        $query = $this->db->query("SELECT Name, password FROM Patient where Name=?", $username);
        $row = $query->row();
        $data['loggedin'] = 'wrong user';
        if (isset($row)) {
            if (password_verify($password, $row->password)) {
                $data['loggedin'] = 'valid user'; } 
             }
        $this->parser->parse('Account/login.html',$data);
    }
}
