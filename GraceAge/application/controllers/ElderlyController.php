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
class ElderlyController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->load->library('parser'); //This will allow us to use the parser in function index.
        $this->load->helper('url'); //This allows to use the base_url function for loading the css.
        $this->load->model('Menu_model');
        $this->load->model('Question_model');
        $this->session->set_userdata('patient_id', 2); // Assume user 2 for now!
    }

    function index() {
        if ($this->session->isLoggedIn == true) { // if session exists
            $data['show_navbar'] = true;
            $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
            $data['page_title'] = 'Elderly Home';
            $data['header1'] = 'Welcome to Elderly Home';
            $data['menu_items'] = $this->Menu_model->get_menuitems('Home');
            $data['content'] = "This is the home page! welcome " . $this->session->Name;
            $data['page_content'] = 'Elderly/index.html';
            $this->parser->parse('master.php', $data);
        } else {
            echo "You should not be here!!!";
        }
    }

    /*     * ************* All Questionnaire page functions *********************** */

    function questionnaire() {
        //Go fetch necessary data from database to setup the correct question.
        $this->Question_model->get_initial_state();

        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['page_title'] = 'Questionnaire';
        $data['header1'] = 'Questionnaire';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Questionnaire');
        $data['answers'] = $this->Question_model->get_answerbuttons();
        $data['navigationbuttons'] = $this->Question_model->get_navigationbuttons();
        $data['questions'] = $this->Question_model->get_question($this->session->question_id);
        $data['page_content'] = 'Elderly/questionnaire.php';
        $this->parser->parse('master.php', $data);
    }

    function previous() {
        $this->output->set_content_type("application/json")->append_output(
                $this->Question_model->get_previous_question_as_json());
    }

    function next() {
        $this->output->set_content_type("application/json")->append_output(
                $this->Question_model->get_next_question_as_json());
    }

    function answer_clicked() {
        $clicked = $this->input->post('clicked');
        $this->session->set_userdata('selected_answer', $clicked);
    }

    /*     * **********************End of Questionnaire functions*************************** */

    function tips() {
        $data['show_navbar'] = true;
        $data['navbar_content'] = 'Elderly/elderlyNavbar.html';
        $data['page_title'] = 'Tips';
        $data['header1'] = 'Tip of the day';
        $data['menu_items'] = $this->Menu_model->get_menuitems('Tips');
        $data['content'] = "This is the Tips page!";
        $data['page_content'] = 'Elderly/tips.html';
        $this->parser->parse('master.php', $data);
    }

}

