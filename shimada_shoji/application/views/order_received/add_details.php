<?php 
$currency = trim($orderReceived['currency']);
$status_finish = in_array($orderReceived['status'], explode(",", STATUS_FINISH));
?>
<?php 
$currencyId = strtolower($currency);
if (!in_array($currencyId, ['vnd', 'jpy', 'usd'])) {
    $currencyId = 'usd';
    $currency = 'USD';
}
?>
<style>
	td div.ellipsis {
    white-space: nowrap !important; 
    max-width: 150px !important; 
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
</style>
<div class="row" onload="myFunction()">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title; ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><button class="btn btn-info" onclick="window.location.href='<?php echo base_url("order_received/edit/" . trim($orderReceived['urlPrimaryKey'])); ?>'"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button>
                    </li>
                    <li><button id="action-save" class="btn btn-primary" <?php echo $status_finish ? 'disabled' : '' ?>><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <!-- form input mask -->
                <div class="x_panel" style="padding: 10px 10px 10px 10px !important;">
                    <form id="detail_information_input" class="form-horizontal">
						<div class="col-md-12 col-sm-12 col-xs-12 no-padding-left no-padding-right">
							<div class="col-md-5 col-sm-12 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-3 col-xs-3 no-padding-left no-padding-right" for="order_receive_no"><?php echo $this->lang->line('order_received_no'); ?></label>
									<div class="col-md-4 col-sm-4 col-xs-4 no-padding-right">
										<input type="text" id="order_receive_no" name="order_receive_no" class="form-control" readonly value="<?php echo ($orderReceived['order_receive_no']); ?>"></input>
									</div>
									<label class="col-md-2" style="text-align: center; font-size: 20px;">-</label>
									<div class="col-md-2 col-sm-1 col-xs-1 no-padding-left">
										<input type="text" class="form-control" readonly value="<?php echo isset($orderReceived['partition_no_show']) ? $orderReceived['partition_no_show'] : ''; ?>"/>
										<input type="hidden" id="partition_no" name="partition_no" class="form-control" readonly value="<?php echo $orderReceived['partition_no'] ?>"/>
									</div>
									<input type="hidden" id="order_receive_date" name="order_receive_date" class="form-control" readonly value="<?php echo $orderReceived['order_receive_date'] ?>"></input>
								</div>
							</div>
							<div class="col-md-7 col-sm-6 col-xs-6">
							<div class="form-group"><label class="control-label col-md-1 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="order_receive_no">&nbsp;&nbsp;</label></div>
							</div>
							</div>
							<div class="col-md-5 col-sm-6 col-xs-6">
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-3 col-xs-3 no-padding-left no-padding-right" for="product_id"><?php echo $this->lang->line('item_code'); ?><span class="required">*</span></label>
									<div class="col-md-4 col-sm-4 col-xs-4 no-padding-right has-clear has-feedback">
										<input type="text" id="product_id" name="product_id" maxlength="20" class="form-control validate[required] text-uppercase" value="" <?php echo $status_finish ? 'readonly' : ''; ?>/>
										<input type="hidden" id="jp_code" name="jp_code" value=""/>
										<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
									</div>
									<div class="col-md-4 col-sm-5 col-xs-5" style="text-align: right;">
										<button  type="button" class="btn btn-primary" data-toggle="modal" data-target="#product_search_modal" style="margin-right: 0;" <?php echo $status_finish ? 'disabled' : '' ?>><?php echo $this->lang->line('search_product'); ?></button>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-4 col-sm-4 col-xs-3 no-padding-left no-padding-right" for="color"><?php echo $this->lang->line('color'); ?></label>
									<div class="col-md-4 col-sm-4 col-xs-4 no-padding-right">
										<input type="text" id="color" name="color" class="form-control" readonly value="">
									</div>
									<div class="col-md-5 col-sm-5 col-xs-5">
									</div>
								</div>
							</div>
							<div class="col-md-7 col-sm-6 col-xs-6 no-padding-right">
								<div class="form-group no-padding-right">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" for="item_name"><?php echo $this->lang->line('item_name'); ?></label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right">
										<input type="text" id="item_name" name="item_name" class="form-control" readonly></input>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" for="composition"><?php echo $this->lang->line('composition'); ?></label>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-right">
										<input type="text" id="composition" name="composition" class="form-control" <?php echo $status_finish ? 'readonly' : ''; ?>/>
									</div>
								</div>
							</div>
							<div class="col-md-10 col-sm-10 col-xs-10">
								<div class="form-group">
									<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" style="margin-left: -3px;" for="order"><?php echo $this->lang->line('quantity'); ?><span class="required">*</span></label>
									<div class="col-md-2 col-sm-2 col-xs-2" style="padding-right: 3px;">
										<input type="text" id="quantity" name="quantity" class="form-control validate[required, min[1]]" value="0" data-input="quantity" data-price="#price" data-base-price="#base_price" data-amount="#total_amount" data-base-amount="#total_base_amount" <?php echo $status_finish ? 'readonly' : ''; ?> onkeydown="return checkQuantity(event)"/>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" style="text-align:left; padding-left:0px">
										<label class="control-label col-md-8 col-sm-6 col-xs-6" style="text-align:left; padding-left:0px" id="unit"></label>
										<label class="control-label col-md-4 col-sm-6 col-xs-6 no-padding-left no-padding-right" style="text-align:center"> X </label>
									</div>
									<label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right"><?php echo $this->lang->line('sell_price'); ?><span class="required">*</span></label>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<input type="text" id="price" name="price" class="form-control validate[required]" data-input="price" data-quantity="#quantity"  data-amount="#total_amount" value="0" <?php echo $status_finish ? 'readonly' : ''; ?> onkeydown="return checkQuantity(event)"/>
										<input type="hidden" id="buy_price" name="buy_price" value="0"/>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" style="text-align:left; padding-left:0px">
										<label class="control-label col-md-8 col-sm-6 col-xs-6" style="text-align:left; padding-left:0px" id="price_currency">(<?php echo $currency ?>)</label>
										<label class="control-label col-md-4 col-sm-6 col-xs-6 no-padding-left no-padding-right" style="text-align: center"> = </label>
									</div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<input type="text" id="total_amount" name="total_amount" class="form-control" readonly value="0">
									</div>
									<label class="control-label col-md-1 col-sm-1 col-xs-1" style="text-align:left; padding-left:0px" id="total_currency">(<?php echo $currency ?>)</label>
								</div>
								</div>
								<div class="col-md-10 col-sm-10 col-xs-10">
								<div class="form-group" style="margin-bottom: 0;">
									<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" style="margin-left: -3px;" for="order"></label>
									<div class="col-md-2 col-sm-2 col-xs-2"></div>
									<label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right"><?php echo $this->lang->line('base_price'); ?></label>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<input type="text" id="base_price" name="base_price" class="form-control" value="0" data-input="base_price" data-quantity="#quantity" data-base-amount="#total_base_amount" <?php echo $status_finish ? 'readonly' : ''; ?> onkeydown="return checkQuantity(event)"/>
									</div>
									<div class="col-md-1 col-sm-1 col-xs-1" style="text-align:left; padding-left:0px">
										<label class="control-label col-md-8 col-sm-6 col-xs-6" style="text-align:left; padding-left:0px" id="price_currency">(<?php echo $currency ?>)</label>
										<label class="control-label col-md-4 col-sm-6 col-xs-6 no-padding-left no-padding-right" style="text-align: center"> = </label>
									</div>
									<div class="col-md-2 col-sm-2 col-xs-2">
										<input type="text" id="total_base_amount" name="total_base_amount" class="form-control" readonly value="0">
									</div>
									<label class="control-label col-md-1 col-sm-1 col-xs-1" style="text-align:left; padding-left:0px" id="total_currency">(<?php echo $currency ?>)</label>
								</div>
							</div>
							<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <button id="add_excel" name="add_excel" type="button" class="btn btn-default pull-right" style="margin-right: 1px !important;" disabled><i class="fa fa-plus"></i> Excel</button>
                                    <button id="add" name="add" type="button" class="btn btn-success pull-right" style="margin-right: 1px !important;" onclick="addItem(this)" <?php echo $status_finish ? 'disabled' : '' ?>><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                                </div>
                            </div>
                    </form>
                </div>
                <div class="x_content">
                  <div class="table-responsive">
                      <table id="items_orders_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                          <thead>
                              <tr>
                                <th>#</th>
                                <th><?php echo $this->lang->line('item_code'); ?></th>
                                <th><?php echo $this->lang->line('item_name'); ?></th>
                                <th><?php echo $this->lang->line('composition'); ?></th>
                                <th><?php echo $this->lang->line('size'); ?></th>
                                <th><?php echo $this->lang->line('color'); ?></th>
                                <th><?php echo $this->lang->line('quantity'); ?></th>
                                <th><?php echo $this->lang->line('hikiate_quantity'); ?></th>
                                <th><?php echo $this->lang->line('unit'); ?></th>
                                <th><?php echo $this->lang->line('sell_price'); ?></th>
                                <th><?php echo $this->lang->line('base_price'); ?></th>
                                <th><?php echo $this->lang->line('total_amount'); ?></th>
                                <th><?php echo $this->lang->line('total_base_amount'); ?></th>
                                <th><?php echo $this->lang->line('currency'); ?></th>
                                <th><?php echo $this->lang->line('status'); ?></th>
                                <th><?php echo $this->lang->line('delivery_date'); ?></th>
                                <th width="50px;"><?php echo $this->lang->line('action'); ?></th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                  </div>
                </div>
              <!-- /form input mask -->
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Modal -->
<!-- Product Allocate -->
<div id="product_allocate_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 80%;">
        <div class="modal-content">

            <div class="modal-header">
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-info" data-dismiss="modal">
                            <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-primary" data-event="save-allocation">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                        </button>
                    </li>
                </ul>
                <h2><?php echo $this->lang->line('product_allocation'); ?></h2>
            </div>
            <div class="modal-body">
				<form class="form-horizontal form-label-left">
					<div class="col-md-8 no-padding-left no-padding-right">
						<div class="form-group">
							<label 
								class="control-label col-md-2 no-padding-left no-padding-right" 
								for="order_receive_no"><?php echo $this->lang->line('order_received_no'); ?>
							</label>
							<div class="col-md-3 no-padding-right">
								<input 
									type="text" 
									id="order_receive_no" 
									name="order_receive_no" 
									class="form-control" 
									readonly 
								/>
							</div>
							<label class="control-label col-md-1 no-padding-top no-padding-right" style="text-align: center; font-size: 20px;">-</label>
							<div class="col-md-1 no-padding-right">
								<input 
									type="hidden" 
									id="partition_no" 
									name="partition_no" 
									class="form-control" 
									readonly 
								/>
								<input 
									type="text"
									name="partition_no_show"
									class="form-control"
                                    value="<?php echo isset($orderReceived['partition_no_show']) ? $orderReceived['partition_no_show'] : ''; ?>"
									readonly
								/>
							</div>
							<input 
								type="hidden" 
								id="order_receive_date" 
								name="order_receive_date" 
								class="form-control" 
								readonly
							/>
							<input 
								type="hidden" 
								id="order_receive_detail_no" 
								name="order_receive_detail_no" 
								class="form-control" 
								readonly 
							/>
							<label 
								class="control-label col-md-2 no-padding-left no-padding-right" 
								for="salesman"><?php echo $this->lang->line('sales_man'); ?>
							</label>
							<div class="col-md-3 no-padding-right">
								<input 
									type="text" 
									id="salesman" 
									name="salesman" 
									class="form-control" 
									readonly 
								/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 no-padding-left no-padding-right" for="search_item_code"><?php echo $this->lang->line('item_code'); ?></label>
							<div class="col-xs-3 no-padding-right">
								<input 
									type="text" 
									class="form-control" 
									id="search_item_code" 
									name="item_code" 
									value="" 
									readonly
								/>
							</div>
							<label class="control-label col-md-2 no-padding-left" for="search_item_name"><?php echo $this->lang->line('item_name'); ?></label>
							<div class="col-md-5 no-padding-left no-padding-right">
								<input 
									type="text" 
									class="form-control" 
									id="search_item_name" 
									name="item_name" 
									value="" 
									readonly
								/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-2 no-padding-left no-padding-right" for="search_item_color"><?php echo $this->lang->line('color'); ?></label>
							<div class="col-md-3 no-padding-right">
								<input 
									type="text" 
									class="form-control" 
									id="search_item_color" 
									name="color" 
									value="" 
									readonly
								/>
							</div>
							<label class="control-label col-md-1 no-padding-left" for="search_item_size"><?php echo $this->lang->line('size'); ?></label>
							<div class="col-md-3 no-padding-right">
								<input 
									type="text" 
									class="form-control" 
									id="search_item_size" 
									name="size" 
									value="" 
									readonly
								/>
							</div>
							<div class="col-md-1 text-left" style="padding-top: 8px;">
								<span data-binding="size_unit">m&sup2;</span>
							</div>
						</div>
                        <div class="form-group">
							<label class="control-label col-md-2 no-padding-left no-padding-right" for="search_identify_name"><?php echo $this->lang->line('identify_name'); ?></label>
							<div class="col-md-3 no-padding-right">
								<input 
									type="text" 
									class="form-control" 
									id="identify_name" 
									name="identify_name" 
									value="<?php echo $orderReceived['identify_name_1']; ?>"
									readonly
								/>
							</div>
                            <div class="col-md-2 text-right" style="padding-top: 5px;">
                                <input type="checkbox" class="flat" id="move_to" name="move_to" value="1" >
                            </div>
                            <label class="col-md-3 text-left no-padding-right" style="padding-top: 5px;" for="move_to">
                                <?php echo ($this->lang->line('move_to')); ?>
                            </label>
                            <div class="col-md-2 no-padding-left no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="next_qty" 
                                    name="next_qty" 
                                    value="" 
                                    onkeydown="return checkQuantity(event)"
                                    readonly
                                />
                            </div>
                        </div>
					</div>
					<div class="col-md-4 no-padding-left no-padding-right">
						<div class="form-group">
							<label class="control-label col-md-4 no-padding-left no-padding-right"></label>
							<?php if($orderReceived['kubun'] === ANOTHER_ORDER): ?>
							<div class="col-md-6" id="another_order">
								<span class="form-control" style="color: red" readonly><?php echo $this->lang->line('another_order'); ?></span>
							</div>
							<?php endif ?>
							<input
									type="hidden"
									id="order_kbn"
									name="order_kbn"
									value="<?php echo $orderReceived['kubun']; ?>"
									readonly
								/>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4 no-padding-left no-padding-right" for="search_item_quantity"><?php echo $this->lang->line('quantity'); ?></label>
							<div class="col-md-6">
								<input 
									type="number" 
									class="form-control" 
									id="search_item_quantity" 
									name="quantity" 
									value="" 
									readonly
								/>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-4 no-padding-left no-padding-right" for="search_allocated_quantity"><?php echo $this->lang->line('hikiate_quantity'); ?></label>
							<div class="col-md-6">
								<input 
									type="number" 
									class="form-control" 
									id="search_allocated_quantity" 
									name="hikiate_quantity"
									value="0"
									readonly
								/>
							</div>
							<!-- <div class="col-md-2" style="text-align: right;">
								<p style="padding-top: 10px;">
									<input type='hidden' value='' name=''>
									<input 
										type="checkbox" 
										class="flat" 
										name="" 
										id="" 
										value=""
									>
								</p>
							</div> -->
					    </div>
                        <a href="" class="inventory_link" style="margin-left: 30px; color:#1106f4; text-decoration: underline">Search in Inventory with this ItemCode</a>
                    </div>
                </form>
                <div class="x_title">
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table 
                            id="allocate_products_list" 
                            class="table table-striped table-bordered display nowrap" 
                            width="100%" 
                            cellspacing="0"
                        />
                            <thead >
                                <tr>
                                    <th style="background-color: #ffff00;"><?php echo $this->lang->line('sales_man'); ?></th>
									<th style="background-color: #ffff00;"><?php echo $this->lang->line('invoice_no'); ?></th>
                                    <th><?php echo $this->lang->line('arrival_date'); ?></th>
									<th><?php echo $this->lang->line('quantity'); ?></th>
									<th style="background-color: #ffff00;"><?php echo $this->lang->line('status'); ?></th>
									<th><?php echo $this->lang->line('arrival'); ?></th>
									<th><?php echo $this->lang->line('inspect'); ?></th>
									<th style="background-color: #ffff00;">OK</th>
									<th>NG</th>
									<th><?php echo $this->lang->line('price'); ?></th>
									<th style="background-color: #ffff00;"><?php echo $this->lang->line('identify_name'); ?></th>
									<th><?php echo $this->lang->line('type'); ?></th>
									<th>PV/PO No</th>
                                    <th><?php echo $this->lang->line('priority'); ?></th>
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
</div>

<!-- Modal -->
<!-- Search -->
<div id="product_search_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <ul class="nav navbar-right panel_toolbox">
                    <li><button class="btn btn-info" data-dismiss="modal"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button></li>
                    <li><button onclick="window.location.href='<?php echo base_url('products/add') ?>'" class="btn btn-primary"><i class="fa fa-plus"></i> IT <?php echo $this->lang->line('new'); ?></button></li>
                </ul>
                <h2><?php echo $this->lang->line('search_product'); ?></h2>
            </div>
            <div class="modal-body">
                <form id="product_search_form" class="form-horizontal form-label-left">
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-5 col-xs-5" for="search_item_codes"><?php echo $this->lang->line('item_code'); ?></label>
                    <div class="col-md-3 col-sm-7 col-xs-7 has-clear has-feedback">
                        <input type="text" class="form-control text-uppercase" id="search_item_codes" name="item_code" maxlength="30" value="">
                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                    </div>
                    <label class="control-label col-md-2 col-sm-5 col-xs-5" for="search_item_names"><?php echo $this->lang->line('item_name'); ?></label>
                    <div class="col-md-3 col-sm-7 col-xs-7 has-clear has-feedback">
                        <input type="text" class="form-control" id="search_item_names" name="item_name" maxlength="100" value="">
                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2 col-sm-5 col-xs-5" for="search_input_customer"><?php echo $this->lang->line('customer'); ?></label>
                    <div class="col-md-3 col-sm-7 col-xs-7 has-clear has-feedback">
                        <input type='text' id="search_input_customer" name='input_customer' maxlength="50" class="form-control text-uppercase" value="<?php echo $orderReceived["customer_short_name"]; ?>"></input>
                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                    </div>
                    <label class="control-label col-md-2 col-sm-5 col-xs-5" for="search_saleman"><?php echo $this->lang->line('sales_man'); ?></label>
                    <div class="col-md-3 col-sm-7 col-xs-7 has-clear has-feedback">
                        <input type="text" id="search_saleman" name="saleman" maxlength="20" class="form-control text-uppercase" value="<?php echo $orderReceived["staff_name"]; ?>"></input>
                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                    </div>
                    <a class="action-search btn btn-info"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></a>
                </div>
                    </form>
                    <div class="x_title">
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                        <table id="products_list_search" class="table table-striped table-bordered display nowrap" width="100%" cellspacing="0">
                            <thead >
                                <tr>
                                    <th><?php echo $this->lang->line('item_code'); ?></th>
                                    <th><?php echo $this->lang->line('item_name'); ?></th>
                                    <th><?php echo $this->lang->line('sales_man'); ?></th>
                                    <th><?php echo $this->lang->line('customer'); ?></th>
                                    <th><?php echo $this->lang->line('size'); ?></th>
                                    <th><?php echo $this->lang->line('color'); ?></th>
                                    <th><?php echo $this->lang->line('unit'); ?></th>
                                    <th><?php echo $this->lang->line('vender'); ?></th>
                                    <th><?php echo $this->lang->line('buy_price'); ?></th>
                                    <th><?php echo $this->lang->line('sell_price'); ?></th>
                                    <th><?php echo $this->lang->line('base_price'); ?></th>
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
</div>
<!-- Salesman Change -->
<div id="inventory_change_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('STR0010_I004'); ?></h4>
            </div>
            <div class="modal-body">
                <form id="edit_inventory_form" class="form-horizontal form-label-left" action="" method='post'>
                    <input type="hidden" class="form-control" id="edit_date" name="edit_date">
                    <input type="hidden" class="form-control" id="partition_no_change" name="partition_no">
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="salesmanchange">
							<?php echo $this->lang->line('sales_man'); ?>
						</label>
                        <div class="col-md-4 col-sm-7 col-xs-7">
                            <input type="text" class="form-control" id="oldsalesmanchange" name="oldsalesmanchange" value="" readonly>
						</div>
                        <label class="control-label col-md-1 col-sm-5 col-xs-5" style="text-align: center;">
                            <span class="glyphicon glyphicon-chevron-right"></span>
						</label>
                        <div class="col-md-5 col-sm-7 col-xs-7">
                            <select id="salesmanchange" name="salesmanchange" class="form-control">
                                <?php foreach ($salesman_list as $salesman): ?>
                                    <option value="<?php echo trim($salesman['komoku_name_2']) ?>"><?php echo trim($salesman['komoku_name_2'])?></option>
                                <?php endforeach?>
                            </select>
						</div>
					</div>
                    <div class="form-group">
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="warehousechange">
							<?php echo $this->lang->line('warehouse'); ?>
						</label>
						<div class="col-md-4 col-sm-7 col-xs-7">
                            <input data-komoku="test" type="text" class="form-control" id="oldwarehousechange" name="oldwarehousechange" value="" readonly>
						</div>
                        <label class="control-label col-md-1 col-sm-5 col-xs-5" style="text-align: center;">
                            <span class="glyphicon glyphicon-chevron-right"></span>
						</label>
                        <div class="col-md-5 col-sm-7 col-xs-7">
                            <select id="warehousechange" name="warehousechange" class="form-control">
                                <?php foreach ($warehouse_list as $warehouse): ?>
                                    <option value="<?php echo trim($warehouse['kubun']) ?>"><?php echo trim($warehouse['komoku_name_2'])?></option>
                                <?php endforeach?>
                            </select>
						</div>
					</div>
                    <div class="form-group">
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="old_inv_no">
                            <?php echo $this->lang->line('invoice_no'); ?>
						</label>
						<div class="col-md-4 col-sm-7 col-xs-7">
                            <input type="text" class="form-control" id="old_inv_no" name="old_inv_no" value="" readonly>
						</div>
                        <label class="control-label col-md-1 col-sm-5 col-xs-5" style="text-align: center;">
                            <span class="glyphicon glyphicon-chevron-right"></span>
						</label>
                        <div class="col-md-5 col-sm-7 col-xs-7">
                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="">
						</div>
					</div>
				</form>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary antoclose2 btn-primary" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></a>
                <button type="button" id="submit_inventory" disabled=true class="btn btn-default antosubmit2 btnOk btn-success"><?php echo $this->lang->line('yes'); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    var orderReceived = <?php echo json_encode($orderReceived);?>;
    var globalAllocateList = []; 
  var currentItem;
  var currentRow;
  function addItem() {
    if (!$('#detail_information_input').validationEngine('validate')) {
      return;
    }
    if (!currentItem) {
      bootbox.alert("<?php echo $this->lang->line('POD0020_E001');?>");
      return;
    }

    var data = {
      order_receive_no: undefined,
      partition_no: undefined,
      order_receive_date: undefined,
      order_receive_detail_no: undefined,
      item_code: $('#product_id').val(),
      jp_code: $('#jp_code').val(),
      buy_price: parseFloat(convertPrice(currentItem,'buy_price')),
      item_name: currentItem.item_name,
      composition_1: currentItem.composition_1,
      composition_2: currentItem.composition_2,
      composition_3: currentItem.composition_3,
      size_unit: currentItem.size_unit,
      size: currentItem.size,
      vendor: currentItem.vendor,
      color: currentItem.color,
      colors:currentItem.colors,
      vendor_color: currentItem.vendor_color,
      unit: currentItem.unit,
      quantity: $('#quantity').val(),
      hikiate_quantity: 0,
      sell_price: $('#price').val(),
      base_price: $('#base_price').val(),
      inspect_date: currentItem.inspect_date,
      inspect_user: currentItem.inspect_user,
      status: currentItem.status,
      delivery_date: ''
    };
    data.amount = data.quantity * data.sell_price;
    data.amount_base = data.quantity * data.base_price;
    data.edit_mode = true;
    var rowNode = $('#items_orders_list').DataTable()
      .row.add( data )
      .draw()
      .node();
    $( rowNode )
      .addClass('added');
  }
  function selectItem(element) {
    var item = $('#products_list_search').DataTable().row( $(element).parents('tr') ).data();
    // console.log(item);
    $("#product_id").val(item.item_code);
    $("#jp_code").val(item.jp_code);
    $("#buy_price").val(convertPrice(item,'buy_price'));
    $("#item_name").val(item.item_name);
    $("#composition").val((item.composition_1 || '') + ' ' + (item.composition_2 || '') + ' ' + (item.composition_3 || ''));
    $("#price").val(convertPrice(item,'sell_price'));
    $("#base_price").val(convertPrice(item,'base_price'));
    $("#color").val(item.color);
    $("#unit").text("(" + item.unit + ")");
    $("#quantity").val(parseFloat(item.quantity)||0).keyup();
    $('#product_search_modal').modal('hide');
    currentItem = item;
  }
  window.onload = (function() {
    $('[data-input="quantity"]').on('keyup mouseup', function(){
      var quantity = parseFloat($(this).val()) || 0;
      var price = $($(this).attr('data-price')).val();
      var basePrice = $($(this).attr('data-base-price')).val();
      $($(this).attr('data-amount')).val((quantity * price).myToFixed(orderReceived.currency) || 0);
      $($(this).attr('data-base-amount')).val((quantity * basePrice).myToFixed(orderReceived.currency) || 0);
    });

    $('[data-input="price"]').on('keyup mouseup', function(){
      var price = $(this).val();
      var quantity = parseFloat($($(this).attr('data-quantity')).val()) || 0;
      $($(this).attr('data-amount')).val((quantity * price).myToFixed(orderReceived.currency) || 0);
    });

    $('[data-input="base_price"]').on('keyup mouseup', function(){
      var price = $(this).val();
      var quantity = parseFloat($($(this).attr('data-quantity')).val()) || 0;
      $($(this).attr('data-base-amount')).val((quantity * price).myToFixed(orderReceived.currency) || 0);
    });

    var ordersListTable = $('#items_orders_list').DataTable({
        "scrollX": true,
        "paging": true,
        "filter": false,
        "ordering": false,
        "dom": 'l<"summary_info">rtip',
        "drawCallback": function() {
            // datepicker
            $('.date').datepicker({
                todayHighlight: true,
                format: "d M, yyyy",
                autoclose: true
            });
            reload_summary_info();
        },
        "columns": [
            { "data": "no", render: function( data, type, row, meta) {
                return (meta.row + 1);
            } },
            { "data": "item_code", "class": 'text-left' },
            { "data": "item_name", "class": 'text-left' },
            { "data": "composition", "class": 'text-left', render: function( data, type, row, meta) {
                return '<div class="ellipsis" title="'+(row.composition_1 || '') + ' ' + (row.composition_2 || '') + ' ' + (row.composition_3 || '')+'">'+(row.composition_1 || '') + ' ' + (row.composition_2 || '') + ' ' + (row.composition_3 || '')+'</div>';
              }
            },
            { "data": "size", render: function(data, type, row, meta) {
                return (row.size || '') + ' ' + (row.size_unit || '');
              }
            },
            { "data": "color",render: function( data, type, row, meta ){
                if(row.edit_mode){
                    var $select = $('<select>').attr("class","form-control no-padding-right").attr("name","color");
                    $select.append($('<option>').attr('value',data).text(data));
                    if(row.colors){
                        Object.values(row.colors).forEach(function(el){
                            $select.append($('<option>').attr('value',el.komoku_name_3).text(el.komoku_name_3));
                        });
                    }
                    
                    return $select[0].outerHTML;
                }
                return data;
            } },
            { "data": "quantity", "class": 'text-right', render: function( data, type, row, meta ){
                if (row.edit_mode){
                    var $input = $('<input>')
                        .attr('type', 'text')
                        .attr('value', parseFloat(row.quantity))
                        .attr('class', 'form-control')
                        .attr('name', 'edit_quantity')
                        .attr('onkeydown', 'return checkQuantity(event)')
                        .css('max-width', '100px')
                        .css('height', '30px');
                    $input = row.validate && !row.validate.edit_quantity ? $input.css('border-color', 'red') : $input;
                    return $input[0].outerHTML;
                } else {
                    return numberWithCommas(row.quantity);
                }
              }
            },
			{ "data": "hikiate_quantity", "class": 'text-right', render: function( data, type, row, meta ){
				return numberWithCommas(row.hikiate_quantity);
			 }},
            { "data": "unit" },
            { "data": 'sell_price', "class": 'text-right', render: function( data, type, row, meta ){
                if (row.edit_mode){
                    var $input = $('<input>')
                        .attr('type', 'text')
                        .attr('value', parseFloat(row.sell_price).myToFixed(orderReceived.currency))
                        .attr('class', 'form-control')
                        .attr('name', 'sell_price')
                        .attr('onkeydown', 'return checkQuantity(event)')
                        .css('max-width', '80px')
                        .css('height', '30px');
                    $input = row.validate && !row.validate.sell_price ? $input.css('border-color', 'red') : $input;
                    return $input[0].outerHTML;
                } else {
                    return numberWithCommas(parseFloat(row.sell_price).myToFixed(orderReceived.currency));
                }
              }
            },
            { "data": 'base_price', "class": 'text-right', render: function( data, type, row, meta ){
                if (row.edit_mode){
                    var $input = $('<input>')
                        .attr('type', 'text')
                        .attr('value', parseFloat(row.base_price).myToFixed(orderReceived.currency))
                        .attr('class', 'form-control')
                        .attr('name', 'base_price')
                        .attr('onkeydown', 'return checkQuantity(event)')
                        .css('max-width', '80px')
                        .css('height', '30px');
                    return $input[0].outerHTML;
                } else {
                    return numberWithCommas(parseFloat(row.base_price).myToFixed(orderReceived.currency));
                }
              }
            },
			{ "data": "amount", "class": 'text-right', render: function( data, type, row, meta ){
				if(row.amount){
					var amount = parseFloat(row.amount);
					return numberWithCommas(amount.myToFixed(orderReceived.currency));
				}      
				return row.amount;
			 }},
			{ "data": "amount_base", "class": 'text-right', render: function( data, type, row, meta ){
				if(row.amount_base){
					var amount = parseFloat(row.amount_base);
					return numberWithCommas(amount.myToFixed(orderReceived.currency));
				}      
				return row.amount_base;
			 }},
            { "data": "currency", render: function(data, type, item, meta) {
                return "<?php echo $currency ?>"
            }},
            { "data": "status", render: function( data, type, row, meta) {
                return row.status == 1 ? '<?php echo $this->lang->line("allocate")?>' : '<?php echo $this->lang->line("unallocate") ?>';                
                // return row.status === "001" ? '未引当':'引当済';
            } },
            { "data": "delivery_date", render: function(data, type, row, meta){
                if (row.edit_mode){
                    var $input = $('<input>')
                        .attr('type', 'text')
                        .attr('value', formatDate(row.delivery_date))
                        .attr('class', 'form-control date')
                        .attr('name', 'delivery_date')
                        .css('max-width', '100px')
                        .css('height', '30px');
                    return $input[0].outerHTML;
                } else {
                    return row.delivery_date;
                }
              }
            },
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
                    if(row.status != null && row.status.trim() == '1'){
                        html += '<a class="btn btn-xs btn-primary" disabled title="<?php echo $this->lang->line('edit'); ?>">'
                                + '<span class="fa fa-edit"></span>'
                                + '</a>';
                        html += '<a style="margin-left: 3px;" class="btn btn-xs btn-danger" disabled title="<?php echo $this->lang->line('delete'); ?>">'
                                + '<span class="fa fa-trash"></span>'
                                + '</a>';
                    }else{
                        html += '<a class="btn btn-xs btn-primary" <?php echo $status_finish ? 'disabled' : 'data-event="edit-item"' ?> title="<?php echo $this->lang->line('edit'); ?>">'
                                + '<span class="fa fa-edit"></span>'
                                + '</a>';
                        html += '<a style="margin-left: 3px;" class="btn btn-xs btn-danger" <?php echo $status_finish ? 'disabled' : 'data-event="delete-item"' ?> title="<?php echo $this->lang->line('delete'); ?>">'
                                + '<span class="fa fa-trash"></span>'
                                + '</a>';
                    }
              }
              if(row.order_receive_detail_no) {
                if((row.status != null && row.status.trim() == '1') || row.edit_mode) {
                    html += '<a class="btn btn-info btn-sm" style="line-height: 1;" title="<?php echo $this->lang->line('unallocate'); ?>" ' 
                        + 'data-event="product-unallocate"> <?php echo $this->lang->line('unallocate'); ?>'
                        + '</a>';
                } else {
                    html += '<a class="btn btn-warning btn-sm" style="line-height: 1;" title="<?php echo $this->lang->line('product_allocation'); ?>" ' 
                        + '<?php echo ($status_finish) ? 'disabled' : 'data-event="product-allocate"' ?> >'
                        + '<?php echo $this->lang->line('product_allocate'); ?>'
                        + '</a>';
                }
              }
              return html;
            } },
        ],
        "ajax": '<?php echo base_url("order_received/details/" . trim($orderReceived['urlPrimaryKey'])); ?>',
    });

    function reload_summary_info(){
        let summary_info = [];
        let str_summary_info = "Quantity: ";
        let amount = 0;
        var table = $('#items_orders_list').DataTable();

        var data = table
            .rows()
            .data();
        if(data.length){
            data.each(function(ele){
                let $quantity = parseFloat(ele.quantity.trim());
                let $unit = ele.unit.trim();
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
            str_summary_info += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Amount: " + numberWithCommas(amount.myToFixed(orderReceived.currency)) + " <?php echo $currency ?>";
            // console.log();
            $(".summary_info").css({
                "height": "30px",
                "line-height": "30px"
            });
            $(".summary_info").html("<b style='color: red'>"+str_summary_info+"</b>");
        }
        
    }
    // Click edit in datatable
    $('#items_orders_list').on('click', '[data-event="edit-item"]', function(){
        var $row = $(this).parents('tr');
        var rowData = ordersListTable.row($row).data();
        rowData.edit_mode = true;
        ordersListTable.row($row).data(rowData).invalidate();
        ordersListTable.draw(false);
    });
    // Click delete in datatable
    $('#items_orders_list').on('click', '[data-event="delete-item"]', function(){
        var $row = $(this).parents('tr');
        rowData = ordersListTable.row($row).data();

        bootbox.confirm({
            title: "Do you want to delete this Item?",
            message: '<h4 style="color:red;">' + rowData.item_code + '</h4>', 
            buttons: {
                confirm: {
                    label: '<?php echo $this->lang->line('yes'); ?>',
                    className: 'btn-success',
                },
                cancel: {
                    label: '<?php echo $this->lang->line('no'); ?>',
                    className: 'btn-primary',
                }
            },
            callback: function (result) {
                if (result) {
                    if(!rowData.order_receive_detail_no) {
                        ordersListTable.row($row).remove().draw();
                    } else {
                        $.ajax({
                        url: '<?php echo base_url('order_received/do_delete_item') ?>',
                        type: "POST",
                        data: rowData,
                        dataType: 'json',
                        async: false,
                        success: function(response) {
                            if (response.success) {
                                ordersListTable.row($row).remove().draw();
                            } else {
                                bootbox.alert({
                                    title: lang['response_error'],
                                    message: response.message
                                });
                            }
                        }
                        });
                    }
                }
            }
        });
    });
    // Click save in datatable
    $('#items_orders_list').on('click', '[data-event="save-item"]', function(){
        var row         = $(this).parents('tr');
        var data        = $('#items_orders_list').DataTable().row(row).data();
        currentRow = data;
        data.validate   = {};

        var edit_quantity = row.find('input[name="edit_quantity"]').val();
        var sell_price = parseFloat(row.find('input[name="sell_price"]').val()).myToFixed(orderReceived.currency);
        var base_price = parseFloat(row.find('input[name="base_price"]').val()).myToFixed(orderReceived.currency);
        var delivery_date = row.find('input[name="delivery_date"]').val();
        var input = row.find(':input');
        data.validate[input[0].name] = !edit_quantity || edit_quantity == '0' ? false : true;
        data.validate[input[1].name] = !sell_price ? false : true;
        data.validate[input[2].name] = !base_price ? false : true;

        if(!data.validate[input[0].name] || !data.validate[input[1].name] || !data.validate[input[2].name]) {
            if(!data.validate[input[0].name]){
                row.find('input[name="edit_quantity"]').focus();
            }
            if(!data.validate[input[1].name]){
                row.find('input[name="sell_price"]').focus();
            }
            if(!data.validate[input[2].name]){
                row.find('input[name="base_price"]').focus();
            }
            return;
        }

        data.quantity                   = edit_quantity;
        data.sell_price 				= sell_price;
        data.base_price 				= base_price;
        data.delivery_date 				= delivery_date;
        data.amount 				    = (data.quantity * data.sell_price).myToFixed(orderReceived.currency);
        data.amount_base 				= (data.quantity * data.base_price).myToFixed(orderReceived.currency);
        data.edit_mode = false;
        data.color = row.find('select[name="color"]')[0].value;
        if($(row).hasClass('added')) {
            data.order_receive_no = '<?php echo $orderReceived['order_receive_no']; ?>';
            data.partition_no = '<?php echo $orderReceived['partition_no']; ?>';
            data.order_receive_date = '<?php echo $orderReceived['order_receive_date']; ?>';
            Object.keys(data).forEach(function(key) {
                if(!data[key] && data[key] != 0) delete data[key];
            });
            delete data.edit_mode;
            delete data.validate;
            $.ajax({
                url: '<?php echo base_url('order_received/do_add_item') ?>',
                type: "POST",
                data: data,
                dataType: 'json',
                async: false,
                success: function(response) {
                    if (response.success) {
                        $(row).removeClass('added');
                        response.data.colors = currentRow.colors;
                        ordersListTable.row(row).data(response.data).invalidate().draw( false );
                    } else {
                        bootbox.alert({
                            title: lang['response_error'],
                            message: response.message
                        });
                    }
                }
            });
        } else {
            $.ajax({
                url: '<?php echo base_url('order_received/do_save_item') ?>',
                type: "POST",
                data: data,
                dataType: 'json',
                async: false,
                success: function(response) {
                    if (response.success) {
                        data.delivery_date = response.data;     
                        ordersListTable.row(row).data(data).invalidate().draw(false);
                    } else {
                        bootbox.alert({
                            title: lang['response_error'],
                            message: response.message
                        });
                    }
                }
            });
        }
        $(row).addClass('edited-item');
    });
    // Click close in datatable
    $('#items_orders_list').on('click', '[data-event="close-item"]', function(){
      var $row = $(this).parents('tr');
      var rowData = ordersListTable.row($row).data();
      rowData.edit_mode = false;
      ordersListTable.row($row).data(rowData).invalidate();
      ordersListTable.draw();
    });

    var dataTB;
    $('#product_search_modal').on('shown.bs.modal', function() {
      $('#product_search_modal #search_item_codes').val($('#product_id').val()).change();
      if (!dataTB) {
        dataTB = $("#products_list_search").DataTable({
          "scrollX": true,
          paging: true,
          ordering: false,
          filter: false,
          columns: [
                { data: 'item_code' },
                { data: 'item_name' },
                { data: 'salesman' },
                { data: 'customer' },
                { data: 'size', className: 'text-center'},
                { data: 'color', className: 'text-center'},
                { data: 'unit', className: 'text-center'},
                { data: 'vendor' },
                { data: 'buy_price_<?php echo $currencyId ?>', className: 'text-right'},
                { data: 'sell_price_<?php echo $currencyId ?>', className: 'text-right'},
                { data: 'base_price_<?php echo $currencyId ?>', className: 'text-right'},
                { data: 'action', className: 'text-center', render: function() {
                  return '<a onclick="selectItem(this)" href="#" style="color: #0af">Select</a>';
                }
              },
          ],
          "ajax": {
              url : "<?php echo base_url("order_received/search_items") ?>",
              type : 'GET',
              data: function ( data ) {
                data.param = {};
                var arr = $('#product_search_form').serializeArray();
                $.each(arr, function(index, item) {
                  if(item.value != '') {
                    data.param[item.name] = item.value;
                  }
                });
              }
          },
        });
      } else {
        dataTB.ajax.reload();
      }
    });

    $('#product_search_modal .action-search').click(function() {
      dataTB.ajax.reload();
    });

    $("#action-save").click(function(e){
        let shouldStop = false;
        ordersListTable.rows().every(function( rowIdx, tableLoop, rowLoop ){
            if(shouldStop) return;
            let data = this.data();
            data.validate   = {};
            if(data.edit_mode){
                let row = $(this.node());
                let edit_quantity = row.find('input[name="edit_quantity"]').val();
                let sell_price = parseFloat(row.find('input[name="sell_price"]').val()).myToFixed(orderReceived.currency);
                let base_price = parseFloat(row.find('input[name="base_price"]').val()).myToFixed(orderReceived.currency);
                let delivery_date = row.find('input[name="delivery_date"]').val();
                data.quantity       = edit_quantity;
                data.sell_price 	= sell_price;
                data.base_price 	= base_price;
                data.delivery_date 	= delivery_date;
                data.amount 		= parseFloat(data.quantity * data.sell_price).myToFixed(orderReceived.currency) ;
                data.amount_base 	= parseFloat(data.quantity * data.base_price).myToFixed(orderReceived.currency) ;
                data.validate['edit_quantity'] = !edit_quantity ? false : true;
                data.validate['sell_price'] = !sell_price ? false : true;
                
                if(!data.validate['edit_quantity'] || !data.validate['sell_price']) {
                    this.data(data).invalidate().draw();
                    shouldStop = true;
                }
            }
        });
        if(shouldStop) return;
      bootbox.confirm({
          title: "<?php echo $this->lang->line('POD0020_I002'); ?>",
          message: '<h4 style="color:red;">' + $('#order_receive_no').val() + '</h4>',
          buttons: {
              confirm: {
                  label: '<?php echo $this->lang->line('yes'); ?>',
                  className: 'btn-success',
              },
              cancel: {
                  label: '<?php echo $this->lang->line('no'); ?>',
                  className: 'btn-primary'
              }
          },
          callback: function(result) {
              if(result) {
                var data = ordersListTable.rows().data();
                var items = [];
                for (let index = 0; index < data.length; index++) {
                  const item = data[index];
                  if(!item['delivery_date']){
                    delete item['delivery_date'];
                  }
                  if(!item['hikiate_quantity']){
                    delete item['hikiate_quantity'];
                  }
                  if(item['validate'] != null){
                    delete item['validate'];
                  }
                  if(item['salesman'] != null){
                    delete item['salesman'];
                  }
                  delete item.colors;
                  items.push(item);
                }
                var jsonString = JSON.stringify(items);
                // do post
                $('<form action="<?php echo base_url('order_received/save_details/' . trim($orderReceived['urlPrimaryKey'])) ?>" method="POST">' + 
                  "<input type='hidden' name='data' value='" + jsonString + "'>" +
                  '</form>').appendTo(document.body).submit();
              }
          }
      });
    });

    var allocateItemsDataTB;
    $('#product_allocate_modal').on('shown.bs.modal', function() {
        $('#move_to').iCheck('uncheck');
        globalAllocateList = [];
        if (!allocateItemsDataTB) {
            allocateItemsDataTB = $("#allocate_products_list").DataTable({
            "scrollX": true,
            ordering: false,
            filter: false,
            "drawCallback": function() {
                // iCheck
                $(document).ready(function() {
                    if ($("input.flat")[0]) {
                        $(document).ready(function() {
                            $("input.flat").iCheck({
                                checkboxClass: "icheckbox_flat-green",
                                radioClass: "iradio_flat-green"
                            });
                        });
                    }
                });
            },
            columns:[
                { data: 'salesman',className: "text-left", render: function(data, type, row, meta){
                    var allocateItemSttArr = '<?php echo ALLOCATE_ITEM_STATUS;?>'.split(",");
                    var orderKbn = $form.find('[name="order_kbn"]').val();
                    var warehouseSubStr = row.warehouse.substring(0, 5);
                    if((allocateItemSttArr.indexOf(row.status_id) == -1)
                        || (row.arrival_status=='')
                        || (row.inspect_status=='')
                        || (parseFloat(row.inspect_ok)<=0)
                        ) {
                            return data;
                        }
                        return '<a class="edit" data-event="inventory-edit">'+(data)+'</a>';
                }},
                { data: 'invoice_no', class:'text-left'},
                { data: 'arrival_date'},
                { data: 'quantity', class:'text-right', render: function(data) {
                    return numberWithCommas(data);
                }},
                { data: 'status', class:'text-center'},
                { data: 'arrival_status', class:'text-center'},
                { data: 'inspect_status', class:'text-center'},
                { data: 'inspect_ok', class:'text-right', render: function(data) {
                    return numberWithCommas(data);
                }},
                { data: 'inspect_ng', class:'text-right', render: function(data) {
                    return numberWithCommas(data);
                }},
                { data: 'buy_price', class:'text-right', render: function(data) {
                    return numberWithCommas(data);
                }},
                { data: 'warehouse', class:'text-left'},
                { data: 'item_type', class:'text-left'},
                { data: 'order_receive_no', class:'text-left'},
                { data: 'priority', class:'text-left',render: function(data, type, row, meta){
                        if(row.priority){
                            return row.priority;
                        }
                        return '';
                    }},
                    { data: 'action', class:'text-center', render: function(data, type, row, meta) {
                        var allocateItemSttArr = '<?php echo ALLOCATE_ITEM_STATUS;?>'.split(",");
                        var orderKbn = $form.find('[name="order_kbn"]').val();
                        var warehouseSubStr = row.warehouse.substring(0, 5);
                        if((allocateItemSttArr.indexOf(row.status_id) == -1)
                            || ((row.salesman !== '<?php echo SALESMAN_FREE; ?>') && (row.salesman !== $form.find('[name="salesman"]').val()))
                            // || (orderKbn === '<?php echo ANOTHER_ORDER; ?>' && warehouseSubStr === '<?php echo WAREHOUSE_SMDJP; ?>')
                            // || (orderKbn === '<?php echo JAPAN_ORDER; ?>' && warehouseSubStr === '<?php echo WAREHOUSE_SMDVN; ?>')
                            || (row.warehouse !== $form.find('[name="identify_name"]').val())
                            || (row.arrival_status=='')
                            || (row.inspect_status=='')
                            || (parseFloat(row.inspect_ok)<=0)
                            || (row.invoice_no=='')
                            ) {
                            return '<input type="checkbox"  name="choose_allocation" class="flat" available="0" value="'+(meta.row)+'" disabled>'
                        }
                        return '<input type="checkbox"  name="choose_allocation" class="flat" available="1" value="'+(meta.row)+'" '+(row.check ? "checked" : "")+'>'
                    }},
            ],
            "ajax": {
                url : "<?php echo base_url("inventory/search_allocation") ?>",
                type : 'GET',
                data: function(data) {
                    var arr = $('#product_allocate_modal form').serializeArray();
                    $.each(arr, function(index, item) {
                        if (item.value != ''){
                            data[item.name] = item.value;
                        }
                    });
                    // console.log(data);
                }
            },
            });
        } else {
            allocateItemsDataTB.clear().draw();
            allocateItemsDataTB.ajax.reload();
        }
    });
    var allocatingItem = null;
    $form = $('#product_allocate_modal form');
    $('#items_orders_list').on('click', '[data-event="product-allocate"]', function() {
        var tr = $(this).closest("tr");
        var item = $('#items_orders_list').DataTable().row(tr).data();
        var parentTableData = $("#items_orders_list").DataTable().rows().data();
        if(parentTableData && parentTableData.length <= 1){
        $('#move_to').iCheck('disable');
        }else{
            $('#move_to').iCheck('enable');
        }
        allocatingItem = item;
        $form.find('[name="order_receive_no"]').val(item.order_receive_no);
        $form.find('[name="partition_no"]').val(item.partition_no);
        $form.find('[name="order_receive_date"]').val(item.order_receive_date);
        $form.find('[name="order_receive_detail_no"]').val(item.order_receive_detail_no);
        $form.find('[name="item_code"]').val(item.item_code);
        $form.find('.inventory_link').attr('href',function(item_code){ return base_url + 'inventory?item_code=' + item.item_code});
        $form.find('[name="item_name"]').val(item.item_name);
        $form.find('[name="color"]').val(item.color);
        $form.find('[name="size"]').val(item.size);
        $form.find('[name="quantity"]').val(parseFloat(item.quantity));
        $form.find('[name="salesman"]').val(item.salesman);
        $form.find('[name="hikiate_quantity"]').val('0');
        $form.find('[data-binding="size_unit"]').text(item.size_unit);
        $form.find('[name="hikiate_quantity"]').parent().removeClass('has-error').removeClass('has-success');   
        $('#product_allocate_modal').modal('show');
    });

    // UnAllocate
    $('#items_orders_list').on('click', '[data-event="product-unallocate"]', function(){
        var $tr = $(this).closest("tr");
        var item = $('#items_orders_list').DataTable().row($tr).data();
        bootbox.confirm({
            title: "<?php echo $this->lang->line('ODS0010_I001');?>",
            message: '<h4 style="color:red;">' + item.item_code + '</h4>', 
            buttons: {
                confirm: {
                    label: '<?php echo $this->lang->line('yes'); ?>',
                    className: 'btn-success',
                },
                cancel: {
                    label: '<?php echo $this->lang->line('no'); ?>',
                    className: 'btn-primary',
                }
            },
            callback: function (result) {
                if (result) {
                    $.ajax({
                        url: '<?php echo base_url('order_received/do_unallocate_item') ?>',
                        type: "POST",
                        data: item,
                        dataType: 'json',
                        async: false,
                        success: function(response) {
                            snackbarShow(response.message);
                            if (response.success) {
                                $('#items_orders_list').DataTable().ajax.reload();
                            }
                        }
                    });
                }
            }
        });
    });
    
    var $selectedInventoryRow = null;
    var $selectedButton = null;
    var selectedInventoryItem;
    $('#product_allocate_modal table').on('click', '[data-event="select-item"]', function(){
        if ($selectedInventoryRow) {
            $selectedInventoryRow.removeClass('selected');
        }
        if ($selectedButton) {
            $selectedButton.attr('disabled', false);
        }
        $selectedButton = $(this);
        $selectedButton.attr('disabled', true);
        var $tr = $(this).closest("tr");
        $selectedInventoryRow = $tr;
        $selectedInventoryRow.addClass('selected');
        selectedInventoryItem = allocateItemsDataTB.row($tr).data();
        var hikiate_quantity = Math.min(selectedInventoryItem.quantity, allocatingItem.quantity);
        $form.find('[name="hikiate_quantity"]').val(hikiate_quantity);
        if ( hikiate_quantity == allocatingItem.quantity) {
            $form.find('[name="hikiate_quantity"]').parent().removeClass('has-error').addClass('has-success');
        } else {
            $form.find('[name="hikiate_quantity"]').parent().addClass('has-error').removeClass('has-success');
        }
    });
    $('[data-event="save-allocation"').click(function(){
            var arr = $form.serializeArray();
            var data = {};
            $.each(arr, function(index, item) {
                data[item.name] = item.value;
            });
            if ((typeof data.move_to === 'undefined' || data.move_to === null) &&  globalAllocateList.length <= 0) {
                snackbarShow('<?php echo $this->lang->line('ODS0010_E002')?>');
               return;
            }
            selectedInventoryList = [];
            globalAllocateList.forEach(function(ele){
                var dt = allocateItemsDataTB.row(ele).data();
                selectedInventoryList.push(dt);
            });
            data['selectedInventoryItem'] = selectedInventoryList;
            $.ajax({
                url: '<?php echo base_url('order_received/do_allocation') ?>',
                type: "POST",
                data: data,
                dataType: 'json',
                async: false,
                success: function(response) {
                    $('#product_allocate_modal').modal('hide');
                    if (response.success) {
                        window.location.href = '<?php echo base_url() . 'order_received?order_receive_no=' . trim($orderReceived['order_receive_no']); ?>';
                    } else {
                        snackbarShow(response.message);
                    }
                },
                error: function(error) {
                    $('#product_allocate_modal').modal('hide');
                    bootbox.alert({
                        message: error.statusText,
                    });
                }
            });
        });
        $('#product_allocate_modal').on('ifChecked', '#move_to',function(event){
            $('#next_qty').attr('readonly',false);
            $('#next_qty').val('');
            $('#allocate_products_list input[name="choose_allocation"]').each( function ( element ) {
                if($(this).attr('available') == '1'){
                    $(this).prop('checked',false).prop('disabled',true).iCheck('update');
                    $(this).closest('tr').removeClass( "selected");
                    globalAllocateList = [];
                }
            });
        });
        $('#product_allocate_modal').on('ifUnchecked', '#move_to', function(event){
            $('#next_qty').attr('readonly',true);
            $('#next_qty').val('');
            $('#allocate_products_list input[name="choose_allocation"]').each( function ( element ) {
                if($(this).attr('available') == '1'){
                    $(this).iCheck('enable');
                }
            });
        });
        $("#inventory_change_modal select").change(function(){
            $("#inventory_change_modal .btnOk").attr("disabled", false);
        });

        $("#inventory_change_modal input").on("focus",function(){
            $("#inventory_change_modal .btnOk").attr("disabled", false);
        });

        $("#inventory_change_modal").on('hidden.bs.modal', function(){
            $("#inventory_change_modal .btnOk").attr("disabled", true);
        });

        $('#product_allocate_modal table').on('click', '[data-event="inventory-edit"]', function(){
            var $tr = $(this).closest("tr");
            selectedInventoryItem = allocateItemsDataTB.row($tr).data();
            $('#partition_no_change').val(selectedInventoryItem.partition_no);
            $('#oldsalesmanchange').val(selectedInventoryItem.salesman);
            $('#salesmanchange').val(selectedInventoryItem.salesman);
            $('#oldwarehousechange').val(selectedInventoryItem.warehouse);
            $('#warehousechange option').each(function(element){
                if ($(this).text().trim() === selectedInventoryItem.warehouse.trim()){
                    $(this).prop('selected', true);
                }
            });
            $('#old_inv_no').val(selectedInventoryItem.invoice_no);
            $('#invoice_no').val(selectedInventoryItem.invoice_no);
            $('#inventory_change_modal').modal('show');
        });
        $('#submit_inventory').click(function(){
            var data = selectedInventoryItem;
            var arr = $form.serializeArray();
            $.each(arr, function(index, item) {
                if(item.name.trim() == 'order_receive_no' || item.name.trim() == 'item_type'){
                    return;
                }
                data[item.name] = item.value;
            });
            arr = $('#edit_inventory_form').serializeArray();
            $.each(arr, function(index, item) {
                data[item.name] = item.value;
            });
            data['item_type'] = data['item_type_code'];
             $.ajax({
                url: '<?php echo base_url('order_received/do_change_inventory') ?>',
                type: "POST",
                data: data,
                dataType: 'json',
                async: false,
                success: function(response) {
                    $('#inventory_change_modal').modal('hide');
                    if (response.success) {
                        snackbarShow('<?php echo $this->lang->line('COMMON_I002');?>');
                        allocateItemsDataTB.ajax.reload();
                    } else {
                        snackbarShow(response.message);
                    }
                }
            });
            
        });
    $('#allocate_products_list').on('ifChecked', '[name="choose_allocation"]', function(){
            var row = $(this).closest('tr');
            var data = allocateItemsDataTB.row(row).data();
            data.check = true;
            data.priority = null;
            row.addClass( "selected" );
            allocateItemsDataTB.row(row).data(data).invalidate();
            globalAllocateList.push(row.index());
            checkAllocate();
        });
        $('#allocate_products_list').on('ifUnchecked', '[name="choose_allocation"]', function(){
            var row = $(this).closest('tr');
            var data = allocateItemsDataTB.row(row).data();
            data.check = false;
            data.priority = null;
            row.removeClass( "selected" );
            allocateItemsDataTB.row(row).data(data).invalidate();
            globalAllocateList.push(row.index());
            globalAllocateList = jQuery.grep(globalAllocateList, function(value) {
                return value != row.index();
            });
            checkAllocate();
        });
        function checkAllocate(){
            var inspect_ok = 0;
            globalAllocateList.forEach(function(ele, index){
                var data = allocateItemsDataTB.row(ele).data();
                inspect_ok += parseFloat(data.inspect_ok);
                data.priority = (index+1);
                allocateItemsDataTB.row(ele).data(data).invalidate();
            });
            allocateItemsDataTB.draw();
            var hikiate_quantity = Math.min(inspect_ok, allocatingItem.quantity);
            $form.find('[name="hikiate_quantity"]').val(hikiate_quantity);
            if ( hikiate_quantity == allocatingItem.quantity) {
                $('#allocate_products_list input[name="choose_allocation"]').each( function ( element ) {
                    if($(this).attr('available') == '1'){
                        if(!this.checked){
                            $(this).iCheck('disable');
                        }
                    }
                });
            }else{
                $('#allocate_products_list input[name="choose_allocation"]').each( function ( element ) {
                    if($(this).attr('available') == '1' && $('#move_to').parent().hasClass('checked') == false){
                        $(this).iCheck('enable');
                    }
                });
            }
        }
  });
  function convertPrice(item, price){
      let postfix = ('_'+orderReceived.currency).toLowerCase();
      let pricePostfix = price+postfix;
      if(item[pricePostfix]){
          return parseFloat(item[pricePostfix]).myToFixed(orderReceived.currency);
      }else{
          if(orderReceived.currency == 'VND'){
              pricePostfix = (price+'_usd').toLowerCase();
              if(orderReceived.rate_usd && item[pricePostfix]){
                  return (parseFloat(orderReceived.rate_usd) * parseFloat(item[pricePostfix])).myToFixed(orderReceived.currency);
              }
              pricePostfix = (price+'_jpy').toLowerCase();
              if(orderReceived.rate_jpy && item[pricePostfix]){
                  return (parseFloat(orderReceived.rate_jpy) * parseFloat(item[pricePostfix])).myToFixed(orderReceived.currency);
              }
          }else if(orderReceived.currency == 'JPY'){
            pricePostfix = (price+'_vnd').toLowerCase();
            if(orderReceived.rate_jpy && item[pricePostfix]){
                return (parseFloat(item[pricePostfix])/parseFloat(orderReceived.rate_jpy)).myToFixed(orderReceived.currency);
            }
            pricePostfix = (price+'_usd').toLowerCase();
            if(orderReceived.rate_usd && orderReceived.rate_jpy && item[pricePostfix]){
                let priceVND = (parseFloat(item[pricePostfix])*parseFloat(orderReceived.rate_usd));
                return (priceVND/parseFloat(orderReceived.rate_jpy)).myToFixed(orderReceived.currency);
            }
          }else if(orderReceived.currency == 'USD'){
            pricePostfix = (price+'_vnd').toLowerCase();
            if(orderReceived.rate_usd && item[pricePostfix]){
                return (parseFloat(item[pricePostfix])/parseFloat(orderReceived.rate_usd)).myToFixed(orderReceived.currency);
            }
            pricePostfix = (price+'_jpy').toLowerCase();
            if(orderReceived.rate_usd && orderReceived.rate_jpy && item[pricePostfix]){
                let priceVND = (parseFloat(item[pricePostfix])*parseFloat(orderReceived.rate_jpy));
                return (priceVND/parseFloat(orderReceived.rate_usd)).myToFixed(orderReceived.currency);
            }
        }
      }
      return '';
  }
</script>
<!-- End Modal -->
