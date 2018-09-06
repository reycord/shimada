<div class="row">
	<div class="x_panel">
		<div class="x_title">
			<h2>
				<?php echo $title ?>
			</h2>
			<ul class="nav navbar-right panel_toolbox">
				<li>
					<button onclick="window.location.href='<?php echo base_url("packing_list") ?>';" class="btn btn-info">
						<i class="fa fa-arrow-left"></i>
						<?php echo $this->lang->line('back'); ?>
					</button>
				</li>
				<li>
					<button onclick="onClear()" class="btn btn-info">
						<i class="fa fa-refresh"></i>
						<?php echo $this->lang->line('clear'); ?>
					</button>
				</li>
				<li>
					<button type="button" id="btnSaveModal" class="btn btn-primary">
						<i class="fa fa-save"></i>
						<?php echo $this->lang->line('save'); ?>
					</button>
				</li>
			</ul>
			<div class="clearfix"></div>
		</div>
        <div class=" collapse in" id="collapse">
            <form id="packingForm" action="<?php echo base_url() ?>packing_list/save" method="post">
                <input type="hidden" class="form-control date" id="itemData" name="packing_details">
                <input type="hidden" class="form-control date" id="dvtObj" name="dvt_obj">
                <input type="hidden" class="form-control" id="totalQuantity" name="quantity">
                <div class="x_panel no-padding-bottom" style="margin-bottom: 5px;" >
                    <div class="x_content form-horizontal myform" >
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <div class="form-group">
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('pack_no'); ?>
                                    </label>
                                </div>
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left no-padding-right">
                                    <input id="pack_no" type="text" class="form-control" value="<?php echo $pack_no ?>" name="pack_no" readonly>
                                </div>
                                <div class="col-sm-3 col-md-3 col-xs-3">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('pack_date'); ?>
                                        <span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left no-padding-right">
                                    <input id="packing_date" type="text" data-validation-engine="validate[required]" class="form-control date" value="<?php echo (new DateTime())->format('d M, Y'); ?>" name="packing_date">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left">
                                    <label class="control-label form-title">
                                    Provisional Input
                                    </label>
                                </div>
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left no-padding-right" style="padding: 5px;">
                                    <input type="checkbox" id="provisionalCheckBox" class="flat">
                                </div>
                                <div class="col-sm-3 col-md-3 col-xs-3">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('status'); ?>
                                    </label>
                                </div>
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left no-padding-right">
                                    <input type="hidden" class="form-control" value="<?php if(isset($status_not_yet)) echo $status_not_yet ?>" name="status">
                                    <input type="text" class="form-control" value="<?php if(isset($status_not_yet_name)) echo $status_not_yet_name ?>" name="status_name" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('consigned_to'); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9 col-md-9 col-xs-9 no-padding-left no-padding-right has-clear has-feedback">
                                    <input type="text" class="form-control" id="delry_to" name="delry_to"/>
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
                                    <!-- <select class="form-control" id="delry_to" name="delry_to">
                                        <option value=""></option>
                                    </select> -->
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('address'); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9 col-md-9 col-xs-9 no-padding-left no-padding-right">
                                    <textarea spellcheck="false" class="form-control" id="delry_to_add" rows="5" name="delry_to_add" readonly><?php if(isset($packing)) echo $packing['delry_to_add'] ?></textarea>
                                </div>
                                <input type="hidden" name ="company_name" id="company_name" value="" >
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('type'); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9 col-md-9 col-xs-9 no-padding-left no-padding-right">
                                    <!-- <select class="form-control" id="types" name="types">
                                        <option value=""></option>
                                        <?php if(isset($types_list)){
                                            foreach($types_list as $types => $ty){?>
                                                <option value="<?php echo $ty['types_code'] ?>"><?php echo $ty['types_name']?></option>
                                            <?php }
                                        }?>
                                    </select> -->
                                    <input type="text" class="form-control" id="types" name="types" value="">
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <div class="form-group">
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('case_mark'); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9 col-md-9 col-xs-9 no-padding-left no-padding-right">
                                    <textarea spellcheck="false" class="form-control" id="case_mark" name="case_mark" style="height: 122px;"><?php if(isset($packing['case_mark'])) echo $packing['case_mark'] ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-3 col-md-3 col-xs-3 no-padding-left">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('note'); ?>
                                    </label>
                                </div>
                                <div class="col-sm-9 col-md-9 col-xs-9 no-padding-left no-padding-right">
                                    <textarea spellcheck="false" class="form-control" name="note" rows="5"><?php if(isset($packing)) echo $packing['note']?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
		<div id="addDVTBox" class="x_panel">
			<div class="x_title" style="margin-bottom: 0px;">
				<h2>
                <button type="button" class="btn btn-sm glyphicon glyphicon-chevron-up" id="btnCollapse"></button></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<button style="width:65px;" id="btnAddDVT" class="btn btn-success" data-toggle="modal" data-target="#packing_list_modal" >
							<i class="fa fa-plus"></i> DVT</button>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
		</div>
		<div id="dvt_kvt_box">
		</div>
	</div>
</div>
<!-- Modal -->
<!-- <div id="info_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel2">
					<?php echo $this->lang->line('PLS0010_E004'); ?>
				</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary antoclose2" data-dismiss="modal">
					<?php echo $this->lang->line('close'); ?>
				</button>
			</div>
		</div>
	</div>
</div> -->
<!-- Delete modal -->
<div id="item_delete_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel2">
					<?php echo $this->lang->line('PLS0010_I005'); ?>
				</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary antoclose2" data-dismiss="modal">
					<?php echo $this->lang->line('no'); ?>
				</button>
				<button type="button" onclick="onDeleteItem()" class="btn btn-success antosubmit2" data-dismiss="modal">
					<?php echo $this->lang->line('yes'); ?>
				</button>
			</div>
		</div>
	</div>
