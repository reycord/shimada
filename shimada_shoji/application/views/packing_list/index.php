<div class="row">
    <form id="packingSearchForm" class="form-horizontal form-label-left" action="<?php echo base_url() ?>packing_list" method="get">
        <div class="form-group">
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <label class="control-label col-md-4 col-sm-5 col-xs-5 no-padding-left no-padding-right" for="pl_no">PL No</label>
                <div class="col-md-8 col-sm-7 col-xs-7 has-clear has-feedback">
                    <input type="text" id="pl_no" name="pl_no" maxlength="20" class="form-control text-uppercase" value="<?php echo $this->input->get('pl_no')?>">
                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <label class="control-label col-md-4 col-sm-5 col-xs-5 no-padding-left no-padding-right" for="delivery_no"><?php echo $this->lang->line('delivery_no'); ?></label>
                <div class="col-md-8 col-sm-7 col-xs-7 has-clear has-feedback">
                    <input type="text" name="dvt_no" id="dvt_no" class="form-control" value="<?php echo $this->input->get('dvt_no')?>">
                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                </div>
            </div>
			<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
                <label class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" style="padding-right: 31px !important;" for="pack_date"><?php echo $this->lang->line('pack_date'); ?></label>
                <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" style="margin-left: -11px !important;">
                    <input type='text' id="packing_from" maxlength="10" class="form-control hasDatepicker date" name='packing_from' value="<?php echo $this->input->get('packing_from')?>"/>
				</div>
				<label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right no-padding-top" for="created_to" style="text-align: center; font-size: 20px;">&sim;</label>
                <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                    <input type='text' id="packing_to" maxlength="10" class="form-control hasDatepicker date" name='packing_to' value="<?php echo $this->input->get('packing_to')?>"/>
                    <div class='err-msg' style="color:red" field="packing_to"></div>
                </div>
                
            </div>
            <!-- <div class="col-md-3 col-sm-3 col-xs-3">
            	<label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-top" for="created_to" style="text-align: center; font-size: 20px;">&sim;</label>
                <div class="col-md-7 col-sm-7 col-xs-7">
                    <input type='text' maxlength="10" class="form-control hasDatepicker date" name='created_to' value="<?php echo set_value('created_to'); ?>"/>
                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                </div>
            </div> -->
        </div>
        <div class="form-group">
			<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <label class="control-label col-md-4 col-sm-5 col-xs-5 no-padding-left" for="status"><?php echo $this->lang->line('status'); ?></label>
                <div class="col-md-8 col-sm-7 col-xs-7">
                    <select name="status" id="status" class="form-control">
                        <option value="">All</option>
                        <?php foreach ($komoku_list as $komoku): ?>
                        <option value="<?php echo $komoku["kubun"]; ?>" <?php if($this->input->get('status') == $komoku["kubun"]) echo 'selected' ?>>
                            <?php echo $komoku["komoku_name_2"]; ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>
			</div>
			<div class="col-md-6 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <label class="control-label col-md-2 col-sm-5 col-xs-5 no-padding-left no-padding-right" for="customer"><?php echo $this->lang->line('consigned_to'); ?></label>
                <div class="col-md-10 col-sm-7 col-xs-10 has-clear has-feedback">
                    <input class="form-control" type="text" id="customer_name" name="customer" value="<?php echo $this->input->get('customer') ?>"/>
                    <input class="form-control" type="hidden"  id="customer_id"  />
                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                </div>
            </div>
			<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                <!-- <label class="control-label col-md-5 col-sm-5 col-xs-5" for="contact"></label>
                <div class="col-md-7 col-sm-7 col-xs-7 has-clear has-feedback">
                    <input type="text" id="contact" name="contact" class="form-control" value="">
                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                </div> -->
			</div>
            <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding-right" style="text-align: right;">
                    <button type="button" id="search" class="btn btn-info" style="margin-right: 0;"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
				</div>
            </div>
        </div>
    </form>
</div>
<style>
 .DTFC_RightBodyLiner{
    width: 420px !important;
 }
