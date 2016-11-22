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

    public function __construct() {
        parent::__construct();
        //$this->load->database();
    }

    function get_topics() {
        $query = $this->db->distinct()->select('Topic')->get('Question');
        return $query->result();
    }

    function get_name($id) {
        $query = $this->db->query("SELECT Name FROM a16_webapps_2.Caregiver WHERE idCaregiver = " . $id);
        return $query->row()->Name;
    }

    function get_patients() {
        $query = $this->db->select('Name')->get('Patient');
        return $query->result();
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
        $id2 = $id[0]->idPatient;

        $query2 = $this->db->select('idQuestion')->get('Question');
        $questions = $query2->result();
        $amount = count($questions);

        unset($questionids);
        $questionids = array();

        for ($x = 0; $x < $amount; $x++) {
            $questionids[$x] = $questions[$x]->idQuestion;
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
