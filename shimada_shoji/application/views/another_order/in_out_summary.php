<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title; ?></h2>
				<ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button id="showTable" type="button" class="btn btn-primary">
                            <i class="fa fa-list"></i>
                        </button>
                    </li>
					<li>
                        <button id="showTableTest" type="button" class="btn btn-info">
                            <i class="fa fa-indent"></i>
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div id="div_in_out_summary_list" style="display: block" class="x_content">
                <div class="table-responsive">
                    <table id="in_out_summary_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th><?php echo $this->lang->line('item_code');?></th>
                                <th><?php echo $this->lang->line('item_name');?></th>
                                <th><?php echo $this->lang->line('size');?></th>
                                <th><?php echo $this->lang->line('color');?></th>
                                <th>Qty in PO</th>
                                <th>PO on Way</th>
                                <th>Qty in DVT</th>
                                <th>Qty OK</th>
                                <th style="background-color: #ffff00;"><?php echo $this->lang->line('balance');?></th>
                                <th>Qty NG</th>
                                <th>Qty P/L</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
			<div id="div_in_out_summary_list2" style="display: none" class="x_content">
                <div class="table-responsive">
					<table id="in_out_summary_list2" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead>
						    <tr>
								<th>No</th>
                            	<th><?php echo $this->lang->line('item_code');?></th>
                            	<th><?php echo $this->lang->line('item_name');?></th>
                            	<th><?php echo $this->lang->line('size');?></th>
                            	<th><?php echo $this->lang->line('color');?></th>
								<th colspan="4" id="last_th">Qty in PO</th>
                                <th colspan="3" id="ng_th">Qty NG</th>
                            </tr>
							<tr style="height: 0px;">
                            	<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
								<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
                            	<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
                            	<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
                            	<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
								<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
								<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
								<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
                                <th style="padding: 0px !important; border-bottom-width: 0px;"></th>
								<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
								<th style="padding: 0px !important; border-bottom-width: 0px;"></th>
                                <th style="padding: 0px !important; border-bottom-width: 0px;"></th>                                
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
    var tableSumDt = $('#in_out_summary_list').DataTable({
        "paging": true,
        "filter": true,
        "ordering": true,
        "scrollX" :true,
        "data": <?php if(isset($in_out_list) && $in_out_list != null) {
                        echo json_encode($in_out_list);
                    } else {
                        echo '[]';
                    } ?>,
        "columns": [
            { "data": "no", render: function( data, type, row, meta ){
                    return '' + (meta.row + 1);
            }},
            { "data": "item_code", "className": "text-left" },
            { "data": "item_name", "className": "text-left" },
            { "data": "size", "className": "text-right" },
            { "data": "color", "className": "text-right" },
            { "data": "sum_pv", "className": "text-right", render: function( data, type, row, meta ){
                return numberWithCommas(row.sum_pv);
            }},
            { "data": "poonway", "className": "text-right", render: function( data, type, row, meta ){
                return numberWithCommas(row.poonway);// + ' ' + unit ;
            }},
            { "data": "sum_dvt", "className": "text-right", render: function( data, type, row, meta ){
                return numberWithCommas(row.sum_dvt);
            }},
            { "data": "sum_store", "className": "text-right", render: function( data, type, row, meta ){
                return numberWithCommas(row.sum_store);
            }},
            { "data": "balance", "className": "text-right", render: function( data, type, row, meta ){
                // console.log(row.balance);
                var balance = numberWithCommas(row.balance);
                if(row.balance < 0) {
                    html = '<p style="color: red;margin-bottom:0px;">'
                            + balance
                            +'</p>';
                    return html;
                } else {
                    if(row.balance != '') {
                        return balance;
                    } else {
                        return '';
                    }
                }
            }},
            { "data": "sum_ng", "className": "text-right", render: function( data, type, row, meta ){
                return numberWithCommas(row.sum_ng);
            }},
            { "data": "sum_pl", "className": "text-right", render: function( data, type, row, meta ){
                return numberWithCommas(row.sum_pl);
            }},
        ]
	});

    var in_out_type2 = $('#in_out_summary_list2').DataTable({
        "data": [],
        "paging": true,
        "filter": true,
        "ordering": false,
        "dom": "l<'btnfilter'>ftip",
        "scrollX" : true,
        "drawCallback": function(setting) {
            var columns = $('#show_column').val();
            $(".btnfilter").css({display: "inline"});
            $(".btnfilter").html(`<select name="show_column" id="show_column" class="form-control">
                <option value="PV">Qty in PO</option>
                <option value="POONWAY">PO on Way</option>
                <option value="OK-NG">Qty in Warehouse</option>
                <option value="P/L">Qty in P/L</option>
            </select>`);
            if(columns != undefined) {
                $('#show_column').val(columns);
            }
        },
        "ajax": {
            "url": '<?php echo base_url('another_order/getIOSummary')?>',
            "data": function(data) {
                data.filter = $('#show_column').val();
                console.log(data);
            },
        },
        "columns": [
            { "data": "no", "name": "no", render: function( data, type, row, meta ){
                return meta.row + 1;
            }},
            { "data": "item_code", "className": "text-left", "name": "item_code" },
            { "data": "item_name", "className": "text-left", "name": "item_name" },
            { "data": "size", "className": "text-center", "name": "size" },
            { "data": "color", "className": "text-center", "name": "color" },
            { "data": "total", "className": "text-right", "name": "total", render: function( data, type, row, meta ){
                return numberWithCommas(row.total);
            }},
            { "data": "quantity", "className": "text-right", "name": "quantity", render: function( data, type, row, meta ){
                return numberWithCommas(row.quantity);
            }},
            { "data": "names", "name": "names", "className": "text-left" },
            { "data": "dates", "name": "dates", render: function( data, type, row, meta ){
                return row.dates;
            }},
            { "data": "sum_ng", "className": "text-right", "name": "sum_ng", render: function( data, type, row, meta ){
                return numberWithCommas(row.sum_ng);
            }},
            { "data": "quantity_ng", "className": "text-right", "name": "quantity_ng", render: function( data, type, row, meta ){
                return numberWithCommas(row.quantity_ng);
            }},
            { "data": "names_ng", "name": "names_ng", "className": "text-left" },
        ],
        // "rowsGroup": [
        //     "item_code:name",
        //     "item_name:name",
        //     "size:name",
        //     "color:name",
        //     "no:name",
        //     "total:name",
        //     "sum_ng:name",
        //     "names_ng:name",
        //     "names:name",
        //     "quantity:name",
        //     "quantity_ng:name",
        //     "dates:name",
        // ],
    });

     var column = $('#show_column').val();
    if(column == 'OK-NG') {
        document.getElementById('last_th').colSpan = '3';

        $('#last_th').text(' Qty ' + 'OK');
        in_out_type2.columns( [ 9,10,11 ] ).visible( true );
        in_out_type2.columns( [ 8 ] ).visible( false );
        in_out_type2.columns.adjust().draw( false );
    } else {
        if(column == 'P/L'){
            document.getElementById('last_th').colSpan = '3';

            $('#last_th').text(' Qty '+ column);
            in_out_type2.columns( [ 8,9,10,11 ] ).visible( false );
            in_out_type2.columns.adjust().draw( false );
        } else {
            document.getElementById('last_th').colSpan = '4';

            $('#last_th').text(' Qty ' + 'PO');
            in_out_type2.columns( [ 9,10,11 ] ).visible( false );
            in_out_type2.columns( [ 8 ] ).visible( true );
            in_out_type2.columns.adjust().draw( false );
        }
    }

    $('#div_in_out_summary_list2').on("change",'#show_column', function(){
        in_out_type2.ajax.reload(null, false);
        in_out_type2.page( 0 ).draw( false );
        var column_name = $(this).val();
        if(column_name == 'OK-NG') {
            document.getElementById('last_th').colSpan = '3';

            $('#last_th').text(' Qty ' + 'OK');
            in_out_type2.columns( [ 9,10,11 ] ).visible( true );
            in_out_type2.columns( [ 8 ] ).visible( false );
            in_out_type2.columns.adjust().draw( false );
        } else {
            if(column_name == 'P/L'){
                document.getElementById('last_th').colSpan = '3';

                $('#last_th').text(' Qty '+ column_name);
                in_out_type2.columns( [ 8,9,10,11 ] ).visible( false );
                in_out_type2.columns.adjust().draw( false );
            }else if(column_name == 'POONWAY'){
                document.getElementById('last_th').colSpan = '4';

                $('#last_th').text(' Qty '+ 'PO on Way');
                in_out_type2.columns( [ 9,10,11 ] ).visible( false );
                in_out_type2.columns( [ 8 ] ).visible( true );
                in_out_type2.columns.adjust().draw( false );
            }  else {
                document.getElementById('last_th').colSpan = '4';

                $('#last_th').text(' Qty ' + 'PO');
                in_out_type2.columns( [ 9,10,11 ] ).visible( false );
                in_out_type2.columns( [ 8 ] ).visible( true );
                in_out_type2.columns.adjust().draw( false );
            }
        }
    });
	
	$('#showTableTest').click(function () {
		$('#div_in_out_summary_list2').show();
		in_out_type2.draw();
		$('#div_in_out_summary_list').hide();
	});

	$('#showTable').click(function () {
		$('#div_in_out_summary_list2').hide();
		$('#div_in_out_summary_list').show();
		tableSumDt.draw();
	});
}

</script>
