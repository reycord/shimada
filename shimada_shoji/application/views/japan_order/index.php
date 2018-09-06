<style>
    td div {
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
                <h2><?php echo $title; ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <form action="<?php echo base_url() ?>japan_order/excel" method="GET">
                            <button type="submit" id="export_excel_modal" class="btn btn-success" style="float: right;"> <i class="fa fa-file-excel-o" ></i> Excel </button>
                        </form>
                    </li>
                    <li>
                        <button type="button" id="show_delivery_modal" class="btn btn-primary" style="float: right;"><?php echo $this->lang->line('delivery_order');?>
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table id="orders_delivery_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>INV</th>
                                <th>Input Date</th>
                                <th><?php echo $this->lang->line('wish_delivery_date');?></th>
                                <th>DVT/DVH</th>
                                <th><?php echo $this->lang->line('times');?></th>
                                <th><?php echo $this->lang->line('shipping_method');?></th>
                                <th><?php echo $this->lang->line('sales_man');?></th>
                                <th><?php echo $this->lang->line('sales_man');?>ID</th>
                                <th><?php echo $this->lang->line('factory_name');?></th>
                                <th><?php echo $this->lang->line('factory_address');?></th>
                                <th><?php echo $this->lang->line('contract_no');?></th>
                                <th><?php echo $this->lang->line('style');?>No</th>
                                <th>O/No</th>
                                <th style="background-color: #66ff99;"><?php echo $this->lang->line('factory_require_date');?></th>
                                <th style="background-color: #66ff99;"><?php echo $this->lang->line('factory_plan_date');?></th>
                                <th style="background-color: #66ff99;"><?php echo $this->lang->line('delivery_date1');?></th>
                                <th>PV No</th>
                                <th>PV in VSIP</th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('request_packing_date');?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('measurem_date');?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('passage_date'); ?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('out_vsip_date'); ?></th>
                                <!-- <th>VSIP→KNQ</th>
                                <th>KNQ→FACTORY</th> -->
                                <th><?php echo $this->lang->line('staff');?></th>
                                <th><?php echo $this->lang->line('status');?></th>
                                <th><?php echo $this->lang->line('note');?></th>
                                <th>Buyer</th>
                                <th class="nosort"><?php echo $this->lang->line('action');?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Delete modal -->
<div id="schedule_delete_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('JOS0010_I004');?></h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no');?></button>
                <button type="button" class="btn btn-success antosubmit2"><?php echo $this->lang->line('yes');?></button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<!-- Insert Delivery modal -->
<div id="delivery_input_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 85%">
        <div class="modal-content">

            <div class="modal-header">
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-info" data-dismiss="modal" id="btn_goto_list">
                            <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-primary" id="btn_save_delivery" type="button">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                        </button>
                    </li>
                </ul>
                <h2><?php echo $this->lang->line('delivery_order_input');?></h2>
                <h5 field="err_exist" class="text-center" style="color:red"></h5>
            </div>
            <div class="modal-footer">
				<form class="form-horizontal form-label-left" id="frm_delivery_input" method="post" action="">
                    <input type="hidden" class="form-control" id="currency" name="currency">    
                    <div class="col-md-7">
						<div class="form-group">
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left"
                                for="dvt_no"
                            >DVT/DVH<span class="required">*</span>
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
								<input 
                                    type="text" 
                                    class="form-control validate[required] text-uppercase" 
                                    id="dvt_no" 
                                    name="dvt_no"
                                    maxlength="10"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
							<label 
                                class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="order_date"
								maxlength="10"
                            >Input Date<span class="required">*</span>
                            </label>
						</div>
						<div class="form-group">
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" 
                                for="times"
                            ><?php echo $this->lang->line('times')?><span class="required">*</span>
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
								<input 
                                    type="text"
                                    class="form-control validate[required]" 
                                    id="times" 
                                    name="times" 
                                    value = "1" 
                                    readonly
                                >
							</div>
							<label 
								class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                            ><?php echo $this->lang->line('pv_information')?><span class="required">*</span>
                            </label>
						</div>
						<div class="form-group">
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" 
                                for="delivery_require_date"
                            ><?php echo $this->lang->line('wish_delivery_date');?>
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
								<input 
                                    type="text" 
                                    class="form-control date" 
                                    id="delivery_require_date" 
                                    name="delivery_require_date"
                                    maxlength="10"
                                    value="<?php echo $currentDate;?>"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
						</div>
						<div class="form-group">
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left"                                 style="padding-right: 8px;" 
                                for="delivery_mothod"
                            ><?php echo $this->lang->line('shipping_method')?>
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
								<select class="form-control" name="delivery_method" id="delivery_method">
									<option value=""></option>
                                    <?php foreach ($shipping_method_list as $shipping): ?>
                                    <option
                                        value="<?php echo $shipping['kubun']; ?>"
                                    ><?php echo $shipping['komoku_name_2']; ?></option>
                                    <?php endforeach?>
								</select>
                            </div>
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" 
                                for="classify"
                            ><?php echo $this->lang->line('classify')?>
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
								<input 
                                    type="text" 
                                    class="form-control" 
                                    id="classify" 
                                    name="classify"
                                    value="Japan Order"
                                    readonly
                                >
							</div>
						</div>
						<div class="form-group">
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" 
                                for="staff"
                            ><?php echo $this->lang->line('sales_man')?>
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
								<input 
                                    type="text" 
                                    class="form-control" 
                                    id="staff" 
                                    name="staff"
                                    maxlength="20"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left"  
                                for="staff_id"
                            ><?php echo $this->lang->line('sales_man')?>ID
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
								<input 
                                    type="text" 
                                    class="form-control text-uppercase" 
                                    id="staff_id" 
                                    name="staff_id"
                                    maxlength="10"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
						</div>
						<div class="form-group">
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" 
                                for="assistance"
                            ><?php echo $this->lang->line('assistance')?>
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
								<input 
                                    type="text" 
                                    class="form-control" 
                                    id="assistance" 
                                    name="assistance"
                                    maxlength="20"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" 
                                for="department"
                            ><?php echo $this->lang->line('department')?>
                            </label>
							<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right  has-clear has-feedback">
								<input 
                                    type="text" 
                                    class="form-control" 
                                    id="department" 
                                    name="department"
                                    maxlength="20"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
						</div>
						<div class="form-group">
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" 
                                for="factory"
                            ><?php echo $this->lang->line('factory_name')?>
                            </label>
							<div class="col-md-9 col-sm-9 col-xs-9 no-padding-left no-padding-right has-clear has-feedback">
								<input 
                                    type="text" 
                                    class="form-control" 
                                    id="factory" 
                                    name="factory"
                                    maxlength="50"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
						</div>
						<div class="form-group">
							<label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left" 
                                for="address"
                            ><?php echo $this->lang->line('factory_address')?>
                            </label>
							<div class="col-md-9 col-sm-9 col-xs-9 no-padding-left no-padding-right">
                                <textarea 
                                    spellcheck="false"
                                    type="text" 
                                    rows="3" 
                                    class="form-control" 
                                    id="address"    
                                    name="address"
                                ></textarea>
							</div>
						</div>
					</div>
					<div class="col-md-5 no-padding-left">
						<div class="form-group">
							<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right has-clear has-feedback">
								<input 
                                    type="text" 
                                    id="order_date" 
                                    name="order_date" 
                                    class="form-control date validate[required]"
                                    maxlength="10"
                                    value="<?php echo $currentDate;?>"
                                />
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
							</div>
						</div>
						<div class="form-group">
                            <table 
                                id="tbl_order_list" 
                                style="border: 1px solid #ddd;"
                                class="table table-striped table-bordered cssTable display nowrap" 
                                cellspacing="0"
                                width="100%"
                            >
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('order_received_no')?></th>
                                        <th><?php echo $this->lang->line('partition_no')?></th>
                                        <th><?php echo $this->lang->line('order_received_date');?></th>
                                        <th><?php echo $this->lang->line('action');?></th>
                                    </tr>
                                </thead>
                            </table>
                            <div field="err-data-table" style="color:white; text-align:left; background-color:red;"></div>
						</div>
						<div class="form-group">
							<div class="col-md-12 col-sm-6 col-xs-6 no-padding-left">
								<button 
                                    type="button" 
                                    class="btn btn-primary" 
                                    id="show_model_order"
                                    style="float: right;"
                                ><?php echo $this->lang->line('btn_add_pv_no')?>
                                </button>
							</div>
						</div>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<!-- Add order modal -->
<div id="pv_no_input_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
				<ul class="nav navbar-right panel_toolbox">
					<li>
                        <button class="btn btn-info" data-dismiss="modal" id="btn_goto_delivery">
                            <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                        </button>
					</li>
					<li>
                        <button class="btn btn-success" id="btn_add_order">
                            <i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?>
                        </button>
					</li>
				</ul>
				<h2><?php echo $this->lang->line('delivery_order_add');?></h2>
            </div>
            <div class="modal-footer">
                <form action="frm-pv-no">
                    <table 
                        id="tbl_add_list"  
                        class="table table-striped table-bordered cssTable display nowrap" 
                        style="width:100%;" 
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('order_received_no'); ?></th>
                                <th><?php echo $this->lang->line('partition_no'); ?></th>
                                <th><?php echo $this->lang->line('order_received_date');?></th>
                                <th><?php echo $this->lang->line('contract_no');?></th>
                                <th><?php echo $this->lang->line('status');?></th>
                                <th><?php echo $this->lang->line('action');?></th>
                            </tr>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<script>
    var orderReceivedList = null;
    window.onload = function() 
    {
        <?php if(isset($order_receive_list) && $order_receive_list != null && sizeof($order_receive_list) > 0): ?>
            orderReceivedList = <?php echo json_encode($order_receive_list); ?>;
        <?php endif ?>
        // Table order
        var orderDt = $('#tbl_order_list').DataTable({
            "data": [],
            "paging": false,
            "filter": false,
            "ordering": true,
            "scrollX": true,
            "scrollY": "250px",
            "info": false,
            "columns": [
                {"data": "order_receive_no","className": "text-left"},
                {"data": "partition_no", "render": function( data, type, row, meta ) {
                    if(row.check_count > 1 || row.partition_no > 1) {
                        return row.partition_no;
                    }
                    return '';
                }},
                {"data": "order_receive_date"},
                {"data": "action", "render": function( data, type, row, meta ){
                    return '<a data-event="delete-item" href="#" class="btn btn-default btn-sm btn-custom" title="Delete">'
                        + '<span class="glyphicon glyphicon-trash"></span>'
                        + '</a>';
                }},
            ]
        });

        $('#btn_save_delivery').click( function(){
            // Validation form Delivery order input
            var validate = $('#frm_delivery_input').validationEngine('validate');
            var dataTable = $('#tbl_order_list').DataTable().data().toArray();
            $('#frm_delivery_input [field=err-data-table]').html('');
            if(dataTable.length == 0) {
                validate = false;
                $('#frm_delivery_input [field=err-data-table]').html('<?php echo 'Please Add PV No!';?>');
            }
            if(!validate) {
                return;
            }

            var array = $('#frm_delivery_input').serializeArray();
            bootbox.confirm({
                message: '<h4><?php echo $this->lang->line('JOS0010_I006')?></h4>',
                buttons: {
                    confirm: {
                        label: '<?php echo $this->lang->line('yes');?>',
                        className: 'btn-success',
                    },
                    cancel: {
                        label: '<?php echo $this->lang->line('no');?>',
                        className: 'btn-primary'
                    }
                },
                callback: function(result) {
                    if(result) {
                        $.ajax({
                            url: '<?php echo base_url('japan_order/check_delivery_order_exist')?>',
                            type: 'post',
                            data: array,
                            dataType: 'json',
                        }).done( function (response){
                            if(!response.success) {
                                $('[field=err_exist]').html(response.message);
                            } else {
                                $('[field=err_exist]').html('');
                                var pv_infor = orderDt.data().toArray();
                                requestData = {};
                                $.each(array, function(i, item){
                                    requestData[item.name] = item.value;
                                });
                                requestData.pv_infor = pv_infor;
                                requestData.current_date = moment().format('YYYY/MM/DD hh:mm:ss');
                                $.ajax({
                                    url: '<?php echo base_url('japan_order/save_delivery')?>',
                                    type: 'post',
                                    data: requestData,
                                    dataType: 'json',
                                }).done( function( response ){
                                    snackbarShow(response.message);
                                    if(response.success) {
                                        var currentPage = itemDt.page();
                                    
                                        // insert new row 
                                        itemDt.row.add(response.data);
                                        var row_select = itemDt.row(':eq(0)', { page: 'current' }).select();
                                        itemDt.draw(false);

                                        // set time out for select
                                        setTimeout(function(){itemDt.row(row_select).deselect();}, 10000);

                                        // move added row to desired index
                                        var index = currentPage * itemDt.page.len(),
                                            rowCount = itemDt.data().length-1,
                                            insertedRow = itemDt.row(rowCount).data(),
                                            tempRow;

                                        for (var i=rowCount;i>index;i--) {
                                            tempRow = itemDt.row(i-1).data();
                                            itemDt.row(i).data(tempRow);
                                            itemDt.row(i-1).data(insertedRow);
                                        }    

                                        // refresh the current page
                                        itemDt.page(currentPage).draw(false);
                                        // close modal
                                        $("#delivery_input_modal").modal('hide');
                                        orderList.ajax.reload();
                                    } else {
                                        itemDt.draw();
                                    }
                                    reloadOrderReceiveList();
                                });
                            }
                        });
                    }
                }
            });
        });

        // Table order list
        var orderList = $('#tbl_add_list').DataTable({
            "ajax": {"url": "<?php echo base_url('japan_order/getOrderReceiveList'); ?>"},
            "paging": true,
            "filter": true,
            "ordering": true,
            "scrollX" : false,
            "info": true,
            "drawCallback": function() {
                $("#tbl_add_list input.flat").iCheck({
                    checkboxClass: "icheckbox_flat-green",
                    radioClass: "iradio_flat-green",   
                });
            },
            "columns": [
                {"data": "order_receive_no", "className": "text-left"},
                {"data": "partition_no", "render": function( data, type, row, meta ) {
                    if(row.check_count > 1 || row.partition_no > 1) {
                        return row.partition_no;
                    }
                    return '';
                }},
                {"data": "order_receive_date"},
                {"data": "contract_no", "className": "text-left"},
                {"data": "komoku_name_2"},
                {"data": "action", "render": function( data, type, row, meta ){
                    if(row.check){
                        return '<input class="flat" id="check_add" type="checkbox" value="1" checked="checked">';
                    }
                    return '<input class="flat" id="check_add" type="checkbox" value="1">';
                }},
            ]
        });

        $('#show_delivery_modal').on('click', function(){
            if(orderList){
                orderList.ajax.reload();
            }
            $('[field=err_exist]').html('');
            if(orderReceivedList == null || orderReceivedList.length <= 0){
                snackbarShow('<?php echo $this->lang->line('JOS0010_I005');?>');
                return;
            }
            $('#delivery_input_modal').modal('show');
            document.getElementById("frm_delivery_input").reset();
            $('#pv_no_input_modal input[type=checkbox]').prop('checked',false);
        });

        $('#delivery_input_modal').on('shown.bs.modal',function(){
            $('#frm_delivery_input').validationEngine('hideAll');
            $('#frm_delivery_input [field=err-data-table]').html('');   
            orderDt.clear();
            orderDt.draw();
        });
        
        $('#delivery_input_modal').on('click', '#show_model_order', function(){ 
            var data_pv = orderDt.data().toArray();
            var data = orderList.data().toArray();
            $.each(data, function(index, value){
                if(value.check) {
                    data[index].check = false;
                }
            });
            $.each(data_pv, function(i,val){
                $.each(data, function(index,value){
                    if(val.order_receive_no == value.order_receive_no && val.partition_no == value.partition_no && val.order_receive_date == value.order_receive_date) {
                        data[index].check = true;
                    }
                });
            });
            orderList.clear();
            orderList.rows.add(data);
            orderList.draw();
            $('#pv_no_input_modal #btn_add_order').attr('disabled',true);
            $('#pv_no_input_modal').modal('show');
            $("#tbl_add_list input[type=checkbox]").each(function() {
                if($(this).is(':checked')) {
                    $(this).attr('disabled', true);
                }
            });
            orderList.draw();
        });
        
        $('#tbl_add_list').on('ifChanged', '#check_add', function() {
            var $row = $(this).parents('tr'),
                rowData = orderList.row($row).data();
            if(rowData.check) {
                rowData.check = false;
            } else {
                rowData.check = true;
            }
            orderList.row($row).data(rowData);
            orderList.draw( false );
            var somethingChecked = false;
            $("#tbl_add_list input[type=checkbox]").each(function() {
                if($(this).is(':checked') && !$(this).is(':disabled')) {
                    somethingChecked = true;
                }
            });
            if(somethingChecked) {
                $('#pv_no_input_modal #btn_add_order').attr('disabled', false);
            } else {
                $('#pv_no_input_modal #btn_add_order').attr('disabled', true);
            }
        });

        $('#btn_add_order').click(function(){
            var addDt = orderList.data().toArray();
            if(addDt.length > 0){
                $('#frm_delivery_input [field=err-data-table]').html('');
            }
            var orderdata = [];
            for (var index = 0; index < addDt.length; index++) {
                element = addDt[index];
                if(element.check == true) {
                    orderdata.push(element);
                }
            }
            orderDt.clear();
            orderDt.rows.add(orderdata);
            orderDt.rows().invalidate('data')
                .draw();
            $('#pv_no_input_modal').modal('hide');
            $('#frm_delivery_input #staff').val(orderdata[0].staff);
            $('#frm_delivery_input #currency').val(orderdata[0].currency);
            $('#frm_delivery_input #department').val(orderdata[0].odr_department);
            $('#frm_delivery_input #assistance').val(orderdata[0].assistance);
            $('#frm_delivery_input #factory').val(orderdata[0].delivery_to);
            $('#frm_delivery_input #address').val(orderdata[0].delivery_address);
        });

        $('#tbl_order_list').on('click', '[data-event="delete-item"]', function (){
            var $row = $(this).parents('tr'),
                rowData = orderDt.row($row).data();
            bootbox.confirm({
                title: "<?php echo $this->lang->line('JOS0010_I001'); ?>",
                message: '<h4 style="color:red;">' + rowData.order_receive_no + '</h4>',
                buttons: {
                    confirm: {
                        label: '<?php echo $this->lang->line('yes');?>',
                        className: 'btn-success',
                    },
                    cancel: {
                        label: '<?php echo $this->lang->line('no');?>',
                        className: 'btn-primary'
                    }
                },
                callback: function(result) {
                    if(result) {
                        orderDt
                            .row($row)
                            .remove();
                        orderDt.rows().invalidate('data')
                        .draw(false);
                        var addDt = orderDt.data().toArray();
                        if(addDt.length > 0){
                            $('#frm_delivery_input #staff').val(addDt[0].staff);
                            $('#frm_delivery_input #department').val(addDt[0].odr_department);
                            $('#frm_delivery_input #currency').val(addDt[0].currency);
                            $('#frm_delivery_input #assistance').val(addDt[0].assistance);
                            $('#frm_delivery_input #factory').val(addDt[0].delivery_to);
                            $('#frm_delivery_input #address').val(addDt[0].delivery_address);
                        }else{
                            $('#frm_delivery_input #staff').val('');
                            $('#frm_delivery_input #department').val('');
                            $('#frm_delivery_input #assistance').val('');
                            $('#frm_delivery_input #currency').val('');
                            $('#frm_delivery_input #factory').val('');
                            $('#frm_delivery_input #address').val('');
                        }
                        reloadOrderReceiveList();
                    }
                }
            });
        });
        // create datatable
        var itemDt = $('#orders_delivery_list').DataTable({
            "data": <?php echo json_encode($dvt); ?>,
            "paging": true,
            "filter": true,
            "oSearch": {"sSearch": '<?php echo ($search_dvt ? $search_dvt : ''); ?>' },
            "ordering": true,
            "scrollX" :true,
            "drawCallback": function() {
                // datepicker
                $(".date").datepicker({
                    todayHighlight: true,
                    format: "d M, yyyy",
                    autoclose: true
                });
                // iCheck
                $("#orders_delivery_list input.flat").iCheck({
                    checkboxClass: "icheckbox_flat-green",
                    radioClass: "iradio_flat-green",   
                });
            },
            // "ajax": {"url": "<?php echo base_url('japan_order/search'); ?>"},
            "columns": [
                { "data": "inv_flg", render: function( data, type, row, meta ){
                    html = '';
                    if (row.edit_mode){
                        if(row.inv_flg == '1')
                            html += '<input name="inv_flg" type="checkbox" class="flat" checked value="'+row.inv_flg+'">';
                        else
                        html += '<input name="inv_flg" type="checkbox" class="flat" value="'+row.inv_flg+'">';
                    } else {
                        if(row.inv_flg == '1')
                            html += '<input name="inv_flg" type="checkbox" class="flat" checked disabled value="'+row.inv_flg+'">';
                        else
                            html += '<input name="inv_flg" type="checkbox" class="flat" disabled value="'+row.inv_flg+'">';
                    }
                    return html;
                }},
                { "data": "order_date", render: function( data, type, row, meta ){
                    return row.order_date;
                }},
                { "data": "delivery_require_date", render: function( data, type, row, meta ){
                    return row.delivery_require_date;
                }},
                { "data": "dvt_no","className": "text-left", render: function( data, type, row, meta ){
                    return "<a class='edit' href='<?php echo base_url()?>japan_order/detail/"+row.order_date+"/"+row.times+"/"+row.dvt_no+"'>"+ row.dvt_no +"</a>";
                }},
                { "data": "times", render: function( data, type, row, meta ){
                    if(row.number_times > 1 || row.times > 1){
                        return row.times;
                    }
                    return '';
                }},
                { "data": "komoku_name_2", render: function( data, type, row, meta ){
                    return row.komoku_name_2;
                }},
                { "data": "staff","className": "text-left", render: function( data, type, row, meta ){
                    return row.staff;
                }},
                { "data": "staff_id","className": "text-left", render: function( data, type, row, meta ){
                    return row.staff_id;
                }},
                { "data": "factory","className": "text-left", render: function( data, type, row, meta ){
                    var html = '';
                    if(row.factory){
                        html += '<div title="'+row.factory+'">'+row.factory+'</div>';
                    }
                    return html;
                }},
                { "data": "address","className": "text-left", render: function( data, type, row, meta ){
                    var html = '';
                    if(row.address){
                        html += '<div title="'+row.address+'">'+row.address+'</div>';
                    }
                    return html;
                }},
                { "data": "contract_no","className": "text-left", render: function( data, type, row, meta ){
                    var html = '';
                    if(row.contract_no){
                        html += '<div title="'+row.contract_no+'">'+row.contract_no+'</div>';
                    }
                    return html;
                }},
                { "data": "stype_no","className": "text-left", render: function( data, type, row, meta ){
                    var html = '';
                    if(row.stype_no){
                        html += '<div title="'+row.stype_no+'">'+row.stype_no+'</div>';
                    }
                    return html;
                }},
                { "data": "o_no","className": "text-left", render: function( data, type, row, meta ){
                    var html = '';
                    if(row.o_no){
                        html += '<div title="'+row.o_no+'">'+row.o_no+'</div>';
                    }
                    return html;
                }},
                { "data": "factory_require_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value', formatDate(row.factory_require_date))
                            .attr('class', 'form-control  hasDatepicker date datatable-input')
                            .attr('name', 'factory_require_date')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.factory_require_date;
                    } 
                }},
                { "data": "factory_plan_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value',  formatDate(row.factory_plan_date))
                            .attr('class', 'form-control  hasDatepicker date datatable-input')
                            .attr('name', 'factory_plan_date')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.factory_plan_date;
                    }
                }},
                { "data": "delivery_plan_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value',  formatDate(row.delivery_plan_date))
                            .attr('class', 'form-control  hasDatepicker date datatable-input')
                            .attr('name', 'delivery_plan_date')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.delivery_plan_date;
                    }
                }},
                { "data": "pv_infor","className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input text-uppercase')
                            .attr('name', 'pv_infor')
                            .attr('value', row.pv_infor)
							.css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        var html = '<div title="'+row.pv_infor+'">'+row.pv_infor+'</div>';
                        return html;
                    }
                }},
                { "data": "pv_in_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value',  formatDate(row.pv_in_date))
                            .attr('class', 'form-control  hasDatepicker date datatable-input')
                            .attr('name', 'pv_in_date')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.pv_in_date;
                    }
                }},
                { "data": "packing_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value',  formatDate(row.packing_date))
                            .attr('class', 'form-control  hasDatepicker date datatable-input')
                            .attr('name', 'packing_date')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.packing_date;
                    }
                }},
                { "data": "measurement_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value',  formatDate(row.measurement_date))
                            .attr('class', 'form-control  hasDatepicker date datatable-input')
                            .attr('name', 'measurement_date')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.measurement_date;
                    }
                }},
                { "data": "passage_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value',  formatDate(row.passage_date))
                            .attr('class', 'form-control  hasDatepicker date datatable-input')
                            .attr('name', 'passage_date')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.passage_date;
                    }
                }},
                { "data": "factory_delivery_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value',  formatDate(row.factory_delivery_date))
                            .attr('class', 'form-control  hasDatepicker date datatable-input')
                            .attr('name', 'factory_delivery_date')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.factory_delivery_date;
                    }
                }},
                { "data": "salesmanname","className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                         var array = <?php echo json_encode($salesman)?>;
                        var $temp = '';
                        for (let index = 0; index < array.length; index++) {
                            const element = array[index];
                            if(row.employee_id.trim() == element.employee_id.trim()) {
                                $temp += '<option selected value='+element.employee_id+'>'+element.sales_man+'</option>'
                            } else {
                                $temp += '<option value='+element.employee_id+'>'+element.sales_man+'</option>'
                            }
                        }
                        var html = '<select name="sales_man" id="item_listbox" class="form-control" style="min-width:100%; height:30px;">'
                                + '<option value=""></option>'
                                + $temp
                                + '</select>';
                        return html;
                    } else {
                        return row.salesmanname;
                    }
                }},
                { "data": "status_name", render: function( data, type, row, meta ){
                    return row.status_name;
                }},
                { "data": "note","className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'note')
                            .attr('value', row.note)
							.css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
						var html = '';
						if(row.note){
							html += '<div class="ellipsis" title="'+row.note+'">'+row.note+'</div>';
						}
						return html;
                    }
                }},
                { "data": "buyer","className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'buyer')
							.attr('maxlength', '50')
                            .attr('value', row.buyer)
							.css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.buyer;
                    }
                }},
                { "data": "action", render: function( data, type, row, meta ){
                    var disableStatus = <?php echo json_encode(explode("," ,STATUS_FINISH)); ?>;
                    var html = '';
                    if (row.edit_mode) {
                        html += '<a data-event="save-item" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
                            + '<span class="fa fa-check"></span>'
                            + '</a>';
                        html += '<a data-event="close-item" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
                            + '<span class="fa fa-close"></span>'
                            + '</a>';
                    } else {
                        // html += '<a href="<?php echo base_url(); ?>japan_order/exportpdf/'+row.dvt_no.trim()+'/'+row.times+'/'+row.order_date+'" class="btn btn-default btn-xs" title="Download">'
                        //     +  '<span class="glyphicon glyphicon-download-alt"></span>'
                        //     + '</a>'
                        if (disableStatus.length > 0 && (disableStatus.indexOf(row.status) == -1)){
                            html += '<a data-event="edit-item" class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>">'
                                + '<span class="fa fa-edit"></span>'
                                + '</a>';
                            html += '<a data-event="delete-item" style="margin-left: 3px; " class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>">'
                                + '<span class="fa fa-trash"></span>'
                                + '</a>';
                        }
                    }
                    return html;
                }},
            ],
            "columnDefs": [ {
                "targets": 'nosort',
                "orderable": false
            } ]
        });

        // Items list box on change
        $('#orders_delivery_list').on('change', '#item_listbox', function() {
            var $row = $(this).parents('tr'),
                rowData = itemDt.row($row).data();
            rowData.employee_id = $(this).val();
            rowData.salesmanname = $row.find('select[name="sales_man"]').children(":selected").text();
            rowData.factory_require_date = $row.find('input[name="factory_require_date"]').val();
            rowData.factory_plan_date = $row.find('input[name="factory_plan_date"]').val();
            rowData.delivery_plan_date = $row.find('input[name="delivery_plan_date"]').val();
            rowData.measurement_date = $row.find('input[name="measurement_date"]').val();
            rowData.pv_in_date = $row.find('input[name="pv_in_date"]').val();
            rowData.packing_date = $row.find('input[name="packing_date"]').val();
            rowData.passage_date = $row.find('input[name="passage_date"]').val();
            rowData.factory_delivery_date = $row.find('input[name="factory_delivery_date"]').val();
            rowData.knq_delivery_date = $row.find('input[name="knq_delivery_date"]').val();
            rowData.knq_fac_deli_date = $row.find('input[name="knq_fac_deli_date"]').val();
            rowData.note = $row.find('input[name="note"]').val();
            rowData.buyer = $row.find('input[name="buyer"]').val();
            rowData.pv_no = $row.find('input[name="pv_no"]').val();
            itemDt.row($row).data(rowData);
            itemDt.draw( false );
        });

        // Click edit in datatable
        $('#orders_delivery_list').on('click', '[data-event="edit-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            rowData.edit_mode = true;
            itemDt.row($row).data(rowData).invalidate();
            itemDt.draw( false ); 
        });

        // Click close in datatable
        $('#orders_delivery_list').on('click', '[data-event="close-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            rowData.edit_mode = false;
            itemDt.row($row).data(rowData).invalidate();
            itemDt.draw( false ); 
        });

        // Click delete in datatable
        $('#orders_delivery_list').on('click', '[data-event="delete-item"]', function(){
            var $row = $(this).parents('tr'),
            rowData = itemDt.row($row).data(),
            requestData = {};
            requestData.order_date = rowData.order_date;
            requestData.times = rowData.times;
            requestData.dvt_no = rowData.dvt_no;
            requestData.edit_date = rowData.edit_date;
            requestData.pv_infor = rowData.pv_infor;
            bootbox.confirm({
                title: "<?php echo ($this->lang->line('JOS0010_I002'));?>",
                message: '<h4 style="color:red;">' + rowData.dvt_no + '</h4>', 
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
                            url: "<?php echo base_url('japan_order/delete')?>",
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
                        reloadOrderReceiveList();
                    }
                }
            });
        });
        
        // Click save in datatable
        $('#orders_delivery_list').on('click', '[data-event="save-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            bootbox.confirm({
                title: "<?php echo ($this->lang->line('JOS0010_I003'));?>",
                message: '<h4 style="color:red;">' + rowData.dvt_no + '</h4>', 
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
                        var requestData = {};
                        requestData.order_date = rowData.order_date;
                        requestData.inv_flg = $row.find("input[name='inv_flg']").prop("checked") == true ? '1' : '0';
                        requestData.times = rowData.times;
                        requestData.dvt_no = rowData.dvt_no;
                        requestData.factory_require_date = $row.find("input[name='factory_require_date']").val();
                        requestData.factory_plan_date = $row.find("input[name='factory_plan_date']").val();
                        requestData.delivery_plan_date = $row.find("input[name='delivery_plan_date']").val();
                        requestData.measurement_date = $row.find("input[name='measurement_date']").val();
                        requestData.pv_in_date = $row.find("input[name='pv_in_date']").val();
                        requestData.packing_date = $row.find("input[name='packing_date']").val();
                        requestData.passage_date = $row.find("input[name='passage_date']").val();
                        requestData.factory_delivery_date = $row.find("input[name='factory_delivery_date']").val();
                        requestData.knq_delivery_date = $row.find("input[name='knq_delivery_date']").val();
                        requestData.knq_fac_deli_date = $row.find("input[name='knq_fac_deli_date']").val();
                        requestData.pv_infor = $row.find("input[name='pv_infor']").val();
                        requestData.salesman = rowData.employee_id;
                        requestData.note = $row.find("input[name='note']").val();
                        requestData.buyer = $row.find("input[name='buyer']").val();
                        requestData.edit_date = rowData.edit_date;
                        // return;
                        $.ajax({
                            url: "<?php echo base_url('japan_order/save')?>",
                            data: requestData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function(response) {
                            // return;
                            snackbarShow(response.message);
                            if(response.success == true) {
                                rowData = response.data;
                                if(rowData.edit_mode) {
                                    rowData.edit_mode = false;
                                }
                                itemDt.row($row).data(rowData).invalidate();
                                itemDt.draw( false );
                            } else {
                                if(response.data != ''){
                                    var error = response.data;
                                    $.each( error, function( i, val ) {
                                        $row.find("[name="+i+"]")
                                        .after( '<div field='+i+' style="color:red;">'+ val +'</div>' );
                                    });
                                    itemDt.draw( false );
                                }
                            }
                        });
                    }
                }
            });
        });
        function reloadOrderReceiveList(){
            $.ajax({
                url: "<?php echo base_url('japan_order/getOrderReceiveList')?>",
                type: 'get',
                dataType: 'json'
            }).done(function(response) {
                orderReceivedList = response.data;
            });
        }
    };
</script>
