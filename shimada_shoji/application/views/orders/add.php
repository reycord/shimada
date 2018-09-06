<!-- Edit by Khanh
	Date : 02/04/2018 -->
	<style>
	td div.ellipsis {
    white-space: nowrap !important; 
    max-width: 120px !important; 
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
</style>
<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo $title ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<?php if($isAddNew) :?>
						<li>
							<button class="btn btn-info" onclick="window.location.href='<?php echo base_url(); ?>orders/add'">
								<i class="fa fa-refresh"></i>
								<?php echo $this->lang->line('clear'); ?>
							</button>
						</li>
					<?php else : ?>
						<li>
							<button class="btn btn-info"onclick="window.history.back();">
								<i class="fa fa-arrow-left"></i> 
								<?php echo $this->lang->line('back') ?>
							</button>
						</li>
					<?php endif?>
					<li>
						<button class="btn btn-primary" type="button" id="btnSaveOdersOut">
							<i class="fa fa-save"></i>
							<?php echo $this->lang->line('save'); ?>
						</button>
					</li>
					<li class="<?php echo $isAddNew?'hidden':'' ?>">
						<button class="btn btn-primary" type="button" id="btnCopy">
							<i class="fa fa-copy"></i>
							<?php echo "Copy";//$this->lang->line('save'); ?>
						</button>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="col-md-12 col-sm-12 col-xs- 12">
					<div class="panel panel-default">
						<div class="panel-body">
							<form class="form-horizontal myform" name="frm_order" id="frm_order" method="post">
								<div>
									<input type="hidden" class="form-control" id="insert_update" name="insert_update" value="<?php  echo ($isAddNew == true) ? 0 : 1 ?>">
								</div>
								<div>
									<input type="hidden" class="form-control" id="data_table" name="data_table" value="">
								</div>
								<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left no-padding-right">
									<div class="form-group">
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="buyer_kb"><?php echo $this->lang->line('buyer'); ?></label>
										</div>
										<div class="col-md-2 no-padding-left" style="margin-left: 0px;">
											<p style="margin:0px; padding-top: 4px; padding-bottom: 4px">
												&nbsp;&nbsp;
												<input type="radio" class="flat" name="buyer_kb" id="buyerEPE" value="<?php echo '1' ?>"  <?php if((isset($orderOut[0]['buyer_kb']) && ($orderOut[0]['buyer_kb'] == '1' || $orderOut[0]['buyer_kb'] == null)) || $isAddNew == true) echo 'checked';?> /> EPE
											</p>
										</div>
										<div class="col-md-3 no-padding-left" style="margin-left: 0px;">
											<p style="margin:0px; padding-top: 4px; padding-bottom: 4px">
												&nbsp;&nbsp;
												<input type="radio" class="flat" name="buyer_kb" id="buyerHANOI" value="<?php echo '2' ?>"  <?php if(isset($orderOut[0]['buyer_kb'])) echo $orderOut[0]['buyer_kb'] == '2' ? 'checked' : '';?> /> HANOI
											</p>
										</div>
										<div class="col-md-2 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="tax"><?php echo str_repeat("&nbsp;", 10); echo $this->lang->line('tax'); ?></label>
										</div>
										<div class="col-md-2 col-sm-3 col-xs-3 no-padding-left no-padding-right">
											<select class="form-control" id="tax" name="tax">
												<option value=""></option>
												<?php 
														foreach ($taxClassifications as $taxlist) {
															$selected = "";
															if (isset($orderOut[0]['tax']))
																if ($taxlist['tax_id'] == $orderOut[0]['tax']) $selected = "selected";

															echo "<option value='" . $taxlist['tax_id'] . "' ".$selected.">" . $taxlist['tax_name'] . "</option>";
														}	
													?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="purchase_order_no">
												<?php echo $this->lang->line('purchase_order_no'); ?>
												<span class="required">*</span>
											</label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-9 no-padding-left no-padding-right">
											<div class="col-md-1 col-sm-1 col-xs-1 form-inp no-padding-left no-padding-right">
												<input type="text" class="form-control no-padding-left no-padding-right" style="text-align:center;" id="po_no1" name="po_no1"
												value="PO" readonly>
											</div>
											<div class='col-md-3 col-sm-3 col-xs-3 form-inp no-padding-right' style="padding-left:2px">
												<select class="form-control form_input" id="po_no2" name="po_no2">
													<?php 
														foreach ($poNoList as $item) {
															$selected = "";
															if (isset($orderOut[0]['order_no_2']))
																if ($orderOut[0]['order_no_2'] == $item['komoku_name_2']) $selected = "selected";

															echo "<option value='" . $item['komoku_name_2'] . "' ".$selected.">" . $item['komoku_name_2'] . "</option>";
														}	
													?>
												</select>
											</div>
											<div class="col-md-1 col-sm-1 col-xs-1 form-inp no-padding-right" style="padding-left:2px">
												<input type="text" class="form-control text-center" id="po_no3" name="po_no3" value="I" readonly>
											</div>
											<div class="col-md-2 col-sm-3 col-xs-3 form-inp no-padding-right" style="padding-left:2px">
												<input maxlength='4' 
														type="text" 
														class="form-control text-center" 
														id="po_no4" 
														name="po_no4" 
														value="<?php 
																	if(isset($orderOut[0]['order_no_4'])){
																		echo str_pad(($orderOut[0]['order_no_4']), 4, '0', STR_PAD_LEFT);
																	}else{
																		echo str_pad(($orders_no_4 + 1 ), 4, '0', STR_PAD_LEFT);
																	}
																?>" readonly>
											</div>
											<div class="col-md-2 col-sm-3 col-xs-3 form-inp no-padding-right" style="padding-left:2px">
												<input type="text" class="form-control" id="buyer_nm" name="buyer_nm" value="<?php echo $orderOut[0]['buyer_kb'] == '2' ? '(HN)' : '';?>" readonly>
											</div>
											<div class="col-md-1 col-sm-1 col-xs-1 form-inp no-padding-right" style="padding-left:2px">
												<input maxlength='2' type="text" class="form-control no-padding-left no-padding-right" style="text-align:center;" id="po_no5"
												name="po_no5" value="<?php echo substr(date("Y"), -2) ?>" readonly>
											</div>
											<div class="col-md-2 col-sm-3 col-xs-3 form-inp no-padding-right" style="padding-left:2px">
												<input maxlength='5' type="text" class="form-control" id="po_no6" name="po_no6" value="<?php if(isset($orderOut[0]['order_no_6'])) echo $orderOut[0]['order_no_6'];?>" readonly>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="currency">
												<?php echo $this->lang->line('currency'); ?>
												<span class="required">*</span>
											</label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
											<select class="form-control validate[required]" id="currency" name="currency">
												<option value=""></option>
												<?php 
													foreach ($currency as $item) {
														$selected = "";
														if (isset($orderOut[0]['currency']))
															if ($orderOut[0]['currency'] == $item['currency_name']) $selected = "selected";

														echo "<option value='" . $item['currency_name'] . "' ".$selected.">" . $item['currency_name'] . "</option>";
													}				
												?>
											</select>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="inv_no">INV No</label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
											<input type="text" class="form-control text-uppercase" id="inv_no" name="inv_no" maxlength="20" value="<?php if(isset($orderOut[0]['invoice_no'])) echo $orderOut[0]['invoice_no'];?>">
											<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="cont_no">CONT No</label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
											<input type="text" class="form-control text-uppercase" id="cont_no" name="cont_no" maxlength="20" value="<?php if(isset($orderOut[0]['contract_no'])) echo $orderOut[0]['contract_no'];?>">
											<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="delivery_date">
												<?php echo $this->lang->line('delivery_date'); ?>
											</label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
											<input type="text" class="form-control date" id="delivery_date" name="delivery_date" maxlength="10" value="<?php if(isset($orderOut[0]['delivery_date'])) echo date_format(date_create($orderOut[0]['delivery_date']), 'd M, Y');?>">
											<input type="hidden" name="delivery_date_old" value="<?php if(isset($orderOut[0]['delivery_date'])) echo date_format(date_create($orderOut[0]['delivery_date']), 'd M, Y');?>">
											<span 
														style="font-size: 20px;cursor: pointer; position: absolute; top: 8px; right: 5px; color: blue" 
														class="fa fa-search" 
														aria-hidden="true"
														data-toggle="popover" data-trigger="manual" data-popover-content="#history_delivery_date" data-placement="top"
											/>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" style="color:#428bca" for="customs_clearance_sheet_no">
												<?php echo $this->lang->line('customs_clearance_sheet_no');?>
											</label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
											<input type="text" class="form-control text-uppercase" id="customs_clearance_sheet_no" name="customs_clearance_sheet_no" maxlength="30" value="<?php if(isset($orderOut[0]['customs_clearance_sheet_no'])) echo $orderOut[0]['customs_clearance_sheet_no'];?>">
											<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" style="color:#428bca" for="customs_clearance_fee"><?php echo $this->lang->line('customs_clearance_fee');?></label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="customs_clearance_fee" name="customs_clearance_fee" value="<?php if(isset($orderOut[0]['customs_clearance_fee'])) echo $orderOut[0]['customs_clearance_fee'];?>" onkeydown="return checkQuantity(event)">
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" style="color:#e43434" for="revise_date"><?php echo $this->lang->line('revise_date'); ?></label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
											<input type="text" class="form-control date" id="revise_date" name="revise_date" maxlength="10" value="<?php if(isset($orderOut[0]['revise_date'])) echo date_format(date_create($orderOut[0]['revise_date']), 'd M, Y');?>">
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="type"><?php echo $this->lang->line('type'); ?></label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="type_name" name="type_name" value="" readonly>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="supplier_name">
												<?php echo $this->lang->line('supplier_name'); ?>
											</label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="supplier_name" name="supplier_name" value="<?php if(isset($orderOut[0]['supplier_name'])) echo $orderOut[0]['supplier_name']; ?>" readonly>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="reference"><?php echo $this->lang->line('reference'); ?></label>
										</div>
										<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="reference" name="reference" value="<?php if(isset($orderOut[0]['reference'])) echo $orderOut[0]['reference'];?>" readonly>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px">
										<div class="col-md-3 col-sm-3 col-xs-3">
											<label class="control-label form-title" for="address">
												<?php echo $this->lang->line('address'); ?>
											</label>
										</div>
										<div class="col-md-9 col-sm-9 col-xs-9 no-padding-left no-padding-right">
											<textarea spellcheck="false" type="text" style="resize: none; height: 78px;" class="form-control" id="address" name="address" readonly><?php if(isset($orderOut[0]['address']) && $orderOut[0]['address'] != null){ echo $orderOut[0]['address']; }?></textarea>
										</div>
									</div>
								</div>
								<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
									<div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="control-label form-title" for="order_user">
												<?php echo $this->lang->line('order_user'); ?>
												<span class="required">*</span>
											</label>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
											<select class="form-control validate[required]" id="order_user" name="order_user">
												<option value=""></option>
												<?php 
													foreach ($orderUserList as $employee) {
														$selected = "";
														if (isset($orderOut[0]['order_user']))
															if (trim($orderOut[0]['order_user']) == trim($employee['employee_id'])) $selected = "selected";
														echo "<option value='" . $employee['employee_id'] . "' ".$selected.">" . $employee['full_name'] . "</option>";
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="control-label form-title" for="order_date"><?php echo $this->lang->line('order_date'); ?>
											<span class="required">*</span>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
											<input type="text" class="form-control date validate[required]" id="order_date" name="order_date" maxlength="10" value="<?php if(isset($orderOut[0]['order_date'])) echo date_format(date_create($orderOut[0]['order_date']), 'd M, Y');?>">
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="control-label form-title" for="shipping_mark">
												<?php echo $this->lang->line('shipping_mark'); ?>
											</label>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
											<textarea spellcheck="false" type="text" style="resize: none; height: 78px;" class="form-control" id="shipping_mark" name="shipping_mark" maxlength="100" ><?php if(isset($orderOut[0]['shipping_mark'])) echo $orderOut[0]['shipping_mark'];?></textarea>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="control-label form-title" style="color:#428bca" for="transport_fee">
												<?php echo $this->lang->line('transport_fee'); ?>
											</label>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="transport_fee" name="transport_fee" value="<?php if(isset($orderOut[0]['transport_fee'])) echo $orderOut[0]['transport_fee'];?>" onkeydown="return checkQuantity(event)">
											<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="control-label form-title" for="accept_user">
												<?php echo $this->lang->line('accept_user'); ?>
											</label>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="accept_user" name="accept_user" value="<?php if(isset($orderOut[0]['accept_user'])) echo $orderOut[0]['accept_user'];?>" readonly>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="control-label form-title" for="accept_date">
												<?php echo $this->lang->line('accept_date'); ?>
											</label>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="accept_date" name="accept_date" value="<?php if(isset($orderOut[0]['accept_date'])) echo $orderOut[0]['accept_date'];?>" readonly>
										</div>
									</div>
									<div class="form-group">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="control-label form-title" for="edit_user">
												<?php echo $this->lang->line('edit_user'); ?>
											</label>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="edit_user" name="edit_user" value="<?php if(isset($orderOut[0]['edit_user'])) echo $orderOut[0]['edit_user'];?>" readonly>
										</div>
									</div>
									<div class="form-group" style="margin-bottom:0px">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label class="control-label form-title" for="edit_date">
												<?php echo $this->lang->line('edit_date'); ?>
											</label>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
											<input type="text" class="form-control" id="edit_date" name="edit_date" value="<?php if(isset($orderOut[0]['edit_date'])) echo $orderOut[0]['edit_date'];?>" readonly>
										</div>
									</div>
									<div class="form-group">
											<input type="hidden" class="form-control" id="po_quant" name="po_quant" value="<?php if(isset($orderOut[0]['quantity'])) echo $orderOut[0]['quantity'];?>">
											<input type="hidden" class="form-control" id="amount" name="amount">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div class="x_content">
					<div class="table-responsive">
						<table id="products_list_tbl" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>No</th>
									<th>
										PV No
									</th>
									<th>
										<?php echo $this->lang->line('item_code'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('item_name'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('size'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('color'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('quantity'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('unit'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('buy_price'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('amount'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('identify_name'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('type'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('supplier'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('sales_man'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('surcharge'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('note'); ?>
									</th>
									<th>
										<?php echo $this->lang->line('action'); ?>
									</th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="col-sm-2" style="margin-top: 10px;">
						<input type="button" id = "show_product_search_modal" class="btn btn-primary" data-toggle="modal" data-target="#product_search_modal" value="<?php echo $this->lang->line('add_item'); ?>"
						/>
					</div>
				</div>
				<!-- /form input mask -->
			</div>
		</div>
	</div>
</div>
<!--Popover DeliveryDate history-->
<div id="history_delivery_date" style="width:40%; position:absolute; top: -2000px">
    <div class="popover-heading">Change history <span style="float:right;cursor:pointer;" class="fa fa-times close" data-toggle="popover"></span></div>
    <div class="popover-body">
        <div class="table-responsive">
            <table id="history_list" class="table table-striped table-bordered" cellspacing="0">
                <thead >
                    <tr>
                      <!-- <th class="no_sort" width="5%">No</th> -->
                      <th width="40%"><?php echo $this->lang->line('change_date'); ?></th>
                      <th width="30%"><?php echo $this->lang->line('delivery_date'); ?></th>
                      <th width="30%"><?php echo $this->lang->line('user_id'); ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--End Popover history-->
<!-- Modal -->
<!-- Search -->
<div id="product_search_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="min-width: 70%;">
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
						<button id="insertProduct" class="btn btn-primary">
							<i class="fa fa-plus"></i> IT
							<?php echo $this->lang->line('new'); ?>
						</button>
					</li>
				</ul>
				<h2>
					<?php echo $this->lang->line('search_product'); ?>
				</h2>
			</div>
			<div class="modal-body">
				<form id="product_search_form" class="form-horizontal form-label-left">
					<!-- Item code AND Item Name -->
					<div class="form-group">
					<label class="control-label col-xs-1" for="search_pv_no">
							<?php echo "PV No"; ?>
						</label>
						<div class="col-xs-2 has-clear has-feedback">
							<input type="text" class="form-control text-uppercase" id="search_pv_no" name="pv_no" maxlength="30" value="">
							<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
						</div>

						<label class="control-label col-xs-1" for="search_item_code">
							<?php echo $this->lang->line('item_code'); ?>
						</label>
						<div class="col-xs-2 has-clear has-feedback">
							<input type="text" class="form-control text-uppercase" id="search_item_code" name="item_code" maxlength="30" value="">
							<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
						</div>

						<label class="control-label col-xs-1" for="search_item_name">
							<?php echo $this->lang->line('item_name'); ?>
						</label>
						<div class="col-xs-2">
							<input type="text" class="form-control" id="search_item_name" name="item_name" maxlength="100" value="">
						</div>

						<div class="col-xs-1 text-right">
							<input type="radio" class="flat" name="search_from" value="1" checked>
						</div>
						<label class="col-xs-2 text-left" for="search_from">
							<?php echo ('From PV'); ?>
						</label>
					</div>

					<!-- Saleman and Supplier And Search -->
					<div class="form-group">
						<label class="control-label col-xs-1" for="size">
							<?php echo $this->lang->line('size'); ?>
						</label>
						<div class="col-xs-2">
							<input type="text" id="search_size" name="size" maxlength="20" class="form-control" value=""></input>
						</div>
						<label class="control-label col-xs-1" for="color">
							<?php echo $this->lang->line('color'); ?>
						</label>
						<div class="col-xs-2">
							<input type="text" id="search_color" name="color" maxlength="20" class="form-control" value=""></input>
						</div>
						<label class="control-label col-xs-1" for="search_saleman">
							<?php echo $this->lang->line('sales_man'); ?>
						</label>
						<div class="col-xs-2">
							<input type="text" id="search_saleman" name="saleman" maxlength="20" class="form-control text-uppercase" value=""></input>
						</div>
						<div class="col-xs-1 text-right">
							<input type="radio" class="flat" name="search_from" value="2">
						</div>
						<label class="col-xs-2 text-left" for="search_from">
							<?php echo ('From item master'); ?>
						</label>
					</div>
					<!-- Input Date (From And To) -->
					<div class="form-group">
						<label class="control-label col-xs-1" for="search_input_date">
							<?php echo $this->lang->line('input_date'); ?>
						</label>
						<div class="col-xs-2">
								<input type='text' class="form-control date" name='from_date' maxlength="10" />
						</div>

						<label class="control-label col-xs-1 no-padding-left no-padding-right no-padding-top" for="created_to" style="text-align: center; font-size: 20px">∼</label>
						<div class="col-xs-2">
								<input type='text' class="form-control date" name='to_date' maxlength="10" />
						</div>
						<label class="control-label col-xs-1" for="search_supplier">
							<?php echo $this->lang->line('supplier'); ?>
						</label>
						<div class="col-xs-2">
							<input type="text" id="search_supplier" name="supplier" maxlength="100" class="form-control" value=""></input>
						</div>
						<div class="col-xs-3 text-right">
							<a class="action-search btn btn-info ">
								<i class="fa fa-search"></i>
								<?php echo $this->lang->line('search'); ?>
							</a>
						</div>
					</div>
				</form>
				<div class="x_title">
					<div class="clearfix"></div>
				</div>
				<div class="table-responsive">
					<table id="products_list_search" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>
									<?php echo $this->lang->line('order_received_no'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('item_code'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('item_name'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('size'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('color'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('quantity'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('unit'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('buy_price'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('currency'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('supplier'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('sales_man'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('action'); ?>
								</th>
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
<!-- insert item -->
<div id="insert_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg" style="min-width: 70%;">
		<div class="modal-content">
			<div class="modal-header">
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<button class="btn btn-info" id="close_insert_item">
							<i class="fa fa-arrow-left"></i>
							<?php echo $this->lang->line('back'); ?>
						</button>
					</li>
					<li>
						<button class="btn btn-primary" id="save_item">
							<i class="fa fa-save"></i>
							<?php echo $this->lang->line('save'); ?>
						</button>
					</li>
				</ul>
				<h2>
					<?php echo $this->lang->line('add_new_product'); ?><span id="insert_error" style="margin-left: 50px; font-size: 14px; color:red; display:none"></span>
				</h2>
			</div>
			<div class="modal-body">
				<div class="x_panel">
					<div class="x_content">
						<form class="form-horizontal myform" action="" method="post" name="frm-product" id="frm-product">
							<div class="form-group">
								<div class="col-xs-2">
										<label class="control-label" for="item_code"><?php echo $this->lang->line('item_code'); ?><span class="required">*</span></label>
								</div>
								<div class="col-xs-2">
										<input type="text" class="validate[required] form-control text-uppercase" id="item_code" name="item_code" maxlength="15">
								</div>
								<div class="col-xs-2">
										<label class="control-label" for="item_name"><?php echo $this->lang->line('item_name'); ?><span class="required">*</span></label>
								</div>
								<div class="col-xs-6">
									<input type="text" class="form-control validate[required]" id="item_name" name="item_name" maxlength="100">
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-2">
									<label class="control-label" for="jp_code"><?php echo $this->lang->line('code_jp'); ?></label>
								</div>
								<div class="col-xs-2">
									<input type="text" class="form-control text-uppercase" id="insert_jp_code" name="jp_code" maxlength="15">
								</div>
								<div class="col-xs-2">
									<label class="control-label" for="salesman"><?php echo $this->lang->line('sales_man'); ?></label>
								</div>
								<div class="col-xs-2">
									<input type="text" class="form-control" id="insert_sales_man" name="salesman">
								</div>
								<div class="col-xs-2">
										<label class="control-label" for="vendor"><?php echo $this->lang->line('vender'); ?></label>
								</div>
								<div class="col-xs-2">
									<input type="text" class="form-control" id="insert_vendor" name="vendor">
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-2">
									<label class="control-label" for="size"><?php echo $this->lang->line('size'); ?></label>
								</div>
								<div class="col-xs-2">
									<input type="text" class="form-control" id="insert_size" name="size">
								</div>
								<div class="col-xs-2">
									<label class="control-label" for="unit"><?php echo $this->lang->line('unit'); ?><span class="required">*</span></label>
								</div>
								<div class="col-xs-2">
									<input type="text" class="form-control" id="insert_unit" name="unit">
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-2">
									<label class="control-label" for="color"><?php echo $this->lang->line('color'); ?></label>
								</div>
								<div class="col-xs-2">
									<input type="text" class="form-control" id="insert_color" name="color">
								</div>
								<div class="col-xs-2">
									<label class="control-label" for="size_unit"><?php echo $this->lang->line('size_unit'); ?></label>
								</div>
								<div class="col-xs-2">
									<input type="text" class="form-control" id="insert_size_unit" name="size_unit">
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-2">
										<label class="control-label" for="buy_price_usd">Buy Price US$</label>
								</div>
								<div class="col-xs-2">
										<input type="number" class="form-control" id="insert_buy_price_us" name="buy_price_usd">
								</div>
								<div class="col-xs-2">
										<label class="control-label" for="sell_price_usd">Sell Price US$</label>
								</div>
								<div class="col-xs-2">
									<input type="number" class="form-control" id="insert_sell_price_us" name="sell_price_usd">
								</div>
								<div class="col-xs-2">
										<label class="control-label" for="base_price_usd">Base Price US$</label>
								</div>
								<div class="col-xs-2">
									<input type="number" class="form-control" id="insert_base_price_us" name="base_price_usd">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var dataTB;
	var currentItem;
	var max_detail_no = "0";
	var no = "";
	var stop_update = false;
	var supplierList = <?php echo json_encode($supplierList)?>;
	var orderOut = <?php echo (isset($orderOut) ? json_encode($orderOut[0]) : 'null'); ?>;
	function aftercheck() {
		max_detail_no = parseFloat(max_detail_no) + 1 ;
        var surcharge_text = ""; //(currentItem.surcharge_color_jpy || currentItem.surcharge_color_usd || currentItem.surcharge_color_vnd ? 'Surcharge by 1 Color,' : '')
        //                     + (currentItem.surcharge_unit_color_jpy || currentItem.surcharge_unit_color_usd || currentItem.surcharge_unit_color_vnd ? 'Surcharge by 1 UNIT/COLOR,' : '')
        //                     + (currentItem.po_amount_min_jpy || currentItem.po_amount_min_usd || currentItem.po_amount_min_vnd ? 'PO\'s amount decide Surcharge,' : '');
		var data = {
			// no 								: 	no,
			order_detail_no			: max_detail_no,
			odr_recv_no					:	currentItem.order_receive_no ? currentItem.order_receive_no : null,
			partition_no				: currentItem.partition_no ? currentItem.partition_no : null,
			odr_recv_date				: currentItem.order_receive_date ? currentItem.order_receive_date : null,
			odr_recv_detail_no	: currentItem.order_receive_detail_no ? currentItem.order_receive_detail_no : null,
			composition_1				: currentItem.composition_1,
			composition_2				: currentItem.composition_2,
			composition_3				: currentItem.composition_3,
			size_unit						: currentItem.size_unit,
			item_code						: currentItem.item_code,
			item_name						: currentItem.item_name,
			size								:	currentItem.size,
			color								: currentItem.color,
			odr_quantity				:	currentItem.quantity,
			unit								: currentItem.unit,
			currency						: currentItem.currency,
			price								: currentItem.sell_price||0,
			amount							: (currentItem.quantity && currentItem.sell_price) ? (currentItem.quantity * currentItem.sell_price) : 0,
			warehouse						: currentItem.identify_name,
			item_type 		    	:  null,
			vendor							: currentItem.vendor,
			salesman						: currentItem.staff,
			surcharge_po				: currentItem.surcharge_po,
			note								: currentItem.note_lapdip || '',
			action							: null,
      surcharge_text   		: surcharge_text
			// add_mode					: 	true,
		};
		data.edit_mode = true;
		var rowNode = $('#products_list_tbl').DataTable()
			.row.add(data)
			.draw()
			.node();
		$( rowNode )
			.addClass('added');
      ordersListTable.page('last').draw('page');
				// var address = (currentItem.company_name ? (currentItem.company_name + '\n') : '');
				var address = "";
				address += (currentItem.head_office_address ? currentItem.head_office_address : '') +'\n';
				address += 'TEL: ' + (currentItem.head_office_phone ? currentItem.head_office_phone : '');
				address += '	FAX: ' + (currentItem.head_office_fax ? currentItem.head_office_fax : '');
				if(currentItem.vendor != null && currentItem.vendor != ''){
					$('#frm_order').find('input[name=supplier_name]').val(currentItem.vendor);
					// $('#frm_order').find('input[name=supplier_name_display]').val(currentItem.short_name);
				}
			var numberRows = ordersListTable.data().count();
			if(numberRows == 1){
				$('#address').val(address);
				$('#frm_order').find('input[name=reference]').val(currentItem.reference);	
			}
	}
		
	// handle select button
	function selectItem(element, allFlg) {
		allFlg = allFlg || false;
		var item = null;
		if(!allFlg){
			item = $('#products_list_search').DataTable().row( $(element).parents('tr') ).data();
		}else{
			item = $('#products_list_search').DataTable().row(0).data();
			var sumQuantity = $('#sum_quantity').text();
			if($.isNumeric(sumQuantity)){
				item.quantity = sumQuantity;
			}
		}
		var insert_update = $('#insert_update').val();
		var delivery_date = new Date(item.delivery_date);
		$("#delivery_date").datepicker('setDate',delivery_date);
		$('#product_search_modal').modal('hide');
		$('#show_product_search_modal').attr('disabled', true);
		// validate insert only 1 item for 1 order 
		item.size = item.size || '';
		item.color = item.color || '';
		var data_table = $('#products_list_tbl').DataTable().data().toArray();
		if(data_table.length > 0){
			for(var i = 0 ; i < data_table.length; i++ ){
				var item_code = data_table[i].item_code || '',
						size = data_table[i].size || '',
						color = data_table[i].color || '';
				if(item_code == item.item_code && size == item.size && color == item.color){
					bootbox.alert("<?php echo $this->lang->line('POD0020_E003');?>");
					$('#show_product_search_modal').attr('disabled', false);
					return;
				}
			}
		}

		currentItem = item;
		if (!currentItem) {
			bootbox.alert("<?php echo $this->lang->line('POD0020_E001'); ?>");
			$('#show_product_search_modal').attr('disabled', false);
			return;
		}
		if((currentItem.vendor == null || currentItem.vendor =='') && data_table.length > 0){
			currentItem.vendor = data_table[0].vendor;
		}
		if(data_table.length == 0 || $("#currency").val() == ""){
			if (currentItem.currency != null && currentItem.currency != "") {
				$("#currency").val(currentItem.currency);
			}
		}
		// if((currentItem.short_name == null || currentItem.short_name == '') && data_table.length > 0){
		// 	currentItem.short_name = data_table[0].short_name;
		// }
		if(insert_update == 1 && !stop_update){
			getMaxDetailNo();
		}
		else {	
			aftercheck();
		}
	}
	function getMaxDetailNo() {
		var url = "<?php echo base_url()?>orders/getDetailNo";
			var requestData = {};
				requestData['order_no_1'] = $('#po_no1').val();
				requestData['order_no_2'] = $("#po_no2 option:selected").text();
				requestData['order_no_3'] = $('#po_no3').val();
				requestData['order_no_4'] = $('#po_no4').val();
				requestData['order_no_5'] = $('#po_no5').val();
				requestData['order_no_6'] = $('#po_no6').val();
				requestData['buyer_kb'] = $('input[name="buyer_kb"]').val();
			$.post(url, requestData, function(response){
				console.log(response);
				var responseJson = JSON.parse(response);
				if(responseJson.data){
					max_detail_no = responseJson.data[0].order_detail_no ;
				}else{
					max_detail_no = 0;
				}
				stop_update = true;
				aftercheck();
			});
	}
	var ordersListTable;
	var salesmanList = <?php echo json_encode($salesman_list);?>;
	var supplierList = <?php echo json_encode($supplierList);?>;
	var sizeList = <?php echo json_encode($size_list);?>;
	var unitList = <?php echo json_encode($unit_list);?>;
	var colorList = <?php echo json_encode($color_list);?>;
	var sizeUnitList = <?php echo json_encode($size_unit_list);?>;
	window.onload = function () {
		// Set autoComplete
		$("#insert_sales_man").autocomplete({
        lookup: salesmanList.map(function(ele){
					return {
						value : ele.komoku_name_2
					}
				}),
        minChars: 0,
        onSelect: function(data){
            $(this).change();
        }
    });
		$("#insert_vendor").autocomplete({
        lookup: supplierList.map(function(ele){
					return {
						value : ele.short_name
					}
				}),
        minChars: 0,
        onSelect: function(data){
            $(this).change();
        }
    });
		$("#insert_size").autocomplete({
        lookup: sizeList.map(function(ele){
					return {
						value : ele.komoku_name_2
					}
				}),
        minChars: 0,
        onSelect: function(data){
            $(this).change();
        }
    });
		$("#insert_unit").autocomplete({
        lookup: unitList.map(function(ele){
					return {
						value : ele.komoku_name_2
					}
				}),
        minChars: 0,
        onSelect: function(data){
            $(this).change();
        }
    });
		$("#insert_color").autocomplete({
        lookup: colorList.map(function(ele){
					return {
						value : ele.komoku_name_2
					}
				}),
        minChars: 0,
        onSelect: function(data){
            $(this).change();
        }
    });
		$("#insert_size_unit").autocomplete({
        lookup: sizeUnitList.map(function(ele){
					return {
						value : ele.komoku_name_2
					}
				}),
        minChars: 0,
        onSelect: function(data){
            $(this).change();
        }
    });
		// Set event for copy button
		$("#btnCopy").on("click", function(){
			$(this).attr("disabled", true);
			$("#btnSaveOdersOut").text("Save As");
			// Enable readonly textbox
			$("input[type='radio']").next().css("pointer-events", "auto");
			$('#order_user,#order_date,#po_no2,'
					+'#currency,#inv_no,#shipping_mark,'
					+'#cont_no,#customs_clearance_sheet_no,'
					+'#customs_clearance_fee, #transport_fee,#revise_date').attr('readonly', false);
			$("#order_user,#order_date,#po_no2").css("pointer-events", "auto");
			$("#show_product_search_modal").attr("disabled", false);
			$("#products_list_tbl .btn").attr("disabled", false);
			// gernerate new po no
			let requestData = {};
			requestData.buyer_kb = orderOut.buyer_kb;
			$.ajax({
					url: "<?php echo base_url('orders/getPONo')?>",
					data: requestData,
					type: 'post',
					dataType: 'json',
					async: true
			}).done(function(response) {
				$('#po_no4').val(response.order_no_4);
			});
			$("#insert_update").val("0");
			isAddNew = true;
		});

		let history = '<?php echo isset($history_list)?json_encode($history_list):""; ?>';
		let dataSet = "";
		if(history){
			dataSet = JSON.parse(history);
		}
		//Config popover history
		$("[data-toggle=popover]").popover({
			container: "body",
			html: true,
			content: function() {
					$("#history_list").DataTable().destroy();
					tmpTable = $("#history_list").DataTable({
							ordering: false,
							dom: "ti",
							scrollX: true,
							scrollY: false,
							paging: false,
							data: dataSet,
							"columns": [
								{ "data": '2'},
								{ "data": '1'},
								{ "data": '0'},
							]
					});
					var content = $(this).attr("data-popover-content");
					return $(content).children(".popover-body").html();
			},
			title: function() {
					var title = $(this).attr("data-popover-content");
					return $(title).children(".popover-heading").html();
			}
	});
	$("[data-toggle=popover]").popover().on("click", function () {//mouseenter
			var _this = this;
			$(this).popover("show");
			$(".popover").on("mouseleave", function () {
					$(_this).popover('hide');
			});
	}).on("mouseleave", function () {
			var _this = this;
			setTimeout(function () {
					if (!$(".popover:hover").length) {
							$(_this).popover("hide");
					}
			}, 300);
	});
	$(document).on("click", ".close", function(){
			let idPopover = $(this).parents(".popover").attr("id");
			$("span[aria-describedby="+idPopover+"]").popover('hide');
	});
	// End popover
		$('#frm-product').validationEngine();
		if($('#insert_update').val() == 0){
			var lastDate = new Date();
			$("#order_date").datepicker('setDate', lastDate);
			lastDate.setDate(lastDate.getDate() + 7);
			$("#delivery_date").datepicker('setDate', lastDate);
			$("#order_user").val("<?php echo $curUser['employee_id']; ?>");
		}
		var flag_finish = 0;
		<?php if(isset($orderOut[0]['flag_finish'])):?>
				flag_finish = <?php echo $orderOut[0]['flag_finish'] ?>;
		<?php endif; ?>	
		var isAddNew = "<?php echo $isAddNew; ?>";
		if( flag_finish == '1' ){
			$('#frm_order input, select, textarea').attr('readonly', 'readonly');
			$("input[type='radio']").next().css("pointer-events", "none");
			$("#delivery_date").removeAttr("readonly");
			// $("#btnSaveOdersOut").hide();
			$("#show_product_search_modal").attr('disabled', true);
		}
		if(!isAddNew){
			$("input[type='radio']").next().css("pointer-events", "none");
			$('#order_user').attr('readonly', 'readonly');
			$("#order_user").css("pointer-events", "none");
			$('#order_date').attr('readonly', 'readonly');
			$("#order_date").css("pointer-events", "none");
			$('#po_no2').attr('readonly', 'readonly');
			$("#po_no2").css("pointer-events", "none");
		}
		$('input[name="buyer_kb"]').on('ifClicked', function (event) {
			if($(this).val() == 1){
				$('#buyer_nm').val('');
			}else if($(this).val() == 2){
				$('#buyer_nm').val('(HN)');
			}
			if (orderOut.buyer_kb && orderOut.buyer_kb.trim() == $(this).val().trim() && !isAddNew) {
				$('#po_no4').val(orderOut.order_no_4);
			} else {
				var requestData = {};
				requestData.buyer_kb = $(this).val();
				$.ajax({
							url: "<?php echo base_url('orders/getPONo')?>",
							data: requestData,
							type: 'post',
							dataType: 'json',
							async: true
					}).done(function(response) {
						$('#po_no4').val(response.order_no_4);
					});
			}
		});
		var insert_update = $('#insert_update').val();
		var po_no1 = $('#po_no1').val(),
			po_no2 = $("#po_no2 option:selected").text(),
			po_no3 = $('#po_no3').val(),
			po_no4 = $('#po_no4').val(),
			po_no5 = $('#po_no5').val(),
			po_no6 = $('#po_no6').val();
		// create datatable
		ordersListTable = $('#products_list_tbl').DataTable({
				"data": [],
				"initComplete": getType,
				"paging": true,
				"filter": false,
				"ordering": false,
				"scrollX" :true,
				"dom": 'l<"summary_info">rtip',
				"drawCallback": function() {
            reload_summary_info();
        },
				"ajax": '<?php echo base_url("orders/tableDetailOrderOut/".$orderOut[0]['order_no_1'].
					"/".$orderOut[0]['order_no_2']."/".$orderOut[0]['order_no_3'].
					"/".$orderOut[0]['order_no_4']."/".$orderOut[0]['order_no_5'].
					"/".$orderOut[0]['buyer_kb']."/".trim($orderOut[0]['order_no_6'])); ?>',
						"columns": [
					{ "data": 'no', render: function( data, type, row, meta){
								return ( parseInt(meta.row) + 1);
						}},
						{ "data": 'odr_recv_no', render: function( data, type, row, meta){
								return row.odr_recv_no;
						}},
						{ "data": "item_code",className: "text-left", render: function( data, type, row, meta ){
								return row.item_code;
						}},
						{ "data": "item_name",className: "text-left", render: function( data, type, row, meta ){
								return row.item_name;
						}},
						{ "data": "size", render: function( data, type, row, meta ){
								return row.size;
						}},
						{ "data": "color", render: function( data, type, row, meta ){
								return row.color;
						}},
						{ "data": "odr_quantity",className: "text-right", render: function( data, type, row, meta ){
								if (row.edit_mode){
										var $input = $('<input>')
												.attr('type', 'text')
												.attr('value', parseFloat(row.odr_quantity))
												.attr('class', 'form-control datatable-input')
												.attr('name', 'quantity')
												.attr('min', '0')
												.attr('onkeydown', 'return checkQuantity(event)')
												.css('max-width', '100px');
										return $input[0].outerHTML;
								} else {
										return numberWithCommas(parseFloat(row.odr_quantity ? row.odr_quantity : '0'));
								}
						}},
						{ "data": "unit", render: function( data, type, row, meta ){
								return row.unit;
						}},
						{ "data": "price",className: "text-right", render: function( data, type, row, meta ){
							let currency = row.currency;
							if($("#currency").val() !== ""){
								currency = $("#currency").val();
							}
								if (row.edit_mode){
										var $input = $('<input>')
												.attr('type', 'text')
												.attr('value', row.price != 0 ? parseFloat(row.price).myToFixed(currency) : "")
												.attr('class', 'form-control datatable-input')
												.attr('name', 'price')
												.attr('min', '0')
												.attr('onkeydown', 'return checkQuantity(event)')
												.css('max-width', '80px');
										return $input[0].outerHTML;
								} else {
										return numberWithCommas(parseFloat(row.price ? row.price : '0').myToFixed(currency));
								}
						}},
						{ "data": "amount",className: "text-right", render: function( data, type, row, meta ){
							let currency = row.currency;
							if($("#currency").val() !== ""){
								currency = $("#currency").val();
							}
							if(row.amount){
								var amount = parseFloat(row.amount);
								return numberWithCommas(amount.myToFixed(currency));
							}      
							return row.amount;
						}},
						{ "data": "warehouse",className: "text-left", render: function( data, type, row, meta ){
							var array = <?php echo json_encode($warehouseList)?>;
							if (row.edit_mode){
								var $temp = '<option></option>';
								for (let index = 0; index < array.length; index++) {
									const element = array[index];
									$temp += '<option ' + (element.kubun == row.warehouse ?  'selected' : '') + ' value='+element.kubun+'>'+element.komoku_name_2+'</option>'
								}
								var html = '<select name="warehouse" id="warehouse" class="form-control" style="max-width:100px;height:30px;">'
																		+ $temp
																		+ '</select>';
									return html;
							} else {
									for (let index = 0; index < array.length; index++) {
										const element = array[index];
										if(element.kubun == row.warehouse){
											return element.komoku_name_2;
										}
									}
								return '';
							}
						}},
						{ "data": "type_name", render: function( data, type, row, meta ){
							var array = <?php echo json_encode($items_list)?>;
							if (row.edit_mode){
								var $temp = '<option value=""'+'</option>';
								for (let index = 0; index < array.length; index++) {
									const element = array[index];
									$temp += '<option ' + (element.kubun == row.item_type ?  'selected' : '') + ' value='+element.kubun+'>'+element.komoku_name_2+'</option>'
								}
								var html = '<select name="item_type" id="item_type" class="form-control" style="max-width:100px;height:30px;">'
																		+ $temp
																		+ '</select>';
														return html;
							} else {
								for (let index = 0; index < array.length; index++) {
									const element = array[index];
									if(element.kubun == row.item_type){
										return element.komoku_name_2;
									}
								}
								return '';
							}
						}},
						{ "data": "supplier_name",className: "text-left", render: function( data, type, row, meta ){
							var array = <?php echo json_encode($supplierList)?>;
							var table = $('#products_list_tbl').DataTable();
							if (row.edit_mode && table.data().count() == 1){
								var $temp = '<option value=""'+'</option>';
								for (let index = 0; index < array.length; index++) {
									const element = array[index];
										$temp += '<option value="'+element.company_id+'"'+(row.vendor && row.vendor.trim() == element.short_name.trim() ? 'selected' : '')+'>'+element.short_name+'</option>'
								}
								var html = '<select name="vendor" id="vendor" class="form-control" style="max-width:100px;height:30px;">'
																		+ $temp
																		+ '</select>';
														return html;
							}
								return row.vendor;
						}},
						// test 
						{ "data": "salesman",className: "text-left", render: function( data, type, row, meta ){
								return row.salesman;
						}},
						{ "data": "surcharge_po",className: "text-left", render: function( data, type, row, meta ){
							if (row.edit_mode){
								var $input = $('<input>')
												.attr('type', 'text')
												.attr('value', row.surcharge_po)
												.attr('class', 'form-control')
												.attr('name', 'surcharge_po')
												.css('max-width', '120px')
												.css('height', '30px');
										return $input[0].outerHTML;
							}
                return '<div class="ellipsis" title="'+ (row.surcharge_text ? row.surcharge_text : '') + (row.surcharge_po ? row.surcharge_po : '') +'">'+ (row.surcharge_text ? row.surcharge_text : '') + (row.surcharge_po ? row.surcharge_po : '')+'</div>';
						}},
						{ "data": "note",className: "text-left", render: function( data, type, row, meta ){
								if (row.edit_mode){
										var $input = $('<input>')
												.attr('type', 'text')
												.attr('value', row.note)
												.attr('class', 'form-control')
												.attr('name', 'note')
												.attr('maxlength', '100')
												.css('max-width', '80px')
												.css('height', '30px');
										return $input[0].outerHTML;
								} else {
										return row.note;
								}
						}},
						{ "data": "action", render: function( data, type, row, meta ){
								var html = '';
								if (row.edit_mode) {
										html += '<a data-event="save-item" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
												+ '<span class="fa fa-check"></span>'
												+ '</a>';
										html += '<a data-event="close-item" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
												+ '<span class="fa fa-close"></span>'
												+ '</a>';
								} else {
										html += '<button data-event="edit-item" class="btn btn-xs btn-primary" '+ ((flag_finish && flag_finish == '1' ) ? 'disabled' : '' )+' title="<?php echo $this->lang->line('edit'); ?>">'
							+ '<span class="fa fa-edit"></span>'
							+ '</button>';
										html += '<button data-event="delete-item" style="margin-left: 3px;" class="btn btn-xs btn-danger" '+ ((flag_finish && flag_finish == '1' ) ? 'disabled' : '' )+' title="<?php echo $this->lang->line('delete'); ?>">'
												+ '<span class="fa fa-trash"></span>'
												+ '</button>';
								}
								return html;
						} },
				],
		} );
		$('#products_list_tbl').on( 'draw.dt', function () {
			var data = $('#products_list_tbl').DataTable().rows().data();
			if(data.length > 0) {
				var currency = data[0].currency;
				if($("#currency").val() !== ""){
					currency = $("#currency").val();
				}
				var totalAmount = data.reduce(function(prev, cur) {
					return prev + parseFloat(cur.amount);
				}, 0);
				var totalQuantity = data.reduce(function(prev, cur) {
					return prev + parseFloat(cur.odr_quantity);
				}, 0);
				$('#amount').val(totalAmount.myToFixed(currency));
				$('#po_quant').val(totalQuantity);
			}
		});
		// set value input type
		function getType(){
			var arrType = [];
			var type = "";
			var type_name = "";
			var type_all = "";
			var rows = ordersListTable.rows().data();
			var array = <?php echo json_encode($items_list)?>;
			
			rows.each(function(value, index){
				type = value.item_type ;
				var array_check = arrType.includes(type);
				if(array_check == false){
					for(let index = 0; index < array.length; index++) {
						const element = array[index];
						if(element.kubun == type){
							type_name = element.komoku_name_2;
						}
					}
					if(type_all == ""){
						type_all = type_name ;
					}else{
						type_all = type_all+'+'+type_name;
					}
					arrType.push(type);
				}
			});
			
			$('#frm_order').find('input[name=type_name]').val(type_all);
		}

		$('#products_list_tbl').on('click', '[data-event="save-item"]', function(){
			var $row 						= $(this).parents('tr');
			var data 						= $('#products_list_tbl').DataTable().row($row).data();
			data.odr_quantity 	= $row.find('input[name="quantity"]').val();
			data.price 					= $row.find('input[name="price"]').val();
			data.amount 				= data.odr_quantity * data.price ;
			data.warehouse			= $row.find('select[name="warehouse"]').val();

			var table = $('#products_list_tbl').DataTable();
			if (table.data().count() == 1){
					data.vendor		= $row.find('input[name=supplier_name]').val();
			}
			
			var surchargeColor = '';
			var surchargeUnitColor = '';
			var surchargePO = '';

			var requestData = {};
					requestData.item_code = data.item_code;
					requestData.size = data.size;
					requestData.color = data.color;
					requestData.odr_quantity = data.odr_quantity;
				$.ajax({
						url: "<?php echo base_url('orders/getSurchrge')?>",
						data: requestData,
						type: 'post',
						dataType: 'json',
						async: false
				}).done(function(response) {
					// if(response.data && response.data.length > 0){
						response.data.forEach(function(ele){
						  surchargeColor = (ele.surcharge_color_jpy || ele.surcharge_color_usd || ele.surcharge_color_vnd ? 'Surcharge by 1 Color' : '');
              surchargeUnitColor = (ele.surcharge_unit_color_jpy || ele.surcharge_unit_color_usd || ele.surcharge_unit_color_vnd ? 'Surcharge by 1 UNIT/COLOR' : '');
              surchargePO = (ele.surcharge_po ? ele.surcharge_po : '');
						});
						data.surcharge_text = (surchargeUnitColor.trim() !== '' ? (surchargeUnitColor + '; ') : '' ) + (surchargeColor.trim() !== '' ? (surchargeColor + ';') : '' );
					// }
				});
			if(	$row.find('input[name="surcharge_po"]').val().trim() === '' || (surchargePO === '')){
				$row.find('input[name="surcharge_po"]').val(surchargePO);
			}
			data.surcharge_po			= $row.find('input[name="surcharge_po"]').val();
			if(data.vendor == null || data.vendor == ''){
				data.vendor			= $row.find('select[name="vendor"] option:selected').text();
				company = searchCompany( $row.find('select[name="vendor"]').val(), supplierList);
				data.company_name = company.company_name;
				// data.short_name = company.short_name;
				// var address = (company.company_name ? (company.company_name + '\n') : '');
				var address = "";
						address += (company.head_office_address ? company.head_office_address : '') +'\n';
						address += 'TEL: '+(company.head_office_phone ? company.head_office_phone : '');
						address += '	FAX: '+(company.head_office_fax ? company.head_office_fax : '');
				var numberRows = ordersListTable.data().count();
				if(numberRows == 1){
					$('#frm_order').find('input[name=supplier_name]').val(data.vendor);
					$('#address').val(address);
					$('#reference').val(company.reference);
				}
				// $('#frm_order').find('input[name=supplier_name_display]').val(data.short_name);
			}
			var array = <?php echo json_encode($warehouseList)?>;
			for (let index = 0; index < array.length; index++) {
				const element = array[index];
				if(data.warehouse == element.kubun){
					data.warehouse_name = element.komoku_name_2;
				}
			}
			data.item_type 		= $row.find('select[name="item_type"]').val();
			
			// Check validate for table
			if(data.warehouse == "" || data.item_type == "" || data.vendor == "" || (data.odr_quantity == "" || data.odr_quantity == 0) || data.price == "" || data.price == 0){

				if(data.warehouse == ""){
					$row.find('select[name=warehouse]').focus();
					$row.find('select[name=warehouse]').css('border-color', 'red');
				}else{
					$row.find('select[name=warehouse]').css('border-color', "");
				}
				if(data.item_type == ""){
					$row.find('select[name=item_type]').focus();
					$row.find('select[name=item_type]').css('border-color', 'red');
				}else{
					$row.find('select[name=item_type]').css('border-color', "");
				}
				if(data.vendor == ""){
					$row.find('select[name=vendor]').focus();
					$row.find('select[name=vendor]').css('border-color', 'red');
				}else{
					$row.find('select[name=vendor]').css('border-color', "");
				}
				if(data.odr_quantity == "" || data.odr_quantity == 0){
					$row.find('input[name="quantity"]').focus();
					$row.find('input[name="quantity"]').css('border-color', 'red');
				}else{
					$row.find('input[name="quantity"]').css('border-color', "");
				}
				if(data.price == "" || data.price == 0){
					$row.find('input[name="price"]').focus();
					$row.find('input[name="price"]').css('border-color', 'red');
				}else{
					$row.find('input[name="price"]').css('border-color', "");
				}
				return;
			}
			$('#show_product_search_modal').attr('disabled', false);
			data.note 		 = $row.find('input[name="note"]').val();
			data.edit_mode = false;
			var rowNode = ordersListTable
				.row($row)
				.data(data)
				.draw(false)
				.node();
			$(rowNode).addClass('edited-item');
			getType();
		});

		// Click delete in datatable
		$('#products_list_tbl').on('click', '[data-event="delete-item"]', function(){
				var $row = $(this).parents('tr'),
				rowData = ordersListTable.row($row).data(),
				requestData = {};
				requestData.kubun = rowData.kubun;
				requestData.komoku_id = rowData.komoku_id;
				
				bootbox.confirm({
						title: "<?php echo $this->lang->line('PLS0010_I005');?>",
						message: '<h4 style="color:red;">' + rowData.item_code + '</h4>', 
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
						callback: function (result) {
						if (result) {
							ordersListTable
							.row($row)
							.remove().draw();
							// if dataTable don't have any row return input supplier empty
							getType();
							if(ordersListTable.data().length == 0){
								$('#show_product_search_modal').attr('disabled', false);
								$('#product_search_form').find('input[name=supplier]').val('');
								$('#product_search_form').find('input[name=supplier]').attr('readonly', false);
								$('#insert_vendor').val('');
								$('#insert_vendor').attr('readonly', false);
								$('#frm_order').find('input[name=supplier_name]').val('');
								// $('#frm_order').find('input[name=supplier_name_display]').val('');
								$('#address').val('');
								$('#frm_order').find('input[name=reference]').val('');
							}
						}
					}
				});
		});

		// Click edit in datatable
		$('#products_list_tbl').on('click', '[data-event="edit-item"]', function(){
				var $row = $(this).parents('tr');
				var rowData = ordersListTable.row($row).data();
				rowData.edit_mode = true;
				ordersListTable.row($row).data(rowData).invalidate();
				ordersListTable.draw(false);
		});

		// Click close in datatable
			$('#products_list_tbl').on('click', '[data-event="close-item"]', function(){
					var $row = $(this).parents('tr');
					var rowData = ordersListTable.row($row).data();
					rowData.edit_mode = false;
					if((rowData.warehouse == null || rowData.warehouse == '') && (rowData.item_type == null || rowData.item_type == '') ){
						$('#supplier_name').val('');
						$('#supplier_name_display').val('');
						$('#address').val('');
						$('#reference').val('');
						ordersListTable.row($row).remove();
						ordersListTable.draw();
					}else{
						ordersListTable.row($row).data(rowData).invalidate();
						ordersListTable.draw( false ); 
					}
					$('#show_product_search_modal').attr('disabled', false);
			});

		$('#product_search_modal').on('shown.bs.modal', function () {
			var data_table = ordersListTable.data();
			if(data_table.length >= 1){
				var cell = data_table.toArray(),
						supplier = cell[0].vendor;
				$('#product_search_form').find('input[name=supplier]').val(supplier);
				$('#product_search_form').find('input[name=supplier]').attr('readonly', true);
				$('#insert_vendor').val(supplier);
				$('#insert_vendor').attr('readonly', true);
			}
			if (!dataTB) {
				dataTB = $("#products_list_search").DataTable({
					"data": [],
					"paging": true,
					"filter": false,
					"ordering": false,
					"scrollX" :true,
    			"serverSide": true,
					"processing": true,
					dom: '<"toolbar">frtip',
					"drawCallback": function() {
						var dt = dataTB.rows().data().toArray();
						if(dt && dt.length > 0){
							var sum = 0;
							for(let i = 0; i < dt.length; i ++){
								if(dt[i].quantity && $.isNumeric(dt[i].quantity)){
									sum += parseInt(dt[i].quantity);
								}
							}
							$("div.toolbar").css('text-align','center');
							$("div.toolbar").html('<span style="color: red">Sum Quantity : <span id="sum_quantity">'+sum+'</span><span> '+dt[0].unit+'</span></span><button class="btn btn-xs btn-primary" style="margin-left: 30px" onclick="selectItem(this, true)">SelectAll</button>');
						}
					},
					"ajax": {
						url: "<?php echo base_url("orders/searchProduct") ?>",
						type: 'post',
						data: function (data) {
							data.param = {};
							var arr = $('#product_search_form').serializeArray();
							$.each(arr, function (index, item) {
								if (item.value != '') {
									data.param[item.name] = item.value;
								}
							});
							var currency = $('#currency').val();
							data.param['currency'] = currency;
						}
					},
					columns: [
						{ data:'order_receive_no',className: "text-left", render: function(data){
							return '<a class="edit" onclick="searchPV(`'+data+'`)">'+(data)+'</a>';
						}},
						{
							data: 'item_code', className: "text-left"
						},
						{
							data: 'item_name', className: "text-left"
						},
						{
							data: 'size'
						},
						{
							data: 'color'
						},
						{ data:'quantity',className: "text-right", render: function(data, type, row, meta){
							return numberWithCommas(row.quantity);
						}},
						{
							data: 'unit'
						},
						{ data:'sell_price',className: "text-right", render: function(data, type, row, meta){
							return parseFloat(row.sell_price).myToFixed(row.currency);
						}},
						{
							data: 'currency'
						},
						{
							data: 'vendor', className: "text-left"
						},
						{
							data: 'staff', className: "text-left"
						},
						{
							data: 'action',
							className: 'text-center',
							render: function () {
								return '<a onclick="selectItem(this)" href="#" class="btn btn-xs btn-info">Select</a>';
							}
						},
					],
					
				});
			} else {
				dataTB.draw();
			}
		});

		$('#product_search_modal .action-search').click(function () {
			// $('#product_search_modal')
			dataTB.ajax.reload();
		});

		// Click button delete 
		$('#btnSaveOdersOut').click(function () {
			var validate  = $('#frm_order').validationEngine('validate');
			if(!validate){
				return;
			}
			
			// validate table if not choose warehouse and type
			var data_table = $('#products_list_tbl').DataTable().data().toArray();
			if(data_table.length > 0){
				$('#products_list_tbl [data-event="save-item"]').trigger( "click" );
				for(var i = 0 ; i < data_table.length; i++ ){
					var warehouse = data_table[i].warehouse,
							item_type = data_table[i].item_type,
							vendor = data_table[i].vendor,
							item_code = data_table[i].item_code;
							odr_quantity = data_table[i].odr_quantity;
							price = data_table[i].price;
					if(warehouse == null || warehouse == "" || item_type == null || item_type == "" || vendor == null || vendor == ""
					|| odr_quantity == "" || odr_quantity == 0 || price == ""){
						// bootbox.alert("<h4><?php echo $this->lang->line('PLS0010_E004');?></h4>");
						return;
					}
				}
			}
			if(data_table.length == 0 ){
				bootbox.alert("<h4><?php echo $this->lang->line('POD0020_E001'); ?></h4>");
				return;
			}

			var po_no1 = $('#po_no1').val(),
				po_no2 = $("#po_no2 option:selected").text(),
				po_no3 = $('#po_no3').val(),
				po_no4 = $('#po_no4').val(),
				po_no5 = $('#po_no5').val(),
				po_no6 = ($('#po_no6').val() != '' && $('#po_no6').val() != undefined && $('#po_no6').val() != null) ? '-'+$('#po_no6').val() : '' ;
			var oder_no = po_no1 + '-' + po_no2 + '-' + po_no3 + '-' + po_no4 + '/' + po_no5 + po_no6;

			var data = ordersListTable.rows().data();
			var items = [];
			for (let index = 0; index < data.length; index++) {
				const item = data[index];
				items.push(item);
			}
			var jsonString = JSON.stringify(items);
			$('#data_table').val(jsonString);
			bootbox.confirm({
				title: "<?php echo $this->lang->line('POD0020_I001');?>",
				message: '<h4 style="color:red;">' + oder_no +'</h4>',
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
				callback: function (result) {
					if (result) {
						var url = "<?php echo base_url('orders/addOrders');?>";
						$("#frm_order").attr("action", url).submit();
					}
				}
			});
		});
		$('#insertProduct').on('click', function(){
			$('#insert_item_modal').modal('show');
		});
		$('#insert_item_modal').on('shown.bs.modal', function() {
			$('#item_code').focus();
			$('#product_search_modal').fadeOut();
			$('#insert_error').hide();
		});
		$('#close_insert_item').on('click', function() {
			$('#insert_item_modal').modal('hide');
			$('#product_search_modal').fadeIn();
			$('#insert_error').hide();
		});
		$('#save_item').on('click', function(){
			if(!$("#frm-product").validationEngine('validate')){
				return;
			}
			var arr = $('#frm-product').serializeArray();
			requestData = {};
			$.each(arr, function (index, item) {
				requestData[item.name] = item.value;
			});
			bootbox.confirm({
					title: "<?php echo ($this->lang->line('POD0020_I002'))?>",
					message: '<h4 style="color:red;">' + requestData.item_code + '</h4>', 
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
											url: "<?php echo base_url('orders/saveItem')?>",
											data: requestData,
											type: 'post',
											dataType: 'json'
									}).done(function(response) {
											if(response.success == true) {
												currentItem = response.data;
												currentItem.amount = 0;
												currentItem.odr_quantity = 0;
												currentItem.staff = currentItem.salesman;
												currentItem.sell_price = currentItem.sell_price_usd;
												var company = searchCompanyByName(currentItem.vendor, supplierList);
												if(company != false){
													currentItem.company_name = company.company_name;
													currentItem.head_office_address = company.head_office_address;
													currentItem.head_office_phone = company.head_office_phone;
													currentItem.head_office_fax = company.head_office_fax;
													currentItem.reference = company.reference;
												}
												getMaxDetailNo();
												// aftercheck();
												$('#insert_error').hide();
												$('#insert_item_modal').modal('hide');
												$('#insert_item_modal input').val('');
												$('#insert_item_modal select').val('');
												$('#product_search_modal').modal('hide');
											} else {
												$('#insert_error').text(response.message);
												$('#insert_error').show();
											}
									});
							}
					}
			});
		});
		function reload_summary_info(){
        let summary_info = [];
        let str_summary_info = "Quantity: ";
        let amount = 0;
        var table = $('#products_list_tbl').DataTable();

        var data = table
            .rows()
            .data();
        if(data.length){
            data.each(function(ele){
                let $quantity = ele.odr_quantity ? parseFloat(ele.odr_quantity.trim()) : 0;
                let $unit = ele.unit ? ele.unit.trim() : " ";
                amount += parseFloat(ele.amount);
                if(!summary_info.hasOwnProperty($unit)){
                    summary_info[$unit] = $quantity;
                }else{
                    summary_info[$unit] += $quantity;
                }
            });
            for(let key in summary_info){
                str_summary_info += summary_info[key] + " " + key + " ; ";
            }
            str_summary_info = str_summary_info.replace(/( ; )$/,"");
            str_summary_info += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount: " + numberWithCommas(amount.myToFixed($('#currency').val())) + ' ' +$('#currency').val();
            $(".summary_info").css({
                "height": "30px",
                "line-height": "30px"
            });
            $(".summary_info").html("<b style='color: red'>"+str_summary_info+"</b>");
        } else {
					$(".summary_info").html("<b style='color: red'>Quantity: 0 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount: 0</b>");
				}
        
    }
	};
	function searchPV(pvNo){
		$('#search_pv_no').val(pvNo);
		dataTB.ajax.reload();
	}
	function searchCompany(nameKey, myArray){
		for (var i=0; i < myArray.length; i++) {
			if (myArray[i].company_id === nameKey) {
				return myArray[i];
			}
		}
		return false;
	}
	function searchCompanyByName(nameKey, myArray){
		for (var i=0; i < myArray.length; i++) {
			if (myArray[i].short_name === nameKey) {
				return myArray[i];
			}
		}
		return false;
	}
</script>
