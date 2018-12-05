<?php 

if(!empty($isFilter)){//is filter comes when filter applied

	if(empty($record_count)){
		$record = "No newsfeeds found";
	}else{
		$record = $record_count.' newsfeeds found.';
	}
?><!-- toaster will show count of data when filter apply here. -->
<script>
$.toast({
  title: "<?php echo $record;?>",
   position: 'middle'
});
</script>
<?php if(empty($record_count)){ ?> <!-- message No record found comes from here if No data found in database -->
<div class="text-center no-record"><h>No feeds found matching your criteria. </h>
<form class="filter-form">
<button type="reset" onclick = "clearFilter();" class="btn mt-10 clear-btn">Clear filters</button></form></div>

<?php } } ?>

<?php if(!empty($postDetail)){ ?>

				<?php foreach($postDetail as $value){

				if(!empty($value->video) AND !empty($value->video_thumb)){?>
				<div class="newsfeed-sec mt-20">
					<div class="newsfeed-head">
						<div class="media">
						<a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($value->userId);?>"><img class="mr-3 rounded-circle" src="<?php if(!empty($value->profileImage) AND file_exists(USER_IMG_PATH_THUB.$value->profileImage)){echo base_url().USER_IMG_PATH_THUB.$value->profileImage; }else{echo base_url().PLACEHOLDER;}?>" width="40px"></a>
						  <div class="media-body news-media-body">
						  	<div class="news-media-lft">
							    <a href="<?php echo base_url().'/user/userProfile/?user_id='. encoding($value->userId);?>"><h5 class="mt-0"><?php echo $value->fullname;?></h5></a>
							    <p><?php echo time_elapsed_string($value->created_post);?></p>
						    </div>
						    <?php if(!empty($value->categoryName)){ 
						    switch ($value->categoryName){
    						case "Career":$class = 'txt-ble';break;case "Marketplace":$class = 'txt-org';break;case "Mabwe411":$class = 'txt-ble';break; case "Connect":$class = 'txt-org';break; }
						    	?>
						   <div class="news-media-rht <?php echo $class;?>">
						   	<a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>"><h6><?php echo $value->categoryName;?></h6></a>
						   	<?php } ?>
						   </div>
						  </div>
						</div>
					</div>
					<div class="newsfeed-body mt-20">						
						<a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>"><h4><?php echo !empty($value->title)?$value->title:'';?></h4>	
						<div class="location">
							<p><i class="fa fa-map-marker"></i> <?php if(!empty($value->address)){ echo $value->address;}?></p>
						</div>
						<ul class="tags-detail">
						<?php if(!empty($value->tags_name)){
							foreach($value->tags_name as $tag){?>
							<li>
								<p>#<?php echo $tag->tagName;?></p>
							</li>
						<?php } } ?>
						</ul>											
						<div><a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>">
							<video style="width: 100%;max-width:500px;height:281px; background-color: #000; border: 1px solid #efee;" poster="<?php echo $value->video_thumb;?>" controls controlsList="nodownload">
							  <source src="<?php echo $value->video;?>" type="video/mp4">
							</video></a>
						</div>
						<?php $word_count = str_word_count($value->description);?>
						<p id="readMore<?php echo $value->postId;?>" class="mt-10" style="overflow: hidden;"><a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>"> <?php echo substr($value->description, 0, 100);?><?php if($word_count > 100){ ?><span id="dott<?php echo $value->postId;?>">...</span> <?php }?><span id="more<?php echo $value->postId;?>" style="display:none;"><?php echo substr($value->description, 101, 5000);?></span><?php if($word_count > 100){?><a id="readMoreButton<?php echo $value->postId;?>"onclick="readMore(<?php echo $value->postId;?>);" class="read-txt" style="color:#ff5e3a; cursor: pointer;">Read More</a><?php }?></a></p>
						</a>
					</div>
					<div class="newsfeed-footer">
						<div class="newsfeed-info">
							<ul class="info-icon">
								<li>
		          					<i class="<?php if($value->user_like_status == 1){echo 'fa fa-heart';}else{ echo 'fa fa-heart-o';}?>" id="likesIconVisible<?php echo $value->postId;?>" onclick="likeUnlike('<?php echo $value->postId;?>');" data-value="<?php if($value->user_like_status == 1){echo 1;}else{ echo 0;}?>" data-url="newsfeed/likeUnlike"></i> 
		          					<a href="javascript:void(0);" data-toggle="modal" data-target="#Likes" onclick="getPostLikes(0,'<?php echo $value->postId;?>');"><span id="likesCount<?php echo $value->postId;?>" data-value="<?php echo $value->like_count;?>"><?php if($value->like_count !== 0){?><?php echo $value->like_count;?></span> <span id="likeDisabled<?php echo $value->postId;?>">Likes</span><?php  }?></a>
		          				</li>
								<li class="text-right">
									<a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>"><i id="comment_count<?php echo $value->postId;?>"  class="fa fa-comment-o"></i>
									<span id="commentCountView<?php echo $value->postId;?>" data-value="<?php echo $value->comment_count;?>"> <?php echo $value->comment_count;?></span> Comments</a>
								</li>
							</ul>
						</div>

						<div class="modal fade" id="Likes<?php echo $value->postId;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							  
							</div>
						<div class="comment-sec">
							<ul class="comments-list">
								<?php if(!empty($value->comment)){ ?>
									
								<li id="comment_show<?php echo $value->postId;?>">
									<div class="media">
									  <a href="<?php echo base_url();?>"><img class="mr-2 rounded-circle" src="<?php if(!empty($value->comment[0]->profileImage) AND file_exists(USER_IMG_PATH_THUB.$value->comment[0]->profileImage)){echo base_url().USER_IMG_PATH_THUB.$value->comment[0]->profileImage; }else{echo base_url().PLACEHOLDER;}?>" width="40px"></a>
									  <div class="media-body comments-media-body">
									  	<div class="comments-media-lft">
										    <a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($value->comment[0]->comment_user_id);?>"><h5 class="mt-0"><?php echo $value->comment[0]->fullname;?></h5></a>
										    <p><?php echo time_elapsed_string($value->comment[0]->comment_crd);?></p>
									    </div>							    
									  </div>
									</div>
									<div class="comments-txt">
										<?php $word_count_comment = str_word_count($value->comment[0]->comment);?>
								    	<p><?php echo substr($value->comment[0]->comment,0,200);if($word_count_comment > 200){echo trim("<span style='color:grey;'>......<a href='#'>view more</a></span>");}?></p>
								    </div>
								</li>
								<?php  }else{ ?>
								<li style="color:white; border:0;" id="comment_show<?php echo $value->postId;?>"></li>
								<?php }?>
							</ul>
							<div class="comment-box ">
								<div class="media">
								 <img class="mr-2 rounded-circle" src="<?php if(!empty($_SESSION[USER_SESS_KEY]['profileImage']) AND file_exists(USER_IMG_PATH_THUB.$_SESSION[USER_SESS_KEY]['profileImage'])){echo base_url().USER_IMG_PATH_THUB.$_SESSION[USER_SESS_KEY]['profileImage']; }else{echo base_url().PLACEHOLDER;}?>" width="40px"><p class="mt-10" style="overflow: hidden;"><a href="javascript:void(0);"  class="tags-txt"></a> </p>

						
								   <div class="media-body">
								    <form action="newsfeed/addComment" id="doComment">
							    		<div class="form-group">
											<textarea onkeyup="getCommentButton('<?php echo $value->postId;?>',this.value.length)" name="comment_text" id="comment_text<?php echo $value->postId;?>" class="form-control main-textarea" id="" placeholder="comment"  maxlength="200"></textarea><span class="remainingCount" id="showRemaining<?php echo $value->postId;?>"><span>
									  	</div>
									  	<button id="buttonComment<?php echo $value->postId;?>" class="btn thm-btn cmt-btn pull-right comment-button" onclick="submitComment('<?php echo $value->postId;?>')" type="button">Comment</button>

									  	<?php $csrf = get_csrf_token();
								         ?>
				  						<input id="csrf<?php echo $value->postId;?>" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
								    </form>

								  </div>
								</div>
							</div>
						</div>
					</div>        		
				</div>
				<?php } if(empty($value->video) AND empty($value->video_thumb)){?>
				<div class="newsfeed-sec mt-20">
					<div class="newsfeed-head">
						<div class="media">
						  <a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($value->userId);?>"><img class="mr-3 rounded-circle" src="<?php if(!empty($value->profileImage) AND file_exists(USER_IMG_PATH_THUB.$value->profileImage)){echo base_url().USER_IMG_PATH_THUB.$value->profileImage; }else{echo base_url().PLACEHOLDER;}?>" width="40px"></a>
						  <div class="media-body news-media-body">
						  	<div class="news-media-lft">
							    <a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($value->userId);?>"><h5 class="mt-0"><?php echo $value->fullname;?></h5></a>
							    <p><?php echo time_elapsed_string($value->created_post);?></p>
						    </div>
						     <?php if(!empty($value->categoryName)){ 
						    switch ($value->categoryName){
    						case "Career":$class = 'txt-ble';break;case "Marketplace":$class = 'txt-org';break;case "Mabwe411":$class = 'txt-ble';break; case "Connect":$class = 'txt-org';break; }
						    	?>
						   <div class="news-media-rht <?php echo $class;?>">
						   	<a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>"><h6><?php echo $value->categoryName;?></h6></a>
						   	<?php } ?>
						    </div>
						  </div>
						</div>
					</div>
					<div class="newsfeed-body mt-20">						
						<a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>">
							<h4><?php echo !empty($value->title)?$value->title:'';?></h4>	
						<div class="location">
							<p><i class="fa fa-map-marker"></i> <?php if(!empty($value->address)){ echo $value->address;}?> </p>
						</div>
						<ul class="tags-detail">
							<?php if(!empty($value->tags_name)){
							foreach($value->tags_name as $tag){?>
							<li>
								<p>#<?php echo $tag->tagName;?></p>
							</li>
							<?php } } ?>
						</ul>
						</a>					
						<div id="NewsFeedsHome<?php echo $value->postId;?>" class="news-feed-slider owl-carousel sliderShowNewsFedd">
							<?php if(!empty($value->images)){

								foreach($value->images as $val){
							?>

				            <div class="testimonial"> 
				            	<a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>"><img src="<?php echo base_url().POST_IMAGE_PATH_THUMB.$val->attachmentName;?>" alt=""></a>
				            </div>
				            <?php } } ?>
				        </div>	
				        <script>
				        	$("#NewsFeedsHome<?php echo $value->postId;?>").owlCarousel({
							  items:1,
							  itemsDesktop:[1000,1],
							  itemsDesktopSmall:[979,1],
							  itemsTablet:[768,1],
							  itemsMobile:[650,1],
							  pagination:true,
							  navigation:false,
							  slideSpeed:1000,
							  autoPlay:true
							});
				        </script>
				       	
						<?php $word_count = str_word_count($value->description);?>
						<p id="readMore<?php echo $value->postId;?>" class="mt-10" style="overflow: hidden;"><a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>"> <?php echo substr($value->description, 0, 100);?><?php if($word_count > 100){ ?><span id="dott<?php echo $value->postId;?>">...</span> <?php }?><span id="more<?php echo $value->postId;?>" style="display:none;"><?php echo substr($value->description, 101, 5000);?></span><?php if($word_count > 100){?><a id="readMoreButton<?php echo $value->postId;?>"onclick="readMore(<?php echo $value->postId;?>);" class="read-txt" style="color:#ff5e3a; cursor: pointer;">Read More</a><?php }?></a></p>
						
					</div>
					
					<div class="newsfeed-footer">
						<div class="newsfeed-info">
							<ul class="info-icon">
								<li>
		          					<i class="<?php if($value->user_like_status == 1){echo 'fa fa-heart';}else{ echo 'fa fa-heart-o';}?>" id="likesIconVisible<?php echo $value->postId;?>" onclick="likeUnlike('<?php echo $value->postId;?>');" data-value="<?php if($value->user_like_status == 1){echo 1;}else{ echo 0;}?>" data-url="newsfeed/likeUnlike"></i> 
		          					<a href="javascript:void(0);" data-toggle="modal" data-target="#Likes" onclick="getPostLikes(0,'<?php echo $value->postId;?>');"><span id="likesCount<?php echo $value->postId;?>" data-value="<?php echo $value->like_count;?>"><?php if($value->like_count !== 0){?><?php echo $value->like_count;?></span> <span id="likeDisabled<?php echo $value->postId;?>">Likes</span><?php  }?></a>
		          					
		          				</li>
								<li class="text-right">
									<a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($value->postId);?>"><i id="comment_count<?php echo $value->postId;?>"  class="fa fa-comment-o"></i>
									<span id="commentCountView<?php echo $value->postId;?>" data-value="<?php echo $value->comment_count;?>"> <?php echo $value->comment_count;?></span> Comments</a>
								</li>
							</ul>
						</div>
						
						<div class="comment-sec ">
							<ul class="comments-list">
								<?php if(!empty($value->comment)){ ?>
									
								<li id="comment_show<?php echo $value->postId;?>">
									<div class="media">
									  <a href="<?php echo base_url();?>"><img class="mr-2 rounded-circle" src="<?php if(!empty($value->comment[0]->profileImage) AND file_exists(USER_IMG_PATH_THUB.$value->comment[0]->profileImage)){echo base_url().USER_IMG_PATH_THUB.$value->comment[0]->profileImage; }else{echo base_url().PLACEHOLDER;}?>" width="40px"></a>
									  <div class="media-body comments-media-body">
									  	<div class="comments-media-lft">
										    <a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($value->comment[0]->comment_user_id);?>"><h5 class="mt-0"><?php echo $value->comment[0]->fullname;?></h5></a>
										    <p><?php echo time_elapsed_string($value->comment[0]->comment_crd);?></p>
									    </div>							    
									  </div>
									</div>
									<div class="comments-txt">
										<?php $word_count_comment = str_word_count($value->comment[0]->comment);?>
								    	<p><?php echo substr($value->comment[0]->comment,0,200);if($word_count_comment > 200){echo trim("<span style='color:grey;'>......<a href='#'>view more</a></span>");}?></p>
								    </div>
								</li>
								<?php  }else{ ?>
								<li style="color:white; border:0;" id="comment_show<?php echo $value->postId;?>"></li>
								<?php }?>
							</ul>
							<div class="comment-box">
								<div class="media">
								  <img class="mr-2 rounded-circle" src="<?php if(!empty($_SESSION[USER_SESS_KEY]['profileImage']) AND file_exists(USER_IMG_PATH_THUB.$_SESSION[USER_SESS_KEY]['profileImage'])){echo base_url().USER_IMG_PATH_THUB.$_SESSION[USER_SESS_KEY]['profileImage']; }else{echo base_url().PLACEHOLDER;}?>"  width="40px">
								  <div class="media-body">
								    <form action="newsfeed/addComment" id="doComment">
							    		<div class="form-group">
											<textarea onkeyup="getCommentButton('<?php echo $value->postId;?>',this.value.length)" name="comment_text" id="comment_text<?php echo $value->postId;?>" class="form-control main-textarea" id="" placeholder="comment"  maxlength="200"></textarea><span class="remainingCount" id="showRemaining<?php echo $value->postId;?>"><span>
									  	</div>
									  	<button id="buttonComment<?php echo $value->postId;?>" class="btn thm-btn cmt-btn pull-right comment-button" onclick="submitComment('<?php echo $value->postId;?>')" type="button">Comment</button>
									  	<?php $csrf = get_csrf_token();
								         ?>
				  						<input id="csrf<?php echo $value->postId;?>" type="hidden" name="<?php echo $csrf['name'];?>"  value="<?php echo $csrf['hash'];?>">
								    </form>

								  </div>
								</div>
							</div>
						</div>
					</div>        		
				</div>
				<!-- <div class="ajax-load text-center" style="display:none" data-isNext = "" data-offset="">
				<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading More post</p>
				</div> -->
<script type="text/javascript">

function getCommentButton(postId,length) {
	var getRemain = 200 - length ;

	$("#showRemaining"+postId).text(getRemain + " Characters remaining.");
    var textInputValue = $("#comment_text"+postId).val();
    if(textInputValue != ''){
      $("#buttonComment"+postId).show();
    }else{
       $("#buttonComment"+postId).hide();
      
    }
};
</script>
<?php }  }?>

<div class="newsfeed-body mt-20">

<?php  }; ?>
<script type="text/javascript">
	function readMore(id){
		$("#more"+id).show();
		$("#readMoreButton"+id).hide();
		$("#dott"+id).remove();
	}

</script>
				
			