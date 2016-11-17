<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Question_model
 *
 * @author orditech
 */

class Question_model extends CI_Model{
    
    private $answers;
    private $navigationbuttons;
            
    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->answers = array(
            array('name' => 'Nooit', 'title' => 'Nooit', 'className' => 'answer_button'),
            array('name' => 'Zelden', 'title' => 'Zelden', 'className' => 'answer_button'),
            array('name' => 'Soms', 'title' => 'Soms', 'className' => 'answer_button'),
            array('name' => 'Meestal', 'title' => 'Meestal', 'className' => 'answer_button'),
            array('name' => 'Altijd', 'title' => 'Altijd', 'className' => 'answer_button'),
        );
        
        $this->navigationbuttons = array(
            array('name' => '<-- Ga terug naar de vorige vraag', 'title' => 'Vorige vraag', 'func' => 'previous()'),
            array('name' => 'Ga verder naar de volgende vraag -->', 'title' => 'Volgende vraag', 'func' => 'next()'),
        );
    }
    
    
    function get_answerbuttons(){
        //$this->set_active();
        return $this->answers;
    }
    
    function get_navigationbuttons(){
        return $this->navigationbuttons;
    }
       
    function get_question($i){
        //$i = 1;
        $query = $this->db->select('Topic, Question')->where('idQuestion', $i)->get('Question');

        //$query = $this->db->get('Question'); //Select all rows and columns from the table
        //echo $this->db->last_query();
        return $query->result();
    }
    
    function get_question_as_json($i){
        //$i = 1;
        $query = $this->db->select('Topic, Question')->where('idQuestion', $i)->get('Question');

        //$query = $this->db->get('Question'); //Select all rows and columns from the table
        //echo $this->db->last_query();
        return json_encode($query->result());
    }
    
    function submit_answer($answer, $q_id){
        $this->db->reconnect();
        $data = array(
            'Patient_idPatient' => 2, // Dummy, will be edited later on
            'Question_idQuestion' => $q_id,
            'Questionaire_Number' => 1, // Dummy, update later!
            'Answer' => $answer,
            'DateTime' => date('Y-m-d H:i:s')
        );
        $this->db->insert('a16_webapps_2.Patient_Answered_Question', $data);
    }

}
