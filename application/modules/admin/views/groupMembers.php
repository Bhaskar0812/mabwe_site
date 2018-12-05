
<?php if($member_list){ foreach($member_list as $key=> $values){?><div class="box-comment">
    <?php      
    if(!empty($values->profile_image)){ ?>
        <img id="blah" class="img-responsive img-circle" src="<?php echo $values->profile_image; ?>" alt="User profile picture">
        <? }else {
             $imgPath = base_url().DEFAULT_IMAGE; ?>
              <img id="blah" class="img-responsive img-circle" src="<?php echo $imgPath; ?>" alt="User profile picture">
              <?php
            }  
        ?>
            <div class="comment-text">
                <span class="username">
    <a href="<?php echo base_url().'admin/users/userDetail/.'.encoding($values->userId); ?>"><?php echo $values->fullname; ?></a>
    <!-- <span class="text-muted pull-right">8:03 PM Today</span> -->
                </span>
                <!-- /.username -->
                <!-- <?php //echo $values->comment; ?> -->
            </div>
            <!-- /.comment-text -->  
</div>
<?php } } ?>