
<?php 

if(!empty($isFilter)){//is filter comes when filter applied

	if(empty($record_count)){
		$record = "No group found";
	}else{
		$record = $record_count.' group found.';
	}
?><!-- toaster will show count of data when filter apply here. -->
<div class="text-center popUpcss">
<script>
$.toast({
  title: "<?php echo $record;?>",
   position: 'middle'
});
</script>
</div>
<?php if(empty($record_count)){ ?> <!-- message No record found comes from here if No data found in database -->
<div class="text-center no-record-group"><h>No group found matching your criteria. </h></div>


<?php } } ?>

<?php if(!empty($group_data)){ foreach($group_data['data'] as $value){ ?>
<div class="col-lg-4 col-md-6 col-sm-12 col-12">
	<div class="group-card mt-10">		    		       	
      	<div class="card">
		  <a href="<?php echo base_url().'groups/groupDetail?group_id='.encoding($value->groupId);?>"><img class="card-img-top set-heigth" src="<?php 
							  if(!empty($value->groupImage) AND file_exists(GROUP_IMAGE_THUMB.$value->groupImage)){
							  	echo base_url().GROUP_IMAGE_THUMB.$value->groupImage; 
							  }else{echo base_url().PLACEHOLDER;

							  }?>"></a>
		 <?php if(!empty($value->categoryName)){ 
	    	switch ($value->categoryName){
			case "Career":$class = 'txt-ble';break;case "Marketplace":$class = 'txt-org';break;case "Mabwe411":$class = 'txt-ble';break; case "Connect":$class = 'txt-org';break; }
			}
	    	?>
		  <div class="card-body <?php echo $class;?>">
		    <a href="<?php echo base_url().'groups/groupDetail?group_id='.encoding($value->groupId);?>"><h5 class="card-title"><?php echo $value->categoryName;?></h5>
		    <h4 class="card-text"><?php echo $value->groupName;?></h4></a>
		  </div>
		  <div class="card-footer">
			<ul class="info-icon">
  				<li>
  					
  					<i class="<?php if($value->user_like_status == 1){echo 'fa fa-heart';}else{ echo 'fa fa-heart-o';}?>" id="likesIconVisible<?php echo $value->groupId;?>" onclick="GrouplikeUnlike('<?php echo $value->groupId;?>');" data-value="<?php if($value->user_like_status == 1){echo 1;}else{ echo 0;}?>" data-url="likeUnlike"></i> 

		          	<a href="javascript:void(0);" data-toggle="modal" data-target="#Likes" onclick="getGroupLikes(0,'<?php echo $value->groupId;?>');"><span id="likesCount<?php echo $value->groupId;?>" data-value="<?php echo $value->like_count;?>"><?php if($value->like_count != 0){?><?php echo $value->like_count;?></span> <span id="likeDisabled<?php echo $value->groupId;?>">Likes</span><?php  }?></a>
  				</li>
  				<li class="text-right">
  					<a href="<?php echo base_url().'groups/groupDetail?group_id='.encoding($value->groupId);?>"><i class="fa fa-comment-o"></i> <?php echo $value->comment_count.' Comments';?>  </a>
  				</li>
  			</ul>
		  </div>
		</div>
	</div>
</div>
<?php }  }else{ if(empty($isFilter)){?> <div class="text-center no-group-found"><h>No group found </h><?php } } ?>