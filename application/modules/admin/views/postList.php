<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Posts(<?php echo $count; ?>)
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
         <li>Posts</li>
      </ol>
    </section>

    <!-- Main content -->
   <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="pull-right col-md-3 noMargin">                   
            </div>
            <div class="pull-right div-select col-md-3 noMargin">  
            </div>
            <div class="box-body">
              <table id="postList" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>
                  <th>S.No.</th>
                  <th>Image</th>
                  <th>Category</th>
                  <th>Title</th>
                  <th>Likes</th>
                  <th>Comments</th>
                  <th style="width:68px;">Posted By</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>