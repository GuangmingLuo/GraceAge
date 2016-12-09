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
        $this->lang->load('topics', $this->session->Language);
        //$this->load->database();
    }

    function get_topics() {
        $query = $this->db->distinct()->select('Topic')->get('Question');
        return $query->result_array();
    }

    function get_topics_as_json() {
        $query = $this->db->distinct()->select('Topic')->get('Question');
        return json_encode($query->result_array());
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
        $query = $this->db->select('Question.Topic, Patient_Answered_Question.Answer')
                ->from('Question')
                ->join('Patient_Answered_Question', 'Question.QuestionNumber=Patient_Answered_Question.Question_Number')
                //->where('Language', $language)    //TODO Get the language from the logged in caregiver.
                ->where('DateTime >=', 'now() - INTERVAL 1 MONTH', FALSE)  //Get Answers from past month. FALSE has to be added for MYSQL functions.
                ->get();
        $this->all_answers = $query->result_array(); //Store the data in an array;
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
        $topic_array = $this->lang->line('topic_array');
        $scores = [];
        for ($j = 0; $j < count($topic_array); $j++) {
            $scores[$j + 1]['Topic'] = $topic_array[$j];
            $scores[$j + 1]['Score'] = $this->calculate_score($topics[$j]['Topic']);
        }
        $scores[$j + 1]['Topic'] = $this->lang->line('y_label');
        $scores[$j + 1]['Score'] = $this->lang->line('recent');
        return json_encode($scores);
    }

    function get_chart_title() {
        $title = $this->lang->line('chart_title');
        return json_encode($title);
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
                    $sum += $allanswers[$j]->Answer - 1;
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
        $display = array();


        if ($username == NULL) {        //give results for no name, all 0
            $query = $this->db->distinct()->select('Topic')->get('Question'); //get all the topics
            $topics = $query->result();
            for ($i = 0; $i < count($topics); $i++) {
                array_push($display, array('Topic' => $topics[$i]->Topic, 'Score' => 0));
            }
        } else {
            $language;
            $query = $this->db->select('Language')->where('Name', $username)->get('Patient');
            $language2 = $query->result();
            $language = $language2[0]->Language;


            $query = $this->db->distinct()->select('Topic')->where('Language', $language)->get('Question'); //get all the topics
            $topics = $query->result();
            $query = $this->db->select('idPatient')->where('Name', $username)->get('Patient');      //get id of patient to use 
            $id2 = $query->result();
            if (count($id2) == 0) {                     //if wrong name is given
                if (count($id2) == 0) {
                    for ($i = 0; $i < count($topics); $i++) {
                        array_push($display, array('Topic' => $topics[$i]->Topic, 'Score' => 0));
                    }
                }
            } else {
                $id = $id2[0]->idPatient;


                $query = $this->db->distinct()->select('QuestionNumber, Topic')->where('Language', $language)->get('Question'); //get all the question numbers allong with their topic
                $questions = $query->result();



                $query = $this->db->select('Answer, Question_Number')->where('Patient_idPatient', $id)->order_by('DateTime', 'DESC')->limit(52)->get('Patient_Answered_Question');  //get last answered questions and their number
                $answers = $query->result();
                $aaa = 0;


                for ($i = 0; $i < count($topics); $i++) {       //itterate through the topics
                    $current = $topics[$i]->Topic;

                    $topicscore = 0;
                    $topicavg;
                    $k = 0;     //amount of questions in the topic

                    for ($a = 0; $a < count($questions); $a++) {    //itterate through the questions
                        if ($topics[$i]->Topic == $questions[$a]->Topic) {  //if question is part of topic do...s
                            $questionnr = $questions[$a]->QuestionNumber;
                            for ($j = 0; $j < count($answers); $j++) {
                                if ($questionnr == $answers[$j]->Question_Number) {
                                    $topicscore += $answers[$j]->Answer - 1;
                                    $k++;
                                }
                            }
                        }
                    }
                    if ($k == 0) {
                        $topicavg = 0;
                    } else {
                        $topicavg = $topicscore * 25 / $k;
                    }
                    $nombre_format_francais = number_format($topicavg, 2, ',', ' ');
                    array_push($display, array('Topic' => $topics[$i]->Topic, 'Score' => $nombre_format_francais));
                }
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

    function getJSONtable() {
        $patients;
        $answers;
        $topics;
        $questions;
        $topicscore;
        $persontopic = array();
        $personavg;
        $resultarray = array();
        $resutls = array();
        $topicsenglish = $this->db->distinct()->select('Topic')->where('Language', 'english')->get('Question')->result();
        $topicsdutch = $this->db->distinct()->select('Topic')->where('Language', 'dutch')->get('Question')->result();
        $questionsenglish = $this->db->select('Topic, QuestionNumber')->where('Language', 'english')->get('Question')->result();
        $questionsdutch = $this->db->select('Topic, QuestionNumber')->where('Language', 'dutch')->get('Question')->result();
        
        

        $query = $this->db->select('idPatient, Name, Language')->get('Patient');
        $patients = $query->result();
        for ($i = 0; $i < count($patients); $i++) {
            //getting all data needed
           // $query = $this->db->select('Topic, QuestionNumber')->where('Language', $patients[$i]->Language)->get('Question');
            //$questions = $query->result();
            $query = $this->db->select('Answer, Question_Number')->where('Patient_idPatient', $patients[$i]->idPatient)->order_by('DateTime', 'DESC')->limit(52)->get('Patient_Answered_Question');
            $answers = $query->result();
           // $query = $this->db->distinct()->select('Topic')->where('Language', $patients[$i]->Language)->get('Question');
           // $topics = $query->result();
            
            if($patients[$i]->Language == 'dutch'){ //dutch case
                
            

            //calculate worst topic here
            for ($j = 0; $j < count($topicsdutch); $j++) { //go through every topic
                $score = 0;
                $amount = 0;
                $avg;
                for ($k = 0; $k < count($questionsdutch); $k++) {  //get all the question nrs for the topic -> different topics per language so this is needed
                    for ($l = 0; $l < count($answers); $l++) {   //go through all answers to see if matched with the topic
                        if ($topicsdutch[$j]->Topic == $questionsdutch[$k]->Topic) {
                            if ($questionsdutch[$k]->QuestionNumber == $answers[$l]->Question_Number) {
                                $score += $answers[$l]->Answer -1;
                                $amount++;
                            }
                        }
                    }
                }if($amount == 0){
                    $avg = 0;
                }else{
                $avg = $score * 25 / $amount;
                }
                array_push($persontopic, array( 'Score' => $avg, 'Topic' => $topicsdutch[$j]->Topic));
                foreach ($persontopic as $key => $row) {
                    $topic[$key] = $row['Topic'];
                    $scoree[$key] = $row['Score'];
                }


                array_multisort($scoree, SORT_ASC, $topic, SORT_ASC, $persontopic);
                $lowesttopic = array_slice($persontopic, 0, 1);
            }
            
            //calculate avg
            $amount = 0;
            $score = 0;
            for ($m = 0; $m < count($answers) ; $m++){
                $score += $answers[$m]->Answer -1;
                $amount++;
            }if($amount == 0){
                $personavg = 0;
                $nombre_format_francais = 0;
            }else{
            $personavg = $score * 25 / $amount;
            $nombre_format_francais = number_format($personavg, 2, ',', ' ');
            }
            array_push($resultarray, array('Name' => $patients[$i]->Name, 'Topic' => $lowesttopic[0]['Topic'], 'Score' => $nombre_format_francais));
            }else{ //english case
                //calculate worst topic here
            for ($j = 0; $j < count($topicsenglish); $j++) { //go through every topic
                $score = 0;
                $amount = 0;
                $avg;
                for ($k = 0; $k < count($questionsenglish); $k++) {  //get all the question nrs for the topic -> different topics per language so this is needed
                    for ($l = 0; $l < count($answers); $l++) {   //go through all answers to see if matched with the topic
                        if ($topicsenglish[$j]->Topic == $questionsenglish[$k]->Topic) {
                            if ($questionsenglish   [$k]->QuestionNumber == $answers[$l]->Question_Number) {
                                $score += $answers[$l]->Answer -1;
                                $amount++;
                            }
                        }
                    }
                }if($amount == 0){
                    $avg = 0;
                }else{
                $avg = $score * 25 / $amount;
                }
                array_push($persontopic, array( 'Score' => $avg, 'Topic' => $topicsenglish[$j]->Topic));
                foreach ($persontopic as $key => $row) {
                    $topic[$key] = $row['Topic'];
                    $scoree[$key] = $row['Score'];
                }


                array_multisort($scoree, SORT_ASC, $topic, SORT_ASC, $persontopic);
                $lowesttopic = array_slice($persontopic, 0, 1);
            }
            
            //calculate avg
            $amount = 0;
            $score = 0;
            for ($m = 0; $m < count($answers) ; $m++){
                $score += $answers[$m]->Answer -1;
                $amount++;
            }if($amount == 0){
                $personavg = 0;
                $nombre_format_francais = 0;
            }else{
            $personavg = $score * 25 / $amount;
            $nombre_format_francais = number_format($personavg, 2, ',', ' ');
            }
            array_push($resultarray, array('Name' => $patients[$i]->Name, 'Topic' => $lowesttopic[0]['Topic'], 'Score' => $nombre_format_francais));
            }
        }
        //echo json_encode($resultarray);
        //return json_encode($resultarray);
        return $resultarray;
    }

    function add_message($message) {
        if ($message == "" || strlen($message) > 255) {
            //error message, nothing filled in or too long
        } else {
            //echo "..".$message."..";
            $name = $this->session->Name;
            date_default_timezone_set("Europe/Brussels");
            $data = array(
                'Name' => $name,
                'Message' => $message,
                'Date' => date('Y-m-d H:i:s')
            );
            $this->db->insert('a16_webapps_2.Messages', $data);
        }
    }

    function show_messages() {
        $messages;
        $query = $this->db->select('Name, Message, Date')->order_by('Date', 'DESC')->limit(6)->get('Messages');
        $messages = $query->result();
        $result = array();
        $messageshow = array();

        for ($i = count($messages) - 1; $i >= 0; $i--) {
            // $date = strtotime($$messages[$i]['Date']);
            //$mysqldate = date( 'Y-m-d H:i:s', $date );
            array_push($result, array('Name' => $messages[$i]->Name, 'Message' => $messages[$i]->Message, 'Date' => $messages[$i]->Date));
        }
//        for($j = 0; $j < count($result); $j++){
//            echo " ".$result[$j]['Message'];
//        }
        return $result;
    }

}
