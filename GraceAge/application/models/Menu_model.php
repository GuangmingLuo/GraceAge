<?php
 
class Menu_model extends CI_Model {
 
    private $menu_items;
     
    function __construct() {
        parent::__construct();
        $this->menu_items = array(
            array('name' => 'Home', 'title' => 'Go Home', 'link' => 'home', 'className' => 'active'),
            array('name' => 'Tips', 'title' => 'Look at the tips', 'link' => 'tips', 'className' => 'inactive'),
            array('name' => 'Questionnaire', 'title' => 'Fill in the questionnaire', 'link' => 'question', 'className' => 'inactive'),  
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

?>