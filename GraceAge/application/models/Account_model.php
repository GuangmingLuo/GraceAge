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
       /*
        * @param: username of Patient or Caregiver. if caregivers and patients with the same username exist, it will return the patient
        * @return: array that represents a tuple from the db + usertype, eg the keys are the atribute names, Values are values
        */
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
    
    function changeLanguage($userType, $lang, $idPatient){
       // $sql = "UPDATE a16_webapps_2.? "
        //            . "SET Language = ?"
         //           . "WHERE id? = ?;";
        $this->db->set('Language', $lang);
        $this->db->where('id'.$userType, $idPatient);
        $this->db->update($userType);
        //$this->db->query($sql, array($userType,$lang,$userType, $idPatient));
    }
    
    function changePassword($userType,$password, $idPatient){
        $this->db->set('password', $password);
        $this->db->where('id'.$userType, $idPatient);
        $this->db->update($userType);
        
    }
    
//    function getProfileItems($name){
//        $result = $this->db->query("SELECT * FROM Patient where Name=?", $name);
//        $items = array(
//            'Birthday'=>$result->row()->Birthday,
//            'Gender' => $result->row()->Gender,
//            'PhoneNumber'=>$result->row()->PhoneNumber,
//        ); 
//        return $items;
//    }
}
