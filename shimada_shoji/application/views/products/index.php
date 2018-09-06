<div class="row">
	<form class="form-horizontal form-label-left" action="" method="get" id="frm_search_product">
		<input type="hidden" name="search" value="1" />
		<div class="form-group no-padding-right">
			<div class="form-group">
				<label class="control-label col-md-1 col-sm-4 col-xs-4"><?php echo $this->lang->line('item_code'); ?></label>
				<div class="col-md-2 col-sm-8 col-xs-8 has-clear has-feedback">
					<input 
						type="text" 
						class="form-control text-uppercase" 
						id="item_code"
						name="item_code"
						maxlength="15"
						value="<?php echo trim($this->input->get('item_code')) ?>"
					>
					<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
				</div>
				<label class="control-label col-md-1 col-sm-4 col-xs-4 no-padding-left"><?php echo $this->lang->line('item_name'); ?></label>
				<div class="col-md-2 col-sm-8 col-xs-8 has-clear has-feedback">
					<input 
						type="text" 
						class="form-control" 
						name="item_name"
						maxlength="100"
						value="<?php echo $this->input->get('item_name') ?>"
					>
					<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
				</div>
				<label class="control-label col-md-1 col-sm-3 col-xs-3 no-padding-left"><?php echo $this->lang->line('vender'); ?></label>
				<div class="col-md-2 col-sm-3 col-xs-3 no-padding-left no-padding-right has-clear has-feedback">
					<input class="form-control text-uppercase" type="text" value="<?php echo $this->input->get('vendor') ?>" id="vendor" name="vendor" />
					<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
				</div>
				<label class="control-label col-md-2 col-sm-8 col-xs-8 no-padding-left no-padding-right"><?php echo $this->lang->line('end_sales'); ?></label>
				<div class="col-md-1 col-sm-1 col-xs-1">
					<p style="padding: 5px;">
						<input type='hidden' value='0' name='end_of_sales'>
						<input
							type="checkbox"
							class="flat"
							name="end_of_sales"
							id="end_of_sales"
							value="1"
							<?php if ($this->input->get('end_of_sales') == '1') : ?>
								checked
							<?php endif ?>
						>
					</p>
				</div>
			</div>
			<div class="form-group" style="margin-bottom: 0px;">
				<label class="control-label col-md-1 col-sm-4 col-xs-4"><?php echo $this->lang->line('sales_man'); ?></label>
				<div class="col-md-2 col-sm-8 col-xs-8 has-clear has-feedback">
					<input class="form-control text-uppercase" type="text" value="<?php echo $this->input->get('salesman') ?>" id="salesman" name="salesman" />
					<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
				</div>
				<label class="control-label col-md-1 col-sm-4 col-xs-4 no-padding-left"><?php echo $this->lang->line('customer'); ?></label>
				<div class="col-md-2 col-sm-8 col-xs-8 has-clear has-feedback">
					<input class="form-control text-uppercase" type="text" value="<?php echo $this->input->get('customer') ?>" id="customer" name="customer" />
					<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
				</div>
				<label class="control-label col-md-1 col-sm-3 col-xs-3">SIZ.COL</label>
				<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left">
					<div class="col-md-6 col-sm-3 col-xs-3 no-padding-left has-clear has-feedback">
						<input 
							class="form-control text-uppercase" 
							type="text" 
							value="<?php echo $this->input->get('size') ?>" 
							id="size" 
							name="size" 
						/>
						<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
					</div>
					<div class="col-md-6 col-sm-8 col-xs-8 no-padding-left no-padding-right has-clear has-feedback">
						<input 
							class="form-control text-uppercase" 
							type="text" 
							value="<?php echo $this->input->get('color') ?>" 
							id="color" 
							name="color" 
						/>
						<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"style="right: 0px !important;"></span>
					</div>
				</div>
				<div class="col-md-2">
					<div style="text-align: right;">
						<button id="btn_search_product" type="button" class="btn btn-info" style="margin-right: 0;"> <?php echo $this->lang->line('search'); ?></button>
						<button id="btn_export_excel" type='button' class="btn btn-success" style="margin-right: 0;"> <i class="fa fa-file-excel-o" ></i> Excel </button>
					</div>
				</div>
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
					<li><button type="button" class="btn btn-primary" onclick="window.location.href='<?php echo base_url(); ?>products/add'"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('add_item'); ?></button>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="table-responsive">
					<table id="products_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th><?php echo $this->lang->line('jp_code'); ?></th>
								<th><?php echo $this->lang->line('item_code'); ?></th>
								<th><?php echo $this->lang->line('item_name'); ?></th>
								<th><?php echo $this->lang->line('size'); ?></th>
								<th><?php echo $this->lang->line('color'); ?></th>
								<th><?php echo $this->lang->line('sales_man'); ?></th>
								<th><?php echo $this->lang->line('customer'); ?></th>
								<th><?php echo $this->lang->line('vender'); ?></th>
								<th><?php echo $this->lang->line('apparel'); ?></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div id="product_del_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog ">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel2"><?php echo $this->lang->line('MTS0030_I001'); ?></h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default antoclose2" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
				<button type="button" class="btn btn-primary antosubmit2"><?php echo $this->lang->line('yes'); ?></button>
			</div>
		</div>
	</div>
