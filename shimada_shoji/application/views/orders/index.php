<style>
	.err-msg {
		color: #d42a38;
}
</style>
<div class="row">
		<form class="form-horizontal form-label-left" id="frm_search_orders">
				<input type="hidden" name="search" value="1" />
				<div class="form-group">
					<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
						<label class="control-label col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right" for="order_no"><?php echo $this->lang->line('purchase_order_no'); ?></label>
						<div class="col-md-8 col-sm-8 col-xs-8 has-clear has-feedback">
							<input type="text" id="order_no" name="order_no" class="form-control text-uppercase" maxlength="21" value="<?php echo $this->input->get('order_no') ?>">
							<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
						</div>
					</div>
					<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
						<label class="control-label col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right" for="date_by"><?php echo $this->lang->line('date_by'); ?></label>
						<div class="col-md-8 col-sm-8 col-xs-8">
							<select class="form-control" id="select_date" name="select_date">
								<option value=""></option>
								<option value="order_date">Order Date</option>
								<option value="create_date">Create Date</option>
							</select>
						</div>
					</div>
					<div class="col-md-5 col-sm-4 col-xs-4">
						<div class="col-md-4 col-sm-5 col-xs-5 no-padding-left no-padding-right">
							<div class='input-group date' id='created_from' data-date-format="yyyy/mm/dd">
								<input name="order_date_from" id="order_date_from" maxlength="10" type='text' class="form-control" value="<?php echo date('d M, Y'); ?>"/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
						<label class="control-label col-md-1 col-sm-4 col-xs-4 no-padding-top" for="created_to" style="text-align: center; font-size: 20px">&sim;</label>
						<div class="col-md-4 col-sm-5 col-xs-5 no-padding-left no-padding-right">
							<div class='input-group date' id='created_to' data-date-format="yyyy/mm/dd">
								<input name="order_date_to" id="order_date_to" maxlength="10" type='text' class="form-control" value="<?php echo date('d M, Y'); ?>"/>
								<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
				</div>
				</div>
				<div class="form-group">
					<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
					<label class="control-label col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right" for="sales_man"><?php echo $this->lang->line('order_user'); ?></label>
					<div class="col-md-8 col-sm-8 col-xs-8">
												<select class="form-control" id="order_user" name="order_user">
														<option value=""></option>
														<?php 
																if ($this->input->get('order_user') !== null) {
																		$selectedOrderUser = $this->input->get('order_user');
								} 
														?>
														<?php foreach ($employee_list as $employee): ?>
														<option
																value="<?php echo $employee['employee_id']; ?>"
								<?php 
									if(isset($selectedOrderUser)):
										if ($employee['employee_id'] == $selectedOrderUser) :
								?>
										selected
										<?php endif ?>
										<?php endif ?>
														>
																<?php echo $employee['sales_man']; ?>
														</option>
														<?php endforeach?>
												</select>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
					<label class="control-label col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right" for="item_code"><?php echo $this->lang->line('item_code'); ?></label>
					<div class="col-md-8 col-sm-8 col-xs-8 has-clear has-feedback">
						<input type="text" id="item_code" name="item_code" maxlength="30" class="form-control text-uppercase" value="<?php echo $this->input->get('item_code') ?>">
						<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
					</div>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
					<label class="control-label col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right" for="status"><?php echo $this->lang->line('status'); ?></label>
					<div class="col-md-8 col-sm-8 col-xs-8">
							<select name="status" id="status" class="form-control">
								<option value="" selected>ALL</option>
									<?php foreach ($status_list as $status): ?>
											<option 
													value="<?php echo $status['kubun'];?>"
													<?php if ($this->input->get('status') == $status['kubun']) :?>selected<?php endif ?>
											>
													<?php echo $status['komoku_name_2']?>
											</option>
									<?php endforeach?>
							</select>
					</div>
				</div>
				
				<div class="col-md-3 col-sm-3 col-xs-3" style="text-align: right;">
						<button id="search_orders" type="button" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
						<button type='button' id="btnExportExcel" class="btn btn-success" style="margin-right: 0;"> <i class="fa fa-file-excel-o" ></i> Excel </button>
				</div>
			</div>
		</form>
</div>
<div class="row">
		<div class="col-md-12 col-xs-12">
				<div class="x_panel">
						<div class="x_title">
								<h2><?php echo $this->lang->line('purchase_order_list'); ?></h2>
								<ul class="nav navbar-right panel_toolbox">
										<li><button class="btn btn-primary" onclick="window.location.href='<?php echo base_url(); ?>orders/add'"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('new_purchase_order'); ?></button>
										</li>
								</ul>
								<div class="clearfix"></div>
						</div>
						<div class="x_content" id="orderOutList">
							<div class="table-responsive">
							<table id="ordering_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
								<thead>
									<tr>
										<th><?php echo $this->lang->line('purchase_order_no'); ?></th>
										<th><?php echo $this->lang->line('order_user'); ?></th>
										<th><?php echo $this->lang->line('supplier'); ?></th>
										<th><?php echo $this->lang->line('total_amount'); ?></th>
										<th><?php echo $this->lang->line('delivery_date'); ?></th>
										<th><?php echo $this->lang->line('customs_clearance_sheet_no'); ?></th>
										<th><?php echo $this->lang->line('customs_clearance_fee'); ?></th>
										<th><?php echo $this->lang->line('transport_fee'); ?></th>
										<th><?php echo $this->lang->line('create_user'); ?></th>
										<th><?php echo $this->lang->line('accept_user'); ?></th>
										<th><?php echo $this->lang->line('accept_date'); ?></th>
										<th><?php echo $this->lang->line('status'); ?></th>
										<th>PO.sh<?php echo $this->lang->line('printdate'); ?></th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
										<th><?php echo $this->lang->line('update_user'); ?></th>
										<th><?php echo $this->lang->line('update_date'); ?></th>
										<th><?php echo $this->lang->line('action'); ?></th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
