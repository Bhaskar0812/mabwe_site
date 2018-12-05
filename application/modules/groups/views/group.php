
<div class="wraper light-bg">
  <div class="mt-78 pb-20 newsfeed-section">
    <div class="container">
	    <div class="row pt-40 mb-3">
	    	<div class="col-lg-8 col-md-8 col-sm-8 col-12">
	    		<div class="clear-boxs">
		    		<div class="search-box mb-10">
		   				<div class="input-group">
						  <input type="text" id="search" name="search" class="form-control main-control" placeholder="Search">
							<div class="input-group-append">	 
							<a href="javascript:void(0);" class="btn search-btn" onclick="groupList(0,1);"><i class="fa fa-search"></i></a>
						  </div>					  
						</div>
				    </div>
				    <div class="ml-10">
						<form class="filter-form">
							<button id="clearButton" type="reset" onclick = "clearFilter();" class="btn clear-btn" style="border-radius: 20px;">Clear Search</button>
						</form>	
				    </div>
			    </div>					
	    	</div>
	    	<div class="col-lg-4 col-md-4 col-sm-4 col-12 text-right">
	    		<div class="add-post">
	          		<a href="javascript:void(0);" class="pst-btn" data-toggle="modal" data-target="#exampleModal" ><i class="fa fa-plus"></i> Add Group</a>
	          	</div>
	    	</div>
	    </div>

	    <div class="row" id="showGroupList">
	    	
	       
	    </div>
	   <div id='showLoaderGroup'  class="show_loader clearfix" data-offset="0" data-isNext="1">
              <img src='<?php echo base_url().MWIMAGES;?>loadMore.apng' alt=''>
        </div>  
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="addGroup" id="groupForm">        	
	        <div class="form-group">
				<div class="group-img">
					<div id="imagePreview">
	  				</div>
					<label for="grp-img" id="imgShow">
		                <input class="form-control main-control" id="grp-img" type="file" name="groupImage" accept="image/jpeg">
		                <div class="grp-icon">                    
		                  <i class="fa fa-file-image-o"></i>
		                </div>
		            </label>
				</div>	
				<span class="text-center dimension" style="color: gray;">Image dimension should be atleast 700*466px</span>			
	        </div>
	        <div class="form-group">
	          <select id="post" name="category" class="form-control main-control custom-select">
				<option value="">Select Post Category</option>
				<?php $category = get_category(); foreach($category as $key => $val){?>
				<option value="<?php echo $key;?>"><?php echo $val;?></option>
      		<?php } ?>
         </select>
	        </div>
	        <div class="form-group">
	        	<input type="text" class="form-control main-control" placeholder="Title" name="title">
	        </div>
	        <div class="form-group tag multiple-tag multiple search selection">
                <select class="form-control main-control custom-select ui fluid search dropdownInt " name="tags" id="tags" multiple/ >
                  <option value="">Add Tag</option>
                  <?php if(!empty($tags)){

                  	foreach($tags['result'] as $value){
                  	?>
                  <option><?php  echo '#'.$value->tagName ; ?></option>
                  <?php } }?>
                </select ><span style="color: gray; font-size:12px;">Type and press enter to add your own tag.</span>
            </div>
            <?php $csrf = get_csrf_token();
			?>
	  			<input id="csrf" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">

	        <div>
        		<button id="groupFormSubmit" type="button" class="btn btn-block thm-btn">Create Group</button>
        	</div>
	    </form>
      </div>
    </div>
  </div>
</div>
