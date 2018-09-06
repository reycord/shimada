<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo $title ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<button class="btn btn-dark" data-toggle="modal" onclick= "beforeShowCONTPrintModal()" >
							<i class="fa fa-file"></i>
							<?php echo $this->lang->line('cont_export');?>
						</button>
					</li>
					<li>
						<button class="btn btn-primary" onclick= "beforeShowPLPrintModal()" data-toggle="modal">
							<i class="fa fa-file"></i>
							<?php echo $this->lang->line('inv_pl_export');?>
						</button>
					</li>
					<!-- <li>
						<button class="btn btn-info" data-toggle="modal" data-target="#origin_set_modal">
							<i class="fa fa-cogs"></i>
							<?php echo $this->lang->line('origin_set');?>
						</button>
					</li> -->
					<!-- <li class="dropdown">
                        <button href="#" class="btn  btn-success dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-share"></i> Tools</button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Import CSV</a>
                            </li>
                        </ul>
                    </li> -->
				</ul>
				<div class="clearfix"></div>
			</div>
			<style>
				#inv_pl_print_list>tbody>tr>td {
					vertical-align: middle !important;
					padding-top: 2px !important;
					padding-bottom: 2px !important;
				};
			</style>
			<div class="x_content">
				<div class="form-group">
					<select 
							class="form-control" 
							multiple 
							name="dvt_select" 
							id="dvt_select"
							style="height: 40px;"
					>
					</select>
				</div>
				<div class="table-responsive">
					<table id="inv_pl_print_list" class="table table-striped table-bordered display nowrap cssTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('delivery_no'); ?></th>
								<th><?php echo $this->lang->line('times'); ?></th>
								<th><?php echo $this->lang->line('passage_date'); ?></th>
								<th><?php echo $this->lang->line('wish_delivery_date'); ?></th>
								<th><?php echo $this->lang->line('packing_no'); ?></th>
								<th><?php echo $this->lang->line('header_name'); ?></th>
								<th><?php echo $this->lang->line('consignee'); ?></th>
								<th><?php echo $this->lang->line('buyer'); ?></th>
								<th><?php echo $this->lang->line('printdate'); ?></th>
								<th><?php echo $this->lang->line('status'); ?></th>
								<th><?php echo $this->lang->line('shipping_method'); ?></th>
								<th><?php echo $this->lang->line('export_same_time'); ?></th>
								<th><?php echo $this->lang->line('select'); ?></th>
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
<!-- Header Set modal -->
	<div id="inv_pl_print_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form class="form-horizontal form-label-left" id="INVPLForm" action="<?php echo base_url() ?>inv_pl_print/inv_pl" method="post">
			<input type="hidden" id="delivery_no_list" name="delivery_no_list" value="" />
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
								<button id="btnINVPrint" type="button" onclick="INVPLPRINT()" class="btn btn-primary">
									<i class="fa fa-print"></i>
									<?php echo $this->lang->line('print'); ?>
								</button>
							</li>
						</ul>
						<h2>
							<?php echo $this->lang->line('inv_pl_export');?>
						</h2>
					</div>
					<div class="modal-footer">
							<div class="col-md-7">
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="header_name">
										<?php echo $this->lang->line('header_name');?>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
										<input type="text" class="form-control" id="header_name" name="header_name"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="header_address">
										<?php echo $this->lang->line('header_address');?><span class="required">*<span>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
										<textarea spellcheck="false" class="form-control form-rounded" 
												data-validation-engine="validate[required]"
												 rows="3" id="header_address" name="header_address"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px; color:#e43434 " for="consigned_name">
										<?php echo $this->lang->line('consigned_to');?>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
										<input type="text" class="form-control" id="consigned_name" name="consigned_name"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="consigned_to">
										<?php echo $this->lang->line('address');?><span class="required">*<span>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
										<textarea spellcheck="false" class="form-control form-rounded" 
												data-validation-engine="validate[required]"
												rows="3" id="consigned_to" name="consigned_to"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="buyer">
										<?php echo $this->lang->line('buyer');?>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
										<input type="text" class="form-control" id="buyer" name="buyer"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="buyer_address">
										<?php echo $this->lang->line('buyer_address');?>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
										<textarea spellcheck="false" class="form-control form-rounded" rows="3" id="buyer_address" name="buyer_address"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="notify">
										<?php echo $this->lang->line('notify');?>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
										<input type="text" class="form-control" id="notify" name="notify"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>

								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="notify_address">
										<?php echo $this->lang->line('notify_address');?>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right" style="padding-left: 5px;">
										<textarea spellcheck="false" class="form-control form-rounded" rows="3" id="notify_address" name="notify_address"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" for="payment_by" style="padding-right: 15px;">
									<?php echo $this->lang->line('payment_term');?>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-feedback has-clear" style="padding-left: 5px;">
										<input type="text" class="form-control" id="payment_by_name" name="payment_by_name"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="inv_delivery_condition">
										<?php echo $this->lang->line('delivery_condition');?>
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
										<input type="text" class="form-control form-rounded text-uppercase" id="inv_delivery_condition" name="inv_delivery_condition">
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="from">
										From
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
										<input type="text" class="form-control form-rounded text-uppercase" id="from" name="from"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="to">
										To
									</label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right has-clear has-feedback" style="padding-left: 5px;">
										<input type="text" class="form-control form-rounded text-uppercase" id="to" name="to"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>
								
								<div class="form-group">
								<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" style="padding-right: 15px;" for="origin">
										<?php echo $this->lang->line('origin_set');?>
									</label>
									<div class="col-md-1" style="padding-top: 5px; text-align: left;">
										<input type="checkbox" class="flat" id="origin" name="origin">
									</div>
									<label class="control-label col-md-4 col-sm-3 col-xs-3 no-padding-left" for="shosha_price">
										<?php echo $this->lang->line('shosha_price_set');?>
									</label>
									<div class="col-md-1 " style="padding-top: 5px; text-align: left;">
										<input type="checkbox" class="flat" id="shosha_price" name="shosha_price">
									</div>
									<label class="control-label col-md-2 col-sm-3 col-xs-3 no-padding-left" for="lot_no">
										<?php echo $this->lang->line('lot_no');?>
									</label>
									<div class="col-md-1" style="padding-top: 5px; text-align: left;">
										<input type="checkbox" class="flat" id="lot_no" name="lot_no">
									</div>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="print_date">
										<?php echo $this->lang->line('printdate');?><span class="required">*</span>
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right has-clear has-feedback">
										<input type="text" class="form-control hasDatepicker date validate[required]" data-validation-engine="validate[required]" id="print_date" name="print_date" maxlength="10" >
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="delivery_print_date">
										<?php echo $this->lang->line('delivery_date');?><span class="required">*</span>
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right has-clear has-feedback">
										<input type="text" class="form-control hasDatepicker date validate[required]" data-validation-engine="validate[required]" id="delivery_print_date" name="delivery_print_date" maxlength="10" >
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group" style="border: 1px solid blue; border-radius: 5px;">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="output_select">
										<?php echo $this->lang->line('output_select');?>
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right" style="text-align: left;">
										<div class="form-group" style="margin-bottom:0px;">
											<div class="col-md-2 no-padding-left" style="padding: 5px;">
												<input type="checkbox" class="flat" id="invoice" name="invoice">
											</div>
											<div class="col-md-10" style="padding: 5px;">
												<span style="text-transform: uppercase; font-weight: bold;">
													<?php echo $this->lang->line('invoice');?>
												</span>
											</div>
										</div>
										<div class="form-group" style="margin-bottom:0px;">
											<div class="col-md-2 no-padding-left" style="padding: 5px;">
												<input type="checkbox" class="flat" id="packing_list" name="packing_list">
											</div>
											<div class="col-md-10" style="padding: 5px;">
												<span style="text-transform: uppercase; font-weight: bold;">
													<?php echo $this->lang->line('packing_list');?>
												</span>
											</div>
										</div>
										<div class="form-group" style="margin-bottom:0px;">
											<div class="col-md-2 no-padding-left" style="padding: 5px;">
												<input type="checkbox" class="flat" id="delivery_note" name="delivery_note">
											</div>
											<div class="col-md-10" style="padding: 5px;">
												<span style="text-transform: uppercase; font-weight: bold;">
													<?php echo $this->lang->line('delivery_note');?>
												</span>
											</div>
										</div>
										<div class="form-group" style="margin-bottom:0px;">
											<div class="col-md-2 no-padding-left" style="padding: 5px;">
												<input type="checkbox" class="flat" id="inv_del_voucher_excel" name="inv_del_voucher_excel">
											</div>
											<div class="col-md-10" style="padding: 5px;">
												<span style="text-transform: uppercase; font-weight: bold;">
													<?php echo $this->lang->line('inv_del_voucher');?>
												</span>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="invoice_no_excel">Invoice No</label>
									<div class="col-md-7 col-sm-3 col-xs-3 no-padding-right has-clear has-feedback">
										<input type="text" class="form-control form-rounded text-uppercase" id="invoice_no_excel" name="invoice_no_excel" maxlength="30"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="invoice_no_excel">Red Invoice No</label>
									<div class="col-md-7 col-sm-3 col-xs-3 no-padding-right has-clear has-feedback">
										<input type="text" class="form-control form-rounded text-uppercase" id="red_invoice_no_excel" name="red_invoice_no_excel" maxlength="30"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="other_reference">Other Reference</label>
									<div class="col-md-7 col-sm-3 col-xs-3 no-padding-right has-clear has-feedback">
										<input type="text" class="form-control form-rounded text-uppercase" id="other_reference" name="other_reference" maxlength="30"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="contract_no_pl">
										<?php echo $this->lang->line('contract_no');?>
									</label>
									<div class="col-md-7 col-sm-3 col-xs-3 no-padding-right has-clear has-feedback">
										<input type="text" class="form-control form-rounded text-uppercase" id="contract_no_pl" name="contract_no_pl" maxlength="30">
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="vessel_flight">
										<?php echo $this->lang->line('vessel_flight');?>
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right has-clear has-feedback">
										<input type="text" class="form-control form-rounded text-uppercase" id="vessel_flight" name="vessel_flight">
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="customer_code">
										<?php echo $this->lang->line('customer_code');?>
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
										<select name="customer_code" id="customer_code" class="form-control">
											<option value=""></option>
											<?php foreach ($customer_code_list as $customer_code): ?>
											<option value="<?php echo $customer_code['kubun']; ?>" <?php if (set_value( 'customer_code') == $customer_code[ 'kubun']) : ?>
												selected
												<?php endif ?> >
												<?php echo $customer_code['komoku_name_2']; ?>
											</option>
											<?php endforeach?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="data_currency">
										<?php echo $this->lang->line('data_currency');?>
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
										<input type="text" class="form-control" id="data_currency" name="data_currency" readonly>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="output_currency">
										<?php echo $this->lang->line('output_currency');?>
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
										<select name="output_currency" id="output_currency" class="form-control">
											<?php foreach ($currency_list as $currency): ?>
											<option value="<?php echo $currency['currency_name']; ?>">
												<?php echo $currency['currency_name']; ?>
											</option>
											<?php endforeach?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="rate_usd">
										<?php echo $this->lang->line('rate_out');?> USD→VND
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
										<input type="text" class="form-control" id="rate_usd" name="rate_usd" onkeydown="return checkQuantity(event)"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="rate_jpy">
										<?php echo $this->lang->line('rate_out');?> JPY→VND
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
										<input type="text" class="form-control" id="rate_jpy" name="rate_jpy" onkeydown="return checkQuantity(event)"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left" for="rate_jpy_usd">
										<?php echo $this->lang->line('rate_out');?> JPY→USD
									</label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
										<input type="text" class="form-control" id="rate_jpy_usd" name="rate_jpy_usd" onkeydown="return checkQuantity(event)"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-5 col-sm-3 col-xs-3 no-padding-left">
										<?php echo $this->lang->line('tax');?></label>
									<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
										<select class="form-control" id="tax_classification" name="tax_classification">
											<option value="001">VAT 10%</option>
											<option value="002">VAT 0%</option>
										</select>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</form>
	</div>
<!-- End Modal -->

