<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Newsfeed extends CommonFront {

    public $data = "";

    function __construct() {
    	$this->check_user_session();
        parent::__construct();
        
    }

    public function index(){
    	$where = array('userId'=>$_SESSION[USER_SESS_KEY]['userId']);
    	$data['userData'] = $this->common_model->getsingle(USERS,$where);
    	$data['title'] = 'NEWSFEED';
        $this->load->front_render('newsfeed',$data);
    } //end function


}//END OF CLASS
