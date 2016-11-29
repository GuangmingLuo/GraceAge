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
        $this->load->library('session');
        $this->load->helper('date');
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
        $language = $this->session->userdata('Language');
        
        $query = $this->db->select('Question.Topic, Patient_Answered_Question.Answer')
                ->from('Question')
                ->join('Patient_Answered_Question', 'Question.QuestionNumber=Patient_Answered_Question.Question_Number')
                ->where('Language', 'dutch')    //TODO Get the language from the logged in caregiver.
                ->where('DateTime >=', 'now() - INTERVAL 1 MONTH', FALSE)  //Get Answers from past month. FALSE has to be added for MYSQL functions.
                ->get();
        $this->all_answers = $query->result_array(); //Store the data in an array;
        return $language;
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
        $i = 1;
        for($j = 0; $j < count($topics); $j++){
            $scores[$i]['Topic'] = $topics[$j]['Topic'];
            $scores[$i]['Score'] = $this->calculate_score($topics[$j]['Topic']);
            //$scores[$topics[$j]['Topic']] = $this->calculate_score($topics[$j]['Topic']);
            $i++;
        }
        //echo json_encode($scores);
        return json_encode($scores);    //TODO: Don't send topic as key but as another value.
    }
    function get_topics_with_lowest_scores($number){
        $this->get_answer_array();
        $topics = $this->get_topics();
        $n = count($topics);
        $scores;        
        for($i = 0,$j = 0; $j < $n; $j++,$i++){
            $scores[$i]['Topic'] = $topics[$j]['Topic']; //echo $scores[$i]['Topic'];
            $scores[$i]['Score'] = $this->calculate_score($topics[$j]['Topic']); //echo round($scores[$i]['Score'],2);
        }
        for($i = 0;$i < $n;$i++) {
            for($j = 1;$j < ($n - $i);$j++) {
                if ($scores[$j - 1]['Score'] > $scores[$j]['Score']) {
                    //swap the elements!
                    $temp['Score'] = $scores[$j - 1]['Score'];
                    $temp['Topic'] = $scores[$j - 1]['Topic'];
                    $scores[$j-1]['Score'] = $scores[$j]['Score'];
                    $scores[$j-1]['Topic'] = $scores[$j]['Topic'];
                    $scores[$j]['Score'] = $temp['Score'];
                    $scores[$j]['Topic'] = $temp['Topic'];
                }
            }
        }
        $topics;
        for($i = 0;$i < $number;$i++) {
            $topics[$i] = $scores[$i]['Topic']; //echo $topics[$i];
        }
        return $topics;    
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
    //calculate topic scores and average score for ginven userid
    function calculate_topic_testplsignore($username) {
        $query = $this->db->query("SELECT idPatient FROM Patient where Name=?", $username);
        $result = $query->row();
        if (!isset($result)) {
            return "wrong name";
        }
        $userid = $result->idPatient;
        $query = $this->db->query('SELECT DISTINCT QuestionNUmber FROM a16_webapps_2.Question;');
        $numberOfQuestions = count($query->result()); // slect the latest 52 question number, topic, answer etc from user with id $userid
        $sql = "SELECT a.Question_Number, q.Topic, a.Answer, a.Questionaire_Number, a.DateTime FROM a16_webapps_2.Patient_Answered_Question AS a, a16_webapps_2.Question AS q WHERE a.Patient_idPatient = ? AND q.idQuestion = a.Question_Number ORDER BY a.DateTime DESC LIMIT ?;";
        $query = $this->db->query($sql, array($userid, $numberOfQuestions));
        $result = $query->result();
        $answerScore = array(); // keeps the score per topic
        $questionCount = array(); // keeps the amount of questions per topic
        $totalScore = 0; // total score of all answers
        foreach ($result as $row) {// go through the answers
            if (isset($answerScore[$row->Topic]))
                $answerScore[$row->Topic] += $row->Answer; //if the topic is not yet in array
            else
                $answerScore[$row->Topic] = $row->Answer;

            if (isset($questionCount[$row->Topic]))
                $questionCount[$row->Topic] ++;
            else
                $questionCount[$row->Topic] = 1;
            $totalScore += $row->Answer;
        }
        foreach ($answerScore as $k => $v) { // every score * 25 / amount of topic questions
            $temp = $v * 25 / $questionCount[$k];
            $answerScore[$k] = $nombre_format_francais = number_format($temp, 2, ',', ' ');
        }
        $avg = $totalScore * 25 / $numberOfQuestions;
        $answerScore["Average"] = $nombre_format_francais = number_format($avg, 2, ',', ' ');

        return $answerScore;
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
