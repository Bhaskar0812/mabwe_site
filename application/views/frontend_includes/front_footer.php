<?php if(!empty($_SESSION[USER_SESS_KEY])){ ?>


<section class="section_7">
  <footer>
    <div class="footer-down pt-20 pb-20">
      <div class="container">      
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="copy-txt">
              <p><?php echo COPYRIGHT;?> <a href="<?php echo base_url();?>">Mabwe</a></p>
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="down-txt">
              <ul>
                <li><a href="javascript:void(0);">Terms & Conditions</a></li>                            
                <li><a href="javascript:void(0);">Help & Support</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</section>
</main>
<!-- Modal -->
<div class="modal fade" id="Filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
       
        	<div class="row">
           

            <?php if(!empty($categories['result'])){foreach($categories['result']  as $value){ ?>
        		<div class="col-lg-6 col-md-6 col-sm-6 col-6">
        			<div class="form-group">
		        		<div class="filter-icon">
		        			<input id="connect<?php echo $value->categoryId;?>" class="getFilterData" value="<?php echo $value->categoryId;?>" name="filter[]" type="checkbox">
			        		<label for="connect<?php echo $value->categoryId;?>">	        			
			        			<img class="inactive" src="<?php echo base_url().CATEGORIES_UPLOAD_FOLDER.$value->categoryImage;?>" alt="">
			        			<!-- <img class="active" src="img/active-career-img.png" alt=""> -->
			        			<span class="active"><i class="fa fa-check"></i></span>
			        			<h6 class="or-clr"><?php echo $value->categoryName;?></h6>
			        		</label>
		        		</div>
		        	</div>	
        		</div>
        		<?php } }?>
        	 <div id='showLoaderScrollFilter'  class="show_loader clearfix" data-offset="0" data-isNext="1">
              <img src='<?php echo base_url().MWIMAGES;?>loadMore.apng' alt=''>
            </div>
        	</div>
          <?php $csrf = get_csrf_token();
                   ?>
       
          <input id="csrf" type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>">
        	<a href="javascript:void(0);" onclick="postList(0,1);" class="apply-button btn apply-btn mt-10">Apply</a>
        	<button type="reset" onclick = "clearFilter();" class="btn mt-10 clear-btn">Clear</button>
        
      </div>
    </div>
  </div>
</div>


<input type="hidden" value="" id="rowCountLikes">
  <div class="modal fade" id="Likes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><span id="likeCount"></span> Likes</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       <div id="boxscroll2">
        <div class="modal-body scroll-div do-nicescrol perfectScrollbar1" id="scrollDiv">
          <ul class="members-list " id="showUserLikedList">
                  
                
          </ul>
        </div>
        <div id='showLoaderScroll' class="show_loader clearfix" data-offset="0" data-isNext="1">
                <img src='<?php echo base_url().MWIMAGES;?>loadMore.apng' alt=''>
          </div>
        </div>
    
        
           <div class="modal-footer modal-footer-border">
        </div>
      </div>
    </div>
  </div>
<input type="hidden" value="" id="rowCountUsers">

<div class="modal fade" id="Members" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabell" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabell"><span id="userCount"></span> Members</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
       <div id="boxscroll22">
        <div class="modal-body scroll-div do-nicescrol perfectScrollbar2" id="scrollDivMembers">
          <ul class="members-list " id="showUserGroupList">
                  
                
          </ul>
        </div>
        <div id='showLoaderScrollList' class="show_loader clearfix" >
                <img src='<?php echo base_url().MWIMAGES;?>loadMore.apng' alt=''>
          </div>
        </div>
    
        
           <div class="modal-footer modal-footer-border">
        </div>
      </div>
    </div>
  </div>

<?php }?>


<?php $base_url = base_url();?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="<?php echo $base_url.MWJS;?>bootstrap.min.js"></script>
<?php if(!empty($_SESSION[USER_SESS_KEY])){?>
<script src="<?php echo $base_url.MWJS;?>perfect-scrollbar.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
<script>
  var ps = new PerfectScrollbar('.perfectScrollbar1');
</script>
<script>
  var ps = new PerfectScrollbar('.perfectScrollbar2');
</script>
<?php } ?>


<?php
    if(!empty($js)){
         load_js($js);
    }
?>
<script src='<?php echo $base_url.MWJS;?>toast.js'></script>
<script src="<?php echo $base_url.MWJS;?>bootbox/bootbox.min.js"></script>

<script src="<?php echo $base_url.MWCJS;?>custom.js?v=6"></script>

</html>