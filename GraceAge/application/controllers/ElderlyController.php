<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GraceAgeController
 *
 * @author orditech
 */
class ElderlyController extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('parser'); //This will allow us to use the parser in function index.
        $this->load->helper('url'); //This allows to use the base_url function for loading the css.
        $this->load->model('Menu_model');
        $this->load->model('Question_model');
        //echo 'id was reset';
        if(!$this->session->has_userdata('id')) {
            $this->session->set_userdata('id', 1);
        }
        else if ($this->session->id > 52){
            $this->session->set_userdata('id', 1);
        }
        else if ($this->session->id < 1){
            $this->session->set_userdata('id', 1);
        }
    }
    
    function index(){
        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['page_title'] = 'Elderly Home';
        $data['header1'] = 'Welcome to Elderly Home';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Home');
        $data['content'] = "This is the home page!";
        $data['page_content']='Elderly/index.html';
        $this->parser->parse('master.php',$data);
    }
    
    
    /*************** All Questionnaire page functions ************************/
            
    function questionnaire(){
        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['page_title'] = 'Questionnaire';
        $data['header1'] = 'Questionnaire';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Questionnaire');
        $data['answers'] = $this->Question_model->get_answerbuttons();
        $data['navigationbuttons'] = $this->Question_model->get_navigationbuttons();
        $data['questions'] = $this->Question_model->get_question($this->session->userdata('id'));;
        $data['page_content']='Elderly/questionnaire.php';
        $this->parser->parse('master.php',$data);
    }
    
        function previous(){
        $this->session->set_userdata('id', $this->session->id - 1);
        $this->output->set_content_type("application/json")->append_output(
                $this->Question_model->get_question_as_json($this->session->id));
    }
    
    function next(){
        $this->Question_model->submit_answer($this->session->selected_answer, $this->session->id);
        $this->session->set_userdata('id', $this->session->id +1);
        $this->output->set_content_type("application/json")->append_output(
                $this->Question_model->get_question_as_json($this->session->id));
        
    }
    
    function answer_clicked(){
        $received_JSON = $this->input->post('answer_clicked');
        $clicked = $received_JSON.clicked;
        $this->session->set_userdata('selected_answer', $clicked);
    }
    
    /************************End of Questionnaire functions****************************/
    
    function tips() {
        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['page_title'] = 'Tips';
        $data['header1'] = 'Tip of the day';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Tips');
        $data['content'] = "This is the Tips page!";
        $data['page_content']='Elderly/tips.html';
        $this->parser->parse('master.php',$data);
    }
}
