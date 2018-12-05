<?php if(!empty($post_details)){?>
<div class="wraper light-bg">
  <div class="mt-78 pb-20 group-detail-section">
    <div class="container">
	    <div class="row">
	    	<div class="col-lg-4 col-md-6 col-sm-12 col-12">
	    		<div class="group-detail news-feed-detail sticky mt-40">
	    			<div class="create-sec">
	    				<div class="detail-media-body">
	    					<div class="detail-media-lft">
							<h6>Posted By</h6>
							</div>
							<!-- <div class="detail-media-rht">
								<div class="dropdown">
							    	<a id="dropdownMenuButton2" href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
							    	<div class="dropdown-menu media-dropdown dropdown-menu-right">
									    <a class="dropdown-item" href="javascript:void(0);"><i class="fa fa-edit"></i>Edit</a>
									    <a class="dropdown-item" href="javascript:void(0);"><i class="fa fa-trash"></i>Delete</a>
									</div>
							    </div>
							</div> -->
						</div>
						<div class="create-txt">
							<div class="media">

							  <a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($post_details->userId);?>"><img class="mr-2 rounded-circle" src="<?php 
							  if(!empty($post_details->profile_image) AND file_exists(USER_IMG_PATH_THUB.$post_details->profile_image)){
							  	echo base_url().USER_IMG_PATH_THUB.$post_details->profile_image; 
							  }else{echo base_url().PLACEHOLDER;

							  }?>" width="50px"></a>
							  <div class="media-body align-self-center">
						  		<h5 class="mt-0"><a href="<?php echo base_url().'user/userProfile/?user_id='. encoding($post_details->userId);?>"><?php echo display_placeholder_text($post_details->fullname);?></a></h5>
						    	<span><?php echo time_elapsed_string($post_details->post_created_date);?></span>					    
							  </div>
							</div>							
						</div>
					</div>
					<ul class="grp-tags">
						<?php foreach($post_details->tags as $val){?>
						<li>
							<p>#<?php echo $val->tagName;?></p>
						</li>
						<?php }?>
					</ul>
					<?php if(!empty($post_details->categoryName)){ 
						    switch ($post_details->categoryName){
    						case "Career":$class = 'txt-ble';break;case "Marketplace":$class = 'txt-org';break;case "Mabwe411":$class = 'txt-ble';break; case "Connect":$class = 'txt-org';break; } }
						    	?>
					<div class="grs-txt pb-15 <?php echo $class;?>">
	    			
	    				<h2><?php echo $post_details->categoryName;?></h2>
					</div>			
	    		</div>
	    	</div>
	    	<?php if(empty($post_details->video) AND empty($post_details->video_thumb)){?>
	    	<div class="col-lg-8 col-md-6 col-sm-12 col-12">
	    		<div class="group-commnt newsfeed-commnt mt-40">
	    			<div class="newsfeed-detail">
		    			<h4><?php echo $post_details->title;?><span></span></h4>
		    			<h6><i class="fa fa-map-marker"></i> <?php  echo $post_details->address;?> </h6>		    			
		    		</div>
					<div class="grp-img">
	    				<div id="NewsFeedsHome<?php echo $post_details->postId;?>" class="news-feed-slider owl-carousel sliderShowNewsFedd">


							<?php if(!empty($post_details->postimage)){
								
								foreach($post_details->postimage as $val){
							?>

				            <div class="testimonial"> 
				            	<a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($post_details->postId);?>"><img src="<?php echo $val->post_attachment;?>" alt=""></a>
				            </div>
				            <?php } } ?>
				        </div>	
				        <script>
				        	$("#NewsFeedsHome<?php echo $post_details->postId;?>").owlCarousel({
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
				    </div>
	    			<div class="newsfeed-detail mt-20">
		    			<p><?php echo $post_details->description;?></p>
		    			<?php if(!empty($post_details->authorisedToWork)){?>
		    			<h5>Are You legally authorized to work in this Country?</h5>
		    			<p><?php if($post_details->authorisedToWork == 1){echo "Yes"; }else{ echo "No";}?></p>
		    			<?php } ?>

		    			<?php if(!empty($post_details->willingToRelocate)){?>
		    			<h5>Are You Willing to relocate?</h5>
		    			<p><?php if($post_details->willingToRelocate == 1){echo "Yes"; }else{ echo "No";}?></p>
		    			<?php } ?>
		    			<!-- <h5>Are You Willing to ship?</h5>
		    			<p>No</p>
		    			<h5>joha@gmail.com</h5>
		    			<p>012345678</p> -->
		    		</div>

		    		
		    		<div class="newsfeed-info-detail mt-10">
						<ul class="info-icon">
							<li>
	          					<i class="<?php if($post_details->user_like_status == 1){echo 'fa fa-heart';}else{ echo 'fa fa-heart-o';}?>" id="likesIconVisible<?php echo $post_details->postId;?>" onclick="likeUnlike('<?php echo $post_details->postId;?>');" data-value="<?php if($post_details->user_like_status == 1){echo 1;}else{ echo 0;}?>" data-url="../newsfeed/likeUnlike"></i>

	          					<a href="javascript:void(0);" data-toggle="modal" data-target="#Likes" onclick="getPostLikes(0,'<?php echo $post_details->postId;?>');"><span id="likesCount<?php echo $post_details->postId;?>" data-value="<?php echo $post_details->like_count;?>">
	          						<?php if($post_details->like_count != 0){?>
	          						<?php echo $post_details->like_count;?>
	          						</span> <span id="likeDisabled<?php echo $post_details->postId;?>">Likes</span><?php  }?></a>
	          				</li>

	          				<li class="text-right">
									<a href="javascript:void(0);"><i id="comment_count<?php echo $post_details->postId;?>"  class="fa fa-comment-o"></i>
									<span id="commentCountView<?php echo $post_details->postId;?>" data-value="<?php echo $post_details->comment_count;?>"> <?php echo $post_details->comment_count;?></span> Comments</a>
							</li>

							
						</ul>
					</div>
					
					<div id='showLoaderComment' class="show_loader clearfix" data-offset="0" data-isNext="1" data-id="<?php echo $post_details->postId;?>">
                    
                    	<img src='<?php echo base_url().MWIMAGES;?>loadMore.apng' alt=''>
            		</div>
            		<input type="hidden" id="rowCountComment" value="">

					<div class="cmnts-list" id="scrollDivPostDetail">
						<ul class="cmmnt-sec perfectScrollbar1" id="showCommentFeedDetails">
						<li class="showdiv">
							<center><button class="pris-cmmt mt-10" onclick="getPostComments(0);" id="showLoadMoreComment" style="cursor: pointer;">Load previous comment..</button></center>
						</li>
						
						</ul>							
					</div>

					<div class="our-commnt-box">
						<div class="media">
						  <img class="mr-2 rounded-circle" src="<?php if(!empty($post_details->profile_image) AND file_exists(USER_IMG_PATH_THUB.$post_details->profile_image)){echo base_url().USER_IMG_PATH_THUB.$post_details->profile_image; }else{echo base_url().PLACEHOLDER;}?>" width="40px">
						  <div class="media-body">
						   <form action="addCommentFromFeedDeatil/" id="doComment">
					    		<div class="form-group">
									<textarea onkeyup="getCommentButton('<?php echo $post_details->postId;?>',this.value.length)" name="comment_text" id="comment_text<?php echo $post_details->postId;?>" class="form-control main-textarea" id="" placeholder="Write comment here..."  maxlength="200"></textarea><span class="remainingCount" id="showRemaining<?php echo $post_details->postId;?>"><span>
							  	</div>
							  	<button id="buttonComment<?php echo $post_details->postId;?>" class="btn thm-btn cmt-btn pull-right comment-button" onclick="submitCommentFromPostDetails('<?php echo $post_details->postId;?>')" type="button">Comment</button>
							  	<?php $csrf = get_csrf_token();
						         ?>
		  						<input id="csrf<?php echo $post_details->postId;?>" type="hidden" name="<?php echo $csrf['name'];?>"  value="<?php echo $csrf['hash'];?>">
						    </form>
						  </div>
						</div>
					</div>
	    		</div>
	    	</div>
	    	<?php } ?>

	    	<?php if(!empty($post_details->post_video) AND !empty($post_details->video_thumb)){?>

	    	<div class="col-lg-8 col-md-6 col-sm-12 col-12">
	    		<div class="group-commnt newsfeed-commnt mt-40">
	    			<div class="newsfeed-detail">
		    			<h4><?php echo $post_details->title;?><span></span></h4>
		    			<h6><i class="fa fa-map-marker"></i> <?php  echo $post_details->address;?> </h6>		    			
		    		</div>
					<div class="grp-img">
	    				<div><a href="<?php echo base_url()."post/postDetailView?post_id=".encoding($post_details->postId);?>">
							<video style="width: 100%;max-width:700px;height:360px; background-color: #000; border: 1px solid #efee;" poster="<?php echo $post_details->video_thumb;?>" controls controlsList="nodownload">
							  <source src="<?php echo $post_details->post_video;?>" type="video/mp4">
							</video></a>
						</div>
	    			</div>

	    			 <script>
				        	$("#NewsFeedsDetail<?php echo $post_details->postId;?>").owlCarousel({
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
	    			<div class="newsfeed-detail mt-20">
		    			<p><?php echo $post_details->description;?></p>
		    			<?php if(!empty($post_details->authorisedToWork)){?>
		    			<h5>Are You legally authorized to work in this Country?</h5>
		    			<p><?php if($post_details->authorisedToWork == 1){echo "Yes"; }else{ echo "No";}?></p>
		    			<?php } ?>

		    			<?php if(!empty($post_details->willingToRelocate)){?>
		    			<h5>Are You Willing to relocate?</h5>
		    			<p><?php if($post_details->willingToRelocate == 1){echo "Yes"; }else{ echo "No";}?></p>
		    			<?php } ?>
		    			<!-- <h5>Are You Willing to ship?</h5>
		    			<p>No</p>
		    			<h5>joha@gmail.com</h5>
		    			<p>012345678</p> -->
		    		</div>

		    		
		    		<div class="newsfeed-info-detail mt-10">
						<ul class="info-icon">
							<li>
	          					<i class="<?php if($post_details->user_like_status == 1){echo 'fa fa-heart';}else{ echo 'fa fa-heart-o';}?>" id="likesIconVisible<?php echo $post_details->postId;?>" onclick="likeUnlike('<?php echo $post_details->postId;?>');" data-value="<?php if($post_details->user_like_status == 1){echo 1;}else{ echo 0;}?>" data-url="../newsfeed/likeUnlike"></i>

	          					<a href="javascript:void(0);" data-toggle="modal" data-target="#Likes" onclick="getPostLikes(0,'<?php echo $post_details->postId;?>');"><span id="likesCount<?php echo $post_details->postId;?>" data-value="<?php echo $post_details->like_count;?>">
	          						<?php if($post_details->like_count != 0){?>
	          						<?php echo $post_details->like_count;?>
	          						</span> <span id="likeDisabled<?php echo $post_details->postId;?>">Likes</span><?php  }?></a>
	          				</li>

	          				<li class="text-right">
									<a href="javascript:void(0);"><i id="comment_count<?php echo $post_details->postId;?>"  class="fa fa-comment-o"></i>
									<span id="commentCountView<?php echo $post_details->postId;?>" data-value="<?php echo $post_details->comment_count;?>"> <?php echo $post_details->comment_count;?></span> Comments</a>
							</li>

							
						</ul>
					</div>
					
					
            		<input type="hidden" id="rowCountComment" value="">

					
													
					<div id='showLoaderComment' class="show_loader clearfix" data-offset="0" data-isNext="1" data-id="<?php echo $post_details->postId;?>">
                    
                    	<img src='<?php echo base_url().MWIMAGES;?>loadMore.apng' alt=''>
            		</div>
					<div class="cmnts-list" id="scrollDivPostDetail">
						
						<ul class="cmmnt-sec perfectScrollbar1" id="showCommentFeedDetails">
						<li class="showdiv">
							<center><button class="pris-cmmt mt-10" onclick="getPostComments(0);" id="showLoadMoreComment" style="cursor: pointer;">Load previous comment..</button></center>
						</li>
						
						</ul>
							

					</div>
					
					<div class="our-commnt-box">
						<div class="media">
						  <img class="mr-2 rounded-circle" src="<?php if(!empty($post_details->profile_image) AND file_exists(USER_IMG_PATH_THUB.$post_details->profile_image)){echo base_url().USER_IMG_PATH_THUB.$post_details->profile_image; }else{echo base_url().PLACEHOLDER;}?>" width="40px">
						  <div class="media-body">
						   <form action="addCommentFromFeedDeatil/" id="doComment">
					    		<div class="form-group">
									<textarea onkeyup="getCommentButton('<?php echo $post_details->postId;?>',this.value.length)" name="comment_text" id="comment_text<?php echo $post_details->postId;?>" class="form-control main-textarea" id="" placeholder="Write comment here..."  maxlength="200"></textarea><span class="remainingCount" id="showRemaining<?php echo $post_details->postId;?>"><span>
							  	</div>
							  	<button id="buttonComment<?php echo $post_details->postId;?>" class="btn thm-btn cmt-btn pull-right comment-button" onclick="submitCommentFromPostDetails('<?php echo $post_details->postId;?>')" type="button">Comment</button>
							  	<?php $csrf = get_csrf_token();
						         ?>
		  						<input id="csrf<?php echo $post_details->postId;?>" type="hidden" name="<?php echo $csrf['name'];?>"  value="<?php echo $csrf['hash'];?>">
						    </form>
						  </div>
						</div>
					</div>
	    		</div>
	    	</div>
	    	<?php } ?>

	    </div>
    </div>
  </div>
</div>

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

<script type="text/javascript">
	function readMore(id){
		$("#more"+id).show();
		$("#readMoreButton"+id).hide();
		$("#dott"+id).remove();
	}

</script>	
<?php }?>

