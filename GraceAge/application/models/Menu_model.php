<?php
 
class Menu_model extends CI_Model {
 
    private $menu_items;
     
    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->lang->load('elderly', $this->session->Language);
        
        $this->menu_items = array(
            array('id'=>'Home_button', 'name' => $this->lang->line('return_to_home'),'iconName' => 'fa fa-repeat', 'title' => 'Go Home', 'link' => 'index', 'className' => 'active'),
            array('id'=>'Tips_button','name' => 'Tips', 'iconName' => 'fa fa-lightbulb-o','title' => 'Look at the tips', 'link' => 'tips', 'className' => 'inactive'),
            array('id'=>'Questionnaire_button','name' => 'Questionnaire','iconName' => 'fa fa-list-alt', 'title' => 'Fill in the questionnaire', 'link' => 'questionnaire', 'className' => 'inactive'),  
        );
    }
     
    function set_active($menutitle) {
        foreach ($this->menu_items as &$item) { // reference to item!!
            if (strcasecmp($menutitle, $item['name']) == 0) {
                $item['className'] = 'active';
            } else {
                $item['className'] = 'inactive';
            }
        }
    }
     
    function get_menuitems($menutitle='Home') {
        $this->set_active($menutitle);
        return $this->menu_items;
    }
     
}
