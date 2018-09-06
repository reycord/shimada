<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button class="btn btn-info" onclick="window.history.back()">
                            <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back');?>
                        </button>
                    </li>
					<li>
						<button type="button" id="btnSave" class="btn btn-primary" style="display:<?php echo ($type == '2' ? 'none' : 'block'); ?>">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save')?>
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-info" onclick="window.location.href='<?php echo base_url(); ?>login/logout'" style="display:<?php echo ($type == '1' || $type == '0' ? 'none' : 'block'); ?>">
                            <i class="fa fa-sign-out"></i> <?php echo $this->lang->line('logout')?>
                        </button>
                    </li>
                    <li >
                        <button type="button" id="btn_delete_user" class="btn btn-danger" style="display:<?php echo ($type == '2' ? 'none' : 'block'); ?>"
                        <?php echo $type == '0' ? 'disabled' : ''?>
                        >
                            <i class="fa fa-trash"></i> <?php echo $this->lang->line('delete')?>
                        </button>
					</li>
				</ul>
                <h5 field="err_old_password" class="text-center" style="color:red"></h5>
                <h5 field="err_exist" class="text-center" style="color:red"></h5>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<br />
				<!-- <div class="container"> -->
				<div class="container">
                    <form class="form-horizontal form-label-left" id="frm_new_user" action="" method="post">
                        <input type="hidden" name="type" id="type" value="<?php echo $type ?>" >
                        <input 
                            type="hidden" 
                            name="edit_date" 
                            id="edit_date" 
                            value="<?php echo set_value('edit_date', $type == '1' || $type == '2' ? (isset($employee['edit_date']) ? $employee['edit_date'] : ""): "")?>" 
                        />
                        <input type="hidden" name="icon" id="icon" value="img.jpg" />
                        <div class="col-sm-4">
                            <div  style="text-align: center;">
                                <a href="#" data-toggle="popover" data-trigger="focus" data-popover-content="#icon_picker" data-placement="right">
                                    <img style="width:128px; height: 128px;" id="avatar" src="<?php echo base_url(); ?>images/icon/<?php echo (!empty($employee['icon']) ? $employee['icon'] : 'img.jpg'); ?>" width:"128" height:"128" >
                                </a>
                            </div>
                            <div class="das_panel">
                                <div class="das_title">
                                    <?php echo $this->lang->line('change_password');?>
                                </div>
                                <div class="change_pass_form">
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left" for="old_password">
                                            <?php echo $this->lang->line('old_password');?>
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input 
                                                type="password" 
                                                id="old_password" 
                                                name="old_password" 
                                                class="form-control"
                                                maxlength="12"
                                                <?php echo $type == '0' || (isset($change_pass_flg) && $change_pass_flg == 1) ? 'disabled' : ''?>
                                            />
                                            <?php echo form_error('old_password'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left" for="new_password">
                                            <?php echo $this->lang->line('new_password');?>
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input 
                                                type="password" 
                                                id="new_password" 
                                                name="new_password"                                                
                                                class="form-control"
                                                maxlength="12"
                                                <?php echo isset($change_pass_flg) && $change_pass_flg == 1 ? 'disabled' : ''?>
                                            />
                                            <?php echo form_error('new_password'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-top" for="confirm_password">
                                            <?php echo $this->lang->line('confirm_password');?>
                                            <span class="required">*</span>
                                        </label>
                                        <div class="col-md-7 col-sm-7 col-xs-7">
                                            <input 
                                                type="password" 
                                                id="confirm_password" 
                                                name="confirm_password" 
                                                class="form-control"
                                                maxlength="12"
                                                <?php echo isset($change_pass_flg) && $change_pass_flg == 1 ? 'disabled' : ''?>
                                            />
                                            <?php echo form_error('confirm_password'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-5 col-sm-5 col-xs-5"></div>
                                        <div class="col-md-7 col-sm-7 col-xs-7" style="text-align: left;">
                                            <button id="btn_change_pass" type="button" class="btn btn-success" style="margin-left: 0;"
                                            <?php echo $type == '0' || (isset($change_pass_flg) && $change_pass_flg == 1) ? 'disabled' : ''?>
                                            >
                                                <i class="fa fa-save"></i>
                                                <?php echo $this->lang->line('save'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <textarea 
                                    spellcheck="false"
                                    type="text" 
                                    class="form-control" 
                                    id="free_writing" 
                                    name="free_writing" 
                                    rows="2" 
                                    placeholder="Free Writing"
                                    maxlength="200" 
                                    <?php echo $type == '2' ? 'readonly' : '' ?> 
                                ><?php echo set_value('free_writing', $type == '1' || $type == '2' ? $employee['note'] : '')?></textarea>
                            </div>
                        </div>
					    <div class="col-sm-8">
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="employee_id">
									<?php echo $this->lang->line('user_id');?>
									<span class="required">*</span>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback" style="padding-left: 10px;">
                                    <input 
                                        type="text" 
                                        id="employee_id" 
                                        name="employee_id" 
                                        class="form-control" 
                                        maxlength="10" 
                                        value="<?php echo set_value('employee_id', $type == '1' || $type == '2' ? trim($employee['employee_id']) : '')?>"
                                        <?php echo ($type=='1' || $type=='2' ? 'readonly' : ''); ?> 
                                    />
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <?php echo form_error('employee_id'); ?>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="birthday">
									<?php echo $this->lang->line('birthday');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<div class="input-group date" id="birthday">
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="birthday" 
                                            maxlength="10" 
                                            value="<?php echo set_value('birthday', $type == '1' || $type == '2' ? ($employee['birthday'] ? date_format(date_create($employee['birthday']), 'd M, Y') : '') : '')?>"
                                            <?php echo ($type=='2' ? 'disabled' : ''); ?>
                                        />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="first_name">
									<?php echo $this->lang->line('family_name');?>
									<span class="required">*</span>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="first_name" 
                                        name="first_name" 
                                        class="form-control" 
                                        maxlength="50" 
                                        value="<?php echo set_value('first_name', $type == '1' || $type == '2' ? $employee['first_name'] : '')?>"
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    />
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <?php echo form_error('first_name'); ?>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="last_name">
									<?php echo $this->lang->line('given_name');?>
									<span class="required">*</span>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="last_name" 
                                        name="last_name" 
                                        class="form-control" 
                                        maxlength="50" 
                                        value="<?php echo set_value('last_name', $type == '1' || $type == '2' ? $employee['last_name'] : '')?>"
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    />
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <?php echo form_error('last_name'); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="first_name_kana">
									<?php echo $this->lang->line('first_name');?> (Katakana)</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="first_name_kana" 
                                        name="first_name_kana" 
                                        class="form-control" 
                                        maxlength="50" 
                                        value="<?php echo set_value('first_name_kana', $type == '1' || $type == '2' ? $employee['first_name_kana'] : '')?>"
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    />
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="last_name_kana">
									<?php echo $this->lang->line('last_name');?> (Katakana)</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="last_name_kana" 
                                        name="last_name_kana" 
                                        class="form-control" 
                                        maxlength="50" 
                                        value="<?php echo set_value('last_name_kana', $type == '1' || $type == '2' ? $employee['last_name_kana'] : '')?>"                                       
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    />
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="company_email">
									<?php echo $this->lang->line('company_email');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="company_email" 
                                        name="company_email" 
                                        class="form-control validate[custom[email]]" 
                                        maxlength="50" 
                                        value="<?php echo set_value('company_email', $type == '1' || $type == '2' ? $employee['email_job'] : '')?>"
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    />
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <?php echo form_error('company_email'); ?>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="personal_email">
									<?php echo $this->lang->line('persional_email');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="personal_email" 
                                        name="personal_email" 
                                        class="form-control validate[custom[email]]" 
                                        maxlength="50" 
                                        value="<?php echo set_value('personal_email', $type == '1' || $type == '2' ? $employee['email_personal'] : '')?>"
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    >
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    <?php echo form_error('personal_email'); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="mobile">
									<?php echo $this->lang->line('mobile');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="mobile" 
                                        name="mobile"
                                        maxlength="15" 
                                        class="form-control" 
                                        value="<?php echo set_value('mobile', $type == '1' || $type == '2' ? $employee['phone'] : '')?>"
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    >
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="telephone">
									<?php echo $this->lang->line('telephone');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="telephone" 
                                        name="telephone" 
                                        class="form-control" 
                                        maxlength="15" 
                                        value="<?php echo set_value('telephone', $type == '1' || $type == '2' ? $employee['tel'] : '')?>"
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    >
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="postal_code">
									<?php echo $this->lang->line('postal_code');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="postal_code" 
                                        name="postal_code" 
                                        class="form-control" 
                                        maxlength="20" 
                                        value="<?php echo set_value('postal_code', $type == '1' || $type == '2' ? $employee['postal_code'] : '')?>"
                                        <?php echo ($type == '2' ? 'readonly' : ''); ?>
                                    >
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title" for="gender">
									<?php echo $this->lang->line('gender');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<p style="margin:0px; padding-top: 8px">
										Male
                                        <input 
                                            type="radio" 
                                            checked="checked" 
                                            class="flat" 
                                            name="gender" 
                                            id="genderM" 
                                            value="male" 
                                            <?php if (set_value('gender', $type == '1' || $type == '2' ? $employee['gender'] : '') == 'male') : ?>
                                                checked
                                            <?php endif ?>
                                            <?php echo $type == '2' ? 'disabled' : '' ?>
                                        /> &nbsp;&nbsp; Female
                                        <input 
                                            type="radio" 
                                            class="flat" 
                                            name="gender" 
                                            id="genderF" 
                                            value="female" 
                                            <?php if (set_value('gender', $type == '1' || $type == '2' ? $employee['gender'] : '') == 'female') : ?>
                                                checked
                                            <?php endif ?>
                                            <?php echo $type == '2' ? 'disabled' : '' ?>
                                        />
									</p>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="address">
									<?php echo $this->lang->line('address');?>
								</label>
								<div class="col-md-10 col-sm-10 col-xs-10 has-clear has-feedback">
                                    <input 
                                        type="text" 
                                        id="address" 
                                        name="address" 
                                        class="form-control" 
                                        value="<?php echo set_value('address', $type == '1' || $type == '2' ? $employee['address'] : '')?>"
                                        <?php echo ($type=='2' ? 'readonly' : ''); ?>
                                    >
                                    <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="department">
									<?php echo $this->lang->line('department');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="department" id="department" class="form-control" <?php echo ($type=='2' || $administrator != '1' ? 'disabled' : ''); ?>>
                                        <option value=""></option>
                                        <?php foreach ($department_list as $department): ?>
                                        <option value="<?php echo $department['kubun']; ?>" 
                                            <?php if (set_value('department', $type == '1' || $type == '2' ? $employee['department'] : '') == $department['kubun']): ?>
                                                selected
                                            <?php endif ?>
                                        >
                                            <?php echo $department['komoku_name_2']; ?>
                                        </option>
                                        <?php endforeach?>
									</select>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="position">
									<?php echo $this->lang->line('position');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="position" id="position" class="form-control" <?php echo ($type=='2' || $administrator != '1' ? 'disabled' : ''); ?>>
                                        <option value=""></option>
                                        <?php foreach ($position_list as $position): ?>
                                        <option value="<?php echo $position['kubun']; ?>" 
                                            <?php if (set_value('position', $type == '1' || $type == '2' ? $employee['position'] : '') == $position['kubun']): ?>
                                                selected
                                            <?php endif ?>
                                        >
                                            <?php echo $position['komoku_name_2']; ?>
                                        </option>
                                        <?php endforeach?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="date_entry">
									<?php echo $this->lang->line('date_entry');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<div class="input-group date" id="date_entry">
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="date_entry"
                                            maxlength="10" 
                                            value="<?php echo set_value('date_entry', $type == '1' || $type == '2' ? ($employee['entry_date'] ? date_format(date_create($employee['entry_date']), 'd M, Y') : '') : '')?>"
                                            <?php echo ($type=='2' || $administrator != '1' ? 'disabled' : ''); ?>
                                        />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
									</div>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title" for="retire">
									<?php echo $this->lang->line('retire');?> Flg</label>
								<div class="col-md-1 col-sm-1 col-xs-1">
									<p style="padding: 5px;">
                                        <input type='hidden' value='0' name='retire'>
                                        <input 
                                            type="checkbox" 
                                            name="retire" 
                                            id="retire" 
                                            value="1" 
                                            class="flat" 
                                            <?php if (set_value('retire', $type == '1' || $type == '2' ? $employee['active_flg'] : '') == '1') : ?>
                                                checked
                                            <?php endif ?>
                                            <?php echo $type == '2' || $administrator != '1' ? 'disabled' : '' ?>
                                        />
										<p>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-left no-padding-right" for="admin">
									<?php echo $this->lang->line('admin');?> Flg</label>
								<div class="col-md-1 col-sm-1 col-xs-1">
									<p style="padding: 5px;">
                                        <input type='hidden' value='0' name='admin'>
                                        <input 
                                            type="checkbox" 
                                            name="admin" 
                                            id="admin" 
                                            value="1" 
                                            class="flat" 
                                            <?php if (set_value('admin', $type == '1' || $type == '2' ? $employee['admin_flg'] : '') == '1') : ?>
                                                checked
                                            <?php endif ?>
                                            <?php echo $type == '2' || $administrator != '1' ? 'disabled' : '' ?>
                                        />
										<p>
								</div>
							</div>
							<div class="form-group">
                                <!-- <label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="classify">
									<?php echo $this->lang->line('classify');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="classify" id="classify" class="form-control" <?php echo ($type=='2' || $administrator != '1' ? 'disabled' : ''); ?>>
                                        <option value=""></option>
                                        <?php foreach ($classify_list as $classify): ?>
                                        <option value="<?php echo $classify['kubun']; ?>" 
                                            <?php if (set_value('classify', $type == '1' || $type == '2' ? $employee['classify'] : '') == $classify['kubun']): ?>
                                                selected
                                            <?php endif ?>
                                        >
                                            <?php echo $classify['komoku_name_2']; ?>
                                        </option>
                                        <?php endforeach?>
									</select>
								</div> -->
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="theme">
									<?php echo $this->lang->line('theme');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="theme" id="theme" class="form-control" <?php echo ($type=='2' ? 'disabled' : ''); ?>>
                                        <option value=""></option>
                                        <option value="1" 
                                            <?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '1'): ?>
                                                selected
                                            <?php endif ?>>Dark</option>
                                        <option value="2" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '2'): ?>
                                                selected
                                            <?php endif ?>>Light</option>
										<option value="3" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '3'): ?>
                                                selected
                                            <?php endif ?>>Playful</option>
                                        <option value="4" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '4'): ?>
                                                selected
                                            <?php endif ?>>Vintage</option>
                                        <option value="5" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '5'): ?>
                                                selected
                                            <?php endif ?>>Pinky</option>
                                        <option value="6" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '6'): ?>
                                                selected
                                            <?php endif ?>>Elegant</option>
                                        <option value="7" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '7'): ?>
                                                selected
                                            <?php endif ?>>Decode</option>
                                        <option value="8" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '8'): ?>
                                                selected
                                            <?php endif ?>>Diaries</option>
                                        <option value="9" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '9'): ?>
                                                selected
                                            <?php endif ?>>Witty</option>
                                        <option value="10" 
											<?php if (set_value('theme', $type == '1' || $type == '2' ? $employee['theme'] : '') == '10'): ?>
                                                selected
                                            <?php endif ?>>Angel</option>
									</select>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2 form-title no-padding-top" for="retirement_date">
									<?php echo $this->lang->line('retirement_date');?>
								</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
									<div class="input-group date" id="retirement_date">
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="retirement_date" 
                                            maxlength="10"
                                            value="<?php echo set_value('retirement_date', $type == '1' || $type == '2' ? ($employee['retire_date'] ? date_format(date_create($employee['retire_date']), 'd M, Y') : '') : '')?>"
                                            <?php echo ($type=='2' || $administrator != '1' ? 'disabled' : ''); ?>
                                        />
										<span class="input-group-addon">
											<span class="glyphicon glyphicon-calendar"></span>
										</span>
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
<!-- Pop Over for select icon -->
<!-- <div id="icon_picker" class="row" style="position: absolute; top:-1000px;"> -->
<div id="icon_picker" style="position:absolute; top: -1000px">
    <div class="row" style="width: 400px;">
        <?php for($i=1; $i <= 14; $i++){?>
            <div class="col-md-4 col-sm-4 col-xs-4" style="text-align: center; margin-top:15px">
                <a href="#">
                    <img onclick="onSelectIcon($(this))" data-icon_name="img<?php echo $i ?>.png" data-event="select-icon" class="select-icon" src="<?php echo base_url(); ?>images/icon/img<?php echo $i ?>.png" width="60" height="60">
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    function onSelectIcon($el){
        $('#avatar').attr('src',$el.attr('src'));
        $('#icon').val($el.data('icon_name'));
    }
    window.onload = function () {
        $('#employee_id').focus();
        var message = "<?php if(isset($err_message)){echo $err_message;}?>";
        if(message != '') {
            snackbarShow(message);
        }
        $("[data-toggle=popover]").popover({
            html : true,
            content: function() {
                return $("#icon_picker").html();
            },
            title: function() {
                return '<h3> Select Icon</h3>'
            }
        });

        // Click button Save
        $('#btnSave').click(function() {
            // Check validate
            var type = $('#type').val();
            $('#first_name').addClass('validate[required]');
            $('#last_name').addClass('validate[required]');
            if(type == '0') {
                $('#employee_id').addClass('validate[required]');
                $('#new_password').addClass('validate[required]');
                $('#confirm_password').addClass('validate[required, equals[new_password]]');
            }
            if(type == '1') {
                $('#employee_id').addClass('validate[required]');
                $('#old_password').removeClass('validate[required, ajax[checkOldPassword]]');
                $('#new_password').removeClass('validate[required]');
                $('#confirm_password').removeClass('validate[required, equals[new_password]]');
            }
            var validate = $('#frm_new_user').validationEngine('validate');
            if(!validate) { return; }

            var employee_id = $('#employee_id').val();
            if(type == '1') {
                bootbox.confirm({
                    title: '<h4><?php echo $this->lang->line('COMMON_I006'); ?></h4>',
                    message: '<h4 style="color:red;">' + employee_id + '</h4>', 
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
                            // Form Submit
                            var url ="<?php echo base_url('users/save');?>";
                            $("#frm_new_user").attr("action", url).submit();
                        }
                    }
                });
            } else {
                if(type == '0') {
                    var dataCheck = {employee_id: employee_id}
                    $.ajax({
                        url: '<?php echo base_url('users/check_exist_user_id')?>',
                        type: 'post',
                        data: dataCheck,
                        dataType: 'json',
                    }).done( function (response){
                        if(response.success) {
                            bootbox.confirm({
                                message: '<h4><?php echo $this->lang->line('MTS0050_I002');?></h4>', 
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
                                        $('[field=err_exist]').html('');
                                        var url ="<?php echo base_url('users/save');?>";
                                        $("#frm_new_user").attr("action", url).submit();
                                    }
                                }
                            });       
                        } else {
                            $('[field=err_exist]').html(response.message);
                        }
                    });      
                }
            }
        });

        // Click button Change Password
        $('#btn_change_pass').click(function() {
            var userid = $('#employee_id').val();
            $('#old_password').addClass('validate[required]');
            $('#new_password').addClass('validate[required]');
            $('#confirm_password').addClass('validate[required, equals[new_password]]');
            
            // Validation
            var validate = $('#frm_new_user').validationEngine('validate');
            if(!validate) {
                return;
            } else {
                var dataCheck = {};
                dataCheck.oldPassword = $('#old_password').val();
                dataCheck.userID = userid;
                $.ajax({
                    url: '<?php echo base_url('users/check_password')?>',
                    type: 'get',
                    data: dataCheck,
                    dataType: 'json',
                }).done(function(response){
                    if(response.success) {
                        $('[field=err_old_password]').html('');
                        bootbox.confirm({
                            message: '<h4><?php echo $this->lang->line('MTS0050_I001'); ?></h4>',
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
                            callback: function(result) {
                                if(result) {
                                    var url ="<?php echo base_url('users/change_password');?>";
                                    $("#frm_new_user").attr("action", url).submit();
                                }
                            }
                        });
                    } else {
                        $('#old_password').val('');
                        $('#old_password').focus();
                        $('[field=err_old_password]').html(response.message);
                    }
                });
            }
        });

        // Click button Delete
        $('#btn_delete_user').click(function() {
            var userid = $('#employee_id').val();
            bootbox.confirm({
                title: "Do you want to delete this User?",
                message: '<h4 style="color:red;">' + userid + '</h4>',
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
                callback: function(result) {
                    if(result) {
                        var url ="<?php echo base_url('users/delete_user');?>";
                        $("#frm_new_user").attr("action", url).submit();
                    }
                }
            });
        });
    };
</script>

