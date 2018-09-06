<?php //print_r( $orderReceived);
	$status_finish = in_array($orderReceived['status'], explode(",", STATUS_FINISH));
?>

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $this->lang->line($orderReceived['order_receive_no'] ? 'update_order_receied' : 'order_received_add');$title; ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <?php if ($orderReceived['partition_no']): ?>
                      <li><button class="btn btn-info" onclick="window.location.href='<?php echo base_url("order_received/index/"); ?>'"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back') ?></button></li>
                    <?php else: ?>
                      <li><button class="btn btn-info" onclick="window.location.href='<?php echo base_url(); ?>order_received/add'" ><i class="fa fa-refresh"></i> <?php echo $this->lang->line('clear'); ?></button>
                    <?php endif;?>
                    </li>
                    <li>
                      <button class="btn btn-primary" id="save_update_action" type="button" form="order_received_form" <?php echo $status_finish ? 'disabled' : '' ?>>
                        <?php if ($orderReceived['partition_no']): ?>
                          <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                        <?php else: ?>
                          <i class="fa fa-arrow-right"></i> <?php echo $this->lang->line('detail'); ?>
                        <?php endif;?>
                      </button>
                    </li>
                    <?php if ($orderReceived['partition_no']): ?>
                      <li>
                        <button class="btn btn-info" onclick="window.location.href='<?php echo base_url() . 'order_received/add_details/' . trim($orderReceived['urlPrimaryKey']) ?>'" >
                          <i class="fa fa-arrow-right"></i> <?php echo $this->lang->line('detail'); ?>
                        </button>
                      </li>
                    <?php endif;?>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
							<div class="panel panel-default">
								<div class="panel-body">
									<form id="order_received_form" class="form-horizontal myform" action="<?php echo base_url(); ?>order_received/save" method="post">
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="order_receive_no" ><?php echo $this->lang->line('order_received_no'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<div class="col-md-9 col-sm-8 col-xs-8 has-clear has-feedback no-padding-left no-padding-right">
													<input type="hidden" id="status" name="status" value="<?php echo $orderReceived['status']; ?>" />
													<input type="text" class="form-control validate[required] text-uppercase" id="order_receive_no" name="order_receive_no" maxlength="50" value="<?php echo $orderReceived['order_receive_no']; ?>" <?php echo $orderReceived['partition_no'] ? 'readonly' : '' ?>>
													<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: -5px !important"></span>
												</div>
												<div class="col-md-3 col-sm-4 col-xs-4 no-padding-right" style="padding-left: 2px;">
													<input type="text" class="form-control" value="<?php echo isset($orderReceived['partition_no_show']) ? $orderReceived['partition_no_show'] : ''; ?>" readonly>
													<input type="hidden" class="form-control" id="partition_no" name="partition_no" value="<?php echo $orderReceived['partition_no']; ?>" readonly>
												</div>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="order_receive_date" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('order_received_date'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control hasDatepicker <?php echo $status_finish ? '' : 'date' ?> validate[required]" id="order_receive_date" name="order_receive_date" maxlength="10" value="<?php echo ($orderReceived['order_receive_date']); ?>" <?php echo $orderReceived['partition_no'] ? 'readonly' : '' ?>>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important"></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="input_user" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('input_user'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<?php if(!$status_finish): ?>
													<select class="form-control validate[required] no-padding-right" id="input_user" name="input_user">
														<option value=""></option>
														<?php foreach ($employeeList as $employee): ?>
															<option value="<?php echo $employee['employee_id']; ?>" <?php echo $employee['employee_id'] == $orderReceived['input_user'] ? 'selected' : '' ?>><?php echo $employee['first_name']; ?> <?php echo $employee['last_name']; ?></option>
														<?php endforeach?>
													</select>
												<?php else: ?>
													<?php
															$inputUserName = '';
															foreach($employeeList as $employee) {
																if($employee['employee_id'] == $orderReceived['input_user']) {
																	$inputUserName = $employee['first_name'] . ' ' . $employee['last_name'];
																	break;
																}
															}
														?>
													<input type="hidden" class="form-control" id="input_user" name="input_user" value="<?php echo $orderReceived['input_user']; ?>"/>
													<input type="text" class="form-control" value="<?php echo $inputUserName; ?>" readonly/>
												<?php endif ?>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-1"></div>
											<div class="col-sm-4" style="border: 1px solid blue; border-radius: 5px;">
												<label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="kubun" ><?php echo $this->lang->line('from'); ?></label>
												<div class="col-sm-6 no-padding-left" style="margin-left: 0px;">
													<p style="margin:0px; padding-top: 4px; padding-bottom: 4px">
														<input type="radio" class="flat" name="kubun" id="kubunS" value="<?php echo ORDER_RECEIVED_TYPE_JP ?>" <?php echo $orderReceived['kubun'] == '' || $orderReceived['kubun'] == ORDER_RECEIVED_TYPE_JP ? 'checked' : ''; ?> <?php echo $status_finish ? 'disabled' : '' ?>/> Shimada Japan
													</p>
												</div>
												<div class="col-sm-3 no-padding-left" style="margin-left: 0px;">
													<p style="margin:0px; padding-top: 4px; padding-bottom: 4px">
														<input type="radio" class="flat" name="kubun" id="kubunO" value="<?php echo ORDER_RECEIVED_TYPE_OTHER ?>"  <?php echo $orderReceived['kubun'] == ORDER_RECEIVED_TYPE_OTHER ? 'checked' : ''; ?> <?php echo $status_finish ? 'disabled' : '' ?>/> Other
													</p>
												</div>
											</div>
											<div class="col-sm-2"></div>
											<div class="col-sm-4" style="border: 1px solid blue; border-radius: 5px;">
												<label class="control-label col-sm-3 form-title no-padding-left no-padding-right" for="seller_kb" ><?php echo $this->lang->line('seller'); ?></label>
												<div class="col-sm-4 no-padding-left" style="margin-left: 0px;">
													<p style="margin:0px; padding-top: 4px; padding-bottom: 4px">
														&nbsp;&nbsp;
														<input type="radio" class="flat" name="seller_kb" id="sellerEPE" value="<?php echo '1' ?>"  <?php echo $orderReceived['seller_kb'] == '' || $orderReceived['seller_kb'] == '1' ? 'checked' : ''; ?> <?php echo $status_finish ? 'disabled' : '' ?>/> EPE
													</p>
												</div>
												<div class="col-sm-4 no-padding-left" style="margin-left: 0px;">
													<p style="margin:0px; padding-top: 4px; padding-bottom: 4px">
														&nbsp;&nbsp;
														<input type="radio" class="flat" name="seller_kb" id="sellerHANOI" value="<?php echo '2' ?>"  <?php echo $orderReceived['seller_kb'] == '2' ? 'checked' : ''; ?> <?php echo $status_finish ? 'disabled' : '' ?>/> HANOI
													</p>
												</div>
											</div>
											<div class="col-sm-1"></div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="odr_department" ><?php echo $this->lang->line('department'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control" id="odr_department" name="odr_department" maxlength="20" value="<?php echo $orderReceived['odr_department']; ?>" <?php echo ($orderReceived['kubun'] == ORDER_RECEIVED_TYPE_OTHER || $status_finish) ? 'readonly' : ''; ?>>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label id="staff_label" class="control-label form-title" for="staff" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('sales_man');?><span class="required" >*</span></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control validate[required]" id="staff" name="staff" maxlength="20" value="<?php echo $orderReceived['staff']; ?>">
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label id="assistance_label" class="control-label form-title" for="assistance" ><?php echo str_repeat("&nbsp;", 5);?><?php echo $orderReceived['kubun'] == ORDER_RECEIVED_TYPE_OTHER ? $this->lang->line("staff") : $this->lang->line("assistance"); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control" id="assistance" name="assistance" maxlength="20" value="<?php echo $orderReceived['assistance']; ?>">
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="customer" ><?php echo $this->lang->line('customer'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left has-clear has-feedback">
												<input type="text" class="form-control validate[required]" id="customer" name="customer" maxlength="100" value="<?php echo $orderReceived['customer']; ?>" <?php echo (!$status_finish) ? "" : 'readonly'; ?>>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
												<label class="control-label form-title" for="identify_name" ><?php echo str_repeat("&nbsp;", 10); echo $this->lang->line('identify_name'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<?php if(!$status_finish): ?>
													<select class="form-control validate[required]" id="identify_name" name="identify_name">
														<option value=""></option>
														<?php foreach ($warehouseList as $identify): ?>
															<option
																value="<?php echo $identify['kubun']; ?>"
																<?php echo (trim($identify['kubun']) == trim($orderReceived['identify_name']) ? 'selected' : ''); ?>
															><?php echo $identify['komoku_name_2']; ?></option>
														<?php endforeach?>
													</select>
												<?php else: ?>
													<input type="text" class="form-control" id="identify_name" name="identify_name" value="<?php echo $orderReceived['identify_name']; ?>" readonly>
												<?php endif ?>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="delivery_to" ><?php echo $this->lang->line('delivery_to'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left has-clear has-feedback">
												<input type="text" class="form-control validate[required]" id="delivery_to" name="delivery_to" maxlength="100" value="<?php echo $orderReceived['delivery_to']; ?>" <?php echo (!$status_finish) ? "" : 'readonly'; ?>>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
												<label class="control-label form-title" for="head_office" ><?php echo str_repeat("&nbsp;", 10); echo $this->lang->line('head_office'); ?></label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control" id="head_office" name="head_office" maxlength="100" value="<?php echo $orderReceived['head_office']; ?>" <?php echo (!$status_finish) ? "" : 'readonly'; ?>>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="delivery_address" ><?php echo $this->lang->line('address'); ?></label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left has-clear has-feedback">
												<textarea spellcheck="false" class="form-control form-rounded" rows="4" id="delivery_address" name="delivery_address" readonly><?php echo $orderReceived['delivery_address'] ?></textarea>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
												<label class="control-label form-title" for="head_office_address" ><?php echo str_repeat("&nbsp;", 10); echo $this->lang->line('address'); ?></label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right has-clear has-feedback">
												<textarea spellcheck="false" class="form-control form-rounded" rows="4" id="head_office_address" name="head_office_address" readonly><?php echo $orderReceived['head_office_address'] ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="payment_term" ><?php echo $this->lang->line('payment_term'); ?></label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left">
												<input type="text" class="form-control" id="payment_term" name="payment_term" value="<?php echo $orderReceived['payment_term']; ?>" readonly>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
												<label class="control-label form-title" for="delivery_term" ><?php echo str_repeat("&nbsp;", 10); echo $this->lang->line('delivery_term'); ?>Delivery Term</label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
												<input type="text" class="form-control" id="delivery_term" name="delivery_term" value="<?php echo $orderReceived['delivery_term']; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="fee_term" ><?php echo $this->lang->line('fee_term'); ?>Fee Term</label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left">
												<input type="text" class="form-control" id="fee_term" name="fee_term" value="<?php echo $orderReceived['fee_term']; ?>" readonly>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left">
												<label class="control-label form-title" for="vat_by" ><?php echo str_repeat("&nbsp;", 10); echo $this->lang->line('vat_by'); ?>VAT By</label>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
												<input type="text" class="form-control" id="vat_by" name="vat_by" value="<?php echo $orderReceived['vat_by']; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="currency" ><?php echo $this->lang->line('currency'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<?php if(!$status_finish): ?>
													<select class="form-control validate[required]" id="currency" name="currency">
														<option value=""></option>
														<?php foreach ($currency as $cur): ?>
															<option
																value="<?php echo $cur['currency_name']; ?>"
																<?php echo (trim($cur['currency_name']) == trim($orderReceived['currency']) ? 'selected' : ''); ?>
															><?php echo $cur['currency_name']; ?></option>
														<?php endforeach?>
													</select>
												<?php else: ?>
													<input type="text" class="form-control" id="currency" name="currency" value="<?php echo $orderReceived['currency']; ?>" readonly>
												<?php endif ?>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="usd_vnd" ><?php echo str_repeat("&nbsp;", 5);?>USD→VND <?php echo $this->lang->line('rate'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control" id="usd_vnd" name="usd_vnd" value="<?php echo $orderReceived['rate_usd']; ?>" <?php echo $status_finish ? 'readonly' : '' ?> onkeydown="return checkQuantity(event)">
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="jpy_vnd" ><?php echo str_repeat("&nbsp;", 5);?>JPY→VND <?php echo $this->lang->line('rate'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control" id="jpy_vnd" name="jpy_vnd" value="<?php echo $orderReceived['rate_jpy']; ?>" <?php echo $status_finish ? 'readonly' : '' ?> onkeydown="return checkQuantity(event)">
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="contract_no" ><?php echo $this->lang->line('contract_no'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control text-uppercase" id="contract_no" name="contract_no" maxlength="20" value="<?php echo $orderReceived['contract_no']; ?>" <?php echo $status_finish ? 'readonly' : '' ?>>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;" ></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="style" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('style'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control text-uppercase" id="style" name="style" maxlength="10" value="<?php echo $orderReceived['style']; ?>" <?php echo $status_finish ? 'readonly' : '' ?>>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="create_user" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('create_user'); ?> </label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<input type="text" class="form-control" id="create_user" name="create_user" value="<?php echo $orderReceived['partition_no'] ? $orderReceived['create_user'] : $user['employee_id']; ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="delivery_date" ><?php echo $this->lang->line('wish_delivery_date'); ?><span class="required">*</span></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<input type="text" class="form-control hasDatepicker <?php echo $status_finish ? '' : 'date' ?> validate[required]" id="delivery_date" name="delivery_date" value="<?php echo date_format(date_create($orderReceived['delivery_date']), 'd M, Y'); ?>" <?php echo $status_finish ? 'readonly' : '' ?>>
												<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden" style="right: 0px !important;"></span>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="tax_classification" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('tax'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right has-clear has-feedback">
												<?php if(!$status_finish): ?>
													<select class="form-control" id="tax_classification" name="tax_classification">
														<option value=""></option>
														<?php foreach ($taxClassifications as $taxClassification): ?>
															<option
																value="<?php echo $taxClassification['tax_id']; ?>"
																<?php echo $orderReceived['tax'] == $taxClassification['tax_id'] ? 'selected' : ''; ?>
															>
																<?php echo $taxClassification['tax_name'] ?>
															</option>
														<?php endforeach?>
													</select>
												<?php else: ?>
													<input type="hidden" class="form-control" id="tax_classification" name="tax_classification" value="<?php echo $orderReceived['tax']; ?>">
													<?php
														$taxName = "";
														foreach ($taxClassifications as $taxClassification) {
																if($orderReceived['tax'] == $taxClassification['tax_id']) {
																	$taxName = $taxClassification["tax_name"];
																	break;
																}
														}
													?>
													<input type="text" class="form-control" value="<?php echo $taxName; ?>" readonly>
												<?php endif ?>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="create_date" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('create_date'); ?></label>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right">
												<input type="text" class="form-control" id="create_date" name="create_date" value="<?php echo $orderReceived['partition_no'] ? $orderReceived['create_date'] : date("Y/m/d"); ?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<div class="col-md-2 col-sm-2 col-xs-2">
												<label class="control-label form-title" for="note" ><?php echo $this->lang->line('note'); ?></label>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right has-clear has-feedback">
												<textarea spellcheck="false" type="text" class="form-control" style="height: 78px;" maxlength="100" name="note" id="note"><?php echo $orderReceived['note']; ?></textarea>
											</div>
											<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
												<div class="form-group">
													<div class="col-md-6 col-sm-6 col-xs-6">
														<label class="control-label form-title" for="edit_user" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('update_user'); ?></label>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
														<input type="text" class="form-control" id="edit_user" name="edit_user" value="<?php echo $orderReceived['partition_no'] ? $user['employee_id'] : ''; ?>" readonly>
														<input type="hidden" class="form-control" id="edit_user_origin" name="edit_user_origin" value="<?php echo $orderReceived['edit_user']; ?>">
													</div>
												</div>
												<div class="form-group">
													<div class="col-md-6 col-sm-6 col-xs-6">
														<label class="control-label form-title" for="edit_date" ><?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('update_date'); ?></label>
													</div>
													<div class="col-md-6 col-sm-6 col-xs-6 no-padding-left no-padding-right">
														<input type="text" class="form-control" id="edit_date" name="edit_date" value="<?php echo $orderReceived['partition_no'] ? date("Y/m/d H:i:s") : ''; ?>" readonly>
														<input type="hidden" class="form-control" id="edit_date_origin" name="edit_date_origin" value="<?php echo $orderReceived['edit_date']; ?>">
													</div>
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
</div>

<script>
var dataFilterSalesman = [];
var salesmanList = <?php echo json_encode($salesman_list); ?>;
window.onload = function() {
	    salesmanList.forEach(function(el){
            dataFilterSalesman.push({value: el.komoku_name_2, data : el.kubun});
		});
        $("#staff").autocomplete({
            lookup: dataFilterSalesman,
						minChars: 0,
						onSelect: function(){
							$(this).change();
						}
        });
		<?php if(isset($orderReceived['error_message'])): ?>
			if('<?php echo isset($orderReceived['error_message'])?'yes':''; ?>' !== '') {
				snackbarShow("<?php echo $orderReceived['error_message'] ?>");
			}
		<?php endif;?>

		var dataCustomer = (<?php echo json_encode($customerList); ?> || []);
		var dataFilterCustomer = [];
		var dataFilterDeliveryTo = [];
		var dataFilterHeadOffice = [];
		// console.log(dataCustomer);
		dataCustomer.forEach(function(element) {
			dataFilterCustomer[dataFilterCustomer.length] = {
				value: element['company_name']||"",
				key: element['company_id'],
				payment_term: element['payment_term'],
				delivery_term: element['delivery_term'],
				fee_term: element['fee_term'],
				vat_by: element['vat_by'],
			};
		});

		var lastDate = new Date();
		<?php if(!isset($orderReceived['partition_no'])):?>
			$("#order_receive_date").datepicker('setDate', lastDate);
			$("#delivery_date").datepicker('setDate', lastDate);
			$("#input_user").val("<?php echo $curUser['employee_id']; ?>");
		<?php endif; ?>

		$("#customer").autocomplete({
			lookup: dataFilterCustomer,
			minChars: 0,
			onSelect: function (customer) {
				$(this).change();
				// console.log(customer);
				$("#delivery_to").val("").change();
				$("#head_office").val("").change();
				$('#delivery_address').val("");
				$('#head_office_address').val("");
				$('#payment_term').val("");
				$('#delivery_term').val("");
				$('#fee_term').val("");
				$('#vat_by').val("");
				dataFilterDeliveryTo = [];
				dataFilterHeadOffice = [];
				dataCustomer.forEach(function(element) {
					element['branches'].forEach(function(elementBranch) {
						if(elementBranch['company_id'] === customer.key) {
							dataFilterDeliveryTo[dataFilterDeliveryTo.length] = {
								value: elementBranch['branch_name'],
								data: elementBranch
							};
						}
					});
					element['head_offices'].forEach(function(elementOffice) {
						if(elementOffice['company_id'] === customer.key) {
							dataFilterHeadOffice[dataFilterHeadOffice.length] = {
								value: elementOffice['head_office_name'],
								data: elementOffice
							};
						}
					});
				});
				$('#payment_term').val(customer.payment_term&&customer.payment_term.split(";")[0]);
				// $('#delivery_term').val(customer.delivery_term);
				$('#fee_term').val(customer.fee_term);
				$('#vat_by').val(customer.vat_by);
				$("#delivery_to").autocomplete({
					lookup: dataFilterDeliveryTo,
					minChars: 0,
					onSelect: function (deliveryTo) {
						$(this).change();
						// set data to Delivery To Address
						var delivery_address = (deliveryTo.data.branch_address || '');
						$('#delivery_address').val(delivery_address);
						$('#delivery_term').val(deliveryTo.data.delivery_term);
					}
				});
				$("#head_office").autocomplete({
					lookup: dataFilterHeadOffice,
					minChars: 0,
					onSelect: function (headOffice) {
						$(this).change();
						// set data to Head Office Address
						var address = (headOffice.data.head_office_address || '');
						$('#head_office_address').val(address);
					}
				});
			}
		});
		
		$('#save_update_action').click(function() {
			if (!$('#order_received_form').validationEngine('validate')) {
				return;
			} else {
				bootbox.confirm({
						title: "<?php echo $this->lang->line('POD0020_I001'); ?>",
						message: '<h4 style="color:red;">' + $('#order_receive_no').val() + '</h4>',
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
										$('#order_received_form').submit();
								}
						}
				});
			}
		});

		$('input[name="kubun"]').on('ifChanged', function (event) {
			if($(this).val() == 1){
				$('#odr_department').attr('readonly',false);
				$('#assistance').val('');
				$('#assistance_label').html('<?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('assistance'); ?>');
			}else if($(this).val() == 2){
				$('#odr_department').attr('readonly',true);
				$('#odr_department').val('');
				$('#assistance').val("<?php echo $curUser['employee_id']; ?>");
				$('#assistance_label').html('<?php echo str_repeat("&nbsp;", 5); echo $this->lang->line('staff'); ?>');
			}
		});
};
</script>
