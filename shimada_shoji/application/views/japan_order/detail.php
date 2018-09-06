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
                <h2><?php echo $title ?></h2>
                <ul class="nav navbar-right panel_toolbox" style="<?php echo ( $editFlg == 1 ? 'display:block' : 'display:none');?>">
					<li>
						<button class="btn btn-info" onclick="window.history.back()">
							<i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
						</button>
					</li>
                    <li>
						<button type="button" class="btn btn-primary" onclick="openInsertModal()" style="float: right;"><?php echo $this->lang->line('packing_order');?></button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive dragscroll">
                    <table 
											style="cursor:move;"
											id="schedule_detail_list" 
											class="table table-striped table-bordered cssTable display nowrap" 
											width="100%" 
											cellspacing="0">
											<thead>
												<tr>
													<th> &nbsp;&nbsp;&nbsp; KNO &nbsp;&nbsp;</th>
													<th>DNO</th>
													<th>Input Date</th>
													<th><?php echo $this->lang->line('times');?></th>
													<th><?php echo $this->lang->line('sales_man');?></th>
													<th><?php echo $this->lang->line('sales_man');?>ID</th>
													<th><?php echo $this->lang->line('contract_no');?></th>
													<th><?php echo $this->lang->line('style');?>No</th>
													<th>O/No</th>
													<th><?php echo $this->lang->line('assistant');?></th>
													<th style="background-color: #ffff00;"><?php echo $this->lang->line('out_vsip_date');?></th>
													<th><?php echo $this->lang->line('consigned_to');?></th>
													<th><?php echo $this->lang->line('shipping_method');?></th>
													<th><?php echo $this->lang->line('action');?></th>
												</tr>
												</thead>
										</table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="packing_order_input_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<ul class="nav navbar-right panel_toolbox">
					<li><button class="btn btn-info" data-dismiss="modal"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button>
					</li>
					<li id="insertKVT" style="display:none"><button class="btn btn-primary" onclick="insertKVT()"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
					</li>
					<li id="updateDetailKVT" style="display:none"><button class="btn btn-primary" onclick="updateDetailKVT()"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
					</li>
				</ul>
				<h2><?php echo $this->lang->line('packing_order_input');?></h2>
			</div>
			<div class="modal-footer">
				<form class="form-horizontal" id = "kvt_info">
					<div class="row">
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-5" for="packing_no"><?php echo $this->lang->line('delivery_date');?><span class="required">*</span></label>
							<div class="col-xs-7">
								<input type="text" class="form-control validate[required] date" id="delivery_date" name="delivery_date">
							</div>
						</div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-5" for="packing_no">KVT No<span class="required">*</span></label>
							<div class="col-xs-7">
								<input type="text" class="form-control validate[required] text-uppercase" id="packing_no" name="packing_no" maxlength="10">
							</div>
						</div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-4" for="contract_no"><?php echo $this->lang->line('contract_no');?></label>
							<div class="col-xs-7">
								<input type="text" class="form-control text-uppercase" id="contract_no" name="contract_no" maxlength="30">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-xs-4"></div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-5" for="o_no">O/NO</label>
							<div class="col-xs-7">
								<input type="text" class="form-control text-uppercase" id="o_no" name="o_no" maxlength="20">
							</div>
						</div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-4" for="stype_no"><?php echo $this->lang->line('style_no');?></label>
							<div class="col-xs-7">
								<input type="text" class="form-control text-uppercase" id="stype_no" name="stype_no" maxlength="20">
							</div>
						</div>
					</div>
					<div class="x_panel">
						<div class="x_title">
								<h2><?php echo $this->lang->line('item_list');?><span style="color: red">*</span></h2>
								<p id="item_list_error" style="color: red; display:none; text-align:center"></p>
							<div class="clearfix"></div>
						</div>
						<div class="x_content">
							<div class="">
								<table id="products_list_search" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
									<thead>
										<tr>
											<th>No</th>
											<th><?php echo $this->lang->line('item_code');?></th>
											<th><?php echo $this->lang->line('items_name');?></th>
											<th><?php echo $this->lang->line('size');?></th>
											<th><?php echo $this->lang->line('color');?></th>
											<th><?php echo $this->lang->line('quantity');?></th>
											<th><?php echo $this->lang->line('action');?></th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
<!-- Modal -->
<div id="pv_no_detail" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<button class="btn btn-info" data-dismiss="modal"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button>
					</li>
					<li>
						<button disabled id="btn_save_pv" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
					</li>
				</ul>
				<h4 class="modal-title"><?php echo $this->lang->line('pv_no_list');?></h4>
            </div>
            <div class="modal-body">
                <div class="das_content table-scroll" style="overflow-y: auto">
					<h5 id="quantity_kvt" style="color: #337ab7;"></h5>
					<table class="table table-bordered cssTable" id="tbl_pv_no" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>PV No</th>
								<th><?php echo $this->lang->line('partition_no'); ?></th>
								<th><?php echo $this->lang->line('order_date'); ?></th>
								<th><?php echo $this->lang->line('quantity').' PV' ?></th>
								<th><?php echo $this->lang->line('out_quantity')?></th>
								<th><?php echo $this->lang->line('action'); ?></th>
							</tr>
						</thead>
					</table>
                    
                </div>
                <div class="col-sm-12 col-md-12 col-xs-12 tile_count no-padding">
                    <div class="col-sm-6 col-md-6 col-xs-6 no-padding-left">
                        <select name="pv_list" id="pv_list" class="form-control">
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-6 col-xs-6 no-padding-right" style="text-align: right;">
                        <button class="btn btn-info" id="add_record" style="margin-right: 0;"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $this->lang->line('btn_add_pv_no'); ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>
