<?php foreach ($posts as $users) {
foreach($users->comments as $key=> $values){                   
?>
<div class="box-footer box-comments customerData">
    <div class="box-comment">
        <?php 
        if(!empty($values->profile_image)){ ?>
            <img id="blah" class="img-responsive img-circle" src="<?php echo $posts['0']->profile_image; ?>" alt="User profile picture">
            <? }else {
                 $imgPath = base_url().DEFAULT_IMAGE; ?>
                  <img id="blah" class="img-responsive img-circle" src="<?php echo $imgPath; ?>" alt="User profile picture">
                  <?php
                }  
            ?>
                <div class="comment-text">
                    <span class="username">
        <?php echo $values->fullname; ?>
        <span class="text-muted pull-right">8:03 PM Today</span>
                    </span>
                    <!-- /.username -->
                    <?php echo $values->comment; ?>
                </div>
                <!-- /.comment-text -->
    </div>
    <!-- /.box-comment -->
</div>
<?php }} ?>



