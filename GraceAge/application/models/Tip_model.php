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
            array('name' => $this->lang->line('go_to_score'),'class' => 'btn btn-arrow-right btn-block', 'func' => 'forward()'),
        );
    }    
    
    function get_tip($i) {
        $random = rand(1, 2);
        $query = $this->db->select($this->session->Language)->where('topic', $i)->get('tips');
        if($this->session->Language === 'english'){
            return $query->row($random)->english;
        }
        if($this->session->Language === 'dutch'){
            return $query->row($random)->dutch;
        }
        
        
    }
    function get_tips_as_json($topic) {
        $query = $this->db->select($this->session->Language)->select('tips.idtips')->where('topic', $topic)->get('tips');
        return json_encode($query->result());
    }
    
    function add_tip($topic, $tip){
        $data = array(
            'topic' => $topic,
            $this->session->Language => $tip
        );
        $this->db->insert('a16_webapps_2.tips' , $data);
    }
    
    function remove_tip($tipId){
        $this->db->where('idtips', $tipId);
        $this->db->delete('tips');
    }
    
    function get_navigationbuttons(){
        return $this->navigationbuttons;
    }
}