<?php //print_r( $orderReceived);?>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
				<h2><?php echo $title; ?></h2>
				<!-- <ul class="nav navbar-right panel_toolbox">
					<li><button id="btnClear" class="btn btn-info"><i class="fa fa-refresh"></i> <?php echo $this->lang->line('clear'); ?></button>
					</li>
				</ul> -->
				<div class="clearfix"></div>
            </div>
            <div class="x_content">
                <!-- form input mask -->
                  <div class="panel panel-default">
                    <div class="panel-body">
                      <form id="inventory_upload_frm" class="form-horizontal myform" action="<?php echo base_url('inventory/uploads');?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                          <div class="col-sm-12" >
                            <label class="control-label col-sm-2 form-title"><?php echo $this->lang->line('excel_file'); ?><span class="required">*</span></label>
                            <div class="col-sm-9" style="padding-left: 6px;">
								<div class="input-group file">
									<?php //echo form_open_multipart('upload/do_upload'); ?>
									<input id="file_upload_inventory" type="text" class="form-control validate[required]" name="file_upload_inventory" placeholder="<?php echo $this->lang->line('file_name'); ?>" readonly>
									<span id="choose_file_inventory" class="input-group-addon btn-primary" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"><?php echo $this->lang->line('choose_file'); ?></span>
								</div>
								<input type="file" id="file_upload_inventory_hidden" name="file_upload_inventory_hidden" style="display:none" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
								<?php echo form_error('file_upload_inventory_hidden'); ?>
							</div>
                          </div>
                        </div>
                        <div class="form-group text-center" style="padding-top:10px; padding-bottom:10px;">
                          <!-- <button id="btn_download" value="Download" class="btn btn-primary">
                            <i class="fa fa-cloud-download"></i> <?php echo $this->lang->line('download'); ?>
                          </button> -->
                          <button type="button" id="btn_upload" value="Upload" class="btn btn-success">
                            <i class="fa fa-cloud-upload"></i> <?php echo $this->lang->line('upload'); ?>
                          </button>
                            <?php if ($this->session->flashdata('error_upload_msg')) { ?>
                                <div style="font-weight: bold; color: red; text-align: left; margin: auto; width: 60%; height: 300px; overflow: auto;"><?php echo $this->session->flashdata('error_upload_msg') ?></div>
                            <?php }?>
                        </div>
							<div class="panel panel-default hidden">
							<div class="panel-body">
								<div class="form-group">
									<div class="col-md-12 col-sm-12 col-xs-12">
										<label class="control-label form-title"><?php echo $this->lang->line('execution_log'); ?></label>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12" style="padding-left: 10px;">
										<iframe id="pre_file_upload" class="form-control" frameborder="0" scrolling="yes" readonly style="height: 500px;"></iframe>
										<!-- <textarea id="pre_file_upload" type="text" class="form-control" name="pre_file_upload" readonly style="height: 350px;"></textarea> -->
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-12" style="text-align: center">
										<button type="button" id="btnImport" data-toggle="modal" data-target="#" class="btn btn-danger"><?php echo $this->lang->line('log_import'); ?></button>
									</div>
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
<!-- Modal -->
<div id="inventory_upload" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2">Inventory Model</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
                <button type="submit" form="" class="btn btn-primary antosubmit2"><?php echo $this->lang->line('yes'); ?></button>
            </div>

        </div>
    </div>
</div>
<!-- End Modal -->
<script>
window.onload = function(){
    //config choose file
    $("#btn_upload").click(function(){
        if(!$("#inventory_upload_frm").validationEngine('validate')){
            return;
        }else{
            bootbox.confirm({
                message: '<h4 style="color:blue;"><?php echo ($this->lang->line('STR0030_I001'))?></h4>',
                buttons: {
                    confirm: {
                        label: '<?php echo $this->lang->line('yes');?>',
                        className: 'btn-success',
                    },
                    cancel: {
                        label: '<?php echo $this->lang->line('no');?>',
                        className: 'btn-primary',
                    }
                },
                callback: function(result){
                    if(result){
                        $("#inventory_upload_frm").submit();
                    };
                }
            })
        }
    });
    
    $('#file_upload_inventory').css( 'cursor', 'pointer' );
    $("#choose_file_inventory, #file_upload_inventory").on("click", function() {
        $("#file_upload_inventory_hidden").trigger("click");
    });
    $("#file_upload_inventory_hidden").change(function() {
        if(this.value.length > 0){
            var pathFile = this.value.replace(/^.*[\\\/]/, '');
            $("#file_upload_inventory").val(pathFile);
        }
        
    });
};
</script>
