<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">

      <h1>
        Comment List
        <small>List(<?php echo $counts; ?>)</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url();?>admin/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a></li>
         <li>Comment's List</li>
        
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
              <table id="commentList" class="table table-bordered table-striped" width="100%">
                <thead>
                <tr>

                  <th style="width: 1%">S.No.</th>
                  <th>Comment</th>
                  <th>Title</th>
                  <th>Status</th>
                  <th style="width: 15%">Action</th>
                </tr>
                </thead>
                <tfoot>
                </tfoot>




                <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


  
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
  