<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Caregiver_Home_model
 *
 * @author orditech
 */
class Caregiver_Home_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        //$this->load->database();
    }
    
    function get_topics(){
        $query = $this->db->distinct()->select('Topic')->get('Question');
        return $query->result();
    }
    
    function get_name($id){
        $query = $this->db->query("SELECT Name FROM a16_webapps_2.Caregiver WHERE idCaregiver = " . $id);
        return $query->row()->Name;
    }
    
    function get_patients(){
        $query = $this->db->select('Name')->get('Patient');
        return $query->result();
    }
}
