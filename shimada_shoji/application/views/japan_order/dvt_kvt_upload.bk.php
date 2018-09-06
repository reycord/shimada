<?php //print_r( $orderReceived);?>
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo $title; ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<button type="button" id="btnPvUpload" data-toggle="modal" data-target="#dvt_kvt_upload_modal" class="btn btn-success">
							<i class="fa fa-cloud-upload"></i>
							<?php echo $this->lang->line('upload_dvt_kvt'); ?>
						</button>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<!-- form input mask -->
				<div class="panel panel-default">
					<div class="panel-body">
						<form id="order_received_pv_upload_form" class="form-horizontal myform" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<div class="col-sm-12">
									<label class="control-label col-sm-2 form-title" for="dvt_kvt_file">
										<?php echo $this->lang->line('dvt_kvt_file'); ?>
										<span class="required">*</span>
									</label>
									<div class="col-sm-9" style="padding-left: 6px;">
										<div class="input-group file">
											<?php echo form_open_multipart('upload/do_upload');?>
											<input id="file_upload" type="text" class="form-control" name="file_upload" placeholder="<?php echo $this->lang->line('file_name'); ?>"
											readonly>
											<span id="choose_file" class="input-group-addon btn-primary" accept=".pdf">
												<?php echo $this->lang->line('choose_file'); ?>
											</span>
										</div>
										<input type="file" id="file_upload_hidden" name="file_upload_hidden" accept=".pdf" style="display:none">
										<?php echo form_error('file_upload'); ?>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-body">
									<div class="form-group">
										<div class="col-sm-4">
											<label class="control-label col-sm-4 form-title" for="preview">
												<?php echo $this->lang->line('preview'); ?> </label>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-12" style="padding-left: 10px;">
											<iframe id="pre_file_upload" class="form-control" frameborder="0" scrolling="no" readonly style="height: 500px;"></iframe>
											<!-- <textarea id="pre_file_upload" type="text" class="form-control" name="pre_file_upload" readonly style="height: 350px;"></textarea> -->
										</div>
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
<!-- Modal -->
<div id="dvt_kvt_upload_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel2">
					<?php echo $this->lang->line('dvt_kvt_modal'); ?>
				</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary antoclose2" data-dismiss="modal">
					<?php echo $this->lang->line('close'); ?>
				</button>
				<button type="submit" class="btn btn-default antosubmit2">
					<?php echo $this->lang->line('yes'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->
