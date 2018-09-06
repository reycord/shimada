<?php //print_r( $order_receive);?>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
				<h2><?php echo $title; ?></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><button class="btn btn-info" onclick="window.location.href='<?php echo base_url(); ?>order_received/pv_upload'"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button>
                    </li>
                    <li><button type="button" data-event="save" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button></li>
				</ul>
				<div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="save_pv_upload_form" class="form-horizontal myform" action="<?php echo base_url('/order_received/save_pv_upload') ?>" method="post">
                    <!-- <input type="hidden" name="uploaded_order_receives" value='<?php //echo json_encode($order_receives) ?>' /> -->
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <?php 
                            function validateDate($date, $format = 'Y/m/d')
                            {
                                $d = DateTime::createFromFormat($format, $date);
                                return $d && $d->format($format) == $date;
                            }
                        ?>
                        <?php foreach ($order_receives as $index => $order_receive) : ?>
                            <?php $rootName = "order_receives[$index]" ?>
                            <?php
                                $getFieldName = function($field) use ($index) {
                                    return "order_receives[$index][$field]";
                                };
                                $getFieldValue = function($field, $default = '', $html_escape = TRUE) use ($index, $getFieldName, $order_receive) {
                                    $fieldName = $getFieldName($field);
                                    return set_value($fieldName, isset($order_receive[$field]) ? $order_receive[$field] : $default, $html_escape);
                                };
                                $echoField = function ($field) use ($index, $getFieldName, $getFieldValue) {
                                    $fieldName = $getFieldName($field);
                                    $value = $getFieldValue($field);
                                    if(validateDate($value, 'Y/m/d')) {
                                        if($value != null && $value != '') {
                                            $value = date_format(new DateTime($value), "j M, Y");
                                        }
                                    }
                                    echo "name='$fieldName' value='$value'";
                                };
                            ?>
                            <?php
                                $orderReceiveErrors = [];
                                foreach($this->form_validation->error_array() as $key => $value) {
                                    if(strpos($key, "order_receives[$index]") === 0) {
                                        $orderReceiveErrors[$key] = $value;
                                    }
                                }
                            ?>
                            <div class="panel panel-default <?php if(count($orderReceiveErrors) > 0): ?>panel-danger<?php endif ?>">
                                <div class="panel-heading" role="tab" id="heading-<?php echo $index ?>">
                                    <h4 class="panel-title">
                                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-order-receive-<?php echo $index ?>" aria-expanded="true" aria-controls="collapseOne">
                                        <span style="display: inline-block;width: 50px;">#<?php echo ($index + 1) ?></span>
                                        <span style="display: inline-block;width: 80px;font-size: 12px">page: <?php echo isset($order_receive['PAGE']) ? $order_receive['PAGE'] : ''?></span>
                                        <?php
                                            if(count($orderReceiveErrors) > 0) {
                                                echo '<span style="display: inline-block;width: auto;font-size: 12px">' . array_values($orderReceiveErrors)[0] . '</span>';
                                            }
                                        ?>
                                        <?php
                                            if(isset($insert_result_array)) {
                                                foreach($insert_result_array as $key => $val) {
                                                    if($order_receive['order_receive_no'] == $val['order_receive_no']) {
                                                        echo '<span style="display: inline-block;width: auto;font-size: 12px; color: red">' . $val['message'] . '</span>';
                                                    }
                                                }
                                            }
                                        ?>
                                        <?php
                                            $itemCodeNotExist = array();
                                            foreach ($order_receive['details'] as $key => $detail) {
                                                if(!$detail['item_code_flg']) {
                                                    $itemCodeNotExist[count($itemCodeNotExist)] = $detail['item_code'];
                                                }
                                            }
                                            echo (count($itemCodeNotExist) > 0) ? 
                                                ('<span style="display: inline-block;width: auto;font-size: 12px; color: red; word-wrap: normal">' . implode(", ", $itemCodeNotExist) 
                                                . '</span>' . " " . '<span style="display: inline-block;width: auto;font-size: 12px; color: blue; word-wrap: normal">' . $this->lang->line("item_not_existed") . '</span>') : "";
                                        ?>
                                    </a>
                                    </h4>
                                </div>
                                <div id="collapse-order-receive-<?php echo $index ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $index ?>">
                                    <div class="panel-body">
									<!-- Comment by Dung -->
                                        <!-- <div class="form-group">
                                            <div class="col-sm-4" >
                                                <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="order_receive_no" ><?php echo $this->lang->line('order_received_no'); ?><span class="required">*</span></label>
                                                <div class="col-sm-5 no-padding-right" style="padding-left: 10px;">
                                                    <input type="text" class="form-control margin-left-3" id="order_receive_no" maxlength="50" <?php $echoField('order_receive_no') ?>>
                                                    <?php echo form_error($getFieldName('order_receive_no')); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="order_receive_date" ><?php echo $this->lang->line('order_received_date'); ?><span class="required">*</span></label>
                                                <div class="col-sm-8" >
                                                    <input type="text" class="form-control hasDatepicker date" id="order_receive_date" maxlength="10" <?php $echoField('order_receive_date') ?>>
                                                    <?php echo form_error($getFieldName('order_receive_date')); ?>
                                                </div>
                                            </div>
											<div class="col-sm-4">
                                                <label class="control-label col-sm-4 no-padding-left no-padding-right" for="input_user" ><?php echo $this->lang->line('input_user'); ?><span class="required">*</span></label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" id="input_user" name="<?php echo $getFieldName('input_user') ?>">
                                                        <option value=""></option>
                                                        <?php foreach ($employeeList as $employee): ?>
                                                        <option value="<?php echo $employee['employee_id']; ?>" <?php echo $employee['employee_id'] == $getFieldValue('input_user') ? 'selected' : '' ?>><?php echo $employee['first_name']; ?> <?php echo $employee['last_name']; ?></option>
                                                        <?php endforeach?>
                                                    </select>
                                                    <?php echo form_error($getFieldName('input_user')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="kubun" ></label>
                                                <div class="col-sm-5 no-padding-left" style="margin-left: 0px;">
                                                    <p style="margin:0px; padding-top: 8px">
                                                        <input type="radio" class="flat" name="<?php echo $getFieldName('kubun') ?>" id="kubunS" value="<?php echo ORDER_RECEIVED_TYPE_JP ?>" <?php echo $getFieldValue('kubun', ORDER_RECEIVED_TYPE_JP) == ORDER_RECEIVED_TYPE_JP ? 'checked' : ''; ?> /> Shimada Japan
                                                        &nbsp;&nbsp;
                                                    </p>
                                                </div>
                                                <div class="col-sm-3 no-padding-left" style="margin-left: 0px;">
                                                    <p style="margin:0px; padding-top: 8px">
                                                        &nbsp;&nbsp;
                                                        <input type="radio" class="flat" name="<?php echo $getFieldName('kubun') ?>" id="kubunO" value="<?php echo ORDER_RECEIVED_TYPE_OTHER ?>"  <?php echo $getFieldValue('kubun') == ORDER_RECEIVED_TYPE_OTHER ? 'checked' : ''; ?> /> Other
                                                    </p>
                                                </div>
                                                <?php echo form_error($getFieldName('kubun')); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
											<div class="col-sm-4">
                                                <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="odr_department" ><?php echo $this->lang->line('department'); ?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control margin-left-3" id="odr_department" maxlength="20" <?php $echoField('odr_department') ?>>
                                                    <?php echo form_error($getFieldName('odr_department')); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="staff" ><?php echo $this->lang->line('sales_man'); ?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="staff" maxlength="20" <?php $echoField('staff') ?>>
                                                    <?php echo form_error($getFieldName('staff')); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="assistance" ><?php echo $this->lang->line('assistance'); ?></label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="assistance" maxlength="20" <?php $echoField('assistance') ?>>
                                                    <?php echo form_error($getFieldName('assistance')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="customer" ><?php echo $this->lang->line('customer'); ?><span class="required">*</span></label>
                                                <div class="col-sm-9 no-padding-left" style="margin-left: -1px;">
                                                    <input type="text" class="form-control" id="customer" maxlength="100" <?php $echoField('customer') ?>>
                                                    <?php echo form_error($getFieldName('customer')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="delivery_to" ><?php echo $this->lang->line('delivery_to'); ?><span class="required">*</span></label>
                                                <div class="col-sm-9 no-padding-left" style="margin-left: -1px;">
                                                    <input type="text" class="form-control" id="delivery_to" maxlength="100" <?php $echoField('delivery_to') ?>>
                                                    <?php echo form_error($getFieldName('delivery_to')); ?>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="head_office" ><?php echo $this->lang->line('head_office'); ?></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="head_office" maxlength="100" <?php $echoField('head_office') ?>>
                                                    <?php echo form_error($getFieldName('head_office')); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-6">
                                                <label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="delivery_address" ><?php echo $this->lang->line('address'); ?></label>
                                                    <div class="col-sm-9 no-padding-left" style="margin-left: -1px;">
                                                        <textarea class="form-control form-rounded" rows="4" id="delivery_address" name="<?php echo $getFieldName('delivery_address') ?>"><?php echo $getFieldValue('delivery_address', '', FALSE) ?></textarea>
                                                        <?php echo form_error($getFieldName('delivery_address')); ?>
                                                    </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="head_office_address" ><?php echo $this->lang->line('address'); ?></label>
                                                    <div class="col-sm-9">
                                                        <textarea class="form-control form-rounded" rows="4" id="head_office_address" name="<?php echo $getFieldName('head_office_address') ?>"><?php echo $getFieldValue('head_office_address', '', FALSE) ?></textarea>
                                                        <?php echo form_error($getFieldName('head_office_address')); ?>
                                                    </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-4">
                                                <label class="control-label col-sm-4 no-padding-left no-padding-right" for="currency" ><?php echo $this->lang->line('currency'); ?><span class="required">*</span></label>
                                                <div class="col-sm-8">
                                                    <select class="form-control margin-left-3" id="currency" name="<?php echo $getFieldName('currency') ?>">
                                                        <option value=""></option>
                                                        <?php foreach ($currency as $cur): ?>
                                                        <option
                                                            value="<?php echo $cur['currency_name']; ?>"
                                                            <?php echo (trim($cur['currency_name']) == trim($getFieldValue('currency')) ? 'selected' : ''); ?>
                                                        ><?php echo $cur['currency_name']; ?></option>
                                                        <?php endforeach?>
                                                    </select>
                                                    <?php echo form_error($getFieldName('currency')); ?>
                                                </div>
                                            </div>
											<div class="col-sm-4">
												<label id="staff_label" class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="usd_vnd" >USD->VND <?php echo $this->lang->line('rate'); ?></label>
                            					<div class="col-sm-8">
													<input type="text" class="form-control" id="usd_vnd" name="usd_vnd">
                            					</div>
											</div>
											<div class="col-sm-4">
                            					<label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="jpy_vnd" >JPY->VND <?php echo $this->lang->line('rate'); ?></label>
                            					<div class="col-sm-8">
													<input type="text" class="form-control" id="jpy_vnd" name="jpy_vnd">
                            					</div>
                          					</div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <div class="col-sm-6 no-padding-left">
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="contract_no" ><?php echo $this->lang->line('contract_no'); ?></label>
                                                    <div class="col-sm-8" style="padding-left: 10px;">
                                                        <input type="text" class="form-control margin-left-3" id="contract_no" maxlength="20" <?php $echoField('contract_no') ?>>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 no-padding-right">
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="style" ><?php echo $this->lang->line('style'); ?></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="style" maxlength="10" <?php $echoField('style') ?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-6 no-padding-left">
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="delivery_date" ><?php echo $this->lang->line('delivery_date'); ?><span class="required">*</span></label>
                                                    <div class="col-sm-8" style="padding-left: 10px;">
                                                        <input type="text" class="form-control hasDatepicker date margin-left-3" id="delivery_date" maxlength="10" <?php $echoField('delivery_date') ?>>
                                                        <?php echo form_error($getFieldName('delivery_date')); ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 no-padding-right">
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="tax_classification" ><?php echo $this->lang->line('tax'); ?></label>
                                                    <div class="col-sm-8">
                                                        <select class="form-control" id="tax_classification" name="<?php echo $getFieldName('tax')?>">
                                                            <option value=""></option>
                                                            <?php foreach ($taxClassifications as $taxClassification): ?>
                                                                <option
                                                                    value="<?php echo $taxClassification['tax_id']; ?>"
                                                                    <?php echo $getFieldValue('tax') == $taxClassification['tax_id'] ? 'selected' : ''; ?>
                                                                >
                                                                    <?php echo $taxClassification['tax_name'] ?>
                                                                </option>
                                                            <?php endforeach?>
                                                        </select>
                                                        <?php echo form_error($getFieldName('tax')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="note" ><?php echo $this->lang->line('note'); ?></label>
                                            <div class="col-sm-8 ">
                                                <textarea type="text" class="form-control" style="height: 78px;" name="<?php echo $getFieldName('note') ?>" id="note" maxlength="100"><?php echo $getFieldValue('note'); ?></textarea>
                                                <?php echo form_error($getFieldName('note', '', FALSE)); ?>
                                            </div>
                                        </div> -->
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label form-title" for="order_receive_no" ><?php echo $this->lang->line('order_received_no'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left no-padding-right">
													<input type="text" class="form-control" id="order_receive_no" maxlength="50" <?php $echoField('order_receive_no') ?>>
													<?php echo form_error($getFieldName('order_receive_no')); ?>
												</div>
												<div class="col-md-4 col-sm-4 col-xs-4 no-padding-right"></div>
											</div>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label form-title" for="order_receive_date" ><?php echo $this->lang->line('order_received_date'); ?><span class="required">*</span></label>
                                            </div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<input type="text" class="form-control hasDatepicker date" id="order_receive_date" maxlength="10" <?php $echoField('order_receive_date') ?>>
												<?php echo form_error($getFieldName('order_receive_date')); ?>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label form-title" for="input_user" ><?php echo $this->lang->line('input_user'); ?><span class="required">*</span></label>
                                            </div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<select class="form-control" id="input_user" name="<?php echo $getFieldName('input_user') ?>">
													<option value=""></option>
													<?php foreach ($employeeList as $employee): ?>
													<option value="<?php echo $employee['employee_id']; ?>" <?php echo $employee['employee_id'] == $getFieldValue('input_user') ? 'selected' : '' ?>><?php echo $employee['first_name']; ?> <?php echo $employee['last_name']; ?></option>
													<?php endforeach?>
												</select>
												<?php echo form_error($getFieldName('input_user')); ?>
											</div>
                                        </div>
                                        <div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="kubun" ></label>
                                        	</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left" style="margin-left: 0px;">
												<p style="margin:0px;">
													<input type="radio" class="flat" name="<?php echo $getFieldName('kubun') ?>" id="kubunS" value="<?php echo ORDER_RECEIVED_TYPE_JP ?>" <?php echo $getFieldValue('kubun', ORDER_RECEIVED_TYPE_JP) == ORDER_RECEIVED_TYPE_JP ? 'checked' : ''; ?> /> Shimada Japan
													&nbsp;&nbsp;
												</p>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left" style="margin-left: 0px;">
												<p style="margin:0px;">
													&nbsp;&nbsp;
													<input type="radio" class="flat" name="<?php echo $getFieldName('kubun') ?>" id="kubunO" value="<?php echo ORDER_RECEIVED_TYPE_OTHER ?>"  <?php echo $getFieldValue('kubun') == ORDER_RECEIVED_TYPE_OTHER ? 'checked' : ''; ?> /> Other
												</p>
											</div>
											<?php echo form_error($getFieldName('kubun')); ?>
                                        </div>
                                        <div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label form-title" for="odr_department" ><?php echo $this->lang->line('department'); ?></label>
                                            </div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<input type="text" class="form-control" id="odr_department" maxlength="20" <?php $echoField('odr_department') ?>>
												<?php echo form_error($getFieldName('odr_department')); ?>
											</div>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="staff" ><?php echo $this->lang->line('sales_man'); ?></label>
                                            </div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<input type="text" class="form-control" id="staff" maxlength="20" <?php $echoField('staff') ?>>
												<?php echo form_error($getFieldName('staff')); ?>
											</div>
                                            <div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="assistance" ><?php echo $this->lang->line('assistance'); ?></label>
                                            </div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<input type="text" class="form-control" id="assistance" maxlength="20" <?php $echoField('assistance') ?>>
												<?php echo form_error($getFieldName('assistance')); ?>
											</div>
                                        </div>
                                        <div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label form-title" for="customer" ><?php echo $this->lang->line('customer'); ?><span class="required">*</span></label>
                                            </div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left">
												<input type="text" class="form-control" id="customer" maxlength="100" <?php $echoField('customer') ?>>
												<?php echo form_error($getFieldName('customer')); ?>
											</div>
                                        </div>
                                        <div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label form-title" for="delivery_to" ><?php echo $this->lang->line('delivery_to'); ?><span class="required">*</span></label>
                                            </div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left">
												<input type="text" class="form-control" id="delivery_to" maxlength="100" <?php $echoField('delivery_to') ?>>
												<?php echo form_error($getFieldName('delivery_to')); ?>
											</div>
                                            <div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
                                                <label class="control-label form-title" for="head_office" ><?php echo $this->lang->line('head_office'); ?></label>
                                            </div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
												<input type="text" class="form-control" id="head_office" maxlength="100" <?php $echoField('head_office') ?>>
												<?php echo form_error($getFieldName('head_office')); ?>
											</div>
                                        </div>
                                        <div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label form-title" for="delivery_address" ><?php echo $this->lang->line('address'); ?></label>
                                            </div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left">
												<textarea spellcheck="false" class="form-control form-rounded" rows="4" id="delivery_address" name="<?php echo $getFieldName('delivery_address') ?>"><?php echo $getFieldValue('delivery_address', '', FALSE) ?></textarea>
												<?php echo form_error($getFieldName('delivery_address')); ?>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
                                                <label class="control-label form-title" for="head_office_address" ><?php echo $this->lang->line('address'); ?></label>
                                            </div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
												<textarea spellcheck="false" class="form-control form-rounded" rows="4" id="head_office_address" name="<?php echo $getFieldName('head_office_address') ?>"><?php echo $getFieldValue('head_office_address', '', FALSE) ?></textarea>
												<?php echo form_error($getFieldName('head_office_address')); ?>
											</div>
                                        </div>
                                        <div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
                                                <label class="control-label form-title" for="currency" ><?php echo $this->lang->line('currency'); ?><span class="required">*</span></label>
                                            </div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<select class="form-control" id="currency" name="<?php echo $getFieldName('currency') ?>">
													<option value=""></option>
													<?php foreach ($currency as $cur): ?>
													<option
														value="<?php echo $cur['currency_name']; ?>"
														<?php echo (trim($cur['currency_name']) == trim($getFieldValue('currency')) ? 'selected' : ''); ?>
													><?php echo $cur['currency_name']; ?></option>
													<?php endforeach?>
												</select>
												<?php echo form_error($getFieldName('currency')); ?>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label id="staff_label" class="control-label form-title" for="usd_vnd" >USD->VND <?php echo $this->lang->line('rate'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<input type="number" class="form-control" id="usd_vnd" name="usd_vnd">
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
                            					<label class="control-label form-title" for="jpy_vnd" >JPY->VND <?php echo $this->lang->line('rate'); ?></label>
                          					</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<input type="number" class="form-control" id="jpy_vnd" name="jpy_vnd">
											</div>
                                        </div>
										<div class="form-group">
											<div class="col-md-8 col-sm-8 col-xs-8 no-padding-left no-padding-right">
												<div class="form-group">
													<div class="col-md-3 col-sm-3 col-xs-3">
														<label class="control-label form-title" for="contract_no" ><?php echo $this->lang->line('contract_no'); ?></label>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
														<input type="text" class="form-control" id="contract_no" maxlength="20" <?php $echoField('contract_no') ?>>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3">
														<label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="style" ><?php echo $this->lang->line('style'); ?></label>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
														<input type="text" class="form-control" id="style" maxlength="10" <?php $echoField('style') ?>>
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-3 col-sm-3 col-xs-3">
														<label class="control-label form-title" for="delivery_date" ><?php echo $this->lang->line('delivery_date'); ?><span class="required">*</span></label>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
														<input type="text" class="form-control hasDatepicker date" id="delivery_date" maxlength="10" <?php $echoField('delivery_date') ?>>
														<?php echo form_error($getFieldName('delivery_date')); ?>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3">
														<label class="control-label form-title" for="tax_classification" ><?php echo $this->lang->line('tax'); ?></label>
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
														<select class="form-control" id="tax_classification" name="<?php echo $getFieldName('tax')?>">
															<option value=""></option>
															<?php foreach ($taxClassifications as $taxClassification): ?>
																<option
																	value="<?php echo $taxClassification['tax_id']; ?>"
																	<?php echo $getFieldValue('tax') == $taxClassification['tax_id'] ? 'selected' : ''; ?>
																>
																	<?php echo $taxClassification['tax_name'] ?>
																</option>
															<?php endforeach?>
														</select>
														<?php echo form_error($getFieldName('tax')); ?>
													</div>
												</div>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="note" ><?php echo $this->lang->line('note'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<textarea spellcheck="false" type="text" class="form-control" style="height: 78px;" name="<?php echo $getFieldName('note') ?>" id="note" maxlength="100"><?php echo $getFieldValue('note'); ?></textarea>
                                                <?php echo form_error($getFieldName('note', '', FALSE)); ?>
											</div>
										</div>
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <?php
                                                $jsonVal = $getFieldValue('details');
                                                if(is_array($jsonVal)){
                                                    $jsonVal = json_encode($jsonVal);
                                                }
                                                ?>
                                                <input type="hidden" name="<?php echo $getFieldName('details') ?>" value='<?php echo $jsonVal; ?>' >
                                                <input type="hidden" name="<?php echo $getFieldName('sum_amount') ?>" value='<?php echo $getFieldValue('sum_amount'); ?>' >
                                                <input type="hidden" name="<?php echo $getFieldName('sum_quantity') ?>" value='<?php echo $getFieldValue('sum_quantity'); ?>' >
                                                <table id="items_orders_list_<?php echo $index ?>" class="table table-striped table-bordered display nowrap" width="100%" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo $this->lang->line('code_jp'); ?></th>
                                                            <th><?php echo $this->lang->line('item_code'); ?></th>
                                                            <th><?php echo $this->lang->line('item_name'); ?></th>
                                                            <th><?php echo $this->lang->line('composition'); ?></th>
                                                            <th><?php echo $this->lang->line('size'); ?></th>
                                                            <th><?php echo $this->lang->line('color'); ?></th>
                                                            <th><?php echo $this->lang->line('size_unit'); ?></th>
                                                            <th><?php echo $this->lang->line('quantity'); ?></th>
                                                            <th><?php echo $this->lang->line('unit'); ?></th>
                                                            <th><?php echo $this->lang->line('price'); ?></th>
                                                            <th><?php echo $this->lang->line('total_amount'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($order_receive['details'] as $key => $detail) { ?>
                                                            <?php if(!$detail['item_code_flg']): ?>
                                                            <tr style="background-color: rgb(255, 232, 217);" data-item='<?php echo json_encode($detail)?>'>
                                                            <?php else: ?>
                                                            <tr>
                                                            <?php endif ?>
                                                                <td><?php echo ($key + 1) ?></td>
                                                                <td>
                                                                    <?php echo $detail['jp_code']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if(!$detail['item_code_flg']): ?>
                                                                        <a href="#" data-event="add-product" data-toggle="modal" data-target="#add_product_modal" style="color: blue; text-decoration: underline;"><?php echo $detail['item_code']; ?></a>
                                                                    <?php else: ?>
                                                                        <?php echo $detail['item_code']; ?>
                                                                    <?php endif ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo $detail['item_name']; ?>
                                                                </td>
                                                                <td>
                                                                    <?php 
                                                                        echo str_replace("\\\"","\"",$detail['composition_1']) . ' ' . str_replace("\\\"","\"",$detail['composition_2']) . ' ' . (isset($detail['composition_3'])?$detail['composition_3']:"");
                                                                    ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php echo $detail['size']; ?>
                                                                </td>
                                                                <td class="text-left">
                                                                    <?php echo $detail['color']; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo $detail['size_unit']; ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo $detail['quantity']; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <?php echo $detail['unit']; ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo (isset($detail['sell_price']) ? $detail['sell_price'] : ""); ?>
                                                                </td>
                                                                <td class="text-right">
                                                                    <?php echo (isset($detail['amount']) ? $detail['amount'] : ""); ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Add product -->
<div id="add_product_modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <ul class="nav navbar-right panel_toolbox">
                    <li><button class="btn btn-info" data-dismiss="modal"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button>
                    </li>
                    <li><button class="btn btn-primary" type="button" id="btnSave"><i class="fa fa-plus"></i> <?php echo $this->lang->line('save'); ?></button>
                    </li>
                </ul>
                <h2><?php echo $this->lang->line('add_new_product'); ?></h2>
            </div>
            <div class="modal-body">
                <form id="add_product_form" class="form-horizontal form-label-left">
                    <div class="form-group">
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="item_code"><?php echo $this->lang->line('item_code'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input 
                                type="text" 
                                class="validate[required] text-uppercase form-control"
                                id="item_code"
                                name="item_code"
                                maxlength="15"
                                value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="item_name"><?php echo $this->lang->line('item_name'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                            <input 
                                type="text" 
                                class="form-control validate[required]" 
                                id="item_name"
                                name="item_name"
                                maxlength="100"
                                value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="jp_code"><?php echo $this->lang->line('code_jp'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input 
                                type="text" 
                                class="form-control text-uppercase validate[required]" 
                                id="jp_code"
                                name="jp_code"
                                maxlength="15"
                                value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label"><?php echo $this->lang->line('sales_man'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control validate[required]" id="salesman" name="salesman" maxlength="20" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="vendor"><?php echo $this->lang->line('vender'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control validate[required]" id="vendor" name="vendor" maxlength="100" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="customer_code"><?php echo $this->lang->line('customer_code'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="hidden" class="form-control" id="customer_code" name="customer_code" maxlength="5" value="003">
                            <input type="text" class="form-control validate[required]" id="customer_name" name="customer_name" value="COM">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="size"><?php echo $this->lang->line('size'); ?></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control" id="size" name="size" maxlength="20" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="unit"><?php echo $this->lang->line('unit'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control validate[required]" id="unit" name="unit" maxlength="10" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="color"><?php echo $this->lang->line('color'); ?></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control" id="color" name="color" maxlength="20" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="size_unit"><?php echo $this->lang->line('size_unit'); ?></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control" id="size_unit" name="size_unit" maxlength="10" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="buy_price_usd"> <?php echo $this->lang->line('buy_price'); ?> US$</label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <input
                                type="number" 
                                name="buy_price_usd" 
                                id="buy_price_usd" 
                                class="form-control"
                                value=""
                                onkeydown="return checkQuantity(event)">
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="sell_price_usd"><?php echo $this->lang->line('sell_price'); ?> US$</label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <input
                                type="number" 
                                name="sell_price_usd" 
                                id="sell_price_usd" 
                                class="form-control"
                                value=""
                                onkeydown="return checkQuantity(event)">
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="base_price_usd"><?php echo $this->lang->line('base_price'); ?> US$</label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <input
                                type="number" 
                                name="base_price_usd" 
                                id="base_price_usd" 
                                class="form-control"
                                value=""
                                onkeydown="return checkQuantity(event)">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = function(){
        $('[data-event="save"]').click(function(){
            bootbox.confirm({
                message: '<?php echo $this->lang->line('pv_modal'); ?>',
                buttons: {
                    confirm: {
                        label: '<?php echo $this->lang->line('yes');?>',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: '<?php echo $this->lang->line('no');?>',
                        className: 'btn-primary'
                    }
                },
                callback: function (result) {
                    if (result) {
                        $('#save_pv_upload_form').submit();
                    }
                }
            });
        });

        $('table[id^="items_orders_list_" ').DataTable({
            "scrollX": true,
            // aaSorting: []
            ordering: false,
            filter: false,
            pageLength: 5,
            lengthChange: false,
        });

        $('div[id^="collapse-order-receive-"').on('shown.bs.collapse', function () {
            $(this).find('table').DataTable().draw();
        });

		var dataSalesman = (<?php echo json_encode($salesman_list); ?> || []);
		var dataVendor = (<?php echo json_encode($vendor_list); ?> || []);
		var dataCustomerCode = (<?php echo json_encode($customer_code_list); ?> || []);
		var dataSize = (<?php echo json_encode($size_list); ?> || []);
		var dataUnit = (<?php echo json_encode($unit_list); ?> || []);
		var dataColor = (<?php echo json_encode($color_list); ?> || []);
        var dataSizeUnit = (<?php echo json_encode($size_unit_list); ?> || []);
        
		var dataFilterSalesman = [];
        dataSalesman.forEach(function(element) {
			dataFilterSalesman[dataFilterSalesman.length] = {
				value: element['komoku_name_2']
			};
        });
        var dataFilterVendor = [];
        dataVendor.forEach(function(element) {
			dataFilterVendor[dataFilterVendor.length] = {
				value: element['short_name']
			};
        });
        var dataFilterCustomerCode = [];
        dataCustomerCode.forEach(function(element) {
			dataFilterCustomerCode[dataFilterCustomerCode.length] = {
                value: element['komoku_name_2'],
                key: element['kubun']
			};
        });
        var dataFilterSize = [];
        dataSize.forEach(function(element) {
			dataFilterSize[dataFilterSize.length] = {
                value: element['komoku_name_2'],
                key: element['kubun']
			};
        });
        var dataFilterUnit = [];
        dataUnit.forEach(function(element) {
			dataFilterUnit[dataFilterUnit.length] = {
                value: element['komoku_name_2'],
                key: element['kubun']
			};
        });
        var dataFilterColor = [];
        dataColor.forEach(function(element) {
			dataFilterColor[dataFilterColor.length] = {
				value: element['komoku_name_2'],
                key: element['kubun']
			};
        });
        var dataFilterSizeUnit = [];
        dataSizeUnit.forEach(function(element) {
			dataFilterSizeUnit[dataFilterSizeUnit.length] = {
				value: element['komoku_name_2'],
                key: element['kubun']
			};
        });
        
        $("#salesman").autocomplete({
			lookup: dataFilterSalesman,
			minChars: 0,
			onSelect: function () {}
        });
        $("#vendor").autocomplete({
			lookup: dataFilterVendor,
			minChars: 0,
            width: '250px',
			onSelect: function () {}
        });
        $("#customer_name").autocomplete({
			lookup: dataFilterCustomerCode,
			minChars: 0,
			onSelect: function (customer) {
                $("#customer_code").val(customer.key);
            }
        });
        $("#size").autocomplete({
			lookup: dataFilterSize,
			minChars: 0,
			onSelect: function () {}
        });
        $("#unit").autocomplete({
			lookup: dataFilterUnit,
			minChars: 0,
			onSelect: function () {}
        });
        $("#color").autocomplete({
			lookup: dataFilterColor,
			minChars: 0,
			onSelect: function () {}
        });
        $("#size_unit").autocomplete({
			lookup: dataFilterSizeUnit,
			minChars: 0,
			onSelect: function () {}
        });

        $form = $('#add_product_modal form');
        $('table[id^="items_orders_list_"').on('click', '[data-event="add-product"]', function() {
            var tr = $(this).closest("tr");
            var item = tr.data('item');
            $form.find('[name="item_code"]').val(item.item_code);
            $form.find('[name="item_name"]').val(item.item_name);
            $form.find('[name="jp_code"]').val(item.jp_code);
            $form.find('[name="sell_price_usd"]').val(item.sell_price);
            $form.find('[name="salesman"]').val($('#staff').val());
            $form.find('[name="size"]').val(item.size);
            $form.find('[name="unit"]').val(item.unit);
            $form.find('[name="color"]').val(item.color);
            $form.find('[name="size_unit"]').val(item.size_unit);
        });

        $('#btnSave').click( function() {
            var validate = $("#add_product_form").validationEngine('validate');
            if(!validate) { return; }

            bootbox.confirm({
                title: "<?php echo $this->lang->line('MTS0010_I002');?>",
                message: '<h4 style="color:red;">' + $("#item_code").val() + '</h4>',
                buttons: {
                    confirm: {
                        label: '<?php echo $this->lang->line('yes');?>',
                        className: 'btn-success',
                    },
                    cancel: {
                        label: '<?php echo $this->lang->line('no');?>',
                        className: 'btn-primary'
                    }
                },
                callback: function(result) {
                    if(result) {
                        var dataCheck = $('#add_product_form').serializeArray();
                        $.ajax({
                            url: '<?php echo base_url('order_received/do_save_product') ?>',
                            type: "POST",
                            data: dataCheck,
                            dataType: 'json',
                            async: false,
                            success: function(response) {
                                $('#add_product_modal').modal('hide');
                                if (response.success) {
                                    window.location.reload();
                                } else {
                                    bootbox.alert({
                                        title: lang['response_error'],
                                        message: response.message,
                                    });
                                }
                            },
                            error: function(error) {
                                $('#add_product_modal').modal('hide');
                                bootbox.alert({
                                    message: error.statusText,
                                });
                            }
                        });
                    }
                }
            }); 
        });
    }
</script>