</div>

<!-- Modal -->
<!-- Delete modal -->
<div id="order_delete_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog ">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('JOS0010_I004'); ?></h4>
						</div>
						<div class="modal-footer">
								<h4 class="err-msg text-left"></h4>
								<button type="button" class="btn btn-default antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
								<button onclick="delete_orders()" type="button" class="btn btn-primary antosubmit2"><?php echo $this->lang->line('yes'); ?></button>
						</div>
				</div>
		</div>
</div>

<!-- Apply modal -->
<div id="apply_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog ">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('JOS0010_I004'); ?></h4>
						</div>
						<div class="modal-footer">
								<h4 class="err-msg text-left"></h4>
								<button type="button" class="btn btn-default antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
								<button onclick="apply_orders()" type="button" class="btn btn-primary antosubmit2"><?php echo $this->lang->line('yes'); ?></button>
						</div>
				</div>
		</div>
</div>

<!-- Accept modal -->
<div id="accept_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog ">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('JOS0010_I004'); ?></h4>
						</div>
						<div class="modal-footer">
								<h4 class="err-msg text-left"></h4>
								<button type="button" class="btn btn-default antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
								<button onclick="accept_orders()" type="button" class="btn btn-primary antosubmit2"><?php echo $this->lang->line('yes'); ?></button>
						</div>
				</div>
		</div>
</div>

<!-- Denial modal -->
<div id="denial_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog ">
				<div class="modal-content">
						<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('JOS0010_I004'); ?></h4>
						</div>
						<div class="modal-footer">
								<h4 class="err-msg text-left"></h4>
								<button type="button" class="btn btn-default antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
								<button onclick="denial_orders()" type="button" class="btn btn-primary antosubmit2"><?php echo $this->lang->line('yes'); ?></button>
						</div>
				</div>
		</div>
</div>

<!-- PO.sheet modal -->
<div id="po_sheet_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="po_sheet_form" class="form-horizontal form-label-left" method="post">
		<input type="hidden" id="po_no_hidden" name="po_no_hidden" value="" />
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<ul class="nav navbar-right panel_toolbox">
						<li>
							<button class="btn btn-info" data-dismiss="modal">
								<i class="fa fa-arrow-left"></i>
								<?php echo $this->lang->line('back'); ?>
							</button>
						</li>
						<li>
							<button  onclick="poSheet()" class="btn btn-primary">
								<i class="fa fa-print"></i>
								<?php echo $this->lang->line('print'); ?>
							</button>
						</li>
					</ul>
					<h2>
						<?php echo $this->lang->line('po_sheet');?>
					</h2>
				</div>
				<div class="modal-footer">
						<div class="col-md-7">
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="header_name">
									<?php echo $this->lang->line('header_name');?><span class="required">*<span>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
									<select class="form-control validate[required]" id="header_name" name="header_name" style="pointer-events: none;" readonly>
										<option value=""></option>
										<?php foreach($partyList as $branch):?>
										<option value="<?php echo $branch['kubun']; ?>"><?php echo $branch['komoku_name_2']; ?></option>
										<?php endforeach?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="header_address">
									<?php echo $this->lang->line('header_address');?>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
									<textarea spellcheck="false" class="form-control form-rounded" rows="5" id="header_address" name="header_address"  style="resize: none;" readonly></textarea>
								</div>
							</div>
						<div class="form-group"> 
							<label class="control-label col-xs-3 no-padding-left" style="padding-right: 15px;" for="supplier">
								<?php echo $this->lang->line('supplier');?>
							</label>
							<div class="col-xs-9 no-padding-right" style="padding-left: 5px;">
								<input type="text" class="form-control" id="supplier" name="supplier" readonly>
							</div>
						</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="shipper">
									<?php echo $this->lang->line('shipper');?>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
									<select class="form-control" id="shipper" name="shipper">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="payment_term">
									<?php echo $this->lang->line('payment_term');?>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
									<input type="text" class="form-control" id="payment_term" name="payment_term">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="surcharge_infor">
									<?php echo $this->lang->line('surcharge');?>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
									<textarea spellcheck="false" class="form-control form-rounded" rows="3" id="surcharge_infor" name="surcharge_infor"  style="resize: none;" readonly></textarea>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="freight">
									<?php echo $this->lang->line('freight');?><span class="required">*<span>
								</label>
								<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
									<select class="form-control validate[required]" id="freight" name="freight">
										<option value=""></option>
										<option value="SELLER">SELLER</option>
										<option value="BUYER">BUYER</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="insurance">
									<?php echo $this->lang->line('insurance');?><span class="required">*<span>
								</label>
								<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
									<select class="form-control validate[required]" id="insurance" name="insurance">
										<option value=""></option>
										<option value="SELLER">SELLER</option>
										<option value="BUYER">BUYER</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="pv_no">
									PV_NO
								</label>
								<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
									<input type="text" class="form-control text-uppercase" id="pv_no" name="pv_no">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="transportation">
									<?php echo $this->lang->line('transportation');?>
								</label>
								<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
									<input type="text" class="form-control text-uppercase" id="transportation" name="transportation" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="signature">
									<?php echo $this->lang->line('signature');?>
								</label>
								<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
									<input type="text" class="form-control" id="signature" name="signature" readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="note">
									<?php echo $this->lang->line('note');?>
								</label>
								<div class="col-md-1 no-padding-left" style="padding: 5px;">
									<input type="checkbox" class="flat" id="note" name="note">
								</div>
							</div>
							<div class="form-group"  id="note_parent">
								<label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left" style="padding-right: 15px;" for=""></label>
								<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
										<select  class="form-control" disabled name="note_detail" id="note_detail">
											<option value="0">Seller agrees... Contract</option>
											<option value="1">Please ensure...</option>
										</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- CONTRACT modal -->
