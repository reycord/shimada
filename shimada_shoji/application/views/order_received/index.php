<?php //echo '<pre>';// print_r($orderReceivedDetails);
// print_r($orderReceivedList); echo '</pre>';?>
<style>
	td div.ellipsis {
    white-space: nowrap !important; 
    max-width: 150px !important; 
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
</style>
<div class="row">
    <form id="search_form" class="form-horizontal form-label-left" action="<?php echo base_url() ?>order_received" method="get">
        <input type="hidden" value="<?php echo $this->input->get('part_no') ?>" name= "part_no" />
        <div class="form-group">
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-right" for="order_no"><?php echo $this->lang->line('order_received_no'); ?></label>
                <div class="col-md-7 col-sm-7 col-xs-7 has-clear has-feedback">
                    <input 
                        type="text" 
                        id="order_receive_no" 
                        name="order_receive_no" 
                        maxlength="50"
                        class="form-control text-uppercase" 
                        value="<?php echo $this->input->get('order_receive_no') ?>"
                    />
                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-right" for="inputuser"><?php echo $this->lang->line('input_user'); ?></label>
                <div class="col-md-7 col-sm-7 col-xs-7">
                	<select name="input_user" id="inputuser" class="form-control no-padding-right">
                        <option value=""></option>
                        <?php foreach ($employeeList as $employee): ?>
                            <option value="<?php echo ($employee['employee_id']) ?>"
                                <?php if ($this->input->get('input_user') == $employee['employee_id']): ?>
                                    selected
                                <?php endif ?>
                            >
                                <?php echo ($employee['first_name']) ?>
                                <?php echo ($employee['last_name']) ?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
                <label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" for="created_from"><?php echo $this->lang->line('order_received_date'); ?></label>
                <div class="col-md-8 col-sm-8 col-xs-8 no-padding-left">
                    <div class="col-md-5 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                        <div class="input-group hasDatepicker date">
                            <input 
                                type='text' 
                                class="form-control" 
                                name='order_receive_date_from' 
                                maxlength="10"
                                value="<?php echo $this->input->get('order_receive_date_from') ?>"
                            />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right no-padding-top" for="created_to" style="text-align: center; font-size: 20px;">&sim;</label>
                    <div class="col-md-5 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                        <div class="input-group hasDatepicker date">
                            <input 
                                type='text' 
                                class="form-control" 
                                name='order_receive_date_to' 
                                maxlength="10"
                                value="<?php echo $this->input->get('order_receive_date_to') ?>"
                            />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
			</div>
        </div>
        <div class="form-group">
			<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <label 
                    class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-right" 
                    for="status"><?php echo $this->lang->line('status'); ?>
                </label>
                <div class="col-md-7 col-sm-7 col-xs-7">
                    <select name="status" id="status" class="form-control no-padding-right">
                        <option value=""></option>
                        <?php foreach ($statusList as $ele): ?>
                            <option value="<?php echo $ele['status_id'] ?>"
                                <?php if($this->input->get('status') == $ele['status_id']): ?>
                                    selected
                                <?php endif ?>
                            >
                                <?php echo $ele['status_name'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
			</div>
			<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-right" for="item"><?php echo $this->lang->line('item'); ?></label>
                <div class="col-md-7 col-sm-7 col-xs-7 has-clear has-feedback">
                    <input 
                        type="text" 
                        id="item" 
                        name="item" 
						maxlength="100"
                        class="form-control text-uppercase"
                        value="<?php echo $this->input->get('item') ?>"
                    />
                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                </div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
                <label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" for="customer"><?php echo $this->lang->line('customer'); ?></label>
                <div class="col-md-9 col-sm-9 col-xs-9 no-padding-left">
                    <div class="col-md-8 col-sm-8 col-xs-8 has-clear has-feedback no-padding-left no-padding-right">
                        <input 
                            type="text" id="customer" 
                            name="customer"
                            maxlength="100"
                            class="form-control text-uppercase"
                            value="<?php echo $this->input->get('customer') ?>"
                        />
                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
                    </div>
                    <label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right no-padding-top" for="created_to" style="text-align: center; font-size: 20px;"></label>
                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" style="text-align: right;">
                        <button 
                            type="submit" 
                            class="btn btn-info" 
                            style="margin-right: 0;">
                            <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                        </button>
                    </div>
                </div>
			</div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $this->lang->line('order_received_list'); ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-primary" onclick="window.location.href='<?php echo base_url(); ?>order_received/add'">
                            <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('order_received_add'); ?>
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive dragscroll">
                    <table 
                        style="cursor:move;"
                        id="orders_list" 
                        class="table table-striped table-bordered cssTable display nowrap" 
                        width="100%" 
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('order_received_no'); ?></th>
                                <th><?php echo $this->lang->line('partition_no'); ?></th>
                                <th><?php echo $this->lang->line('order_received_date'); ?></th>
                                <th><?php echo $this->lang->line('customer'); ?></th>
                                <th><?php echo $this->lang->line('input_user'); ?></th>
                                <th><?php echo $this->lang->line('wish_delivery_date'); ?></th>
                                <th><?php echo $this->lang->line('status'); ?></th>
                                <th><?php echo $this->lang->line('quantity'); ?></th>
                                <th><?php echo $this->lang->line('amount'); ?></th>
                                <th><?php echo $this->lang->line('currency'); ?></th>
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
							<div class="col-md-3 no-padding-right">
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
									value="" 
									readonly
								/>
                            </div>
                            <div class="col-md-2 text-right" style="padding-top: 5px;">
                                <input type="checkbox" class="flat" id="move_to" name="move_to" value="1" >
                            </div>
                            <label class="col-md-3 text-left no-padding-left no-padding-right" style="padding-top: 5px;" for="move_to">
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
							<div class="col-md-6" id="another_order">
								<span class="form-control" style="color: red" readonly><?php echo $this->lang->line('another_order'); ?></span>
								<input
									type="hidden"
									id="order_kbn"
									name="order_kbn"
									readonly
								/>
							</div>
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
							<div class="col-md-2" style="text-align: right;">
								<!-- <p style="padding-top: 10px;">
									<input type='hidden' value='' name=''>
									<input 
										type="checkbox" 
										class="flat" 
										name="" 
										id="" 
										value=""
									>
								</p> -->
							</div>
							
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
<!-- Item Inventory Change -->
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
    var globalAllocateList = []; 
    var dataFilterCustomer = [];
    var customerList = <?php echo json_encode($customer_list); ?>;
    window.onload = (function() {

        customerList.forEach(function(el){
            dataFilterCustomer.push({value: el.company_name, data : el.company_id});
        });
        $("#customer").autocomplete({
            lookup: dataFilterCustomer,
            minChars: 0,
            width: '400px',
            onSelect: function(){
                $(this).change();
            }
        });

        var new_data = [];
        var statusFinishList = '<?php echo STATUS_FINISH; ?>'.split(",");
        var table = $("#orders_list").DataTable({
            ordering: true,
            filter: false,
            "order": [[ 2, "desc" ]],
            // columnDefs: [ { orderable: false, targets: [3,4,5,6,7,8,9,10] }],
            columns: [
                { data: 'order_receive_no', "class": 'text-left', render: function( data, type, row, meta ){
                    var detailUrl = '<?php echo base_url('order_received/edit/') ?>'
                        + row.urlPrimaryKey.trim()
                    $a = $('<a></a>')
                        .addClass('edit')
                        .attr('href', detailUrl)
                        .text(row.order_receive_no.trim());
                    $toggle = $('<span></span>')
                        .addClass('collapse-order glyphicon glyphicon-triangle-right')
                    return $toggle.prop('outerHTML') + ' ' + $a.prop('outerHTML');
                    
                }},
                { data: 'partition_no', render: function(data, type, row, meta ){
                        if(new_data.length > 0) {
                            var checkedData = new_data.filter(function(item){
                                return (row.order_receive_no == item.order_receive_no &&
                                        row.order_receive_date == item.order_receive_date)
                            });
                            if(checkedData && checkedData.length > 1) {
                                row.partition_no_show = data;
                                return data;
                            } else {
                                row.partition_no_show = "";
                                return "";
                            }
                        } else {
                            var count = 0;
                            <?php foreach($orderReceivedList as $ele): ?>
                                if(row.order_receive_no == '<?php echo $ele['order_receive_no']; ?>' && 
                                    row.order_receive_date == '<?php echo $ele['order_receive_date']; ?>') {
                                    count++;
                                }
                            <?php endforeach ?>
                            if(count > 1) {
                                row.partition_no_show = data;
                                return data;
                            } else {
                                row.partition_no_show = "";
                                return "";
                            }
                        }
                    }
                },
                { data: 'order_receive_date' },
                { data: 'customer', "class": 'text-left'},
                { data: 'input_user', "class": 'text-left', render: function(data, type, row, meta ){
                        <?php foreach($employeeList as $ele): ?>
                            if(data === '<?php echo $ele['employee_id']; ?>') {
                                return '<?php echo $ele['first_name'] . ' ' . $ele['last_name']; ?>';
                            }
                        <?php endforeach ?>
                        return data;
                    }
                },
                { data: 'delivery_date' },
                { data: 'status', render: function(data, type, row, meta ){
                        var statusName = "";
                        <?php foreach($statusList as $ele): ?>
                            if(data == '<?php echo $ele['status_id']; ?>') {
                                statusName = '<?php echo $ele['status_name']; ?>';
                                return statusName;
                            }
                        <?php endforeach ?>
                        return statusName;
                    }
                },
                { data: 'sum_quantity', "class": 'text-right', render: function(data, type, row, meta) {
                    var el = "";
                    Object.keys(data).forEach(function(key) {
                        el += el ==""? data[key]+" " + key : "," + data[key]+" " + key;
                    });
                    return el;
                }},
                { data: 'sum_amount', "class": 'text-right', render: function(data, type, row, meta) {
                    if(row.sum_amount){
                        var amount = parseFloat(row.sum_amount);
                        return numberWithCommas(amount.myToFixed(row.currency));
                    }
                    return row.sum_amount;
                    }
                },
                { data: 'currency' },
                { data: 'action', className: 'text-center', render: function(data, type, row, meta) {
                    var status = row.status;
                    var statusFinishResult = statusFinishList.indexOf(status) >= 0 ? 'class="btn btn-sm btn-custom btn-default" disabled' : 'class="btn btn-sm btn-custom btn-danger" data-event="delete"';
                    $html = '<div class="input-group" style="padding-left: 1px;">'
                        + '<a onclick="show_modal_pi(this)" data-toggle="modal" class="btn btn-success btn-sm btn-custom no-padding-top no-padding-bottom" title="<?php echo $this->lang->line('proforma_invoice'); ?>">'
                        + '<span>P.I</span>'
                        + '</a>';
                    $html += '<span style="padding-left: 1px;">'
                        + '<a ' + statusFinishResult + ' title="<?php echo $this->lang->line('delete'); ?>">'
                        + '<span class="glyphicon glyphicon-trash"></span>'
                        + '</a>'
                        + '</span>';
                    return $('<div class="input-group"></div>')
                        .append($html)
                        .prop('outerHTML');;
                }},
            ],
            "processing": true,
            "serverSide": true,
            "createdRow": function(row, data, dataIndex) {
                if(data.kubun == '2'){
                    $(row).css('background-color', 'rgb(255, 232, 217)');
                }
            },
            "ajax": {
                url : "<?php echo base_url("order_received/search") ?>",
                type : 'post',
                data: function ( data ) {
                    data.param = {};
                    var arr = $('#search_form').serializeArray();
                    $.each(arr, function(index, item) {
                        if(item.value != '') {
                            data.param[item.name] = item.value;
                        }
                    });
                }
            },
        });
        
        var orderID = "<?php if(isset($orderID)){echo $orderID;}else{ echo '';}?>";
        if(orderID != '') {
            $('#order_receive_no').val(orderID);
            table.ajax.reload();
        }

        $('#orders_list').on('click', 'span.collapse-order', function(){
            var tr = $(this).closest("tr");
            var row = table.row(tr);
            if (row.child.isShown()) {
                $(this).removeClass("glyphicon-triangle-bottom")
                row.child.hide();
                tr.removeClass("shown");
            } else {
                $(this).addClass("glyphicon-triangle-bottom");
                row.child(renderChildRow(row.data())).show();
                tr.addClass("shown");
            }
        });
        $('#orders_list').on('click', 'a[data-event="delete"]', function(){
            var tr = $(this).closest("tr");
            var row = table.row(tr);
            var rowData = row.data();
            bootbox.confirm({
                title: "<?php echo $this->lang->line('JOS0010_I004');?>",
                message: '<label class="red">' + rowData.order_receive_no + '</label>', 
                buttons: {
                    confirm: {
                        label: '<?php echo $this->lang->line('yes');?>',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: '<?php echo $this->lang->line('no');?>',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if (result) {
                        $.ajax({
                            url: '<?php echo base_url('order_received/delete')?>',
                            type: "POST",
                            data: { 
                                order_receive_no: rowData.order_receive_no,
                                partition_no: rowData.partition_no,
                                order_receive_date: rowData.order_receive_date,
                            },
                            success: function(response) {
                                new_data = response.data;
                                table.draw(false);
                            },
                            error: function(error) {
                                // console.log(error);
                                bootbox.alert(error);
                            }
                        });
                    }
                }
            });
        });

        function renderChildRow(orderReceive) {
            var statusFinishResult = statusFinishList.indexOf(orderReceive.status) >= 0 ? true : false;
            var el =
                "<table class='table table-striped table-dashed datatable display nowrap' width='100%' cellspacing='0'>" +
                "<thead style='background-color:#d2ecc0'>" +
                "<tr>" +
                "<th>No</th>" +
                "<th>" + lang['item_code'] + "</th>" +
                "<th>" + lang['item_name'] + "</th>" +
                "<th>" + lang['composition'] + "</th>" +
                "<th>" + lang['size'] + "</th>" +
                "<th>" + lang['color'] + "</th>" +
                "<th>" + lang['quantity'] + "</th>" +
                "<th>" + lang['hikiate_quantity'] + "</th>" +
                "<th>" + lang['unit'] + "</th>" +
                "<th>" + lang['sell_price'] + "</th>" +
                "<th>" + lang['total_amount'] + "</th>" +
                "<th>" + lang['currency'] + "</th>" +
                "<th>" + lang['status'] + "</th>" +
                "<th>" + lang['action'] + "</th>" +
                "</tr>" +
                "</thead>" +
                "<tbody>";
            if (orderReceive.details == null || orderReceive.details.length <= 0) {
                el += '<tr><td colspan="14">' + lang['data_not_available'] + '</td></tr>';
            } else {
                for (var i = 0; i < orderReceive.details.length; i++) {
                    var item = orderReceive.details[i];
                    var allocated = item.status != null && item.status.trim()  == '1';
					if(item.amount){
						var amount = parseFloat(item.amount);
						item.amount = numberWithCommas(amount.myToFixed(orderReceive.currency));
					}
                    // console.log(item);
                    el +=
                        "<tr data-item='" + JSON.stringify(item) + "'>" +
                        "<td>" +
                        (i + 1) +
                        "</td>" +
                        "<td class='text-left'>" +
                        item.item_code +
                        "</td>" +
                        "<td class='text-left'>" +
                        item.item_name +
                        "</td>" +
                        "<td class='text-left'> <div class='ellipsis' title=' "+(item.composition_1 || "") + " " + (item.composition_2 || "") + " " + ( item.composition_3 || "") +"' >" +
                        (item.composition_1 || '') + ' ' + (item.composition_2 || '') + ' ' + ( item.composition_3 || '') +
                        "</div></td>" +
                        "<td>" +
                        (item.size || '') + ' ' + (item.size_unit || '') +
                        "</td>" +
                        "<td>" +
                        (item.color || '') +
                        "</td>" +
                        "<td class='text-right'>" +
                        (numberWithCommas(item.quantity) || '') +
                        "</td>" +
                        "<td class='text-right'>" +
                        (numberWithCommas(item.hikiate_quantity) || '') +
                        "</td>" +
                        "<td>" +
                        (item.unit  || '') +
                        "</td>" +
                        "<td class='text-right'>" +
                        (numberWithCommas(item.sell_price)  || '') +
                        "</td>" +
                        "<td class='text-right'>" +
                        (item.amount || '') +
                        "</td>" +
                        "<td>" +
                        (orderReceive.currency  || '') +
                        "</td>" +
                        "<td>" +
                        (allocated ? '<?php echo $this->lang->line("allocate") ?>' : '<?php echo $this->lang->line("unallocate") ?>') +
                        "</td>" +
                        "<td>"
                            + '<a class="btn ' + (allocated||statusFinishResult ? 'btn-info' : 'btn-warning') + ' btn-sm btn-custom" title="<?php echo $this->lang->line('product_allocation'); ?>" ' 
                            + (allocated||statusFinishResult ? 'data-event="product-unallocate"' : 'data-event="product-allocate"')
                            + '>'
                            + (allocated||statusFinishResult ? '<?php echo $this->lang->line("unallocate") ?>' : '<?php echo $this->lang->line('product_allocate'); ?>')
                            + '</a>' +
                        "</td>" +
                        "</tr>";
                }
            }
            el += "</tbody>" + "</table>";
            return el;
        }
        
        $('#btnSearch').click(function(){
            table.ajax.reload();
        });

        var allocateItemsDataTB;
        $('#product_allocate_modal').on('shown.bs.modal', function() {
            $('#move_to').iCheck('uncheck');
            globalAllocateList = [];
            if (!allocateItemsDataTB) {
                allocateItemsDataTB = $("#allocate_products_list").DataTable({
                "scrollX": true,
                // aaSorting: []
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
                    // { data: 'item_code', class:'text-left'},
                    // { data: 'item_name', class:'text-left'},
                    { data: 'invoice_no', class:'text-left'},
                    { data: 'arrival_date'},
                    { data:'quantity',className: "text-right", render: function(data){
                        return numberWithCommas(data);
                    }},
                    { data: 'status', class:'text-center'},
                    { data: 'arrival_status', class:'text-center'},
                    { data: 'inspect_status', class:'text-center'},
                    { data:'inspect_ok',className: "text-right", render: function(data){
                        return numberWithCommas(data);
                    }},
                    { data:'inspect_ng',className: "text-right", render: function(data){
                        return numberWithCommas(data);
                    }},
                    { data:'buy_price',className: "text-right", render: function(data){
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
                    data: function ( data ) {
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
        $('#orders_list').on('click', '[data-event="product-allocate"]', function(){
            var $tr = $(this).closest("tr");
            var $parentTR = $(this).closest("table").closest("tr").prev();
            var parentTableData = table.row($parentTR).data();
            var item = $tr.data('item');
            allocatingItem = item;
            $form.find('[name="order_receive_no"]').val(item.order_receive_no);
            $form.find('[name="partition_no"]').val(item.partition_no);
            $form.find('[name="partition_no_show"]').val(parentTableData.partition_no_show);
            $form.find('[name="order_receive_date"]').val(item.order_receive_date);
            $form.find('[name="order_receive_detail_no"]').val(item.order_receive_detail_no);
            $form.find('[name="item_code"]').val(item.item_code);
            $form.find('.inventory_link').attr('href',function(item_code){ return 'inventory?item_code=' + item.item_code});
            $form.find('[name="item_name"]').val(item.item_name);
            $form.find('[name="color"]').val(item.color);
            $form.find('[name="size"]').val(item.size);
            $form.find('[name="quantity"]').val(parseFloat(item.quantity));
            $form.find('[name="hikiate_quantity"]').val('0');
            $form.find('[name="salesman"]').val(parentTableData.staff);
            $form.find('[data-binding="size_unit"]').text(item.size_unit);
            if(parentTableData.kubun === '<?php echo JAPAN_ORDER; ?>') {
                $('#another_order').css('display', 'none');
            }else{
                $('#another_order').css('display', 'block');
            }
            $form.find('[name="order_kbn"]').val(parentTableData.kubun);
            $form.find('[name="identify_name"]').val(parentTableData.identify_name_1);
            $form.find('[name="hikiate_quantity"]').parent().removeClass('has-error').removeClass('has-success');
            if((parentTableData.details) && (parentTableData.details).length <= 1){
                $('#move_to').iCheck('disable');
            }else{
                $('#move_to').iCheck('enable');
            }
            $('#product_allocate_modal').modal('show');
        });
        // UnAllocate
         $('#orders_list').on('click', '[data-event="product-unallocate"]', function(){
            var $tr = $(this).closest("tr");
            var item = $tr.data('item');
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
                                    table.ajax.reload();
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
            var hikiate_quantity = Math.min(selectedInventoryItem.inspect_ok, allocatingItem.quantity);
            $form.find('[name="hikiate_quantity"]').val(parseFloat(hikiate_quantity));
            if ( hikiate_quantity == allocatingItem.quantity) {
                $form.find('[name="hikiate_quantity"]').parent().removeClass('has-error').addClass('has-success');
            } else {
                $form.find('[name="hikiate_quantity"]').parent().addClass('has-error').removeClass('has-success');
            }
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
                        snackbarShow('<?php echo $this->lang->line('COMMON_I002');?>');
                        new_data = response.data;
                        table.draw(false);
                        globalAllocateList = [];
                    } else {
                        snackbarShow(response.message);
                    }
                },
                error: function(error) {
                    $('#product_allocate_modal').modal('hide');
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
</script>
