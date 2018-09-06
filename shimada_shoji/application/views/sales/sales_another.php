<div class="row">
	<form class="form-horizontal form-label-left" action="" method="get" id="frm_search_sales">
        <div class="form-group">
            <div class="col-md-6 col-sm-5 col-xs-5">
                <label class="control-label col-md-2 col-sm-4 col-xs-4 no-padding-left" for="date_from"><?php echo $this->lang->line('delivery_date')?></label>
                <div class="col-md-4 col-sm-5 col-xs-5 no-padding-left no-padding-right">
                    <div class='input-group date' id='date_from' data-date-format="yyyy/mm/dd">
                        <input 
                            name="delivery_date_from" 
                            id="delivery_date_from" 
                            maxlength="10" 
                            type='text' 
                            class="form-control" 
                            value="<?php echo $this->input->get('delivery_date_from') ?>"
                        />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <label class="control-label col-md-1 col-sm-4 col-xs-4 no-padding-top" for="date_to" style="text-align: center; font-size: 20px">&sim;</label>
                <div class="col-md-4 col-sm-5 col-xs-5 no-padding-left no-padding-right">
                    <div class='input-group date' id='date_to' data-date-format="yyyy/mm/dd">
                        <input 
                            name="delivery_date_to" 
                            id="delivery_date_to" 
                            maxlength="10" 
                            type='text' 
                            class="form-control" 
                            value="<?php echo $this->input->get('delivery_date_to') ?>"
                        />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 col-md-offset-2" style="text-align: right;">
                <button id="search_sales" type="button" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
            </div>
        </div>
	</form>
