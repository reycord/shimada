<div class="row">
	<div class="col-md-12  col-sm-12 col-xs-12">
		<div class="x_panel">
			<!-- Title top -->
			<div class="x_title">
				<h2>
					<?php echo $title ?>
				</h2>
				<div class="clearfix"></div>
			</div>

			<!-- Content of page -->
			<div class="x_content">
				<div class="panel panel-default" style="margin-bottom: 3px !important;">
					<div class="panel-body" style="padding: 4px !important;">
						<label class="control-label" style="font-size: 20px; margin-bottom: 0;"><?php echo $this->lang->line('hello');?>
							<a class="edit" href="<?php echo base_url('users/profile/'.$user['employee_id'])?>">
								<?php if (isset($user) && $user != null) {echo ($user['first_name'] .' '. $user['last_name']);}?>
							</a>!</label>
					</div>
				</div>

				<!-- Manager -->
				<div class="panel panel-default" style="margin-bottom: 3px !important;">
					<div class="panel-body" style="padding: 4px !important;">
						<div class="form-group col-md-12" style="margin-bottom: 0;">
							<label class="control-label" style="font-size: 18px; margin-bottom: 0;"><?php echo $this->lang->line('manager');?></label>
						</div>
						<div class="form-group col-md-12" style="margin-bottom: 5px !important;">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-info control-label icon-dasboard"><i class="fa fa-info-circle"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('order_received');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countOrderReceive']['count'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_order_receive'); ?></span>
							</div>
						</div>
						<div class="form-group col-md-12" style="margin-bottom: 0;">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-info control-label icon-dasboard"><i class="fa fa-info-circle"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('ordering');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countOrderOut']['count'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_order_out'); ?></span>
							</div>
						</div>
					</div>
				</div>

				<!-- HCMC Office -->
				<div class="panel panel-default" style="margin-bottom: 3px !important;">
					<div class="panel-body" style="padding: 4px !important;">
						<div class="form-group col-md-12" style="margin-bottom: 0;">
							<label class="control-label" style="font-size: 18px; margin-bottom: 0;">HCMC Office</label>
						</div>
						<div class="form-group col-md-12" style="margin-bottom: 5px !important;">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-info control-label icon-dasboard"><i class="fa fa-info-circle"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('confirm_schedule');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countHCMCScheduledInput']['count'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_confirm_schedule'); ?></span>
							</div>
						</div>
						<div class="form-group col-md-12" style="margin-bottom: 5px">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-warning control-label icon-dasboard"><i class="fa fa-warning"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('sales_input');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countSalesInput']['count'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_sales_input'); ?></span>
							</div>
						</div>

						<div class="form-group col-md-12" style="margin-bottom: 0;">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-warning control-label icon-dasboard"><i class="fa fa-warning"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('delivery_date_input');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countDeliveryDate'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_delivery_date'); ?></span>
							</div>
						</div>

					</div>
				</div>

				<!-- VSIP Office -->
				<div class="panel panel-default" style="margin-bottom: 6px !important;">
					<div class="panel-body" style="padding: 4px !important;">
						<div class="form-group col-md-12" style="margin-bottom: 0;">
							<label class="control-label" style="font-size: 18px; margin-bottom: 0;">VSIP Office</label>
						</div>
						<div class="form-group col-md-12" style="margin-bottom: 5px !important;">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-warning control-label icon-dasboard"><i class="fa fa-warning"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('input_schedule');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countScheduledInput']['count'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_schedule_input'); ?></span>
							</div>
						</div>
						<div class="form-group col-md-12" style="margin-bottom: 5px !important;">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-warning control-label icon-dasboard"><i class="fa fa-warning"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('inventory_input');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countInventoryInput']['count'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_inventory_input'); ?></span>
							</div>
						</div>
						<div class="form-group col-md-12" style="margin-bottom: 5px !important;">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-info control-label icon-dasboard"><i class="fa fa-info-circle"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('delivery_order');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countDeliveryOrder']['count'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_delivery_order'); ?></span>
							</div>
						</div>
						<div class="form-group col-md-12" style="margin-bottom: 0;">
							<div class="col-md-2" style="text-align: right;">
								<span class="btn btn-info control-label icon-dasboard"><i class="fa fa-info-circle"></i></span>
							</div>
							<div class="col-md-3" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"><?php echo $this->lang->line('packing_order');?></span>
							</div>
							<div class="col-md-1" style="padding-top: 5px;">
								<span style="font-size: 17px;">: </span>
								<span class="control-label" style="font-size: 17px; color: #068aff;"> <?php echo $order_receive_count['countPackingOrder']['count'] ?></span>
							</div>
							<div class="col-md-6 no-padding-left" style="padding-top: 5px;">
								<span class="control-label" style="font-size: 17px;"> <?php echo $this->lang->line('infor_packing_order'); ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
