<div class="row">
    <form id="search_form" class="form-horizontal form-label-left" method="get" action="<?php echo base_url('inventory'); ?>">
        <div class="form-group">
            <label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right" for="salesman"><?php echo $this->lang->line('sales_man'); ?></label>
            <div class="col-md-2 col-sm-2 col-xs-2 has-clear has-feedback">
                <input class="form-control" type="text" value="<?php echo $this->input->get('salesman') ?>" id="salesman" name="salesman" />
                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                <!-- <select class="form-control" id="salesman" name="salesman">
                    <option value=""></option>
                    <?php foreach ($customer_list as $customer): ?>
                    <option
                        value="<?php echo trim($customer['komoku_name_2']); ?>" <?php echo $this->input->get('salesman')===$customer['komoku_name_2']?'selected':'';?>><?php echo $customer['komoku_name_2']; ?></option>
                    <?php endforeach?>
                </select> -->
            </div>
            <label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right" for="select_date"><?php echo $this->lang->line('date_by'); ?></label>
            <div class="col-md-2 col-sm-2 col-xs-2">
                <select class="form-control" id="select_date" name="select_date">
                    <option value="arrival_date" <?php echo $this->input->get('select_date')==='arrival_date'?'selected':'';?>>Arrival Date</option>
                    <option value="inspect_date" <?php echo $this->input->get('select_date')==='inspect_date'?'selected':'';?>>Inspection Date</option>
                    <option value="create_date" <?php echo $this->input->get('select_date')==='create_date'?'selected':'';?>>Create Date</option>
                </select>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4 no-padding-right">
                <div class="col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-right">
                    <div class='input-group date' data-date-format="yyyy/mm/dd">
                        <input type='text' class="form-control" id='created_from' name='created_from' maxlength="12" value="<?php echo $this->input->get('created_from'); ?>"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-top" for="add_date" style="text-align: center; font-size: 20px">&sim;</label>
                <div class="col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-right">
                    <div class='input-group date' data-date-format="yyyy/mm/dd">
                        <input type='text' class="form-control" id='created_to' name='created_to' maxlength="12" value="<?php echo $this->input->get('created_to'); ?>"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2" style="text-align: center;">
                <label class="control-label" style="color:red">
                    <input type="checkbox" id="nggreater0" name="nggreater0" class="flat" value="1" <?php echo !empty($this->input->get('nggreater0'))?"checked":""; ?> style="position: absolute; opacity: 0;">
                        &nbsp;&nbsp; NG > 0 only 
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right" for="item_code"><?php echo $this->lang->line('item_code'); ?></label>
            <div class="col-md-2 col-sm-2 col-xs-2 has-clear has-feedback">
                <input type='text' class="form-control text-uppercase" id='item_code' name='item_code' maxlength="15" value="<?php echo $this->input->get('item_code'); ?>"/>
                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
            </div>
            <label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right" for="item_name"><?php echo $this->lang->line('item_name'); ?></label>
            <div class="col-md-2 col-sm-2 col-xs-2 has-clear has-feedback">
                <input type='text' class="form-control" id='item_name' name='item_name' maxlength="100" value="<?php echo $this->input->get('item_name'); ?>"/>
                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 no-padding-left">
            <label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="warehouse"><?php echo $this->lang->line('warehouse'); ?></label>
            <div class="col-md-3 col-sm-3 col-xs-3">
                <select id="warehouse" name="warehouse" class="form-control">
                    <option value="">ALL</option>
                    <?php $custom_warehouse_list = array_column($warehouse_list, "komoku_name_2");
                        $custom_warehouse_list = array_map(function($value){
                                return explode("_",trim($value))[0];
                            },
                            $custom_warehouse_list
                        );
                        $custom_warehouse_list = array_unique($custom_warehouse_list);
                    ?>
                    <?php foreach ($custom_warehouse_list as $warehouse): ?>
                        <option value="<?php echo $warehouse ?>" <?php echo $this->input->get('warehouse')===$warehouse?'selected':'';?>><?php echo $warehouse; ?></option>
                    <?php endforeach?>
                </select>
            </div>
            <div class="col-md-3 col-sm-2 col-xs-2 no-padding-left has-clear has-feedback">
                <select name="place" id="place" class="form-control no-padding-right">
                    <option value="">ALL</option>
                    <?php $custom_warehouse_list = array_column($warehouse_list, "komoku_name_2");
                        $custom_warehouse_list = array_map(function($value){
                                return explode("_",trim($value),2)[1];
                            },
                            $custom_warehouse_list
                        );
                        $custom_place_list = array_unique($custom_warehouse_list);
                    ?>
                    <?php foreach ($custom_place_list as $place): ?>
                        <option value="<?php echo $place ?>" <?php echo $this->input->get('place')===$place?'selected':'';?>><?php echo $place; ?></option>
                    <?php endforeach?>
                </select>
            </div>
                <div class="col-md-4 col-sm-3 col-xs-3 no-padding-left no-padding-right" style="text-align: right">
                    <button type='button' id="btnSearch" class="btn btn-info"><?php echo $this->lang->line('search'); ?></button>
                    <button type='button' id="btnExportExcel" class="btn btn-success" style="margin-right: 0;"> <i class="fa fa-file-excel-o" ></i> Excel </button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $this->lang->line('inventory_list'); ?></h2>
                <ul class="nav navbar-right panel_toolbox hidden">
                    <li><button class="btn btn-success" onclick="window.location.href='<?php echo base_url(); ?>inventory/add'"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('new'); ?></button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive dragscroll">
                    <table style="cursor: move;" id="products_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead >
                            <tr>
                                <th><?php echo $this->lang->line('item_code'); ?></th>
                                <th><?php echo $this->lang->line('item_name'); ?></th>
                                <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                <th><?php echo $this->lang->line('order_received_no'); ?></th>
                                <th><?php echo $this->lang->line('size'); ?></th>
                                <th><?php echo $this->lang->line('color'); ?></th>
                                <th><?php echo $this->lang->line('quantity'); ?></th>
                                <th><?php echo $this->lang->line('unit'); ?></th>
                                <th><?php echo $this->lang->line('type'); ?></th>
                                <th><?php echo $this->lang->line('warehouse'); ?></th>
                                <th><?php echo $this->lang->line('status'); ?></th>
                                <th><?php echo $this->lang->line('arrival'); ?></th>
                                <th><?php echo $this->lang->line('inspect'); ?></th>
                                <th>OK</th>
                                <th>NG</th>
                                <th><?php echo $this->lang->line('price'); ?></th>
                                <th><?php echo $this->lang->line('sales_man'); ?></th>
                                <th><?php echo $this->lang->line('arrival_date'); ?></th>
                                <th><?php echo $this->lang->line('add_date'); ?></th>
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
<!-- Delete -->
<div id="inventory_del_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('MTS0030_I001'); ?></h4>
            </div>
            <div class="modal-body">
				<label class="red">IT00000001</label>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary antoclose2 btn-primary" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></a>
                <a type="button" class="btn btn-default antosubmit2 btnOk btn-success"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Keep -->
