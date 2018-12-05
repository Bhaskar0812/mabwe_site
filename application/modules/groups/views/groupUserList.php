
            
         
<?php if(!empty($userDetail)){
	foreach($userDetail as $key => $value){
	?>

<li>
    <div class="media">
  	<img class="mr-2 rounded-circle" src="<?php if(!empty($value->profile_image) AND file_exists(USER_IMG_PATH_THUB.$value->profile_image)){echo base_url().USER_IMG_PATH_THUB.$value->profile_image; }else{echo base_url().PLACEHOLDER;}?>"" width="50px">
  		<div class="media-body">                    
    <h5 class="mt-0">
      <a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($value->user_id);?>"><?php echo $value->fullname;?> </a><?php if(!empty($value->isAdmin) AND $value->isAdmin == 1){?><span class="pull-right admin-txt">Group Admin</span><?php }?>
    </h5>
    <p><?php echo $value->country;?></p>                 
  </div>
</div>
  </li>


<?php } }else{ ?><li>
	<div class="media text-center no-likes" id="showMsg" >
		No Members in this group.
	</div>
</li>

<?php }?>



<script>
	var group_id = '<?php echo $group_id;?>';
	$("#scrollDivMembers").scroll(function() {
		var $this = $("#scrollDivMembers");
		var $results = $("#showUserGroupList");
		if ($this.scrollTop() + $this.height() > $results.height()) {
                getGroupUsers(1,group_id);
        }	
	});
</script>