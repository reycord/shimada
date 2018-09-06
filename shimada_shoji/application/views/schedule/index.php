<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title ?></h2>
                <!-- <ul class="nav navbar-right panel_toolbox">
					<li><button id="btnSave" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('save');?></button>
					</li>
                </ul> -->
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
				<div class="table-responsive">
					<table id="scheduleTable" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('order_received_no');?></th>
								<th><?php echo $this->lang->line('partition_no');?></th>
								<th><?php echo $this->lang->line('sales_man');?></th>
								<th><?php echo $this->lang->line('status');?></th>
								<th><?php echo $this->lang->line('wish_delivery_date');?></th>
								<th><?php echo $this->lang->line('plan_inspection_date');?></th>
								<th><?php echo $this->lang->line('plan_packing_date');?></th>
								<th><?php echo $this->lang->line('plan_delivery_date');?></th>
								<th><?php echo $this->lang->line('action');?></th>
								<!-- <th style="display:none;"><?php echo $this->lang->line('edit');?> Flg</th> -->
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
	window.onload = function () {
		$('#scheduleTable').DataTable({
            "data": <?php echo json_encode($data_table); ?>,
            "paging": true,
            "filter": true,
            "ordering": false,
            "scrollX" :true,
            "drawCallback": function() {
				// datepicker
				$(".date").datepicker({
						todayHighlight: true,
						format: "d M, yyyy",
						autoclose: true
				});
			},
            "columns": [
							{ "data": "order_receive_no",class : 'text-left', render: function( data, type, row, meta ){
									return '<a class="edit" href="<?php echo base_url(); ?>order_received/index/'+ row.order_receive_no +'" style="color:#428bca;">'
									+ row.order_receive_no
									+ '</a>';
										}},
											{ "data": "partition_no",class : 'text-center', render: function( data, type, row, meta ){
									var count = 0;
									<?php foreach($data_table as $ele): ?>
										if(row.order_receive_no == '<?php echo $ele['order_receive_no']; ?>' && 
											row.order_receive_date == '<?php echo $ele['order_receive_date']; ?>') {
											count++;
										}
									<?php endforeach ?>
									if(count > 1) {
										return data;
									} else {
										return '';
									}
									return row.partition_no;
							}},
							{ "data": "staff",class : 'text-left', render: function( data, type, row, meta ){
									return row.staff;
							}},
							{ "data": "status", render: function( data, type, row, meta ){
									return row.odre_status;
							}},
							{ "data": "delivery_date", render: function( data, type, row, meta ){
									return row.delivery_date;
							}},
							{ "data": "plan_inspect_date", render: function( data, type, row, meta ){
									if(row.edit_mode){
										var $input = $('<input>')
												.attr('type', 'text')
												.attr('class', 'form-control hasDatepicker date')
												.attr('name', 'plan_inspect_date')
												.attr('value', formatDate(row.plan_inspect_date))
												.css('max-width', '100px')
												.css('height', '25px');
										return $input[0].outerHTML;
									}
									if(row.plan_inspect_date > row.delivery_date) {
									return '<p style="color:red;margin-bottom:0px;">'+row.plan_inspect_date+'</p>'
								} else {
									return row.plan_inspect_date;
								}

							}},
							{ "data": "plan_packing_date", render: function( data, type, row, meta ){
								if(row.edit_mode){
									var $input = $('<input>')
											.attr('type', 'text')
											.attr('class', 'form-control hasDatepicker date')
											.attr('name', 'plan_packing_date')
											.attr('value', formatDate(row.plan_packing_date))
											.css('max-width', '100px')
											.css('height', '25px');
									return $input[0].outerHTML;
								}
								if(row.plan_packing_date > row.delivery_date) {
									return '<p style="color:red;margin-bottom:0px;">'+row.plan_packing_date+'</p>'
								} else {
									return row.plan_packing_date;
								}
							}},
							{ "data": "plan_delivery_date", render: function( data, type, row, meta ){
								if(row.edit_mode){
									var $input = $('<input>')
											.attr('type', 'text')
											.attr('class', 'form-control hasDatepicker date')
											.attr('name', 'plan_delivery_date')
											.attr('value', formatDate(row.plan_delivery_date))
											.css('max-width', '100px')
											.css('height', '25px');
									return $input[0].outerHTML;
								}
								if(row.plan_delivery_date > row.delivery_date) {
									return '<p style="color:red;margin-bottom:0px;">'+row.plan_delivery_date+'</p>'
								} else {
									return row.plan_delivery_date;
								}
							}},
							{ "data": "action", render: function( data, type, row, meta ){
					<?php $permissionList = explode(",", PERMISSION_MANAGER);?>
					<?php $disabledFlg = in_array($user['permission_id'] ,$permissionList);?>
					var disabled_edit = row.accpt_flg == 2 ? 'disabled' : '';
					var disabled = (row.accpt_flg == 1 || row.accpt_flg == 2) ? 'disabled' : '';
					var confirmDisabled = (row.accpt_flg != 1 <?php echo (!$disabledFlg ? ' || true' : '')?> ) ? 'disabled' : '';
					var html = '';
					if(row.edit_mode){
							html += '<a data-event="save-item" class="btn btn-sm btn-success" title="<?php echo $this->lang->line('save'); ?>" style="margin-right: 3px;">'
								+ '<span class="fa fa-check"></span>'
								+ '</a>';
							html += '<a data-event="close-item" class="btn btn-sm btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
								+ '<span class="fa fa-close"></span>'
								+ '</a>';
						return html;
					}else{
						html += '<button data-event="edit-item" class="btn btn-sm '+(disabled_edit ? 'btn-default' : 'btn-primary' )+'" title="<?php echo $this->lang->line('edit'); ?>" '+ disabled_edit +' style="margin-right: 2px;">'
								+ '<span class="fa fa-edit"></span>'
								+ '</button>';
						html += '<button data-event="accept-item" class="btn '+(disabled ? 'btn-default' : 'btn-warning' )+' btn-sm no-padding-top no-padding-bottom" '+ disabled +' title="<?php echo $this->lang->line('apply'); ?>"  style="margin-right: 2px;">'
								+ '<span>Apply</span>'
								+ '</button>';
						html += '<button data-event="confirm-item" class="btn '+( row.accpt_flg == 2 ? 'btn-default' : 'btn-danger') + ' btn-sm no-padding-top no-padding-bottom" '+ confirmDisabled +' title="<?php echo $this->lang->line('confirm'); ?>" style="margin-right: 2px;">'
								+ '<span>Confirm</span>'
								+ '</button>';
						return html;
					}
                }},
            ],
			"createdRow": function(row, data, dataIndex) {
			$(row).find('td:eq(8)').attr('style', 'padding: 2px 5px 2px 5px !important');
			if(data.kubun == '2'){
							$(row).css('background-color', 'rgb(255, 232, 217)');
					}
			}
		});

		// Handle button edit item
		$('#scheduleTable').on('click', '[data-event="edit-item"]', function(){
				var $row = $(this).parents('tr');
				var rowData = $('#scheduleTable').DataTable().row($row).data();
				rowData.edit_mode = true;
				$('#scheduleTable').DataTable().row($row).data(rowData).invalidate();
				$('#scheduleTable').DataTable().draw(false);
		});

		// Handle button save item
		$('#scheduleTable').on('click', '[data-event="save-item"]', function(){
			var $row 			= $(this).parents('tr');
			var rowData 		= $('#scheduleTable').DataTable().row($row).data();
			// set data for edit 
            var requestData = {};
			requestData.order_receive_no 		= rowData.order_receive_no,
			requestData.partition_no 			= rowData.partition_no,
			requestData.order_receive_date 		= rowData.order_receive_date,
			// requestData.delivery_date 		= $row.find('input[name="delivery_date"]').val();
			requestData.plan_inspect_date 		= $row.find('input[name="plan_inspect_date"]').val();
			requestData.plan_packing_date 		= $row.find('input[name="plan_packing_date"]').val();
			requestData.plan_delivery_date 		= $row.find('input[name="plan_delivery_date"]').val();
			requestData.edit_date 				= rowData.edit_date;

			bootbox.confirm({
				title: "<?php echo ($this->lang->line('SKS0010_E002'))?>",
				message: '<h4 style="color:blue;">' + rowData.order_receive_no + '</h4>', 
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
							url: "<?php echo base_url('schedule/saveSchedule')?>",
							data: requestData,
							type: 'post',
							dataType: 'json'
						}).done(function(response) {
							snackbarShow(response.message);
							if(response.success == true) {
								rowData = response.data[0];
								if(rowData.edit_mode) {
									rowData.edit_mode = false;
								}
								$('#scheduleTable').DataTable().row($row).data(rowData).invalidate();
								$('#scheduleTable').DataTable().draw(false);
							}else{
								$('#scheduleTable').DataTable().draw(false);
							}
						});
					}
				}
			});
		});

		// Handle button close item
		$('#scheduleTable').on('click', '[data-event="close-item"]', function(){
			var $row = $(this).parents('tr');
			var rowData = $('#scheduleTable').DataTable().row($row).data();
			rowData.edit_mode = false;
			$('#scheduleTable').DataTable().row($row).data(rowData).invalidate();
			$('#scheduleTable').DataTable().draw(false); 
		});
		
		// Handle button accept
		$('#scheduleTable').on('click', '[data-event="accept-item"]', function(){
			// If button disabled => do nothing
			if(!$(this).attr('disabled')){
				var $row = $(this).parents('tr'),
					rowData = $('#scheduleTable').DataTable().row($row).data();
			if(rowData.plan_inspect_date == null || rowData.plan_inspect_date == '' || rowData.plan_packing_date == null || rowData.plan_packing_date == '' || rowData.plan_delivery_date == null || rowData.plan_delivery_date == ''){
				snackbarShow("<?php echo $this->lang->line('SKS0010_E001');?>");
				return;
			}
				var requestData = {
					order_receive_no 		: rowData.order_receive_no,
					partition_no 			: rowData.partition_no,
					order_receive_date 		: rowData.order_receive_date,
					edit_date 		: rowData.edit_date,
				};
				var temp = this;
				bootbox.confirm({
					title: "<?php echo ($this->lang->line('SKS0010_E003'))?>",
					message: '<h4 style="color:blue;">' + rowData.order_receive_no + '</h4>', 
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
								url: "<?php echo base_url('schedule/acceptItem')?>",
								data: requestData,
								type: 'post',
								dataType: 'json'
							}).done(function(response) {
								snackbarShow(response.message);
								if(response.success == true) { 
									rowData = response.data[0];
									$('#scheduleTable').DataTable().row($row).data(rowData).invalidate();
									$('#scheduleTable').DataTable().draw(false);
									$(temp).attr('disabled', 'disabled');
									$(temp).removeClass('btn-warning').removeClass('btn-danger').addClass('btn-default');
									<?php if($disabledFlg):?>
									$row.find('button[data-event="confirm-item"]').removeClass('btn-default').addClass('btn-danger');
									$row.find('button[data-event="confirm-item"]').attr('disabled', false);
									<?php endif ?>
								}else{
									$('#scheduleTable').DataTable().draw(false);
								}
							});
						}
					}
				});
			}
		});

		$('#scheduleTable').on('click', '[data-event="confirm-item"]', function(){
			// If button disabled => do nothing
			if(!$(this).attr('disabled')){
				var $row = $(this).parents('tr'),
					rowData = $('#scheduleTable').DataTable().row($row).data();
				var requestData = {
					order_receive_no 		: rowData.order_receive_no,
					partition_no 			: rowData.partition_no,
					order_receive_date 		: rowData.order_receive_date,
					edit_date 		: rowData.edit_date,
				};
				var temp = this;
				bootbox.confirm({
					title: "<?php echo ($this->lang->line('SKS0010_E004'))?>",
					message: '<h4 style="color:blue;">' + rowData.order_receive_no + '</h4>', 
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
								url: "<?php echo base_url('schedule/confirmOrderReceived')?>",
								data: requestData,
								type: 'post',
								dataType: 'json'
							}).done(function(response) {
								snackbarShow(response.message);
								if(response.success == true) { 
									$(temp).attr('disabled', 'disabled');
									$(temp).removeClass('btn-danger').addClass('btn-default');
									$row.find('button[data-event="edit-item"]').removeClass('btn-primary').addClass('btn-default');
									$row.find('button[data-event="edit-item"]').attr('disabled', true);
								}
							});
						}
					}
				});
			}
		});
 
		// Handle button Save
		// $('#btnSave').click(function () {
		// 	// find anything button save_edit
		// 	var check_row = $('#scheduleTable').find('a[data-event=save-item]').length;
		// 	if(check_row > 0 ){
		// 		bootbox.alert("Please click save item before save");
		// 		return;
		// 	}

		// 	var data_table = $('#scheduleTable').DataTable().data().toArray();
		// 	var requestData = {};
		// 	var url = "<?php //echo base_url();?>schedule/saveSchedule";
		// 	if(data_table.length > 0){
		// 		requestData['data_table'] = data_table;
		// 		$.post(url, requestData, function (response) { 
		// 			var responseJson = JSON.parse(response);
		// 			snackbarShow(responseJson.message);
		// 			setTimeout(function(){location.reload()},1500);
		// 		});
		// 	} 
		// });
	}
</script>