</div>

<script>
	var dataFilterSalesman = [];
	var dataFilterCustomer = [];
	var dataFilterVendor = [];
	var salesmanList = <?php echo json_encode($salesman_list); ?>;
	var customerList = <?php echo json_encode($customer_list); ?>;
	var vendorList = <?php echo json_encode($vendor_list); ?>;
	var size_list = (<?php echo json_encode($size_list); ?>) || [];
	var color_list = (<?php echo json_encode($color_list); ?>) || [];
	window.onload = function() {
		salesmanList.forEach(function(el){
			dataFilterSalesman.push({value: el.komoku_name_2, data : el.kubun});
		});
		customerList.forEach(function(el){
			dataFilterCustomer.push({value: el.short_name, data : el.company_id});
		});
		vendorList.forEach(function(el){
			dataFilterVendor.push({value: el.short_name, data : el.short_name});
		});

		$('#item_code').focus();
		$('#btn_search_product').on('click', function() {
			// reload datatable
			$('#frm_search_product').attr("action", "");
			$('#frm_search_product').submit();
			// $('#products_list').DataTable().ajax.reload();
		});
		$('#btn_export_excel').on('click', function() {
			$('#frm_search_product').attr("action",'<?php echo base_url("/products/excel") ?>');
			$('#frm_search_product').submit();
		});
		
		var productDt = $('#products_list').DataTable({
			"data": <?php $data = $this->session->flashdata('data');
										if ($data != null) {
											echo $data;
										} else {
											echo '[]';
										} ?>,
			"paging": true,
			"filter": false,
			"ordering": true,
			"scrollX" : true,
			"dom": "lBfrtip",
			"buttons": ['colvis'],
			<?php if ($this->session->flashdata('data') == null) : ?>
			"serverSide": true,
			"ajax": {
				"url": "<?php echo base_url('products/search'); ?>",
				"type": 'post',
				"data": function ( data ) {
					data.param = {};
					var arr = $('#frm_search_product').serializeArray();
					$.each(arr, function(index, item) {
						if(item.value != '') {
							data.param[item.name] = item.value;
						}
					});
				},
			},
			<?php endif ?>
			"columns": [
				{ "data": "jp_code", className: "text-left" },
				{ "data": "item_code", className: "text-left",
					"render": function( data, type, row, meta ) {
						return '<a href='+row.url_encode+' style="color:#428bca;">'+row.item_code+'</a>';
					}
				},
				{ "data": "item_name", className: "text-left" },
				{ "data": "size", className: "text-left" },
				{ "data": "color", className: "text-left" },
				{ "data": "salesman", className: "text-left" },
				{ "data": "customer", className: "text-left" },
				{ "data": "vendor", className: "text-left" },
				{ "data": "apparel", className: "text-left" },
			],
		});

		var data = <?php if ($this->session->flashdata('data')) {
													echo $this->session->flashdata('data');
												} else {
													echo true;
												} ?>;
		if(data != true) {
			$('#item_code').val(data[0].item_code.trim());
		}

		$('#products_list').on( 'draw.dt', function(){
			if (! productDt.data().any() ) {
				bootbox.alert("<h4 style='color:#0080FF; text-align:center;'><?php echo $this->lang->line('COMMON_E007'); ?></h4>");
			}
		});
		$("#salesman").autocomplete({
			lookup: dataFilterSalesman,
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		$("#customer").autocomplete({
			lookup: dataFilterCustomer,
			minChars: 0,
			width: '200px',
			onSelect: function(){
				$(this).change();
			}
		});
		$("#vendor").autocomplete({
			lookup: dataFilterVendor,
			minChars: 0,
			onSelect: function(){
				$(this).change();
			}
		});
		$("#size").autocomplete({
            lookup: Object.keys(size_list).map(function(key){
                return {
                    value: size_list[key]["komoku_name_2"],
                };
            }),
            minChars: 0,
            onSelect: function () {
                $(this).change();
            }
        });
		$("#color").autocomplete({
            lookup: Object.keys(color_list).map(function(key){
                return {
                    value: color_list[key]["komoku_name_2"],
                };
            }),
            minChars: 0,
            onSelect: function () {
                $(this).change();
            }
        });
	}
	
</script>
