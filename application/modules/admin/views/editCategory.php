
<div class="modal fade" id="commonModal" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Update Category</h4>
    </div>
    <div id="qwerty" class="modal-body">
      
      <form id="myModl" enctype="multipart/form-data">
        <span style="color:green;" id="succs"></span>
        <span style="color:red;" id="errr"></span>
        <div class="form-group">
          <label for="pwd">Category Name</label>
          <input type="hidden" name="categoryId" value="<?php echo $categories->categoryId; ?>">
          <input type="text" class="form-control" placeholder="" name="categoryName" value="<?php echo $categories->categoryName; ?>">
           <label id="name_error" class="error" for="name" style="color:red;"><?php //echo form_error('name'); ?></label>
        </div>
          <div class="col-md-12 pad-md-lft" >
                    <?php if(!empty($categories->categoryImage)){ ?>
                        <span onclick="$('#faclose').hide();$('#faCamera').show();$('#user_image').val(null);" id="faclose" class="fa fa-close pos-abso2 remove_img1" data-avtar="<?php echo base_url().DEFAULT_IMAGE;?>"></span>  
                      <?php }?>
          <div class="form-group">
              <div class="lbl-div float-left">
                <label>Category Image</label>
              </div>
              <div class="sec-image pad-md-lft">
                  <div class="profile_content edit_img">
                         
                  <div class="file_btn file_btn_logo">
                    <input type="file" style="max-width: 100px;"  class="input_img2" id="user_image" name="image" onchange="$('#faclose').show();$('#faCamera').hide(); readURL(this);">
                    <span class="glyphicon input_img2 logo_btn" style="display: block;">
                      <div id="show_company_img"></div>
                      <span class="ceo_logo">
                          <?php if(!empty($categories->categoryImage)){ ?>
                              <img id="blah" class="img-cls-rel" src="<?php echo base_url().CATEGORY_IMAGE."/".$categories->categoryImage; ?>" alt="your image" / width="100" height="100"><i class="fa fa-camera pos-abso"  id="faCamera" style="display: none;"></i>
                          <?php }else{ ?>
                              <img src="<?php echo base_url().DEFAULT_IMAGE;?>">
                         <?php }?>
                      </span>
                      
                    </span>
                    <!-- <img class="show_company_img2" style="display:none" alt="img" src="<?php //echo base_url() ?>/backend_assets/images/logo.png"> -->
                    
                  </div>
                </div>
                <div class="ceo_file_error file_error text-danger"></div>
              </div>
          </div>
          </div>
                                
        <input type="hidden" name="check_delete" id="check_delete" value="0" />

        <div class="modal-footer">          
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-default">UPDATE</button> 
        </div>
      </form>

    </div>

    

  </div>
  </div>
</div>
<script>
function show_loader(){
 $('#tl_admin_loader').show();
 }

 function hide_loader(){
 $('#tl_admin_loader').hide();
 }


  $(document).ready(function() {
    $("#myModl").validate({
        rules: {
          categoryName: {
                required: true,
            },
        } 
    });

  });

   
  $('#myModl').submit(function(e){
    e.preventDefault();
    if ($('#myModl').valid()==false) {
      toastr.error('Fillup required condtion');
      return false;
    }
    var formData = new FormData(this);
    $.ajax({
      type:"post",
      url:"<?php echo base_url(); ?>admin/categories/editCategorySub",
      data:formData,
      dataType:'json',
      cache:false,
      processData:false,
      contentType:false,
      beforeSend: function () { 
       show_loader(); 
       },
      success:function(resp){
        hide_loader(); 
        if (resp.status==1) {
          var delay = 2000;
          toastr.success(resp.msg);
          setTimeout(function(){
            window.location = '<?php echo base_url(); ?>admin/categories/categoryList'
          },delay);

        }
         if (resp.status==0) {
          toastr.error(resp.msg);
        }
      }
    });
  });
</script>


