<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Account_model
 *
 * @author axel
 */
class Account_model extends CI_Model {

    //put your code here

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function getUser($username) {
        $query = $this->db->query("SELECT * FROM Patient where Name=?", $username);
        $row = $query->row_array();

        if (!isset($row)) { // not a patient
            $query = $this->db->query("SELECT * FROM Caregiver where Name=?", $username);
            $row = $query->row_array();
            if (!isset($row)) {
                return NULL;
            } // also not a caregiver
            else {
                $row["userType"] = "Caregiver"; // is a caregiver
                return $row;
            }
        } else {
            $row["userType"] = "Patient"; //is a patient
            return $row;
        }
    }

    function addUser($usertype, $language, $username, $password) {
        $query = $this->db->query("SELECT Name FROM Patient where Name=?", $username);
        $row = $query->row();
        if (isset($row)) {// existing patient
            return false; // no succes
        }
        $query = $this->db->query("SELECT Name FROM Caregiver where Name=?", $username);
        $row = $query->row();
        if (isset($row)) {// existing caregiver
            return false; // no succes
        }
        // make the new user
        $data = array(
            'Language' => $language,
            'Name' => $username,
            'password' => $password
        );
        $this->db->insert($usertype, $data);
        return true;
    }

}
