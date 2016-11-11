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
        $data['page_title'] = 'Grace Age Questionnaire';
        $data['header1'] = 'Interrai Questionnaire';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Questionnaire');
        $this->parser->parse('template', $data);
    }
    
    function previous(){
        $this->session->set_userdata('id', $this->session->id - 1);
        $this->output->set_content_type("application/json")->append_output(
                $this->Question_model->get_question_as_json($this->session->id));
    }
    
    function next(){
        $this->session->set_userdata('id', $this->session->id +1);
        $this->output->set_content_type("application/json")->append_output(
                $this->Question_model->get_question_as_json($this->session->id));
    }
            
    function question(){
        //$this->load->library('session');
        
        $data['answers'] = $this->Question_model->get_answerbuttons();
        $data['navigationbuttons'] = $this->Question_model->get_navigationbuttons();
        $data2['questions'] = $this->Question_model->get_question($this->session->userdata('id'));
        //echo '       ', $this->session->userdata('id');
        $data['content'] = $this->parser->parse('question', $data2, true);
        $this->parser->parse('template', $data);
    }
    
    function home() {
        $data['page_title'] = 'Grace Age home';
        $data['header1'] = 'Welcome';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Home');
        $data['content'] = "This is the home page!";
        $this->parser->parse('template', $data);
    }
    
    function tips() {
        $data['page_title'] = 'Tips tips tips';
        $data['header1'] = 'Tip of the day';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Tips');
        $data['content'] = "An apple a day, keeps the doctor away!";
        $this->parser->parse('template', $data);
    }
}
