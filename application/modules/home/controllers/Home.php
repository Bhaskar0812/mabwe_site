<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CommonFront {

    public $data = "";

    function __construct() {
       
        parent::__construct();
         $this->check_user_session();
        
    }
    public function index(){
        $data['title'] = 'HOME';
        $this->load->front_render('home_page',$data);

    } 

}//END OF CLASS