<!-- Modal -->
<!-- Origin Set modal -->
<div id="origin_set_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
						<button class="btn btn-primary">
							<i class="fa fa-save"></i>
							<?php echo $this->lang->line('save'); ?>
						</button>
					</li>
				</ul>
				<h2>
					<?php echo $this->lang->line('origin_set');?>
				</h2>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table id="origin_list" data-modal="#origin_set_modal" class="table table-striped table-bordered datatable cssTable display nowrap"
					width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>
									<?php echo $this->lang->line('delivery_no');?>
								</th>
								<th>KVT No</th>
								<th>
									<?php echo $this->lang->line('item_code');?>
								</th>
								<th>
									<?php echo $this->lang->line('item_name');?>
								</th>
								<th>
									<?php echo $this->lang->line('size');?>
								</th>
								<th>
									<?php echo $this->lang->line('color');?>
								</th>
								<th>
									<?php echo $this->lang->line('price');?>
								</th>
								<th>
									<?php echo $this->lang->line('origin');?>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<a class="edit" href="#">DVT008517</a>
								</td>
								<td>
									<a class="edit" href="#">KVT009951</a>
								</td>
								<td>IT0000001</td>
								<td>Polyess</td>
								<td></td>
								<td></td>
								<td>
									<input class="form-control" style="width: 100px; height: 30px;" placeholder="0.0001">
								</td>
								<td>
									<select name="origin" id="origin" class="form-control" style="width: 70px; height: 30px;">
										<option value=""></option>
										<?php foreach ($origin_list as $origin): ?>
										<option value="<?php echo $origin['kubun']; ?>" <?php if (set_value( 'origin') == $origin[ 'kubun']) : ?>
											selected
											<?php endif ?> >
											<?php echo $origin['komoku_name_2']; ?>
										</option>
										<?php endforeach?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<a class="edit" href="#">DVT008517</a>
								</td>
								<td>
									<a class="edit" href="#">KVT009951</a>
								</td>
								<td>IT0000001</td>
								<td>Polyess</td>
								<td></td>
								<td></td>
								<td>
									<input class="form-control" style="width: 100px; height: 30px;" placeholder="0.0001">
								</td>
								<td>
									<select name="origin" id="origin" class="form-control" style="width: 70px; height: 30px;">
										<option value=""></option>
										<?php foreach ($origin_list as $origin): ?>
										<option value="<?php echo $origin['kubun']; ?>" <?php if (set_value( 'origin') == $origin[ 'kubun']) : ?>
											selected
											<?php endif ?> >
											<?php echo $origin['komoku_name_2']; ?>
										</option>
										<?php endforeach?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<a class="edit" href="#">DVT008517</a>
								</td>
								<td>
									<a class="edit" href="#">KVT009951</a>
								</td>
								<td>IT0000001</td>
								<td>Polyess</td>
								<td></td>
								<td></td>
								<td>
									<input class="form-control" style="width: 100px; height: 30px;" placeholder="0.0001">
								</td>
								<td>
									<select name="origin" id="origin" class="form-control" style="width: 70px; height: 30px;">
										<option value=""></option>
										<?php foreach ($origin_list as $origin): ?>
										<option value="<?php echo $origin['kubun']; ?>" <?php if (set_value( 'origin') == $origin[ 'kubun']) : ?>
											selected
											<?php endif ?> >
											<?php echo $origin['komoku_name_2']; ?>
										</option>
										<?php endforeach?>
									</select>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