<div id="inventory_keep_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('STR0010_I001'); ?></h4>
            </div>
            <!-- <div class="modal-body">
				<label class="red">IT00000001</label>
            </div> -->
            <div class="modal-footer">
                <a type="button" class="btn btn-primary antoclose2 btn-primary" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></a>
                <a type="button" class="btn btn-default antosubmit2 btnOk btn-success"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>
<!-- Keep -->
<div id="inventory_end_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('STR0010_I006'); ?></h4>
            </div>
            <!-- <div class="modal-body">
				<label class="red">IT00000001</label>
            </div> -->
            <div class="modal-footer">
                <a type="button" class="btn btn-primary antoclose2 btn-primary" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></a>
                <a type="button" class="btn btn-default antosubmit2 btnOk btn-success"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Salesman Change -->
<div id="inventory_salesman_change_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('STR0010_I004'); ?></h4>
            </div>
            <div class="modal-body">
                <form id="edit_inventory_form" class="form-horizontal form-label-left" action="" method='post'>
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
                                <?php foreach ($customer_list as $customer): ?>
                                    <option value="<?php echo urlencode(trim($customer['komoku_name_2'])) ?>"><?php echo trim($customer['komoku_name_2'])?></option>
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
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="item_type">
							<?php echo $this->lang->line('type'); ?>
						</label>
						<div class="col-md-4 col-sm-7 col-xs-7">
                            <input data-komoku="" type="text" class="form-control" id="olditem_type" name="olditem_type" value="" readonly>
						</div>
                        <label class="control-label col-md-1 col-sm-5 col-xs-5" style="text-align: center;">
                            <span class="glyphicon glyphicon-chevron-right"></span>
						</label>
                        <div class="col-md-5 col-sm-7 col-xs-7">
                            <select id="item_type" name="item_type" class="form-control">
                                <?php foreach ($item_type_list as $item_type): ?>
                                    <option value="<?php echo trim($item_type['kubun']) ?>"><?php echo trim($item_type['komoku_name_2'])?></option>
                                <?php endforeach?>
                            </select>
						</div>
					</div>
                    <div class="form-group">
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="okquantity">
                            OK <?php echo $this->lang->line('quantity'); ?>
						</label>
						<div class="col-md-4 col-sm-7 col-xs-7">
                            <input type="text" class="form-control" id="oldokquantity" name="oldokquantity" value="" readonly>
						</div>
                        <label class="control-label col-md-1 col-sm-5 col-xs-5" style="text-align: center;">
                            <span class="glyphicon glyphicon-chevron-right"></span>
						</label>
                        <div class="col-md-5 col-sm-7 col-xs-7">
                            <input type="text" class="form-control" id="okquantity" name="okquantity" value="">
						</div>
					</div>
                    <div class="form-group">
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="ngquantity">
                            NG <?php echo $this->lang->line('quantity'); ?>
						</label>
						<div class="col-md-4 col-sm-7 col-xs-7">
                            <input type="text" class="form-control" id="oldngquantity" name="oldngquantity" value="" readonly>
						</div>
                        <label class="control-label col-md-1 col-sm-5 col-xs-5" style="text-align: center;">
                            <span class="glyphicon glyphicon-chevron-right"></span>
						</label>
                        <div class="col-md-5 col-sm-7 col-xs-7">
                            <input type="text" class="form-control" id="ngquantity" name="ngquantity" value="">
						</div>
                        <input type="hidden" class="form-control" id="edit_date" name="edit_date">
					</div>
				</form>
            </div>
            <div class="modal-footer">
                <a type="button" class="btn btn-primary antoclose2 btn-primary" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></a>
                <button type="button" disabled=true class="btn btn-default antosubmit2 btnOk btn-success"><?php echo $this->lang->line('yes'); ?></button>
            </div>
        </div>
    </div>
