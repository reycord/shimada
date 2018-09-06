<!-- Created by Khanh
    Date : 2018/04/11 -->
<?php 
    // var_dump($dvt) ;
?>
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
                <h2><?php echo $title; ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <form action="<?php echo base_url() ?>another_order/excel" method="GET">
                            <button type="submit" id="export_excel_modal" class="btn btn-success" style="float: right;"><i class="fa fa-file-excel-o" ></i> Excel </button>
                        </form>
                    </li>
                    <li>
                        <button type="button" id="show_delivery_input_modal" class="btn btn-primary" style="float: right;"><?php echo $this->lang->line('delivery_order');?> </button>
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
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <!-- <th>INV</th> -->
                                <!-- <th> &nbsp;&nbsp;&nbsp; KNO &nbsp;&nbsp;</th> -->
                                <th><?php echo $this->lang->line('purchase_order_no');?></th>
                                <th><?php echo $this->lang->line('times');?></th>
                                <!-- <th><?php echo $this->lang->line('partition_no');?></th> -->
                                <th>Input Date</th>
                                <!-- <th>Deli Req Date</th> -->
                                <th><?php echo $this->lang->line('shipping_method');?></th>
                                <!-- <th><?php echo $this->lang->line('code_sales');?></th> -->
                                <!-- <th><?php echo $this->lang->line('staff');?></th>
                                <th>ID<?php echo $this->lang->line('staff');?></th> -->
                                <th><?php echo $this->lang->line('factory_name');?></th>
                                <th><?php echo $this->lang->line('factory_address');?></th>
                                <th><?php echo $this->lang->line('contract_no');?></th>
                                <th><?php echo $this->lang->line('style');?>No</th>
                                <th>O/No</th>
                                <th style="background-color: #66ff99;"><?php echo $this->lang->line('factory_require_date');?></th>
                                <th style="background-color: #66ff99;"><?php echo $this->lang->line('factory_plan_date');?></th>
                                <th style="background-color: #66ff99;"><?php echo $this->lang->line('delivery_date1');?></th>
                                <!-- <th>PO No</th> -->
                                <!-- <th>PO in VSIP</th> -->
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('request_packing_date');?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('measurem_date');?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('passage_date'); ?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('out_vsip_date'); ?></th>
                                <th><?php echo $this->lang->line('staff');?></th>
                                <th><?php echo $this->lang->line('status');?></th>
                                <th><?php echo $this->lang->line('note');?></th>
                                <th style='min-width: 200px'><?php echo $this->lang->line('case_mark')." File";?></th>
                                <th style='min-width: 200px'><?php echo $this->lang->line('case_mark')." Text";?></th>
                                <th>Buyer</th>
                                <th class="nosort"><?php echo $this->lang->line('action');?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Load data by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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
                        <button class="btn btn-primary" id="btn_save_delivery">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                        </button>
                    </li>
                </ul>
                
                <h2><?php echo $this->lang->line('delivery_order_input');?><span id="err_msg" style="color:red; margin-left: 100px; text-align: center; display:none" ></span></h2>
            </div>
            <div class="modal-footer">
                <form class="form-horizontal form-label-left" id="frm_delivery_input">
                    <input type="hidden" name="po_infor" value="" />
                    <input class="hidden" name="stype_no" id="stype_no"/>
                    <input type="hidden" id="currency" name="currency">
                    <div>
                        <input type="hidden" class="form-control validate[required]" id="data_table" name="data_table" value="">
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right"
                                for="dvt_no"
                            >PO No<span class="required">*</span>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <select class="form-control validate[required]" name="dvt_no" id="dvt_no">
                                </select>
                                <div field="dvt_no" style="color:red; text-align:left;"></div>
                            </div>
                            <label 
                                class="control-label col-xs-6 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="order_date"
                            >Input Date<span class="required">*</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right"
                                for="times"
                            ><?php echo $this->lang->line('times')?><span class="required">*</span>
                            </label>
                                <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <input 
                                    type="text"
                                    class="form-control validate[required]" 
                                    id="times" 
                                    name="times" 
                                    value = "0" 
                                    readonly
                                >
                                <div field="times" style="color:red; text-align:left;"></div>
                            </div>
                            <label 
                                class="control-label col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                            >Item List
                            </label>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="delivery_require_date"
                            ><?php echo $this->lang->line('wish_delivery_date');?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control date" 
                                    id="delivery_require_date" 
                                    name="delivery_require_date"
                                    value="<?php echo date('d M, Y');?>"
                                >
                            </div>
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="kubun"
                            ><?php echo $this->lang->line('classify')?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="kubun" 
                                    name="kubun" 
                                    value="Other" 
                                    readonly
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="delivery_method"
                            ><?php echo $this->lang->line('shipping_method')?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
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
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="contract_no"
                            ><?php echo $this->lang->line('contract_no');?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control text-uppercase" 
                                    id="contract_no" 
                                    name="contract_no"
									maxlength="30"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="staff"
                            ><?php echo $this->lang->line('staff')?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="staff" 
                                    name="staff"
									maxlength="20"
                                >
                            </div>
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="staff_id"
                            >ID<?php echo $this->lang->line('staff')?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control text-uppercase" 
                                    id="staff_id" 
                                    name="staff_id"
									maxlength="10"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="assistance"
                            ><?php echo $this->lang->line('assistance')?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="assistance" 
                                    name="assistance"
									maxlength="20"
                                >
                            </div>
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="department"
                            ><?php echo $this->lang->line('department')?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="department" 
                                    name="department"
									maxlength="20"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="factory"
                            ><?php echo $this->lang->line('factory_name')?>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-9 no-padding-right">
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="factory" 
                                    name="factory"
									maxlength="50"
                                >
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="address"
                            ><?php echo $this->lang->line('factory_address')?>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-9 no-padding-right">
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
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="case_mark"
                            ><?php echo $this->lang->line('case_mark')?>
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-9 no-padding-right">
                                <div class="input-group file">
                                    <input type="text" class="form-control" id="input_case_mark"  name="case_mark" placeholder="<?php echo $this->lang->line('file_name');?>" value="" readonly>
                                    <span class="input-group-addon btn-primary case-mark"><?php echo $this->lang->line('choose_file'); ?></span>
                                </div>
                                <input type="file" class="form-control file_case_mark" id="file_case_mark" name="file_case_mark" style="display:none" value="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                style="text-align: right;" 
                                for="text_case_mark">
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-9 no-padding-right">
                                <textarea 
                                    spellcheck="false"
                                    type="text" 
                                    rows="3" 
                                    class="form-control" 
                                    id="text_case_mark" 
                                    name="text_case_mark"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 no-padding-left">
                        <div class="form-group">
                            <div class="col-xs-4 no-padding-left">
                                <input 
                                    type="text" 
                                    id="order_date" 
                                    name="order_date" 
                                    class="form-control date validate[required]"
                                    value="<?php echo  date('d M, Y');?>"
                                />
                                <div field="order_date" style="color:red; text-align:left;"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <table 
                                id="tbl_order_list" 
                                style="border: 1px solid #ddd;"
                                class="table table-striped table-bordered cssTable display nowrap" 
                                width="100%" 
                                cellspacing="0"
                            >
                                <thead style = "background-color: #3598dc;">
                                    <tr>
                                        <th><?php echo $this->lang->line('item_code');?></th>
                                        <th><?php echo $this->lang->line('items_name');?></th>
                                        <th><?php echo $this->lang->line('size');?></th>
                                        <th><?php echo $this->lang->line('color');?></th>
                                        <th><?php echo $this->lang->line('quantity');?></th>
                                    </tr>
                                </thead>
                            </table>
                            <div field="po_infor" style="color:red; text-align:left;"></div>
                        </div>
                        <!-- <div class="form-group">
                            <div class="col-md-12 col-sm-6 col-xs-6 no-padding-left">
                                <button 
                                    type="button" 
                                    class="btn btn-primary"
                                    data-toggle="modal" 
                                    data-target="#pv_no_input_modal" 
                                    id="show_model_order"
                                    style="float: right;"
                                >Add PV No
                                </button>
                            </div>
                        </div> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<script>
    window.onload = function() 
    {
        $('#frm_delivery_input').validationEngine();
        // create datatable
        var itemDt = $('#schedule_detail_list').DataTable({
            "data": <?php echo json_encode($dvt); ?>,
            "paging": true,
            "filter": true,
            "oSearch": {"sSearch": '<?php echo ($search_dvt ? $search_dvt : ''); ?>' },
            "ordering": true,
            "scrollX" : false,
            "drawCallback": function() {
                // datepicker
                $(".date").datepicker({
                    todayHighlight: true,
                    format: "d M, yyyy",
                    autoclose: true
                });
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
                { "data": "dvt_no", class: 'text-left', render: function( data, type, row, meta ){
                    var partitionNo = (row.number_times > 1) ? row.times : "";
                    var dvtNo = data;
                    var detailUrl = '<?php echo base_url('order_received/?order_receive_no=') ?>'
                        + dvtNo + "&part_no="+partitionNo;
                    $a = $('<a></a>')
                        .addClass('edit')
                        .attr('href', detailUrl)
                        .text(dvtNo);
                    $toggle = $('<span></span>')
                        .addClass('collapse-order glyphicon glyphicon-triangle-right')
                    return $toggle.prop('outerHTML') + ' ' + $a.prop('outerHTML');
                    
                }, className: ''},
                // { "data": "partition_no", render: function( data, type, row, meta ){
                    // var dvtNo = row.dvt_no.split("-");
                    // if(dvtNo.length == 1){
                    //     return '';
                    // }
                    // return dvtNo[dvtNo.length - 1];
                    // return "";
                // }},
                { "data": "times", render: function( data, type, row, meta ){
                    if(row.number_times > 1){
                        return row.times;
                    }
                    return '';
                }},
                { "data": "order_date", render: function( data, type, row, meta ){
                    return row.order_date;
                }},
                { "data": "komoku_name_2", render: function( data, type, row, meta ){
                    return row.komoku_name_2;
                }},
                // { "data": "delivery_require_date", render: function( data, type, row, meta ){
                //     return row.delivery_require_date;
                // }},
                // { "data": "staff", render: function( data, type, row, meta ){
                //     return row.staff;
                // }},
                // { "data": "staff_id", render: function( data, type, row, meta ){
                //     return row.staff_id;
                // }}, 
                { "data": "factory",class: 'text-left', render: function( data, type, row, meta ){
                    if(row.factory){
                        return ('<div class="ellipsis" title="'+row.factory+'">'+row.factory+'</div>');
                    }
                    return '';
                }},
                { "data": "address",class: 'text-left', render: function( data, type, row, meta ){
                    if(row.address){
                        return ('<div class="ellipsis" title="'+row.address+'">'+row.address+'</div>');
                    }
                    return '';
                }},
                { "data": "contract_no",class: 'text-left', render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control text-uppercase')
                            .attr('name', 'contract_no')
							.attr('maxlength', '30')
                            .attr('value', row.contract_no)
                            .css('max-width', '90px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    }
                    return row.contract_no;
                }},
                { "data": "stype_no",class: 'text-left', render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control text-uppercase')
                            .attr('name', 'stype_no')
							.attr('maxlength', '20')
                            .attr('value', row.stype_no)
                            .css('max-width', '90px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    }
                    return row.stype_no;
                }},
                { "data": "o_no",class: 'text-left', render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control text-uppercase')
                            .attr('name', 'o_no')
							.attr('maxlength', '20')
                            .attr('value', row.o_no)
                            .css('max-width', '90px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    }
                    return row.o_no;
                }},
                { "data": "factory_require_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value', formatDate(row.factory_require_date))
                            .attr('class', 'form-control  hasDatepicker date')
                            .attr('name', 'factory_require_date')
                            .css('max-width', '100px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.factory_require_date;
                    } 
                }},
                { "data": "factory_plan_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value',formatDate( row.factory_plan_date))
                            .attr('class', 'form-control  hasDatepicker date')
                            .attr('name', 'factory_plan_date')
                            .css('max-width', '100px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.factory_plan_date;
                    }
                }},
                { "data": "delivery_plan_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value', formatDate(row.delivery_plan_date))
                            .attr('class', 'form-control  hasDatepicker date')
                            .attr('name', 'delivery_plan_date')
                            .css('max-width', '100px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.delivery_plan_date;
                    }
                }},
                // { "data": "pv_infor", render: function( data, type, row, meta ){
                //     if (row.edit_mode){
                //         var $input = $('<input>')
                //             .attr('type', 'text')
                //             .attr('class', 'form-control')
                //             .attr('name', 'pv_infor')
                //             .attr('value', row.pv_infor)
                //             .css('max-width', '90px')
                //             .css('height', '30px');
                //         return $input[0].outerHTML;
                //     } else {
                //         return row.pv_infor;
                //     }
                // }},
                // { "data": "pv_in_date", render: function( data, type, row, meta ){
                //     if (row.edit_mode){
                //         var $input = $('<input>')
                //             .attr('value', formatDate(row.pv_in_date))
                //             .attr('class', 'form-control  hasDatepicker date')
                //             .attr('name', 'pv_in_date')
                //             .css('max-width', '100px')
                //             .css('height', '30px');
                //         return $input[0].outerHTML;
                //     } else {
                //         return row.pv_in_date;
                //     }
                // }},
                { "data": "packing_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value', formatDate(row.packing_date))
                            .attr('class', 'form-control  hasDatepicker date')
                            .attr('name', 'packing_date')
                            .css('max-width', '100px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.packing_date;
                    }
                }},
                { "data": "measurement_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value', formatDate(row.measurement_date))
                            .attr('class', 'form-control  hasDatepicker date')
                            .attr('name', 'measurement_date')
                            .css('max-width', '100px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.measurement_date;
                    }
                }},
                { "data": "passage_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value', formatDate(row.passage_date))
                            .attr('class', 'form-control  hasDatepicker date')
                            .attr('name', 'passage_date')
                            .css('max-width', '100px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.passage_date;
                    }
                }},
                { "data": "factory_delivery_date", render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('value', formatDate(row.factory_delivery_date))
                            .attr('class', 'form-control  hasDatepicker date')
                            .attr('name', 'factory_delivery_date')
                            .css('max-width', '100px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        return row.factory_delivery_date;
                    }
                }},
                { "data": "salesmanname",class: 'text-left', render: function( data, type, row, meta ){
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
                        var html = '<select name="sales_man" id="item_listbox" class="form-control" style="max-width:100px;height:30px;">'
                                + '<option value=""></option>'
                                + $temp
                                + '</select>';
                        return html;
                    } else {
                        return row.salesmanname;
                    }
                }},
                { "data": "status_name",class: 'text-left', render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var array = <?php echo json_encode($status_list)?>;
                        var $temp = '';
                        for (let index = 0; index < array.length; index++) {
                            const element = array[index];
                            if(row.status_name == element.note2) {
                                $temp += '<option selected value='+element.status_code+'>'+element.note2+'</option>'
                            } else {
                                $temp += '<option value='+element.status_code+'>'+element.note2+'</option>'
                            }
                        }
                        var html = '<select name="status_name" id="status_name" class="form-control" style="max-width:100px;height:30px;">'
                                + '<option value=""></option>'
                                + $temp
                                + '</select>';
                        return html;
                    } else {
                        return row.status_name;
                    }
                }},
                { "data": "note",class: 'text-left', render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control')
                            .attr('name', 'note')
                            .attr('value', row.note)
                            .css('max-width', '90px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        var html = '';
                        if(row.note){
                            html += '<div class="ellipsis" title="'+row.note+'">'+row.note+'</div>';
                        }
                        return html;
                    }
                }},
                { "data": "case_mark", class :'text-left', render: function( data, type, row, meta ){
                    var file_input = "";
                    var val_case_mark = "";
                    if(row.case_mark){
                        val_case_mark = row.case_mark; 
                        file_input = row.case_mark.split('/').pop();
                    }
                    if(row.edit_mode){
                        var case_mark = '';
                        case_mark +='<div class="input-group file">'
                                    +'<input style="width : 200px" type="text" class="form-control" name="file_upload" value="'+ (file_input ? file_input : "" ) +'" placeholder="<?php echo $this->lang->line('file_name'); ?>" readonly>'
                                    +'<span class="input-group-addon btn-primary case-mark"><?php echo $this->lang->line('choose_file'); ?></span>'
                                    +'</div>'
                                    +'<input type="file" name="file_upload_hidden" class="file_upload_hidden" style="display:none">'
                                    +'<?php echo form_error('file_upload_hidden'); ?>';
                    }
                    else {
                        var case_mark = '';
                        case_mark +='<div class="input-group file">'
                                    +'<a href="'+ val_case_mark +'" download="'+val_case_mark+'"style="width : 200px; overflow:hidden;" type="text" class="align-right" name="file_upload">'+ file_input +'</a>'
                                    // +'<input style="width : 200px" type="text" class="form-control" name="file_upload" value='+ row.case_mark +' placeholder="<?php echo $this->lang->line('file_name'); ?>" readonly>'
                                    +'<span class="input-group-addon btn-primary case-mark" style="display:none"><?php echo $this->lang->line('choose_file'); ?></span>'
                                    +'</div>'
                                    +'<input type="file" name="file_upload_hidden" class="file_upload_hidden" style="display:none">'
                                    +'<?php echo form_error('file_upload_hidden'); ?>';
                    }
                    return case_mark;
                }},
                { "data": "case_mark_text", class :'text-left', render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<textarea></textarea>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'case_mark_text')
                            .attr('spellcheck', false)
                            .text(row.case_mark_text)
                            .attr('maxlength', 100)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        var html = '';
                        if(row.case_mark_text){
                            html += '<div class="ellipsis" title="'+row.case_mark_text+'">'+row.case_mark_text+'</div>';
                        }
                        return html;
                    }
                }},
                { "data": "buyer", class: 'text-left', render: function( data, type, row, meta ){
                    if (row.edit_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control')
                            .attr('name', 'buyer')
                            .attr('maxlength', '100')
                            .attr('value', row.buyer)
                            .css('max-width', '90px')
                            .css('height', '30px');
                        return $input[0].outerHTML;
                    } else {
                        var html = '';
                        if(row.buyer){
                            html += '<div class="ellipsis" title="'+row.buyer+'">'+row.buyer+'</div>';
                        }
                        return html;
                    }
                }},
                { "data": "action", "name":"nosort", render: function( data, type, row, meta ){
                    var html = '';
                    if (row.edit_mode) {
                        html += '<a data-event="save-item" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
                            + '<span class="fa fa-check"></span>'
                            + '</a>';
                        html += '<a data-event="close-item" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
                            + '<span class="fa fa-close"></span>'
                            + '</a>';
                    } else {
                        // html += "<a href='<?php echo base_url(); ?>another_order/exportpdf/"+row.dvt_no.trim()+"/"+row.times+"/"+row.order_date+"' class='btn btn-default btn-xs' title='Download'>"
                        //     +  '<span class="glyphicon glyphicon-download-alt"></span>'
                        //     + '</a>'
                        html += '<a data-event="edit-item" class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>">'
                            + '<span class="fa fa-edit"></span>'
                            + '</a>';
                        html += '<a data-event="delete-item" style="margin-left: 3px;" class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>">'
                            + '<span class="fa fa-trash"></span>'
                            + '</a>';
                    }
                    return html;
                }},
            ],
            "columnDefs": [ {
                "targets": 'nosort',
                "orderable": false
            } ]
        });
        currentKVTData = itemDt;
        // collapse detail
        $('#schedule_detail_list').on('click', 'span.collapse-order', function(){
            var tr = $(this).closest("tr");
            currentKVTRow = tr;
            var row = itemDt.row(tr);
            
            itemDt.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
                if(tr.index() != rowIdx){
                    itemDt.row(rowIdx).child.hide();
                    $('#schedule_detail_list').find('span.collapse-order').removeClass("glyphicon-triangle-bottom");
                }
            });
            if (row.child.isShown()) {
                $(this).removeClass("glyphicon-triangle-bottom");
                row.child.hide();
                tr.removeClass("shown");
            } else {
                $(this).addClass("glyphicon-triangle-bottom");
                row.child(renderChildRow(row.data())).show();
                tr.addClass("shown");
                currentDetail = row.data().dvt_no.trim();
                currentDetailData = $('#'+currentDetail.hashCode()).DataTable({
                    "data": [],
                    "paging": false,
                    "filter": false,
                    "ordering": false,
                    "scrollX" :true,
                    "info": false,
                    "ajax": {
                        "url": "<?php echo base_url('another_order/searchItems'); ?>",
                        "type": 'post',
                        "data": function ( data ) {
                            data.param = {};
                            data.param['order_date'] = row.data().order_date;
                            data.param['dvt_no'] = row.data().dvt_no;
                            data.param['kvt_no'] = row.data().dvt_no;
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
                            return row.item_name;
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
                                    .attr('class', 'form-control')
                                    .attr('name', 'quantity')
                                    .attr('value', parseFloat(row.quantity))
                                    .attr('onkeydown', 'return checkQuantity(event)')
                                    .css('max-width', '90px')
                                    .css('height', '30px');
                                return $input[0].outerHTML;
                            } else {
                                return parseFloat(row.quantity);
                            }
                        }},
                        { "data": "buy_price", render: function( data, type, row, meta ){
                            return parseFloat(row.buy_price).myToFixed(row.currency);
                        }},
                        { "data": "sell_price", render: function( data, type, row, meta ){
                            return parseFloat(row.sell_price).myToFixed(row.currency);
                        }},
                        { "data": "packing_date", render: function( data, type, row, meta ){
                            if (row.edit_mode){
                                var $input = $('<input>')
                                    .attr('value', formatDate(row.packing_date))
                                    .attr('class', 'form-control hasDatepicker date')
                                    .attr('name', 'packing_date')
                                    .css('max-width', '100px')
                                    .css('height', '30px');
                                return $input[0].outerHTML;
                            } else {
                                return row.packing_date;
                            }
                        }},
                        { "data": "arrival_date", render: function( data, type, row, meta ){
                            return row.arrival_date;
                        }},
                        { "data": "item_quantity","className": "text-right", render: function( data, type, row, meta ){
                            return parseFloat(row.item_quantity);
                        }},
                        { "data": "action", render: function( data, type, row, meta ){
                            var html = '';
                            if (row.edit_mode) {
                                html += '<a onclick="DetailSave(this)" data-event="save-item-detail" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
                                    + '<span class="fa fa-check"></span>'
                                    + '</a>';
                                html += '<a onclick="DetailClose(this)" data-event="close-item-detail" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
                                    + '<span class="fa fa-close"></span>'
                                    + '</a>';
                            } else {
                                html += '<a onclick="DetailEdit(this)" data-event="edit-item-detail" class="btn btn-xs btn-info" title="<?php echo $this->lang->line('edit'); ?>">'
                                    + '<span class="fa fa-edit"></span>'
                                    + '</a>';
                                html += '<a onclick="DetailDelete(this)" data-event="delete-item-detail" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('delete'); ?>">'
                                    + '<span class="fa fa-trash"></span>'
                                    + '</a>';
                            }
                            return html;
                        }},
                    ]
                });
            }
        });
        
        function renderChildRow(data) {
            var el =
                "<table id='"+data.dvt_no.trim().hashCode()+"' class='table table-striped table-dashed display nowrap' width='100%' cellspacing='0'>" +
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
                "<th>" + lang['pack_date'] + "</th>" +
                // "<th>PO No</th>" +
                "<th>" + lang['input_date'] + "</th>" +
                "<th>" + lang['store_quantity'] + "</th>" +
                "<th>" + lang['action'] + "</th>" +
                "</tr>" +
                "</thead></table>";
            return el;
        }

        // Items list box on change
        $('#schedule_detail_list').on('change', '#item_listbox', function() {
            var $row = $(this).parents('tr'),
            rowData = itemDt.row($row).data();
            rowData.employee_id = $(this).val();
            rowData.salesmanname = $row.find('select[name="sales_man"]').children(":selected").text();
            rowData.status_name = $row.find('select[name="status_name"]').children(":selected").text();
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
            // rowData.pv_no = $row.find('input[name="pv_no"]').val();
            itemDt.row($row).data(rowData);
            itemDt.draw( false );
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
        $('#show_delivery_input_modal').on('click', function(){
            var orderReceivedList = null;
            <?php if(isset($receiveList) && $receiveList != null && sizeof($receiveList) > 0): ?>
                orderReceivedList = <?php echo json_encode($receiveList); ?>;
            <?php endif ?>
            
            $.ajax({
                url: "<?php echo base_url('another_order/getPOList')?>",
                type: 'get',
                dataType: 'json',
                async: false,
            }).done(function(response) {
               if(response.data){
                    orderReceivedList = response.data;
                    if(orderReceivedList.length == 0){
                        snackbarShow('<?php echo $this->lang->line('JOS0010_I005');?>');
                        return;
                    }else{
                        $('#dvt_no').find('option').remove();
                        $('#dvt_no').append('<option value=""></option>');
                        for(let i = 0; i < orderReceivedList.length; i++){
                            var dtValue = orderReceivedList[i].order_receive_no+'/'+orderReceivedList[i].partition_no+'/'+orderReceivedList[i].order_receive_date;
                            var dtText = '';
                            if(orderReceivedList[i].partition_no >= 2 || (orderReceivedList[i].number_partition && orderReceivedList[i].number_partition > 1)){
                                dtText = orderReceivedList[i].order_receive_no.trim()+'-'+orderReceivedList[i].partition_no;
                            }else{
                                dtText = orderReceivedList[i].order_receive_no.trim();
                            }
                            $('#dvt_no').append('<option value="'+dtValue+'">'+dtText+'</option>');
                        }
                        $('#delivery_input_modal').modal('show');
                    }
               }
            });
        });
        // Click delete in datatable
        $('#schedule_detail_list').on('click', '[data-event="delete-item"]', function(){
            var $row = $(this).parents('tr'),
            rowData = itemDt.row($row).data(),
            requestData = {};
            requestData.order_date = rowData.order_date;
            requestData.times = rowData.times;
            requestData.dvt_no = rowData.dvt_no;
            requestData.pv_infor = rowData.pv_infor;

            bootbox.confirm({
                title: "Do you want to delete this DVT?",
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
                            url: "<?php echo base_url('another_order/delete')?>",
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
        });

        // Click save in datatable
        $('#schedule_detail_list').on('click', '[data-event="save-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            bootbox.confirm({
                title: "<?php echo $this->lang->line('JOS0010_I003');?>",
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
                        var fd = new FormData();
                        var casemark_file = $row.find("input[name='file_upload_hidden']").prop('files')[0];
                        if(casemark_file){
                            fd.append('casemark_file', casemark_file);
                            $.ajax({
                                url: "<?php echo base_url('another_order/uploadcasemarkfile')?>",
                                data: fd,
                                type: 'post',
                                cache: false,
                                contentType: false,
                                processData: false
                            });
                            requestData.casemark_file_name = casemark_file['name'];
                        }
                        
                        requestData.order_date = rowData.order_date;
                        requestData.inv_flg = $row.find("input[name='inv_flg']").prop("checked") == true ? '1' : '0';
                        requestData.times = rowData.times;
                        requestData.dvt_no = rowData.dvt_no;
                        requestData.edit_date = rowData.edit_date;
                        requestData.stype_no = $row.find("input[name='stype_no']").val();
                        requestData.o_no = $row.find("input[name='o_no']").val();
                        requestData.contract_no = $row.find("input[name='contract_no']").val();
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
                        // requestData.pv_infor = $row.find("input[name='pv_infor']").val();
                        requestData.salesman = rowData.employee_id;
                        // requestData.salesman = $row.find("select[name='sales_man']").val();
                        requestData.status_code = $row.find("select[name='status_name']").val();
                        requestData.note = $row.find("input[name='note']").val();
                        requestData.case_mark_text = $row.find("textarea[name='case_mark_text']").val();
                        requestData.buyer = $row.find("input[name='buyer']").val();
                        $.ajax({
                            url: "<?php echo base_url('another_order/save')?>",
                            data: requestData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function(response) {
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
        
        // handle button casemark
        $('#schedule_detail_list').on('click', 'span.case-mark', function(){
            var tr = $(this).parents('tr');
            tr.find("input[name=file_upload_hidden]").trigger("click");
        });
        $('#schedule_detail_list').on('change', '.file_upload_hidden', function(){
            var tr_hidden = $(this).parents('tr');
            if(this.value.length > 0){
                var pathFile = this.value.replace(/^.*[\\\/]/, '');
                $(tr_hidden).find("input[name=file_upload]").attr("value", pathFile);
            }
        });
        $('#frm_delivery_input').on('click', 'span.case-mark', function(){
            $("input[name=file_case_mark]").trigger("click");
        });
        $('#frm_delivery_input').on('change', '.file_case_mark', function(){
            if(this.value.length > 0){
                var pathFile = this.value.replace(/^.*[\\\/]/, '');
                $("#frm_delivery_input input[name=case_mark]").attr("value", pathFile);
            }
        });
        // ***********************END******************

        $('#tbl_order_list').DataTable({
            "data": [],
            "paging": true,
            "filter": false,
            "ordering": false,
            "scrollX" :true,
            "searching" :false,
            "serverSide": true,
            "processing": true,
            "pagingType": "simple",
            dom:  "<'row'<'col-xs-7'><'col-xs-5 text-right'l>>"
            +"<'row'<'col-xs-12'tr>>" +
         "<'row'<'col-xs-4'i><'col-xs-8'p>>",
            "deferLoading": 0, // here
            "ajax": {
                url: "<?php echo base_url()?>another_order/getOrderReceiveDetail",
                type: 'post',
                data: function (data) {
                    var select_option = $("#dvt_no option:selected").val();
                    var temp = select_option.split('/'),
                    // PVNo co dau / "NQV-0118-1/S02&G02"
                    partition_no                        = temp[temp.length-2];
                    order_receive_date                  = temp[temp.length-1];
                    order_receive_no                    = select_option.replace("/"+partition_no+"/"+order_receive_date, "");
                    data.param = {};
                    data.param['order_receive_no']    = order_receive_no;
                    data.param['partition_no']        = partition_no;
                    data.param['order_receive_date']  = order_receive_date;
                }
            },
            columns: [
                {
                    data: 'item_code',"className": "text-left"
                },
                {
                    data: 'item_name',"className": "text-left"
                },
                {
                    data: 'size'
                },
                {
                    data: 'color'
                },
                {
                    data: 'quantity',"className": "text-right"
                },
            ],
        });


        // Hanle load data to table in popup 
        $('#dvt_no').on('change' ,function() {
            $('#tbl_order_list').DataTable().ajax.reload();
            var select_option = $(this).val();
            if(select_option != ''){
                var requestData = {};
                var temp = select_option.split('/');
                // PVNo co dau / "NQV-0118-1/S02&G02"
                requestData.partition_no            = temp[temp.length-2];
                requestData.order_receive_date      = temp[temp.length-1];
                requestData.order_receive_no        = $(this).val().replace("/"+requestData.partition_no+"/"+requestData.order_receive_date, "");
                $('#times').val(requestData.partition_no);
                $.ajax({
                    url: "<?php echo base_url('another_order/getOrderReceiveInfo')?>",
                    data: requestData,
                    type: 'post',
                    dataType: 'json'
                }).done(function(response) {
                    if(response.data) {
                        var orderReceiveInfo = response.data;
                        $('#staff').val(orderReceiveInfo['staff']);
                        $('#currency').val(orderReceiveInfo['currency']);
                        $('#assistance').val(orderReceiveInfo['assistance']);
                        $('#department').val(orderReceiveInfo['department']);
                        $('#factory').val(orderReceiveInfo['delivery_to']);
                        $('#address').val(orderReceiveInfo['delivery_address']);
                        $('#department').val(orderReceiveInfo['odr_department']);
                        $('#contract_no').val(orderReceiveInfo['contract_no']);
                        $('#stype_no').val(orderReceiveInfo['style']);
                    }
                });
            }else{
                $('#staff').val('');
                $('#assistance').val('');
                $('#department').val('');
                $('#factory').val('');
                $('#address').val('');
                $('#department').val('');
                $('#contract_no').val('');
                $('#currency').val('');
            }
        });

        $('#btn_save_delivery').click(function(event){
            // prevent default event
            event.preventDefault();
            var requestData = {};
            var arr = $('#frm_delivery_input').serializeArray();
            $.each(arr, function(index, item) {requestData[item.name] = item.value; });
            if(!$('#frm_delivery_input').validationEngine('validate')) {
                return;
            }
            bootbox.confirm({
                title: "<?php echo $this->lang->line('JOS0010_I003');?>",
                message: '<h4 style="color:red;">' + $('#dvt_no option:selected').text() + '</h4>', 
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
                        var casemark_file = $("#frm_delivery_input input[name='file_case_mark']").prop('files')[0];
                        var fd = new FormData();
                        if(casemark_file){
                            fd.append('casemark_file', casemark_file);
                            $.ajax({
                                url: "<?php echo base_url('another_order/uploadcasemarkfile')?>",
                                data: fd,
                                type: 'post',
                                cache: false,
                                contentType: false,
                                processData: false,
                            });
                            requestData.casemark_file_name = casemark_file['name'];
                        }

                        var url = "<?php echo base_url();?>another_order/saveDelivery";
                        $.post(url, requestData, function (response) { 
                            var responseJson = JSON.parse(response);
                            if(responseJson.success == true){
                                $('#dvt_no option:selected').remove();
                                var currentPage = itemDt.page();
                                // insert new row 
                                // responseJson.data.buyer = '//TODO';
                                itemDt.row.add(responseJson.data);
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
                                // location.reload();
                                snackbarShow(responseJson.message);
                            }else{
                                $('#err_msg').text(responseJson.message);
                                $('#err_msg').show();
                            }
                        });
                    }
                }
            });
        })

        $('#delivery_input_modal').on('shown.bs.modal', function(){
            document.getElementById("file_case_mark").value = null;
            $('#err_msg').text('');
            $('#err_msg').hide();
            $('#frm_delivery_input').validationEngine('hideAll')
            $(this).find("#input_case_mark").val('');
            $(this).find('form')[0].reset();
            $('#tbl_order_list').DataTable().ajax.reload();
        });
    };

    // edit detail item
    function DetailEdit(obj){
        var $row = $(obj).parents('tr');
        var rowData = currentDetailData.row($row).data();
        rowData.edit_mode = true;
        currentDetailData.row($row).data(rowData).invalidate();
        currentDetailData.draw( false );
    }

    // delete detail item
    function DetailDelete(obj){
        var $row = $(obj).parents('tr');
        var rowData = currentDetailData.row($row).data();
        var parentRowData = currentKVTData.row(currentKVTRow).data();
        var requestData = {};
            requestData.order_date = parentRowData.order_date;
            requestData.times = parentRowData.times;
            requestData.dvt_no = parentRowData.dvt_no;
            requestData.kvt_no = parentRowData.dvt_no;
            requestData.item_code = rowData.item_code;
            requestData.detail_no = rowData.detail_no;
            requestData.color = rowData.color;
            requestData.size = rowData.size;
            bootbox.confirm({
                title: "Do you want to delete this Item?",
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
                            url: "<?php echo base_url('another_order/deleteKVTItem')?>",
                            data: requestData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function(response) {
                            snackbarShow(response.message);
                            if(response.success == true) { 
                                currentDetailData.row($row).remove();
                                currentDetailData.draw( false );
                                currentDetailData.ajax.reload();
                            } else {
                                currentDetailData.draw( false );
                            }
                        });
                    }
                }
            });
    }

    // close edit item
    function DetailClose(obj){
        var $row = $(obj).parents('tr');
        var rowData = currentDetailData.row($row).data();
        rowData.edit_mode = false;
        currentDetailData.row($row).data(rowData).invalidate();
        currentDetailData.draw( false ); 
    }

    // save item 
    function DetailSave(obj){
        var $row = $(obj).parents('tr');
        var rowData = currentDetailData.row($row).data();
        var parentRowData = currentKVTData.row(currentKVTRow).data();
        bootbox.confirm({
            title: "<?php echo $this->lang->line('JOS0010_I003');?>",
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
                        requestData.edit_date = rowData.edit_date;
                        requestData.times = parentRowData.times;
                        requestData.dvt_no = parentRowData.dvt_no;
                        requestData.kvt_no = parentRowData.dvt_no;
                        requestData.item_code = rowData.item_code;
                        requestData.color = rowData.color;
                        requestData.size = rowData.size;
                        requestData.detail_no = rowData.detail_no;
                        requestData.quantity = $row.find("input[name='quantity']").val();
                        requestData.packing_date = $row.find("input[name='packing_date']").val();
                        requestData.pv_no = rowData.pv_no;
                        // requestData.pv_no = $row.find("select[name='pv_no']").children(":selected").text();
                        $.ajax({
                        url: "<?php echo base_url('another_order/saveKVTItem')?>",
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
</script>