<!-- Modal -->
<div id="times_modal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<ul class="nav navbar-right panel_toolbox">
					<li><button class="btn btn-info" data-dismiss="modal"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button>
					</li>
					<li id="times_save"><button class="btn btn-primary" onclick="saveTimes()"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
					</li>
				</ul>
				<h2><?php echo $this->lang->line('input_quantity_for_times');?></h2>
			</div>
			<div class="modal-footer">
				<form class="form-horizontal" id="frm_times">
					<input type="hidden" class="form-control" id="order_date" name="order_date" >
					<input type="hidden" class="form-control" id="edit_date" name="edit_date" >
					<input type="hidden" class="form-control" id="detail_no" name="detail_no" >
					<div class="row">
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-5" for="dno">DNO</label>
							<div class="col-xs-7">
								<input type="text" class="form-control" id="dno" name="dno" readonly>
							</div>
						</div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-4" for="kno">KNO</label>
							<div class="col-xs-7">
								<input type="text" class="form-control" id="kno" name="kno" readonly>
							</div>
						</div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-4" for="kno">Times</label>
							<div class="col-xs-7">
								<input type="text" class="form-control" id="times" name="times" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-5" for="item_code"><?php echo $this->lang->line('item_code');?></label>
							<div class="col-xs-7">
								<input type="text" class="form-control" id="item_code" name="item_code" readonly>
							</div>
						</div>
						<div class="form-group col-xs-8">
							<label class="control-label col-xs-2" for="item_name"><?php echo $this->lang->line('item_name');?></label>
							<div class="col-xs-7" style="padding-left: 6px;">
								<input type="text" class="form-control" id="item_name" name="item_name" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-5" for="color"><?php echo $this->lang->line('color');?></label>
							<div class="col-xs-7">
								<input type="text" class="form-control" id="color" name="color" readonly>
							</div>
						</div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-4" for="size"><?php echo $this->lang->line('size');?></label>
							<div class="col-xs-7">
								<input type="text" class="form-control" id="size" name="size" readonly>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-5" for="quantity"><?php echo $this->lang->line('quantity');?></label>
							<div class="col-xs-7">
								<input type="text" class="form-control" id="quantity" name="quantity" maxlength="10" readonly>
							</div>
						</div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-4" for="times1">This Times<span class="required">*</span></label>
							<div class="col-xs-7">
								<input type="text" class="form-control validate[required]" id="times1" name="times1" onkeydown="return checkQuantity(event)" onkeyup="calculator()">
							</div>
						</div>
						<div class="form-group col-xs-4">
							<label class="control-label col-xs-4" for="times2">Next Times</label>
							<div class="col-xs-7">
								<input type="text" class="form-control" id="times2" name="times2" readonly>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
    </div>
