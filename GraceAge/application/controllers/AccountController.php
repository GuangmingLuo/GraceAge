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
                $data['loggedin'] = 'valid user';
            }
        }
        $this->parser->parse('Account/login.html',$data);
    }
    
    public function register() {
        $data['register_state'] = 'Ready to log in';
        $this->parser->parse('Account/register.html', $data);
    }

    public function registerPost() {
        $usertype = filter_input(INPUT_POST, 'usertype');        
        $username = filter_input(INPUT_POST, 'username');
        $password1 = filter_input(INPUT_POST, 'password1');
        $password2 = filter_input(INPUT_POST, 'password2');
        $this->load->database();
        $query = $this->db->query("SELECT Name FROM Patient where Name=?", $username);
        $row = $query->row();
        if (isset($row)) {
            $data['register_state'] = 'This user has been registered';
        }elseif($password1 === $password2){
            $password = password_hash($password1, PASSWORD_DEFAULT);
            $this->db->query("INSERT INTO $usertype (Name, password) VALUES ('$username','$password')" );
            $data['register_state'] = 'Registration succeeds!';
        }else{
            $data['register_state'] = 'The passwords are not the same!';
        }
        $this->parser->parse('Account/register.html', $data);
    }

}