</style>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
			<div class="x_title">
                <h2><?php echo $title ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><button class="btn btn-primary" onclick="window.location.href='<?php echo base_url(); ?>packing_list/add'"><i class="fa fa-plus"></i> <?php echo $this->lang->line('new'); ?></button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div>
                    <table id="packing_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0" style="margin-bottom: 10px">
                        <thead>
                            <tr>
                                <th>PL No</th>
                                <!-- <th>INV No</th> -->
                                <th>DVT No</th>
                                <th><?php echo $this->lang->line('actual_pack_date'); ?></th>
                                <!-- <th><?php //echo $this->lang->line('buyer'); ?></th> -->
                                <th><?php echo $this->lang->line('consigned_to'); ?></th>
                                <th><?php echo $this->lang->line('status'); ?></th>
                                <th><?php echo $this->lang->line('package'); ?></th>
                                <th><?php echo $this->lang->line('quantity'); ?></th>
                                <th>NETWT</th>
                                <th>GROSSWT</th>
                                <th>MEAS(M3)</th>
                                <th><?php echo $this->lang->line('edit_user'); ?></th>
                                <th><?php echo $this->lang->line('note'); ?></th>
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
<!-- Apply modal -->
<div id="packing_apply_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('PLS0010_I011'); ?></h4>
            </div>
            <div class="modal-body">
                <h5 id="packing_apply_modal_pl_no" style="color:red" ></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
                <button id="packing_apply_modal_btn" onclick = "applyOnClick($(this).data('pl'))" data-pl="" class="btn btn-success antosubmit2" data-dismiss="modal"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>
<!-- Accept 1 modal -->
<div id="packing_accept1_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('PLS0010_I012'); ?></h4>
            </div>
            <div class="modal-body">
                <h5 id="packing_accept1_modal_pl_no" style="color:red" ></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
                <button id="packing_accpet1_modal_btn" onclick = "accept1OnClick( $(this).data('pl'))" data-pl="" class="btn btn-success antosubmit2" data-dismiss="modal"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>
<!-- CDTT modal -->
<div id="packing_cdtt_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('PLS0010_I012'); ?></h4>
            </div>
            <div class="modal-body">
                <h5 id="packing_cdtt_modal_pl_no" style="color:red" ></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
                <button id="packing_cdtt_modal_btn" onclick = "cdttOnClick( $(this).data('pl'))" data-pl="" class="btn btn-success antosubmit2" data-dismiss="modal"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>
<!-- Accept modal -->
<div id="packing_accept_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('PLS0010_I009'); ?></h4>
            </div>
            <div class="modal-body">
                <h5 id="packing_accept_modal_pl_no" style="color:red" ></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
                <button id="packing_accept_modal_btn" onclick = "acceptOnClick( $(this).data('pl'))" data-pl="" class="btn btn-success antosubmit2" data-dismiss="modal"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>
<!-- Denied modal -->
<div id="packing_denied_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('PLS0010_I010'); ?></h4>
            </div>
            <div class="modal-body">
                <h5 id="packing_denied_modal_pl_no" style="color:red; text-align: center;font-weight: bold" ></h5>
                <div class="row">
                    <label class="control-label form-title col-md-3 col-sm-3 col-xs-3" style="text-align: center; padding-top: 5px;" for="order_no">Reason:</label>
                    <div class="col-md-8 col-sm-8 col-xs-8 no-padding-left has-clear has-feedback">
                        <input type="text" id="reason" name="reason" class="form-control" value="" />
                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
                <button id="packing_denied_modal_btn" onclick = "deniedOnClick( $(this).data('pl'), $('#reason').val())" data-pl="" class="btn btn-success antosubmit2" data-dismiss="modal"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>
