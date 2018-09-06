<?php //print_r( $orderReceived);?>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
							<h2><?php echo $title; ?></h2>
							<ul class="nav navbar-right panel_toolbox">
									<li><button type="button" id="btnPvUpload" data-toggle="modal" data-target="#pv_upload_modal" class="btn btn-success"><i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('pv_upload'); ?> </button>
									</li>
							</ul>
							<div class="clearfix"></div>
            </div>
            <div class="x_content">
                <!-- form input mask -->
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <form id="order_received_pv_upload_form" class="form-horizontal myform" action="<?php echo base_url(); ?>order_received/do_upload_pv" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                          <div class="col-sm-12" >
                            <label class="control-label col-sm-2 form-title" for="pv_file" ><?php echo $this->lang->line('pv_file'); ?><span class="required">*</span></label>
                            <div class="col-sm-9" style="padding-left: 6px;">
															<div class="input-group file">
																<input id="file_upload" type="text" class="form-control validate[required]" name="file_upload" placeholder="<?php echo $this->lang->line('file_name'); ?>" readonly>
																<span id="choose_file" class="input-group-addon btn-primary" accept=".pdf"><?php echo $this->lang->line('choose_file'); ?></span>
															</div>
															<input type="file" id="file_upload_hidden" name="file_upload_hidden" class="validate[required]" accept=".pdf" style="display:none">
														</div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-4">
                            <label class="control-label col-sm-4 form-title" for="preview" ><?php echo $this->lang->line('preview'); ?> </label>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-sm-12" style="padding-left: 10px;">
                            <iframe id="pre_file_upload" class="form-control" frameborder="0" scrolling="no" readonly style="height: 500px;"></iframe>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
              <!-- /form input mask -->
			  					
            	</div>
            </div>
        </div>
    </div>
    </div>
</div>
<script>
window.onload = function(){
  $('#btnPvUpload').click(function() {
    if (!$('#order_received_pv_upload_form').validationEngine('validate')) {
      return;
    } else {
      $('#order_received_pv_upload_form').submit();
    }
  });
}
</script>