</div>
<!--Popover history-->
<!-- <a class="btn btn-primary" tabindex="0" data-toggle="popover" data-trigger="click" data-popover-content="#history" data-placement="right">Popover Example</a> -->
<div id="history" style="width:40%; position:absolute; top: -2000px">
    <div class="popover-heading">Change history <span style="float:right;cursor:pointer;" class="fa fa-times close" data-toggle="popover"></span></div>
    <div class="popover-body">
        <div class="table-responsive">
            <table id="history_list" class="table table-striped table-bordered" cellspacing="0">
                <thead >
                    <tr>
                        <!-- <th class="no_sort" width="5%">No</th> -->
                        <th width="27%"><?php echo $this->lang->line('change_date'); ?></th>
                        <th width="15%"><?php echo $this->lang->line('quantity'); ?></th>
                        <th width="15%">OK</th>
                        <th width="13%">NG</th>
                        <th width="30%"><?php echo $this->lang->line('reason'); ?></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!--End Popover history-->
<script>
    var dataFilterSalesman = [];
    var customerList = <?php echo json_encode($customer_list); ?>;
    window.onload = function(){
        customerList.forEach(function(el){
            dataFilterSalesman.push({value: el.komoku_name_2, data : el.kubun});
        });
        var tmpTable;
        var permission_id = '<?php echo $this->session->userdata('user')['permission_id'] ?>';
        var permission_manage = '<?php echo PERMISSION_MANAGER; ?>';
        var status_finish = '<?php echo STATUS_FINISH; ?>';
        var disable_property = permission_id !== permission_manage ? "disabled" : "";
        var table = $("#products_list").DataTable({
            ordering: true,
            filter: false,
            scrollX: false,
            drawCallback: function(){
                $("[data-toggle=popover]").popover({
                    container: "body",
                    html: true,
                    content: function() {
                        var $row = $(this).parents('tr');
                        var rowData = table.row($row).data();
                        $("#history_list").DataTable().destroy();
                        tmpTable = $("#history_list").DataTable({
                            ordering: false,
                            dom: "ti",
                            scrollX: true,
                            scrollY: false,
                            columns: [
                                // { data: 'No', render: function ( data, type, full, meta ) {
                                //         return  meta.row + 1;
                                //     }, class:'text-left'},
                                { data: 'create_date', render: function(data, type, row, meta){
                                        return row.create_date.split(".")[0];
                                    }, class:'text-center'},
								{ data:'quantity',className: "text-right", render: function(data){
									return numberWithCommas(data);
								}},
								{ data:'inspect_ok',className: "text-right", render: function(data){
									return numberWithCommas(data);
								}},
								{ data:'inspect_ng',className: "text-right", render: function(data){
									return numberWithCommas(data);
								}},
                                { data: 'note', class:'text-left'}
                            ],
                            data: rowData.history
                        });
                        var content = $(this).attr("data-popover-content");
                        return $(content).children(".popover-body").html();
                    },
                    title: function() {
                        var title = $(this).attr("data-popover-content");
                        return $(title).children(".popover-heading").html();
                    }
                });
                $("[data-toggle=popover]").popover().on("mouseenter", function () {
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
            },
            columns: [
                { data: 'item_code', class:'text-left', render: function(data, type, row, meta){
                    var url = "<?php echo base_url('inventory/edit/')?>" + row.urlPrimaryKey;
                    $a = $('<a></a>')
                        .attr('href', url)
                        .addClass('edit')
                        .text(row.item_code.trim())
                    return $a.prop('outerHTML');
                }, className: 'edit'},
                { data: 'item_name', class:'text-left'},
                { data: 'invoice_no', class:'text-left'},
                { data: 'order_no', class:'text-left'},
                { data: 'size' },
                { data: 'color' },
                { data: 'quantity', render: function(data, type, row, meta){
                    return '<a Style="color: blue; font-weight: bold;" class="" tabindex="0" data-toggle="popover" data-trigger="manual" data-popover-content="#history" data-placement="top">'+numberWithCommas(row.quantity)+'</a>';
                    }, class:'text-right'},
                { data: 'unit' },
                { data: 'item_type', class:'text-left' },
                { data: 'warehouse', class:'text-left'},
                { data: 'status', class:'text-left', render: function(data, type, row, meta){
                    if(row.status){
                        let css = '';
                        //Keeping status
                        if(row.status_cd === '017'){
                            css = 'style="color:red;font-weight:bold"';
                        }
                        return '<span '+css+'>'+row.status+'</span>';
                    }
                    return '';
                }},
                { data: 'arrival_status', class:'text-left'},
                { data: 'inspect_status', class:'text-left' },
				{ data:'inspect_ok',className: "text-right", render: function(data){
					return numberWithCommas(data);
				}},
                { data:'inspect_ng',className: "text-right", render: function(data){
					return numberWithCommas(data);
				}},
				{ data:'buy_price',className: "text-right", render: function(data){
					return numberWithCommas(data);
                }},
                { data: 'salesman', class:'text-left' },
                { data: 'arrival_date' },
                { data: 'create_date' },
                { data: 'action', className: 'text-center', render: function(data, type, row, meta) {
                    console.log(row);
                    var keepUrl = "<?php echo base_url('inventory/keep/')?>" + row.urlPrimaryKey;
                    var unkeepUrl = "<?php echo base_url('inventory/unkeep/')?>" + row.urlPrimaryKey;
                    var chgUrl = "<?php echo base_url('inventory/changeItem/')?>" + row.urlPrimaryKey;
                    var delUrl = "<?php echo base_url('inventory/delete/')?>" + row.urlPrimaryKey;
                    var endUrl = "<?php echo base_url('inventory/end/')?>" + row.urlPrimaryKey;
                    let keepunkeepUrl = unkeepUrl;
                    let textKeepunkeep = "Release";
                    //Keeping status
                    if(row.status_cd!=='017'){
                        keepunkeepUrl = keepUrl;
                        textKeepunkeep = "Keep";
                    }
                    let textDisable = ''
                    if(status_finish.indexOf(row.status_cd)>=0){
                        textDisable = 'disabled';
                    }
                    let textDisableKeepBtn = '';
                    let DisableEndBtn = false;
                    if(row.inspect_ok == 0) textDisableKeepBtn = 'disabled';
                    // Truong hop Warehouse co chu Cho xuat cuoi cung thi disabled
                    const warehouse_List = row.warehouse.split("_");
                    if (warehouse_List[warehouse_List.length - 1] == "Cho xuat") {
                        textDisable = 'disabled';
                        textDisableKeepBtn = 'disabled';
                        DisableEndBtn = true;
                    }
                    $keep = '<button style="width:65px;" data-url="'+keepunkeepUrl+'" class="btn btn-warning btn-sm btn-custom no-padding-top no-padding-bottom btnKeep" '+textDisableKeepBtn+' title='+textKeepunkeep+'>'
	                    +  '<span>'+textKeepunkeep+'</span>'
                        +  '</button>'
                    $chg = '<span class="input-group-btn" style="padding-left:2px;width:65px;display:inline-block">'
                        +   '<button '+disable_property+' style="width:100%" data-url="'+chgUrl+'" class="btn btn-info btn-sm btn-custom no-padding-top no-padding-bottom btnChangeSaleman" '+textDisable+' title="<?php echo $this->lang->line("customer_change"); ?>">'
                        +   '<span>Change</span>'
                        +   '</button>'
                        +   '</span>'
                    $end = '<span class="input-group-btn" style="padding-left:2px;display:inline-block">'
                        +   '<button style="width:100%" data-url="'+endUrl+'" class="btn ' + (row.status_cd == "015" || row.status_cd == "012" ? 'btn-default' :'btn-success') + ' btn-sm btn-custom no-padding-top no-padding-bottom btnEnd"'+ (row.status_cd == "017" || row.status_cd == "015" || row.status_cd == "012" || DisableEndBtn ? ' disabled ' :'') + 'title="<?php echo $this->lang->line("end"); ?>">'
                        +   '<span>End</span>'
                        +   '</button>'
                        +   '</span>'
                    $del = '<span class="input-group-btn" style="padding-left: 2px;">'
                        +   '<button data-url="'+delUrl+'" class="btn btn-danger btn-sm btn-custom btnDelete" '+ (row.status_cd == "015" ? 'btn-default' :'btn-success') + (row.status_cd == "017" ? ' disabled' :'') +' title="Delete">'
                        +   '<span class="glyphicon glyphicon-trash"></span>'
                        +   '</button>'
                        +   '</span>'
                    return $('<div class="input-group"></div>')
                        .append($keep)
                        .append($chg)
                        .append($end)
                        .append($del)
                        .prop('outerHTML');
                }},
            ],
            "createdRow": function( row, data, dataIndex){
                let arrival_date = data['arrival_date'];
                if(arrival_date){
                    let date1 = new Date(arrival_date);
                    let date2 = new Date();
                    let timeDiff = Math.abs(date2.getTime() - date1.getTime());
                    let diffMonths = Math.ceil(timeDiff / (1000 * 3600 * 24 * 30));
                    let ok_quantity = data['inspect_ok'];
                    if( diffMonths >= 12 && ok_quantity > 0){
                        $(row).css('background-color', 'orange');
                        $(row).find("td:eq(17)").css({"font-weight": "bold", "color": "red"});
                    }else if( diffMonths >= 6 && ok_quantity > 0){
                        $(row).css('background-color', 'yellow');
                        $(row).find("td:eq(17)").css({"font-weight": "bold", "color": "red"});
                    }
                }
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                url : "<?php echo base_url("inventory/search") ?>",
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
        $(document).on("click", ".close", function(){
            let idPopover = $(this).parents(".popover").attr("id");
            $("a[aria-describedby="+idPopover+"]").popover('hide');
        });
        
        $("#products_list").on('click', '.btnKeep', function(){
            var $row = $(this).parents('tr');
            var rowData = table.row($row).data();
            var url = $(this).data("url");
            var flgUnkeep = url.indexOf("unkeep")>=0;
            var post_url =url.replace("/unkeep/","/checkEditDate/");
            post_url = post_url.replace("/keep/","/checkEditDate/");
            data = {
                "edit_date" : rowData.edit_date,
            };
            $.ajax({
                url : post_url,
                type : "POST",
                dataType : "json",
                data: data,
                success : function(data) {
                    // TODO
                    // return;
                    console.log(data);
                    if(data.success){
                        $("#inventory_keep_modal .btnOk").attr("href",url);
                        var message = "<?php echo $this->lang->line('STR0010_I001'); ?>";
                        if(flgUnkeep) message = message.replace("keep", "release");
                        $("#inventory_keep_modal .modal-title").text(message);
                        $("#inventory_keep_modal").modal("show");
                    }else{
                        snackbarShow(data.message);
                    }
                },
                error : function(data) {
                    // TODO
                    snackbarShow("error");
                }
            });
        });
        $("#products_list").on('click', '.btnChangeSaleman', function(){
            var $row = $(this).parents('tr');
            var rowData = table.row($row).data();
            if(rowData.status_cd == "017"){
                snackbarShow("<?php echo $this->lang->line("STR0010_E001") ?>".format(rowData.salesman));
                return;
            }else if(rowData.status_cd == "005"){
                snackbarShow("<?php echo $this->lang->line("STR0010_E002") ?>");
                return;
            }
            if(rowData.arrival_status_cd==null){
                snackbarShow("<?php echo $this->lang->line("STR0010_E003") ?>");
                return;
            }
            if(rowData.quantity == 0){
                snackbarShow("<?php echo $this->lang->line("STR0010_E004") ?>");
                return;
            }
            var salesman = rowData.encodeSalesman;
            var decodeSalesman = rowData.salesman;
            $("#edit_date").val(rowData.edit_date);
            if($("#salesmanchange option").text().indexOf(decodeSalesman) < 0){
                $('#salesmanchange').prepend(
                    $('<option></option>').val(salesman).html(decodeSalesman));
            }
            $("#salesmanchange").val(salesman);
            $("#oldsalesmanchange").val(decodeSalesman);
            var warehouse = rowData.warehouse;
            var warehouse_cd = rowData.warehouse_cd;
            if($("#warehousechange option").text().indexOf(warehouse) < 0){
                $('#warehousechange').prepend(
                    $('<option></option>').val(warehouse_cd).html(warehouse));
            }
            $("#warehousechange").val(warehouse_cd);
            $("#oldwarehousechange").val(warehouse);
            $("#oldwarehousechange").data("komoku", rowData.warehouse_cd);
            var type = rowData.item_type;
            var type_cd = rowData.item_type_cd;
            if($("#item_type option").text().indexOf(type) < 0){
                $('#item_type').prepend(
                    $('<option></option>').val(type_cd).html(type));
            }
            $("#item_type").val(type_cd);
            $("#olditem_type").val(type);
            $("#olditem_type").data("komoku", type_cd);
            $("#okquantity").val(parseFloat(rowData.inspect_ok));
            $("#oldokquantity").val(parseFloat(rowData.inspect_ok));
            $("#ngquantity").val(parseFloat(rowData.inspect_ng));
            $("#oldngquantity").val(parseFloat(rowData.inspect_ng));
            url = $(this).data("url");
            $("#edit_inventory_form").attr("action", url);
            // $("#inventory_salesman_change_modal .btnOk").attr("href", url);
            $("#inventory_salesman_change_modal .btnOk").attr("disabled", true);            
            $("#inventory_salesman_change_modal").modal("show");
        });
        $("#inventory_salesman_change_modal .btnOk").click(function(){
            // var url = $("#edit_inventory_form").attr('action');
            // console.log(url);
            let oldsalesmanchange = $("#inventory_salesman_change_modal #oldsalesmanchange").val();
            let salesmanchange = $("#inventory_salesman_change_modal #salesmanchange").val();
            let salesmanchangeDecode = $("#inventory_salesman_change_modal #salesmanchange option:selected").text();
            let oldwarehouse = $("#inventory_salesman_change_modal #oldwarehousechange").data("komoku");
            let warehouse = $("#inventory_salesman_change_modal #warehousechange").val();
            let olditem_type = $("#inventory_salesman_change_modal #olditem_type").data("komoku");
            let item_type = $("#inventory_salesman_change_modal #item_type").val();
            let oldokquantity = parseFloat($("#inventory_salesman_change_modal #oldokquantity").val());
            let okquantity = parseFloat($("#inventory_salesman_change_modal #okquantity").val());
            let oldngquantity = parseFloat($("#inventory_salesman_change_modal #oldngquantity").val());
            let ngquantity = parseFloat($("#inventory_salesman_change_modal #ngquantity").val());
            // Validate form
            if(oldsalesmanchange === salesmanchangeDecode
                    && oldwarehouse === warehouse
                    && olditem_type === item_type
                    && oldokquantity === okquantity
                    && oldngquantity === ngquantity){
                snackbarShow("Please select value for change");
                return;
            }
            if((okquantity > oldokquantity)||(ngquantity > oldngquantity)){
                snackbarShow("quantity must less than or equal old quantity");
                return;
            }
            if((oldokquantity != okquantity)||(oldngquantity != ngquantity)){
                if(oldsalesmanchange === salesmanchangeDecode
                    && oldwarehouse === warehouse
                    && olditem_type === item_type){
                        snackbarShow("Please change some value");
                        return;
                    }
            }
            url = $("#edit_inventory_form").attr("action").replace("/changeItem/","/checkEditDate/");
            data = {
                "edit_date" : $("#edit_date").val(),
            };
            $.ajax({
                url : url,
                type : "POST",
                dataType : "json",
                data: data,
                success : function(data) {
                    // TODO
                    // return;
                    console.log(data);
                    if(data.success){
                        $("#edit_inventory_form").submit();
                    }else{
                        snackbarShow(data.message);
                    }
                },
                error : function(data) {
                    // TODO
                    snackbarShow("error");
                }
            });
        });

        $("#inventory_salesman_change_modal select").change(function(){
            $("#inventory_salesman_change_modal .btnOk").attr("disabled", false);
        });
        $("#inventory_salesman_change_modal input").on("focus",function(){
            $("#inventory_salesman_change_modal .btnOk").attr("disabled", false);
        });

        $("#products_list").on('click','.btnDelete', function(){
            var $row = $(this).parents('tr');
            var rowData = table.row($row).data();
            var url = $(this).data('url');
            var label = "item code: " + rowData.item_code;
            $("#inventory_del_modal .modal-body label").html(label);
            $("#inventory_del_modal").modal('show');
            $("#inventory_del_modal .btnOk").attr('href', url)
        } );
        $("#btnExportExcel").click(function(){
            if($("#created_from").val()==""||$("#created_to").val()==""){
                if(!$("#created_from").val())$("#created_from").focus();
                if(!$("#created_to").val())$("#created_to").focus();
                snackbarShow("<?php echo $this->lang->line('STR0010_E006');?>");
                return;
            }
            $("#search_form").attr("action", "<?php echo base_url("inventory/export_excel"); ?>").submit();
        });
        $("#salesman").autocomplete({
            lookup: dataFilterSalesman,
            minChars: 0,
            onSelect: function (salesman) {
                $(this).change();
                // set data to Delivery To Address
                // $('#delivery_address').val(salesman.value);
            }
        });

        $("#btnSearch").click(function(){
            // table.ajax.reload();
            // e.preventDefault();
            $("#search_form").attr("action", "<?php echo base_url("inventory"); ?>").submit();
        });
        $("#products_list").on('click','.btnEnd', function(){
            let $row = $(this).parents('tr');
            let rowData = table.row($row).data();
            let url = $(this).data('url');
            post_url = url.replace("/end/","/checkEditDate/");
            data = {
                "edit_date" : rowData.edit_date,
            };
            $.ajax({
                url : post_url,
                type : "POST",
                dataType : "json",
                data: data,
                success : function(data) {
                    // TODO
                    // return;
                    console.log(data);
                    if(data.success){
                        $("#inventory_end_modal .btnOk").attr("href",url);
                        $("#inventory_end_modal").modal("show");
                    }else{
                        snackbarShow(data.message);
                    }
                },
                error : function(data) {
                    // TODO
                    snackbarShow("error");
                }
            });
            // var label = "item code: " + rowData.item_code;
            // $("#inventory_del_modal .modal-body label").html(label);
            // $("#inventory_del_modal").modal('show');
            // $("#inventory_del_modal .btnOk").attr('href', url)
        } );
    }
</script>
