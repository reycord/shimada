<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <!-- <li><button class="btn btn-info" onclick="window.history.back();"><i class="fa fa-arrow-left"></i> Back</button>
                    </li> -->
                    <li><button class="btn btn-info" onclick="window.location.href='<?php echo base_url(); ?>login/logout'"><i class="fa fa-sign-out"></i> <?php echo $this->lang->line('logout')?></button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            <br />
                <div class="container">
									<div class="col-sm-4">
										<div style="text-align: center;">
											<img src="<?php echo base_url(); ?>images/img.jpg" alt="">
										</div>
										<div class="das_panel">
											<div class="das_title">
												<?php echo $this->lang->line('change_password');?>
											</div>
											<div class="change_pass_form">
												<form class="form-horizontal form-label-left" >
													<div class="form-group">
															<label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left" for="old_password"><?php echo $this->lang->line('old_password');?><span class="required">*</span></label>
															<div class="col-md-7 col-sm-7 col-xs-7">
																<input type="password" id="old_password" name="old_password" class="form-control">
															</div>
													</div>
													<div class="form-group">
														<label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left" for="new_password"><?php echo $this->lang->line('new_password');?><span class="required">*</span></label>
														<div class="col-md-7 col-sm-7 col-xs-7">
															<input type="password" id="new_password" name="new_password" class="form-control">
														</div>
													</div>
													<div class="form-group">
														<label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-top" for="confirm_password"><?php echo $this->lang->line('confirm_password');?><span class="required">*</span></label>
														<div class="col-md-7 col-sm-7 col-xs-7">
															<input type="password" id="confirm_password" name="confirm_password" class="form-control">
														</div>
													</div>
													<div class="form-group">
														<div class="col-md-9 col-md-offset-3 col-sm-offset-3">
															<button id="send" type="button" class="btn btn-success"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
														</div>
													</div>
												</form>
											</div>
										</div>
										<div class="col-sm-12">
										<textarea spellcheck="false" type="text" class="form-control" id="free_writing" name="free_writing" rows="2" placeholder="Free Writing"></textarea>
										</div>
									</div>
									<div class="col-sm-8">
                    <form class="form-horizontal form-label-left">
											<div class="form-group">
												<input type="text" name="employee_id" id="employee_id" value="<?php //echo ($type == '1' || $type == '2' ? $employee['employee_id'] : ''); ?>" hidden>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="user_id"><?php echo $this->lang->line('user_id');?><span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-4" style="padding-left: 10px;">
													<input type="text" id="user_id" name="user_id" class="form-control" maxlength="20" value="1011<?php //echo ($type == '1' || $type == '2' ? $employee['employee_id'] : ''); ?><?php //echo ($type == '0' ? set_value('user_id') : ''); ?>" <?php //echo ($type == '1' || $type == '2' ? 'readonly' : ''); ?> >
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="password"><?php echo $this->lang->line('password');?><span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="password" id="password" name="password" class="form-control" maxlength="20" value="123<?php //echo ($type == '1' || $type == '2' ? $employee['password'] : ''); ?><?php //echo ($type == '0' ? set_value('password') : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="first_name"><?php echo $this->lang->line('first_name');?><span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="text" id="first_name" name="first_name" class="form-control" value="Quang<?php //echo ($type == '1' || $type == '2' ? $employee['first_name'] : ''); ?><?php //echo ($type == '0' ? set_value('first_name') : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="last_name"><?php echo $this->lang->line('last_name');?><span class="required">*</span></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="text" id="last_name" name="last_name" class="form-control" value="Dat<?php //echo ($type == '1' || $type == '2' ? $employee['last_name'] : ''); ?><?php //echo ($type == '0' ? set_value('last_name') : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="first_name_kata"><?php echo $this->lang->line('first_name');?> (Katakana)</label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="text" id="first_name_kata" name="first_name_kata" class="form-control" value="<?php //echo ($type == '1' || $type == '2' ? $employee['first_name'] : ''); ?><?php //echo ($type == '0' ? set_value('first_name') : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="last_name_kata"><?php echo $this->lang->line('last_name');?> (Katakana)</label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="text" id="last_name_kata" name="last_name_kata" class="form-control" value="<?php //echo ($type == '1' || $type == '2' ? $employee['last_name'] : ''); ?><?php //echo ($type == '0' ? set_value('last_name') : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="company_email"><?php echo $this->lang->line('company_email');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="text" id="conpany_email" name="conpany_email" class="form-control" value="quangtuandat@gmail.com<?php //echo ($type == '1' || $type == '2' ? $employee['email_job'] : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="email"><?php echo $this->lang->line('persional_email');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="text" id="email" name="email" class="form-control" value="datpro0399@gmail.com<?php //echo ($type == '1' || $type == '2' ? $employee['email_personal'] : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="mobile"><?php echo $this->lang->line('mobile');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="text" id="mobile" name="mobile" class="form-control" value="0978977910<?php //echo ($type == '1' || $type == '2' ? $employee['phone'] : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="phone"><?php echo $this->lang->line('telephone');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<input type="text" id="phone" name="phone" class="form-control" value="(+84)2882145214<?php //echo ($type == '1' || $type == '2' ? $employee['phone'] : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="birthday"><?php echo $this->lang->line('birthday');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <div class="input-group date" id="birthday">
														<input type="text" class="form-control" name="birthday" value="1994/03/07<?php //echo ($type == '1' || $type == '2' ? $employee['birthday'] : ''); ?>" <?php //echo ($type == '2' ? 'disabled' : ''); ?>/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
                          </div>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title" for="gender"><?php echo $this->lang->line('gender');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<p style="margin:0px; padding-top: 8px">
															Male <input type="radio" checked="checked" class="flat" name="gender" id="genderM" value="male" <?php //echo ((($type == '0') || (($type == '1' || $type == '2') && $employee['gender'] == 'male')) ? 'checked' : ''); ?>  <?php //echo ($type == '2' ? 'disabled' : ''); ?>/>
															&nbsp;&nbsp;
															Female <input type="radio" class="flat" name="gender" id="genderF" value="female"  <?php //echo (($type == '1' || $type == '2') && $employee['gender'] == 'female' ? 'checked' : ''); ?> <?php //echo ($type == '2' ? 'disabled' : ''); ?>/>
													</p>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="address"><?php echo $this->lang->line('address');?></label>
                        <div class="col-md-10 col-sm-10 col-xs-10">
													<input type="text" id="address" name="address" class="form-control" value="Trang Bom city<?php //echo ($type == '1' || $type == '2' ? $employee['address'] : ''); ?>" <?php //echo ($type == '2' ? 'readonly' : ''); ?>>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="department"><?php echo $this->lang->line('department');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<select name="department" id="department" class="form-control" <?php //echo ($type == '2' ? 'disabled' : ''); ?>>
                            <option value="">Dev</option>
                            <?php //foreach ($departmentList as $department): ?>
                              <option value="<?php //echo $department['department_id']; ?>" <?php //echo (($type == '1' || $type == '2') && $employee['department'] == $department['department_id'] ? 'selected' : ''); ?>><?php //echo $department['department_name']; ?></option>
                            <?php //endforeach?>
                          </select>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="position"><?php echo $this->lang->line('position');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<select name="position" id="position" class="form-control" <?php //echo ($type == '2' ? 'disabled' : ''); ?>>
                            <option value="">Programer</option>
                            <?php //foreach ($positionList as $position): ?>
                              <option value="<?php //echo $position['position_id']; ?>" <?php //echo (($type == '1' || $type == '2') && $employee['position'] == $position['position_id'] ? 'selected' : ''); ?>><?php //echo $position['position_name']; ?></option>
                            <?php //endforeach?>
                          </select>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="date_entry"><?php echo $this->lang->line('date_entry');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <div class="input-group date" id="date_entry">
														<input type="text" class="form-control" name="date_entry" value="<?php //echo ($type == '1' || $type == '2' ? $employee['entry_date'] : ''); ?>" <?php //echo ($type == '2' ? 'disabled' : ''); ?>/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
                          </div>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title" for="activated"><?php echo $this->lang->line('activated');?></label>
                        <div class="col-md-1 col-sm-1 col-xs-1">
													<p style="padding: 5px;">
                              <input checked="checked" type="checkbox" name="activated" id="activated" value="1" class="flat" <?php //echo ((($type == '1' || $type == '2') && $employee['status'] == 1) || ($type == '0') ? 'checked' : ''); ?> <?php //echo ($type == '2' ? 'disabled' : ''); ?>/>
                          <p>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title" for="admin"><?php echo $this->lang->line('admin');?></label>
                        <div class="col-md-1 col-sm-1 col-xs-1">
													<p style="padding: 5px;">
                            <input type="checkbox" name="admin" id="admin" value="1" class="flat" <?php //echo ((($type == '1' || $type == '2') && $employee['status'] == 1) || ($type == '0') ? 'checked' : ''); ?> <?php //echo ($type == '2' ? 'disabled' : ''); ?>/>
                          <p>
                        </div>
                      </div>
											<div class="form-group">
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="classify"><?php echo $this->lang->line('classify');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
													<select name="classify" id="classify" class="form-control" <?php //echo ($type == '2' ? 'disabled' : ''); ?>>
                            <option value=""></option>
                            <option value="1" selected="selected">Internship</option>
                            <option value="2">Probationary employee</option>
                            <option value="3">Regular employee</option>
                          </select>
                        </div>
                        <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="retirement_date"><?php echo $this->lang->line('retirement_date');?></label>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                          <div class="input-group date" id="retirement_date">
														<input type="text" class="form-control" name="retirement_date" value="<?php //echo ($type == '1' || $type == '2' ? $employee['birthday'] : ''); ?>" <?php //echo ($type == '2' ? 'disabled' : ''); ?>/>
														<span class="input-group-addon">
															<span class="glyphicon glyphicon-calendar"></span>
														</span>
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
