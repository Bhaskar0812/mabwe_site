<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <h1>
        Groups(<?php echo $counts; ?>)
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
         <li>Groups</li>
        
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
              <table id="groupList" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>

                  <th style="width: 1%">S.No.</th>
                  <th style="width: 94px;">Image</th>
                  <th>Group Name</th>
                  <th>Category</th>
                  <th>Status</th>
                  <th style="width: 15%">Action</th>
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
  