<div id="contract_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form id="contract_form" class="form-horizontal form-label-left" action="<?php echo base_url() ?>orders/excel" method="POST">
		<input type="hidden" name="po_no" id="po_no" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<ul class="nav navbar-right panel_toolbox">
						<li>
							<button type="button" class="btn btn-info" data-dismiss="modal">
								<i class="fa fa-arrow-left"></i>
								<?php echo $this->lang->line('back'); ?>
							</button>
						</li>
						<li>
							<button type="button" onclick="contract()" class="btn btn-primary">
								<i class="fa fa-print"></i>
								<?php echo $this->lang->line('print'); ?>
							</button>
						</li>
					</ul>
					<h2>
						<?php echo $this->lang->line('contract_print');?>
					</h2>
				</div>
				<div class="modal-footer">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" for="header_name">
								<?php echo $this->lang->line('header_name');?><span class="required">*<span>
							</label>
							<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
								<select class="form-control validate[required]" id="contract_header_name" name="contract_header_name" style="pointer-events: none;" readonly>
									<option value=""></option>
									<?php foreach($partyList as $party):?>
									<option value="<?php echo $party['kubun']; ?>"><?php echo $party['komoku_name_2']; ?></option>
									<?php endforeach?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right"  for="contract_header_address">
								<?php echo $this->lang->line('header_address');?>
							</label>
							<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
								<textarea spellcheck="false" class="form-control form-rounded" rows="5" id="contract_header_address" name="contract_header_address" style="resize: none;" readonly></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3 no-padding-left no-padding-right" for="contract_payment_term">
								<?php echo $this->lang->line('payment_term');?><span class="required">*<span>
							</label>
							<div class="col-xs-9 no-padding-right" style="padding-left: 5px;">
								<input type="text" class="form-control" id="contract_payment_term" name="contract_payment_term">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-right" for="inv_delivery_condition">
								<?php echo $this->lang->line('reference');?>
							</label>
							<div class="col-md-9 col-sm-3 col-xs-3 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
								<input type="text" class="form-control form-rounded text-uppercase" id="reference" name="reference" maxlength="30">
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important"></span>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group"> 
							<label class="control-label col-xs-3 no-padding-left no-padding-right" for="contract_supplier">
								<?php echo $this->lang->line('supplier');?><span class="required">*<span>
							</label>
							<div class="col-xs-9 no-padding-right" style="padding-left: 5px;">
								<input type="text" class="form-control" id="contract_supplier" name="contract_supplier" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right"  for="contract_bank_address">
								<?php echo $this->lang->line('bank');?>
							</label>
							<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
								<textarea spellcheck="false" class="form-control form-rounded" rows="5" id="contract_bank_address" name="contract_bank_address" style="resize: none;" readonly></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-xs-3 no-padding-left no-padding-right" for="contract_date">
								<?php echo $this->lang->line('contract_date');?><span class="required">*<span>
							</label>
							<div class="col-xs-3 no-padding-right" style="padding-left: 5px;">
								<input type="text" class="form-control validate[required] date" id="contract_date" name="contract_date" value="<?php echo date('j M, Y');?>">
							</div>
							<label class="control-label col-xs-3 no-padding-left no-padding-right" for="contract_expire_date">
								<?php echo $this->lang->line('expire_date');?><span class="required">*<span>
							</label>
							<div class="col-xs-3 no-padding-right" style="padding-left: 5px;">
								<input type="text" class="form-control validate[required] date" id="contract_expire_date" name="contract_expire_date">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" for="contract_signature">
								<?php echo $this->lang->line('signature');?>
							</label>
							<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
								<input type="text" class="form-control" id="contract_signature" name="contract_signature" readonly>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="modal fade" id="denialModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h4><?php echo ($this->lang->line('POD0010_I003')); ?></h4>
        </div>
        <div class="modal-body">
            <h4 style="color:red" id="po_no_denial"></h4>
            <label class="col-md-2 col-xs-2 col-sm-2" for="note_denial"><?php echo $this->lang->line('reason');?> :</label>
            <textarea spellcheck="false" type="text" class="form-control" id="note_denial" name="note_denial" rows="3"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $this->lang->line('no');?></button>
            <button id="btn_denial" type="button" class="btn btn-success" ><?php echo $this->lang->line('yes');?></button>
        </div>
      </div>
      
    </div>
  </div>

