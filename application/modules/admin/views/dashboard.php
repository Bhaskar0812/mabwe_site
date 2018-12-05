

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 style="color:black;">
        Dashboard
        <small>Admin</small>
      </h1>
      <ol class="breadcrumb">
        <!-- <li><a href="<?php //echo base_url();?>admin"><i class="fa fa-dashboard"></i>Dashboard</a></li> -->
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
           <div class="row">
             <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $count;?></h3>

              <p>USERS</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="<?php echo base_url();?>admin/users/allUsers" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>


        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $posts;?></h3>

              <p>POSTS</p>
            </div>
            <div class="icon">
              <i class="fa fa-sticky-note"></i>
            </div>
            <a href="<?php echo base_url();?>admin/users/postList_ajax" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $groups;?><sup style="font-size: 20px"></sup></h3>
              <p>GROUPS</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="<?php echo base_url();?>admin/groups/groupList" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $category;?><sup style="font-size: 20px"></sup></h3>
              <p>CATEGORIES</p>
            </div>
            <div class="icon">
              <i class="fa fa-list"></i>
            </div>
            <a href="<?php echo base_url();?>admin/categories/categoryList" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
       
        <!-- ./col -->
        <!-- ./col -->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  
