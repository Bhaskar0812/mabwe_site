<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CommonBack {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('category_model');
        $this->check_user_session();
    }

    public function editFn(){
        $data['parent'] = "Category";
        $data['title']  = "Category";
        $table = ADMIN;
        $catTable = CATEGORIES;
        $where = array('id'=> $_SESSION['biz_user_sess']['id']);
        $id=decoding($this->input->post('id'));
        $array = array('categoryId'=>$id);
        $data['admin'] = $this->common_model->select_row($where,$table);
        $data['categories'] = $this->common_model->select_row($array,$catTable);
        //pr($data['categories']);
        $this->load->view('editCategory',$data,'');
    }

    public function editCategorySub()
    {
        $res = array();
        $this->form_validation->set_rules('categoryName','Category Name','required');
        if ($this->form_validation->run()==FALSE) {
            $res['status'] = 0;
            $res['msg'] = validation_errors($res);
            echo json_encode($resp);
            exit;
        }
        $response = array();
        $photo = '';
        $pic = $_FILES['image']['name'];
        if(!empty($pic)){ 
            $this->load->model('image_model');   
            $upload                      =   $_FILES['image']['name'];
            $imageName                   =   'image';
            $folder                      =   "profile";
            $response                    =   $this->image_model->updateMedia($imageName,$folder);
            //IMAGE UPLOAD.
        }
        if(!empty($response) && is_array($response)){
                    $image = 0;
                    $res['messages']['imageerror'] = $response['error'];
                    echo json_encode($res);
                    exit;
        }
        $id=$this->input->post('id');
        $dataUpdate = array();
        $dataUpdate['categoryName']        =   $this->input->post('categoryName');
        if(!empty($image)){
            $dataUpdate['profileImage'] =   $image;
        }
        $update  =   $this->common_model->updateAdmin($where,$dataUpdate,$table);
        //print_r($updateMyPost);die;
        if ($updateMyPost!=true) {
            $resp['status'] = 0;
            $resp['msg'] = "Data Not Updated";
            echo json_encode($resp);
            exit;
        }
            $resp['status'] = 1;
            $resp['msg'] = "Data Updated Successfully";
            echo json_encode($resp);
            exit;
    }//END OF FUNCTION



    public function categoryList(){
        
        $data['parent'] = "Category";
        $data['title'] = "Category";
        $table = ADMIN;
        $group_id = decoding($this->uri->segment(4));
        //echo $group_id;die;
        $where = array('id'=> $_SESSION['biz_user_sess']['id']);
        $data['counts'] = $this->category_model->count_all('categories');
        //$data['count'] = $this->group_model->select_result('groups');
        //pr($data['counts']);
        $data['admin'] = $this->common_model->select_row($where,$table);
        $this->load->admin_render('categoryList', $data, '');
    }

    public function getCategoryList() { //get user list

        $list = $this->category_model->get_list(array('user_id'=> $_SESSION['biz_user_sess']['id']));
       // pr($list);
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            if(empty($get->categoryImage)){
                $imgPath = base_url().DEFAULT_IMAGE;
            }else{
                $imgPath = base_url().CATEGORY_IMAGE."/".$get->categoryImage;
            }
            
            $row[] = $no;
            $row[] = '<a><img src="'.$imgPath.'" class="ListImage"></a>';
          
            $row[] = display_placeholder_text($get->categoryName); 
            $encoded = encoding($get->categoryId);
            if($get->status){
                 
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".CATEGORIES."','categoryId','".$encoded."','$get->status','category','Category')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".CATEGORIES."','categoryId','".$encoded."','$get->status','category','Category')" ;
                 $class = 'fa fa-check';
            }
            $encoded = encoding($get->categoryId);
           $viewUrl = base_url().'admin/categories/editFn/'.encoding($get->categoryId);
           //$viewUrl = base_url().onclick='openModal(".$get->categoryId.")';
           $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';
           $clkEdit = "editFn('admin','categories','editFn','".$encoded."')" ;
           $action .= '<a href="javascript:void(0)" title="View" onclick="'.$clkEdit.'" class="on-default edit-row table_action" >'.'<i class="fa fa-edit" aria-hidden="true"></i>'.'</a>';
           //'<i class="fa fa-edit" onclick='openModal(".$get->categoryId.")' aria-hidden="true"></i>';
          

           $row[] = $action;
             $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->category_model->count_all(),
                "recordsFiltered" => $this->category_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function

    

}//end of class