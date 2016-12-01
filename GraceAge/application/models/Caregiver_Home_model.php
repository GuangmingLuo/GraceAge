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

    function get_answer_array() {
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

    function calculate_score($topic) {
        $sum_of_answers = 0;
        $iterations = 0;
        for ($i = 0; $i < count($this->all_answers); $i++) {
            if ($this->all_answers[$i]['Topic'] == $topic) {
                $sum_of_answers += $this->all_answers[$i]['Answer'];
                $iterations++;
            }
        }
        return ($sum_of_answers / $iterations) * 25;
    }

    function get_topic_with_score() {
        $this->get_answer_array();
        $topics = $this->get_topics();
        $scores;
        $i = 1;
        for ($j = 0; $j < count($topics); $j++) {
            $scores[$i]['Topic'] = $topics[$j]['Topic'];
            $scores[$i]['Score'] = $this->calculate_score($topics[$j]['Topic']);
            //$scores[$topics[$j]['Topic']] = $this->calculate_score($topics[$j]['Topic']);
            $i++;
        }
        //echo json_encode($scores);
        return json_encode($scores);    //TODO: Don't send topic as key but as another value.
    }

    function get_topics_with_lowest_scores($number) {
        $this->get_answer_array();
        $topics = $this->get_topics();
        $n = count($topics);
        $scores;
        for ($i = 0, $j = 0; $j < $n; $j++, $i++) {
            $scores[$i]['Topic'] = $topics[$j]['Topic']; //echo $scores[$i]['Topic'];
            $scores[$i]['Score'] = $this->calculate_score($topics[$j]['Topic']); //echo round($scores[$i]['Score'],2);
        }
        for ($i = 0; $i < $n; $i++) {
            for ($j = 1; $j < ($n - $i); $j++) {
                if ($scores[$j - 1]['Score'] > $scores[$j]['Score']) {
                    //swap the elements!
                    $temp['Score'] = $scores[$j - 1]['Score'];
                    $temp['Topic'] = $scores[$j - 1]['Topic'];
                    $scores[$j - 1]['Score'] = $scores[$j]['Score'];
                    $scores[$j - 1]['Topic'] = $scores[$j]['Topic'];
                    $scores[$j]['Score'] = $temp['Score'];
                    $scores[$j]['Topic'] = $temp['Topic'];
                }
            }
        }
        $topics;
        for ($i = 0; $i < $number; $i++) {
            $topics[$i] = $scores[$i]['Topic']; //echo $topics[$i];
        }
        return $topics;
    }



    function get_username_id() {
        $namesid;
        $query = $this->db->select('Name, idPatient')->get('Patient');
        return $query->result();
    }

    function calculate_avg() {
        $namesid;
        $allanswers;
        $temp;
        $temp2;
        $results = array();
        $avg = 0;
        $k = 0;
        $query = $this->db->select('Name, idPatient')->order_by('idPAtient', 'ASC')->get('Patient');
        $names = $query->row()->Name;

        $namesid = $query->result();
        for ($i = 0; $i < count($namesid); $i++) {      //get all last answers from all patients
            $id = $namesid[$i]->idPatient;
            $query2 = $this->db->select('Patient_idPatient, Answer')->where('Patient_idPatient', $id)->order_by('DateTime', 'DESC')->limit(52)->get('Patient_Answered_Question');
            $temp = $query2->result();
            if (isset($temp2)) {
                $allanswers = array_merge($temp2, $temp);
            } else {
                $allanswers = $temp;
            }

            $temp2 = $allanswers;
        }
        for ($i = 0; $i < count($namesid); $i++) {          //itterate through all patients
            $sum = 0;
            $id = $namesid[$i]->idPatient;
            for ($j = 0; $j < count($allanswers); $j++) {   //itterate through all the answers the patient has answered
                if ($allanswers[$j]->Patient_idPatient == $namesid[$i]->idPatient) {
                    $sum += $allanswers[$j]->Answer;
                    $k++;
                }
            }
            if ($k == 0) {
                $avg = 0;
            } else {
                $avg = $sum * 25 / $k;
            }
            $k = 0;
            $nombre_format_francais = number_format($avg, 2, ',', ' ');

            if ($avg != 100 && $avg != 0) {     //check if the patient has allready filled in answers
                array_push($results, array('Score' => $nombre_format_francais, 'Name' => $namesid[$i]->Name));
            }
        }
        foreach ($results as $key => $row) {
            $score[$key] = $row['Score'];
            $name[$key] = $row['Name'];
        }


        array_multisort($score, SORT_ASC, $name, SORT_ASC, $results);
        $urgent = array_slice($results, 0, 10);

        return $urgent;
    }

    function calculate_topic_eff($username) {

        $answerspatient;
        $display = array();
        $query = $this->db->distinct()->select('Topic')->get('Question'); //get all the topics
        $topics = $query->result();


        if ($username == NULL) {
            for($i = 0; $i < count($topics) ; $i++){
                array_push($display, array('Topic' => $topics[$i]->Topic, 'Score' => 0));
            }
        }else{
        $query = $this->db->select('idPatient')->where('Name', $username)->get('Patient');
        $id2 = $query->result();
        $id = $id2[0]->idPatient;

        $query = $this->db->distinct()->select('QuestionNumber, Topic')->get('Question');
        $questions = $query->result();

        

        $query = $this->db->select('Answer, Question_Number')->where('Patient_idPatient', $id)->order_by('DateTime', 'DESC')->limit(52)->get('Patient_Answered_Question');  //get last answered questions
        $answers = $query->result();
        $aaa =0;
        

        for ($i = 0; $i < count($topics); $i++) {
            $current = $topics[$i]->Topic;
            //echo " ".$current;
            $topicscore=0;
            $topicavg;
            $k = 0;
            $neededquestions;
            for ($a = 0 ; $a < count($questions); $a++) {
                if($topics[$i]->Topic == $questions[$a]->Topic) {
                    $questionnr = $questions[$a]->QuestionNumber;
                    for($j = 0; $j < count($answers) ; $j++){
                        if($questionnr == $answers[$j]->Question_Number){
                            $topicscore += $answers[$j]->Answer;
                            $k++;
                        }
                    }
                    
                }
            }
            $topicavg = $topicscore * 25 / $k;
            $nombre_format_francais = number_format($topicavg, 2, ',', ' ');
            array_push($display, array('Topic' => $topics[$i]->Topic, 'Score' => $nombre_format_francais));
        }
        
        }
        return $display;
    }

    

    

    

    function current_user($username) {
        if ($username == NULL) {
            return " nobody, please select someone.";
        }
        return $username;
    }

}
