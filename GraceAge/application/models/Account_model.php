<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Account_model extends CI_Model{
    
    function get_register_state(){
        if (!empty($_POST["username"]) && !empty($_POST["password1"]) && !empty($_POST["password2"]) && !empty($_POST["usertype"])) { // check if none of the input is empty
            $usertype = filter_input(INPUT_POST, 'usertype');
            $username = filter_input(INPUT_POST, 'username');
            $password1 = filter_input(INPUT_POST, 'password1');
            $password2 = filter_input(INPUT_POST, 'password2');
            $query = $this->db->query("SELECT Name FROM Patient where Name=?", $username);
            $row = $query->row();
            if (isset($row)) {
                return 'This user has already been registered';
            } elseif ($password1 === $password2) {
                $password = password_hash($password1, PASSWORD_DEFAULT);
                $this->db->query("INSERT INTO $usertype (Name, password) VALUES ('$username','$password')");
                return 'Registration succeeds!';
            } else {
                return 'The passwords are not the same!';
            }
        }else{
            return 'invalid input, please fill in all fields';
        }
    }
    
    function get_login_state(){
        if (isset($_POST["username"]) && !empty($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["password"])) { // check if input is set
            $username = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');
            $query = $this->db->query("SELECT Name, password FROM Patient where Name=?", $username);
            $row = $query->row();
            if (isset($row)) {
                if (password_verify($password, $row->password)) {
                    redirect(base_url() . 'ElderlyController/index');
                    return 'Valid user';    
                }else{
                    return 'Password is wrong';
                }
            }else{
                return 'Invalid user'; 
            }
        } else {
            return 'Please fill in both username and password';
        }
    }
}