</div>
<!--Save modal -->
<div id="save_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel2">
					<?php echo $this->lang->line('PLS0010_I006'); ?>
				</h4>
			</div>
            <div class="modal-body">
                <h5 style="color:red" >PL No: <?php echo $pack_no ?></h5>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary antoclose2" data-dismiss="modal">
					<?php echo $this->lang->line('no'); ?>
				</button>
				<button type="button" onclick="onSave()" class="btn btn-success antosubmit2" data-dismiss="modal">
					<?php echo $this->lang->line('yes'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- delete packing_details modal -->
<div id="packing_details_delete_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel2">
					<?php echo $this->lang->line('JOS0010_I002'); ?>
				</h4>
			</div>
            <div class="modal-body">
                <h4 style="color:red" id="dvt_kvt_no"></h4>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary antoclose2" data-dismiss="modal">
					<?php echo $this->lang->line('no'); ?>
				</button>
				<button type="button" data-dismiss="modal" onclick="onDeleteDVTKVT()" class="btn btn-success antosubmit2">
					<?php echo $this->lang->line('yes'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<!-- delete packing modal -->
<div id="packing_delete_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel2">
					<?php echo $this->lang->line('PLS0010_I007'); ?>
				</h4>
			</div>
            <div class="modal-body">
                <h5 style="color:red">Pack No : <?php if(isset($packing)) echo $packing['pack_no'] ?><?php ?></h5>
            </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary antoclose2" data-dismiss="modal">
					<?php echo $this->lang->line('no'); ?>
				</button>
				<button type="button" data-dismiss="modal" onclick="onDeletePacking()" class="btn  btn-success antosubmit2">
					<?php echo $this->lang->line('yes'); ?>
				</button>
			</div>
		</div>
	</div>
</div>

<div id="packing_list_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document" style="width: 70%">
		<div class="modal-content col-sm-12 col-xs-12 col-md-12">
			<div class="modal-header">
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-info" data-dismiss="modal">
                            <i class="fa fa-arrow-left"></i>
                            <?php echo $this->lang->line('back'); ?>
                        </button>
                        <button class="btn btn-default" id="dvt_kvt_select" disabled>
                            <?php echo $this->lang->line('select'); ?>
                        </button>
                    </li>
                </ul>
                <h2>
                    <?php echo $this->lang->line('packing_order_list'); ?>
                </h2>
			</div>

			<div class="modal-body col-sm-12 col-xs-12 col-md-12">
                <div class="col-sm-12">
                   <!-- <div class="col-sm-3 col-sm-offset-3 no-padding-left">
                        <p style="margin:0px; padding-top: 8px">
                            <input type="radio" class="flat" checked name="kubun" id="kubunS" value="1"/> Shimada Japan
                            &nbsp;&nbsp;
                        </p>
                    </div>
                    <div class="col-sm-3 no-padding-left">
                        <p style="margin:0px; padding-top: 8px">
                            &nbsp;&nbsp;
                            <input type="radio" class="flat" name="kubun" id="kubunO" value="2"/> Other
                        </p>
                    </div> -->
                </div>

                <div class="table-responsive col-sm-12 col-xs-12 col-md-12 dragscroll">
                    <table style="cursor:move;" id="DVTKVTDataTable" data-modal="#packing_list_modal" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('kubun');?></th>
                                <th><?php echo $this->lang->line('delivery_no');?></th>
                                <th><?php echo $this->lang->line('times');?></th>
                                <th>KVT_NO</th>
                                <th><?php echo $this->lang->line('consigned_to');?></th>
                                <th><?php echo $this->lang->line('order_date');?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('request_packing_date');?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('measurem_date');?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('out_vsip_date');?></th>
                                <th><?php echo $this->lang->line('action');?></th>
                                <!-- <th>Hidden</th> -->
                            </tr>
                        </thead>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>

<div id="item_list_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                </ul>
                <h2>
                    <?php echo $this->lang->line('packing_order_list'); ?>
                </h2>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
                    <table id="itemDataTable" data-modal="" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th align="left"><?php echo $this->lang->line('item_code');?></th>
                                <th align="left"><?php echo $this->lang->line('item_name');?></th>
                                <th><?php echo $this->lang->line('size');?></th>
                                <th><?php echo $this->lang->line('color');?></th>
                                <th><?php echo $this->lang->line('quantity');?></th>
                                <th><?php echo $this->lang->line('action');?></th>
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

<div id="provisional_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header" >
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-info" data-dismiss="modal">
                            <i class="fa fa-arrow-left"></i>
                            <?php echo $this->lang->line('back'); ?>
                        </button>
                    </li>
                </ul>
                <h2>
                    Provisional Input
                </h2>
			</div>
            <style>
                .myCustomTable td{
                    vertical-align: middle !important;
                    padding-top: 1px !important;
                    padding-bottom: 1px !important;
                }
            </style>
			<div class="modal-body">
				<div class="table-responsive">
                    <table id="provisional" data-modal="" class="table myCustomTable table-striped table-bordered display nowrap" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th  align="left">PO</th>
                                <th  align="left"><?php echo $this->lang->line('item_code');?></th>
                                <th  align="left"><?php echo $this->lang->line('item_name');?></th>
                                <th><?php echo $this->lang->line('size');?></th>
                                <th><?php echo $this->lang->line('color');?></th>
                                <th><?php echo $this->lang->line('quantity');?></th>
                                <th style="display:none"></th>
                                <th>
                                    <button data-dismiss="modal" class="btn btn-default btn-xs provision-select" disabled style="margin-bottom: 0px;margin-right: 0px;" title="<?php echo $this->lang->line('select'); ?>">
                                        <span ><?php echo $this->lang->line('select'); ?></span>
                                    </button>
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
<div id="inv_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                </ul>
                <h2>
                    <?php echo $this->lang->line('invoce_list'); ?>
                </h2>
			</div>
			<div class="modal-body">
                <input type="hidden" id="inv_no"></input>
				<div class="table-responsive">
                    <table id="INVDataTable" data-modal="" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th align="left"><?php echo $this->lang->line('item_code');?></th>
                                <th align="left"><?php echo $this->lang->line('item_name');?></th>
                                <th><?php echo $this->lang->line('size');?></th>
                                <th><?php echo $this->lang->line('color');?></th>
                                <th><?php echo $this->lang->line('warehouse'); ?></th>
                                <th><?php echo $this->lang->line('sales_man'); ?></th>
                                <th><?php echo $this->lang->line('invoice_no');?></th>
                                <th><?php echo $this->lang->line('status'); ?></th>
                                <th><?php echo $this->lang->line('arrival_date');?></th>
                                <th><?php echo $this->lang->line('store_quantity');?> OK</th>
                                <th><?php echo $this->lang->line('out_quantity');?></th>
                                <th>
                                    <button class="btn btn-default btn-xs inv-select" style="margin-bottom: 0px;margin-right: 0px;" title="<?php echo $this->lang->line('select'); ?>" disabled="disabled">
                                        <span ><?php echo $this->lang->line('select'); ?></span>
                                    </button>
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

<script>
var globalAllocateList = []; 
var globalUnAllocateList = []; 
var selectedInventoryList = [];
var DVTKVTDataTable;
var itemDataTable;
var provisionTable;
var INVTable;
var provisionSelectedData = [];
var historyPacking = [];
var dataTableArray = [];
var packingDetailsList = <?php echo json_encode($packing_details_list); ?>;
var packageTypes = <?php echo json_encode($package_type_list) ?>;
var provisionalsData = <?php echo json_encode($provisionals) ?>;
var INVData = <?php echo json_encode($INV) ?>;
var globalVar = {selectedKubun: "1", checkedCount: 0, kubun: "1"};
var dataDeliveryTo = (<?php echo json_encode($delivery_to); ?> || []);
var dataFilterDeliveryTo = [];
var checkFlg = false;
dataDeliveryTo.forEach(function(element) {
    dataFilterDeliveryTo[dataFilterDeliveryTo.length] = {
        value: element['branch_name'],
        data: element
    };
});

var typesList = (<?php echo json_encode($types_list); ?> || []);
var dataFilterTypesList = [];
typesList.forEach(function(element) {
    dataFilterTypesList[dataFilterTypesList.length] = {
        value: element['types_name'],
        data: element
    };
});
function unique(value, index, self){
    return self.indexOf(value) === index;
}
const defaultRow = {package_type: "", number_from: "", 
                    number_to:"", grosswt: "" , 
                    measure: "", lot_no: "", 
                    note: "" , action:"", details_no: "",package_type_1: "", edit_mode: true, add_mode: true};

window.onload = function() {
    $(document).on('click', '.btnDelete', function(){
        var dvt_kvt_no = $(this).attr('dvt_kvt_no');
        $('#packing_details_delete_modal #dvt_kvt_no').text(dvt_kvt_no);
        $('#packing_details_delete_modal').modal('show');
    });
    $("#btnCollapse").click(function(){
        if($("#collapse").hasClass('in')){
            $("#collapse").collapse('hide');
            $("#btnCollapse").removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
        } else{
            $("#collapse").collapse('show');
            $("#btnCollapse").removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
        }
    });
    $("#delry_to").autocomplete({
        lookup: dataFilterDeliveryTo,
        minChars: 0,
        onSelect: function(data){
            $(this).change();
            $('#delry_to_add').val("");
            // var delivery_address = '';
            // var branch = data.data;
            // delivery_address = (branch.branch_address?branch.branch_address:"") + '\nTEL: '
            //     + [ branch.branch_phone, branch.branch_tel].filter(function(n){ return n != undefined && n != "" }).join(' - ') + '\nFAX: '
            //     + (branch.branch_fax? branch.branch_fax : "");
            $('#delry_to_add').val(data.data.branch_address);
        }
    });
    $("#types").autocomplete({
        lookup: dataFilterTypesList,
        minChars: 0,
        onSelect: function(data){
            $(this).change();
        }
    });
    // hide provisional
    $("#addPOBox").hide();
    //validation engineer
    $("#packingForm").validationEngine();
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex, row, counter) {
            if(settings.sTableId == "INVDataTable"){
                if(globalVar.targetTable && globalVar.targetRow){
                    var rowData = globalVar.targetTable.row(globalVar.targetRow).data();
                    return rowData.item_code.trim() == row.item_code.trim() && rowData.color.trim() == row.color.trim() && rowData.size.trim() == row.size.trim();
                }
            }
            if(settings.sTableId == "DVTKVTDataTable"){
                return (globalVar.kubun && row.kubun && row.kubun == globalVar.kubun) && (row.kvt_list.length > 0);
            }
            return true;
        }
    );
    // Fix Header datatable for datatable in Modal
    $('#packing_list_modal').on('shown.bs.modal', function () {
        DVTKVTDataTable.ajax.reload();
        DVTKVTDataTable.draw();
        if(INVTable){
            INVTable.ajax.reload();
        }
    });
    $('#item_list_modal').on('shown.bs.modal', function () {
        itemDataTable.ajax.reload();
        itemDataTable.draw();
    });

    // Store datatable in dataTableArray
    packingDetailsList.forEach(function(packingDetails){
        var tmpTable = dataTableInit('packing_list_' + packingDetails[0]["dvt_no"].trim().hashCode() + '_' + packingDetails[0]["kvt_no"].trim().hashCode(), packingDetails);
        dataTableArray.push({name: packingDetails[0]["dvt_no"].trim().hashCode() + '_' + packingDetails[0]["kvt_no"].trim().hashCode() , table:tmpTable});
    });

    // Init datatable with table name and Data
    function dataTableInit(tableId, data){
        let table = $("#"+tableId).DataTable({
            "data":data,
            "paging": true,
            // "filter": true,
            "order": [
                [ 4, "asc" ],
                [ 6, "asc" ],
                [ 7, "asc" ],
            ],
            "scrollX" :true,
            "dom": 'l<"summary_info col-md-6 col-sm-6 col-xs-6"><"col-md-1 col-sm-1 col-xs-1"f>rtip',
            "drawCallback": function() {
                reload_summary_info();
            },
            "columnDefs": [ {
                "targets": 'nosort',
                "orderable": false
            } ],
            "columns": [
                { "data": "package_type", render: function( data, type, row, meta ){
                    if(row.edit_mode){
                        if(row.duplicate_mode) {
                            var package_type = '';
                            packageTypes.forEach(function(item){
                                if(row.package_type == item.type_code) {
                                    package_type = item.type_name;
                                } 
                            });
                            return package_type;
                        } else {
                            // render combobox
                            var $combobox = document.createElement('select');
                            $combobox.name = "package_type";
                            $combobox.className = "form-control";
                            packageTypes.forEach(function(item){
                                var $option = document.createElement('option');
                                $option.value = item.type_code;
                                $option.innerHTML = item.type_name;
                                if(row.package_type == item.type_code){
                                    $option.setAttribute("selected", true);
                                }
                                $combobox.appendChild($option);
                            });
                            return $combobox.outerHTML;
                        }
                    }else{
                        let type = row.package_type;
                        packageTypes.forEach(function(item){
                            if(row.package_type == item.type_code) {
                                type = item.type_name;
                                return;
                            }
                        });
                        return type;
                    }
                }},
                { "data": "number_from", className: "text-center", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var readonly = row.duplicate_mode ? 'readonly' : '';
                        let val = row.number_from||'';
                        var el = '<input type="text" '+readonly+' class="form-control text-uppercase" name="number_from" value="' 
                                    + val
                                    + '" style="max-width:90px; height:30px; text-align: center;' 
                                    + (row.validate && !row.validate.number_from ? 'border-color: red':'')
                                    +'" />';
                            return el;
                    } else {
                        return row.number_from;
                    }
                }},
                { "data": "number_to", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var readonly = row.duplicate_mode ? 'readonly' : '';
                        let val = row.number_to||'';
                        var el = '<input type="text" class="form-control text-uppercase" name="number_to" value="' + val + '" style="max-width:90px; height:30px; text-align: center;" '+readonly+'/>';
                            return el;
                    } else {
                        return row.number_to;
                    }
                }},
                { "data": "details_no", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('disabled', true)
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'details_no')
                            .attr('value', row.details_no)
                            .css('text-align', 'center')
							.css('max-width', '90px')
							.css('height', '30px');
                        if(row.kubun == '000') {
                            $input.attr('class','hidden');
                        }
                        return $input[0].outerHTML;
                    } else {
                        return row.details_no;
                    }
                }},
                { "data": "item_code", className: "text-left" },
                { "data": "item_name", className: "text-left" },
                { "data": "size"},
                { "data": "color"},
                { "data": "quantity_detail", className: "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var el = '<input type="text" onkeydown="return checkQuantity(event)" class="form-control" name="quantity_detail" value="' 
                        + (row.quantity_detail != null?parseFloat(row.quantity_detail):0) + '" style="max-width:90px; height:30px; text-align: right; '
                        + (row.validate && !row.validate.quantity_detail? 'border-color: red':'')
                        + '" />';
                        return el;
                    } else {
                        return numberWithCommas(row.quantity_detail);
                    }
                }},
                { "data": "multiple", className: "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var el = '<input type="text" onkeydown="return checkQuantity(event)" class="form-control" name="multiple" value="' 
                        + row.multiple
                        + '" style="max-width:90px; height:30px; text-align: right; '
                        + (row.validate && !row.validate.multiple? 'border-color: red':'')
                        + '" />';
                        return el;
                    } else {
                        return numberWithCommas(row.multiple);
                    }
                }},
                { "data": "quantity", className: "text-right quantity", render: function( data, type, row, meta ){
                        return numberWithCommas(parseFloat(row.multiple) * parseFloat(row.quantity_detail));
                }},
                { "data": "netwt", className: "text-right",  render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var readonly = row.duplicate_mode ? 'readonly' : '';
                        var el = '<input type="text" '+readonly+' onkeydown="return checkQuantity(event)" class="form-control" name="netwt" value="' 
                        + (row.netwt != null ? parseFloat(row.netwt) : 0) + '" style="max-width:90px; height:30px; text-align: right; '
                        + (row.validate && !row.validate.netwt? 'border-color: red':'')
                        + '" />';
                        return el;
                    } else {
                        if(row.netwt){
                            var netwt = parseFloat(row.netwt);
                            return numberWithCommas(netwt.toFixed(2));
                        }
                        return row.netwt;
                    }
                }},
                { "data": "grosswt", className: "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var readonly = row.duplicate_mode ? 'readonly' : '';
                        var el = '<input type="text" '+readonly+' onkeydown="return checkQuantity(event)" class="form-control" name="grosswt" value="' 
                            + (row.grosswt != null ? parseFloat(row.grosswt) : 0) + '" style="max-width:90px; height:30px; text-align: right; '
                            + (row.validate && !row.validate.grosswt? 'border-color: red':'')
                            + '" />';
                        return el;
                    } else {
                        return numberWithCommas(row.grosswt);
                    }
                }},
                { "data": "measure", className: "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var readonly = row.duplicate_mode ? 'readonly' : '';
                        var el = '<input type="text" '+readonly+' onkeydown="return checkQuantity(event)" class="form-control" name="measure" value="' 
                        + (row.measure != null ? parseFloat(row.measure) : 0) + '" style="max-width:90px; height:30px; text-align: right; '
                        + (row.validate && !row.validate.measure? 'border-color: red':'')
                        + '" />';
                        return el;
                    } else {
                        return numberWithCommas(row.measure);
                    }
                }},
                { "data": "lot_no", className: "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var readonly = row.duplicate_mode ? true : false;
                        var $input = $('<input>')
							.attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'lot_no')
                            .attr('value', row.lot_no)
                            .attr('readonly', readonly)
                            .css('text-align', 'right')
							.css('max-width', '90px')
							.css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.lot_no;
                    }
                }},
                { "data": "inv_no", className: "text-left inv_no", render: function( data, type, row, meta ){
                    if(row.edit_mode){
                        // if(row.duplicate_mode){
                        //     return '';
                        // }
                        return  '<a data-event="invo-item" data-toggle="modal" data-target="#inv_modal" title="Add">'
                                    + '<span class="glyphicon glyphicon-plus"></span>'
                                    + '</a> '+ (data||"");
                    }else{
                        return  data;
                    }
                    
                }},
                { "data": "package_type_1", className: "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var readonly = row.duplicate_mode ? true : false;
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'package_type_1')
                            .attr('value', row.package_type_1)
                            .attr('readonly', readonly)
							.css('max-width', '120px')
							.css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.package_type_1;
                    }
                }},
                { "data": "note", className: "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var readonly = row.duplicate_mode ? true : false;
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'note')
                            .attr('value', row.note)
                            .attr('readonly', readonly)
							.css('max-width', '90px')
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
                            html += '<a data-event="edit-item" class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>">'
                                + '<span class="fa fa-edit"></span>'
                                + '</a>';
                            html += '<a data-event="delete-item" style="margin-left: 3px;" class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>">'
                                + '<span class="fa fa-trash"></span>'
                                + '</a>';
                        }
                        html += '<a data-event="duplicate_item" style="margin-left: 3px;" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('duplicate'); ?>">'
                                + '<span class="fa fa-plus"></span>'
                                + '</a>';
                        return html;
                }}
            ]
        });
        // Click on edit button on datatable
        $("#"+tableId).on("click", '[data-event="edit-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = table.row($row).data();
            if(rowData.add_mode == true) {
                rowData.add_mode = false;
            }
            historyPacking[$row.index()] = Object.assign({}, rowData) ;
            rowData.edit_mode = true;
            table.row($row).data(rowData).invalidate();
            table.draw( false );
        });
        // Click on close button on datatable
        $("#"+tableId).on('click', '[data-event="close-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = table.row($row).data();
            if(rowData.add_mode){
                table.row($row).remove();
            }else{
                rowData = historyPacking[$row.index()];
                rowData.edit_mode = false;
                table.row($row).data(rowData).invalidate();
            }
            table.draw();
        });

        $("#"+tableId).on('input','[name="quantity_detail"], [name="multiple"]',function(){
            var $row = $(this).parents('tr');
            var rowData = table.row($row).data();
            var quantity_detail = parseFloat($row.find('input[name=quantity_detail]').val());
            var multiple = parseFloat($row.find('input[name=multiple]').val());
            $row.find('.quantity').text(numberWithCommas(quantity_detail*multiple));
            if(!rowData.duplicate_mode){
                $row.find('input[name=netwt]')[0].value = numberWithCommas(quantity_detail*multiple*rowData.origin_netwt);
            }
        });
        $("#"+tableId).on('input','[name="number_from"], [name="number_to"]',function(){
            var $row = $(this).parents('tr');
            var rowData = table.row($row).data();
            var number_from = $row.find('input[name=number_from]').val();
            var number_to = $row.find('input[name=number_to]').val()||number_from;
            if(/\d+$/.test(number_from)){
                number_from = /\d+$/.exec(number_from)[0];
            }
            if(/\d+$/.test(number_to)){
                number_to = /\d+$/.exec(number_to)[0];
            }
            var no = number_to - number_from + 1;
            if(!no || no <= 0){
                snackbarShow('Value invalid!!!!!!!!');
                return;
            }
            $row.find('input[name=details_no]')[0].value = no;
        });
        $("#"+tableId).on('input','[name="netwt"], [name="grosswt"]',function(){
            var $row = $(this).parents('tr');
            let netwt = $row.find('input[name=netwt]').val();
            let grosswt = $row.find('input[name=grosswt]').val();
            if(netwt > grosswt){
                snackbarShow('Value invalid!!!!!!!!');
                return;
            }
            $row.find('input[name=details_no]')[0].value = no;
        });
        $("#"+tableId).on('click', '[data-event="duplicate_item"]', function(){
            let row = $(this).parents('tr');
            let rowData = table.row(row).data();
            var input = row.find(':input');

            if(rowData.edit_mode) {
                rowData.validate = {};
                let validate = true;
                for(let i = 0; i< input.length; i++){
                    rowData[input[i].name] = input[i].value;
                    // validate row data
                    if(input[i].value == '' && (input[i].name == 'quantity_detail' || input[i].name == 'multiple' || input[i].name == 'number_from')){
                        rowData.validate[input[i].name] = false;
                        validate = false;
                        checkFlg = true;
                    }else{
                        rowData.validate[input[i].name] = true;
                    }
                }
                if(rowData['number_to'] != null && rowData['number_from'] != null && (parseInt(rowData['number_from']) >= parseInt(rowData['number_to'])) || !rowData['number_from'] && rowData['number_to'] ){
                    validate = false;
                    checkFlg = true;
                    rowData.validate["number_to"] = false;
                    snackbarShow('<?php echo $this->lang->line('PLS0020_E005'); ?>');
                }
                if(!validate) {
                    table.row(row).data(rowData).invalidate();
                    table.draw(false);
                    return;
                }
            }
        
            let rowIndex = table.row(row).index();
            let table_data = table.rows().data().toArray();
            let package_type = row.find("select[name=package_type]").val();
            let number_from = row.find(":input[name=number_from]").val();
            let number_to = row.find(":input[name=number_to]").val();
            let quantity_detail = row.find(":input[name=quantity_detail]").val();
            let multiple = row.find(":input[name=multiple]").val();
            let netwt = row.find(":input[name=netwt]").val();
            let grosswt = row.find(":input[name=grosswt]").val();
            let measure = row.find(":input[name=measure]").val();
            let lot_no = row.find(":input[name=lot_no]").val();
            let package_type_1 = row.find(":input[name=package_type_1]").val();
            let note = row.find(":input[name=note]").val();

            let new_data = table_data.reduce(function(res, current, index, array){
                let tmpItem = [];
                if(index == rowIndex){
                    if(package_type) current.package_type = package_type;
                    if(number_from) current.number_from = number_from;
                    if(number_to) current.number_to = number_to;
                    if(quantity_detail) current.quantity_detail = quantity_detail;
                    if(multiple) current.multiple = multiple;
                    if(netwt) current.netwt = netwt;
                    if(grosswt) current.grosswt = grosswt;
                    if(measure) current.measure = measure;
                    if(lot_no) current.lot_no = lot_no;
                    if(package_type_1) current.package_type_1 = package_type_1;
                    if(note) current.note = note;
                    let tmpCurrent = Object.assign({},current);
                    tmpCurrent.duplicate_mode = true;
                    tmpCurrent.add_mode = true;
                    tmpCurrent.netwt = 0;
                    tmpCurrent.grosswt = 0;
                    tmpCurrent.measure = 0;
                    tmpCurrent.lot_no = 0;
                    tmpCurrent.inv_no = null;
                    tmpCurrent.package_type_1 = null;
                    tmpCurrent.note = null;
                    delete tmpCurrent.pack_no;
                    tmpCurrent.packing_details = parseFloat(tmpCurrent.packing_details) + 1;
                    tmpItem = [current, tmpCurrent];
                }else{
                    tmpItem = [current];
                }
                return res.concat(tmpItem);
            },[]);
            table.clear();
            table.rows.add(new_data);
            table.draw(false);
        });
        // Click on save button on datatable
        $("#"+tableId).on('click', '[data-event="save-item"]', function(){
            var $row = $(this).parents('tr');
            var input = $row.find(':input');
            var rowData = table.row($row).data();
            rowData.validate = {};
            let validate = true;
            for(let i = 0; i< input.length; i++){
                rowData[input[i].name] = input[i].value;
                // validate row data
                if(input[i].value == '' && (input[i].name == 'quantity_detail' || input[i].name == 'multiple' || input[i].name == 'number_from')){
                    rowData.validate[input[i].name] = false;
                    validate = false;
                    checkFlg = true;
                }else{
                    rowData.validate[input[i].name] = true;
                }
            }
            if(rowData['number_to'] != null && rowData['number_from'] != null && (parseInt(rowData['number_from']) >= parseInt(rowData['number_to'])) || !rowData['number_from'] && rowData['number_to'] ){
                validate = false;
                checkFlg = true;
                rowData.validate["number_to"] = false;
                snackbarShow('<?php echo $this->lang->line('PLS0020_E005'); ?>');
            }
            rowData.edit_mode = !validate;
            rowData.add_mode = !validate;
            table.row($row).data(rowData).invalidate();
            table.draw(false);
        });
        // Click on delete button on datatable
        $("#"+tableId).on('click', '[data-event="delete-item"]', function(){
            globalVar.targetRow = $(this).parents('tr');
            $("#item_delete_modal").modal();
            globalVar.targetTable = table;
        });

        $("#"+tableId).on('click', '[data-event="invo-item"]', function(){
            globalVar.targetRow = $(this).parents('tr');
            globalVar.targetTable = table;
        });
        $("#btnSaveModal").click(function(){
            $("#"+tableId).find("a[data-event='save-item']" ).trigger('click');
            if(!checkFlg){
                $("#save_modal").modal('show');
            } else {
                checkFlg = false;
            }
        });
        function reload_summary_info(){
            let summary_info = [];
            let str_summary_info = "Quantity: ";
            let amount = 0;
            let totalQuantity = 0;
            let itemTypeList = [];

            var data = $("#"+tableId).DataTable()
                .rows()
                .data();
            if(data.length){
                data.each(function(ele){
                    let $quantity = ele.quantity_detail ? parseFloat(ele.quantity_detail.trim()) : 0;
                    let $multiple = ele.multiple ? parseFloat(ele.multiple.trim()) : 1;
                    let $unit = ele.unit ? ele.unit.trim() : " ";
                    let $types= ele.item_types ? ele.item_types : [];
                    amount += parseFloat(ele.amount);
                    totalQuantity += $quantity * $multiple;
                    if(!summary_info.hasOwnProperty($unit)){
                        summary_info[$unit] = $quantity * $multiple;
                    }else{
                        summary_info[$unit] += $quantity * $multiple;
                    }
                    if($types.length > 0){
                        itemTypeList = itemTypeList.concat($types);
                    }
                });
                for(let key in summary_info){
                    str_summary_info += summary_info[key] + " " + key + " ; ";
                }
                str_summary_info = str_summary_info.replace(/( ; )$/,"");
                $(".summary_info").css({
                    "height": "30px",
                    "line-height": "30px"
                });
                $("#"+ tableId + "_wrapper .summary_info").html("<b style='color: red'>"+str_summary_info+"</b>");
            } else {
                $("#"+ tableId + "_wrapper .summary_info").html("<b style='color: red'>Quantity: 0</b>");
            }
            $("#totalQuantity").val(totalQuantity);
            itemTypeList = itemTypeList.filter(unique);
            // console.log(itemTypeList);
            $("#types").val(itemTypeList.join("+"));
        }
    
        return table;
    }
    INVTable = $("#INVDataTable").DataTable({
        // "data":INVData,
        "paging": true,
        "ordering": false,
        "scrollX": true,
        "bLengthChange": false,
        dom: '<"toolbar">lrtip',
        "ajax": {
            url: "<?php echo base_url("packing_list/getStoreItemsList") ?>",
            "type": 'post',
            "data": function ( data ) {
                if(globalVar.targetTable){
                    data.param = globalVar.targetTable.row(globalVar.targetRow).data();
                }else{
                    data.param = [];
                }
            }
        },
        "drawCallback": function() {
            let packingQuantity =  0;
            if(globalVar.targetRow)
            {
                packingQuantity = globalVar.targetRow.find('.quantity').text();
            }
            $("div.toolbar").css('text-align','left');
            $("div.toolbar").addClass('col-xs-6').html('<span style="color: #5bc0de">Packing Quantity : <span id="packing_quantity">'+packingQuantity+'</span></span>');
        },
        "rowCallback": function( row, data ) {
            if (data.selected) {
                $(row).addClass('selected');
            }
        },
        "columnDefs": [
            { className: "text-left", "targets": [ 0,1,4 ] },
            { className: "text-right", "targets": [ 5] }
        ],
        "columns": [
                    {"data": "item_code"},
                    {"data": "item_name"},
                    {"data": "size"},
                    {"data": "color"},
                    {"data": "warehouse"},
                    {"data": "salesman"},
                    {"data": "inv_no"},
                    {"data": "status"},
                    {"data": "arrival_date"},
                    { "data": "store_quantity", className: "text-right", render: function(data) {
						return numberWithCommas(data);
					}},
                    { "data": "out_quantity",className: "text-right", render: function(data, type, row, meta){
                        var currentRow = this;
                        if(globalVar.targetRow)
                        {
                            let _quantity = parseFloat($("#packing_quantity").text().replace(",",""));
                            let _quantity_inv = 0;
                            let _inv_no = $("#inv_modal #inv_no").val();
                            //BU-11/11/2017-123,BU-11/11/2017-123
                            let arr_inv_quantity = _inv_no.split(",");
                            for(let i=0; i < arr_inv_quantity.length; i++){
                                let currentValue = arr_inv_quantity[i];
                                if(currentValue){
                                    let arrInv_no = currentValue.split("-");
                                    let quantity = arrInv_no.pop();
                                    let inv_str = arrInv_no.join("-");
                                    if(inv_str === row.inv_no.trim()){
                                        row.selected = true;
                                        $(row).addClass('selected');
                                        data = quantity;
                                        if(globalAllocateList.indexOf(meta.row)<0){
                                            globalAllocateList.push(meta.row);
                                        }
                                        if(globalUnAllocateList.indexOf(meta.row)<0){
                                            globalUnAllocateList.push(meta.row);
                                        }
                                        break;
                                    }
                                }
                            }
                            
                            data = Math.min(data, _quantity);
                        }
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'out_quantity')
                            .attr('maxlength', '20')
                            .attr('value', parseFloat(data))
                            .attr('onkeydown', 'return checkQuantity(event)');
                        return $input[0].outerHTML;
                    }},
                    {"data": "action", render: function( data, type, row, meta ){
                        var el = '<label class="check-container" style="font-size: 15px;">'
                                        + '<input class="inv-check" type="checkbox" '+ (row.status_code == '016' && !row.selected ? 'disabled="disabled"' : '')+ (row.selected ? 'checked' : '')+'>'
                                        +'<span class="check-checkmark" style="margin-left: 5px;height: 20px;width: 20px;"></span>'
                                        + '</label>';
                        return el;
                    }
                    }]
    });

    provisionTable = $("#provisional").DataTable({
        "data":provisionalsData,
        "paging": true,
        "ordering": false,
        "columnDefs": [
            { className: "text-left", "targets": [ 0,1,2 ] },
            { className: "text-right", "targets": [ 5] }
        ],
        "columns": [{"data": "po"},
                    {"data": "item_code"},
                    {"data": "item_name"},
                    {"data": "size"},
                    {"data": "color"},
                    { "data": "quantity", render: function(data) {
                            return numberWithCommas(data);
                        }},
                    {"data": "action", render: function( data, type, row, meta ){
                        var el = '<label class="check-container">'
                                            + '<input class="provision-check" type="checkbox" >'
                                            +'<span class="check-checkmark" style="margin-left: 5px;"></span>'
                                        + '</label>';
                        return el;
                    }
                    }]
    });
    DVTKVTDataTable = $("#DVTKVTDataTable").DataTable({
        "paging": true,
        "filter": true,
        "ordering": false,
        "dom": 'l<\'select-kubun\'>frtip',
        "drawCallback": function() {
            // iCheck
            $("input.flat-icheck").iCheck({
                radioClass: "iradio_flat-green"
            });
            $('input[name=kubun]').on('ifChecked', function (event) {
                globalVar.kubun = $(this).val();
                DVTKVTDataTable.draw(); 
            });
        },
        "columnDefs": [
            { className: "text-left", "targets": [ 1,3,4] }
        ],
        "columns": [
                {"data": "kubun", "visible": false},
                { "data": "dvt_no", render: function( data, type, row, meta ){
                    var el =  '<span style="color:#428bca" class="collapse-order glyphicon glyphicon-triangle-right"></span>'+  row.dvt_no;
                        return el;  
                }},
                { "data": "times", render: function( data, type, row, meta ){
                    if(row.count > 1 || row.times > 1){
                        return row.times;
                    }
                    return '';
                }},
                { "data": "kvt_no"},
                { "data": "factory"},
                { "data": "order_date"},
                { "data": "packing_date"},
                { "data": "measurement_date"},
                { "data": "factory_delivery_date"},
                { "data": "action", render: function( data, type, row, meta ){
                    // change to default icon
                    var el = '<div><i class="fa fa-square-o" style="font-size: 1.5em; margin-top: 3px;"></i></div>'
                    return el;
                }},
                {"data":"kvt_list", "searchable":false, "visible": false}
            ],
        "ajax":{
                url : "<?php echo base_url("packing_list/loadDeliveryKDV") ?>",
                type : 'get'
        }
    });
    $(".select-kubun").css({display: "inline","padding-left": "10px"});
    $(".select-kubun").html('<label><input type="radio" class="flat-icheck" checked name="kubun" id="kubunS" value="1"/> Shimada Japan</label>' + 
                            '<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>' + 
                            '<label><input type="radio" class="flat-icheck" name="kubun" id="kubunO" value="2"/> Other</label>');
    // $('input[name=kubun]').on('ifChecked', function (event) {
    //     globalVar.kubun = $(this).val();
    //     DVTKVTDataTable.draw();
    //  });

    itemDataTable = $("#itemDataTable").DataTable({
        "paging": true,
        "filter": false,
        "ordering": false,
        "scrollX" :true,
        "columnDefs": [
            { className: "text-left", "targets": [ 0,1] },
            { className: "text-right", "targets": [ 4] }
        ],
        "columns": [
                { "data": "item_code"},
                { "data": "item_name"},
                { "data": "size"},
                { "data": "color"},
                { "data": "quantity", render: function(data) {
					return numberWithCommas(data);
				}},
                { "data": "action", render: function( data, type, row, meta ){
                    var el = '<button data-dismiss="modal" class="btn btn-info btn-xs kdv-select" title="<?php echo $this->lang->line('select'); ?>">'
                                + '<span ><?php echo $this->lang->line('select'); ?></span>'
                            + '</button>'
                    return el;
                }}
            ],
        "ajax":{
                url : "<?php echo base_url("packing_list/loadItemByDVTandKVT") ?>",
                type : 'post',
                data:function(data){
                    data.dvt_no = globalVar&&globalVar.dvt ? globalVar.dvt: '0';
                    data.kvt_no = globalVar&&globalVar.kvt ? globalVar.kvt: '0';
                }
        }
    });
    // click sellect button on Item Datatable
    $("#itemDataTable").on('click', 'button.kdv-select', function(){
        var tr = $(this).closest("tr");
        var row = itemDataTable.row(tr);
        var rowData = Object.assign({}, defaultRow);
        rowData = Object.assign(rowData, row.data());
        if(!rowData.origin_netwt){
            rowData.origin_netwt = rowData.netwt ? rowData.netwt : 0;
        }
        rowData.netwt =  (rowData.origin_netwt && rowData.quantity_detail && rowData.multiple? rowData.origin_netwt*rowData.quantity_detail*rowData.multiple : 0);
        rowData.inv_no = "";
        rowData.quantity_detail = rowData.quantity;
        rowData.multiple = 1;
        // add an item to target Table
        globalVar.targetTable.row.add(rowData).draw(false);
    });

    // show child in DVTKVT datatable
    $('#DVTKVTDataTable').on('click', 'span.collapse-order', function(){
        var tr = $(this).closest("tr");
        var row = DVTKVTDataTable.row(tr);
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
    $('#DVTKVTDataTable').on('click', '[data-event=child-checked]', function(){
        // add checked attribute to master Object
        // get child's data
        var childData = $(this).closest("td").data("item");
        // get parent's data
        var parentRow = $(this).closest("table").closest("tr").prev("tr");
        var parentData = DVTKVTDataTable.row(parentRow).data();
        var fixData = parentData['kvt_list'];
        var kvt_length = fixData.length;
        fixData = fixData.filter(function(el) {
            return (el.color == childData.color && el.item_code == childData.item_code 
            && el.item_name == childData.item_name && el.netwt == childData.netwt 
            && el.quantity_detail == childData.quantity_detail && el.size == childData.size);
        });

        if(!fixData[0].checked) {
            fixData[0].checked = true;
            selectActionControl(++globalVar.checkedCount);
        } else {
            delete fixData[0].checked;
            selectActionControl(--globalVar.checkedCount);
        }

        fixData[0].marked = true;
        // change to any-check icon
        if($(this).closest("tbody").find("input:checked").length > 0){
            // $(this).closest("table").closest('tr').prev('tr').find('button').prop('disabled',false).removeClass("btn-default").addClass("btn-info");
            // $('#dvt_kvt_select').prop('disabled',false).removeClass("btn-default").addClass("btn-info");
            if($(this).closest("tbody").find("input:checked").length < kvt_length) {
                delete parentData.checkedAll;
                parentRow.find("td:eq(8)>div>i").removeAttr("class").attr('class', 'fa fa-check-square-o');
                // uncheck-all
                $(this).closest("tbody").prev().find('input[type="checkbox"]').prop('checked', false);
            } else {
                // check-all
                $(this).closest("tbody").prev().find('input[type="checkbox"]').prop('checked', true);
                parentData.checkedAll = true;
                parentRow.find("td:eq(8)>div>i").removeAttr("class").attr('class', 'fa fa-check-square');
            }
        }else{
            // $(this).closest("table").closest('tr').prev('tr').find('button').prop('disabled',true).removeClass("btn-info").addClass("btn-default");
            // $('#dvt_kvt_select').prop('disabled',true).removeClass("btn-info").addClass("btn-default");
            $(this).closest("tbody").prev().find('input[type="checkbox"]').prop('checked', false);
            parentRow.find("td:eq(8)>div>i").removeAttr("class").attr('class', 'fa fa-square-o');
        }
    });

    $("#provisional").on('click', 'input.provision-check', function(){
        var tr = $(this).closest("tr");
        var quantityOut = $(tr).find('input[name="out_quantity"]').val();
        var requiredQuantity = parseFloat($("#packing_quantity").text().replace(",",""));
        if($(this).closest("tbody").find("input:checked").length > 0 && $(this).closest("tbody").find("input:checked").length < 2 && quantityOut >= requiredQuantity){
            $("#provisional").find('button').prop('disabled',false).removeClass("btn-default").addClass("btn-info");
        }else{
            $("#provisional").find('button').prop('disabled',true).removeClass("btn-info").addClass("btn-default");
        }
        if(!$(this)[0].checked){
            tr.removeClass("selected")
            return;
        }
        tr.addClass( "selected" );
    });

    $("#provisional_modal").on('shown.bs.modal', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    });
    $("#inv_modal").on('shown.bs.modal', function (e) {
        globalAllocateList = [];
        let inv_no =  "";
        if(globalVar.targetRow){
            inv_no = globalVar.targetRow.find('.inv_no').text().trim();
        }
        $("#inv_modal #inv_no").val(inv_no);
        if(INVTable){
            INVTable.ajax.reload();
        }
        INVTable.draw(false);
    });
    $("#inv_modal").on('hidden.bs.modal', function (e) {
        $(".inv-select").prop('disabled',true).removeClass("btn-info").addClass("btn-default");
    });
    $("#provisional").on('click', 'button.provision-select', function(){
        var selectedRows = provisionTable.rows('.selected').data();
        var drawRows = [];
        var tmpTable = getTableByName("xxxxxxxx_xxxxxxxx");
        for(var i =0; i < selectedRows.length; i ++){
            var item = selectedRows[i];
            item.kvt_no = "xxxxxxxx";
            item.dvt_no = "xxxxxxxx";
            var tmpRow = Object.assign({}, defaultRow);
            var tmpRow = Object.assign(tmpRow,item);
            if(!tmpRow.origin_netwt){
                tmpRow.origin_netwt = tmpRow.netwt ? tmpRow.netwt : 0;
            }
            tmpRow.netwt *=tmpRow.quantity;
            if(tmpTable){
                tmpTable.table.row.add(tmpRow).draw(false);
                // unchecked
            }else{
                drawRows.push(tmpRow);
            }
        }
        if(!tmpTable){
            renderKVTDVT({"dvt_no":"xxxxxxxx", "kvt_no":"xxxxxxxx", "kvt_list":drawRows});
        }
        var allData = provisionTable.rows().data().toArray();
        provisionTable.rows().remove().draw();
        provisionTable.rows.add(allData).draw();
    }); 

    $("#INVDataTable").on('click', 'input.inv-check', function(){
        if($(this).closest("tbody").find("input:checked").length > 0 && $(this).closest("tbody").find("input:checked").length < 2){
            $(".inv-select").prop('disabled',false).removeClass("btn-default").addClass("btn-info");
        }else{
            $(".inv-select").prop('disabled',true).removeClass("btn-info").addClass("btn-default");
        }
        var row = $(this).closest('tr');
        var data = $("#INVDataTable").DataTable().row(row).data();
        data.priority = null;
        if(!$(this)[0].checked){
            data.check = false;
            row.removeClass( "selected" );
            // $("#INVDataTable").DataTable().row(row).data(data).invalidate();
            globalAllocateList.push(row.index());
            globalAllocateList = jQuery.grep(globalAllocateList, function(value) {
                return value != row.index();
            });
            return;
        }else{
            data.check = true;
            row.addClass( "selected" );
            // $("#INVDataTable").DataTable().row(row).data(data).invalidate();
            globalAllocateList.push(row.index());
        }
    });

    $("#inv_modal").on('click', 'button.inv-select', function(){
        // var rowData = globalVar.targetTable.row(globalVar.targetRow).data();
        // var selectedRows = INVTable.rows('.selected').data().filter(function(row,index){
        //     return rowData.item_code == row.item_code && rowData.color == row.color && rowData.size == row.size;
        // });
        // rowData.inv_no = "";
        // for(var i =0; i < selectedRows.length; i ++){
        //          rowData.inv_no += selectedRows[i].inv_no ? (rowData.inv_no ? "," + selectedRows[i].inv_no.trim() : selectedRows[i].inv_no.trim()) : "";
        // }
        if(INVTable.rows('.selected').data().length <= 0){
            snackbarShow('<?php echo $this->lang->line('POD0020_E001')?>');
            return;
        }
        var rowData = globalVar.targetTable.row(globalVar.targetRow).data();
        var packingQuantity = $('#packing_quantity').text().replace(",","");
        packingQuantity = packingQuantity != '' ? parseFloat(packingQuantity) : 0;
        var totalINVQuantity = 0;
        var exceedFlg = false;
        var invNo = '';
        var data = {};
        var itemTypes = [];
        data["inv_no_quantity"] = $("#inv_no").val();
        INVTable.rows('.selected').every( function ( rowIdx ) {
            var $row = INVTable.row(rowIdx).node();
            var invRowData = INVTable.row(rowIdx).data();
            var invQuantity = $($row).find('input[name="out_quantity"]').val();
            if(invQuantity){
                itemTypes.push(invRowData.item_type_nm);
                totalINVQuantity += parseFloat(invQuantity);
                if(parseFloat(invQuantity) > parseFloat(invRowData.out_quantity)){
                    exceedFlg = true;
                }
                invNo += invNo != '' ? ("," + invRowData.inv_no.trim() + '-' + invQuantity) : (invRowData.inv_no.trim() + '-' + invQuantity);
            }
        });
        if(exceedFlg){
            snackbarShow('<?php echo $this->lang->line('PLS0020_E003');?>');
            return;
        }
        if(totalINVQuantity > packingQuantity){
            snackbarShow('<?php echo $this->lang->line('PLS0020_E001');?>');
            return;
        }
        if(totalINVQuantity < packingQuantity){
            snackbarShow('<?php echo $this->lang->line('PLS0020_E003');?>');
            return;
        }
        rowData.inv_no = invNo;
        rowData.item_types = itemTypes;
        //Allocate
        selectedInventoryList = [];
        var unallocateList = globalUnAllocateList.slice(0);
        globalAllocateList.forEach(function(ele){
            var unallocateIndex = unallocateList.indexOf(ele);
            var row = $("#INVDataTable").DataTable().row(ele); 
            var dt = row.data();
            if(unallocateIndex >= 0) {
                unallocateList.splice(unallocateIndex, 1);
            }
            if (dt['status_code'] == '016') {
                return;
            }
            dt["hikiate_quantity"] = $(row.node()).find('[name=out_quantity]').val();
            selectedInventoryList.push(dt);
        });
        data['selectedInventoryItem'] = selectedInventoryList;
        data['unAllocateList'] = [];
        if(unallocateList.length > 0) {
            unallocateList.forEach(function(idx){
                var row = $("#INVDataTable").DataTable().row(idx); 
                var dt = row.data();
                data['unAllocateList'].push(dt);
            });
        }
        $.ajax({
            url: '<?php echo base_url('packing_list/do_allocation') ?>',
            type: "POST",
            data: data,
            dataType: 'json',
            async: true,
            success: function(response) {
                $('#inv_modal').modal('hide');
                if (response.success) {
                    snackbarShow('<?php echo $this->lang->line('COMMON_I002');?>');
                    globalAllocateList = [];
                } else {
                    snackbarShow(response.message);
                }
            },
            error: function(error) {
                $('#inv_modal').modal('hide');
            }
        });
        var input = globalVar.targetRow.find(':input');
        for(let i = 0; i< input.length; i++){
            rowData[input[i].name] = input[i].value;
        }
        // rowData["inv_no"] = response["data"];
        globalVar.targetTable.row(globalVar.targetRow).data(rowData).invalidate();
        globalVar.targetTable.draw();
        // redraw INVTable
        // var allData = INVTable.rows().data().toArray();
        // INVTable.rows().remove().draw();
        // INVTable.rows.add(allData).draw();
    }); 
    // dt.column( 'class of column needing update' ).invalidate().render()
    // render new datatable with data
    function renderKVTDVT(data){
        data['dvt_no_hash'] = data['dvt_no'].trim().hashCode();
        data['kvt_no_hash'] = data['kvt_no'].trim().hashCode();
        var el =  '<div class="x_panel" id="' + data['dvt_no_hash'] + '_' + data['kvt_no_hash'] + '">'
                    + '<div class="x_title">'
                        + '<div class="form-group">'
                            + '<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">'
                                + '<label style="padding-top:8px;" class="control-label col-md-4 col-sm-4 col-xs-4 no-padding-left" for="dvt_no">DVT No</label>'
                                + '<div class="col-md-8 col-sm-8 col-xs-8 has-clear has-feedback">'
                                    + '<input type="text" id="dvt_no" name="dvt_no" class="form-control" value=" ' + data['dvt_no'] + '" readonly>'
                                + '</div>'
                            + '</div>'
                            + '<div class="col-md-1 col-sm-3 col-xs-3">'
                            + '</div>'
                            + '<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">'
                                + '<label style="padding-top:8px;" class="control-label col-md-4 col-sm-4 col-xs-4 no-padding-left" for="kvt_no">KVT No</label>'
                                + '<div class="col-md-8 col-sm-8 col-xs-8 has-clear has-feedback">'
                                    + '<input type="text" id="kvt_no" name="kvt_no" class="form-control" value="' + data['kvt_no'] + '" readonly>'
                                + '</div>'
                            + '</div>'
                            + '<div class="col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-right" style="text-align: right;">'
                                + '<button dvt_kvt_no="' + data['dvt_no_hash'] + ' - ' + data['kvt_no_hash'] + '" class="btn btn-danger btnDelete" onclick="globalVar.deleteKVTDVT = \''+ data['dvt_no_hash'] + "_" + data['kvt_no_hash'] +'\'">'
                                    + '<i class="fa fa-trash"></i>'
                                    + ' <?php echo $this->lang->line('delete'); ?>'
                                + '</button>'
                            +'</div>'
                        + '</div>'
                        + '<div class="clearfix"></div>'
                    + '</div>'
                    + '<div class="x_content">'
                        + '<div class="table-responsive">'
                            + '<table id="packing_list_' + data['dvt_no_hash'] + '_' + data['kvt_no_hash'] + '" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0" style="margin-bottom: 10px">'
                                + '<thead>'
                                    + '<tr>'
                                        + '<th><?php echo $this->lang->line('package'); ?></th>'
                                        + '<th>From<span class="required" >*</span></th>'
                                        + '<th>To</th>'
                                        + '<th>No</th>'
                                        + '<th><?php echo $this->lang->line('item_code'); ?></th>'
                                        + '<th><?php echo $this->lang->line('item_name'); ?></th>'
                                        + '<th><?php echo $this->lang->line('size'); ?></th>'
                                        + '<th>COL</th>'
                                        + '<th><?php echo $this->lang->line('quantity_detail'); ?><span class="required" >*</span></th>'
                                        + '<th><?php echo $this->lang->line('multiple'); ?><span class="required" >*</span></th>'
                                        + '<th><?php echo $this->lang->line('quantity'); ?></th>'
                                        + '<th>NETWT</th>'
                                        + '<th>GROSSWT</th>'
                                        + '<th>MEAS(M3)</th>'
                                        + '<th>LotNo</th>'
                                        + '<th>INV No</th>'
                                        + '<th><?php echo $this->lang->line('package_type'); ?></th>'
                                        + '<th><?php echo $this->lang->line('note'); ?></th>'
                                        + '<th class="nosort">'
                                            + '<a class="btn btn-xs btn-success" style="margin:0px" onClick="showItemListMode(\'' + data['dvt_no_hash'] + '\',\'' + data['kvt_no_hash'] + '\')" data-toggle ="modal" data-target="#item_list_modal">'
                                                + '<span class="fa fa-plus item-list"></span>'
                                            + '</a>'
                                        +'</th>'
                                    + '</tr>'
                                + '</thead>'
                                + '<tbody>';
                                el += '</tbody>'
                            + '</table>'
                        + '</div>'
                    + '</div>'
                + '</div>';
            $("#dvt_kvt_box").append(el);
            var tmpTable = dataTableInit('packing_list_' + data['dvt_no_hash'] + '_' + data['kvt_no_hash'],data['kvt_list']);
            dataTableArray.push(
                {
                    name: data['dvt_no_hash'] + '_' + data['kvt_no_hash'] , 
                    table: tmpTable, 
                    dvt : {
                            'dvt_no' : data['dvt_no'].trim(), 
                            'order_date' : data['order_date'], 
                            'times' : data['times'] 
                        }
                });
    }
		
    $("#packing_list_modal").on("change",'[data-event=check-all]',function(){
        var parentRow = $(this).closest("table").closest("tr").prev("tr");
        var parentData = DVTKVTDataTable.row(parentRow).data();
        var fixData = parentData['kvt_list'];
        // change to check-all icon
        $(this).closest('table').find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        if($(this).prop('checked')) {
            $(this).closest('table').closest('tr').prev('tr').find("td:eq(8)>div>i").removeAttr("class").attr('class', 'fa fa-check-square');
            // set checked flag for all kvt in current dvt
            parentData.checkedAll = true;
            fixData.forEach(function(it) {
                it.checked = true;
                selectActionControl(++globalVar.checkedCount);
            });
        } else {
            //uncheck all
            delete parentData.checkedAll;
            fixData.forEach(function(it) {
                delete it.checked;
                selectActionControl(--globalVar.checkedCount);
            });
            $(this).closest('table').closest('tr').prev('tr').find("td:eq(8)>div>i").removeAttr("class").attr('class', 'fa fa-square-o');
        }
        // if($(this).prop('checked')){
        //     $(this).closest("table").closest('tr').prev('tr').find('button').prop('disabled',false).removeClass("btn-default").addClass("btn-info");
        // }else{
        //     $(this).closest("table").closest('tr').prev('tr').find('button').prop('disabled',true).removeClass("btn-info").addClass("btn-default");
        // }

        // if($(this).closest("tbody").find("input:checked").length > 0){
        //     $('#dvt_kvt_select').prop('disabled',false).removeClass("btn-default").addClass("btn-info");
        // }else{
        //     $('#dvt_kvt_select').prop('disabled',true).removeClass("btn-info").addClass("btn-default");
        // }
    });

    // input
    $("#provisionalCheckBox").on("ifChanged",function(event){
        if($(this)[0].checked){
            // $("#addDVTBox").hide();
            // $("#addPOBox").show();
            $('input[name=status_name]').val("Provisional Input");
            $('input[name=status]').val("020");
        }else{
            // $("#addPOBox").hide();
            // $("#addDVTBox").show();
            $('input[name=status_name]').val("Not yet");
            $('input[name=status]').val("019");
        }
        // dataTableArray = [];
        // $('#dvt_kvt_box').empty();
    });

    $('#dvt_kvt_select').click(function() {
        // add item to master page
        var dvtList = DVTKVTDataTable.rows().data().toArray();
        var checkedRowData = [];
        var kvtTemp = [];
        let firstRowFound = false;
        let firstFactory = "";
        let factoryFlg = false;
        let rowData = [];

        dvtList.forEach(function(dvt) {
            dvt['kvt_list'].forEach(function(kvt) {
                if(kvt.checked) {
                    if(!firstRowFound) {
                        rowData = dvt;
                        firstFactory = dvt.factory;
                        firstRowFound = true;
                    }
                    if(firstRowFound && firstFactory != dvt.factory) {
                        factoryFlg = true;
                        return;
                    }
                }
            });
            if(factoryFlg) return;
        });
        if(!factoryFlg) {
            dvtList.forEach(function(dvt) {
                kvtTemp = [];
                dvt['kvt_list'].forEach(function(kvt) {
                    if(kvt.checked) {
                        var data = {"item_code": kvt.item_code ? kvt.item_code.trim() : "",
                                    "item_name": kvt.item_name ? kvt.item_name.trim() : "",
                                    "size": kvt.size ? kvt.size.trim() : "",
                                    "color": kvt.color ? kvt.color.trim() : "",
                                    "quantity": kvt.quantity ? kvt.quantity.trim() : "",
                                    "unit": kvt.unit ? kvt.unit.trim() : "",
                                    "quantity_detail": kvt.quantity ? kvt.quantity.trim() : "",
                                    "multiple": kvt.multiple ? kvt.multiple : "1",
                                    "netwt": (kvt.netwt ? kvt.netwt.trim() : 0) * (kvt.quantity ? kvt.quantity.trim() : 0),
                                    "origin_netwt": kvt.netwt ? kvt.netwt.trim() : "",
                                    "kvt_no": dvt.kvt_no.trim(),
                                    "dvt_no": dvt.dvt_no.trim(),
                                    "inv_no": (kvt.inv_no ? kvt.inv_no.trim(): ""),
                                    "item_types" : kvt.types ? kvt.types : [],
                                    "order_date": dvt.order_date,
                                    "times": dvt.times,
                                    "jp_code": kvt.item_jp_code ? kvt.item_jp_code.trim() : "",
                                    "checked": true};
                        kvtTemp.push(Object.assign(data, defaultRow));
                        // delete kvt.checked;
                    }
                });
                // check Datatable with kvt and dvt is exist?
                var existTable = getTableByName(dvt.dvt_no.trim().hashCode() + '_' + dvt.kvt_no.trim().hashCode());
                if(existTable) {
                    kvtTemp.forEach(function(item) {
                        existTable.table.row.add(item).draw(false);
                    });
                } else {
                    if(kvtTemp.length) renderKVTDVT({'dvt_no': dvt.dvt_no, 'kvt_no': dvt.kvt_no, 'order_date': dvt.order_date, 'times': dvt.times, 'kvt_list': kvtTemp});
                }
            });

            if(!$('#case_mark').val()) 
            {
                $('#case_mark').val(rowData['case_mark']);
            }
            // generate option after select KVT from DVT
            $('#company_name').val(rowData['company_name']);
            $('#delry_to').val(rowData['branch_name']).change();
            $('#delry_to_add').val(rowData['branch_address']);
            DVTKVTDataTable.ajax.reload();
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex, row, counter) {
                    if(settings.sTableId == "DVTKVTDataTable"){
                        return $('#delry_to').val() ? (row.factory && row.factory == $('#delry_to').val()) : true;
                    }
                    return true;
                }
            );
            DVTKVTDataTable.draw();
            $('input[name=kubun]').iCheck('disable');
            $('#packing_list_modal').modal('hide');
        } else {
            snackbarShow('<?php echo $this->lang->line('PLS0020_E002');?>', 3000);
        }
    });
}
//Control DVT-KVT select action
function selectActionControl(checkedCount) {
    if(checkedCount > 0) {
        $('#dvt_kvt_select').prop('disabled',false).removeClass("btn-default").addClass("btn-primary");
    } else {
        $('#dvt_kvt_select').prop('disabled',true).removeClass("btn-primary").addClass("btn-default");
    }
}
// find TableData in dataTableArray
function getTableByName(tableName){
    let tmpTable = dataTableArray.filter(function(el){
        return (el.name == tableName);
    })[0];
    return tmpTable;
}
// Render child row in KVTDVTDataTable
function renderChildRow(data) {
    var el =
        "<table class='table table-striped table-dashed datatable display nowrap' width='100%' cellspacing='0'>" +
        "<thead style='background-color:#d2ecc0'>" +
        "<tr>" +
        "<th>" + lang['item_code'] + "</th>" +
        "<th>" + lang['item_name'] + "</th>" +
        "<th>" + lang['size'] + "</th>" +
        "<th>" + lang['color'] + "</th>" +
        "<th>" + lang['quantity'] + "</th>"+
        '<th><label class="check-container" style="font-size: 10px;"><input type="checkbox" data-event="check-all" ' + (data.checkedAll?'checked':'') + '><span class="check-checkmark" style="margin-left: 2px;"></span></label></th>' +
        "</tr>" +
        "</thead>" +
        "<tbody>";
    data['kvt_list'].forEach(function(item){
        el +=
                '<tr>' +
                '<td style="text-align:left">' +
                (item['item_code']? item['item_code'] : '') +
                "</td>" +
                '<td style="text-align:left">' +
                (item['item_name']? item['item_name'] : '') +
                "</td>" +
                "<td>" +
                (item['size']? item['size'] : '')+
                "</td>" +
                "<td>" +
                (item['color']? item['color'] : '') +
                "</td>" +
                '<td style="text-align:right">' +
                (item['quantity']? item['quantity'] : 0) +
                "</td>"+
                '<td style="display:none;">' +
                (item['netwt']? item['netwt'] : 0) +
                "</td>"+
                '<td data-item=\''+ JSON.stringify(item) +'\'>' +
                    '<label class="check-container" style=font-size:15px"">'+
                        '<input type="checkbox" data-event="child-checked" class="child-box" ' + (item.checked?'checked':'') + '>'+
                        '<span class="check-checkmark"></span>'+
                    '</label>'+
                "</td>" +
                "</tr>";
    });
    el += "</tbody>" + "</table>";
    return el;
}
// Delete an Item row in Datatable
function onDeleteItem(){
    globalVar.targetTable.row(globalVar.targetRow).remove().draw();
}
// Show Item Modal when click in plus button
function showItemListMode(dvt,kvt){
    globalVar.dvt = dvt;
    globalVar.kvt = kvt;
    globalVar.targetTable = dataTableArray.filter(function(tb){
        return (tb.name == dvt + '_' + kvt);
    })[0].table;
}
// delete a DVTKVTDataTable
function onDeleteDVTKVT(id){
    document.getElementById(globalVar.deleteKVTDVT).remove();
    // $("#"+globalVar.deleteKVTDVT).hide();
    dataTableArray = dataTableArray.filter(function(el) {
        return el.name !== globalVar.deleteKVTDVT;
    });

    if(dataTableArray.length == 0){
        $('input[name=kubun]').iCheck('enable');  
        var dvtList = DVTKVTDataTable.rows().data().toArray();
        dvtList.forEach(function(dvt) {
            dvt['kvt_list'].forEach(function(kvt) {
                delete kvt.checked;
            });
        });
    }
    // remove selected information
    $("#delry_to").val("");
    $("#delry_to_add").val("");
    $("#case_mark").val("");
}
// Save
function onSave(){
    var tableData = [];
    // var isEditing = false;
    var dvtObj = [];
    // check datatable is being edit
    dataTableArray.forEach(function(tb){
        var tbData = tb.table.rows().data().toArray();
        var result = tbData.filter(function( obj ) {
            return obj.edit_mode == true;
        });
        // if(result.length > 0){
        //     isEditing = true;
        // }
        for(var i = 0 ; i< tbData.length; i++){
            let data = tb.table.rows(i).data()[0];
            tableData.push(data);
        }
        if(tb.dvt){
            dvtObj.push(tb.dvt);
        }
    });

    // if(isEditing){
    //     $("#info_msg").text("<?php echo $this->lang->line('PLS0010_E004'); ?>");
    //     $("#info_modal").modal();
    //     return;
    // }
    // delete unusal properties
    tableData.forEach(function(data){
        delete data.checked;
        delete data.edit_mode;
        delete data.add_mode;
        delete data.action;
        delete data.validate;
        delete data.origin_netwt;
        delete data.po;
        delete data.duplicate_mode;
        if(!data.details_no) delete data.details_no;
    });
    
    $("#itemData").val(JSON.stringify(tableData));
    $("#dvtObj").val(JSON.stringify(dvtObj));
    $("#packingForm").submit(); 
    
}
// Delete
function onDeletePacking(){
    $.ajax({
        url:"<?php echo base_url("packing_list/delete") ?>",
        type:'POST',
        data: {id: $("#pack_no").val().trim()}
    });
}
// clear
function onClear(){
    location.reload();
    return;
    var allInputs = document.getElementsByTagName("input");
    for(var x=0; x < allInputs.length; x++){
        if(allInputs[x].name != 'status_name' && allInputs[x].name != 'pack_no'){
            allInputs[x].value = '';
        }
        if(allInputs[x].name == 'packing_date'){
            allInputs[x].value = new Date().toISOString().slice(0, 10).split("-").join("/");
        }
    }
    var allSelectBox = document.getElementsByTagName("select");
    for(var x=0; x < allSelectBox.length; x++){
        allSelectBox[x].selectedIndex =0;
    }

    var allArea = document.getElementsByTagName("textarea");
    for(var x=0; x < allArea.length; x++){
        allArea[x].value = '';
    }
}
</script>
