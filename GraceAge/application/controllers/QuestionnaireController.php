<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QuestionnaireController extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library('parser');
    }
    
    //go to this url to see the result:
    //http://localhost/GraceAge/index.php/QuestionnaireController/getQuestions
    
    public function getQuestions() {
        $this->load->database();
        $query = $this->db->query("SELECT Question FROM Question");
        $data['questions'] = $query->result_array();
        $this->parser->parse('Questionnaire/questionnaire.html', $data);
    }
    
    public function getUnansweredQuestionByUser() {
        
    }
    
    public function answerQuestions() {
        
    }
}