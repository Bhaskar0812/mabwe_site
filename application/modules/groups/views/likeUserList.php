
            
         
<?php if(!empty($userDetail)){
	foreach($userDetail as $key => $value){

	?>
<li>
	<div class="media">
		<img class="mr-2 rounded-circle" src="<?php if(!empty($value->profileImage) AND file_exists(USER_IMG_PATH_THUB.$value->profileImage)){echo base_url().USER_IMG_PATH_THUB.$value->profileImage; }else{echo base_url().PLACEHOLDER;}?>" width="50px">
			<div class="media-body">								  	
				<h5 class="mt-0">
					<a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($value->userId);?>"><?php echo $value->fullName;?></a>
				</h5>
				<p><?php echo $value->country;?></p>							    
			</div>
	</div>
</li>

<?php } }else{ ?><li>
	<div class="media text-center no-likes" id="showMsg" >
		No Likes on this post.
	</div>
</li>

<?php }?>



<script>
	var group_id = '<?php echo $group_id;?>';
	$("#scrollDiv").scroll(function() {
		var $this = $(this);
		var $results = $("#showUserLikedList");
		if ($this.scrollTop() + $this.height() == $results.height()) {
                getGroupLikes(1,group_id);
        }

			
	});
</script>