<!-- Delete modal -->
<div id="packing_delete_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('PLS0010_I007'); ?></h4>
            </div>
            <div class="modal-body">
                <h5 id="packing_delete_modal_pl_no" style="color:red" ></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary antoclose2" data-dismiss="modal"><?php echo $this->lang->line('no'); ?></button>
                <button id="packing_delete_modal_btn" onclick = "deleteOnClick( $(this).data('pl'))" data-pl="" class="btn btn-success antosubmit2" data-dismiss="modal"><?php echo $this->lang->line('yes'); ?></a>
            </div>
        </div>
    </div>
</div>

<!-- Excel Packing Slip Form -->
<div>
    <form id="packing_slip_form" action="<?php echo base_url() ?>packing_list/excel" method="GET">
        <input type="hidden" name="packing_slip_packing_no" id="packing_slip_packing_no" />
    </form>
</div>

<script type="text/javascript">
var table;
var user = <?php echo json_encode($user); ?>;
var dvtList = (<?php echo json_encode($delivery_list); ?>);
var dataFilterCustomer = [];
var customerList = <?php echo json_encode($customer_list); ?>;
var searchCustiomerId = '<?php echo $this->input->get('customer') ?>'
window.onload = function() {
    $("#dvt_no").autocomplete({
        lookup: dvtList.map(function(dvt){
            return {"value": dvt.dvt_no.trim()};
        }),
		minChars: 0,
        onSelect: function(){
            $(this).change();
        }
    });
    $("#dvt_no").on("focus", function(){
        $(this).select();
    });
    customerList.forEach(function(el){
        if(el.branch_name){
            dataFilterCustomer.push({value: el.branch_name});
        }
    });
    
    // Globle value
    var acceptTargetId = null;
    var deniedTargetId = null;
    var deleteTargetId = null;
    var applyTargetId = null;
     // Validate search Form
     $('#search').on('click', function(){
        $('.err-msg[field=order_date_to]').html('');
        let packing_from = $('#packing_from').val();
        let packing_to = $('#packing_to').val();
        let pl_no = $('#pl_no').val();
        let delivery_no = $('#dvt_no').val();
        let status = $('#status').val();
        let customer = $('#customer').val();
        let contact = $('#contact').val();
        // Date valid
        packing_from = new Date(packing_from);
        packing_to = new Date(packing_to);
        if(packing_from > packing_to) {
            $('.err-msg[field=packing_to]').html('Packing date from > Packing date to!');
            return;
        }
        $("#packingSearchForm").submit();
        table.ajax.reload();
	});

    table = $("#packing_list").DataTable({
        ordering: false,
        filter: false,
        scrollX: true,
        scrollCollapse: true,
        formatNumber: function ( toFormat ) {
            return toFormat.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "'");
        },
        fixedColumns:   {
            leftColumns: 0,
            rightColumns: 1
        },
        columns:[
            // { data:'pack_no',className: "text-left",render :function(data, type, row, meta){
            { data:'pack_no',className: "text-left",render :function(data, type, row, meta){
                $a = $('<a></a>')
                        .addClass('edit')
                        .attr('href','<?php echo base_url("packing_list/edit?id=") ?>'+data)
                        .text(data);
                return $a.prop('outerHTML');
                } 
            },
            // { data:'invoice_no',className: "text-left" },
            { data:'dvt_no',className: "text-left",render: function(data){
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
            { data:'packing_date' },
            // { data:'customer',className: "text-left" },
            { data:'delry_to',className: "text-left" },
            { data:'status_name' },
            { data:'packages',className: "text-right" },
            { data:'quantity',className: "text-right", render: function(data){
				return numberWithCommas(data);
			 }},
			{ data:'netwt',className: "text-right", render: function(data){
				return numberWithCommas(data);
			 }},
			{ data:'grosswt',className: "text-right", render: function(data){
				return numberWithCommas(data);
			 }},
			{ data:'measure',className: "text-right", render: function(data){
				return numberWithCommas(data);
			 }},
            { data:'edit_user',className: "text-left" },
            { data:'note',className: "text-left" },
            { data: 'pack_no', render: function(data, type, row, meta){
                $apply = '<button data-toggle="modal" onclick="$(\'#packing_apply_modal_pl_no\').text(\'PL No: \'+'+ data +' ); $(\'#packing_apply_modal_btn\').data(\'pl\','+ data +')" data-target="#packing_apply_modal" class="btn ' + (row["apply_user"] ?'btn-default' :'btn-warning') + ' btn-sm btn-custom no-padding-top no-padding-bottom" style="margin:1px" title="<?php echo $this->lang->line("apply"); ?>"'
                            + (!row["apply_user"] ?'':'disabled') +'>'
                            + '<span>Apply</span>'
                            + '</button>';
                            
                $accept1 = '<button data-toggle="modal" onclick="$(\'#packing_accept1_modal_pl_no\').text(\'PL No: \'+'+ data +' ); $(\'#packing_accpet1_modal_btn\').data(\'pl\','+ data +')" data-target="#packing_accept1_modal" class="btn ' + (row["accept_user"] ?'btn-default' :'btn-danger') + ' btn-sm btn-custom no-padding-top no-padding-bottom" style="margin:1px" title="<?php echo $this->lang->line("accept"); ?> 1"'
                            + (row["apply_user"] && !row["accept_user"] && (row["status"] == "013" || row["status"] == "022" ) && user["permission_id"] == '<?php echo PERMISSION_MANAGER ?>' ? '':'disabled') +'>'
                            + '<span>Acpt-1</span>'
                            + '</button>';

                $cdtt = '<button data-toggle="modal" onclick="$(\'#packing_cdtt_modal_pl_no\').text(\'PL No: \'+'+ data +' ); $(\'#packing_cdtt_modal_btn\').data(\'pl\','+ data +')" data-target="#packing_cdtt_modal" class="btn ' + (row["measurement_user"] ?'btn-default' :'btn-warning') + ' btn-sm btn-custom no-padding-top no-padding-bottom" style="margin:1px" title="<?php echo $this->lang->line("cdtt"); ?>"'
                            + (row["apply_user"] && !row["measurement_user"] ? '':'disabled') +'>'
                            + '<span>CDTT</span>'
                            + '</button>';
                
                $accept = '<button data-toggle="modal" onclick="$(\'#packing_accept_modal_pl_no\').text(\'PL No: \'+'+ data +' ); $(\'#packing_accept_modal_btn\').data(\'pl\','+ data +')" data-target="#packing_accept_modal" class="btn ' + (row["status"] == "018" ?'btn-default' :'btn-danger') + ' btn-sm btn-custom no-padding-top no-padding-bottom" style="margin:1px" title="<?php echo $this->lang->line("accept"); ?> 2"'
                            + (row["apply_user"] && row["accept_user"] && row["status"] == "022" && user["permission_id"] == '<?php echo PERMISSION_MANAGER ?>' ? '':'disabled') +'>'
                            + '<span>Acpt-2</span>'
                            + '</button>';

                $denied = '<button data-toggle="modal" onclick="$(\'#packing_denied_modal_pl_no\').text(\'PL No: \'+'+ data +' ); $(\'#packing_denied_modal_btn\').data(\'pl\','+ data +');$(\'#reason\').val(\''+ row.note +'\');" data-target="#packing_denied_modal" class="btn ' + (row["apply_user"] ?'btn-info' :'btn-default') + ' btn-sm btn-custom no-padding-top no-padding-bottom" style="margin:1px" title="<?php echo $this->lang->line("denial"); ?>"'
                            + (row["apply_user"] && row["status"] != "018" && user["permission_id"] == '<?php echo PERMISSION_MANAGER ?>' ?'':'disabled') +'>'
                            + '<span><?php echo $this->lang->line("denial"); ?></span>'
                            + '</button>';

                $excel = '<button data-toggle="modal" onclick="exportPackingSlip(' + data + ')"  class="btn bg-info btn-sm btn-success no-padding-top no-padding-bottom" style="margin:1px" title="Excel">'
                            + '<span>Excel</span>'
                            + '</button>';

                $delete = '<button  data-toggle="modal" onclick="$(\'#packing_delete_modal_pl_no\').text(\'PL No: \'+'+ data +' ); $(\'#packing_delete_modal_btn\').data(\'pl\','+ data +')" data-target="#packing_delete_modal" class="btn btn-danger btn-sm btn-custom" style="margin:1px" title="<?php echo $this->lang->line("delete"); ?>"'
                           + (row["status"] != "018" && user["permission_id"] == '<?php echo PERMISSION_MANAGER ?>' ?'':'disabled') +'>'
                            + '<span class="glyphicon glyphicon-trash"></span>'
                            + '</a>';
                
                return $('<div class="input-group"></div>')
                        .append($apply)
                        .append($accept1)
                        .append($cdtt)
                        .append($accept)
                        .append($denied)
                        .append($excel)
                        .append($delete)
                        .prop('outerHTML');
            }}
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
                url : "<?php echo base_url("packing_list/search") ?>",
                type : 'POST',
                data: function ( data ) {
                    data.param = {};
                    data.ajax = true;
                    var arr = $('#packingSearchForm').serializeArray();
                    $.each(arr, function(index, item) {
                        if(item.value != '') {
                            data.param[item.name] = item.value;
                        }
                    });
                    //console.log(data);
                }
            },
        "createdRow": function(row, data, dataIndex) {
            $(row).find('td:eq(13)').attr('style', 'padding: 2px 5px 2px 5px !important');
            if(data.kubun == '2'){
                $(row).find('td:eq(1)').css('background-color', 'rgb(255, 232, 217)');
            }
        }
    });

    $("#customer_name").autocomplete({
        lookup: dataFilterCustomer,
        minChars: 0,
        width: '500px',
        onSelect:function(suggestion){
            $(this).change();
            $("#customer_id").val(suggestion.data);
        }
    });


}

function applyOnClick(pl){

    $.ajax({
        url:"<?php echo base_url("packing_list/apply") ?>",
        type:'POST',
        data: {id: pl},
        success: function(result, status, xhr){
            result = JSON.parse(result);
            snackbarShow(result["message"], 3000);
            //reload data
            table.ajax.reload(false);
        }
    });
}
function accept1OnClick(pl){
    $.ajax({
        url:"<?php echo base_url("packing_list/accept1") ?>",
        type:'POST',
        data: {id: pl},
        success: function(result, status, xhr){
            result = JSON.parse(result);
            snackbarShow(result["message"], 3000);
            //reload data
            table.ajax.reload(false);
        }
    });
}
function cdttOnClick(pl){
    $.ajax({
        url:"<?php echo base_url("packing_list/cdtt") ?>",
        type:'POST',
        data: {id: pl},
        success: function(result, status, xhr){
            result = JSON.parse(result);
            snackbarShow(result["message"], 3000);
            //reload data
            table.ajax.reload(false);
        }
    });
}
function acceptOnClick(pl){
    $.ajax({
        url:"<?php echo base_url("packing_list/accept") ?>",
        type:'POST',
        data: {id: pl},
        success: function(result, status, xhr){
            result = JSON.parse(result);
            snackbarShow(result["message"], 3000);
            //reload data
            table.ajax.reload(false);
        }
    });
}
function deniedOnClick(pl, reason){
    $.ajax({
        url:"<?php echo base_url("packing_list/denied") ?>",
        type:'POST',
        data: {id: pl, reason: reason},
        success: function(result, status, xhr){
            result = JSON.parse(result);
            snackbarShow(result["message"], 3000);
            //reload data
            table.ajax.reload(false);
        }
    });
}

function deleteOnClick(pl){
    $.ajax({
        url:"<?php echo base_url("packing_list/delete") ?>",
        type:'POST',
        data: {id: pl},
        success: function(result, status, xhr){
            result = JSON.parse(result);
            snackbarShow(result["message"], 3000);
            //reload data
            table.ajax.reload(false);
        }
    });
}

function exportPackingSlip($pack_no){
    $("#packing_slip_packing_no").val($pack_no);
    $("#packing_slip_form").submit();
}  
</script>
