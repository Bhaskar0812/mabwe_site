<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CommonBack {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
        
    }

    public function index(){//INDEX FUNCTION.
    	 if(!empty($this->session->userdata('id')))
            redirect('admin/dashboard');
           $this->load->view('login');   
    }//END OF FUNCTION

    public function registerView(){//Registration Form view FUNCTION.
    	 if(!empty($this->session->userdata('id')))
            redirect('admin/dashboard');
           $this->load->view('register');   
    }//END OF FUNCTION 



    public function login(){  //LOGIN FUNCION..
        $res =array();
        $this->form_validation->set_rules('userName', 'Username/Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE){
            foreach($_POST as $key =>$value){
                $res['messages'][$key] = form_error($key);
            }//foreach end..
        }
        else{ 
            $password        =        $this->input->post('password');
            $userName        =        $this->input->post('userName');
            $email = $userName;
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $where = array('userName'=>$email);
            }else{
                 $where = array('email'=>$email);
            }
            $isLogin         =        $this->common_model->isLogin($where,$password, ADMIN);
            if($isLogin == TRUE){
               $res['messages']['success']       =          ResponseMessages::getStatusCodeMessage(106);
            }else{
                $res['messages']['unsuccess']    =          ResponseMessages::getStatusCodeMessage(105);
            }
    }
    echo !empty($res) ?json_encode($res): redirect('admin'); //USED JSON ENCODE TO SHOW ERROR THROUGH AJAX.
    }//END OF FUNCTION

    public function dashboard(){//Dashboard View Function FUNCTION.
         if(empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Dashboard";
            $data['title'] = "Dashboard";
            $table = ADMIN;
            $where = array('id'=> $this->session->userdata('id'));
            $tableData = USERS;
            $data['admin'] = $this->common_model->select_row($where,$table);
            $data['companies'] = $this->common_model->select_count(BUSINESS);
            $data['count'] = $this->common_model->select_count($tableData);
            $data['inventories'] = $this->common_model->select_count(INVENTORIES);
            $data['clients'] = $this->common_model->select_count(CLIENTS);
           $this->load->admin_render('dashboard',$data,'');   
    }//END OF FUNCTION

    public function logout() {//logout fun.
        $this->session->sess_destroy();
        $this->session->set_flashdata('login_err', 'Sign out successfully done! ');
        redirect('admin','refresh');
           
    }//END OF FUNCTION 

    function customGet($select,$where,$table){
        //select single data..with given criteria..
        $this->db->select($select);
        $this->db->where($where);
        $query = $this->db->get($table);
        //echo $this->db->last_query();die;
        if($query->num_rows() == 1){
        $user = $query->row();
        return $user;
        }return FALSE;
    }//END OF FUNCTION..

    public function userList() {//userList
        if(empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Userlist";
            $data['title'] = "Userlist";
            $table = ADMIN;
            $where = array('id'=> $this->session->userdata('id'));
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('userList',$data,'');
           
    }//END OF FUNCTION 

    public function businessList() {//BusinessList.
        if(empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Business";
            $data['title'] = "Business";
            $table = ADMIN;
            $where = array('id'=> $this->session->userdata('id'));
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('businessList',$data,'');
           
    }//END OF FUNCTION  

    public function getInoices(){
         if(empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Invoice";
            $data['title'] = "Invoice";
            $table = ADMIN;
            $where = array('id'=> $this->session->userdata('id'));
            $data['admin'] = $this->common_model->select_row($where,$table);
        $this->load->admin_render('invoicesList', $data, '');
    }

    public function inventoryList() {//BusinessList.
        if(empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Inventory";
            $data['title'] = "Inventory";
            $table = ADMIN;
            $where = array('id'=> $this->session->userdata('id'));
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('inventoryList',$data,'');
           
    }//END OF FUNCTION 

    public function clientList() {//BusinessList.
        if(empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Clients";
            $data['title'] = "Clients";
            $table = ADMIN;
            $where = array('id'=> $this->session->userdata('id'));
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('clientList',$data,'');
           
    }//END OF FUNCTION 

    public function getSaleman() {//BusinessList.
        if(empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Salesman";
            $data['title'] = "Salesman";
            $table = ADMIN;
            $where = array('id'=> $this->session->userdata('id'));
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('salesmanList',$data,'');
           
    }//END OF FUNCTION 

    public function profileView() {//profile view function.
         if(empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Profile";
            $data['title'] = "Profile";
            $table = ADMIN;
            $where = array('id'=> $this->session->userdata('id'));
            $data['admin'] = $this->common_model->select_row($where,$table);
            $this->load->admin_render('profile',$data,'');
           
    }//END OF FUNCTION 

    public function editSubmit() { //EDIT PROFILE SUBMIT UPDATE
        if(empty($this->session->userdata('id')))
            redirect('admin');
        $res = array();
        $whereAdmin = array('id'=>$this->session->userdata('id'));
        $selectEmail = $this->common_model->select_row($whereAdmin,ADMIN);
        $emails = strtolower($this->input->post('email'));
        if(strtolower($selectEmail->email) != $emails){
            $this->form_validation->set_rules('email', 'Email', 'trim|required|is_valid|is_unique[admin.email]');
        }
        $this->form_validation->set_rules('fullName', 'Full Name', 'trim|required|callback__alpha_spaces_check');
        if ($this->form_validation->run() == FALSE){
            foreach($_POST as $key =>$value){
                $res['messages'][$key] = form_error($key);
            }//foreach end..
        }else{
        $response = array();
        $photo = '';
        if(!empty($_FILES['photo']['name'])){ 
            $this->load->model('image_model');   
            $upload                      =   $_FILES['photo']['name'];
            $imageName                   =   'photo';
            $folder                      =   "profile";
            $response['image']           =   $this->image_model->updateMedia($imageName,$folder);
            //IMAGE UPLOAD.
        }
        if(!isset($response['image']['error'])){
            $dataUpdate = array();
            $image                      =   isset($response['image'])?($response['image']):"";
            $table                      =   ADMIN;
            $where                      =   array('id'=>$this->session->userdata('id'));
            $dataUpdate['fullName']     =   $this->input->post('fullName');
            $dataUpdate['email']        =   $this->input->post('email');
            $dataUpdate['upd']          =   date("Y-m-d H:m:s");
            if(!empty($image)){
                $dataUpdate['profileImage'] =   $image;
            }
            $update                     =   $this->common_model->update($where,$dataUpdate,$table); //UPDATE DATABASE.
            if($update == TRUE){
                $res['messages']['success'] = ResponseMessages::getStatusCodeMessage(108);
            }else{  
                $res['messages']['unsuccess'] = ResponseMessages::getStatusCodeMessage(144);
            } 
        }else{
             $res['messages']['imageerror'] = $response['image']['error'];
        }
        }
        echo json_encode($res);
    }//END OF FUNCTION

    function changePassword(){ //Chnage Password SUBMIT FUNCTION.. 
        if(empty($this->session->userdata('id')))
            redirect('admin');
        $res = array();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]',array('required'=>'Please enter current password','min_length'=>'Password Should be atleast 6 Character Long'));
        $this->form_validation->set_rules('npassword', 'new password', 'trim|required|matches[rnpassword]|min_length[6]',array('required'=>'Please enter new password','min_length'=>'Password Should be atleast 6 Character Long','matches'=>'New Password does not match with retype password'));
        $this->form_validation->set_rules('rnpassword', 'retype new password ', 'trim|required|min_length[6]',array('required'=>'Please retype new password','min_length'=>'Password Should be atleast 6 Character Long'));
        $this->form_validation->set_error_delimiters('<div class="err_msg">', '</div>');
        if ($this->form_validation->run() == FALSE)
        { 
            foreach($_POST as $key =>$value){
            $res['messages'][$key] = form_error($key);
            }
        }else 
        {
            $password =$this->input->post('password');
            $npassword =$this->input->post('npassword');
            $table  = ADMIN;
            $select = "password";
            $where = array('id' => $this->session->userdata('id')); 
            $admin = $this->common_model->customGet($select,$where,$table); // password with select from here to check old passwod
            $passwordc = $admin->password;
            $passwordVerfied = password_verify($password, $passwordc); //verified password here. 
            if($passwordVerfied){
                $newPassword = password_hash($this->input->post('npassword') , PASSWORD_DEFAULT);//password hash encrypt.
                $data =array('password'=> $newPassword); 
                $update = $this->common_model->update($where, $data, $table);
                if($update){
                    $res = array();
                    if($update){
                        $res['messages']['success']= ResponseMessages::getStatusCodeMessage(140); //UPDATE SUCCESSFULLY
                    }
                    else{
                    $res['messages']['failed']='Failed! Please try again'; //ERROR NOT UPDATE
                    }
                } 
            }else{
                $res['messages']['unsuccess']= ResponseMessages::getStatusCodeMessage(141); //ERROR FOR OLD PASSWORD WRONG
            }
        }
    echo json_encode($res); //USED JSON ENCODE TO SHOW ERROR THROUGH AJAX. 
    }//END OF FUNCTION

    public function forgetPassword() { //Forget Password Function.. Send Link by using this function.
        if(!empty($this->session->userdata('id')))
            redirect(site_url().'admin/dashboard');
            $res = array();
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            if ($this->form_validation->run() == FALSE){
                $res['messages']['email'] = form_error('email');
            } //END OF FORM VALIDATION IF
        else{
            $dataSet['email'] = $this->input->post('email');
            $table = ADMIN;
            $response = $this->common_model->forgetPassword($table,$dataSet);
            if($response == TRUE){
                $res['messages']['success']   =   ResponseMessages::getStatusCodeMessage(120);
           }else{
                $res['messages']['unsuccess'] = ResponseMessages::getStatusCodeMessage(143);
           }
        }
    echo json_encode($res);   
    }//END OF FUNCTION 

    public function forgetPassView() {//profile view function.
         if(!empty($this->session->userdata('id')))
            redirect('admin');
            $data['parent'] = "Forget";
            $data['title'] = "Forget";
            $this->load->view('forgetPass',$data,'');
           
    }//END OF FUNCTION 
    
    public function setPassword($data="") { //SET PASSWORD VIEW FUNCTION.
        if(!empty($this->session->userdata('id')))
            redirect('admin/dashboard');
            $table= ADMIN;
            $where = array('forgetPass'=> $data);
            $dataUser['admin'] = $this->common_model->select_row($where,$table);
            $this->load->view('setPassword',$dataUser);
           
    }//END OF FUNCTION 

    public function deleteImg() { //DELETE IMAGE FUNCTION
        $this->load->model('image_model');
        if(empty($this->session->userdata('id')))
            redirect(site_url().'admin');
            $image = $this->input->post('image');
            $id = $this->input->post('id');
            $path = "uploads/profile/";
            $response = $this->image_model->unlinkFile($path,$image); 
        if($response == TRUE){
            $where = array('id'=> $id);
            $data = array('profileImage'=>'');
            $table = ADMIN;
            $this->common_model->update($where,$data,$table);
            $error['success']="Profile Image Deleted";
        }
        echo json_encode($error);   //USED JSON ENCODE TO SHOW ERROR THROUGH AJAX.
    }//END OF FUNCTION

    public function setPassReset() {
        $res = array();
        if(!empty($this->session->userdata('id')))
            redirect(site_url().'admin/dashboard');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
        if ($this->form_validation->run() == FALSE){
            foreach($_POST as $key =>$value){
                $res['messages'][$key] = form_error($key);
            }//foreach end..
        } //END OF FORM VALIDATION IF
        else{
            $dataSet['password'] = password_hash($this->input->post('password'),PASSWORD_DEFAULT);
            $dataSet['forgetPass'] = '';
            $dataSet['upd'] = date("Y-m-d H:m:s");
            //$dataSet['emailLink'] = '';
            $table = ADMIN;
            $where = array('id' => $this->input->post('id'));
            $response = $this->common_model->update($where,$dataSet,$table);
            if($response == TRUE){
                $res['messages']['success'] = ResponseMessages::getStatusCodeMessage(145);
            }else{
                $res['messages']['unsuccess'] = ResponseMessages::getStatusCodeMessage(146);
            }
        }
        echo json_encode($res);     
    }//END OF FUNCTION

    function _alpha_spaces_check($string){
    if(alpha_spaces($string)){
      return true;
    }
    else{
      $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
      return FALSE;
    }
    }

    function activeInactive(){
        if(empty($this->session->userdata('id')))
            redirect(site_url().'admin');
        $data['id'] =  decoding($this->input->post('id'));

        $table = $this->input->post('table');  
        $column = $this->input->post('id_name');  

        $status = $this->Users_model->activeInactive($table,$data,$column);

        if($status == TRUE){
            $data['messages']['activated'] = 'Activated Successfully';
        }else{
            $data['messages']['inactivated'] ='Inactivated Successfully';
        }
        echo json_encode($data);
    }

    function deleteData(){
        if(empty($this->session->userdata('id')))
            redirect(site_url().'admin');
        $data['id'] =  decoding($this->input->post('id'));
        $table = $this->input->post('table');  
        $column = $this->input->post('id_name');  

        $status = $this->Users_model->deleteData($table,$data,$column);

        if($status == TRUE){
            $data['messages']['delete'] = 'Delete Successfully';
        }else{
            $data['messages']['notDelete'] ='Not Deleted';
        }
        echo json_encode($data);
    }
   
}//END OF CLASS
