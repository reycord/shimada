<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    <?php echo $title; ?>
                </h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button 
                            onclick="window.location.href='<?php echo base_url('analysis'); ?>'"
                            type="button" 
                            id="clear" 
                            class="btn btn-info" 
                            style="float: right;"
                        ><i class="fa fa-refresh"></i><?php echo $this->lang->line('clear');?>
                        </button>
                    </li>
                    <li>
                        <button 
                            type="button" 
                            id="draw_chart" 
                            class="btn btn-primary" 
                            style="float: right;"
                        ><?php echo $this->lang->line('display');?>
                        </button>
                    </li>
                    <li>
                        <form id="frm_export_excel">
                            <input type="hidden" name="form_data" value="" />
                            <button 
                                type="button" 
                                id="export_excel" 
                                class="btn btn-success" 
                                style="float: right;"
                            ><i class="fa fa-file-excel-o"></i>Excel 
                            </button>
                        </form>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="panel panel-default">
                    <div class="panel-body" style="padding-bottom: 0px;">
                        <form id="draw_chart_frm" class="form-horizontal form-label-left">
                            <div class="form-group">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('time');?>
                                    </label>
                                </div>
                                <div class="col-md-7 col-sm-7 col-xs-7 no-padding-left">
                                    <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left">
                                        <div class="input-group hasDatepicker date">
                                            <input 
                                                type='text' 
                                                class="form-control" 
                                                name='date_from'
                                                id="date_from" 
                                                maxlength="10"
                                            />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <label 
                                        class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right no-padding-top" 
                                        for="created_to"
                                        style="text-align: center; font-size: 20px;">&sim;</label>
                                    <div class="col-md-4 col-sm-4 col-xs-4 no-padding-right">
                                        <div class="input-group hasDatepicker date">
                                            <input 
                                                type='text' 
                                                class="form-control" 
                                                name='date_to' 
                                                id="date_to"
                                                maxlength="10"
                                                value="<?php echo date_format(date_create(), 'd M, Y');?>"
                                            />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <label class="control-label form-title" for="horizontal_order">
                                        <?php echo $this->lang->line('horizontal_axis')."(Order Out)";?><span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 no-padding-left">
                                    <select 
                                        class="form-control validate[required]" 
                                        multiple 
                                        name="horizontal_order" 
                                        id="horizontal_order"
                                    >
                                        <option value="jp_code">Code_JP</option>
                                        <option selected value="item_code">Item Code</option>
                                        <option value="size">Size</option>
                                        <option value="color">Color</option>
                                        <option value="short_name">Supplier</option>
                                        <option value="salesman">Salesman</option>
                                        <option value="odr_recv_no">PO</option>
                                    </select>
                                    <div field="horizontal" style="color:red; text-align:left;"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <label class="control-label form-title" for="horizontal_order_receive">
                                        <?php echo $this->lang->line('horizontal_axis')."(Order Receive)";?><span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 no-padding-left">
                                    <select 
                                        class="form-control validate[required]" 
                                        multiple 
                                        name="horizontal_order_receive" 
                                        id="horizontal_order_receive"
                                    >
                                        <option value="jp_code">Code_JP</option>
                                        <option selected value="item_code">Item Code</option>
                                        <option value="size">Size</option>
                                        <option value="color">Color</option>
                                        <option value="short_name">Customer</option>
                                        <option value="staff">Salesman</option>
                                        <option value="order_receive_no">PV/PO</option>
                                    </select>
                                    <div field="horizontal" style="color:red; text-align:left;"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <label class="control-label form-title" for="vertical">
                                        <?php echo $this->lang->line('vertical_axis');?><span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left">
                                    <select 
                                        class="form-control validate[required]" 
                                        multiple 
                                        name="vertical" 
                                        id="vertical"
                                    >
                                        <option selected value="amount">Amount</option>
                                        <option value="quantity">Quantity</option>
                                    </select>
                                    <div field="vertical" style="color:red; text-align:left;"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-3 col-sm-3 col-xs-3">
                                    <label class="control-label form-title" for="currency" ><?php echo $this->lang->line('currency'); ?>
                                    </label>
                                </div>
                                <div class="col-md-9 col-sm-9 col-xs-9 no-padding-left">
                                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
                                        <select class="form-control" id="currency" name="currency">
                                            <option value="USD">USD</option>
                                            <option value="JPY">JPY</option>
                                            <option value="VND">VND</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right">
                                        <label class="control-label form-title" for="usd_vnd" ><?php echo str_repeat("&nbsp;", 15);?>USD→VND <?php echo $this->lang->line('rate'); ?><span class="required">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                        <input type="text" class="form-control validate[required, min[1]]" id="usd_vnd" name="usd_vnd" value="<?php echo $rateList[1]['rate']?>" onkeydown="return checkQuantity(event)">
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
                                    </div>
                                    <div class="col-md-3 col-sm-2 col-xs-2 no-padding-right">
                                        <label class="control-label form-title" for="jpy_vnd" ><?php echo str_repeat("&nbsp;", 15);?>JPY→VND <?php echo $this->lang->line('rate'); ?><span class="required">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                        <input type="text" class="form-control validate[required, min[1]]" id="jpy_vnd" name="jpy_vnd" value="<?php echo $rateList[2]['rate']?>" onkeydown="return checkQuantity(event)">
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div id="orderChart">
                    </div>
                    <div id="orderReceiveChart">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function () {
        $('#horizontal_order').select2({ width: "80%" });
        $('#horizontal_order_receive').select2({ width: "80%" });
        $('#vertical').select2({ width: "80%" });
        
        /** Export excel */
        $('#export_excel').click(function(){
            var validate = $('#draw_chart_frm').validationEngine('validate');
            if(!validate) { return; }
            var url = "<?php echo base_url('analysis/excel');?>";
            var requestDt = {
                "horizontal_order": $('#horizontal_order').val(),
                "horizontal_order_receive": $('#horizontal_order_receive').val(),
                "vertical": $('#vertical').val(),
                "currency": $('#currency').val(),
                "usd_vnd": $('#usd_vnd').val(),
                "jpy_vnd": $('#jpy_vnd').val()
            };
            if($('#date_from').val() != "" && $('#date_from').val() != undefined) {
                requestDt.date_from = $('#date_from').val();
            }
            if($('#date_to').val() != "" && $('#date_to').val() != undefined) {
                requestDt.date_to = $('#date_to').val();
            }
            $('#frm_export_excel input[name="form_data"]').val(JSON.stringify(requestDt));
            $("#frm_export_excel").attr("action", url).submit();
        });

        /** Click button Draw Chart */
        $('#draw_chart').click(function(event){
            var validate = $('#draw_chart_frm').validationEngine('validate');
            if(!validate) { return; }
            var requestDt = {
                "horizontal_order": $('#horizontal_order').val(),
                "horizontal_order_receive": $('#horizontal_order_receive').val(),
                "vertical": $('#vertical').val(),
                "currency": $('#currency').val(),
                "usd_vnd": $('#usd_vnd').val(),
                "jpy_vnd": $('#jpy_vnd').val()
            };
            if($('#date_from').val() != "" && $('#date_from').val() != undefined) {
                requestDt.date_from = $('#date_from').val();
            }
            if($('#date_to').val() != "" && $('#date_to').val() != undefined) {
                requestDt.date_to = $('#date_to').val();
            }
            console.log(requestDt);
            $.ajax({
                url: "<?php echo base_url('analysis/getChartData')?>",
                data: requestDt,
                dataType: "JSON",
                type: "GET",
                success: function(response){
                    console.log(response);
                    document.getElementById('orderChart').style.height = "500px";
                    document.getElementById('orderReceiveChart').style.height = "500px";
                    if(response.success){
                        var data = response.data;

                        var orderOutChart = echarts.init(document.getElementById('orderChart'));
                        var orderReceiveChart = echarts.init(document.getElementById('orderReceiveChart'));

                        if(data.data_series_amount.length >= 8 || data.data_series_quantity.length >= 8) {
                            var data_zoom_order = [{
                                type: "slider",
                                height: 20
                            }];
                        } else {
                            var data_zoom_order = [{
                                show: false
                            }];
                        }
                        
                        if(data.data_series_amount_receive.length >= 8 || data.data_series_quantity_receive.length >= 8) {
                            var data_zoom_order_receive = [{
                                type: "slider",
                                height: 20
                            }];
                        } else {
                            var data_zoom_order_receive = [{
                                show: false
                            }];
                        }

                        if(data.data_legend.length == 1) {
                            if(data.data_legend[0] == "amount") {
                                var yAxis = [{
                                    name: requestDt.currency,
                                    type : 'value'
                                }];
                                var series = [{
                                    name: data.data_legend[0],
                                    type: 'bar',
                                    barMaxWidth: 25,
                                    sort: 'descending',
                                    data: data.data_series_amount
                                }];
                            } else {
                                var yAxis = [{
                                    type : 'value',
                                }];
                                var series = [{
                                    name: data.data_legend[0],
                                    type: 'bar',
                                    barMaxWidth: 25,
                                    sort: 'descending',
                                    data: data.data_series_quantity
                                }];
                            }
                        } else {
                            var yAxis = [
                                {
                                    name: requestDt.currency,
                                    type : 'value'
                                },
                                {
                                    type : 'value',
                                }
                            ];
                            var series = [
                                {
                                    name:'amount',
                                    type:'bar',
                                    barMaxWidth: 25,
                                    sort: 'descending',
                                    data: data.data_series_amount
                                },
                                {
                                    name:'quantity',
                                    type:'line',
                                    yAxisIndex: 1,
                                    data: data.data_series_quantity
                                }
                            ];
                        }

                        if(data.data_legend.length == 1) {
                            if(data.data_legend[0] == "amount") {
                                var yAxisReceive = [{
                                    name: requestDt.currency,
                                    type : 'value'
                                }];
                                var seriesReceive = [{
                                    name: data.data_legend[0],
                                    type: 'bar',
                                    barMaxWidth: 25,
                                    sort: 'descending',
                                    data: data.data_series_amount_receive
                                }];
                            } else {
                                var yAxisReceive = [{
                                    type : 'value',
                                }];
                                var seriesReceive = [{
                                    name: data.data_legend[0],
                                    type: 'bar',
                                    barMaxWidth: 25,
                                    sort: 'descending',
                                    data: data.data_series_quantity_receive
                                }];
                            }
                        } else {
                            var yAxisReceive = [
                                {
                                    name: requestDt.currency,
                                    type : 'value'
                                },
                                {
                                    type : 'value',
                                }
                            ];
                            var seriesReceive = [
                                {
                                    name:'amount',
                                    type:'bar',
                                    barMaxWidth: 25,
                                    sort: 'descending',
                                    data: data.data_series_amount_receive
                                },
                                {
                                    name:'quantity',
                                    type:'line',
                                    yAxisIndex: 1,
                                    data: data.data_series_quantity_receive
                                }
                            ];
                        }
                        
                        // specify chart configuration item and data
                        var optionOrderOutChart = {
                            tooltip : {
                                trigger: 'axis'
                            },
                            toolbox: {
                                feature : {
                                    saveAsImage : {
                                        title: "Save As PNG"
                                    }
                                }
                            },
                            title: {
                                padding: 20,
                                textAlign: 'left',
                                text: 'Orders Out Chart',
                            },
                            grid: {
                                y: 100,
                                y2: 135
                            },
                            calculable : true,
                            legend: {
                                padding: 50,
                                data: data.data_legend,
                            },
                            dataZoom: data_zoom_order,
                            xAxis : [
                                {   
                                    type : 'category',
                                    data : data.data_xaxis
                                }
                            ],
                            yAxis : yAxis,
                            series : series 
                        };

                        var optionOrderReceiveChart = {
                            tooltip : {
                                trigger: 'axis'
                            },
                            toolbox: {
                                feature : {
                                    saveAsImage : {
                                        title: "Save As PNG"
                                    }
                                }
                            },
                            title: {
                                padding: 20,
                                textAlign: 'left',
                                text: 'Orders Receive Chart',
                            },
                            grid: {
                                y: 100,
                                y2: 135
                            },
                            calculable : true,
                            legend: {
                                padding: 50,
                                data: data.data_legend,
                            },
                            dataZoom: data_zoom_order_receive,
                            xAxis : [
                                {   
                                    type : 'category',
                                    data : data.data_xaxis_receive
                                }
                            ],
                            yAxis : yAxisReceive,
                            series : seriesReceive 
                        };

                        // use configuration item and data specified to show chart
                        orderOutChart.setOption(optionOrderOutChart);
                        orderReceiveChart.setOption(optionOrderReceiveChart);
                    }
                },
                error: function(){
                    alert('error');
                }
            });
        });
	};
</script>
