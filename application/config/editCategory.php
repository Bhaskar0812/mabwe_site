
<div class="modal fade" id="commonModal" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      <h4 class="modal-title">Update Category</h4>
    </div>
    <div id="qwerty" class="modal-body">
      
      <form id="myModl" >
        <span style="color:green;" id="succs"></span>
        <span style="color:red;" id="errr"></span>
        <div class="form-group">
          <label for="pwd">Category Name</label>
          <input type="hidden" name="categoryId" value="<?php echo $categories->categoryId; ?>">
          <input type="text" class="form-control" placeholder="" name="categoryName" value="<?php echo $categories->categoryName; ?>">
           <label id="name_error" class="error" for="name" style="color:red;"><?php //echo form_error('name'); ?></label>
        </div>
        <div class="form-group">
          <label for="pwd">Category Image</label>
          <input type="file" class="form-control" placeholder="" name="image" value="<?php echo $categories->categoryImage; ?>">
          
        </div>
        <form id="form1" runat="server">
        <input type='file' onchange="readURL(this);" />
        <img id="blah" src="<?php echo base_url().CATEGORY_IMAGE."/".$categories->categoryImage; ?>" alt="your image" / width="100" height="100" style="border-radius: 50%;">
    </form>
        <!-- <div class="checkbox">
          <label><input type="checkbox" name="remember"> Remember me</label>
        </div> -->
        <button type="submit" class="btn btn-default">Submit</button>
      </form>

    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
  </div>
  </div>
</div>



<script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
</script>