</div>
<script>
	var currentDetailData = null;
	var currentKVTData = null;
	var currentKVTRow = null;
	var packingOrder = null;
	var pvDt = null;
	var pvTemp = '';
	var currentRowItem = null;
	var currentDetail = '';
	var dvtInfo = {'order_date': '', 'dvt_no': '', 'times': 0};
	var listKVT = <?php echo json_encode($listKVT); ?>;
	var currentDVT = '';
	var historyKVT = [];
	window.onload = function()
	{
		$('#times_modal').validationEngine();
		if(listKVT.length > 0){
			currentDVT = listKVT[0]['dvt_no'];
		}
		var urlParams = <?php echo json_encode($urlParams); ?>;
		if(urlParams.length > 0){
			dvtInfo['order_date'] = urlParams[0];
			dvtInfo['dvt_no'] = urlParams[1];
			if(urlParams[1].length < 10){
				dvtInfo['dvt_no'] = urlParams[1] + new Array(10 - urlParams[1].length + 1).join(' ');
			}
			
			dvtInfo['times'] = urlParams[2];
		}
		// PV No datatable
		pvDt = $('#tbl_pv_no').DataTable({
            "paging": false,
            "filter": false,
            "ordering": false,
            "scrollX" : false,
            "info": false,  
            "data": [],
            "columns": [
                {data: 'no', className: "text-center", render: function( data, type, row, meta){
                    return '' + (meta.row + 1);
                }},
                {data: 'pv_no', className: "text-left"},
				{data: 'partition_no', className: "text-center"},
				{data: 'order_receive_date', className: "text-center"},
				{ data:'quantity',className: "text-right", render: function(data){
					return data;
				}},
				{ data:'quantity_kvt',className: "text-right", render: function(data){
					//return numberWithCommas(data);
					var $input = $('<input>')
						.attr('type', 'number')
						.attr('class', 'form-control datatable-input')
						.attr('name', 'pv-quantity')
						.attr('maxlength', '20')
						.attr('value', parseFloat(data))
						.attr('onkeydown', 'return checkQuantity(event)');
					return $input[0].outerHTML;
				}},
                {data: 'action', class: 'text-center', render: function(){
                    return  '<a data-event="delete-item" href="#" class="btn btn-default btn-xs btn-custom" title="Delete">'
                        + '<span class="glyphicon glyphicon-trash"></span>'
                        + '</a>';
                }},
            ]
		});
		$('#pv_no_detail').on('shown.bs.modal', function() {
			$("#btn_save_pv").prop("disabled", true);
		})
		$("#pv_no_detail").on("focus click", "input,a,#add_record", function(){
			$("#btn_save_pv").prop("disabled", false);
		});
		packingOrder = $('#products_list_search').DataTable( {
			"data": [],
			"paging": false,
			// "pageLength": 5,
			// "lengthMenu": [ 5, 10, 25, 50, 75, 100 ],
			"scrollY" : 200,
			"scrollX" : true,
			"order": [[ 0, "asc" ]],
			"ajax": {
				"url": "<?php echo base_url('japan_order/getStoreItems'); ?>",
				"type": 'post',
				"data": function ( data ) {
					data.param = dvtInfo;
				}
			},
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
			"columns": [
						{ "data": "no", render: function( data, type, row, meta ){
							return (meta.row+1);
						}},
						{ "data": "item_code","className": "text-left", render: function( data, type, row, meta ){
							return row.item_code;
						}},
						{ "data": "item_name","className": "text-left", render: function( data, type, row, meta ){
							return row.item_name;
						}},
						{ "data": "size", render: function( data, type, row, meta ){
							return row.size;
						}},
						{ "data": "color", render: function( data, type, row, meta ){
							return row.color;
						}},
						{ "data": "quantity","className": "text-right", render: function( data, type, row, meta ){
							return numberWithCommas(row.quantity);
						}},
						{ "data": "", render: function( data, type, row, meta ){
							// var listKVT = <?php echo json_encode($listKVT); ?>;
							var flg = false;
							if ( row.color == null ) row.color = '';
							if ( row.size == null ) row.size = '';
							listKVT.forEach(function($tmpkvt){
								flg = flg ? true : $tmpkvt.detail.some(function($ite){
									return $ite.item_code == row.item_code && $ite.color ==  row.color && $ite.size == row.size && $ite.pv_no.substr(0, $ite.pv_no.lastIndexOf('-')) == row.order_receive_no;
								});
							});
							// for(let i=0; i< listKVT.length; i++){
							// 	if(currentDVT && currentDVT != '' && listKVT[i]['dvt_no'] == currentDVT){
							// 		for(let j=0; j< listKVT[i]['detail'].length; j++){
							// 			if(listKVT[i]['detail'][j]['color'] == null) listKVT[i]['detail'][j]['color'] = '';
							// 			if(listKVT[i]['detail'][j]['size'] == null) listKVT[i]['detail'][j]['size'] = '';
							// 			if(
							// 			listKVT[i]['detail'][j]['item_code'] == row.item_code &&
							// 			listKVT[i]['detail'][j]['color'] == row.color &&
							// 			listKVT[i]['detail'][j]['size'] == row.size && listKVT[i]['detail'][j]['size']){
							// 				flg = true;
							// 			}
							// 		}
							// 	}
							// }
							var html = '';
							html += '<input type="checkbox" class="flat" ';
							if(flg) {
								html += 'disabled checked';
							}
							html += '>';
							return html;
						}},
					],
		} );
		var itemDt = $('#schedule_detail_list').DataTable({
            // "data": <?php echo json_encode($listKVT); ?>,
            "data": [],
            "paging": true,
            "filter": true,
            "ordering": true,
            "scrollX" : false,
            "ajax": {
					"url": "<?php echo base_url('japan_order/searchKVT'); ?>",
					"type": 'post',
					"data": function ( data ) {
						data.param = dvtInfo;
					}
				},
            "columns": [
                { "data": "kvt_no", render: function( data, type, row, meta ){
                    html = '<div style="color:#4285f4; text-align: left"><span class="collapse-order glyphicon glyphicon-triangle-right"></span>' + row.kvt_no+'</div>';
                    return html;
                }},
                { "data": "dvt_no","className": "text-left", render: function( data, type, row, meta ){
                    return row.dvt_no;
                }},
                { "data": "order_date", render: function( data, type, row, meta ){
                    return row.order_date;
                }},
                { "data": "times", render: function( data, type, row, meta ){
									if(row.times_count > 1 || row.times > 1){
										return row.times;
									}
                    return '';
                }},
                { "data": "staff","className": "text-left", render: function( data, type, row, meta ){
                    return row.staff;
                }},
                { "data": "staff_id","className": "text-left", render: function( data, type, row, meta ){
                    return row.staff_id;
                }},
                { "data": "contract_no","className": "text-left", render: function( data, type, row, meta ){
                    return row.contract_no;
                }},
                { "data": "stype_no","className": "text-left", render: function( data, type, row, meta ){
                    return row.stype_no;
                }},
                { "data": "o_no","className": "text-left", render: function( data, type, row, meta ){
                    return row.o_no;
                }},
                { "data": "assistance","className": "text-left", render: function( data, type, row, meta ){
                	return row.assistance;
                }},
				{ "data": "delivery_date", render: function( data, type, row, meta ){
                	return row.delivery_date;
                }},
                { "data": "factory","className": "text-left", render: function( data, type, row, meta ){
					if(row.factory){
						return '<div class="ellipsis" title="'+row.factory+'">'+row.factory+'</div>';
					}
                	return '';
                }},
				{ "data": "delivery_method", render: function( data, type, row, meta ){
                	return row.delivery_method;
				}},
				 { "data": "action", render: function( data, type, row, meta ){
                    var html = '';
					var disableStatus = <?php echo json_encode(explode("," ,STATUS_FINISH)); ?>;
					if (disableStatus.length > 0 && (disableStatus.indexOf(row.dvt_status) == -1)){
					html += '<a data-event="edit-item" onclick="openEditModal(this)" class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>">'
						+ '<span class="fa fa-edit"></span>'
						+ '</a>';
					html += '<a data-event="delete-item" style="margin-left: 3px;" class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>">'
						+ '<span class="fa fa-trash"></span>'
						+ '</a>';
					}
                    return html;
                } },
            ]
		});
		currentKVTData = itemDt;
		var hisShow = false;
		// collapse detail
		$('#schedule_detail_list').on('click', 'span.collapse-order', function(){
			var tr = $(this).closest("tr");
			currentKVTRow = tr;
			var row = itemDt.row(tr);
			hisShow = row.child.isShown();
			itemDt.rows().eq(0).each( function ( rowIdx ) {
				if(row.index() != rowIdx){
					var otherRow = itemDt.row(rowIdx);
					if(otherRow.child.isShown()){
						otherRow.child.hide();
						otherRow.nodes().to$().removeClass("shown");
						$('#schedule_detail_list').find('span.collapse-order').removeClass("glyphicon-triangle-bottom");
					}
				}
			} );
            if (hisShow) {
                $(this).removeClass("glyphicon-triangle-bottom");	
                row.child.hide();
                tr.removeClass("shown");
            } else {
                $(this).addClass("glyphicon-triangle-bottom");
				row.child(renderChildRow(row.data())).show();
				tr.addClass("shown");
				currentDetail = row.data().kvt_no.trim()+row.data().order_date.trim()+row.data().times.trim();
				currentDetailData = $('#'+currentDetail).DataTable({
					"data": [],
					"paging": false,
					"filter": false,
					"ordering": false,
					"scrollX" :true,
					"info": false,
					"ajax": {
						"url": "<?php echo base_url('japan_order/searchItems'); ?>",
						"type": 'post',
						"data": function ( data ) {
							data.param = {};
							data.param['order_date'] = row.data().order_date;
							data.param['dvt_no'] = row.data().dvt_no;
							data.param['kvt_no'] = row.data().kvt_no;
							data.param['times'] = row.data().times;
						}
					},
					"drawCallback": function() {
						// datepicker
						$(".date").datepicker({
							todayHighlight: true,
							format: "d M, yyyy",
							autoclose: true
						});
					},
					"columns": [
						{ "data": "no", render: function( data, type, row, meta ){
							return (meta.row+1);
						}},
						{ "data": "item_code","className": "text-left", render: function( data, type, row, meta ){
							return row.item_code;
						}},
						{ "data": "item_name","className": "text-left", render: function( data, type, row, meta ){
							return '<div class="ellipsis" title="'+row.item_name+'">'+row.item_name+'</div>';
						}},
						{ "data": "size", render: function( data, type, row, meta ){
							return row.size;
						}},
						{ "data": "color", render: function( data, type, row, meta ){
							return row.color;
						}},
						{ "data": "quantity","className": "text-right", render: function( data, type, row, meta ){
							if (row.edit_mode){
								var $input = $('<input>')
									.attr('type', 'number')
									.attr('class', 'form-control datatable-input')
									.attr('name', 'quantity')
									.attr('maxlength', '20')
									.attr('value', parseFloat(row.quantity))
									.attr('onkeydown', 'return checkQuantity(event)');
								return $input[0].outerHTML;
							} else {
								return numberWithCommas(row.quantity);
							}
						}},
						{ "data": "buy_price","className": "text-right", render: function( data, type, row, meta ){
							return numberWithCommas(parseFloat(row.buy_price).myToFixed(row.currency));
						}},
						{ "data": "sell_price","className": "text-right", render: function( data, type, row, meta ){
							return numberWithCommas(parseFloat(row.sell_price).myToFixed(row.currency));
						}},
						{ "data": "base_price","className": "text-right", render: function( data, type, row, meta ){
							return numberWithCommas(parseFloat(row.base_price).myToFixed(row.currency));
						}},
						{ "data": "shosha_price","className": "text-right", render: function( data, type, row, meta ){
							if (row.edit_mode){
								var $input = $('<input>')
									.attr('value', parseFloat(row.shosha_price).myToFixed(row.currency))
									.attr('type', 'number')
									.attr('class', 'form-control datatable-input')
									.attr('name', 'shosha_price')
									.attr('onkeydown', 'return checkQuantity(event)');
								return $input[0].outerHTML;
							} else {
								return numberWithCommas(parseFloat(row.shosha_price).myToFixed(row.currency));
							}
						}},
						{ "data": "packing_date", render: function( data, type, row, meta ){
							if (row.edit_mode){
								var $input = $('<input>')
									.attr('value', formatDate(row.packing_date))
									.attr('class', 'form-control hasDatepicker date datatable-input')
									.attr('name', 'packing_date')
									.css('max-width', '100px');
								return $input[0].outerHTML;
							} else {
								return row.packing_date;
							}
						}},
						{ "data": "pv_no",  "className": "text-left", render: function( data, type, row, meta ){
							var array = <?php echo json_encode($pvNoList)?>;
							if (row.edit_mode){
								if(row.pv_no != null && row.pv_no != ''){
									const pvList = row.pv_no.replace(/\s/g,'').split(",");
									if(pvList.length > 1){
										return '<a onclick="editPVNo(this)" class="edit" title = "'+row.pv_no.replace(/\s/g,'')+'">'+pvList[0].split("*")[0].trim()+',...</a>';
									}else{
										return '<a onclick="editPVNo(this)" class="edit">'+pvList[0].split("*")[0].trim()+'</a>';
									}
								}else{
									return  '<a onclick="editPVNo(this)" title="Add">'
											+ '<span class="glyphicon glyphicon-plus"></span>'
											+ '</a>';
								}
							} else {
									if(row.pv_no != null && row.pv_no != ''){
										const pvList = row.pv_no.replace(/\s/g,'').split(",");
										if(pvList.length > 1){
											return '<div title = "'+row.pv_no.replace(/\s/g,'')+'">'+pvList[0].split("*")[0]+',...</div>';
										}else{
											return '<div>'+pvList[0].split("*")[0]+'</div>';
										}
									}
								return '';
							}
						}},
						{ "data": "arrival_date", render: function( data, type, row, meta ){
							return row.arrival_date;
						}},
						{ "data": "item_quantity","className": "text-right", render: function( data, type, row, meta ){
							return numberWithCommas(row.item_quantity);
						}},
						{ "data": "action","className" : "text-left",render: function( data, type, row, meta ){
							var html = '';
							var disableStatus = <?php echo json_encode(explode("," ,STATUS_FINISH)); ?>;
							if (row.edit_mode) {
								html += '<a onclick="DetailSave(this)" data-event="save-item-detail" style="margin: 0 1px;" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
									+ '<span class="fa fa-check"></span>'
									+ '</a>';
								html += '<a onclick="DetailClose(this)" data-event="close-item-detail" style="margin: 0 1px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
									+ '<span class="fa fa-close"></span>'
									+ '</a>';
							} else {
								if (disableStatus.length > 0 && (disableStatus.indexOf(row.status) == -1 && (disableStatus.indexOf(row.dvt_status) == -1))){
									html += '<a onclick="DetailEdit(this)" data-event="edit-item-detail" style="margin: 0 1px;" class="btn btn-xs btn-info" title="<?php echo $this->lang->line('edit'); ?>">'
										+ '<span class="fa fa-edit"></span>'
										+ '</a>';
									html += '<a onclick="DetailDelete(this)" data-event="delete-item-detail" style="margin: 0 1px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('delete'); ?>">'
										+ '<span class="fa fa-trash"></span>'
										+ '</a>';
									if(row.status != '005'){
										html += '<a onclick="Times(this)" data-event="times-item-detail" style="margin: 0 1px;" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('split_times'); ?>">'
										+ 'Times'
										+ '</a>';
									}
									
								}
							}
							return html;
						}},
					]
				});
            }
		});
		
		// Click edit in datatable
        $('#schedule_detail_list').on('click', '[data-event="edit-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            rowData.edit_mode = true;
            itemDt.row($row).data(rowData).invalidate();
            itemDt.draw( false ); 
		});
		
		// Click close in datatable
        $('#schedule_detail_list').on('click', '[data-event="close-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            rowData.edit_mode = false;
            itemDt.row($row).data(rowData).invalidate();
            itemDt.draw( false ); 
		});
		// Click save in datatable
        $('#schedule_detail_list').on('click', '[data-event="save-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            bootbox.confirm({
                title: "<?php echo ($this->lang->line('JOS0020_I001'))?>",
                message: '<h4 style="color:red;">' + rowData.kvt_no + '</h4>', 
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
                        requestData.times = rowData.times;
						requestData.dvt_no = rowData.dvt_no;
						requestData.kvt_no = rowData.kvt_no;
						requestData.edit_date = rowData.edit_date;
                        requestData.stype_no = $row.find("input[name='stype_no']").val();
                        requestData.o_no = $row.find("input[name='o_no']").val();
                        $.ajax({
                            url: "<?php echo base_url('japan_order/saveKVT')?>",
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
		 // Click delete KVT in datatable
		 $('#schedule_detail_list').on('click', '[data-event="delete-item"]', function(){
            var $row = $(this).parents('tr'),
            rowData = itemDt.row($row).data(),
            requestData = {};
			requestData.order_date = rowData.order_date;
			requestData.times = rowData.times;
			requestData.dvt_no = rowData.dvt_no;
			requestData.kvt_no = rowData.kvt_no;
			requestData.edit_date = rowData.edit_date;

            bootbox.confirm({
                title: "<?php echo ($this->lang->line('JOS0020_I002'))?>",
                message: '<h4 style="color:red;">' + rowData.kvt_no + '</h4>', 
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
                            url: "<?php echo base_url('japan_order/deleteKVT')?>",
                            data: requestData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function(response) {
                            snackbarShow(response.message);
                            if(response.success == true) { 
                                itemDt.row($row).remove();
                                itemDt.draw( false );
								updateKVTList();
                            } else {
                                itemDt.draw( false );
                            }
                        });
                    }
                }
            });
        });
		function renderChildRow(data) {
            var el =
                "<table id='"+data.kvt_no.trim()+data.order_date.trim()+data.times.trim()+"' class='table table-striped table-dashed display nowrap' width='100%' cellspacing='0'>" +
                "<thead style='background-color:#d2ecc0'>" +
                "<tr>" +
                "<th>No</th>" +
                "<th>" + lang['item_code'] + "</th>" +
                "<th>" + lang['item_name'] + "</th>" +
                "<th>" + lang['size'] + "</th>" +
                "<th>" + lang['color'] + "</th>" +
                "<th>" + lang['quantity'] + "</th>" +
                "<th>" + lang['buy_price'] + "</th>" +
                "<th>" + lang['sell_price'] + "</th>" +
                "<th>" + lang['base_price'] + "</th>" +
                "<th>" + lang['shosha_price'] + "</th>" +
                "<th>" + lang['pack_date'] + "</th>" +
                "<th style='background-color: #ffff00;'>PV No</th>" +
                "<th>" + lang['input_date'] + "</th>" +
                "<th>" + lang['store_quantity'] + "</th>" +
                "<th>" + lang['action'] + "</th>" +
                "</tr>" +
                "</thead></table>";
            return el;
		}
		// Click edit in datatable
		$('#'+currentDetail).on('click', '[data-event="edit-item-detail"]', function(){
			var $row = $(this).parents('tr');
			var rowData = currentDetailData.row($row).data();
			rowData.edit_mode = true;
			currentDetailData.row($row).data(rowData).invalidate();
			currentDetailData.draw( false ); 
		});

		$('#packing_order_input_modal').on('shown.bs.modal', function() {
			$('#kvt_info').validationEngine('hideAll');
			if(packingOrder != null){
				packingOrder.ajax.reload();
				packingOrder.draw();
			}
			$("#packing_no").focus();
			if($("#packing_no").attr('readonly') == 'readonly'){
				$("#contract_no").focus();
			}
		});
		$('#add_record').click(function(e) {
			e.preventDefault(); 
			var pv_no = $("#pv_list :selected").attr('data-pv_no'),
			quantity = $("#pv_list").val(),
			quantity_kvt =$("#pv_list :selected").attr('data-kvt'),
			partition_no = $("#pv_list :selected").attr('data-part'),
			partition_no_display = $("#pv_list :selected").attr('data-part'),
			order_date = $("#pv_list :selected").attr('data-order_date');
			check_count = $("#pv_list :selected").attr('data-check_count');
			data_price = $("#pv_list :selected").attr('data-price');
			if(pv_no != '') {
				var pvData = pvDt.data();
				var findItem = false;
				for (var index = 0; index < pvData.length; index++) {
					var pv = pvData[index];
					if (pv.pv_no == pv_no && pv.partition == partition_no) {
						findItem = true;
						break;
					}
				}
				if (findItem) {
					bootbox.alert('<h4><?php echo $this->lang->line('JOS0020_E002');?></h4>');
					return;
				}
				if(check_count == '1'){
					partition_no_display = '';
				}
				pvDt.row.add({pv_no: pv_no, partition_no: partition_no_display, order_receive_date: order_date, quantity: quantity, quantity_kvt: quantity_kvt, check_count: check_count, partition: partition_no, data_price:data_price}).draw();
			}
		});
		$('#btn_save_pv').click(function(){
			var totalQuantity = 0;
			var missingFlg = false;
			$('#tbl_pv_no input[name="pv-quantity"]').each(function(ele){
				if($(this).val() == '' || parseInt($(this).val()) <= 0){
					$(this).focus();
					// $(this).css('border-color', 'red');
					missingFlg = true;
					return;
				}
				totalQuantity += parseInt($(this).val());
			});
			if(missingFlg){
				return;
			}
			var $row = $(currentRowItem).closest('tr');
			rowData = currentDetailData.row($row).data();
			var itemQuantity = parseInt($row.find('input[name="quantity"]').val());
			if(totalQuantity > itemQuantity){
				bootbox.alert('<h4><?php echo $this->lang->line('JOS0020_E003');?></h4>');
				return;
			}
			var pvData = pvDt.data();
			for (let i = 0; i< pvData.length; i++){
				if(parseInt(pvData[i].quantity) < parseInt(pvData[i].quantity_kvt) ){
					bootbox.alert('<?php echo $this->lang->line('JOS0020_E004'); ?>');
					return;
				}
			}
			pvTemp = '';
			var sell_price = 0;
			var base_price = 0;
			var buy_price = 0;
			var shosha_price = 0;
			var num = 0;
			for (var i = 0; i < pvData.length; i++) {
				var tail = pvData[i].pv_no;
				num += parseInt(pvData[i].quantity_kvt);
				tail += ('-' + pvData[i].partition);
				if(i == (pvData.length - 1)){
					pvTemp += (tail +'*'+ pvData[i].quantity_kvt);
				}else{
					pvTemp += (tail +'*'+ pvData[i].quantity_kvt) + ",";
				}
				if(pvData[i].data_price){
					const dataPrice = JSON.parse(pvData[i].data_price);
					sell_price += dataPrice.sell_price ? (parseFloat(dataPrice.sell_price) * pvData[i].quantity_kvt) : 0;
					base_price += dataPrice.base_price ? (parseFloat(dataPrice.base_price)* pvData[i].quantity_kvt) : 0;
					buy_price += dataPrice.buy_price ? (parseFloat(dataPrice.buy_price) * pvData[i].quantity_kvt) : 0;
					shosha_price += dataPrice.shosha_price ? (parseFloat(dataPrice.shosha_price) * pvData[i].quantity_kvt) : 0;
				}
			}
			var shosha = $row.find('input[name="shosha_price"]').val();
			if(shosha == ''){
				$row.find('input[name="shosha_price"]').val(shosha_price/num);
			}
			rowData.pv_no = pvTemp;
			rowData.quantity = $row.find('input[name="quantity"]').val();
			rowData.packing_date = $row.find('input[name="packing_date"]').val();
			rowData.currency = (pvData[0] && pvData[0].data_price) ? JSON.parse(pvData[0].data_price).currency : 'USD';
			rowData.sell_price = (sell_price/num).myToFixed(rowData.currency);
			rowData.base_price = (base_price/num).myToFixed(rowData.currency);
			rowData.buy_price = (buy_price/num).myToFixed(rowData.currency);
			rowData.shosha_price = $row.find('input[name="shosha_price"]').val();
			currentDetailData.row($row).data(rowData).invalidate();
			currentDetailData.draw(true);
			listPVNoChange($row);
			$('#pv_no_detail').modal('hide');
		});
		// $('#pv_no_detail').on('hidden.bs.modal', function(){
		// 	var pvData = pvDt.data();
		// 	var $row = $(currentRowItem).parents('tr'),
		// 	rowData = currentDetailData.row($row).data();
		// 	pvTemp = '';
		// 	for (var i = 0; i < pvData.length; i++) {
		// 		var tail = pvData[i].pv_no;
		// 		if(pvData[i].check_count == '2'){
		// 			tail += ('-' + pvData[i].partition);
		// 		} 
		// 		if(i == (pvData.length - 1)){
		// 			pvTemp += (tail +'*'+ pvData[i].quantity);
		// 		}else{
		// 			pvTemp += (tail +'*'+ pvData[i].quantity) + ",";
		// 		}
		// 	}
		// 	rowData.pv_no = pvTemp;
		// 	rowData.quantity = $row.find('input[name="quantity"]').val();
		// 	rowData.packing_date = $row.find('input[name="packing_date"]').val();
		// 	currentDetailData.row($row).data(rowData).invalidate();
		// 	currentDetailData.draw( false );
		// 	listPVNoChange($row);
		// });

		$('#pv_no_detail').on('change', 'input[name="pv-quantity"]' ,function () {
			var $row = $(this).parents('tr');
			rowData = pvDt.row($row).data();
			var quantity = $row.find(this).val(); 
			rowData.quantity_kvt = quantity;
		});
		// Click edit in datatable
		$('#tbl_pv_no').on('click', '[data-event="delete-item"]', function(){
			var $row = $(this).parents('tr');
			pvDt.row($row).remove();
			pvDt.draw();
		});
	}
	function updateKVTList(){
		var requestData = {};
		requestData.param = dvtInfo;
		$.ajax({
			url: "<?php echo base_url('japan_order/searchKVT')?>",
			data: requestData,
			type: 'post',
			dataType: 'json',
		}).done( function(response) {
			if(response.data){
				listKVT = response.data;
			}
		});
	}
	function editPVNo(obj){
		currentRowItem = obj;
		var $row = $(obj).closest('tr'),
		rowData = currentDetailData.row($row).data(),
		requestData = {};
			
		// Get quantity from input when click pv no list 
		var quantityLanguage = '<?php echo $this->lang->line('quantity');?>'
		var quantity_kvt = $row.find("input[name='quantity']").val();
		if(quantity_kvt == null || quantity_kvt == '') {
			$('#quantity_kvt').text(quantityLanguage + ' in KVT : 0');
		} else {
			$('#quantity_kvt').text(quantityLanguage+' in KVT : '+quantity_kvt);
		}

		const itemPVList = rowData.pv_no;
		var pvList = [];
		if(itemPVList != undefined && itemPVList != null){
			pvList = itemPVList.split(",");
		}

		var parentRowData = currentKVTData.row(currentKVTRow).data();
		requestData.order_date = parentRowData.order_date;
		requestData.times = parentRowData.times;
		requestData.dvt_no = parentRowData.dvt_no;
		requestData.kvt_no = parentRowData.kvt_no;
	  requestData.item_code = rowData.item_code;
	  requestData.color = rowData.color;
		requestData.size = rowData.size;
		requestData.pvList = pvList;
		if(pvList.length > 0){
			$.ajax({
				url: "<?php echo base_url('japan_order/getPVList')?>",
				data: requestData,
				type: 'post',
				dataType: 'json',
			}).done( function(response) {
				pvDt.clear();
				if(response != null && response != undefined) {
					for (var index = 0; index < response.length; index++) {
						// const element = response[index];
						var partition = '';
						if(response[index]['check_count'] == '2'){
							partition = response[index]['partition_no'];
						}
						const element = {
							'pv_no' : response[index]['pv_no'],
							'partition_no' : partition,
							'partition' : response[index]['partition_no'],
							'order_receive_date' : response[index]['order_receive_date'],
							'quantity' : response[index]['quantity'],
							'quantity_kvt' : response[index]['quantity_kvt'],
							'check_count' : response[index]['check_count'],
							'data_price': JSON.stringify(response[index]),
						}
						pvDt.row.add(element);
					}
				}
				pvDt.draw();
				$('#pv_no_detail').modal('show');
			});
		}else{
			pvDt.clear();
			pvDt.draw();
			$('#pv_no_detail').modal('show');
		}

		requestData.pvList = null;
		$.ajax({
			url: "<?php echo base_url('japan_order/getPVList')?>",
			data: requestData,
			type: 'post',
			dataType: 'json',
		}).done( function(response) {
			if(response != null && response != undefined) {
				$('#pv_list').find('option').remove();
				for (var index = 0; index < response.length; index++) {
					var opt = document.createElement('option');
					var html = response[index]['pv_no'].trim();
					if(response[index]['check_count'] == '2'){
						html += "-" + response[index]['partition_no'];
					}
					opt.value = response[index]['quantity'];
					opt.innerHTML = html;
					opt.setAttribute ("data-check_count", response[index]['check_count']);
					opt.setAttribute ("data-pv_no", response[index]['pv_no']);
					opt.setAttribute ("data-part", response[index]['partition_no']);
					opt.setAttribute ("data-order_date", response[index]['order_receive_date']);
					opt.setAttribute ("data-kvt", response[index]['quantity_kvt']);
					opt.setAttribute ("data-price", JSON.stringify(response[index]));
					$('#pv_list').append(opt);
				}
			}
		});
	}
	function DetailEdit(obj){
		var $row = $(obj).parents('tr');
		var rowData = currentDetailData.row($row).data();
		historyKVT[$row.index()] = Object.assign({}, rowData) ;
		rowData.edit_mode = true;
		pvTemp = rowData.pv_no;
		currentDetailData.row($row).data(rowData).invalidate();
		currentDetailData.draw( false );
	}
	function DetailDelete(obj){
		var $row = $(obj).parents('tr');
		var rowData = currentDetailData.row($row).data();
		var parentRowData = currentKVTData.row(currentKVTRow).data();
		var requestData = {};
			requestData.order_date = parentRowData.order_date;
			requestData.times = parentRowData.times;
			requestData.dvt_no = parentRowData.dvt_no;
			requestData.kvt_no = parentRowData.kvt_no;
			requestData.detail_no = rowData.detail_no;
			requestData.item_code = rowData.item_code;
			requestData.color = rowData.color;
			requestData.size = rowData.size;
			requestData.edit_date = rowData.edit_date;
			bootbox.confirm({
				title: "<?php echo ($this->lang->line('MTS0030_I001'))?>",
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
				callback: function(result) {
					if(result) {
						$.ajax({
							url: "<?php echo base_url('japan_order/deleteKVTItem')?>",
							data: requestData,
							type: 'post',
							dataType: 'json'
						}).done(function(response) {
							snackbarShow(response.message);
							if(response.success == true) { 
								currentDetailData.row($row).remove();
								// currentDetailData.ajax.reload();
								currentDetailData.draw( false );
								if(response.data){
									parentRowData = response.data;
									updateKVTList();
								}
								if(parentRowData.length <= 0 || (parentRowData.length > 0 && parentRowData.detail.length <= 0)){
									location.reload();
								}
							} else {
								currentDetailData.draw( false );
							}
						});
					}
				}
			});
	}
	function DetailClose(obj){
		var $row = $(obj).parents('tr');
		var rowData = currentDetailData.row($row).data();
		if(historyKVT[$row.index()]){
			rowData = historyKVT[$row.index()];
		}
		rowData.edit_mode = false;
		currentDetailData.row($row).data(rowData).invalidate();
		currentDetailData.draw(); 
	}
	function DetailSave(obj){
		const validateResult = $('input[name="quantity"]').val();
		if(validateResult < 0){
			$('input[name="quantity"]').css('border-color', 'red');
			return;
		}else{
			$('input[name="quantity"]').css('border-color', '#ccc');
		}
		var $row = $(obj).parents('tr');
		var rowData = currentDetailData.row($row).data();
		var parentRowData = currentKVTData.row(currentKVTRow).data();
		bootbox.confirm({
			title: "<?php echo ($this->lang->line('POD0020_I002'))?>",
			message: '<h4 style="color:red;">' +  rowData.item_code + '</h4>', 
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
						requestData.order_date = parentRowData.order_date;
						requestData.times = parentRowData.times;
						requestData.dvt_no = parentRowData.dvt_no;
						requestData.kvt_no = parentRowData.kvt_no;
						requestData.item_code = rowData.item_code;
						requestData.detail_no = rowData.detail_no;
						requestData.color = rowData.color;
						requestData.size = rowData.size;
						requestData.buy_price = rowData.buy_price;
						requestData.sell_price = rowData.sell_price;
						requestData.base_price = rowData.base_price;
						requestData.currency = rowData.currency ? rowData.currency : '';
						requestData.edit_date = rowData.edit_date;
						requestData.shosha_price = $row.find("input[name='shosha_price']").val();
						requestData.quantity = $row.find("input[name='quantity']").val();
						requestData.packing_date = $row.find("input[name='packing_date']").val();
						requestData.pv_no = pvTemp; //$row.find("select[name='pv_no']").children(":selected").text();
						$.ajax({
						url: "<?php echo base_url('japan_order/saveKVTItem')?>",
						data: requestData,
						type: 'post',
						dataType: 'json'
					}).done(function(response) {
						snackbarShow(response.message);
						if(response.success == true) {
							parentRowData = response.data;
							if(rowData.edit_mode) {
								rowData.edit_mode = false;
							}
							currentDetailData.row($row).data(parentRowData.detail[currentDetailData.row($row).index()]).invalidate();
							currentKVTData.ajax.reload();
							currentDetailData.draw( false );
						} else {
							if(response.data != ''){
								var error = response.data;
								$.each( error, function( i, val ) {
									$row.find("[name="+i+"]")
									.after( '<div field='+i+' style="color:red;">'+ val +'</div>' );
								});
								currentDetailData.draw( false );
							}
						}
					});
				}
            }
		});
	}
	function listPVNoChange(obj) {
		var $row = obj; //$(currentRowItem).parents('tr'),
			var rowData = currentDetailData.row($row).data();
			var requestData = {};
			const pvList = pvTemp.replace(/\s/g,'').split(",");
			var pvNoList = [];
			if(pvList.length > 0){
				for (let i = 0; i < pvList.length; i++) {
					pvNoList.push(pvList[i].split("*")[0]);
				}
			}
			requestData.item_code = rowData.item_code;
			requestData.color = rowData.color;
			requestData.size = rowData.size;
			requestData.order_receive_no = pvNoList;
			$.ajax({
					url: "<?php echo base_url('japan_order/searchItemByPVNo')?>",
					data: requestData,
					type: 'post',
					dataType: 'json'
				}).done(function(response) {
					if(response.success == true) {
						var storeQuantity = 0;
						var storeDate = '';
						for(let i= 0; i < response.data.length; i++){
							if(response.data[i].quantity)
								storeQuantity = parseInt(storeQuantity) + parseInt(response.data[i].quantity);
							if(response.data[i].arrival_date && response.data[i].arrival_date != null)
								if(i == (response.data.length - 1)){
									storeDate += (response.data[i].arrival_date);
								}else{
									storeDate += (response.data[i].arrival_date + ",");
								}
						}
						currentDetailData.row($row).data(rowData).invalidate();
						currentDetailData.draw( false );
						$('#'+currentDetail).find('tbody tr:nth-child('+($row.index()+1)+') td:nth-child(13)').html('<span title="'+storeDate+'">'+storeDate+'</span>');
						$('#'+currentDetail).find('tbody tr:nth-child('+($row.index()+1)+') td:nth-child(14)').text(storeQuantity);
					} else {
						currentDetailData.draw( false );
					}
				});
		// rowData.pv_no = $row.find('select[name="pv_no"]').children(":selected").text();
		currentDetailData.row($row).data(rowData);
		currentDetailData.draw( false );
	}
	function insertKVT(){
		var validateResult = $('#kvt_info').validationEngine('validate');
		if(!validateResult){
			return;
		}
		var requestData = {};
		var urlParams = <?php echo json_encode($urlParams); ?>;
		var items = new Array();
		var allPV = new Array();
		var errorFlg = false;
		requestData.urlParams = urlParams;
		requestData.delivery_date = $('#delivery_date').val();
		requestData.kvt_no = $('#packing_no').val();
		requestData.contract_no = $('#contract_no').val();
		requestData.o_no = $('#o_no').val();
		requestData.stype_no = $('#stype_no').val();
		$.each($("input[type='checkbox']:checked"), function() {
			var $row = $(this).parents('tr');
		   	var rowData = packingOrder.row($row).data();
			if(!this.disabled){
		   		items.push(rowData);
			}
			allPV.push(rowData);
	   });
	   requestData.items = items;
	   requestData.allPV = allPV;
	   if(items.length <= 0){
			$('#item_list_error').text("<?php echo ($this->lang->line('JOS0020_E001'));?>");
			$('#item_list_error').show();
			errorFlg = true;
	   }else{
			$('#item_list_error').hide();
	   }
	   if(errorFlg){
			errorFlg = false;
			return;
	   }
	   bootbox.confirm({
			title: "<?php echo ($this->lang->line('JOS0020_I001'))?>",
			message: '<h4 style="color:red;">' + requestData.kvt_no + '</h4>', 
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
						url: "<?php echo base_url('japan_order/insertKVT')?>",
						data: requestData,
						type: 'post',
						dataType: 'json'
					}).done(function(response) {
						if(response.success == true) {
							location.reload();
						} else {
							$('#item_list_error').text(response.message);
							$('#item_list_error').show();
						}
					});
				}
			}
		});
	}
	function openInsertModal(){
		$('#packing_no').val("").attr('readonly', false);
		$('#contract_no').val("");
		$('#o_no').val("");
		$('#stype_no').val("");
		$('#insertKVT').show();
		$('#updateDetailKVT').hide();
		var requestData = {};
		var urlParams = <?php echo json_encode($urlParams); ?>;
		requestData.order_date = urlParams[0];
		requestData.dvt_no = urlParams[1];
		requestData.times = urlParams[2];
		$.ajax({
			"url": '<?php echo base_url('japan_order/getInfoDVT');?>',
			"type": 'post',
			"data": requestData,
			"dataType": 'json',
		}).done(function(response){
			if(response.success) {
				if(response.data.contract_no){
					$('#contract_no').val(response.data.contract_no.trim());
				}
				if(response.data.style){
					$('#stype_no').val(response.data.style.trim());
				}
				$('#delivery_date').val(formatDate(response.data.plan_delivery_date));
			}
		});
		$('#packing_order_input_modal').modal('show');
	}
	var detailRowIndex = 0;
	var currentRowData = {};
	function openEditModal(obj){
		$('#item_list_error').hide();
		$('#insertKVT').hide();
		$('#updateDetailKVT').show();
		// var listKVT = <?php echo json_encode($listKVT); ?>;
		var $row = $(obj).parents('tr'),
			rowData = currentKVTData.row($row).data();
			currentRowData = rowData;
		detailRowIndex = $row.index();
		currentDVT = rowData.dvt_no;
		$('#packing_no').val(rowData.kvt_no).attr('readonly', true);
		const contractNo = rowData.contract_no == null ? '' : rowData.contract_no.trim();
		const oNo = rowData.o_no == null ? '' : rowData.o_no.trim();
		const stypeNo = rowData.stype_no == null ? '' : rowData.stype_no.trim();
		const deliveryDate = rowData.delivery_date == null ? '' : formatDate(rowData.delivery_date);
		$('#contract_no').val(contractNo);
		$('#o_no').val(oNo);
		$('#stype_no').val(stypeNo);
		$('#delivery_date').val(deliveryDate);
		dvtInfo['order_date']  = rowData.order_date;
		dvtInfo['dvt_no'] = rowData.dvt_no;
		dvtInfo['times'] = rowData.times;
		$('#packing_order_input_modal').modal('show');
	}
	function updateDetailKVT(){
		$('#item_list_error').hide();
		var requestData = {};
		// var listKVT = <?php echo json_encode($listKVT); ?>;
		var items = new Array();
		var errorFlg = false;
		currentDVT = currentRowData.dvt_no;
		requestData.kvt = currentRowData; 
		requestData.edit_date = currentRowData.edit_date;
		requestData.order_date = currentRowData.order_date;
		requestData.dvt_no = currentRowData.dvt_no;
		requestData.times = currentRowData.times;
		requestData.delivery_date = $('#delivery_date').val();
		requestData.kvt_no = $('#packing_no').val();
		requestData.contract_no = $('#contract_no').val();
		requestData.o_no = $('#o_no').val();
		requestData.stype_no = $('#stype_no').val();
		$.each($("input[type='checkbox']:checked"), function() {
           var $row = $(this).parents('tr');
		   var rowData = packingOrder.row($row).data();
		   items.push(rowData);           
	   });
	   requestData.items = items;
	   if(items.length <= 0){
			$('#item_list_error').text("<?php echo ($this->lang->line('JOS0020_E001'));?>");
			$('#item_list_error').show();
			errorFlg = true;
	   }else{
			$('#item_list_error').hide();
	   }
	   if(errorFlg){
			errorFlg = false;
			return;
	   }
	   bootbox.confirm({
			title: "<?php echo ($this->lang->line('JOS0020_I001'))?>",
			message: '<h4 style="color:red;">' + requestData.kvt_no + '</h4>', 
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
						url: "<?php echo base_url('japan_order/updateDetailKVT')?>",
						data: requestData,
						type: 'post',
						dataType: 'json'
					}).done(function(response) {
						if(response.success == true) {
							location.reload();
						} else {
							$('#item_list_error').text(response.message);
							$('#item_list_error').show();
						}
					});
				}
			}
		});
	}
	function Times(obj){
		var $row = $(obj).parents('tr');
		var rowData = currentDetailData.row($row).data();
		var parentRowData = currentKVTData.row(currentKVTRow).data();
		$('#times_modal input').val('');
		$('#times_modal #dno').val(parentRowData.dvt_no);
		$('#times_modal #kno').val(parentRowData.kvt_no);
		$('#times_modal #times').val(parentRowData.times);
		$('#times_modal #order_date').val(parentRowData.order_date);
		$('#times_modal #edit_date').val(rowData.edit_date);		
		$('#times_modal #item_code').val(rowData.item_code);
		$('#times_modal #item_name').val(rowData.item_name);
		$('#times_modal #color').val(rowData.color);
		$('#times_modal #size').val(rowData.size);
		$('#times_modal #quantity').val(rowData.quantity);
		$('#times_modal #detail_no').val(rowData.detail_no);
		$('#times_modal').modal('show');
	}
	function saveTimes(){
		const result = $('#frm_times').validationEngine('validate');
		if(!result){
			return;
		}
		if($('#times2').val() <= 0) {
			snackbarShow('<?php echo $this->lang->line('JOS0020_I003');?>');
			return;
		}
		var requestData = {};
		requestData.edit_date = $('#times_modal #edit_date').val();
		requestData.order_date = $('#times_modal #order_date').val();
		requestData.dvt_no = $('#times_modal #dno').val();
		requestData.times = $('#times_modal #times').val();
		requestData.kvt_no = $('#times_modal #kno').val();
		requestData.item_code = $('#times_modal #item_code').val();
		requestData.color = $('#times_modal #color').val();
		requestData.size = $('#times_modal #size').val();
		requestData.time1 = $('#times_modal #times1').val();
		requestData.time2 = $('#times_modal #times2').val();
		requestData.detail_no = $('#times_modal #detail_no').val();
		bootbox.confirm({
			title: "<?php echo $this->lang->line('split_times');?>",
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
					$('#times_modal').modal('hide');
					$.ajax({
						url: "<?php echo base_url('japan_order/divideKVT')?>",
						data: requestData,
						type: 'post',
						dataType: 'json'
					}).done(function(response) {
						snackbarShow(response.message);
						if(response.success == true) {
							location.reload();
						}
					});
				}
			}
		});
	}
	function calculator(){
		var num1 = parseFloat($('#frm_times #quantity').val());
		var num2 = parseFloat($('#frm_times #times1').val());
		const num3 = num1 - num2;
		if($.isNumeric(num3)){
			$('#frm_times #times2').val(num3);
		}
	}
</script>