<!-- Error Modal -->
<div id="info_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="info_message">
					
				</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary antoclose2" data-dismiss="modal">
					<?php echo $this->lang->line('close'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

<!-- Modal -->
<!-- CONT Print modal -->
<div id="cont_print_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="min-width: 80%;">
		<div class="modal-content">
			<div class="modal-header">
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<button class="btn btn-info" data-dismiss="modal"><i class="fa fa-arrow-left"></i><?php echo $this->lang->line('back'); ?></button>
					</li>
					<li>
						<button class="btn btn-primary" id="btn_cont_print"><i class="fa fa-print"></i><?php echo $this->lang->line('print'); ?></button>
					</li>
				</ul>
				<h2>
					<?php echo $this->lang->line('contract_print');?>
				</h2>
			</div>
      <form class="form-horizontal form-label-left" id="frm_cont_print" method="post" action="">
				<!-- <input type="hidden" id="type" name="type"/> -->
				<input type="hidden" id="dvt_key" name="dvt_key">
				<input type="hidden" id="pack_no" name="pack_no">
				<input type="hidden" id="reference" name="reference">
				<input type="hidden" id="contract_from_date" name="contract_from_date">
				<input type="hidden" id="contract_end_date" name="contract_end_date">
				<input type="hidden" id="delivery_data" name="delivery_data"/>
        <div class="modal-footer">
					<div class="no-padding-left no-padding-right">
              <div class="form-group">
                  <label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="output_select"><?php echo $this->lang->line('output_select');?><span class="required">*</span></label>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding-right" style="text-align: left;">
										<p style="margin:0px; padding-top: 5px">
											<label><input type="radio" class="flat" name="output_select" id="output_select1" value="1" checked/> <?php echo $this->lang->line('eachtime');?></label>
										</p>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3 no-padding-right" style="text-align: left;">
										<p style="margin:0px; padding-top: 5px">
											<label><input type="radio" class="flat" name="output_select" id="output_select3" value="3"/> <?php echo $this->lang->line('sales_contract');?></label>
										</p>
								</div>
								<div class="col-md-3 col-sm-3 col-xs-3 no-padding-right" style="text-align: left;">
										<p style="margin:0px; padding-top: 5px">
											<label><input type="radio" class="flat" name="output_select" id="output_select2" value="2"/> <?php echo $this->lang->line('principal_contract');?></label>
										</p>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding-right" style="text-align: left;">
										<p style="margin:0px; padding-top: 5px">
											<label><input type="radio" class="flat" name="output_select" id="output_select4" value="4"/> <?php echo $this->lang->line('agreement');?></label>
										</p>
								</div>
        	</div>
				</div>
				<div id="agreement_eachtime" class="x_panel no-padding-left no-padding-right" style="margin-bottom: 0px;">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="contract_no_eachtime">
								<?php echo $this->lang->line('contract_no_eachtime');?><span class="required">*</span>
							</label>
							<div class="col-md-6">
								<input type="hidden" id="eachtime_no_old" name="eachtime_no_old">
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-left no-padding-right">
									<input type="text" style="text-align: center;" class="form-control text-uppercase" id="eachtime_no" name="eachtime_no" maxlength="3">
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center; padding-left: 0; padding-right: 0;" id="eachtime_1" name="eachtime_1" value="HDTL" readonly>
								</div>
								<div class="col-md-1 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="eachtime_2" name="eachtime_2" value="E" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control no-padding-left no-padding-right" style="text-align: center;" id="eachtime_3" name="eachtime_3" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input type="text" class="form-control" style="text-align: center;" id="eachtime_4" name="eachtime_4" readonly>
								</div>
								<div class="col-md-1 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="eachtime_5" name="eachtime_5" value="<?php echo (substr(date("Y"), -2));?>" readonly>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding-right" style="padding-left: 2px;">
									<input class="form-control text-uppercase" style="text-align: center;" id="eachtime_6" name="eachtime_6" value="" maxlength="10">
								</div>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="scan_sign_ae">
										<?php echo $this->lang->line('scan_sign');?>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2" style="text-align: left;">
								<p style="padding-top: 5px;">
									<input
										value="scan_sign_ae"
										type="checkbox"
										class="flat"
										name="scan_sign_ae"
										id="scan_sign_ae">
								</p>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="contract_date_ae">
								<?php echo $this->lang->line('contract_date');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input class="form-control date" id="contract_date_ae" name="contract_date_ae">
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="delivery_date">
								<?php echo $this->lang->line('delivery_date');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input class="form-control date" id="delivery_date" name="delivery_date">
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="data_currency">
								<?php echo $this->lang->line('data_currency');?>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input type="text" class="form-control" id="data_currency_eachtime" name="data_currency_eachtime" readonly>
							</div>
						</div>

						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="payment_methods_eachtime">
								<?php echo $this->lang->line('payment_methods');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2 has-clear has-feedback">
								<input type="text" name="payment_methods_eachtime" id="payment_methods_eachtime" class="form-control"/>
								<input type="hidden" name="payment_methods_eachtime_vn" id="payment_methods_eachtime_vn"/>
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-right">
								<?php echo $this->lang->line('tax');?></label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<select class="form-control" id="tax_eachtime" name="tax_eachtime">
									<option value="001">VAT 10%</option>
									<option value="002">VAT 0%</option>
								</select>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="output_currency">
								<?php echo $this->lang->line('output_currency');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<select name="output_currency_eachtime" id="output_currency_eachtime" class="form-control">
									<?php foreach ($currency_list as $currency): ?>
									<option value="<?php echo $currency['currency_name']; ?>"><?php echo $currency['currency_name']; ?></option>
									<?php endforeach?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="payment_term_eachtime">
								<?php echo $this->lang->line('payment_term');?><span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-6 has-clear has-feedback">
								<input type="text" name="payment_term_eachtime" id="payment_term_eachtime" class="form-control"/>
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="rate_eachtime">
								<?php echo $this->lang->line('rate_out');?> USD→VND
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input onkeydown="return checkQuantity(event)" type="text" class="form-control" id="rate_eachtime" name="rate_eachtime"/>
							</div>
						</div>

						<div class="form-group" style="margin-bottom: 0;">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" style="padding-right: 5px;" for="delivery_to">
								<?php echo $this->lang->line('delivery_place');?><span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<textarea spellcheck="false" class="form-control" rows="3" id="delivery_to" name="delivery_to" readonly></textarea>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-4">
								<div class="form-group" style="margin-bottom: 10px;">
									<label class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right" for="rate_eachtime_jpy">
										<?php echo $this->lang->line('rate_out');?> JPY→VND
									</label>
									<div class="col-md-6 col-sm-6 col-xs-6 no-padding-right has-clear has-feedback">
										<input onkeydown="return checkQuantity(event)" type="text" name="rate_eachtime_jpy" id="rate_eachtime_jpy" class="form-control"/>
									</div>
								</div>
								<div class="form-group" style="margin-bottom: 10px;">
									<label class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right" for="rate_eachtime_jpy_usd">
										<?php echo $this->lang->line('rate_out');?> JPY→USD
									</label>
									<div class="col-md-6 col-sm-6 col-xs-6 no-padding-right has-clear has-feedback">
										<input onkeydown="return checkQuantity(event)" type="text" name="rate_eachtime_jpy_usd" id="rate_eachtime_jpy_usd" class="form-control"/>
									</div>
								</div>
								<div class="form-group" style="margin-bottom: 0px;">
									<label class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right" for="delivery_condition">
										<?php echo $this->lang->line('delivery_condition');?><span class="required">*</span>
									</label>
									<div class="col-md-6 col-sm-6 col-xs-6 no-padding-right has-clear has-feedback">
										<input type="text" name="delivery_condition" id="delivery_condition" class="form-control"/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: -5px !important"></span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="agreement" class="x_panel no-padding-left no-padding-right" style="display: none; margin-bottom: 0px;">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="contract_no_agreement">
								<?php echo $this->lang->line('contract_no_agreement');?><span class="required">*</span>
							</label>
							<div class="col-md-4">
								<input type="hidden" id="agreement_no_old" name="agreement_no_old">
								<div class="col-md-3 col-sm-1 col-xs-1 no-padding-left no-padding-right">
									<input type="text" class="form-control text-uppercase" style="text-align: center;" id="agreement_contract_no" name="agreement_contract_no" maxlength="3">
								</div>
								<div class="col-md-3 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="agreement_1" name="agreement_1" value="AG" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="agreement_2" name="agreement_2" value="E" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input type="text" class="form-control" style="text-align: center;" id="agreement_3" name="agreement_3" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="agreement_4" name="agreement_4" value="<?php echo (substr(date("Y"), -2));?>" readonly>
								</div>
							</div>
						</div>
						<div class="form-group" style="margin-bottom: 0;">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="contract_date_ae_agreement">
									<?php echo $this->lang->line('contract_date');?><span class="required">*</span>
								</label>
								<div class="col-md-2 col-sm-2 col-xs-2">
									<input class="form-control date" id="contract_date_ae_agreement" name="contract_date_ae_agreement">
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="end_date_ae">
									<?php echo $this->lang->line('end_day');?><span class="required">*</span>
								</label>
								<div class="col-md-2 col-sm-2 col-xs-2">
									<input class="form-control date" id="end_date_ae" name="end_date_ae">
								</div>
						</div>
					</div>
					<div id="principal_contract" class="x_panel no-padding-left no-padding-right" style="display: none; margin-bottom: 0px;">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="contract_no_principal">
								<?php echo $this->lang->line('contract_no_principal');?><span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-6">
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-left no-padding-right">
									<input type="text" style="text-align: center;" class="form-control text-uppercase" id="principal_contract_no" name="principal_contract_no" maxlength="3">
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="principal_1" name="principal_1" value="HDNT" readonly>
								</div>
								<div class="col-md-1 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="principal_2" name="principal_2" value="E" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input type="text" class="form-control" style="text-align: center;" id="principal_3" name="principal_3" readonly>
								</div>
								<div class="col-md-1 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="principal_4" name="principal_4" value="<?php echo (substr(date("Y"), -2));?>" readonly>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding-right" style="padding-left: 2px;">
									<input class="form-control text-uppercase" style="text-align: center;" id="principal_5" name="principal_5" value="" maxlength="10">
								</div>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="scan_sign">
								<?php echo $this->lang->line('scan_sign');?>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2" style="text-align: left;">
								<p style="padding-top: 5px;">
									<input
										value="1"
										type="checkbox"
										class="flat"
										name="scan_sign"
										id="scan_sign">
								</p>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="contract_date_principal">
								<?php echo $this->lang->line('contract_date');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input class="form-control date" id="contract_date_principal" name="contract_date_principal">
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="end_date_principal">
								<?php echo $this->lang->line('end_day');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input class="form-control date" id="end_date_principal" name="end_date_principal">
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="party_charged_principal">
								<?php echo $this->lang->line('party_charged');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
              	<select class="form-control" id="party_charged_principal" name="party_charged_principal">
										<option></option>
										<option value="Bên A"><?php echo $this->lang->line('party_a');?></option>
										<option value="Bên B"><?php echo $this->lang->line('party_b');?></option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="payment_methods_principal">
								<?php echo $this->lang->line('payment_methods');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2 has-feedback has-clear">
								<input type="text" class="form-control" id="payment_methods_principal" name="payment_methods_principal"/>
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback"></span>
							</div>
							<label class="control-label col-md-1 col-sm-2 col-xs-2 no-padding-right">
								<?php echo $this->lang->line('tax');?></label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<select class="form-control" id="tax_principal" name="tax_principal">
									<option value="001">VAT 10%</option>
									<option value="002">VAT 0%</option>
								</select>
							</div>
							<div class="form-group col-md-5 col-sm-4 col-xs-4 no-padding-left">
								<label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-right" for="terms_overdue">
									<?php echo $this->lang->line('terms_overdue');?>
								</label>
								<div class="col-md-1 col-sm-1 col-xs-1" style="text-align: left;">
									<p style="padding-top: 5px;">
										<input 
											value="1"
											type="checkbox"
											class="flat"
											name="terms_overdue"
											id="terms_overdue">
									</p>
								</div>
								<label class="control-label col-md-5 col-sm-2 col-xs-2 no-padding-left no-padding-right" style="color:red;" for="sign">
								<?php echo $this->lang->line('sign');?>
								</label>
								<div class="col-md-1 col-sm-2 col-xs-2" style="text-align: left;">
									<p style="padding-top: 5px;">
										<input 
											value="1"
											type="checkbox"
											class="flat"
											name="sign"
											id="sign">
									</p>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="payment_term_principal">
								<?php echo $this->lang->line('payment_term');?><span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-6 has-clear has-feedback">
								<input type="text" class="form-control" id="payment_term_principal" name="payment_term_principal"/>
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback"></span>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="payment_currency">
								<?php echo $this->lang->line('payment_currency');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
                <select class="form-control" id="payment_currency" name="payment_currency">
									<option></option>
										<?php foreach ($currency_list as $currency): ?>
                  		<option value="<?php echo $currency['currency_name']; ?>"><?php echo $currency['currency_name']; ?></option>
                    <?php endforeach?>
								</select>
							</div>
						</div>
						<div class="form-group" style="margin-bottom: 0;">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="fee_terms_principal">
								<?php echo $this->lang->line('fee_terms');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<select class="form-control" id="fee_terms_principal" name="fee_terms_principal">
									<option></option>
									<?php foreach ($fee_terms_list as $fee_terms): ?>
										<option value="<?php echo $fee_terms['kubun']; ?>"><?php echo $fee_terms['komoku_name_2']; ?></option>
									<?php endforeach?>
								</select>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="feedback_within">
								<?php echo $this->lang->line('feedback_within');?><span class="required">*</span>
							</label>
							<div class="col-md-1 col-sm-1 col-xs-1">
								<input type="number" save="" min="1" oninput="value = (this.value.includes('-')) ? value = this.save: this.save = value" class="form-control" id="feedback_within" name="feedback_within" />
							</div>
							<label class="control-label col-md-1 col-sm-1 col-xs-1" style="text-align:left">
								<?php echo $this->lang->line('days');?>
							</label>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="delivery_condition_principal">
								<?php echo $this->lang->line('delivery_condition');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2 has-clear has-feedback">
								<input type="text" class="form-control" id="delivery_condition_principal" name="delivery_condition_principal">
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback"></span>
							</div>
						</div>
					</div>
					<div id="sales_contract" class="x_panel no-padding-left no-padding-right" style="display: none; margin-bottom: 0px;">
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="contract_no">
								<?php echo $this->lang->line('contract_no');?><span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-7 col-xs-7">
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-left no-padding-right">
									<input type="text" class="form-control text-uppercase" style="text-align: center;" id="contract_no" name="contract_no" maxlength="3">
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="contract_no_1" name="contract_no_1" value="HDT" readonly>
								</div>
								<div class="col-md-1 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="contract_no_2" name="contract_no_2" value="E" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input type="text" class="form-control no-padding-left no-padding-right" style="text-align: center;" id="contract_no_3" name="contract_no_3" maxlength="3" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="contract_no_4" name="contract_no_4" readonly>
								</div>
								<div class="col-md-1 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control" style="text-align: center;" id="contract_no_5" name="contract_no_5" value="<?php echo (substr(date("Y"), -2));?>" readonly>
								</div>
								<div class="col-md-2 col-sm-1 col-xs-1 no-padding-right" style="padding-left: 2px;">
									<input class="form-control text-uppercase" style="text-align: center;" id="contract_no_6" name="contract_no_6" maxlength="10" value="">
								</div>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="sign_required">
								<?php echo $this->lang->line('sign_required');?>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2" style="text-align: left;">
								<p style="padding-top: 5px;">
									<input 
										value="1"
										type="checkbox"
										class="flat"
										name="sign_required"
										id="sign_required">
								</p>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="contract_date">
								<?php echo $this->lang->line('contract_date');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input class="form-control date" id="contract_date" name="contract_date">
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="party_charged">
								<?php echo $this->lang->line('party_charged');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
                <select class="form-control" id="party_charged" name="party_charged">
									<option></option>
									<option value="A"><?php echo $this->lang->line('party_a');?></option>
									<option value="B"><?php echo $this->lang->line('party_b');?></option>
								</select>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="sales_currency">
								<?php echo $this->lang->line('data_currency');?>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input type="text" class="form-control" id="sales_currency" name="sales_currency" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="payment_methods">
								<?php echo $this->lang->line('payment_methods');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2 has-clear has-feedback">
								<input type="text" class="form-control" id="payment_methods" name="payment_methods">
								<input type="hidden" class="form-control" id="payment_methods_vn" name="payment_methods_vn">
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1"></div>
							<label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-right">
								<?php echo $this->lang->line('tax');?></label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<select class="form-control" id="tax_sales" name="tax_sales">
									<option value="001">VAT 10%</option>
									<option value="002">VAT 0%</option>
								</select>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="sales_output_currency">
								<?php echo $this->lang->line('output_currency');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<select name="sales_output_currency" id="sales_output_currency" class="form-control">
									<option value=""></option>
									<?php foreach ($currency_list as $currency): ?>
										<option value="<?php echo $currency['currency_name']; ?>"><?php echo $currency['currency_name']; ?></option>
									<?php endforeach?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="payment_term">
								<?php echo $this->lang->line('payment_term');?><span class="required">*</span>
							</label>
							<div class="col-md-6 col-sm-6 col-xs-6 has-clear has-feedback">
								<input type="test" class="form-control" id="payment_term" name="payment_term"/>
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback"></span>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="sales_rate">
								<?php echo $this->lang->line('rate_out');?> USD→VND
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input type="text" class="form-control" id="sales_rate" name="sales_rate" onkeydown="return checkQuantity(event)">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="fee_terms">
								<?php echo $this->lang->line('fee_terms');?><span class="required">*</span>
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<select class="form-control" id="fee_terms" name="fee_terms">
									<option></option>
									<?php foreach ($fee_terms_list as $fee_terms): ?>
                    <option value="<?php echo $fee_terms['kubun']; ?>"><?php echo $fee_terms['komoku_name_2']; ?></option>
                  <?php endforeach?>
								</select>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="quantity_odds">
								<?php echo $this->lang->line('quantity_odds');?><span class="required">*</span>
							</label>
							<div class="col-md-1 col-sm-1 col-xs-1">
								<input type="number" save="" min="1" oninput="value = (this.value.includes('-')) ? value = this.save: this.save = value" class="form-control" id="quantity_odds" name="quantity_odds" />
							</div>
							<label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left" style="text-align:left"> % </label>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="sales_rate_jpy">
								<?php echo $this->lang->line('rate_out');?> JPY→VND
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input type="text" class="form-control" id="sales_rate_jpy" name="sales_rate_jpy" onkeydown="return checkQuantity(event)">
							</div>
						</div>
						<div class="form-group" style="margin-bottom: 0;">
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="po_dvt">
								<?php echo $this->lang->line('po_dvt');?>PO/DVT List</span>
							</label>
							<div class="col-md-5 col-sm-6 col-xs-6 has-clear has-feedback">
								<input type="text" class="form-control" id="po_dvt" name="po_dvt"/>
								<input type="hidden" id="po_dvt_selected" name="po_dvt_selected"/>
								<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback"></span>
							</div>
							<div class="col-md-1 col-sm-1 col-xs-1" style="padding-top: 5px; text-align: left;">
								<button type="button" class="btn btn-xs btn-success" style="margin:0px"  data-toggle ="modal" data-target="#PODVT_modal">
									<span class="fa fa-plus item-list"></span>
								</button>
							</div>
							<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="sales_rate_jpy_usd">
								<?php echo $this->lang->line('rate_out');?> JPY→USD
							</label>
							<div class="col-md-2 col-sm-2 col-xs-2">
								<input type="text" class="form-control" id="sales_rate_jpy_usd" name="sales_rate_jpy_usd" onkeydown="return checkQuantity(event)">
							</div>
						</div>
					</div>
						<div class="x_panel" style="padding-top: 0; padding-bottom: 0; margin-bottom: 0;">
								<div class="x_content">
										<div class="" role="tabpanel" data-example-id="togglable-tabs" id="tabs">
												<ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
														<li role="presentation" class="active">
																<a href="#tab_content1" id="party_a" role="tab" data-toggle="tab" aria-expanded="true" style="padding-top: 6px; padding-bottom: 6px; color:#e43434"><?php echo $this->lang->line('party_a');?></a>
														</li>
														<li role="presentation" class="">
																<a href="#tab_content2" role="tab" id="party_b" data-toggle="tab" aria-expanded="false" style="padding-top: 6px; padding-bottom: 6px;"><?php echo $this->lang->line('party_b');?></a>
														</li>
														<li role="presentation" class="">
																<a href="#tab_content3" role="tab" id="consignee" data-toggle="tab" aria-expanded="false" style="padding-top: 6px; padding-bottom: 6px; color:#e43434"><?php echo $this->lang->line('consignee');?></a>
														</li>
														<li role="presentation" class="">
																<a href="#tab_content4" role="tab" id="notify" data-toggle="tab" aria-expanded="false" style="padding-top: 6px; padding-bottom: 6px;"><?php echo $this->lang->line('notify');?></a>
														</li>
														<a class="edit" href="#" style="font-size: 15px;"><?php echo $this->lang->line('contract_template_file');?></a>
												</ul>
												<div id="myTabContent" class="tab-content">
														<div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
															<div class="form-group">
																	<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="party_a">
                                      <?php echo $this->lang->line('party_a');?> <span class="required">*</span>
                                  </label>
																	<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left no-padding-right">
																		<select class="form-control" id="party_a" name="party_a">
																			<option></option>
																			<?php foreach ($party_a_list as $party_a): ?>
																				 <option field="<?php 
																														 $info = $party_a['komoku_name_3'];
																														 $info = str_replace("MUA", "BÁN", $info);
																														 $info = str_replace("BUYER", "SELLER", $info);
																													 echo $info;
																												?>" value="<?php echo $party_a['kubun']; ?>"><?php echo $party_a['komoku_name_2']; ?></option>
                                      <?php endforeach?>
																		</select>
																	</div>
															</div>
														<div class="form-group">
															<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="party_a_info"><?php echo $this->lang->line('party_a_info');?></label>
															<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left no-padding-right">
																<textarea spellcheck="false" class="form-control" rows="5" id="party_a_info" name="party_a_info" readonly></textarea>
															</div>
														</div>
                 	          <div class="form-group bank-group">
															<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="bank_name">
																<?php echo $this->lang->line('bank');?><span class="required">*</span>
															</label>
															<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left no-padding-right">
																<select class="form-control" id="bank_name" name="bank_name">
																	<option></option>
																	<?php foreach ($bank_list as $bank): ?>
																		<option field="<?php echo $bank['komoku_name_3']?>"	value="<?php echo $bank['kubun']; ?>"><?php echo $bank['komoku_name_2']; ?></option>
																	<?php endforeach?>
																</select>
															</div>
														</div>
														<div class="form-group bank-group">
															<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="bank_info">
																<?php echo $this->lang->line('bank_info');?>
															</label>
															<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left no-padding-right">
																<textarea spellcheck="false" class="form-control" rows="3" id="bank_info" name="bank_info" readonly></textarea>
															</div>
														</div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="tab_content2" aria-labelledby="profile-tab">
											<div class="form-group">
												<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="party_b">
                          <?php echo $this->lang->line('party_b');?><span class="required">*</span>
                        </label>
											<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left has-clear has-feedback">
												<input type="hidden" value="" name="company_name_b" id="company_name_b">
												<input type="hidden" value="" name="tel_b" id="tel_b">
												<input type="hidden" value="" name="fax_b" id="fax_b">
												<input type="hidden" value="" name="address_b" id="address_b">
												<input type="hidden" value="" name="represented_by_b" id="represented_by_b">
												<input type="hidden" value="" name="position_b" id="position_b">
												<input type="text" class="form-control" id="party_b" name="party_b"/>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="party_b_info"><?php echo $this->lang->line('party_b_info');?></label>
											<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left">
												<textarea spellcheck="false" class="form-control" rows="5" id="party_b_info" name="party_b_info" readonly></textarea>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left agreement_info">
												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left"><?php echo $this->lang->line('agreement_no');?>Agreement No : </label>
													<label id="agreement_no_label" class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left"></label>
												</div>
												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left"><?php echo $this->lang->line('contract_date');?> : </label>
													<label id="contract_date_label" class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left"></label>
												</div>
												<div class="form-group">
													<label class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left"><?php echo $this->lang->line('end_day');?> : </label>
													<label id="end_day_label" class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left"></label>
												</div>
											</div>
										</div>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content3" aria-labelledby="profile-tab">
										<div class="form-group">
											<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="consignee">
													<?php echo $this->lang->line('consignee');?><span class="required">*</span>
											</label>
											<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left has-clear has-feedback">
												<input type="text" class="form-control" id="consignee" name="consignee"/>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
											</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="consignee_info"><?php echo $this->lang->line('consignee_info');?></label>
											<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left">
											<textarea spellcheck="false" class="form-control" rows="5" id="consignee_info" name="consignee_info" readonly></textarea>
											<input type="hidden" id="consignee_info_vn" name="consignee_info_vn"/>
											</div>
										</div>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="tab_content4" aria-labelledby="profile-tab">
										<div class="form-group">
											<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="notify">
													<?php echo $this->lang->line('notify');?>
											</label>
										<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left has-clear has-feedback">
											<input type="text" class="form-control" id="notify" name="notify"/>
											<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
											<!-- <select class="form-control" id="notify" name="notify">
												<option></option>
											</select> -->
										</div>
										</div>
										<div class="form-group">
											<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left" for="notify_info"><?php echo $this->lang->line('notify_info');?></label>
											<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left">
												<textarea spellcheck="false" class="form-control" rows="5" id="notify_info" name="notify_info" readonly></textarea>
										</div>
									</div>
								</div>
						</div>
				</div>
		</div>
	</div>
</div>
            </form>
		</div>
	</div>
</div>
<div id="PODVT_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width: 70%">
		<div class="modal-content col-sm-12 col-xs-12 col-md-12">
			<div class="modal-header">
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-info" data-dismiss="modal">
                            <i class="fa fa-arrow-left"></i>
                            <?php echo $this->lang->line('back'); ?>
                        </button>
                        <button class="btn btn-default" id="dvt_po_select" disabled>
                            <?php echo $this->lang->line('select'); ?>
                        </button>
                    </li>
                </ul>
                <h2>
                    <?php echo $this->lang->line('po_dvt_list'); ?>
                </h2>
			</div>
			<div class="modal-body col-sm-12 col-xs-12 col-md-12">
					<div class="col-sm-12">
					</div>

					<div class="table-responsive col-sm-12 col-xs-12 col-md-12 dragscroll">
							<table style="cursor:move;" id="PODVTTable" data-modal="#PODVT_modal" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
									<thead>
											<tr>
													<th><?php echo $this->lang->line('delivery_no');?></th>
													<th><?php echo $this->lang->line('times');?></th>
													<th>K.No</th>
													<th><?php echo $this->lang->line('order_date');?></th>
													<th><?php echo $this->lang->line('status');?></th>
													<th><?php echo $this->lang->line('action');?></th>
											</tr>
									</thead>
							</table>
					</div>
			</div>
		</div>
	</div>
</div>
<script>
	var INVPLDatatable;
	var tableData = [];
	var defaultValue = null;
	var dataHeader = (<?php echo json_encode($branchInfo) ?> || []);
	var dataConsignee = (<?php echo json_encode($consigneeto) ?> || []);
	var dataPartyB = (<?php echo json_encode($party_b_list) ?> || []);
	var deliveryCondition = (<?php echo json_encode($delivery_condition_list) ?> || []);
	var paymentTermEachTime = (<?php echo json_encode($payment_term_eachtime) ?> || []);
	var payment_method_list = (<?php echo json_encode($payment_method_list) ?> || []);
	var listPartyB = (<?php echo json_encode($list_partyb_consignee) ?> || []);
	var payment_by_list = (<?php echo json_encode($payment_by_list) ?> || []);
	var listNotify;
	var listChecked = [];
	var listShipping = (<?php echo json_encode($shipping_method_list) ?> || []);
	var data_datatable = (<?php echo json_encode($data_datatable) ?> || []);
	// console.log(deliveryCondition);
	// var dataCustomer = (<?php echo json_encode($customerList); ?> || []);
	var dataFilterHeader = [];
	var dataFilterCustomer = [];
	var dataFilterConsignee = [];
	var dataFilterShousha = [];
	var currentPageIndex = 0;
	var currentKubun = 1;
	var PODVTTable = null;
	function unique(value, index, self){
		return self.indexOf(value) === index;
	}
	window.onload = function() {
		PODVTTable = $("#PODVTTable").DataTable({
        "paging": true,
        "filter": true,
        "ordering": true,
				"drawCallback": function() {
					// iCheck
					$("#PODVTTable input.choose").iCheck({
							checkboxClass: "icheckbox_flat-green"
					});
				},
        "columns": [
                { "data": "dvt_no", "class": 'text-left' },
                { "data": "times", render: function( data, type, row, meta ){
                    if(row.count > 1 || row.times > 1){
                        return row.times;
                    }
                    return '';
                }},
                { "data": "kvt_no", "class": 'text-left' },
								{ "data": "order_date"},
								{ "data": "status"},
                { "data": "action", render: function( data, type, row, meta ){
									var isCheck = "";
									var isDisable = "";
									if(row.checked){
										isCheck = " checked ";
									}
									if(row.disabled){
										isDisable = " disabled ";
									}
									var $el = '<input type="checkbox" class="choose" ' + isCheck + isDisable + '/>';
									return $el;
                }}
            ],
        "ajax":{
                url : "<?php echo base_url("inv_pl_print/getPODVT") ?>",
								type : 'post',
                data:function(data){
                    data.kubun = currentKubun;
										data.delivery_data = $("#delivery_data").val();
										data.po_dvt_selected = $("#po_dvt_selected").val();
                }
								
        }
		});
		$("#PODVT_modal").on('shown.bs.modal', function(){
			if(PODVTTable){
				PODVTTable.ajax.reload();
			}
		});
		$("#PODVTTable").on("ifChanged", ".choose", function(event){
			$("#dvt_po_select").attr("disabled", false).removeClass("btn-default").addClass("btn-primary");
			var $row = $(this).parents('tr');
			var rowData = PODVTTable.row($row).data();
			rowData.checked = $(this)[0].checked;
		});
		$("#dvt_po_select").on('click', function(){
			var dtChoose = PODVTTable.rows().data();
			var packed_array = [];
			var chooseList = dtChoose.filter(function(el) {
				// if packed, disable = true
				if(el.disabled){
					packed_array.push(el.dvt_no + "-" + el.times);
				}
      	return el.checked && !el.disabled;
			});
			if(chooseList.length > 0) {
				var print_array = [];
				var dvtList = chooseList.map(function(choose) {
					print_array.push({ 
						delivery_no : choose.dvt_no,
          	order_date : choose.order_date,
            times : choose.times
					});
					return choose.dvt_no + "-" + choose.times;
				});
				$("#po_dvt_selected").val(JSON.stringify(print_array));
				$("#po_dvt").val(packed_array.concat(dvtList.toArray()).join());
			} else{
				$("#po_dvt").val("");
				$("#po_dvt_selected").val("");
			}
			$("#PODVT_modal").modal('hide');
		});
		$('input[name="scan_sign_ae"]').on('ifChecked', function (event) {
				$(".agreement_info").show();
		});
		$('input[name="scan_sign_ae"]').on('ifUnchecked', function (event) {
			$(".agreement_info").hide();
		});
		
		$("#payment_by_name").autocomplete({
			lookup: Object.keys(payment_by_list).map(function(key){
				return {value: payment_by_list[key]["komoku_name_3"]};
			}).concat(Object.keys(paymentTermEachTime).map(function(key){
				return {value: paymentTermEachTime[key]["komoku_name_3"]};
			})),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});

		$("#payment_methods_principal").autocomplete({
			lookup: Object.keys(payment_method_list).map(function(key){
				return {value: payment_method_list[key]["komoku_name_3"]};
			}),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		$("#payment_methods_eachtime").autocomplete({
			lookup: Object.keys(payment_method_list).map(function(key){
				return {value: payment_method_list[key]["komoku_name_2"],
								value_vn: payment_method_list[key]["komoku_name_3"]
							};
			}),
			minChars: 0,
			onSelect: function(data){
				$(this).change();
				$("#payment_methods_eachtime_vn").val(data.value_vn);
			}
		});
		$("#payment_methods").autocomplete({
			lookup: Object.keys(payment_method_list).map(function(key){
				return {
								value: payment_method_list[key]["komoku_name_2"],
								value_vn: payment_method_list[key]["komoku_name_3"]
								};
			}),
			minChars: 0,
			onSelect: function(data){
				$(this).change();
				$("#payment_methods_vn").val(data.value_vn);
			}
		});

		$("#agreement_eachtime #payment_term_eachtime").autocomplete({
			lookup: Object.keys(paymentTermEachTime).map(function(key){
				return {value: paymentTermEachTime[key]["komoku_name_3"]};
			}),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		$("#sales_contract #payment_term").autocomplete({
			lookup: Object.keys(paymentTermEachTime).map(function(key){
				return {value: paymentTermEachTime[key]["komoku_name_3"]};
			}),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		$("#principal_contract #payment_term_principal").autocomplete({
			lookup: Object.keys(paymentTermEachTime).map(function(key){
				return {value: paymentTermEachTime[key]["komoku_name_3"]};
			}),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		$("#vessel_flight").autocomplete({
			lookup: Object.keys(listShipping).map(function(key){
				return {value: listShipping[key]["komoku_name_2"]};
			}),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});

		$("#delivery_condition").autocomplete({
			lookup: Object.keys(deliveryCondition).map(function(key){
				return {value: deliveryCondition[key]["komoku_name_2"]};
			}),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		$("#delivery_condition_principal").autocomplete({
			lookup: Object.keys(deliveryCondition).map(function(key){
				return {value: deliveryCondition[key]["komoku_name_2"]};
			}),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		$("#inv_delivery_condition").autocomplete({
			lookup: Object.keys(deliveryCondition).map(function(key){
				return {value: deliveryCondition[key]["komoku_name_2"]};
			}),
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		// Set autocomplete for header
		dataHeader.forEach(function(element){
			dataFilterHeader[dataFilterHeader.length] = {
				value: element['komoku_name_2'] || "",
				data: element['komoku_name_3'] || ""
				// data: element['komoku_name_3']?element['komoku_name_3']:"" + "\n" + element['note1']?element['note1']:""
			}
		});
		$("#header_name").autocomplete({
			lookup: dataFilterHeader,
			minChars: 0,
			onSelect: function (header){
				$(this).change();
				$("#header_address").val(header.data).change();
			}
		});
		//Set autocomplete consignee
		Object.keys(dataConsignee).forEach(function(key){
			dataFilterConsignee[dataFilterConsignee.length] = {
				value: dataConsignee[key]["name"],
				data: dataConsignee[key],
			}
		});
		$("#consigned_name").autocomplete({
			lookup: dataFilterConsignee,
			minChars: 0,
			onSelect: function (data){
				$(this).change();
				$("#buyer").val("").change();
				$("#buyer_address").val("");
				$("#consigned_to").val("");
				$("#consigned_to").val(data.data.address);
				$("#notify").val("").change();
				$("#notify_address").val("");
				$("#inv_delivery_condition").val(data.data.delivery_condition);
				dataFilterShousha = [];
				dataFilterCustomer = [];
				Object.keys(data.data.shousha).forEach(function(key){
					if(data.data.shousha[key]["name"]){
						dataFilterShousha[dataFilterShousha.length] = {
							value: data.data.shousha[key]["name"],
							data: data.data.shousha[key]["address"],
						}
					}
				});
				Object.keys(data.data.customer).forEach(function(key){
					if(data.data.customer[key]["name"]){
						dataFilterCustomer[dataFilterCustomer.length] = {
							value: data.data.customer[key]["name"],
							data: data.data.customer[key]
							
						}
					}
				});
				
				$("#buyer").autocomplete({
					lookup: dataFilterCustomer,
					minChars: 0,
					onSelect: function (data){
						$(this).change();
						$("#buyer_address").val(data.data.address);
						$("#payment_by_name").val(data.data.payment_term);
					}
				})
				$("#notify").autocomplete({
					lookup: dataFilterShousha,
					minChars: 0,
					onSelect: function (data){
						$(this).change();
						$("#notify_address").val("");
						$("#notify_address").val(data.data);
					}
				})
			}
		});

		$("#INVPLForm").validationEngine();
		$("#frm_cont_print").validationEngine({validateNonVisibleFields: true, updatePromptsPosition:true});
		$('input[name="output_select"]').on('ifChecked', function (event){
			// Hide all popup validate engine
			$('#frm_cont_print').validationEngine('hideAll');
			// set active for first tabs
			$('#tabs a:first').tab('show');
			
			$(".autocomplete-suggestions").hide()		// hide autocomplete suggestion
			$('#principal_contract').hide();        // hide Principal Contract
			$('#agreement_eachtime').hide();        // hide Agreement and Eachtime contract
			$('#sales_contract').hide();            // hide Sum Sales Contract
			$('#agreement').hide();            			// hide agreement Contract
			$('#myTab #notify').show();             // show Notify tab
			$('#myTab #consignee').show();          // show consignee tab
			
			// If checked Agreement and Eachtime Contract
			if($(this).val() == '1') {
				$(".bank-group").show();
				load_agreement_eachtime(tableData);
				$('#agreement_eachtime').show();
			}

			// If checked Principal Contract
			if($(this).val() == '2') {
					$('#myTab #notify').hide();
					$('#myTab #consignee').hide();
					$(".bank-group").show();
					load_principal(tableData);
					$('#principal_contract').show();
			}

			// If checked Sum Sales Contract
			if($(this).val() == '3') {
					$('#myTab #notify').hide();
					$('#myTab #consignee').hide();
					$(".bank-group").show();
					load_sales_contract(tableData);
					$('#sales_contract').show();
			}
			// If checked Sum Sales Contract
			if($(this).val() == '4') {
					$('#myTab #notify').hide();
					$('#myTab #consignee').hide();
					$(".bank-group").hide();
					load_agreement(tableData);
					$('#agreement').show();
			}
		});
		$('#contract_no_sale').on('input',function(){
			if($(this).val().substring(0, 12) != "SMDVN-SMDJP-"){
				$(this).value = "SMDVN-SMDJP-";
			}
		});
		$('select#header_name').change(function(){
				var $option = $('select#header_name option:selected');
				if ($option.length > 0) {
					var payment = $option.data('name');
					var telfax = $option.data('telfax');
					$("#header_address").val(payment + "\n" + telfax);
				}
		});
		$('select#customer').change(function(){
			var $option = $('select#customer option:selected');
			if ($option.length > 0 && $option.val()) {
				var branchOffice = $option.data('branch');
				var header_address = '';
				if(branchOffice && branchOffice['branch_name']){
					header_address = branchOffice.branch_address + '\n'
					+ (branchOffice.branch_tel ? 'TEL:' + branchOffice.branch_tel + ' ': "")
					+ (branchOffice.branch_fax ? 'FAX:' + branchOffice.branch_fax + ' ': "");
				}else if(branchOffice){
					header_address = branchOffice.head_office_address + '\n'
					+ (branchOffice.head_office_tel ? 'TEL:' + branchOffice.head_office_tel + ' ': "")
					+ (branchOffice.head_office_fax ? 'FAX:' + branchOffice.head_office_fax + ' ': "");
				}
				$("#consigned_to").val(header_address);
			}else{
				$("#consigned_to").val("");
			}
		});
		function init_option_list_dvtno(){
			let list_dvt_no = [];
			data_datatable.map(function(item){
				$val = item.delivery_no;
				if(item.count > 1 || item.times > 2){
						$val = $val + "-" + item.times;
				}
				if($.inArray($val, list_dvt_no)==-1){
					list_dvt_no.push($val);
				}
			});
			let data_select = [];
			list_dvt_no.map(function(item){
				data_select.push({id: item, text: item});
			});
			$('#dvt_select').select2({
				placeholder: "Select DeliveryNo",
				data: data_select,
				allowClear: true
			});
		}	
		init_option_list_dvtno();
		function filter_dvt_no(){
			let data = $('#dvt_select').select2('data');
			let values_selected = data.map(function(item){return item.id;});
			let datatable = $("#inv_pl_print_list").DataTable();
			let filteredData = [];
			if(values_selected.length > 0){
				filteredData = data_datatable.filter(function(item){
															let	$val = item.delivery_no;
															if(item.count > 1 || item.times > 2){
																	$val = $val + "-" + item.times;
															}
															item.checked = true;
															return values_selected.indexOf($val) > -1
													});
			}else{
				filteredData = data_datatable.filter(function(item){item.checked=false;return true});
			}
			datatable.clear();
			datatable.rows.add(filteredData);
			datatable.draw();
		}

		$('#dvt_select').on('select2:select select2:unselect', function(){
			filter_dvt_no();
		});
		
		INVPLDatatable = $("#inv_pl_print_list").DataTable({
			"paging": true,
			"ordering": true,
			"oSearch": {"sSearch": '<?php echo ($search_dvt ? $search_dvt : ''); ?>' },
			"scrollX" :true,
			"displayStart": currentPageIndex,
			"columnDefs": [
				{"className": "dt-body-left", "targets": [2]}
			],
			"columns": [
				{ "data": "delivery_no","className": "text-left", render: function(data, type, row, meta){
					if(row.kubun == '1'){
						return '<a class="edit" href="<?php echo base_url()?>japan_order/index/'+row.delivery_no+'">'+row.delivery_no+'</a>';
					}else{
						var a = row.delivery_no.trim();
						var delivery_no = a.slice(0, -2);
						return '<a class="edit" href="<?php echo base_url()?>another_order/index/'+delivery_no+'">'+row.delivery_no+'</a>';
					}
				}},
				{ "data": "times","className": "text-center", render: function(data, type, row, meta){
					if(row.count > 1 || row.times > 2){
						return row.times;
					}
					return '';
				}},
				{ "data": "passage_date","className": "text-center"},
				{ "data": "delivery_date","className": "text-center"},
				{ "data": "pack_no", render: function(data){
					if(data){
						var dataArray = data.split(',');
						var uniqueArray = [];
						dataArray.forEach(function(item){
								item = item.trim();
								if(uniqueArray.indexOf(item) < 0){
									uniqueArray.push(item);
								}
						});
						return uniqueArray.join();
					}
					return data;
					}},
				{ "data": "header_name","className": "text-left"},
				{ "data": "customer","className": "text-left", render:function(data, type, row, meta){
					if(data){
						return data;
					}else if(row.pack_consigned_name){
						var a = row.pack_consigned_name.split(",");
						a = a.filter(function(item, pos) {
							return item && item.trim() != "" && a.indexOf(item.trim()) == pos;
						});
						return a.join();
					}else{
						return "";
					}
				}},
				{ "data": "buyer","className": "text-left", render:function(data, type, row, meta){
					if(data){
						return data;
					}else if(row.pack_customer){
						var a = row.pack_customer.split(",");
						a = a.filter(function(item, pos) {
							return item && item.trim() != "" && a.indexOf(item.trim()) == pos;
						});
						return a.join();
					}else{
						return "";
					}
				}},
				{ "data": "print_date","className": "text-left", render:function(data, type, row, meta){
					if(data){
						return data.substr(0,10);
					}
					return "";
				}},
				{ "data": "status"},
				{ "data": "delivery_method", render:function(data, type, row, meta){
					return row.vessel_flight || row.delivery_method;
				}},
				{ "data": "note"},
				{ "data": null, render : function(data, type, row, meta){
					let checked = "";
					if(row.checked){
						checked = "checked";
					}
					var $el = '<label class="check-container" style="font-size: 15px;">'
							+ '<input type="checkbox" '+checked+'>'
							+ '<span class="check-checkmark" style="height: 20px; width: 20px;"></span>'
							+ '</label>';
					return $el;
				}}
			],
			"createdRow": function(row, data, dataIndex) {
					if(data.kubun == '2'){
							$(row).css('background-color', 'rgb(255, 232, 217)');
					}
			},
			"rowCallback": function( row, data ) {
				let index = INVPLDatatable.row(row).indexes()[0];
				let idx = listChecked.indexOf(index);
					if(idx != -1){
						$(row).find('input[type="checkbox"]').iCheck('check');
						data.checked = true;
					}
			},
			// data: data_datatable
			"ajax":{
				url : "<?php echo base_url("inv_pl_print/loadINVPL") ?>",
				type : 'post'
			}
    	});

			$('#inv_pl_print_list').on('xhr.dt', function( e, settings, json){
				$(this).data('is-loaded', true);
			});

			$('#inv_pl_print_list').on('draw.dt', function (){
				if($(this).data('is-loaded')){
					$(this).data('is-loaded', false);
					$(this).DataTable().page(currentPageIndex).draw(false);
				}
			});
			
			$('#inv_pl_print_list').on('page.dt', function () {
				var info = $('#inv_pl_print_list').DataTable().page.info();
				currentPageIndex = info.page;
			});

      $("#inv_pl_print_list").on("click", 'input[type="checkbox"]', function(){
            var $row = $(this).parents('tr');
						var rowData = INVPLDatatable.row($row).data();
            rowData.checked = $(this)[0].checked;
						// add checked row to list
						if (rowData.checked == true) {
							listChecked.push(INVPLDatatable.row($row).indexes()[0]);
						} else {
							var id = listChecked.indexOf(INVPLDatatable.row($row).indexes()[0]);
							if(id != -1) {
								listChecked.splice(id, 1);
							}
						}
            // $("#data_currency").val(rowData["currency"]);
      });
			//print modal
			$("#inv_pl_print_modal").on('shown.bs.modal', function() {
				var currencyList = [];
					tableData.forEach(function(element){
						currencyList.push(element['currency']);
					});
				currencyList = currencyList.filter(unique);
				$("#data_currency").val(currencyList.join());
				$("#inv_pl_print_modal #print_date").datepicker("setDate", new Date());
				$("#header_name").val(tableData[0].header_name).trigger("propertychange");
				$("#header_address").val(tableData[0].header_address);
				// $("#customer").val(tableData[0].customer ? tableData[0].customer : tableData[0].pack_customer);
				$("#consigned_name").val(tableData[0].customer?tableData[0].customer:tableData[0].pack_consigned_name.trim()).change();
				$("#consigned_to").val(tableData[0].consignee ? tableData[0].consignee : tableData[0].pack_consigned_to );
				$("#buyer").val(tableData[0].buyer ? tableData[0].buyer : tableData[0].pack_customer).change();
				$("#buyer_address").val(tableData[0].buyer_add ? tableData[0].buyer_add : tableData[0].pack_consigned_to);
				$("#payment_by").val(tableData[0].payment).trigger('change');
				$("#customer_code").val(tableData[0].customer_code ? tableData[0].customer_code.trim() : "");
				$("#rate_usd").val(tableData[0].rate);
				$("#rate_jpy").val(tableData[0].rate_jpy ? tableData[0].rate_jpy : "");
				$("#rate_jpy_usd").val(tableData[0].rate_jpy_usd ? tableData[0].rate_jpy_usd : "");
				$("#notify").val(tableData[0].notify ? tableData[0].notify: "");
				$("#notify_address").val(tableData[0].notify_address ? tableData[0].notify_address: "");
				$("#other_reference").val(tableData[0].other_reference ? tableData[0].other_reference: "");
				$("#invoice_no_excel").val(tableData[0].invoice_no ? tableData[0].invoice_no : "");
				$("#red_invoice_no_excel").val(tableData[0].red_invoice_no ? tableData[0].red_invoice_no : "");
				$("#from").val(tableData[0].from ? tableData[0].from: "");
				$("#to").val(tableData[0].to ? tableData[0].to : "");
				$("#payment_by_name").val(tableData[0].payment_term ? tableData[0].payment_term: "");
				$("#inv_delivery_condition").val(tableData[0].delivery_condition ? tableData[0].delivery_condition : "");
				$("#vessel_flight").val(tableData[0].vessel_flight || tableData[0].delivery_method).change();
				if (tableData[0].delivery_date) {
					$("#delivery_print_date").datepicker("setDate", formatDate(tableData[0].delivery_date));
				} else {
					$("#delivery_print_date").datepicker("setDate", new Date());
				}
				$("#contract_no_pl").val(tableData[0].contract_no_print||tableData[0].contract_no.substring(0,30)).change();
				//Set select shosha nitify
				dataFilterShousha = [];
				let data = dataConsignee[$("#consigned_name").val()];
				if(data){
					Object.keys(data.shousha).forEach(function(key){
						dataFilterShousha[dataFilterShousha.length] = {
							value: data.shousha[key]["name"],
							data: data.shousha[key]["address"],
						}
					});
					$("#notify").autocomplete({
						lookup: dataFilterShousha,
						minChars: 0,
						onSelect: function (data){
							$(this).change();
							$("#notify_address").val("");
							$("#notify_address").val(data.data);
						}
					})
				}
				if(tableData[0].print_currency){
					$("#output_currency").val(tableData[0].print_currency);
				}
      });
			$("#inv_pl_print_modal").on('hidden.bs.modal', function(){
					INVPLDatatable.ajax.reload();
			});

		// Set value for eachtime_3 when eachtime_no on keyup
		$('#agreement_eachtime #eachtime_no').on('keyup', function(){
			var requestData = {};
			requestData.eachtime_no = $(this).val();
			requestData.contract_type = $("#eachtime_1").val();
			requestData.party_a = $("#eachtime_4").val();
			requestData.kubun = '2002';
			$.ajax({
				"url": '<?php echo base_url('inv_pl_print/get_eachtime_no')?>',
				"data": requestData,
				"type": "POST",
				"dataType": "json",
			}).done(function(response){
				if(response.success) {
					$('#agreement_eachtime #eachtime_3').val(response.eachtime_3);
				} else {
					$('#agreement_eachtime #eachtime_3').val('001');
				}
			});
		});
		// Set value for eachtime_3 when eachtime_no on keyup
		$('#sales_contract #contract_no').on('keyup blur', function(){
			var requestData = {};
			requestData.eachtime_no = $(this).val();
			requestData.party_a = $("#contract_no_4").val();
			requestData.contract_type = $("#contract_no_1").val();
			requestData.kubun = '2003';
			$.ajax({
				"url": '<?php echo base_url('inv_pl_print/get_eachtime_no')?>',
				"data": requestData,
				"type": "POST",
				"dataType": "json",
			}).done(function(response){
				if(response.success) {
					$('#sales_contract #contract_no_3').val(response.eachtime_3);
				} else {
					$('#sales_contract #contract_no_3').val('001');
				}
			});
		});
		// Set value for bank information when select bank on change
		$('#tab_content1 #bank_name').on("change", function(){
				var bank_info = $('#tab_content1 #bank_name option:selected').attr('field');
				$('#tab_content1 #bank_info').val(bank_info);
		});

		// Set value for party a information 
		$('#tab_content1 #party_a').on("change", function(){
				var party_a_info = $('#tab_content1 #party_a option:selected').attr('field');
				$('#tab_content1 #party_a_info').val(party_a_info);
				if($(this).val() == '002'){
						$('#contract_no_4').val('(HN)');
						$('#agreement #agreement_3').val('(HN)');
						$('#agreement_eachtime #eachtime_4').val('(HN)');
						$('#principal_contract #principal_3').val('(HN)');
				}else{
						$('#contract_no_4').val('');
						$('#agreement #agreement_3').val('');
						$('#agreement_eachtime #eachtime_4').val('');
						$('#principal_contract #principal_3').val('');
				}
		});

		// sum sales contract
		$('#sign_required').on('ifChecked', function(event){
			if($('#tab_content3 #consignee').val()==''){
				if(tableData[0].pack_consigned_name){
					$('#tab_content3 #consignee').val(tableData[0].pack_consigned_name);
					$('#tab_content3 #consignee_info').val(tableData[0].pack_consigned_to);
				}else{
					$('#tab_content3 #consignee').val(tableData[0].consignee);
					$('#tab_content3 #consignee_info').val(tableData[0].buyer_add);
				}
				$('#tab_content3 #consignee').change();
				$('#tab_content2 #party_b').val(tableData[0].buyer);
				$('#tab_content4 #notify').val(tableData[0].notify);
				$('#tab_content4 #notify_info').val(tableData[0].notify_address);
			}
				$('#myTab #notify').show();
				$('#myTab #consignee').show();
		});

    $('#sign_required').on('ifUnchecked', function(){
			$('#myTab li').each(function( index ) {
				$(this).children().hide();
				$(this).removeClass('active');
			});
			$('#myTab #party_a').show();
			$('#myTab #party_b').show();
			$('#myTab #party_a').parent().addClass('active');

			$('#tab_content1').addClass('active in');
			$('#tab_content2').removeClass('active in');
			$('#tab_content3').removeClass('active in');
			$('#tab_content4').removeClass('active in');
		});
		// $('#sales_output_currency').on( 'change' ,function(){
		// 	var outputCurrency = $(this).val();
		// 	var currentCurrency = $('#sales_currency').val();
		// 	if(outputCurrency == currentCurrency){
		// 		$('#sales_rate').val(1);
		// 	} else {
		// 		if(currentCurrency == 'VND'){
		// 			let salesRate = ('rate_'+outputCurrency).toLowerCase();
		// 			if(defaultValue && defaultValue[salesRate]){
		// 				$('#sales_rate').val(1/parseFloat(defaultValue[salesRate]));
		// 			}
		// 		} else if(outputCurrency == 'VND'){
		// 			let salesRate = ('rate_'+currentCurrency).toLowerCase();
		// 			if(defaultValue && defaultValue[salesRate]){
		// 				$('#sales_rate').val(defaultValue[salesRate]);
		// 			}
		// 		} else{
		// 			$('#sales_rate').val('');
		// 		}
		// 	}
		// });
			$('#output_currency_eachtime').on( 'change' ,function(){
				var outputCurrency = $(this).val();
				var currentCurrency = $('#data_currency_eachtime').val();
				if(outputCurrency == currentCurrency){
					$('#rate_eachtime').val(1);
				} else {
					if(currentCurrency == 'VND'){
						let salesRate = ('rate_'+outputCurrency).toLowerCase();
						if(defaultValue && defaultValue[salesRate]){
							$('#rate_eachtime').val(1/parseFloat(defaultValue[salesRate]));
						}
					} else if(outputCurrency == 'VND'){
						let salesRate = ('rate_'+currentCurrency).toLowerCase();
						if(defaultValue && defaultValue[salesRate]){
							$('#rate_eachtime').val(defaultValue[salesRate]);
						}
					} else{
						$('#rate_eachtime').val('');
					}
				}
			});
      $('#cont_print_modal').on("click", "#btn_cont_print", function(){
					// Remove all class validate[required] in form
					$('#cont_print_modal input').removeClass('validate[required]');
					$('#cont_print_modal select').removeClass('validate[required]');
					$('#cont_print_modal textarea').removeClass('validate[required]');

					// Validate for party_a and party_b
					$('#tab_content1 #party_a').addClass('validate[required]');
					$('#tab_content2 #party_b').addClass('validate[required]');
					if($('#output_select1').is(':checked')) {
						// Add class validate[required] and Validation agreement and eachtime contract
						$('#agreement_eachtime #eachtime_no').addClass('validate[required]');
						$('#agreement_eachtime #contract_date_ae').addClass('validate[required]');
						$('#agreement_eachtime #delivery_date').addClass('validate[required]');
						$('#agreement_eachtime #delivery_condition').addClass('validate[required]');
						$('#agreement_eachtime #output_currency_eachtime').addClass('validate[required]');
						$('#agreement_eachtime #payment_methods_eachtime').addClass('validate[required]');
						$('#tab_content3 #consignee').addClass('validate[required]');
						$('#tab_content1 #bank_name').addClass('validate[required]');

						var validate = $('#frm_cont_print').validationEngine('validate');
						if(!validate) { return; }
						var data_currency_eachtime = $('#agreement_eachtime #data_currency_eachtime').val();
						var output_currency_eachtime = $('#agreement_eachtime #output_currency_eachtime').val();
						var rate_eachtime = $('#agreement_eachtime #rate_eachtime').val();

						if(data_currency_eachtime == output_currency_eachtime && rate_eachtime == '') {
							snackbarShow('<?php echo $this->lang->line('SES0010_E006')?>');
							return;
						}
						var url = "<?php echo base_url('inv_pl_print/eachTimePrintExcel')?>";
						$("#frm_cont_print").attr("action", url).submit();
						// $("#cont_print_modal").modal('hide');
					}else if($('#output_select2').is(':checked')) {
							// Add class validate[required] and Validation principal contract
						$('#principal_contract #principal_contract_no').addClass('validate[required]');
						$('#principal_contract #delivery_condition_principal').addClass('validate[required]');
						$('#principal_contract #party_charged_principal').addClass('validate[required]');
						$('#principal_contract #payment_currency').addClass('validate[required]');
						$('#principal_contract #payment_term_principal').addClass('validate[required]');
						$('#principal_contract #feedback_within').addClass('validate[required]');
						$('#principal_contract #end_date_principal').addClass('validate[required]');
						$('#principal_contract #fee_terms_principal').addClass('validate[required]');
						$('#principal_contract #contract_date_principal').addClass('validate[required]');
						$('#principal_contract #payment_methods_principal').addClass('validate[required]');
						$('#tab_content1 #bank_name').addClass('validate[required]');

						var validate = $('#frm_cont_print').validationEngine('validate');
						if(!validate) { return; }

						var url = "<?php echo base_url('inv_pl_print/HDNTPrintWord')?>";
						$("#frm_cont_print").attr("action", url).submit();
						// $("#cont_print_modal").modal('hide');
					} else if($('#output_select3').is(':checked')){
						$('#sales_contract #contract_no').addClass('validate[required]');
						$('#sales_contract #contract_no_3').addClass('validate[required]');
						$('#sales_contract #contract_date').addClass('validate[required]');
						$('#sales_contract #party_charged').addClass('validate[required]');
						// $('#sales_contract #vat').addClass('validate[required]');
						$('#sales_contract #payment_methods').addClass('validate[required]');
						$('#sales_contract #payment_term').addClass('validate[required]');
						$('#sales_contract #fee_terms').addClass('validate[required]');
						$('#sales_contract #sales_output_currency').addClass('validate[required]');
						$('#sales_contract #quantity_odds').addClass('validate[required]');
						$('#tab_content3 #consignee').removeClass('validate[required]');

						if($('#sales_contract #sign_required').is(':checked')) {
							$('#tab_content3 #consignee').addClass('validate[required]');
						}

						var validate = $('#frm_cont_print').validationEngine('validate');
						if(!validate) { return; }
						var url = "<?php echo base_url('inv_pl_print/SumSalesPrintWord')?>";
						$("#frm_cont_print").attr("action", url).submit();
						// $("#cont_print_modal").modal('hide');
					} else if($('#output_select4').is(':checked')){
						$('#agreement #agreement_contract_no').addClass('validate[required]');
						$('#agreement #contract_date_ae_agreement').addClass('validate[required]');
						$('#agreement #end_date_ae').addClass('validate[required]');
						$('#tab_content1 #party_a').addClass('validate[required]');
						$('#tab_content2 #party_b').addClass('validate[required]');
						var validate = $('#frm_cont_print').validationEngine('validate');
						if(!validate) { return; }
						var url = "<?php echo base_url('inv_pl_print/agreementPrintExcel')?>";
						$("#frm_cont_print").attr("action", url).submit();
						// $("#cont_print_modal").modal('hide');
					}
				});
	}

	function getSignatureInfo(){
		$("#tab_content3 #consignee").autocomplete({
			lookup: dataFilterConsignee,
			minChars: 0,
			onSelect: function (data){
				$(this).change();
				let address = data.data.address;
				let address_vn = data.data.address_vn
				let deliveryPlaceList = data.data.delivery_place;
				// let deliveryFilter = Object.keys(deliveryPlaceList).filter(function(key){
				// 		if(deliveryPlaceList[key]["address"]){
				// 			return true;
				// 		}
				// 		return false;
				// 	}).map(function(key){
				// 		return{value: deliveryPlaceList[key]["address"]};
				// 	});
				$('#tab_content3 #consignee_info').val(address);
				$('#tab_content3 #consignee_info_vn').val(address_vn);
				$('#agreement_eachtime #delivery_to').val(address);
				// Delivery Place is consignee address
				// deliveryFilter.push({value: address})
				// if(deliveryFilter.length == 1){
				// 	$('#agreement_eachtime #delivery_to').val(deliveryFilter[0].value);
				// }else{
				// 	$('#agreement_eachtime #delivery_to').val("");
				// 	$('#agreement_eachtime #delivery_to').autocomplete({
				// 		lookup: deliveryFilter,
				// 		minChars: 0
				// 	});
				// }
				listNotify = data.data.shousha;
				listPartyB = data.data.customer;
				$("#tab_content2 #party_b").val("");
				$("#tab_content2 #party_b_info").val("");
				$('#tab_content4 #notify').val("");
				$('#tab_content4 #notify_info').val("");
				$('#agreement_eachtime #payment_term_eachtime').val("");
				$("#sales_contract #payment_term").val("");
				$("#principal_contract #payment_term_principal").val("");
				$('#agreement_eachtime #delivery_condition').val(data.data.delivery_condition);
				$("#principal_contract #delivery_condition_principal").val(data.data.delivery_condition);
				load_notify(listNotify);
				load_party_b(listPartyB);
			}
		});
	}

	function beforeShowPLPrintModal(){
    tableData = [];
		var currency = [];
		var kubun = [];
		var customer = [];
        var tbData = INVPLDatatable.rows().data();
		for(var i = 0 ; i< tbData.length; i++){
			let data = INVPLDatatable.rows(i).data()[0];
			if(data.checked){
				tableData.push(data);
				if(data.currency && currency.indexOf(data.currency) == -1){
					currency.push(data.currency);
				}
				if(data.kubun && kubun.indexOf(data.kubun) == -1){
					kubun.push(data.kubun);
				}
				if(data.customer && customer.indexOf(data.customer) == -1){
					customer.push(data.customer);
				}
				if(!data.customer && data.pack_customer && customer.indexOf(data.pack_customer) == -1){
					customer.push(data.pack_customer);
				}
				
			}
		}
		// if(currency.length > 1){
		// 	$("#info_message").text('<?php echo $this->lang->line('SES0010_E003') ?>');
		// 	$("#info_modal").modal();
		// 	return;
		// }
		if(kubun.length > 1){
			$("#info_message").text('<?php echo $this->lang->line('SES0010_E004') ?>');
			$("#info_modal").modal();
			return;
		}
		if(customer.length > 1){
			$("#info_message").text('<?php echo $this->lang->line('SES0010_E005') ?>');
			$("#info_modal").modal();
			return;
		}
		if(tableData.length > 1) {
			var count = 1;
			$.each(tableData, function(index, value){
				if(index > 0) {
					if( value.pack_consigned_name != tableData[index-1].pack_consigned_name || value.kubun != tableData[index-1].kubun || (value.buyer||value.pack_customer) != (tableData[index-1].buyer||tableData[index-1].pack_customer)) {
						count += 1;
					}
				}
			});
			if(count != 1) {
				$("#info_message").text('<?php echo $this->lang->line('SES0010_E005') ?>');
				$("#info_modal").modal();
				return;
			}
		}
    if(tableData.length > 0){
			$("#inv_pl_print_modal").modal();
		}else{
      snackbarShow('<?php echo $this->lang->line('SES0010_E001'); ?>');
			// $("#info_message").text('<?php echo $this->lang->line('SES0010_E001') ?>');
			// $("#info_modal").modal();
		}
    }

    function beforeShowCONTPrintModal(){
			$('#tabs a:first').tab('show');
			// $('#agreement_contract_no').prop('readonly', false);
			// $('#eachtime_no').prop('readonly', false);
			// $('#principal_contract_no').prop('readonly', false);
			$('#agreement_eachtime #eachtime_3').val('');
		
			// $('#type').val('1');
			tableData = [];
			var currency = [];
			var tbData = INVPLDatatable.rows().data();
			for(var i = 0 ; i< tbData.length; i++){
				let data = INVPLDatatable.rows(i).data()[0];
				if(data.checked){
					tableData.push(data);
					if(data.currency && currency.indexOf(data.currency) == -1){
						currency.push(data.currency);
					}
				}
      }
			// if(currency.length > 1){
			// 	$("#info_message").text('<?php echo $this->lang->line('SES0010_E003') ?>');
			// 	$("#info_modal").modal();
			// 	return;
			// }
      if(tableData.length > 0){
				getSignatureInfo();
				var key = '';
				var packNo = '';
				var count = 1;
				var printArray = [];
				for(let i = 0; i < tableData.length; i++){
					var temp = tableData[i];
					currentKubun = temp.kubun;
					if(i == (tableData.length - 1)){
						key += temp.delivery_no+";"+temp.order_date+";"+temp.times;
						packNo += temp.pack_no;
					}else{
						key += temp.delivery_no+";"+temp.order_date+";"+temp.times+"*";
						packNo += temp.pack_no+"*";
					}
					printArray.push({ 
						delivery_no : temp.delivery_no,
          	order_date : temp.order_date,
            times : temp.times,
            pack_no : temp.pack_no
					});
				}
				$('#delivery_data').val(JSON.stringify(printArray));
				$('#dvt_key').val(key);
				$('#pack_no').val(packNo);
				if(tableData.length > 1) {
					$.each(tableData, function(index, value){
						if(index > 0) {
							if( value.pack_consigned_name != tableData[index-1].pack_consigned_name || value.kubun != tableData[index-1].kubun || value.buyer != tableData[index-1].buyer) {
								count += 1;
							}
						}
					});
					if(count != 1) {
						$("#info_message").text('<?php echo $this->lang->line('SES0010_E005') ?>');
						$("#info_modal").modal();
						return;
					}
				}

				if($('#output_select1').is(':checked')) {
						load_agreement_eachtime(tableData);
				}
				if($('#output_select2').is(':checked')) {
						load_principal(tableData);
				}
				if($('#output_select3').is(':checked')) {
						load_sales_contract(tableData);
				}
				if($('#output_select4').is(':checked')) {
					load_agreement(tableData);
				}
				$("#cont_print_modal").modal();
		}else{
				$("#info_message").text('<?php echo $this->lang->line('SES0010_E001') ?>');
				$("#info_modal").modal();
		} 
  }

	function INVPLPRINT(){
		$("#delivery_no_list").val(JSON.stringify(tableData));
		if(!$("#invoice").prop('checked') && !$("#packing_list").prop('checked') && !$("#delivery_note").prop('checked') && !$("#inv_del_voucher_excel").prop('checked')){
				snackbarShow('<?php echo $this->lang->line('SES0010_E002'); ?>');
				return;
		}
		if($("#inv_del_voucher_excel").prop('checked') && !$("#invoice_no_excel").val()){
				snackbarShow('<?php echo $this->lang->line('SES0010_E007'); ?>');
				$('#invoice_no_excel').focus();
				return;
		}
		// if($("#data_currency").val() && $("#data_currency").val().trim() != $("#output_currency").val() && $("#rate").val() == ""){
		// 		snackbarShow('<?php echo $this->lang->line('SES0010_E006'); ?>');
		// 		return;
		// }
		$("#INVPLForm").submit();
  }
	function load_party_b(listPartyB){
		// $("#tab_content2 #party_b option").remove();
		// $("#tab_content2 #party_b").append('<option></option>');
		$("#tab_content2 #party_b").autocomplete({
			lookup: Object.keys(listPartyB).filter(function(key){
				if(key) return true;
				return false;
			}).map(function(key){
				return {
					value: listPartyB[key]["company_name"],
					data: listPartyB[key]
				};
			}),
			minChars: 0,
			onSelect: function (data){
				$(this).change();
				var customer 						= data.data.company_name||"",
						tel      						= data.data.head_office_tel||"",
						fax      						= data.data.head_office_fax||"",
						address  						= data.data.head_office_address||"",
						address_vn					= data.data.head_office_address_vn||"",
						represented 				= data.data.head_office_contract_name||"",
						position 						= data.data.head_office_position||"",
						reference      			= data.data.reference||"",
						contract_from_date  = data.data.contract_from_date||"",
						contract_end_date  = data.data.contract_end_date||"";
				var party_b_info = "";
				var customer_vn = "";
				if(address_vn){
					customer_vn = /(.*)/.exec(address_vn)[1];
					address_vn = /\n([\d\D]*)/.exec(address_vn)[1];
				}
				if(customer || tel || fax || address){
					party_b_info = customer_vn + "\n"
												+ customer + "\n"
												+ "Địa chỉ: " + address_vn + "\n"
												+ "Address: " + address + "\n"
												+ "Điện thoại/Tel: " + tel + "\n"
												+ "Fax: " + fax + "\n"
												+ "Đại diện bởi/Represented by: " + represented + "\n"
												+ "Chức vụ/Position: " + position;
				}
				$('#tab_content2 #party_b_info').val(party_b_info);
				$('#agreement_eachtime #payment_term_eachtime').val(data.data.payment_term);
				$("#sales_contract #payment_term").val(data.data.payment_term);
				$("#principal_contract #payment_term_principal").val(data.data.payment_term);
				// Set value for input hidden(company_name_b, tel_b, fax_b, address_b) of party_b
				$('#company_name_b').val(customer);
				$('#tel_b').val(tel);
				$('#fax_b').val(fax);
				$('#address_b').val(address);
				$('#represented_by_b').val(represented);
				$('#position_b').val(position);
				$('#reference').val(reference);
				$('#contract_from_date').val(contract_from_date);
				$('#contract_end_date').val(contract_end_date);
				$('#contract_date_ae_agreement').val(contract_from_date);
				$('#end_date_ae').val(contract_end_date);
				// Set agreement info
				$('#agreement_no_label').html(reference);
				$('#contract_date_label').html(contract_from_date);
				$('#end_day_label').html(contract_end_date);
			}
		});
	}
	function load_notify(listNotify){
		$("#tab_content4 #notify").autocomplete({
			lookup: Object.keys(listNotify).filter(function(key){
				if(key) return true;
				return false;
			}).map(function(key){
				return {
					value: listNotify[key]["name"],
					data: listNotify[key]
				};
			}),
			minChars: 0,
			onSelect: function (data){
				$(this).change();
				var address = data.data.address;
				$('#tab_content4 #notify_info').val(address);
			}
		});
	}

	function load_agreement_eachtime(tableData)
	{
    resetModal();	// Function remove all value in form
		// Load party b option list
		getSignatureInfo();
		// Set default state
		$('#scan_sign_ae').iCheck("uncheck").trigger("ifUnchecked");
		// Get Currency from database
		var currencyList = [];
				tableData.forEach(function(element){
					currencyList.push(element['currency']);
				});
			currencyList = currencyList.filter(unique);
			$('#data_currency_eachtime').val(currencyList.join());

		// Set current date for input date
		$("#agreement_eachtime #contract_date_ae").datepicker("setDate", new Date());
		$("#agreement_eachtime #delivery_date").datepicker("setDate", new Date());
		$('#eachtime_6').val('');
		if( tableData.length >= 1){
			var searchData = {};
      searchData.packingList = tableData;
			searchData.kubun = '2002';
			$('#tab_content3 #consignee').val((tableData[0].customer?tableData[0].customer:tableData[0].pack_consigned_name).trim()).change();
			// if(tableData[0].pack_consigned_name){
			// 	$('#tab_content3 #consignee').val(tableData[0].pack_consigned_name).change();
			// }else{
			// 	$('#tab_content3 #consignee').val(tableData[0].consignee).change();
			// }

			$('#tab_content2 #party_b').val(tableData[0].buyer).change();
			$('#tab_content4 #notify').val(tableData[0].notify).change();
			// Get Contract data from database
			$.ajax({
				"url": '<?php echo base_url('inv_pl_print/getSalesContractPrinted');?>',
				"dataType": 'json',
				"data": searchData,
				"type": 'post',
			}).done( function(response){
				if(response) {
					if(response.data && Object.keys(response.data).length > 0){
						const data = response.data;
						var contractDate = '',
							endDate = '',
							deliveryDate = '',
							agreementDate = '';
						if(data.contract_date){
							contractDate = formatDate(data.contract_date);
						}
						if(data.end_date){
							endDate = formatDate(data.end_date);
						}
						if(data.delivery_date){
							deliveryDate = formatDate(data.delivery_date_eachtime);
						}
						if(data.agreement_date){
							agreementDate = formatDate(data.agreement_date);
						}
						$('#agreement_eachtime #contract_date_ae').val(contractDate);
						
						$('#agreement_eachtime #delivery_date').val(deliveryDate);
						$('#agreement_eachtime #delivery_condition').val(data.delivery_condition).change();
						$('#agreement_eachtime #payment_term_eachtime').val(data.payment_term).change();
						$('#agreement_eachtime #rate_eachtime').val(data.rate);
						$('#agreement_eachtime #rate_eachtime_jpy').val(data.rate_jpy);
						$('#agreement_eachtime #rate_eachtime_jpy_usd').val(data.rate_jpy_usd);
						$('#agreement_eachtime #output_currency_eachtime').val(data.payment_currency);
						$('#agreement_eachtime #payment_methods_eachtime').val(data.payment_methods).change();
						$('#tab_content1 #party_a').val(data.party_a);
						$('#tab_content1 #party_a_info').val($('#tab_content1 #party_a option:selected').attr('field'));

						$('#tab_content1 #bank_name').val(data.bank);
						$('#tab_content1 #bank_info').val($('#bank_name option:selected').attr('field'));
						let consignee = data.customer?data.customer:data.pack_consigned_name;
						if(consignee){
							$('#tab_content3 #consignee').val().change();
						}
						// if(data.consignee){
						// 	$('#tab_content3 #consignee').val(data.consignee).change();
						// }else{
						// 	$('#tab_content3 #consignee').val(tableData[0].pack_consigned_name).change();
						// }

						$('#tab_content2 #party_b').val(data.party_b).change();
						$('#tab_content4 #notify').val(data.notify).change();
						if(data.contract_no && data.contract_no != ''){
							const noList = data.contract_no.split("-");
							$('#eachtime_no').val(noList[0]);
							// if($('#tab_content1 #party_a').val() == '002') {
							// 	$('#eachtime_4').val('(HN)');
							// }
							var arr = noList[3].split('/');
							var number_arr = arr[0].split('(');
							$('#eachtime_3').val(number_arr[0]);
							if(typeof number_arr[1] !== 'undefined'){
								$('#eachtime_4').val('('+number_arr[1]);
							}
							//Set tail for eachtime_6
							if(arr.length == 2){
								let tail = arr[1];
								let str_tail = "";
								if(/\((.*)\)/.test(tail)){
									let arr_tail = /\((.*)\)/.exec(tail);
									str_tail = arr_tail[1];
								}
								$('#eachtime_6').val(str_tail);
							}
							else {
								$('#eachtime_6').val(noList[6]);
							}
						}
						$('#agreement_eachtime #eachtime_no_old').val(data.contract_no);
						// if(data.contract_no == null || data.contract_no == '') {
						// 	$('#type').val('1');
						// } else {
						// 	$('#type').val('2');
						// }
					} else {
						if(response.increNo){
							$('#eachtime_3').val(response.increNo);
						}
						$('#eachtime_no').attr('readonly',false);
						$('#eachtime_6').attr('readonly',false);
						$('#eachtime_4').val('');
					}
				}
			});
		}
    }

		function load_agreement(tableData)
	{
    resetModal();	// Function remove all value in form
		// Load party b
		// var listPartyB = dataPartyB;
		load_party_b(listPartyB);
		var requestData = {};
		requestData.dvtList = tableData;

		// Set current date for input date
		$("#agreement #contract_date_ae_agreement").datepicker("setDate", new Date());
		let strCurrentYear = (new Date()).getFullYear();
		$("#agreement #end_date_ae").datepicker("setDate", new Date(strCurrentYear + "/12/31"));

		if( tableData.length >= 1){
			$('#tab_content2 #party_b').val(tableData[0].buyer).change();
			var searchData = {};
      searchData.partyb = $('#tab_content2 #party_b').val();
			searchData.kubun = '2004';
			// Get Contract data from database
			$.ajax({
				"url": '<?php echo base_url('inv_pl_print/getAgreementContract');?>',
				"dataType": 'json',
				"data": searchData,
				"type": 'post',
			}).done( function(response){
				if(response) {
					if(response.data && Object.keys(response.data).length > 0){
						const data = response.data;
						var contractDate = '',
							endDate = '',
							agreementDate = '';
						if(data.contract_date){
							contractDate = formatDate(data.contract_date);
						}
						if(data.end_date){
							endDate = formatDate(data.end_date);
						}
						$('#agreement #contract_date_ae_agreement').val(contractDate);
						
						if(endDate){
							$('#agreement #end_date_ae').val(endDate);
						}
						$('#tab_content1 #party_a').val(data.party_a);
						$('#tab_content1 #party_a_info').val($('#tab_content1 #party_a option:selected').attr('field'));

						$('#tab_content2 #party_b').val(data.party_b).change();
						// Set value for Agreement Contract No and EachTime Contract No
						if(data.contract_no && data.contract_no != ''){
							const noList = data.contract_no.split("-");
							$('#agreement_contract_no').val(noList[0]);
							// $('#agreement_contract_no').prop('readonly', true);
							if($('#tab_content1 #party_a').val() == '002') {
								$('#agreement_3').val('(HN)');
							}
							$('#agreement #agreement_no_old').val(data.contract_no);
						}
						// if(data.contract_no == null || data.contract_no == '') {
						// 	$('#type').val('1');
						// } else {
						// 	$('#type').val('2');
						// }
					}
				}
			});
		}
    }

	function load_principal(tableData)
	{
    resetModal();
		// listPartyB = dataPartyB;
		load_party_b(listPartyB);
		// $("#tab_content2 #party_b option").each(function(){
		// 	var str_company = $(this).text(),
    // 			company_name = str_company.toUpperCase().trim(),
		// 		str_customer = tableData[0]['pack_customer'].trim(),
		// 		pack_customer = str_customer.toUpperCase();
		// 	if (company_name == pack_customer) {
		// 		$(this).prop("selected", true);
		// 	}
		// });

		// set current date
		$("#principal_contract #contract_date_principal").datepicker("setDate", new Date());
		$("#principal_contract #end_date_principal").datepicker("setDate", new Date());
		$('#principal_5').val('');
		if(tableData.length >= 1) {
			var searchData = {};
            searchData.packingList = tableData;
			searchData.kubun = '2001';
			$('#tab_content2 #party_b').val(tableData[0].buyer).change();
			$.ajax({
				"url": '<?php echo base_url('inv_pl_print/getSalesContractPrinted');?>',
				"dataType": 'json',
				"data": searchData,
				"type": 'post',
			}).done( function(response){
				if(response) {
					if(response.data && Object.keys(response.data).length > 0){
						const data = response.data;
						$('#principal_contract #contract_no_principal').val(data.contract_no);
						var contractDate = '',
							endDate = '';
						if(data.contract_date){
							contractDate = formatDate(data.contract_date);
						}
						if(data.end_date){
							endDate = formatDate(data.end_date);
						}
						$('#principal_contract #contract_date_principal').val(contractDate);
						$('#principal_contract #delivery_condition_principal').val(data.delivery_condition);
						$('#principal_contract #party_charged_principal').val(data.party_charged);
						$('#principal_contract #payment_methods_principal').val(data.payment_methods).change();
						$('#principal_contract #payment_currency').val(data.payment_currency);
						$('#principal_contract #payment_term_principal').val(data.payment_term);
						$('#principal_contract #feedback_within').val(data.feedback_day_num);
						$('#principal_contract #end_date_principal').val(endDate);
						$('#principal_contract #fee_terms_principal').val(data.fee_terms);
						if(data.signature == 't') {
							$('#principal_contract #sign').iCheck('check');
						}
						if(data.terms_overdue == 't') {
							$('#principal_contract #terms_overdue').iCheck('check');
						}
						if(data.scan_signature == 't') {
							$('#principal_contract #scan_sign').iCheck('check');
						}
						$('#tab_content1 #party_a').val(data.party_a);
						$('#tab_content1 #party_a_info').val($('#tab_content1 #party_a option:selected').attr('field'));

						$('#tab_content1 #bank_name').val(data.bank);
						$('#tab_content1 #bank_info').val($('#bank_name option:selected').attr('field'));
						$('#tab_content2 #party_b').val(data.party_b).change();
						// $('#tab_content2 #party_b').val(data.party_b);
						// var customer = $('#tab_content2 #party_b option:selected').attr('company-name'),   // get company name from select
						// 	tel      = $('#tab_content2 #party_b option:selected').attr('tel'),              // get tel from select
						// 	fax      = $('#tab_content2 #party_b option:selected').attr('fax'),              // get fax from select
						// 	address  = $('#tab_content2 #party_b option:selected').attr('address'),          // get address from select
						// 	party_b_info = customer + "\n"
						// 				+ "Address/Địa chỉ: " + address + "\n"
						// 				+ "Tel/ Điện thoại: " + tel + "\n"
						// 				+ "Fax: " + fax + "\n"
						// 				+ "Represented by/Đại diện bởi: " + "\n"
						// 				+ "Position/Chức vụ: ";
						// $('#tab_content2 #party_b_info').val(party_b_info);
						// $('#company_name_b').val(customer);
						// $('#tel_b').val(tel);
						// $('#fax_b').val(fax);
						// $('#address_b').val(address);
						if(data.contract_no && data.contract_no != ''){
							const noList = data.contract_no.split("-");
							$('#principal_contract_no').val(noList[0]);
							// $('#principal_contract_no').prop('readonly', true);XXX-HDNT-E(HN)/18(UQ)
							if($('#tab_content1 #party_a').val() == '002') {
								$('#principal_3').val('(HN)');
							}
							var arr = noList[2].split('/');
							//Set tail for eachtime_6
							if(arr.length == 2){
								let tail = arr[1];
								let str_tail = "";
								if(/\((.*)\)/.test(tail)){
									let arr_tail = /\((.*)\)/.exec(tail);
									str_tail = arr_tail[1];
								}
								$('#principal_5').val(str_tail);
							}
						}
						// if(data.contract_no == null || data.contract_no == '') {
						// 	$('#type').val('1');
						// } else {
						// 	$('#type').val('2');
						// }
					}
				}
			});
		}
    }

    function load_sales_contract(tableData)
    {
        resetModal();
				// listPartyB = dataPartyB;
				load_party_b(listPartyB);
				$("#contract_date").val(formatDate(new Date()));
				$('#eachtime_6').val('');
				var data = '1';
				var currencyList = [];
				tableData.forEach(function(element){
					currencyList.push(element['currency']);
				});
				currencyList = currencyList.filter(unique);
				$('#sales_currency').val(currencyList.join());
				if( tableData.length >= 1){
						var searchData = {};
						searchData.packingList = tableData;
						searchData.kubun = '2003';
						$('#tab_content2 #party_b').val(tableData[0].buyer).change();
						$.ajax({
								"url": '<?php echo base_url('inv_pl_print/getSalesContractPrinted');?>',
								"dataType": 'json',
								"data": searchData,
								"type": 'post',
						}).done( function(response){
								if(response){
									if(response.data && Object.keys(response.data).length > 0){
										const data = response.data;
										if(data.contract_no && data.contract_no != ''){
											const noList = data.contract_no.split("-");
											$('#contract_no').val(noList[0]);
											// $('#contract_no').attr('readonly',true);XXX-HDT-E-001/18(UQ)
											var arr = noList[3].split('/');
											var number_arr = arr[0].split('(');
											$('#contract_no_3_old').val(number_arr[0]);
											$('#contract_no_3').val(number_arr[0]);
											if(typeof number_arr[1] !== 'undefined'){
												$('#contract_no_4').val('('+number_arr[1]);
											}
											// if($('#tab_content1 #party_a').val() == '002') {
											// 	$('#contract_no_4').val('(HN)');
											// 	console.log('hanoi');
											// }
											var contract_no_4 = arr[0].substring(3, arr[0].length);
											$('#contract_no_4').val(contract_no_4);
											//Set tail for eachtime_6
											if(arr.length == 2){
												let tail = arr[1];
												let str_tail = "";
												if(/\((.*)\)/.test(tail)){
													let arr_tail = /\((.*)\)/.exec(tail);
													str_tail = arr_tail[1];
												}
												$('#contract_no_6').val(str_tail);
											} else {
												$('#contract_no_6').val(noList[6]);
												// $('#contract_no_6').attr('readonly',true);
											}
										}
										var contractDate = '';
										if(data.contract_date){
											contractDate = formatDate(data.contract_date);
										}
										$('#sales_contract #contract_date').val(contractDate);
										$('#sales_contract #party_charged').val(data.party_charged);
										$('#sales_contract #vat').val(data.vat);
										$('#sales_contract #payment_methods').val(data.payment_methods).change();
										$('#sales_contract #payment_term').val(data.payment_term);
										$('#sales_contract #fee_terms').val(data.fee_terms);
										$('#sales_contract #sales_output_currency').val(data.payment_currency);
										$('#sales_contract #sales_rate').val(data.rate);
										$('#sales_contract #sales_rate_jpy').val(data.rate_jpy);
										$('#sales_contract #sales_rate_jpy_usd').val(data.rate_jpy_usd);
										$('#sales_contract #quantity_odds').val(data.quantity_odds);
										if(data.signature == 't'){
											$('#sales_contract #sign_required').iCheck('check');
											$('#myTab #notify').show();
											$('#myTab #consignee').show();
											if(data.pack_consigned_name){
												$('#tab_content3 #consignee').val(data.pack_consigned_name).change();
											}else{
												$('#tab_content3 #consignee').val(data.consignee).change();
											}
											$('#tab_content2 #party_b').val(data.party_b).change();
											$('#tab_content4 #notify').val(data.notify).change();
										}else{
											$('#sales_contract #sign_required').iCheck('uncheck');
											$('#myTab #notify').hide();
											$('#myTab #consignee').hide();
										}
										$('#tab_content1 #party_a').val(data.party_a);
										$('#tab_content1 #party_a_info').val($('#tab_content1 #party_a option:selected').attr('field'));

										$('#tab_content1 #bank_name').val(data.bank);
										$('#tab_content1 #bank_info').val($('#bank_name option:selected').attr('field'));
										$('#tab_content2 #party_b').val(data.party_b).change();
										
										if(data.consignee){
											$('#tab_content3 #consignee').val(data.consignee).change();
										}
										if(data.notify){
											$('#tab_content4 #notify').val(data.notify).change();
										}
									}else{
										if(response.increNo){
											$('#contract_no_3').val(response.increNo);
										}
										$('#contract_no').attr('readonly',false);
										$('#contract_no_6').attr('readonly',false);
										$('#contract_no_4').val('');
									}
								}
						});
				}
    }
	// function parse num to char 
    function pad(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }
    function resetModal()
    {
			$('#frm_cont_print input[type="text"]').val('');
			$("#frm_cont_print").find('input[type="text"]').val('');
			$("#frm_cont_print").find('input[type="number"]').val('');
			$("#frm_cont_print").find('input.date').val('');
			$("#frm_cont_print").find('select').val('');
			$("#frm_cont_print").find('input[type="checkbox"]').removeAttr('checked').iCheck('update'); 
			$("#frm_cont_print").find('textarea').val('');
		}
</script>

<style>
.dt-body-left{
	text-align:left !important;
}
</style>
