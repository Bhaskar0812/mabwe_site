
<?php if(!empty($groupData)){ ?>
<div class="col-lg-4 col-md-6 col-sm-12 col-12">
	<div class="group-card mt-10">		    		       	
      	<div class="card">
		  <a href="<?php echo base_url().'groups/groupDetail?group_id='.encoding($groupData['data'][0]->groupId);?>"><img class="card-img-top set-heigth" src="<?php 
					  if(!empty($groupData['data'][0]->groupImage) AND file_exists(GROUP_IMAGE_THUMB.$groupData['data'][0]->groupImage)){
					  	echo base_url().GROUP_IMAGE_THUMB.$groupData['data'][0]->groupImage; 
					  }else{echo base_url().PLACEHOLDER;

					  }?>"></a>
		 <?php if(!empty($groupData['data'][0]->categoryName)){ 
	    	switch ($groupData['data'][0]->categoryName){
			case "Career":$class = 'txt-ble';break;case "Marketplace":$class = 'txt-org';break;case "Mabwe411":$class = 'txt-ble';break; case "Connect":$class = 'txt-org';break; }
			}
	    	?>
		  <div class="card-body <?php echo $class;?>">
		    <a href=""><h5 class="card-title"><?php echo $groupData['data'][0]->categoryName;?></h5></a>
		    <h4 class="card-text"><?php echo $groupData['data'][0]->groupName;?></h4>
		  </div>
		  <div class="card-footer">
			<ul class="info-icon">
  				<li>

  					<i class="<?php if($groupData['data'][0]->user_like_status == 1){echo 'fa fa-heart';}else{ echo 'fa fa-heart-o';}?>" id="likesIconVisible<?php echo $groupData['data'][0]->groupId;?>" onclick="GrouplikeUnlike('<?php echo $groupData['data'][0]->groupId;?>');" data-value="0" data-url="likeUnlike"></i> 
		          	<a href="javascript:void(0);" data-toggle="modal" data-target="#Likes" onclick="getGroupLikes(0,'<?php echo $groupData['data'][0]->groupId;?>');"><span id="likesCount<?php echo $groupData['data'][0]->groupId;?>" data-value="<?php echo $groupData['data'][0]->like_count;?>"><?php if($groupData['data'][0]->like_count != 0){?><?php echo $groupData['data'][0]->like_count;?></span> <span id="likeDisabled<?php echo $groupData['data'][0]->groupId;?>">Likes</span><?php  }?></a>
  				</li>
  				<li class="text-right">
  					<a href="group-detail.html"><i class="fa fa-comment-o"></i> <?php echo $groupData['data'][0]->comment_count.' Comments';?></a>
  				</li>
  			</ul>
		  </div>
		</div>
	</div>
</div>
<?php } ?>