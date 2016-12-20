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
    function get_rewards() {
        $query = $this->db->query("select Reward, Price, Language, Available from Rewards order by Available=' ', Id desc");
        return $query->result();
    }
    
    function rewardExists($reward){
        $query = $this->db->query('SELECT Reward FROM Rewards where Reward=?', $reward);
        if ($query->num_rows()==0) {
            return false;
        }
        else {
            return true;
        }
    }
    
    function add_reward($reward,$price, $language){
        if ($this->rewardExists($reward)) {
            return false;
        }
        else {
            $data = array(
            'Reward' => $reward,
            'Price' => $price,
            'Language' => $language
        );
        $this->db->insert('a16_webapps_2.Rewards' , $data);
        return true;
        }
    }
    
    function edit_reward($reward,$available){
        $data = array(
            'Available' => $available
        );
        $this->db->where('Reward' , $reward);
        $this->db->update('Rewards' , $data);
    }
}