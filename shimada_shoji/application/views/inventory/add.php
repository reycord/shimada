<div class="row">
	<div class="col-md-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>
					<?php echo $title ?>
				</h2>
				<ul class="nav navbar-right panel_toolbox">
					<li>
						<button id="btnBack" class="btn btn-info" onclick="window.history.back()">
							<i class="fa fa-arrow-left"></i>
							<?php echo $this->lang->line('back'); ?>
						</button>
					</li>
					<li>
						<button id="btnSave" class="btn btn-primary" type="submit" form="order">
							<i class="fa fa-save"></i>
							<?php echo $this->lang->line('save'); ?>
						</button>
					</li>
				</ul>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form class="form-horizontal myform" action="<?php echo isset($editStoreItem)?base_url('inventory/edit/').$editStoreItem["urlPrimaryKey"]:base_url('inventory/add') ?>" method="post" name="add_inventory" id="add_inventory" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('final_customer'); ?><span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
                                    <select id="final_customer" name="final_customer" class="form-control <?php echo isset($editStoreItem)?'':'validate[required]'?>">
                                        <option value=""></option>
                                        <?php foreach ($customerList as $customer): ?>
                                        <option value="<?php echo trim($customer['komoku_name_2']) ?>" <?php echo set_select('final_customer', trim($customer['komoku_name_2']), isset($editStoreItem)?trim($customer['komoku_name_2'])===trim($editStoreItem["salesman"]):FALSE)?>>
                                            <?php echo $customer['komoku_name_2']?>
                                        </option>
                                        <?php endforeach?>
                                    </select>
                                    <?php echo form_error('final_customer'); ?>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title">
                                        <?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('warehouse'); ?><span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
                                    <select id="warehouse" name="warehouse" class="form-control <?php echo isset($editStoreItem)?'':'validate[required]'?>">
                                        <option value=""></option>
                                        <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?php echo trim($warehouse['kubun']) ?>" <?php echo set_select('warehouse', trim($warehouse['kubun']), isset($editStoreItem)?$editStoreItem["warehouse_cd"]==trim($warehouse['kubun']):FALSE)?>>
                                            <?php echo $warehouse['komoku_name_2']?>
                                        </option>
                                        <?php endforeach?>
                                    </select>
                                    <?php echo form_error('warehouse'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('item_code'); ?>
                                        <span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                    <input value="<?php echo set_value('item_code', isset($editStoreItem)?$editStoreItem['item_code']:''); ?>" type="text" id="item_code" name="item_code" class="form-control validate[required]" onblur="getItemById(this)">
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <?php echo form_error('item_code'); ?>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2" style="text-align: right;">
                                    <button type="button" class="btn btn-primary hidden" data-toggle="modal" data-target="#product_search_modal" style="margin-right: 0;" <?php echo isset($editStoreItem)?"disabled":""; ?>>
                                        <?php echo $this->lang->line('search_product'); ?>
                                    </button>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title">
                                        <?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('item_name'); ?>
                                    </label>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
                                    <input value="<?php echo set_value('item_name', isset($editStoreItem)?$editStoreItem['item_name']:''); ?>" type="text" id="item_name" name="item_name" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title">
                                        <?php echo $this->lang->line('color'); ?>
                                        <span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                    <select id="color" name="color" class="form-control">
                                        <option value="%7B%7D"></option>
                                        <?php foreach ($color_list as $color): ?>
                                            <?php if($color!==''): ?>
                                            <option value="<?php echo trim($color['komoku_name_2']) ?>" <?php echo set_select('color', trim($color['komoku_name_2']), isset($editStoreItem)?trim($editStoreItem["color"])===trim($color['komoku_name_2']):FALSE)?>>
                                                <?php echo $color['komoku_name_2']?>
                                            </option>
                                            <?php endif; ?>
                                        <?php endforeach?>
                                    </select>
                                    <?php echo form_error('color'); ?>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title">
                                        <?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('size'); ?>
                                        <span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                    <select id="size" name="size" class="form-control">
                                        <option value="%7B%7D"></option>
                                        <?php foreach ($size_list as $size): ?>
                                            <?php if($size!==''): ?>
                                            <option value="<?php echo trim($size['komoku_name_2']) ?>" <?php echo set_select('size', trim($size['komoku_name_2']), isset($editStoreItem)?trim($editStoreItem["size"])===trim($size['komoku_name_2']):FALSE)?>>
                                                <?php echo $size['komoku_name_2']?>
                                            </option>
                                            <?php endif; ?>
                                        <?php endforeach?>
                                    </select>
                                    <?php echo form_error('size'); ?>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title">
                                        <?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('unit'); ?>
                                        <span class="required">*</span>
                                    </label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                    <select id="unit" name="unit" class="form-control">
                                        <option value=""></option>
                                        <?php foreach ($unit_list as $unit): ?>
                                            <option value="<?php echo trim($unit['komoku_name_2']) ?>" <?php echo set_select('unit', trim($unit['komoku_name_2']), isset($editStoreItem)?trim($editStoreItem["unit"])===trim($unit['komoku_name_2']):FALSE)?>>
                                                <?php echo $unit['komoku_name_2']?>
                                            </option>
                                        <?php endforeach?>
                                    </select>
                                    <?php echo form_error('unit'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title">OD<?php echo $this->lang->line('quantity'); ?></label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                    <input type="text" id="od_quantity" name="od_quantity" value="<?php echo set_value('od_quantity', isset($editStoreItem)?number_format($editStoreItem['quantity']):''); ?>" class="form-control" readonly>
                                    <?php echo form_error('od_quantity'); ?>
                                </div>
								<div class="col-md-2 col-sm-2 col-xs-2">
									<label class="control-label form-title"><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('type'); ?></label>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
									<select class="form-control" name="item_type" id="item_type">
										<option value=""></option>
										<?php foreach ($item_types as $item_type): ?>
										<option value="<?php echo trim($item_type['kubun']) ?>" <?php echo set_select('item_type', trim($item_type['kubun']), isset($editStoreItem)?$editStoreItem["item_type_cd"]===trim($item_type['kubun']):FALSE)?>>
											<?php echo $item_type['komoku_name_2']?>
										</option>
										<?php endforeach?>
									</select>
									<?php echo form_error('item_type'); ?>
								</div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label class="control-label form-title"><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('order_received_no'); ?></label>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
                                    <input type="text" id="order_no" name="order_no" value="<?php echo set_value('order_no', isset($editStoreItem)?$editStoreItem['order_no']:''); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-8 col-xs-8 no-padding-left no-padding-right">
                                <div class="form-group" style="border-top: 1px solid; padding-top:8px">
                                    <div class="col-md-3 col-sm-3 col-xs-3" >
                                        <label class="control-label form-title" style="color: blue"><?php echo $this->lang->line('arrival_date'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="arrival_date" name="arrival_date" value="<?php echo set_value('arrival_date', isset($editStoreItem) ? !empty($editStoreItem['arrival_date'])?date_format(date_create($editStoreItem['arrival_date']),'d M, Y'):'':''); ?>" class="form-control date">
                                    </div>
									<div class="col-md-3 col-sm-3 col-xs-3">
                                    	<label class="control-label form-title"style="color: blue"><?php echo str_repeat("&nbsp;", 5); ?> INV No</label>
                                	</div>
                                	<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                    	<input type="text" id="inv_no" name="inv_no" maxlength="20" value="<?php echo set_value('inv_no', isset($editStoreItem)?$editStoreItem['invoice_no']:''); ?>" class="form-control text-uppercase">
                                	</div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title" style="color: blue"><?php echo $this->lang->line('arrival_quantity'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="arrival_quantity" name="arrival_quantity" value="<?php echo set_value('arrival_quantity', isset($editStoreItem)?number_format($editStoreItem['arrival_ok']):''); ?>" class="form-control" onkeydown="return checkQuantity(event)">
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
                                <div class="form-group" style="border-top: 1px solid; padding-top:8px">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title" style="color: red"><?php echo $this->lang->line('inspection_date'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="inspection_date" name="inspection_date" value="<?php echo set_value('inspection_date', isset($editStoreItem)? !empty($editStoreItem['inspect_date'])?date_format(date_create($editStoreItem['inspect_date']),'d M, Y'):'':''); ?>" class="form-control date">
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title" style="color: red">
                                            <?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('quantity'); ?> OK<span class="required lblrequired hidden">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="ok_quantity" name="ok_quantity" value="<?php echo set_value('ok_quantity', isset($editStoreItem)?number_format($editStoreItem['inspect_ok']):''); ?>" class="form-control" onkeydown="return checkQuantity(event)">
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                        <?php echo form_error('ok_quantity'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-3" style="color: red">
                                        <label class="control-label form-title"><?php echo $this->lang->line('inspection_quantity'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="inspection_quantity" name="inspection_quantity" value="<?php echo set_value('inspection_quantity', isset($editStoreItem)?number_format($editStoreItem['arrival_ng']):''); ?>" class="form-control" onkeydown="return checkQuantity(event)">
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title" style="color: red">
                                            <?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('quantity'); ?> NG<span class="required lblrequired hidden">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="ng_quantity" name="ng_quantity" value="<?php echo set_value('ng_quantity', isset($editStoreItem)?number_format($editStoreItem['inspect_ng']):''); ?>" class="form-control" onkeydown="return checkQuantity(event)">
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                        <?php echo form_error('ng_quantity'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title"><?php echo $this->lang->line('inspection_rate'); ?></label>
                                    </div>
                                    <div class="col-md-9 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="inspection_rate" name="inspection_rate" value="<?php echo set_value('inspection_rate', isset($editStoreItem)?$editStoreItem['inspection_rate']:''); ?>" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title"><?php echo $this->lang->line('create_user'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="create_user" name="create_user" value="<?php echo set_value('create_user', isset($editStoreItem)?$editStoreItem['create_user']:$user["first_name"]." ".$user["last_name"]); ?>" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title"><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('create_date'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="create_date" name="create_date" value="<?php echo set_value('create_date', isset($editStoreItem)?$editStoreItem['create_date']:date("Y/m/d")); ?>" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title"><?php echo $this->lang->line('update_user'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="update_user" name="update_user" value="<?php echo set_value('update_user', $user["employee_id"]); ?>" class="form-control" readonly>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label class="control-label form-title"><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('update_date'); ?></label>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                        <input type="text" id="edit_date" name="edit_date" value="<?php echo isset($editStoreItem)?$editStoreItem['edit_date']:''; ?>" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
                                <div class="form-group" style="border-top: 1px solid; padding-top:8px">
                                    <div class="col-md-6 col-sm-6 col-xs-6" style="color: blue">
                                        <label class="control-label form-title"><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('arrival_note'); ?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
                                        <textarea spellcheck="false" type="text" style="height: 78px;" id="arrival_note" name="arrival_note" maxlength="100" class="form-control"><?php echo set_value('arrival_note', isset($editStoreItem)?$editStoreItem['arrival_note']:''); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group" style="border-top: 1px solid; padding-top:8px">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <label class="control-label form-title" style="color: red"><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('inspection_note'); ?></label>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
                                        <textarea spellcheck="false" type="text" style="height: 78px;" id="inspection_note" name="inspection_note" maxlength="100" class="form-control"><?php echo set_value('inspection_note', isset($editStoreItem)?$editStoreItem['inspect_note']:''); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                    <label class="control-label form-title"><?php echo str_repeat("&nbsp;", 5); ?>Up file</label>
                                    </div>
                                    <div class="col-md-9 col-sm-9 col-xs-9 no-padding-right">
										<div class="input-group file">
											<input  type="text" id="inspect_note_path_file" name="inspect_note_path_filename" class="form-control" value="" placeholder="<?php echo $this->lang->line('file_name'); ?>" readonly>
											<span  id="inspection_note_file_btn" class="input-group-addon btn-primary" accept=""><?php echo $this->lang->line('choose_file'); ?></span>
										</div>
										<input  style="display:none" accept="application/pdf" type="file" id="inspection_note_file" name="inspection_note_file"  class="form-control-file"/>
									</div>
                                </div>
								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-xs-3">
                                    </div>
									<div class="col-md-9 col-sm-9 col-xs-9 no-padding-left no-padding-right">
										<?php if($editStoreItem['inspect_note_path'] != null && $editStoreItem['inspect_note_path'] != ''): ?>
											<a href="<?php echo base_url('upload/'.substr($editStoreItem['inspect_note_path'],-36)) ?>" title="<?php echo substr($editStoreItem['inspect_note_path'],0,-36) ?>" target="_blank" style="color:#428bca; text-decoration: underline"><?php echo mb_strimwidth(substr($editStoreItem['inspect_note_path'],0,-36), 0, 40, "...")?></a>
										<?php endif ?>
									</div>
								</div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>

<!--Order List Modal -->
<!--Product Modal -->
<div id="modal_search_product" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel2">
					<?php echo $this->lang->line('ordering_list'); ?>
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="table-responsive">
						<table id="search_product_list" data-modal="#modal_search_product" class="table table-striped table-bordered" style="width: 100%">
							<thead>
								<tr>
									<th>
										<?php echo 'item_no'; ?>
									</th>
									<th>
										<?php echo 'item_name'; ?>
									</th>
									<th>
										<?php echo 'size'; ?>
									</th>
									<th>
										<?php echo 'color'; ?>
									</th>
									<th>
										<?php echo 'lot_quantity'; ?>
									</th>
									<th>
										<?php echo 'action'; ?>
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
</div>
<!-- Search -->
<div id="product_search_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
					<li>
						<button onclick="window.location.href='<?php echo base_url('products/add') ?>'" class="btn btn-primary">
							<i class="fa fa-plus"></i> IT
							<?php echo $this->lang->line('new'); ?>
						</button>
					</li>
				</ul>
				<h2>
					<?php echo $this->lang->line('search_product'); ?>
				</h2>
			</div>
			<div class="modal-body">
				<form id="product_search_form" class="form-horizontal form-label-left">
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="search_item_code">
							<?php echo $this->lang->line('item_code'); ?>
						</label>
						<div class="col-md-3 col-sm-7 col-xs-7">
							<input type="text" class="form-control" id="search_item_code" name="item_code" value="">
						</div>
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="search_item_name">
							<?php echo $this->lang->line('item_name'); ?>
						</label>
						<div class="col-md-3 col-sm-7 col-xs-7">
							<input type="text" class="form-control" id="search_item_name" name="item_name" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="search_input_date">
							<?php echo $this->lang->line('input_date'); ?>
							<span class="required">*</span>
						</label>
						<div class="col-md-3 col-sm-7 col-xs-7">
							<div class='input-group date' id='search_input_date' data-date-format="yyyy/mm/dd">
								<input type='text' class="form-control" name='input_date' />
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
						<label class="control-label col-md-2 col-sm-5 col-xs-5" for="search_saleman">
							<?php echo $this->lang->line('sales_man'); ?>
							<span class="required">*</span>
						</label>
						<div class="col-md-3 col-sm-7 col-xs-7">
							<input type="text" id="search_saleman" name="saleman" class="form-control" value=""></input>
						</div>
						<a class="action-search btn btn-info">
							<i class="fa fa-search"></i>
							<?php echo $this->lang->line('search'); ?>
						</a>
					</div>
				</form>
				<div class="x_title">
					<div class="clearfix"></div>
				</div>
				<div class="table-responsive">
					<table id="products_list_search" class="table table-striped table-bordered display nowrap" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>
									<?php echo $this->lang->line('order_received_no'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('item_code'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('item_name'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('quantity'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('size'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('size_unit'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('color'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('unit'); ?>
								</th>
								<th>
									<?php echo "hikiate_quantity"; //$this->lang->line('hikiate_quantity'); ?>
								</th>
								<th>
									<?php echo $this->lang->line('action'); ?>
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
<!-- End Modal -->
<script>
	var currentItem;

	function selectItem(element) {
		var item = $('#products_list_search').DataTable().row($(element).parents('tr')).data();
		console.log(item.item_code);
		$("#item_code").val(item.item_code);
		$("#item_name").val(item.item_name);
		$("#color").val(item.color);
		$("#size").val(item.size);
		$("#unit").val(item.unit);
		$("#od_quantity").val(item.sum);
		$("#color").attr('disabled', true);
		$("#size").attr('disabled', true);
		$("#unit").attr('disabled', true);
		$('#product_search_modal').modal('hide');

		currentItem = item;
	}
	window.onload = (function () {
		var dataTB;
		$('#product_search_modal').on('shown.bs.modal', function () {
			$('#search_item_code').val($('#product_id').val());
			if (!dataTB) {
				dataTB = $("#products_list_search").DataTable({
					// aaSorting: []
					ordering: false,
					filter: false,
					columns: [
						{ data: 'order_receive_no' },
						{ data: 'item_code' },
						{ data: 'item_name' },
						{ data: 'sum' },
						{ data: 'size' },
						{ data: 'size_unit'},
						{ data: 'color' },
						{ data: 'unit' },
						// { data: 'quantity' },
						{ data: 'hikiate_quantity' },
						{ data: 'action', className: 'text-center', render: function() {
							return '<a onclick="selectItem(this)" href="#" style="color: #0af">Select</a>';
							}
						},
					],
					"serverSide": true,
					"ajax": {
						url : "<?php echo base_url("inventory/search_order_received_detail") ?>",
						type : 'GET',
						data: function ( data ) {
							data.param = {};
							var arr = $('#product_search_form').serializeArray();
							$.each(arr, function(index, item) {
							if(item.value != '') {
								data.param[item.name] = item.value;
							}
							});
						}
					},
				});
			} else {
				dataTB.draw();
			}
		});

		$('#product_search_modal .action-search').click(function () {
			$('#product_search_modal ')
			dataTB.ajax.reload();
		});
		$("#btnSave").click(function(){
			let arrival_quantity = parseFloat($("#arrival_quantity").val());
			// if(arrival_quantity){
			// 	$("#inspection_quantity").attr("class", "form-control");
			//  $("#inspection_quantity").addClass("validate[max["+arrival_quantity+"]]");
			// }
			// let inspection_quantity = Number($("#inspection_quantity").val());
			// let ok_quantity = Number($("#ok_quantity").val());
			// let ng_quantity = Number($("#ng_quantity").val());
			// if(ok_quantity){
			// 	$("#ok_quantity").attr("class", "form-control");
			// 	let calc = inspection_quantity-ng_quantity;
			// 	if(calc > 0){
			// 		$("#ok_quantity").addClass("validate[max["+(inspection_quantity-ng_quantity)+"]]");
			// 	}
			// }
			// if(ng_quantity){
			// 	$("#ng_quantity").attr("class", "form-control");
			// 	let calc = inspection_quantity-ok_quantity;
			// 	if(calc > 0){
			// 		$("#ng_quantity").addClass("validate[max["+(inspection_quantity-ok_quantity)+"]]");
			// 	}
			// }
			if($("#add_inventory").validationEngine('validate')){
				bootbox.confirm({
								message: "<h4><?php echo ($this->lang->line('STR0010_I005'))?></h4>",
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
                callback: function(result){
									if(result){
										url = $(".myform").attr("action").replace("/edit/","/checkEditDate/");
										data = {
											"edit_date" : $("#edit_date").val(),
										};
										$.ajax({
											url : url,
											type : "POST",
											dataType : "json",
											data: data,
											success : function(data) {
												// TODO
												// return;
												console.log(data);
												if(data.success){
													$('#add_inventory').submit();
												}else{
													snackbarShow(data.message);
												}
											},
											error : function(data) {
												// TODO
												snackbarShow("error");
											}
										});
									}
								}
				})
			}
		});
		$("a.btnDelete").click(function(){
			url = $(this).data("url");
			// console.log(url);
			$("#confirm_modal a.btnOk").attr("href", url);
			$("#confirm_modal").modal("show");
		});

		function inventorySearchProduct(){
			btnSelectHTML = '<button onclick="selectProduct(this)" data-item-no={0} class="btn btn-default btn-sm btn-custom btnSelectInventory">\
							<span class="glyphicon glyphicon-ok"> Select</span>\
						</button>';
			url = $("#search_product").data("url");
			$.ajax({
				url : url,
				type : "POST",
				dataType : "json",
				success : function(data) {
					// TODO
					// return;
					console.log(data);
					arrData = [];
					data.forEach(function(json){
						item_no     = json.item_code;
						item_name   = json.item_name;
						size        = json.size;
						color       = json.color;
						lot_quantity = json.lot_quantity;
						p_urlSelect = btnSelectHTML.format(item_no);
						arrRow = [item_no
									,item_name
									,size
									,color
									,lot_quantity
									,p_urlSelect
								]
						arrData.push(arrRow);
					}) ;
					var table = $('#search_product_list').dataTable().api();
					table.clear();
					table.rows.add(arrData);
					table.draw();
					// console.log("last");
					$("#modal_search_product").modal("show");
				},
				error : function(data) {
					// TODO
					console.log("error");
				}
			});
		}

		function selectProduct(obj){
			item_no = $(obj).data("item-no");
			tdList = $("#search_product_list tbody tr:contains('"+ item_no +"')").find("td");
			$("#item_code").val($(tdList[0]).text());
			$("#item_name").val($(tdList[1]).text());
			$("#size").val($(tdList[2]).text());
			$("#color").val($(tdList[3]).text());
			$("#od_quantity").val($(tdList[4]).text());
			$("#modal_search_product").modal("hide");
		}
        $("#inspection_note_file_btn").on('click',function(){
            $("#inspection_note_file").trigger("click")
        });
        $("#inspection_note_file").on('change',function(){
            var pathFile = this.value.replace(/^.*[\\\/]/, '');
            $("#inspect_note_path_file").val(pathFile);
        });
		function getItemById(obj){
			item_no = $(obj).val();
			url = base_url + "inventory/getItem/" + item_no;
			$.ajax({
				url : url,
				type : "POST",
				dataType : "json",
				success : function(data) {
					// TODO
					console.log(data);
					if(data){
						$("#item_name").val(data.item_name);
						$("#size").val(data.size);
						$("#color").val(data.color);
						$("#od_quantity").val(data.lot_quantity);
					}else{
						$("#item_name").val("");
						$("#size").val("");
						$("#color").val("");
						$("#od_quantity").val("");
					}
				},
				error : function(data) {
					// TODO
					console.log("error");
				}
			});
		}
		$("#item_code").keypress(function(event){
			if(event.keyCode == 13){
				$(this).blur();
			}
		});
		<?php if(isset($editStoreItem)):?>
		<?php if(in_array($editStoreItem['status_cd'], explode(",", STATUS_FINISH_INVENTORY))):?>
		$(".form-control-clear").remove();
		$("input").removeClass("date");
		$("input,select,textarea").attr("readonly", true);
		$("input,select,textarea").css("pointer-events", "none");
		$("#btnSave").attr("disabled", "disabled");
		<?php else: ?>
		$("#color,#size,#unit,#item_type,#item_code,#order_no").attr("readonly", true);
		$("#final_customer,#warehouse").attr("readonly", true);
		$("select[readonly=readonly]").css("pointer-events", "none");
		// $("input:read-only ~ span").css("display", "none");
		<?php endif;?>
		<?php endif;?>
		$("#arrival_quantity").on("keyup mouseup", function(){
			if($(this).val()!=""&&$(this).val()!=0){
				if($("#arrival_date").val()==""){
						$("#arrival_date").val('<?php echo date("d M, Y");?>');
				}
			}
		});
		$("#inspection_quantity, #ok_quantity, #ng_quantity").on("keyup mouseup", function(){
			if($(this).val()!=""&&$(this).val()!=0){
				if($("#inspection_date").val()==""){
						$("#inspection_date").val('<?php echo date("d M, Y");?>');
				}
				if(parseFloat($("#inspection_quantity").val().replace(",", "")) > parseFloat($("#arrival_quantity").val().replace(",", ""))){
					$("#inspection_quantity").css("color", "red");
				}else{
					$("#inspection_quantity").css("color", "#555");
				}
				if(parseFloat($("#inspection_quantity").val().replace(",", "")) - parseFloat($("#ok_quantity").val().replace(",", "")) <  parseFloat($("#ng_quantity").val().replace(",", ""))){
					$("#ng_quantity").css("color", "red");
					$("#ok_quantity").css("color", "red");
				}else{
					$("#ng_quantity").css("color", "#555");
					$("#ok_quantity").css("color", "#555");
				}
			}
		}).trigger("keyup");
		$("#arrival_quantity, #inspection_quantity, #ok_quantity, #ng_quantity").on("focus", function(){
			$(this).select();
		});
		$("#inspection_quantity").on("keyup mouseup", function(){
			if($(this).val()!=""){
				$("#ok_quantity,#ng_quantity").addClass("validate[required]");
				$('span.lblrequired').removeClass("hidden");
			}else{
				$("#ok_quantity,#ng_quantity").removeClass("validate[required]");
				$('span.lblrequired').addClass("hidden");
			}
		});
	});

</script>
