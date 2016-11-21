<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Caregiver_Menu_model
 *
 * @author orditech
 */
class Caregiver_Menu_model extends CI_Model{
    
    private $caregiver_menu_items;
    
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->lang->load('caregiver_menu', $this->session->Language);
        $this->caregiver_menu_items = array(
            array('name' => $this->lang->line('caregiver_menu_general'), 'title' => 'Algemene informatie', 'link' => 'index', 'className' => 'active', 'gridClass' => 'col-lg-2'),
            array('name' => $this->lang->line('caregiver_menu_personal'), 'title' => 'Persoonlijk', 'link' => 'personal', 'className' => 'inactive', 'gridClass' => 'col-lg-2'),
            array('name' => $this->lang->line('caregiver_menu_tips'), 'title' => 'Bekijk de tips', 'link' => 'tips', 'className' => 'inactive', 'gridClass' => 'col-lg-2'),
            //array('name' => '', 'title' => '', 'link' => '#', 'className' => 'inactive', 'gridClass' => 'col-lg-4'),
            //array('name' => 'Profiel', 'title' => 'Profiel', 'link' => 'profile', 'className' => 'inactive', 'gridClass' => 'col-lg-2')
        );
    }
    function set_active($menutitle) {
        foreach ($this->caregiver_menu_items as &$item) { // reference to item!!
            if (strcasecmp($menutitle, $item['name']) == 0) {
                $item['className'] = 'active';
            } else {
                $item['className'] = 'inactive';
            }
        }
    }
     
    function get_menuitems($menutitle='Algemeen') {
        $this->set_active($menutitle);
        return $this->caregiver_menu_items;
    }
}
