<?php if(!empty($group_detail)){?>


<div class="wraper light-bg">
  <div class="mt-78 pb-20 group-detail-section">
    <div class="container">
	    <div class="row">
	    	<div class="col-lg-4 col-md-6 col-sm-12 col-12">
	    		<div class="group-detail sticky mt-40">
	    			<div class="create-sec">						
						<div class="detail-media-body">
	    					<div class="detail-media-lft">
							<h6>Create By</h6>
							</div>

							<?php if($group_detail->isAdmin == 1){?>
							<div class="detail-media-rht">
								<div class="dropdown">
							    	<a id="dropdownMenuButton2" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
							    	<div class="dropdown-menu media-dropdown dropdown-menu-right">
								<a class="dropdown-item" href="javascript:void(0);" data-toggle="modal" data-target="#updateGroup"><i class="fa fa-edit"></i>Edit</a>
									    <a class="dropdown-item" href="javascript:void(0);" onclick="groupDelete('<?php echo $group_detail->groupId;?>')"><i class="fa fa-trash"></i>Delete</a>
									</div>
							    </div>
							</div>
							<?php } ?>
						</div>
						<div class="create-txt">
							<div class="media">
							 <a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($group_detail->userId);?>"><img class="mr-2 rounded-circle" src="<?php 
							  if(!empty($group_detail->profileImage) AND file_exists(USER_IMG_PATH_THUB.$group_detail->profileImage)){
							  	echo base_url().USER_IMG_PATH_THUB.$group_detail->profileImage; 
							  }else{echo base_url().PLACEHOLDER;

							  }?>" width="50px"></a>
							  <div class="media-body align-self-center">
						  		<h5 class="mt-0"><a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($group_detail->userId);?>"><!-- <?php echo display_placeholder_text($post_details->fullname);?> --></a><?php echo $group_detail->fullname;?></h5>						    
							  </div>
							</div>
						</div>
					</div>
					<ul class="grp-tags">
						<?php if(!empty($group_detail->tags)){ foreach($group_detail->tags as $tagName){ ?>
						<li>
							<p><?php echo '#'.$tagName->tagName;?></p>
						</li>
						<?php } }?>
					</ul>
					 <?php if(!empty($group_detail->categoryName)){ 
				    	switch ($group_detail->categoryName){
						case "Career":$class = 'txt-ble';break;case "Marketplace":$class = 'txt-org';break;case "Mabwe411":$class = 'txt-ble';break; case "Connect":$class = 'txt-org';break; }
						}
	    			?>
	    			<div class="grs-txt mb-15 <?php echo $class;?>">
						<h2><?php echo $group_detail->categoryName; ?></h2>
					</div>
					<div class="grp-dtail text-center">
						<div class="likes-icon">	    						
							<h5>Members</h5>
							<a href="javascript:void(0);" onclick="getGroupUsers(0,<?php echo $group_detail->groupId;?>);" data-toggle="modal" data-target="#Members">
								<p><i class="fa fa-users"></i> <?php echo $group_detail->members_count;?></p>
							</a>
	    				</div>
	    			</div>									
	    		</div>
	    	</div>
	    	<div class="col-lg-8 col-md-6 col-sm-12 col-12">
	    		<div class="group-commnt mt-40">
	    			<div class="grs-txt">
	    				<h4><?php echo $group_detail->groupName;?></h4>
	    			</div>
	    			<div class="grp-img">
	    				<img src="<?php 
							  if(!empty($group_detail->post_image) AND file_exists(GROUP_IMAGE.$group_detail->post_image)){
							  	echo base_url().GROUP_IMAGE.'medium/'.$group_detail->post_image; 
							  }else{echo base_url().PLACEHOLDER;

							  }?>" alt="">
	    			</div>
					<div class="newsfeed-info-detail mt-15">
						<ul class="info-icon">
						<li>
							<i class="<?php if($group_detail->user_like_status == 1){echo 'fa fa-heart';}else{ echo 'fa fa-heart-o';}?>" id="likesIconVisible<?php echo $group_detail->groupId;?>" onclick="GrouplikeUnlike('<?php echo $group_detail->groupId;?>');" data-value="<?php if($group_detail->user_like_status == 1){echo 1;}else{ echo 0;}?>" data-url="likeUnlike"></i> 
				          	<a href="javascript:void(0);" data-toggle="modal" data-target="#Likes" onclick="getGroupLikes(0,'<?php echo $group_detail->groupId;?>');"><span id="likesCount<?php echo $group_detail->groupId;?>" data-value="<?php echo $group_detail->like_count;?>"><?php if($group_detail->like_count != 0){?><?php echo $group_detail->like_count;?></span> <span id="likeDisabled<?php echo $group_detail->groupId;?>">Likes</span><?php  }?></a>
				          </li>

				          <li class="text-right">
									<a href="javascript:void(0);"><i id="comment_count<?php echo $group_detail->groupId;?>"  class="fa fa-comment-o"></i>
									<span id="commentCountView<?php echo $group_detail->groupId;?>" data-value="<?php echo $commentCount;?>"> <?php echo $commentCount;?></span> Comments</a>
							</li>
							
						</ul>
					</div>
					<div id='showLoaderComment' class="show_loader clearfix" data-offset="0" data-isNext="1" data-id="<?php echo $group_detail->groupId;?>">
                    
                    	<img src='<?php echo base_url().MWIMAGES;?>loadMore.apng' alt=''>
            		</div>
            		<input type="hidden" id="rowCountComment" value="">

					<div class="cmnts-list" id="scrollDivGroupDetail">
	
						<ul class="cmmnt-sec perfectScrollbar1" id="showCommentGroupDetails">
						<li class="showdiv">
							<center><button class="pris-cmmt mt-10" onclick="getGroupComments(0);" id="showLoadMoreCommentGroup" style="cursor: pointer;">Load previous comment..</button></center>
						</li>
						</ul>							
					
					</div>
					<div class="our-commnt-box">
						<div class="media">
						  <img class="mr-2 rounded-circle" src="<?php if(!empty($userData->profileImage) AND file_exists(USER_IMG_PATH_THUB.$userData->profileImage)){echo base_url().USER_IMG_PATH_THUB.$userData->profileImage; }else{echo base_url().PLACEHOLDER;}?>" width="40px">
						  <div class="media-body">
						   <form action="addCommentFromGroupDeatil/" id="doComment">
					    		<div class="form-group">
									<textarea onkeyup="getCommentButton('<?php echo $group_detail->groupId;?>',this.value.length)" name="comment_text" id="comment_text<?php echo $group_detail->groupId;?>" class="form-control main-textarea" id="" placeholder="Write comment here..."  maxlength="200"></textarea><span class="remainingCount" id="showRemaining<?php echo $group_detail->groupId;?>"><span>
							  	</div>
							  	<button id="buttonComment<?php echo $group_detail->groupId;?>" class="btn thm-btn cmt-btn pull-right comment-button" onclick="submitCommentFromGroupDetails('<?php echo $group_detail->groupId;?>')" type="button">Comment</button>
							  	<?php $csrf = get_csrf_token();
						         ?>
		  						<input id="csrf<?php echo $group_detail->groupId;?>" type="hidden" name="<?php echo $csrf['name'];?>"  value="<?php echo $csrf['hash'];?>">
						    </form>
						  </div>
						</div>
					</div>
	    		</div>
	    	</div>
	    </div>
    </div>
  </div>
</div>

<div class="modal fade" id="updateGroup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="updateGroupDetail" id="groupFormUpdate">        	
	        <div class="form-group">
				<div class="group-img">
					<div id="imagePreview">
	  				</div>

	  				<label for="" id="lbremove111">
	  					<div class="grp-icon1" id="remove111">
	  						<img id="hwremove111" class="img-fluid show-group_image" src="<?php 
							  if(!empty($group_detail->post_image) AND file_exists(GROUP_IMAGE.$group_detail->post_image)){
							  	echo base_url().GROUP_IMAGE.'medium/'.$group_detail->post_image; 
							  }else{echo base_url().PLACEHOLDER;

							  }?>"><a onclick="removeImageGroup('remove111')" ><span><i class="fa fa-close cls_icon">
	  							
	  						</i>
	  					</span>
	  					</a>
	  					</div>
	  				</lable>

					<label for="grp-img1" id="imgShow" style="display: none;">
		                <input class="form-control main-control" id="grp-img1" type="file" name="groupImage" accept="image/jpeg" value="<?php  if(!empty($group_detail->post_image) AND file_exists(GROUP_IMAGE.$group_detail->post_image)){
							  	echo base_url().GROUP_IMAGE.'medium/'.$group_detail->post_image; 
							  }?>">
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
				<option value="<?php echo $key;?>" <?php if($group_detail->categoryName == $val){echo "selected";}?>><?php echo $val;?></option>
      		<?php } ?>
         </select>
	        </div>
	        <div class="form-group">
	        	<input type="text" class="form-control main-control" placeholder="Title" name="groupName" value="<?php echo $group_detail->groupName;?>">

	        	<input type="hidden" class="form-control main-control" placeholder="Title" name="group_id" value="<?php echo $group_detail->groupId;?>">
	        </div>
	         <div class="form-group tag multiple-tag multiple search selection">
	         	
              <?php if(!empty($group_detail->tags)){ foreach($group_detail->tags as $tagName){ ?>
						
            	 <input type="hidden" name="tagsName[]" value="<?php echo '#'.$tagName->tagName;?>">
              <?php } }?>
	         	
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
        		<button id="groupEditSubmit" type="button" class="btn btn-block thm-btn">Update Group</button>
        	</div>
	    </form>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
function getCommentButton(groupId,length) {
	var getRemain = 200 - length ;

	$("#showRemaining"+groupId).text(getRemain + " Characters remaining.");
    var textInputValue = $("#comment_text"+groupId).val();
    if(textInputValue != ''){
      $("#buttonComment"+groupId).show();
    }else{
       $("#buttonComment"+groupId).hide();
      
    }
};
</script>

<script type="text/javascript">
	function readMore(id){
		$("#more"+id).show();
		$("#readMoreButton"+id).hide();
		$("#dott"+id).remove();
	}

</script>	
<?php }?>