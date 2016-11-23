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
class Caregiver_Home_model extends CI_Model {
    
    private $all_answers;

    public function __construct() {
        parent::__construct();
        //$this->load->database();
    }

    function get_topics() {
        $query = $this->db->distinct()->select('Topic')->get('Question');
        return $query->result_array();
    }

    function get_name($id) {
        $query = $this->db->query("SELECT Name FROM a16_webapps_2.Caregiver WHERE idCaregiver = " . $id);
        return $query->row()->Name;
    }

    function get_patients() {
        $query = $this->db->select('Name')->get('Patient');
        return $query->result();
    }

    function get_answer_array(){
        $query = $this->db->select('Question.Topic, Patient_Answered_Question.Answer')
                ->from('Question')
                ->join('Patient_Answered_Question', 'Question.QuestionNumber=Patient_Answered_Question.Question_Number')
                ->where('Language', 'dutch')
                ->get();
        $this->all_answers = $query->result_array(); //Store the data in an array;
        //print_r($this->all_answers);
        //echo count($this->all_answers);
    }
    
    function calculate_score($topic){
        $sum_of_answers = 0;
        $iterations = 0;
        for($i = 0; $i < count($this->all_answers); $i++){
            if($this->all_answers[$i]['Topic'] == $topic){
                $sum_of_answers += $this->all_answers[$i]['Answer'];
                $iterations++;
            }
        }
        return ($sum_of_answers/$iterations) * 25;
    }
    
    function get_topic_with_score(){
        $this->get_answer_array();
        $topics = $this->get_topics();
        $scores;
        for($j = 0; $j < count($topics); $j++){
            $scores[$topics[$j]['Topic']] = $this->calculate_score($topics[$j]['Topic']);
        }
        //echo json_encode($scores);
        return json_encode($scores);
    }
            
    function print_score($username) {
        /* $query = $this->db->query("SELECT idPatient FROM Patient where Name= '?' ", $username); */
        $query = $this->db->select('idPatient')->where('Name', $username)->get('Patient');
        $id = $query->result();
        $id2 = $id[0]->idPatient;
        $query2 = $this->db->select('Answer')->where('Patient_idPatient', $id2)->order_by('DateTime', 'DESC')->limit(1)->get('Patient_Answered_Question');
        return $query2->result();
    }

    function calculate_topic($username, $topic) {
        if($username == NULL){
           return 0;
        }
        $query = $this->db->select('idPatient')->where('Name', $username)->get('Patient');
        $id = $query->result();
        if(count($id)==0){
            return "wrong name";
        }
        $id2 = $id[0]->idPatient;

        $query = $this->db->distinct()->select('QuestionNumber')->where('Topic', $topic)->get('Question');
        $questions = $query->result();
        $amount = count($questions);

        unset($questionids);
        $questionids = array();

        for ($x = 0; $x < $amount; $x++) {
            $questionids[$x] = $questions[$x]->QuestionNumber;
        }

        unset($antwoordenarray);
        $antwoordenarray = array();

        foreach ($questionids as $idss) {
            $voorwaarde = array('Patient_idPatient' => $id2, 'Question_Number' => $idss);
            $query = $this->db->select('Answer')->where($voorwaarde)->order_by('DateTime', 'DESC')->limit(1)->get('Patient_Answered_Question');
            $antwoord = $query->result();
            if(count($antwoord) == 0){
                
                return 0;
            }
            $antwoord2 = $antwoord[0]->Answer;
            array_push($antwoordenarray, $antwoord2);
        }
        $som = array_sum($antwoordenarray) * 25 / $amount;
        
        $nombre_format_francais = number_format($som, 2, ',', ' ');
        return $nombre_format_francais;
    }
    
    function average_score($username){
        if($username == NULL){
           return 0;
        }
        $query = $this->db->select('idPatient')->where('Name', $username)->get('Patient');
        $id = $query->result();
        if(count($id)==0){
            return "wrong name";
        }
        $id2 = $id[0]->idPatient;

        $query2 = $this->db->distinct()->select('QuestionNumber')->get('Question');
        $questions = $query2->result();
        $amount = count($questions);

        unset($questionids);
        $questionids = array();

        for ($x = 0; $x < $amount; $x++) {
            $questionids[$x] = $questions[$x]->QuestionNumber;
        }
        unset($antwoordenarray);
        $antwoordenarray = array();

        foreach ($questionids as $idss) {
            $voorwaarde = array('Patient_idPatient' => $id2, 'Question_Number' => $idss);
            $query = $this->db->select('Answer')->where($voorwaarde)->order_by('DateTime', 'DESC')->limit(1)->get('Patient_Answered_Question');
            $antwoord = $query->result();
            if(count($antwoord) == 0){
                return 0;
            }
            $antwoord2 = $antwoord[0]->Answer;
            array_push($antwoordenarray, $antwoord2);
        }
        $som = array_sum($antwoordenarray) * 25 / $amount;
        
        $nombre_format_francais = number_format($som, 2, ',', ' ');
        return $nombre_format_francais;
        
    }
    function current_user($username){
        if($username == NULL){
            return " nobody, please select someone.";
        }
        return $username;
    }

}
