<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Reward_model extends CI_Model{
               
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
    }  
    
    function get_rewards_as_json() {
        $query = $this->db->select('*')->from('Rewards')->get();
        return json_encode($query->result());
    }
    
    function add_reward($reward){
        $data = array(
            'Reward' => $reward,
            'Price' => '10',
            'Language' => $this->session->Language,
        );
        $this->db->insert('a16_webapps_2.Rewards' , $data);
    }
}