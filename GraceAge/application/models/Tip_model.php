<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Tip_model extends CI_Model{
               
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->lang->load('tip', $this->session->Language);
        
        
        $this->navigationbuttons = array(
            array('name' => $this->lang->line('return_to_question'),'class' => 'btn  btn-arrow-left btn-block',  'func' => 'back()'),
            array('name' => $this->lang->line('confirm_weekly_goal'),'class' => 'btn btn-arrow-right btn-block', 'func' => 'forward()'),
        );
    }    
    
    function get_tip($i) {
        $query = $this->db->select($this->session->Language)->where('idtips', $i)->get('tips');
        if($this->session->Language === 'english'){
            return $query->row()->english;
        }
        if($this->session->Language === 'dutch'){
            return $query->row()->dutch;
        }
        
        
    }
    
    function get_navigationbuttons(){
        return $this->navigationbuttons;
    }
}