</div>

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <form action="<?php echo base_url() ?>sales/another_order_excel" method="GET" id="form_export_excel">
                            <input name="data_export" type="hidden" id="data_export"/>
                            <button type="submit" id="btnExcel" class="btn btn-success">
                                <i class="fa fa-file-excel-o" ></i> Excel
                            </button>
                        </form>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content" id="salesList">
                <div class="table-responsive">
                    <table 
                        id="sales_list" 
                        class="table table-striped table-bordered cssTable display nowrap"
                        width="100%" 
                        cellspacing="0"
                    >
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('contract_no'); ?></th>
                                <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                <th><?php echo $this->lang->line('delivery_no'); ?></th>
                                <th><?php echo $this->lang->line('times'); ?></th>
                                <th><?php echo $this->lang->line('passage_date'); ?></th>
                                <th><?php echo $this->lang->line('wish_delivery_date');?></th>
                                <th style="background-color: #66ff99;"><?php echo $this->lang->line('delivery_date'); ?></th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('billing_date'); ?></th>
                                <th><?php echo $this->lang->line('receipt_contract_date'); ?></th>
                                <th><?php echo $this->lang->line('type'); ?></th>
                                <th><?php echo $this->lang->line('ex_customs_clearance_fee'); ?></th>
                                <th><?php echo $this->lang->line('transport_fee'); ?></th>
                                <th><?php echo $this->lang->line('customer'); ?></th>
                                <th><?php echo $this->lang->line('header_name'); ?></th>
                                <th><?php echo $this->lang->line('consignee'); ?></th>
                                <th><?php echo $this->lang->line('sales_man'); ?></th>
                                <th><?php echo $this->lang->line('shipping_method'); ?></th>
                                <th><?php echo $this->lang->line('num_orders'); ?></th>
                                <th><?php echo $this->lang->line('order_amount'); ?></th>
                                <th><?php echo $this->lang->line('dif_amount'); ?></th>
                                <th><?php echo $this->lang->line('update_user'); ?></th>
                                <th><?php echo $this->lang->line('update_date'); ?></th>
                                <th><?php echo $this->lang->line('status'); ?></th>
                                <th><?php echo $this->lang->line('note'); ?></th>
                                <th><?php echo $this->lang->line('action'); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    window.onload = function()
    {
        $('#btnExcel').click(function(){
            var data_export = {
                'delivery_date_from': $('#delivery_date_from').val(),
                'delivery_date_to': $('#delivery_date_to').val()
            };
            $('input[name=data_export').val(JSON.stringify(data_export));
            $("#form_export_excel").submit();
        });
        
        $('#sales_list').on( 'draw.dt', function () {
            $(".date").datepicker({
                todayHighlight: true,
                format: "d M, yyyy",
                autoclose: true
            });
        });
        var permissionID = '<?php echo $permissionID;?>';
        var saleDt = $('#sales_list').DataTable({
            "data": [],
            "paging": true,
            "filter": true,
            "ordering": true,
            "scrollX" : true,
            "dom": 'l<"fixed_columns col-sm-4 col-xs-1"><"col-md-2 col-sm-2 col-xs-1"B><"col-md-1 col-sm-1 col-xs-1"f>rtip',
            "buttons": ['colvis'],
            "drawCallback": function() {
                // datepicker
                $(".date").datepicker({
                    todayHighlight: true,
                    format: "d M, yyyy",
                    autoclose: true
                });

                var columns = $('#fixedColumns').val();
                $(".fixed_columns").css({"display": "inline"});
                $(".fixed_columns").html(`<label class="control-label" style="padding-right: 5px;"><?php echo $this->lang->line('freeze_column'); ?></label>
                <select name="fixedColumns" id="fixedColumns" class="form-control" style="height:30px;">
                    <option value="1"><?php echo $this->lang->line('contract_no'); ?></option>
                    <option value="2"><?php echo $this->lang->line('invoice_no'); ?></option>
                    <option value="3"><?php echo $this->lang->line('delivery_no'); ?></option>
                    <option value="4"><?php echo $this->lang->line('times'); ?></option>
                    <option value="5"><?php echo $this->lang->line('passage_date'); ?></option>
                    <option value="6"><?php echo $this->lang->line('wish_delivery_date'); ?></option>
                </select>`);
                if(columns != undefined) {
                    $('#fixedColumns').val(columns);
                }
            },
            "ajax": { 
                "url": "<?php echo base_url('sales/searchDVT2'); ?>",
				"type": 'post',
				"data": function ( data ) {
					data.param = {};
					var arr = $('#frm_search_sales').serializeArray();
					$.each(arr, function(index, item) {
						if(item.value != '') {
							data.param[item.name] = item.value;
						}
					});
				}
            },
            "columns": [
                { "data": "contract_no", "class": 'text-left' },
                { "data": "invoice_no","class": 'text-left' },
                { "data": "dvt_no","class": 'text-left',
                    "render": function( data, type, row, meta ) {
                        return '<a class="edit" href="<?php echo base_url(); ?>inv_pl_print/index/'+ row.dvt_no +'" style="color:#428bca;">'
                            + row.dvt_no
                            + '</a>';
                    }
                },
                { "data": "times", "render": function( data, type, row, meta ) {
                    if(row.times_count > 1 || row.times > 1) {
                        return row.times;
                    }
                    return '';
                }},
                { "data": "passage_date"},
                { "data": "delivery_require_date"},
                { "data": "official_delivery_date", "render": function ( data, type, row, meta ) {
                    if (row.edit_mode) {
                        var status = false;
                        if(row.status == '015') {
                            status = true;
                        }
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('value', formatDate(row.official_delivery_date))
                            .attr('class', 'form-control datatable-input date')
                            .attr('name', 'official_delivery_date')
                            .attr('disabled', status)
                            .attr('maxlength', 10)
                            .css('text-align', 'center')
                            .css('max-width', '110px')
                        return $input[0].outerHTML;
                    }
                    return data;
                }},
                { "data": "payment_date", "render": function ( data, type, row, meta ) {
                    if (row.edit_mode) {
                        var status = false;
                        if(row.status == '015') {
                            status = true;
                        }
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('value', formatDate(row.payment_date))
                            .attr('class', 'form-control datatable-input date')
                            .attr('name', 'payment_date')
                            .attr('disabled', status)
                            .attr('maxlength', 10)
                            .css('text-align', 'center')
                            .css('max-width', '110px')
                        return $input[0].outerHTML;
                    }
                    return data;
                }},
                { "data": "submit_contract_date", "render": function ( data, type, row, meta ) {
                    if (row.edit_mode) {
                        var status = false;
                        if(row.status == '015') {
                            status = true;
                        }
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('value', formatDate(row.submit_contract_date))
                            .attr('class', 'form-control datatable-input date')
                            .attr('name', 'submit_contract_date')
                            .attr('disabled', status)
                            .attr('maxlength', 10)
                            .css('text-align', 'center')
                            .css('max-width', '110px')
                        return $input[0].outerHTML;
                    }
                    return data;
                }},
                { "data": "receipt_type","render": function ( data, type, row, meta ) {
                    if (row.edit_mode) {
                        var status = false;
                        if(row.status == '015') {
                            status = true;
                        }
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('value', row.receipt_type)
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'receipt_type')
                            .attr('disabled', status)
                            .css('text-align', 'right')
                            .css('max-width', '110px')
                        return $input[0].outerHTML;
                    }
                    return data;
                }},
                { "data": "customs_clearance_fee", "class": 'text-right', "render": function ( data, type, row, meta ) {
                    if (row.edit_mode) {
                        var status = false;
                        if(row.status == '015') {
                            status = true;
                        }
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('value', parseFloat(row.customs_clearance_fee))
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'customs_clearance_fee')
                            .attr('onkeydown', 'return checkQuantity(event)')
                            .attr('disabled', status)
                            .css('text-align', 'right')
                            .css('max-width', '110px')
                        return $input[0].outerHTML;
                    }
                    return numberWithCommas(data);
                }},
                { "data": "transport_fee", "class": 'text-right', "render": function ( data, type, row, meta ) {
                    if (row.edit_mode) {
                        var status = false;
                        if(row.status == '015') {
                            status = true;
                        }
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('value', parseFloat(row.transport_fee))
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'transport_fee')
                            .attr('onkeydown', 'return checkQuantity(event)')
                            .attr('disabled', status)
                            .css('text-align', 'right')
                            .css('max-width', '110px')
                        return $input[0].outerHTML;
                    }
                    return numberWithCommas(data);
                }},
                { "data": "company_name","class": 'text-left' },
                { "data": "seller","class": 'text-left' },
                { "data": "consignee","class": 'text-left' },
                { "data": "input_user_nm","class": 'text-left' },
                { "data": "vessel_flight" },
				{ "data": "sum_quantity", "class": 'text-right', render: function(data) {
                    // var el = "";
                    // Object.keys(data).forEach(function(key) {
                    //     el += el ==""? data[key]+" " + key : "," + data[key]+" " + key;
                    // });
					return  numberWithCommas(data);
				}},
				{ "data": "sum_amount", "class": 'text-right', render: function(data, type, row, meta ) {
					if(data){
                        return numberWithCommas(parseFloat(data).myToFixed(row.currency));
                    }
					return '';
				}},
				{ "data": "dif_amount", "class": 'text-right', render: function(data, type, row, meta ) {
                    if(data){
                        return numberWithCommas(parseFloat(data).myToFixed(row.currency));
                    }
					return '';
				}},

                { "data": "edit_user", "class": 'text-left' },
                { "data": "edit_date", "class": 'text-right' },
                { "data": "sales_status", "class": 'text-left' },
                { "data": "note","render": function ( data, type, row, meta ) {
                    if (row.edit_mode) {
                        var status = false;
                        if(row.status == '015') {
                            status = true;
                        }
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('value', row.note)
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'note')
                            .attr('disabled', status)
                            .css('text-align', 'right')
                            .css('max-width', '110px')
                        return $input[0].outerHTML;
                    }
                    return data;
                }},
                { "data": "action",
                    "render": function ( data, type, row, meta ) {
                        // check permission
                        var disableStatus = (permissionID != '002');
                        var html = '';
                        if (row.edit_mode) {
                            html += '<button data-event="save-item" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
                                + '<span class="fa fa-check"></span>'
                                + '</button>';
                            html += '<button data-event="close-item" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
                                + '<span class="fa fa-close"></span>'
                                + '</button>';
                        } else {
                            html += '<button data-event="edit-item" class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>" ' + (disableStatus ? 'disabled' : '') + '>'
                                + '<span class="fa fa-edit"></span>'
                                + '</button>';
                            html += '<button data-event="delete-item" class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>" ' + (disableStatus ? 'disabled' : '') + '>'
                                + '<span class="fa fa-trash"></span>'
                                + '</button>';
                        }
                        return html;
                    }
                }
            ],
            "createdRow": function(row, data, dataIndex) {
                $(row).find('td:eq(12)').attr('style', 'padding: 2px 5px 2px 5px !important');
            }
        });

        var fixedColumns = new $.fn.dataTable.FixedColumns(saleDt, {
            iLeftColumns: 1
        });

        $('#salesList').on("change", "#fixedColumns", function(){
            fixedColumns.s.iLeftColumns = $(this).val();
            fixedColumns.fnRedrawLayout();
        });
        $('#search_sales').on('click', function(){
            $('#frm_search_sales').submit();
        });

        // Click edit in datatable
        $('#sales_list').on('click', '[data-event="edit-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = saleDt.row($row).data();
            rowData.edit_mode = true;
            saleDt.row($row).data(rowData).invalidate();
            saleDt.draw( false ); 
        });
        
        // Click close in datatable
        $('#sales_list').on('click', '[data-event="close-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = saleDt.row($row).data();
            rowData.edit_mode = false;
            saleDt.row($row).data(rowData).invalidate();
            saleDt.draw( false ); 
        });

        // Click save in datatable
        $('#sales_list').on('click', '[data-event="save-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = saleDt.row($row).data();
            bootbox.confirm({
                title: "<?php echo ($this->lang->line('COMMON_I006'));?>",
                message: '<h4 style="color:red;">' + rowData.contract_no + '</h4>', 
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
                        requestData.contract_no = rowData.contract_no;
                        requestData.delivery_no = rowData.dvt_no;
                        requestData.delivery_date = rowData.order_date;
                        requestData.times = rowData.times;
                        requestData.kubun_update = '2';
                        requestData.official_delivery_date = $row.find("input[name='official_delivery_date']").val();
                        requestData.payment_date = $row.find("input[name='payment_date']").val();
                        requestData.customs_clearance_fee = $row.find("input[name='customs_clearance_fee']").val();
                        requestData.transport_fee = $row.find("input[name='transport_fee']").val();
                        requestData.receipt_type = $row.find("input[name='receipt_type']").val();
                        requestData.submit_contract_date = $row.find("input[name='submit_contract_date']").val();
                        requestData.note = $row.find("input[name='note']").val();
                        requestData.edit_date = rowData.edit_date;
                        // return;
                        $.ajax({
                            url: "<?php echo base_url('sales/save_sales')?>",
                            data: requestData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function(response) {
                            // return;
                            snackbarShow(response.message);
                            if(response.success == true) {
                                saleDt.ajax.reload();
                            }
                        });
                    }
                }
            });
        });

        // Click delete in datatable
        $('#sales_list').on('click', '[data-event="delete-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = saleDt.row($row).data();
            bootbox.confirm({
                title: "<?php echo ($this->lang->line('SES0020_I004'));?>",
                message: '<h4 style="color:red;">' + rowData.contract_no + '</h4>', 
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
                        requestData.contract_no = rowData.contract_no;
                        requestData.delivery_no = rowData.dvt_no;
                        requestData.delivery_date = rowData.order_date;
                        requestData.times = rowData.times;
                        requestData.edit_date = rowData.edit_date;
                        // return;
                        $.ajax({
                            url: "<?php echo base_url('sales/delete_sales')?>",
                            data: requestData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function(response) {
                            // return;
                            snackbarShow(response.message);
                            if(response.success == true) {
                                saleDt.ajax.reload();
                            }
                        });
                    }
                }
            });
        });
    }

</script>
