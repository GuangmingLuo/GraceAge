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
        $this->answers = array(
            array('name' => 'Nooit', 'title' => 'Nooit', 'className' => 'active'),
            array('name' => 'Zelden', 'title' => 'Zelden', 'className' => 'inactive'),
            array('name' => 'Soms', 'title' => 'Soms', 'className' => 'inactive'),
            array('name' => 'Meestal', 'title' => 'Meestal', 'className' => 'inactive'),
            array('name' => 'Altijd', 'title' => 'Altijd', 'className' => 'inactive'),
        );
        
        $this->navigationbuttons = array(
            array('name' => '<-- Ga terug naar de vorige vraag', 'title' => 'Vorige vraag', 'func' => '<?php echo base_url()?>GraceAgeController/question'),
            array('name' => 'Ga verder naar de volgende vraag -->', 'title' => 'Volgende vraag', 'func' => '<?php echo base_url()?>GraceAgeController/question'),
        );
    }
    
    function set_active($answer) {
        foreach ($this->answers as &$item) { // reference to item!!
            if (strcasecmp($answer, $item['name']) == 0) {
                $item['className'] = 'active';
            } else {
                $item['className'] = 'inactive';
            }
        }
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
        echo $this->db->last_query();
        return $query->result();
    }

}
