<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CommonBack {

    public $data = "";

    function __construct() {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('category_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->check_admin_user_session();
    }

    public function editFn(){
        $data['parent'] = "Categories";
        $data['title']  = "Categories";
        $table = ADMIN;
        $catTable = CATEGORIES;
        $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
        $id=decoding($this->input->post('id'));
        $array = array('categoryId'=>$id);
        $data['admin'] = $this->common_model->select_row($where,$table);
        $data['categories'] = $this->common_model->select_row($array,$catTable);
        //pr($data['categories']);
        $this->load->view('editCategory',$data,'');
    }

    public function editCategorySub()
    {
        $resp = array();
        $this->form_validation->set_rules('categoryName','Category Name','required');
        if ($this->form_validation->run()==FALSE) {
            $resp['status'] = 0;
            $resp['msg'] = validation_errors();
            //pr($res);
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
            $folder                      =   "categories";
            $response                    =   $this->image_model->updateMedia($imageName,$folder);
            //IMAGE UPLOAD.
        }
        if(!empty($response) && is_array($response)){
                    $image = 0;
                    $resp['status'] = 0;
                    $resp['msg'] = $response['error'];
                    echo json_encode($resp);
                    exit;
        }
        $id=$this->input->post('categoryId');
        //echo $id;die;
        $where = array('categoryId'=>$id);
        $table = CATEGORIES;
        $dataUpdate = array();
        $image                      =   isset($response) ? ($response):"";
        $dataUpdate['categoryName']        =   $this->input->post('categoryName');
        if(!empty($image)){
            $dataUpdate['categoryImage'] =   $image;
        }
        //$dataUpdate['categoryImage'] = !empty($response) ? $response : "";
        $update  =   $this->category_model->updateCategory($where,$dataUpdate,$table);
        //print_r($updateMyPost);die;
        if ($update!=true) {
            $resp['status'] = 0;
            $resp['msg'] = "Category Not Updated";
            echo json_encode($resp);
            exit;
        }
            $resp['status'] = 1;
            $resp['msg'] = "Category Updated Successfully";
            echo json_encode($resp);
            exit;
    }//END OF FUNCTION



    public function categoryList(){
        
        $data['parent'] = "Categories";
        $data['title'] = "Categories";
        $table = ADMIN;
        $group_id = decoding($this->uri->segment(4));
        //echo $group_id;die;
        $where = array('id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']);
        $data['counts'] = $this->category_model->count_all('categories');
        //$data['count'] = $this->group_model->select_result('groups');
        //pr($data['counts']);
        $data['admin'] = $this->common_model->select_row($where,$table);
        $this->load->admin_render('categoryList', $data, '');
    }

    public function getCategoryList() { //get user list

        $list = $this->category_model->get_list(array('user_id'=> $_SESSION[ADMIN_USER_SESS_KEY]['id']));
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