<script>
	// TODO comment
	var poSheetRequestData = {};
	var branchInfo = <?php echo json_encode($branchInfo)?>;
	var partyList = <?php echo json_encode($partyList)?>;
	<?php $permissionList = explode(",", PERMISSION_MANAGER);?>
	var permission = <?php echo json_encode(in_array($user['permission_id'] ,$permissionList)); ?>;
	var finish = <?php echo json_encode(explode(",", STATUS_FINISH))?>;

	window.onload = function() {
		$('#po_sheet_form').validationEngine();
		var itemDt = $('#ordering_list').DataTable({
			"data": [],
			"paging": true,
			"filter": false,
			"ordering": true,
			"scrollX" :true,
			"serverSide": true,
			"dom": 'l <"fixed_columns"> rtip',
			"drawCallback": function(setting) {
				var columns = $('#fixedColumns').val();
				$(".fixed_columns").css({display: "inline"});
				$(".fixed_columns").html(`<label class="control-label" style="padding-right: 5px;"><?php echo $this->lang->line('freeze_column'); ?></label>
				<select name="fixedColumns" id="fixedColumns" class="form-control" style="height:30px;">
					<option value="1"><?php echo $this->lang->line('purchase_order_no'); ?></option>
					<option value="2"><?php echo $this->lang->line('order_user'); ?></option>
					<option value="3"><?php echo $this->lang->line('supplier'); ?></option>
					<option value="4"><?php echo $this->lang->line('total_amount'); ?></option>
					<option value="5"><?php echo $this->lang->line('delivery_date'); ?></option>
				</select>`);
				if(columns != undefined) {
					$('#fixedColumns').val(columns);
				}
			},
			"ajax": {
				"url": "<?php echo base_url('orders/search'); ?>",
				"type": 'post',
				"data": function ( data ) {
					data.param = {};
					var arr = $('#frm_search_orders').serializeArray();
					$.each(arr, function(index, item) {
						if(item.value != '') {
							data.param[item.name] = item.value;
						}
					});
				}
			},
			"columns": [
				{ "data": "order_no", className: "text-left",
					"render": function( data, type, row, meta ) {
						var order_no_6 = (row.order_no_6 != null && row.order_no_6 != "") ? ('-'+row.order_no_6) : "";
						return '<a href="<?php echo base_url() ?>orders/edit/'+ row.order_no_1 +'/'+row.order_no_2+'/'+row.order_no_3+'/'+row.order_no_4+'/'+row.order_no_5+'/'+row.buyer_kb+'/'+row.order_no_6+'" style="color:#428bca;">'+row.order_no_1 +'-'+row.order_no_2+'-'+row.order_no_3+'-'+('0000'+row.order_no_4).substring(row.order_no_4.length)+(row.buyer_kb == '2' ? '(HN)' : '')+'/'+row.order_no_5+order_no_6+'</a>'
					}
				},
				{ "data": "order_user" , className: "text-left"},
				{ "data": "supplier_name" , className: "text-left"},
				{ "data": "amount", className: "text-right", 
					"render": function( data, type, row, meta ) {
						return (numberWithCommas(parseFloat(row.amount).myToFixed(row.currency)));
					} 
				},
				{ "data": "delivery_date" },
				{ "data": "customs_clearance_sheet_no" , className: "text-left" },
				{ "data": "customs_clearance_fee" , className: "text-right",
					"render": function( data, type, row, meta ) {
						return (numberWithCommas(parseFloat(row.customs_clearance_fee).myToFixed(row.currency)));
					} 
				},
				{ "data": "transport_fee" , className: "text-right",
					"render": function( data, type, row, meta ) {
						return (numberWithCommas(parseFloat(row.transport_fee).myToFixed(row.currency)));
					} 
				},
				{ "data": "create_user" , className: "text-left" },
				{ "data": "accept_user" , className: "text-left" },
				{ "data": "accept_date" },
				{ "data": "status_name" },
                { "data": "po_sheet_date" },
                { "data": "note_denial" },
				{ "data": "edit_user" , className: "text-left" },
				{ "data": "edit_date" },
				{ "data": "action", render: function( data, type, row, meta ){
					// set active or inactive for accept button and denial button
										var apply_user = accept_user = denial_user = 'disabled';
										var finishIndex = finish.indexOf(row.kubun);
										var finishFlg = (finishIndex == -1 ? true : false);
										var html = '<div class="input-group" style="padding-left: 1px;">'
					+ '<a data-event="apply-item" class="btn '+ (row["apply_user"] ? 'btn-default' :'btn-warning') + ' btn-sm btn-custom no-padding-top no-padding-bottom" title="<?php echo $this->lang->line('apply'); ?>" '+ (row["apply_user"] || !finishFlg?'disabled':'') +'>'
										+ '<span>Apply</span>'
					+ '</a>';
					html += '<div class="input-group" style="padding-left: 1px;">'
										+ '<a data-event="accept-item" class="btn ' + (row["accept_user"] ? 'btn-default' :'btn-danger') + ' btn-sm btn-custom no-padding-top no-padding-bottom" title="<?php echo $this->lang->line('accept'); ?>" '+ (!row["apply_user"]||row["accept_user"]||!permission||!finishFlg?'disabled':'') +'>'
										+ '<span><?php echo $this->lang->line('accept'); ?></span>'
										+ '</a>';
										html += '<span class="input-group-btn" style="padding-left: 1px;">'
										+ '<a data-event="denial-item" class="btn ' + (row["apply_user"] ?'btn-info' :'btn-default') + ' btn-sm btn-custom no-padding-top no-padding-bottom" title="<?php echo $this->lang->line('denial'); ?>" '+ (row["apply_user"]&&permission&&finishFlg?'':'disabled') +'>'
										+ '<span><?php echo $this->lang->line('denial'); ?></span>'
										+ '</a>'
										+ '</span>';
					html += '<div class="input-group" style="padding-left: 1px;">'
					+ '<a onclick="show_modal_contract(this)" data-toggle="modal" class="btn btn-success btn-sm btn-custom no-padding-top no-padding-bottom" title="<?php echo $this->lang->line('cont'); ?>">'
					+ '<span>CONT</span>'
					+ '</a>';
					html += '<span class="input-group-btn" style="padding-left: 1px;">'
					+ '<a data-event="posh-item" class="btn btn-primary btn-sm btn-custom no-padding-top no-padding-bottom" title="<?php echo $this->lang->line('po_sh'); ?>">'
					+ '<span>PO.sh</span>'
					+ '</a>'
					+ '</span>'
					+ '<span style="padding-left: 1px;">'
					+ '<a data-event="delete-item" class="btn btn-danger btn-sm btn-custom" data-toggle="modal" title="<?php echo $this->lang->line('delete'); ?>" ' + (finishFlg ? '' : 'disabled') + '>'
					+ '<span class="glyphicon glyphicon-trash"></span>'
					+ '</a>'
					+ '</span>';
					return html;
				}},
			],
			"createdRow": function(row, data, dataIndex) {
				$(row).find('td:eq(15)').attr('style', 'padding: 2px 5px 2px 5px !important');
			}
		});
		
		var fixedColumns = new $.fn.dataTable.FixedColumns(itemDt, {
			iLeftColumns: 1
		});
		$('#orderOutList').on("change", "#fixedColumns", function(){
			fixedColumns.s.iLeftColumns = $(this).val();
			fixedColumns.fnRedrawLayout();
		});

		$('#search_orders').on('click', function(){
			$('.err-msg[field=order_date_to]').html('');
			var create_date_from = $('#order_date_from').val();
			var create_date_to = $('#order_date_to').val();

			create_date_from = new Date(create_date_from);
			create_date_to = new Date(create_date_to);
		
			// compare create date to vs create date from
			if(create_date_to < create_date_from) {
				$('.err-msg[field=order_date_to]').html('Create date from > Create date to!');
				return;
			}
				itemDt.ajax.reload();
		});
		$('#btnExportExcel').on('click', function(){
			var dt = itemDt.rows().data();
			// console.log(dt);
			var json = [];
			for (let i = 0; i < dt.length ; i++){
				var requestData = {
					order_no_1 		:  dt[i].order_no_1,
					order_no_2 		:  dt[i].order_no_2,
					order_no_3 		:  dt[i].order_no_3,
					order_no_4 		:  dt[i].order_no_4,
					order_no_5 		:  dt[i].order_no_5,
					order_no_6		:  dt[i].order_no_6,
					buyer_kb			:  dt[i].buyer_kb,
					order_date		:  dt[i].order_date,
				};
				json.push(requestData);
			};
			var form = document.createElement("form");
			form.setAttribute("method", "post");
			form.setAttribute("action", "<?php echo base_url(); ?>orders/po_excel");
			var hiddenField = document.createElement("textarea");
			hiddenField.setAttribute("type", "hidden");
			hiddenField.setAttribute("name", "export_data");
			hiddenField.value = JSON.stringify(json);
			form.appendChild(hiddenField);
			document.body.appendChild(form);
			form.submit();
		});
		
		// handle button apply
		$('#ordering_list').on('click', '[data-event="apply-item"]', function(){
			// If button disabled => do nothing
			if(!$(this).attr('disabled')){
				var $row = $(this).parents('tr');
				rowData = itemDt.row($row).data();
				// get user login, full time current and data of this row
				var user_login = "<?php echo $user['employee_id'] ?>";
				var today = new Date()
				today = moment().format("YYYY-MM-DD HH:mm:ss",today);

				var data = $('#ordering_list').DataTable().row( $(event).parents('tr') ).data();
				var requestData = {
					order_no_1 		: rowData.order_no_1,
					order_no_2 		: rowData.order_no_2,
					order_no_3 		: rowData.order_no_3,
					order_no_4 		: rowData.order_no_4,
					order_no_5 		: rowData.order_no_5,
					order_no_6		: rowData.order_no_6,
					buyer_kb 			: rowData.buyer_kb,
					edit_date 		: rowData.edit_date,
					edit_user 		: rowData.edit_user,
					order_date		: rowData.order_date,
					apply_user		: user_login,
					apply_date		: today,
				};
				
				var order_no_6 = (rowData.order_no_6 != null) ? ('-'+rowData.order_no_6) : "";
				bootbox.confirm({
					title: "<?php echo ($this->lang->line('POD0010_I001')); ?>",
					message: '<h4 style="color:blue;">' + rowData.order_no_1 +'-'+rowData.order_no_2+'-'+rowData.order_no_3+'-'+rowData.order_no_4+'/'+rowData.order_no_5+'</h4>', 
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
					callback: function(result) {
						if(result) {
							$.ajax({
								url: "<?php echo base_url('orders/apply')?>",
								data: requestData,
								type: 'post',
								dataType: 'json'
							}).done(function(response) {
								snackbarShow(response.message);
								if(response.success == true) { 
									// itemDt.row($row).remove();
									// itemDt.ajax.reload();
									itemDt.draw( false );
								} else {
									itemDt.draw( false );
								}
							});
						}
					}
				});
			}
		});

		// handle button accept
		$('#ordering_list').on('click', '[data-event="accept-item"]', function(){
			// If button disabled => do nothing
			if(!$(this).attr('disabled')){
				var $row = $(this).parents('tr');
				rowData = itemDt.row($row).data();
				// get user login, full time current and data of this row
				var user_login = "<?php echo $user['employee_id'] ?>";
				var today = new Date()
				today = moment().format("YYYY-MM-DD HH:mm:ss",today);

				var data = $('#ordering_list').DataTable().row( $(event).parents('tr') ).data();
				var requestData = {
					order_no_1 		: rowData.order_no_1,
					order_no_2 		: rowData.order_no_2,
					order_no_3 		: rowData.order_no_3,
					order_no_4 		: rowData.order_no_4,
					order_no_5 		: rowData.order_no_5,
					order_no_6 		: rowData.order_no_6,
					buyer_kb 			: rowData.buyer_kb,
					edit_date 		: rowData.edit_date,
					edit_user 		: rowData.edit_user,
					order_date		: rowData.order_date,
					accept_user		: user_login,
					accept_date		: today,
				};
				
				var order_no_6 = (rowData.order_no_6 != null) ? ('-'+rowData.order_no_6) : "";
				bootbox.confirm({
					title: "<?php echo ($this->lang->line('POD0010_I002')); ?>",
					message: '<h4 style="color:blue;">' + rowData.order_no_1 +'-'+rowData.order_no_2+'-'+rowData.order_no_3+'-'+rowData.order_no_4+'/'+rowData.order_no_5+'</h4>', 
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
					callback: function(result) {
						if(result) {
							$.ajax({
								url: "<?php echo base_url('orders/accept')?>",
								data: requestData,
								type: 'post',
								dataType: 'json'
							}).done(function(response) {
								snackbarShow(response.message);
								if(response.success == true) {
									// itemDt.ajax.reload();
									itemDt.draw( false );
								} else {
									itemDt.draw( false );
								}
							});
						}
					}
				});
			}
		});
		$('#note').on('ifChecked', function(event){
			$('#note_detail').attr("disabled", false);
		});
		$('#note').on('ifUnchecked', function(event){
			$('#note_detail').attr("disabled", true);
		});
		// handle button denial
		$('#ordering_list').on('click', '[data-event="denial-item"]', function(){
			// If button disabled => do nothing
			if(!$(this).attr('disabled')){
				var $row = $(this).parents('tr');
                rowData = itemDt.row($row).data();
                // console.log(rowData);
				// get user login, full time current and data of this row
				var user_login = "<?php echo $user['employee_id'] ?>";
				var today = new Date()
				today = moment().format("YYYY-MM-DD HH:mm:ss",today);

				var data = $('#ordering_list').DataTable().row( $(event).parents('tr') ).data();
				var requestData = {
					order_no_1 		: rowData.order_no_1,
					order_no_2 		: rowData.order_no_2,
					order_no_3 		: rowData.order_no_3,
					order_no_4 		: rowData.order_no_4,
					order_no_5 		: rowData.order_no_5,
					order_no_6 		: rowData.order_no_6,
					buyer_kb 			: rowData.buyer_kb,
					order_date		: rowData.order_date,
					edit_date 		: rowData.edit_date,
					edit_user 		: rowData.edit_user,
					denial_user		: user_login,
                    denial_date		: today,
				};
				
                var order_no_6 = (rowData.order_no_6 != null) ? ('-'+rowData.order_no_6) : "";
                $('#po_no_denial').text(rowData.order_no_1 +'-'+rowData.order_no_2+'-'+rowData.order_no_3+'-'+rowData.order_no_4+'/'+rowData.order_no_5);
                $('#note_denial').val(rowData.note_denial);
                $('#denialModal').modal('show');
                $('#btn_denial').click(function(){
                    requestData.note_denial = $('#note_denial').val();
                    $.ajax({
                        url: "<?php echo base_url('orders/denial')?>",
                        data: requestData,
                        type: 'post',
                        dataType: 'json'
                    }).done(function(response) {
                        snackbarShow(response.message);
                        if(response.success == true) { 
                            // itemDt.row($row).remove();
                            // itemDt.ajax.reload();
                            $('#denialModal').modal('hide');
                            itemDt.draw( false );
                        } else {
                            itemDt.draw( false );
                        }
                    });
                });
				// bootbox.confirm({
				// 	title: "<?php echo ($this->lang->line('POD0010_I003')); ?>",
                //     message: $(".content-modal-denial").html(), 
				// 	buttons: {
                //         confirm: {
				// 			label: '<?php echo $this->lang->line('yes');?>',
				// 			className: 'btn-success',
				// 		},
				// 		cancel: {
				// 			label: '<?php echo $this->lang->line('no');?>',
				// 			className: 'btn-primary',
                //         }
				// 	},
				// 	callback: function(result) {
				// 		if(result) {
                //             var note = document.getElementById('note_denial').value;  
                //             // console.log(requestData); return;
				// 			$.ajax({
				// 				url: "<?php echo base_url('orders/denial')?>",
				// 				data: requestData,
				// 				type: 'post',
				// 				dataType: 'json'
				// 			}).done(function(response) {
				// 				snackbarShow(response.message);
				// 				if(response.success == true) { 
				// 					// itemDt.row($row).remove();
				// 					// itemDt.ajax.reload();
				// 					itemDt.draw( false );
				// 				} else {
				// 					itemDt.draw( false );
				// 				}
				// 			});
				// 		}
				// 	}
				// });
            }
        });

		// handle button PO.sh
		$('#ordering_list').on('click', '[data-event="posh-item"]', function(){
			var $row = $(this).parents('tr');
			rowData = itemDt.row($row).data();
			$('#supplier').val(rowData.supplier_name);
			if(rowData.buyer_kb == '1'){
				$('#header_name').val('001');
			}else{
				$('#header_name').val('002');
			}
			const header = searchBranch($('#header_name').val(), partyList);
			if(header){
				$("#header_address").val(header.note2);
			}
			// get user login, full time current and data of this row
			var user_login = "<?php echo $user['employee_id'] ?>";
			var today = new Date();
			today = moment().format("YYYY-MM-DD HH:mm:ss",today);
			var po_no_hidden = rowData.order_no_1+";"+rowData.order_no_2+";"+rowData.order_no_3+";"+rowData.order_no_4+";"+
					rowData.order_no_5+";"+rowData.buyer_kb+";"+rowData.order_no_6+";"+rowData.order_date;
			$('#po_no_hidden').val(po_no_hidden);
			poSheetRequestData = {
				order_no_1 		: rowData.order_no_1,
				order_no_2 		: rowData.order_no_2,
				order_no_3 		: rowData.order_no_3,
				order_no_4 		: rowData.order_no_4,
				order_no_5 		: rowData.order_no_5,
				order_no_6 		: rowData.order_no_6,
				order_date		: rowData.order_date,
				buyer_kb			: rowData.buyer_kb,
				po_sheet_date	: today,
			};
			$.ajax({
				url: "<?php echo base_url('orders/getPOPrintInfo')?>",
				data: poSheetRequestData,
				type: 'post',
				async: false,
				dataType: 'json'
			}).done(function(response) {
				var res = response.data;
				var pv_info = response.pv_info;
				var supplier = response.supplier;
				if(supplier){
					$('#surcharge_infor').val(supplier.surcharge_infor);
				}
				if(res && Object.keys(res).length > 0) {
					// $('#header_name').val(res.header);
					// const header = searchBranch(res.header, partyList);
					// if(header){
					// 	$("#header_address").val(header.note2);
					// }
					$('#pv_no').val(res.pv_no);
					if(res.note == '1'){
						$('#note').iCheck('check');
					}
					if(res.note_detail ){
						$('#note_detail').val(res.note_detail);
					}
					// $('#supplier').val(res.supplier_name);
					$('#insurance').val(res.insurance);
					$('#freight').val(res.freight);

					$("#intercom > option").each(function() {
						if($(this).text() == res.intercom){
							$(this).prop('selected', true);
						}else{
							$(this).prop('selected', false);
						}
					});
				}
				if(pv_info) { 
					$('#pv_no').val(pv_info);
				}
				if(supplier && Object.keys(supplier).length > 0) {
					if (supplier.first_name){
						$('#signature').val(supplier.first_name+' '+supplier.last_name);
					}
					$('#payment_term').val(supplier.payment_term);
					$('#po_sheet_modal #payment_term').val(supplier.payment_term&&supplier.payment_term.split(";")[0].trim());
					$('#transportation').val(supplier.transportation);
					var shipper = supplier.shippers;
					for(let i = 0; i < shipper.length; i++){
							var shipperName = shipper[i].shipper_name;
							if(res.shipper == shipperName){
								$('#shipper').append($("<option></option>").attr("value",shipperName).prop('selected', true).text(shipperName)); 
								continue;
							}
							$('#shipper').append($("<option></option>").attr("value",shipperName).text(shipperName)); 
					}
				}
				$('#po_sheet_modal').modal('show');
			});
		});

		// handle button delete
		$('#ordering_list').on('click', '[data-event="delete-item"]', function(){
			// If button disabled => do nothing
			if(!$(this).attr('disabled')){
				var $row = $(this).parents('tr');
				rowData = itemDt.row($row).data();
				// get user login, full time current and data of this row
				var user_login = "<?php echo $user['employee_id'] ?>";
				var today = new Date()
				today = moment().format("YYYY-MM-DD HH:mm:ss",today);

				var data = $('#ordering_list').DataTable().row( $(event).parents('tr') ).data();
				var requestData = {
					order_no_1 		: rowData.order_no_1,
					order_no_2 		: rowData.order_no_2,
					order_no_3 		: rowData.order_no_3,
					order_no_4 		: rowData.order_no_4,
					order_no_5 		: rowData.order_no_5,
					order_no_6 		: rowData.order_no_6,
					buyer_kb 			: rowData.buyer_kb,
					order_date		: rowData.order_date,
				};
				
				var order_no_6 = (rowData.order_no_6 != null) ? ('-'+rowData.order_no_6) : "";
				bootbox.confirm({
					title: "<?php echo $this->lang->line('POD0010_I005');?>",
					message: '<h4 style="color:blue;">' + rowData.order_no_1 +'-'+rowData.order_no_2+'-'+rowData.order_no_3+'-'+('0000'+rowData.order_no_4).substring(rowData.order_no_4.length)+'/'+rowData.order_no_5+'</h4>', 
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
					callback: function(result) {
						if(result) {
							$.ajax({
								url: "<?php echo base_url('orders/delete')?>",
								data: requestData,
								type: 'post',
								dataType: 'json'
							}).done(function(response) {
								snackbarShow(response.message);
								if(response.success == true) { 
									itemDt.row($row).remove();
									itemDt.draw( false );
								} else {
									itemDt.draw( false );
								}
							});
						}
					}
				});
			}
		});
	// $('#header_name').on('change', function(){
	// 	const branch = searchBranch(this.value, partyList);
	// 	$("#header_address").val(branch.note2);
	// });
	// $('#contract_header_name').on('change', function(){
	// 	const branch = searchBranch(this.value, partyList);
	// 	$("#contract_header_address").val(branch.komoku_name_3);
	// });
	// $('#contract_bank').on('change', function(){
	// 	const bank = searchBranch(this.value, bankList);
	// 	$("#contract_bank_address").val(bank.komoku_name_3);
	// });

	$('#po_sheet_modal').on('hidden.bs.modal', function () {
				$('#po_sheet_modal select').val("");
		$('#po_sheet_modal input').val("");
		$('#po_sheet_modal textarea').val("");
		$('#po_sheet_modal input').iCheck('uncheck');
		$('#po_sheet_form').validationEngine('hideAll');
	});
	$('#contract_modal').on('hidden.bs.modal', function() {
		$('#contract_modal select').val('');
		$('#contract_modal textarea').val('');
		$('#contract_expire_date').val('');
	});
};
	function poSheet(){
		const result = $('#po_sheet_form').validationEngine('validate');
		if(!result){
			return;
		}
		$.ajax({
			url: "<?php echo base_url('orders/posh')?>",
			data: poSheetRequestData,
			type: 'post',
			dataType: 'json'
		}).done(function(response) {
			snackbarShow(response.message);
			$('#ordering_list').DataTable().draw(false);
		});

		var url = "<?php echo base_url('orders/exportpdf')?>";
		$("#po_sheet_form").attr("action", url).submit();
		$('#po_sheet_modal').modal('hide');
	}

	function searchBranch(nameKey, myArray){
		for (var i=0; i < myArray.length; i++) {
			if (myArray[i].kubun === nameKey) {
				return myArray[i];
			}
		}
		return false;
	}
	function show_modal_contract(obj){
		var $row = $(obj).parents('tr');
		var rowData = $('#ordering_list').DataTable().row($row).data();
		var poNo = rowData.order_date+','+rowData.order_no_1+','+rowData.order_no_2+','+rowData.order_no_3+','+rowData.order_no_4+','+rowData.order_no_5+','+rowData.buyer_kb;
		// console.log(partyList);
		if(rowData.buyer_kb == '1'){
			$('#contract_header_name').val('001');
		}else{
			$('#contract_header_name').val('002');
		}
		const cont_header = searchBranch($('#contract_header_name').val(), partyList);
		if(cont_header){
			$("#contract_header_address").val(cont_header.note2);
		}
		$('#po_no').val(poNo);
			contractRequestData = {
				order_no_1 		: rowData.order_no_1,
				order_no_2 		: rowData.order_no_2,
				order_no_3 		: rowData.order_no_3,
				order_no_4 		: rowData.order_no_4,
				order_no_5 		: rowData.order_no_5,
				order_no_6 		: rowData.order_no_6,
				order_date		: rowData.order_date,
				buyer_kb			: rowData.buyer_kb,
			};
			$.ajax({
				url: "<?php echo base_url('orders/getContractPrintInfo')?>",
				data: contractRequestData,
				type: 'post',
				async: false,
				dataType: 'json'
			}).done(function(response) {
				var res = response.data;
				if(res && Object.keys(res).length > 0) { 
					// $('#contract_header_name').val(res.party_a);
					// const party_a = searchBranch(res.party_a, partyList);
					// if(party_a){
					// 	$("#contract_header_address").val(party_a.komoku_name_3);
					// }
					// $('#contract_bank').val(res.bank);
					// const bank = searchBranch(res.bank, bankList);
					// if(bank){
					// 	$("#contract_bank_address").val(bank.komoku_name_3);
					// }
					$('#contract_supplier').val(res.supplier_name);
					$('#contract_bank_address').val(res.bank_info);
					$('#contract_payment_term').val(res.payment_term);

					if(res.contract_date){
						$('#contract_date').val(formatDate(res.contract_date));
					}

					$('#contract_expire_date').val(formatDate(res.end_date));
					if(res.last_name){
						$('#contract_signature').val(res.first_name + ' ' + res.last_name);
					}
					if(res.reference){
						$('#reference').val(res.reference_print||res.reference);
					}
				}
		});
		$('#contract_modal').modal('show');
	}
	function contract(){
		if(!$('#contract_form').validationEngine('validate')){
			return;
		}
		$('#contract_form').submit();
		$('#contract_modal').modal('hide');
	}
</script>

