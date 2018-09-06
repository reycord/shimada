<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <!-- Title top -->
            <div class="x_title">
                <h2><?php echo $title ?></h2>
                <ul class="nav navbar-right panel_toolbox">
					<li>
                        <button class="btn btn-info" onclick="window.history.back()">
                            <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-primary" type="button" id="btnSave">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                        </button>
                    </li>
                    <?php if($type == '1'){?>       
                    <li>
                        <button class="btn btn-primary" type="button" id="btnSaveAs">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save_as'); ?>
                        </button>
                    </li>
                    <?php }?>
                    <li>
                        <button class="btn btn-danger" id="btnDelete" <?php echo $type == '0' ? 'disabled' : '';?>>
                            <i class="fa fa-trash"></i> <?php echo $this->lang->line('delete'); ?>
                        </button>
                    </li>
                </ul>
                <h5 field="err_exist" class="text-center" style="color:red"></h5>
                <div class="clearfix"></div>
            </div>

            <!-- Content of page -->
            <div class="x_content">
                <div class="col-md-12 col-sm-12 col-xs- 12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <!-- Form add new product -->

                            <form class="form-horizontal myform" action="" method="post" name="frm-product" id="frm-product">
						        <div class="form-group">
                                    <input id="type" class="hidden" type="text" name="type" value="<?php echo $type;?>">
                                    <input 
                                        type="hidden" 
                                        name="item_code_old" 
                                        id="item_code_old" 
                                        value="<?php echo set_value('item_code_old', $type == '1' ? $items['item_code'] : '')?>"
                                    />
                                    <input 
                                        type="hidden" 
                                        name="jp_code_old" 
                                        id="jp_code_old" 
                                        value="<?php echo set_value('jp_code_old', $type == '1' ? $items['jp_code'] : '')?>"
                                    />
                                    <input 
                                        type="hidden" 
                                        name="customer_code_old" 
                                        id="customer_code_old" 
                                        value="<?php echo set_value('customer_code_old', $type == '1' ? $items['customer_code'] : '')?>"
                                    />
                                    <input 
                                        type="hidden" 
                                        name="customer_old" 
                                        id="customer_old" 
                                        value="<?php echo set_value('customer_old', $type == '1' ? $items['customer'] : '')?>"
                                    />
                                    <input 
                                        type="hidden" 
                                        name="salesman_old" 
                                        id="salesman_old" 
                                        value="<?php echo set_value('salesman_old', $type == '1' ? $items['salesman'] : '')?>"
                                    />
                                    <input 
                                        type="hidden" 
                                        name="size_old" 
                                        id="size_old" 
                                        value="<?php echo set_value('size_old', $type == '1' ? $items['size'] : '')?>"
                                    />
                                    <input 
                                        type="hidden" 
                                        name="color_old" 
                                        id="color_old" 
                                        value="<?php echo set_value('color_old', $type == '1' ? $items['color'] : '')?>"
                                    />
                                    <input 
                                        type="hidden" 
                                        name="item_name_vn_old" 
                                        id="item_name_vn_old" 
                                        value="<?php echo set_value('item_name_vn_old', $type == '1' ? $items['item_name_vn'] : '')?>"
                                    />
                                    <input 
                                        type="hidden" 
                                        name="item_name_old" 
                                        id="item_name_old" 
                                        value="<?php echo set_value('item_name_old', $type == '1' ? $items['item_name'] : '')?>"
                                    />
                                    <input type="hidden" name="id_del_list" id="id_del_list"/>
                                    <input name="data_surcharge" type="text" class="hidden"/>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="item_code" style="color: blue"><?php echo $this->lang->line('item_code'); ?><span class="required">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="validate[required] text-uppercase form-control"
                                            id="item_code"
                                            name="item_code"
                                            maxlength="15"
                                            value="<?php echo set_value('item_code', $type == '1' ? $items['item_code'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                        <?php echo form_error('item_code'); ?>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="item_name" style="color: blue"><?php echo $this->lang->line('item_name'); ?><span class="required">*</span></label>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control validate[required]" 
                                            id="item_name"
                                            name="item_name"
                                            maxlength="100"
                                            value="<?php echo set_value('item_name', $type == '1' ? $items['item_name'] : '')?>" 
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="item_name_vn" style="color: blue"><?php echo $this->lang->line('item_name'); ?> VN</label>
                                    </div>
                                    <div class="col-md-10 col-xs-10 col-sm-10 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control"
                                            id="item_name_vn"
                                            name="item_name_vn"
                                            maxlength="200"
                                            value="<?php echo set_value('item_name_vn', $type == '1' ? $items['item_name_vn'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="item_name_com"><?php echo $this->lang->line('item_name'); ?> COM</label>
                                    </div>
                                    <div class="col-md-10 col-xs-10 col-sm-10 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control"
                                            id="item_name_com"
                                            name="item_name_com"
                                            maxlength="200"
                                            value="<?php echo set_value('item_name_com', $type == '1' ? $items['item_name_com'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="item_name_dsk"><?php echo $this->lang->line('item_name'); ?> DSK</label>
                                    </div>
                                    <div class="col-md-10 col-xs-10 col-sm-10 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control"
                                            id="item_name_dsk"
                                            name="item_name_dsk"
                                            maxlength="200"
                                            value="<?php echo set_value('item_name_dsk', $type == '1' ? $items['item_name_dsk'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="item_name_des"><?php echo $this->lang->line('item_name'); ?> DES</label>
                                    </div>
                                    <div class="col-md-10 col-xs-10 col-sm-10 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control"
                                            id="item_name_des"
                                            name="item_name_des"
                                            maxlength="200"
                                            value="<?php echo set_value('item_name_des', $type == '1' ? $items['item_name_des'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="jp_code" style="color: blue"><?php echo $this->lang->line('code_jp'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control text-uppercase" 
                                            id="jp_code"
                                            name="jp_code"
                                            maxlength="15"
                                            value="<?php echo set_value('jp_code', $type == '1' ? trim($items['jp_code']) : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                        <?php echo form_error('jp_code'); ?>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="composition_1"><?php echo $this->lang->line('composition'); ?> 1</label>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="composition_1"
                                            name="composition_1"
                                            maxlength="50"
                                            value="<?php echo set_value('composition_1', $type == '1' ? $items['composition_1'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input 
                                            id="customer_code" 
                                            name="customer_code"
                                            class="hidden"
                                            value=""
                                        />
                                        <?php echo form_error('customer_code'); ?>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="composition_2"><?php echo $this->lang->line('composition'); ?> 2</label>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="composition_2"
                                            name="composition_2"
                                            maxlength="50"
                                            value="<?php echo set_value('composition_2', $type == '1' ? $items['composition_2'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" id="end_of_sales"><?php echo $this->lang->line('end_sales'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2" style="padding-top: 5px;">
                                        <input type='hidden' value='0' name='end_of_sales'>
                                        <input 
                                            type="checkbox"
                                            class="flat"
                                            name="end_of_sales"
                                            value="1"
                                            <?php if (set_value('end_of_sales', $type == '1' ? $items['end_of_sales'] : '') == '1') : ?>
                                                checked
                                            <?php endif ?>
                                        >
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="composition_3"><?php echo $this->lang->line('composition'); ?> 3</label>
                                    </div>
                                    <div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="composition_3"
                                            name="composition_3"
                                            maxlength="50"
                                            value="<?php echo set_value('composition_3', $type == '1' ? $items['composition_3'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" style="color: blue"><?php echo $this->lang->line('sales_man'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <select name="salesman" id="salesman" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($salesman_list as $salesman): ?>
                                            <option
                                                value="<?php echo $salesman['komoku_name_2']; ?>"
                                                <?php if (set_value('salesman', $type == '1' ? trim($items['salesman']) : '') == trim($salesman['komoku_name_2'])) : ?>
                                                    selected
                                                <?php endif ?>
                                            ><?php echo $salesman['komoku_name_2']; ?></option>
                                            <?php endforeach?>
                                        </select>
                                        <?php echo form_error('salesman'); ?>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" style="color: blue"><?php echo $this->lang->line('customer'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2  has-clear has-feedback">
                                        <input class="form-control" type="text" value="<?php echo $type == '1' ? $items['customer'] : '' ?>" id="customer" name="customer" />
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="apparel"><?php echo $this->lang->line('apparel'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <select name="apparel" id="apparel" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($apparel_list as $apparel): ?>
                                            <option
                                                value="<?php echo $apparel['komoku_name_2']; ?>"
                                                <?php if (set_value('apparel', $type == '1' ? $items['apparel'] : '') == $apparel['komoku_name_2']) : ?>
                                                    selected
                                                <?php endif ?>
                                            ><?php echo $apparel['komoku_name_2']; ?></option>
                                            <?php endforeach?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="vendor" style="color: blue"><?php echo $this->lang->line('vender'); ?><span class="required">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <select name="vendor" id="vendor" class="form-control validate[required]">
                                            <option value=""></option>
                                            <?php foreach ($vendor_list as $vendor): ?>
                                            <option
                                                value="<?php echo $vendor['short_name']; ?>"
                                                <?php if (set_value('vendor', $type == '1' ? $items['vendor'] : '') == $vendor['short_name']) : ?>
                                                    selected
                                                <?php endif ?>
                                            ><?php echo $vendor['short_name']; ?></option>
                                            <?php endforeach?>
                                        </select>
                                        <?php echo form_error('vendor'); ?>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="vendor_parts"><?php echo $this->lang->line('vender_part'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                                        <input 
                                            type="text"
                                            name="vendor_parts" 
                                            id="vendor_parts" 
                                            class="form-control"
                                            maxlength="30"
                                            value="<?php echo set_value('vendor_parts', $type == '1' ? $items['vendor_parts'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="unit"><?php echo $this->lang->line('unit'); ?><span class="required">*</span></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <select name="unit" id="unit" class="form-control validate[required]">
                                            <option value=""></option>
                                            <?php foreach ($unit_list as $unit): ?>
                                            <option
                                                value="<?php echo $unit['komoku_name_2']; ?>"
                                                <?php if (set_value('unit', $type == '1' ? $items['unit'] : '') === $unit['komoku_name_2']) : ?>
                                                    selected
                                                <?php endif ?>
                                            ><?php echo $unit['komoku_name_2']; ?></option>
                                            <?php endforeach?>
                                        </select>
										<?php echo form_error('unit'); ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="size" style="color: blue"><?php echo $this->lang->line('size'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input 
                                            type="text"
                                            name="size" 
                                            id="size" 
                                            class="form-control"
                                            maxlength="30"
                                            value="<?php echo set_value('size', $type == '1' ? $items['size'] : '')?>"
                                        />
                                        <?php echo form_error('size'); ?>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="inspection_rate"><?php echo $this->lang->line('inspection_rate'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                                        <input 
                                            type="text"
                                            name="inspection_rate" 
                                            id="inspection_rate" 
                                            class="form-control"
                                            maxlength="20"
                                            value="<?php echo set_value('inspection_rate', $type == '1' ? $items['inspection_rate'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="size_unit"><?php echo $this->lang->line('size_unit'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <select name="size_unit" id="size_unit" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($size_unit_list as $size_unit): ?>
                                            <option
                                                value="<?php echo $size_unit['komoku_name_2']; ?>"
                                                <?php if (set_value('size_unit', $type == '1' ? $items['size_unit'] : '') === $size_unit['komoku_name_2']) : ?>
                                                    selected
                                                <?php endif ?>
                                            ><?php echo $size_unit['komoku_name_2']; ?></option>
                                            <?php endforeach?>
                                        </select>
                                        <?php echo form_error('size_unit'); ?>
                                    </div>
                                </div>
								<div class="form-group  ">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="color" style="color: blue"><?php echo $this->lang->line('color'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input 
                                            type="text"
                                            name="color" 
                                            id="color" 
                                            class="form-control"
                                            maxlength="30"
                                            value="<?php echo set_value('color', $type == '1' ? $items['color'] : '')?>"
                                        />
										<?php echo form_error('color'); ?>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                                        <label class="control-label" for="vender_color"><?php echo $this->lang->line('vendor_color'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                                        <input
                                            type="text"  
                                            name="vendor_color" 
                                            id="vendor_color" 
                                            class="form-control"
                                            maxlength="30"
                                            value="<?php echo set_value('vendor_color', $type == '1' ? $items['vendor_color'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="origin"><?php echo $this->lang->line('origin'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <select name="origin" id="origin" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($origin_list as $origin): ?>
                                            <option
                                                value="<?php echo $origin['komoku_name_2']; ?>"
                                                <?php if (set_value('origin', $type == '1' ? $items['origin'] : '') == $origin['komoku_name_2']) : ?>
                                                    selected
                                                <?php endif ?>
                                            ><?php echo $origin['komoku_name_2']; ?></option>
                                            <?php endforeach?>
                                        </select>
                                    </div>
                                </div>
								<div class="form-group">
									<div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="color_note"><?php echo $this->lang->line('color_note'); ?></label>
                                    </div>
									<div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                                        <input 
                                            type="text"
                                            name="color_note" 
                                            id="color_note" 
                                            class="form-control" 
                                            maxlength="100"
                                            value="<?php echo set_value('color_note', $type == '1' ? $items['color_note'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
								</div>
								<div class="form-group">
									<div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="lapdip_note"><?php echo $this->lang->line('lapdip_note'); ?></label>
                                    </div>
									<div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                                        <input 
                                            type="text"
                                            name="lapdip_note" 
                                            id="lapdip_note" 
                                            class="form-control" 
                                            maxlength="100"
                                            value="<?php echo set_value('lapdip_note', $type == '1' ? $items['note_lapdip'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
								</div>
                                <!-- <div class="form-group">
									<div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="inspection_rate"><?php echo $this->lang->line('inspection_rate'); ?></label>
                                    </div>
									<div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                                        <input 
                                            type="text"
                                            name="inspection_rate" 
                                            id="inspection_rate" 
                                            class="form-control" 
                                            maxlength="100"
                                            value="<?php echo set_value('inspection_rate', $type == '1' ? $items['inspection_rate'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
								</div> -->
                                <div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="moq">M.O.Q</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input 
                                            type="number"
                                            name="moq" 
                                            id="moq" 
                                            class="form-control"
                                            value="<?php echo set_value('moq', $type == '1' ? $items['moq'] : '')?>"
                                        >
                                    </div>
									<div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="net_wt">Net WT</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="net_wt" 
                                            id="net_wt" 
                                            class="form-control"
                                            value="<?php echo set_value('net_wt', $type == '1' ? $items['net_wt'] : '')?>"
                                        >
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="lot_quantity">Lot <?php echo $this->lang->line('quantity'); ?></label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="lot_quantity" 
                                            id="lot_quantity" 
                                            class="form-control"
                                            value="<?php echo set_value('lot_quantity', $type == '1' ? $items['lot_quantity'] : '')?>"
                                        >
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
                                            value="<?php echo set_value('buy_price_usd', $type == '1' ? $items['buy_price_usd'] : '')?>"
                                        >
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="buy_price_vnd"> <?php echo $this->lang->line('buy_price'); ?> VND</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="buy_price_vnd" 
                                            id="buy_price_vnd" 
                                            class="form-control"
                                            value="<?php echo set_value('buy_price_vnd', $type == '1' ? $items['buy_price_vnd'] : '')?>"
                                        >
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="buy_price_jpy"> <?php echo $this->lang->line('buy_price'); ?> JPY</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="buy_price_jpy" 
                                            id="buy_price_jpy" 
                                            class="form-control"
                                            value="<?php echo set_value('buy_price_jpy', $type == '1' ? $items['buy_price_jpy'] : '')?>"
                                        >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="sell_price_usd"><?php echo $this->lang->line('sell_price'); ?> US$</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="sell_price_usd" 
                                            id="sell_price_usd" 
                                            class="form-control"
                                            value="<?php echo set_value('sell_price_usd', $type == '1' ? $items['sell_price_usd'] : '')?>"
                                        >
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="sell_price_vnd"><?php echo $this->lang->line('sell_price'); ?> VND</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="sell_price_vnd" 
                                            id="sell_price_vnd" 
                                            class="form-control"
                                            value="<?php echo set_value('sell_price_vnd', $type == '1' ? $items['sell_price_vnd'] : '')?>"
                                        >
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="sell_price_jpy"><?php echo $this->lang->line('sell_price'); ?> JPY</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="sell_price_jpy" 
                                            id="sell_price_jpy" 
                                            class="form-control"
                                            value="<?php echo set_value('sell_price_jpy', $type == '1' ? $items['sell_price_jpy'] : '')?>"
                                        >
                                    </div>
                                </div>
								<div class="form-group">
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="base_price_usd"><?php echo $this->lang->line('base_price'); ?> US$</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="base_price_usd" 
                                            id="base_price_usd" 
                                            class="form-control"
                                            value="<?php echo set_value('base_price_usd', $type == '1' ? $items['base_price_usd'] : '')?>"
                                        >
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="base_price_vnd"><?php echo $this->lang->line('base_price'); ?> VND</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="base_price_vnd" 
                                            id="base_price_vnd" 
                                            class="form-control"
                                            value="<?php echo set_value('base_price_vnd', $type == '1' ? $items['base_price_vnd'] : '')?>"
                                        >
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="base_price_jpy"><?php echo $this->lang->line('base_price'); ?> JPY</label>
                                    </div>
                                    <div class="col-md-2 col-xs-2 col-sm-2">
                                        <input
                                            type="number" 
                                            name="base_price_jpy" 
                                            id="base_price_jpy" 
                                            class="form-control"
                                            value="<?php echo set_value('base_price_jpy', $type == '1' ? $items['base_price_jpy'] : '')?>"
                                        >
                                    </div>
                                </div>
								<div class="form-group">
									<div class="col-md-2 col-xs-2 col-sm-2">
                                        <label class="control-label" for="note"><?php echo $this->lang->line('note'); ?></label>
                                    </div>
									<div class="col-md-6 col-xs-6 col-sm-6 has-clear has-feedback">
                                        <input 
                                            type="text"
                                            name="note" 
                                            id="note" 
                                            class="form-control"
                                            maxlength="100"
                                            value="<?php echo set_value('note', $type == '1' ? $items['note'] : '')?>"
                                        >
                                        <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                    </div>
									<div class="col-md-2 col-xs-2 col-sm-2">
										<button type="button" class="btn btn-primary" style="margin-bottom: 0;" id="btnSurcharge"><?php echo $this->lang->line('surcharge'); ?></button>
									</div>
								</div>
								<div class="form-group">
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left">
                                            <label class="control-label" for="create_user"><?php echo $this->lang->line('create_user'); ?></label>
                                        </div>
                                        <div class="col-md-8 col-sm-8 col-xs-8 no-padding-right" style="padding-left:7px;">
                                            <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    name="create_user" 
                                                    id="create_user" 
                                                    maxlength="10"
                                                    value="<?php echo set_value('create_user', $type == '1' ? $items['create_user'] : '')?>"
                                                    readonly
                                                >
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-4 no-padding-right">
                                                <label class="control-label" for="create_date"><?php echo $this->lang->line('create_date'); ?></label>
                                            </div>
                                            <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                                <input 
                                                    type="text" 
                                                    class="form-control" 
                                                    name="create_date" 
                                                    id="create_date" 
                                                    maxlength="10"
                                                    value="<?php echo set_value('create_date', $type == '1' ? $items['create_date'] : '')?>"
                                                    readonly
                                                >
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding-left no-padding-right">
                                            <label class="control-label" for="edit_user"><?php echo $this->lang->line('update_user'); ?></label>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="edit_user" 
                                                name="edit_user" 
                                                maxlength="10"
                                                value="<?php echo set_value('edit_user', $type == '1' ? $items['edit_user'] : '')?>"
                                                readonly
                                            >
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                            <label class="control-label" for="edit_date"><?php echo $this->lang->line('update_date'); ?></label>
                                        </div>
                                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding-right" style="padding-right: 2px;">
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                id="edit_date" 
                                                name="edit_date"
                                                maxlength="10" 
                                                value="<?php echo set_value('edit_date', $type == '1' ? $items['edit_date'] : '')?>"
                                                readonly
                                            >
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

<!-- Modal -->
<!-- Surcharge -->
<div id="surcharge_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 80%;">
        <div class="modal-content">
            <div class="modal-header">
				<ul class="nav navbar-right panel_toolbox">
					<li><button class="btn btn-info" data-dismiss="modal"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button>
					</li>
				</ul>
				<h2><?php echo $this->lang->line('surcharge'); ?></h2>
            </div>
            <div class="modal-body">
				<!-- <div class="table-responsive"> -->
					<table id="products_list_surcharge" class="table table-striped table-bordered display nowrap" width="100%" cellspacing="0">
						<thead >
							<tr>
								<th><?php echo $this->lang->line('item_code'); ?></th>
								<th><?php echo $this->lang->line('size'); ?></th>
                                <th><?php echo $this->lang->line('size_unit'); ?></th>
								<th><?php echo $this->lang->line('color'); ?></th>
								<th>Qty per COLOR From</th>
								<th>Qty per COLOR To</th>
								<th>Qty per ORDER</th>
								<th>PO's Amount($)</th>
                                <th>PO's Amount(vnđ)</th>
                                <th>PO's Amount(jpy)</th>
								<th>Sur by 1Unit/Color($)</th>
                                <th>Sur by 1Unit/Color(vnđ)</th>
                                <th>Sur by 1Unit/Color(jpy)</th>
								<th>Sur by 1 Color($)</th>
								<th>Sur by 1 Color(vnđ)</th>
								<th>Sur by 1 Color(jpy)</th>
								<th>Sur by 1 PO</th>
								<th>
									<a id="btnAdd" class="btn btn-xs btn-success" style="margin: 0;" title="<?php echo $this->lang->line('add'); ?>">
                                        <span class="fa fa-plus"></span>
                                    </a>
								</th>
							</tr>
						</thead>
						<tbody></tbody>
                	</table>
				<!-- </div> -->
            </div>
        </div>
    </div>
</div>

<script>
    var dataFilterCustomer = [];
    var customerList = <?php echo json_encode($customer_list); ?>;
    var size_list = (<?php echo json_encode($size_list); ?> || []);
    var color_list = (<?php echo json_encode($color_list); ?> || []);
    window.onload = function() 
    {
        customerList.forEach(function(el){
            dataFilterCustomer.push({value: el.short_name, data : el.company_id});
        });
        $("#customer").autocomplete({
            lookup: dataFilterCustomer,
            minChars: 0,
            width: '200px',
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
        var create_date = '<?php echo isset($current_date) ? $current_date : ''?>';
        $('#item_code').focus();
        if($('#type').val() == '0') {
            var user_login = '<?php echo isset($user_login) ? $user_login : ''?>';
            $('#create_user').val(user_login);
            $('#create_date').val(create_date);
        }
        if($('#type').val() === '1') {
            var item_name = $('#item_name');
            moveCursorToEnd(item_name);
            if($('#size').val() == '') {
                var size = "<?php if(isset($items['size']))
                                    {
                                        echo $items['size'];
                                    } else {
                                        echo "";
                                    }?>";
                if(size != '') {
                    $('#size').append($('<option>', {value:size, text:size})).val(size);
                }
            }
            if($('#color').val() == '') {
                var color = "<?php if(isset($items['color']))
                                    {
                                        echo $items['color'];
                                    } else {
                                        echo "";
                                    }?>";
                if(color != '') {
                    var add_option = $('#color').append($('<option>', {value:color, text:color})).val(color);
                }
            }
            if($('#salesman').val() == '') {
                var salesman = "<?php if(isset($items['salesman']))
                                    {
                                        echo $items['salesman'];
                                    } else {
                                        echo "";
                                    }?>";
                if(salesman != '') {
                    var add_option = $('#salesman').append($('<option>', {value:salesman, text:salesman})).val(salesman);
                }
            }
            if($('#unit').val() == '') {
                var unit = "<?php if(isset($items['unit']))
                                    {
                                        echo $items['unit'];
                                    } else {
                                        echo "";
                                    }?>";
                if(unit != '') {
                    var add_option = $('#unit').append($('<option>', {value:unit, text:unit})).val(unit);
                }
            }
            if($('#size_unit').val() == '') {
                var size_unit = "<?php if(isset($items['size_unit']))
                                    {
                                        echo $items['size_unit'];
                                    } else {
                                        echo "";
                                    }?>";
                console.log(size_unit);
                if(size_unit != '') {
                    var add_option = $('#size_unit').append($('<option>', {value:size_unit, text:size_unit})).val(size_unit);
                }
            }
            if($('#apparel').val() == '') {
                var apparel = "<?php if(isset($items['apparel']))
                                    {
                                        echo $items['apparel'];
                                    } else {
                                        echo "";
                                    }?>";
                if(apparel != '') {
                    var add_option = $('#apparel').append($('<option>', {value:apparel, text:apparel})).val(apparel);
                }
            }
            if($('#customer_code').val() == '') {
                var customer_code = "<?php if(isset($items['customer_code']))
                                    {
                                        echo $items['customer_code'];
                                    } else {
                                        echo "";
                                    }?>";
                if(customer_code != '') {
                    var add_option = $('#customer_code').append($('<option>', {value:customer_code, text:customer_code})).val(customer_code);
                }
            }
        }

		//create table Surcharge
		var surchargeDt = $('#products_list_surcharge').DataTable({
            "data": [],
            "paging": true,
            "filter": true,
            "ordering": true,
            "scrollX" : true,
			"ajax": {
				"url": "<?php echo base_url('products/search_surcharge'); ?>",
				"type": 'post',
				"data": function ( data ) {
                    data.param = {};
                    data.param['item_code'] = $('#item_code').val();
                    data.param['color'] = $('#color').val();
                    data.param['size'] = $('#size').val();
				},
			},
            "columns": [
                { "data": "item_code", "className": "text-left", render: function( data, type, row, meta ) {
                    if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'item_code')
                            .attr('field', row.id)
                            .attr('readonly', true)
                            .attr('value', row.item_code.trim())
                            .attr('maxlength', 10)
                            .css('text-align', 'left');
                        return $input[0].outerHTML;
                    } else {
                        return '<p style="margin-bottom:0px;" field='+row.id+'>'+row.item_code+'</p>';
                    }
				}},
                { "data": "size", "className": "text-left", render: function( data, type, row, meta ) {
                    if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'size')
                            .attr('value', row.size)
                            .css('text-align', 'left');
                        return $input[0].outerHTML;
                    } else {
                        return row.size;
                    }
				}},
                { "data": "size_unit", "className": "text-left", render: function( data, type, row, meta ) {
                    if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'size_unit')
                            .attr('value', row.size_unit)
                            .css('text-align', 'left');
                        return $input[0].outerHTML;
                    } else {
                        return row.size_unit;
                    }
				}},
                { "data": "color", "className": "text-left", render: function( data, type, row, meta ) {
                    if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'color')
                            .attr('value', row.color)
                            .css('text-align', 'left');
                        return $input[0].outerHTML;
                    } else {
                        return row.color;
                    }
				}},
                { "data": "qty_by_color_from", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'qty_by_color_from')
                            .attr('value',  (row.qty_by_color_from ?parseFloat(row.qty_by_color_from): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.qty_by_color_from ?numberWithCommas(row.qty_by_color_from): '');
                    }
				}},
                { "data": "qty_by_color_to", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'qty_by_color_to')
                            .attr('value',  (row.qty_by_color_to ?parseFloat(row.qty_by_color_to): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.qty_by_color_to ?numberWithCommas(row.qty_by_color_to): '');
                    }
				}},
                { "data": "qty_by_order", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'qty_by_order')
                            .attr('value',  (row.qty_by_order ?parseFloat(row.qty_by_order): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.qty_by_order ?numberWithCommas(row.qty_by_order): '');
                    }
				}},
                { "data": "po_amount_min_usd", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'po_amount_min_usd')
                            .attr('value',  (row.po_amount_min_usd ?parseFloat(row.po_amount_min_usd): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.po_amount_min_usd ?numberWithCommas(row.po_amount_min_usd): '');
                    }
				}},
                { "data": "po_amount_min_vnd", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'po_amount_min_vnd')
                            .attr('value', (row.po_amount_min_vnd ?parseFloat(row.po_amount_min_vnd): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.po_amount_min_vnd ?numberWithCommas(row.po_amount_min_vnd): '');
                    }
				}},
                { "data": "po_amount_min_jpy", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'po_amount_min_jpy')
                            .attr('value',  (row.po_amount_min_jpy ?parseFloat(row.po_amount_min_jpy): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.po_amount_min_jpy ?numberWithCommas(row.po_amount_min_jpy): '');
                    }
				}},
				{ "data": "surcharge_unit_color_usd", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'surcharge_unit_color_usd')
                            .attr('value',  (row.surcharge_unit_color_usd ?parseFloat(row.surcharge_unit_color_usd): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.surcharge_unit_color_usd ?numberWithCommas(row.surcharge_unit_color_usd): '');
                    }
				}},
                { "data": "surcharge_unit_color_vnd", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'surcharge_unit_color_vnd')
                            .attr('value',  (row.surcharge_unit_color_vnd ?parseFloat(row.surcharge_unit_color_vnd): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.surcharge_unit_color_vnd ?numberWithCommas(row.surcharge_unit_color_vnd): '');
                    }
				}},
                { "data": "surcharge_unit_color_jpy", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'surcharge_unit_color_jpy')
                            .attr('value',  (row.surcharge_unit_color_jpy ?parseFloat(row.surcharge_unit_color_jpy): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.surcharge_unit_color_jpy ?numberWithCommas(row.surcharge_unit_color_jpy): '');
                    }
				}},
                { "data": "surcharge_color_usd", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'surcharge_color_usd')
                            .attr('value',  (row.surcharge_color_usd ?parseFloat(row.surcharge_color_usd): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.surcharge_color_usd ?numberWithCommas(row.surcharge_color_usd): '');
                    }
				}},
                { "data": "surcharge_color_vnd", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'surcharge_color_vnd')
                            .attr('value',  (row.surcharge_color_vnd ?parseFloat(row.surcharge_color_vnd): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.surcharge_color_vnd ?numberWithCommas(row.surcharge_color_vnd): '');
                    }
				}},
                { "data": "surcharge_color_jpy", "className": "text-right", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'surcharge_color_jpy')
                            .attr('value',  (row.surcharge_color_jpy ? parseFloat(row.surcharge_color_jpy): ''))
                            .css('text-align', 'right');
                        return $input[0].outerHTML;
                    } else {
                        return (row.surcharge_color_jpy ? numberWithCommas(row.surcharge_color_jpy) : '');
                    }
				}},
                { "data": "surcharge_po", "className": "text-left", render: function( data, type, row, meta ) {
					if (row.edit_mode || row.add_mode) {
						var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'surcharge_po')
                            .attr('value',  row.surcharge_po)
                            .css('max-width', '100%')
                            .css('text-align', 'left');
                        return $input[0].outerHTML;
                    } else {
                        return row.surcharge_po;
                    }
				}},
                { "data": "action", render: function( data, type, row, meta ){
                    var html = '';
                    if (row.edit_mode || row.add_mode) {
                        html += '<a data-event="save-item" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
                            + '<span class="fa fa-check"></span>'
                            + '</a>';
                        html += '<a data-event="close-item" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
                            + '<span class="fa fa-close"></span>'
                            + '</a>'
                    } else {
                        html += '<a data-event="edit-item" class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>">'
					        + '<span class="fa fa-edit"></span>'
					        + '</a>';
                        html += '<a data-event="delete-item" style="margin-left: 3px;" class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>">'
                            + '<span class="fa fa-trash"></span>'
                            + '</a>'
                    }
                    return html;
                } },
            ],
        } );

        $("#surcharge_modal").on("hide.bs.modal", function (e) {
            var surcharge_arr = surchargeDt.data().toArray();
            if(surcharge_arr.length > 0) {
                $.each(surcharge_arr, function(index, value){
                    if(value['edit_mode'] == true || value['add_mode'] == true) {
                        e.preventDefault();
                        snackbarShow('<?php echo $this->lang->line('MTS0020_I003');?>');
                        return;
                    }
                });
            }
        });
    $('#surcharge_modal').on("shown.bs.modal", function (e) {
        surchargeDt.ajax.reload();
    });
        $('#btnSurcharge').on('click', function(){
            $('#surcharge_modal').modal('show');
            var surcharge_arr = surchargeDt.data().toArray();
            if(surcharge_arr.length > 0) {
                console.log(surcharge_arr);
                $.each(surcharge_arr, function(index, value){
                    surcharge_arr[index]['item_code'] = $('#item_code').val();
                });
                surchargeDt.clear();
                surchargeDt.rows.add(surcharge_arr);
                surchargeDt.rows().invalidate('data').draw();
            } else {
                surchargeDt.draw();
            }
        });

		// Add new row in table
        $('#btnAdd').on('click', function() {
            var data = surchargeDt.data(),
                itemCode = $('#item_code').val(),
                size = $('#size').val();
                size_unit = $('#size_unit').val();
                color = $('#color').val();
                currentPage = surchargeDt.page();

            surchargeDt.row.add({
                id: "",
                item_code: itemCode, 
                size: size, 
                size_unit: size_unit,
                color: color, 
                qty_by_color_from: null, 
                qty_by_color_to: null,
                qty_by_order: null,
                po_amount_min_usd: null,
                surcharge_unit_color_usd: null,
                surcharge_color_usd: null,
                po_amount_min_vnd: null,
                surcharge_unit_color_vnd: null,
                surcharge_color_vnd: null,
                po_amount_min_jpy: null,
                surcharge_unit_color_jpy: null,
                surcharge_color_jpy: null,
				surcharge_po: null,
                add_mode: true,
            }).draw(false);

            //move added row to desired index
            var index = currentPage * surchargeDt.page.len(),
                rowCount = surchargeDt.data().length-1,
                insertedRow = surchargeDt.row(rowCount).data(),
                tempRow;

            for (var i=rowCount;i>index;i--) {
                tempRow = surchargeDt.row(i-1).data();
                surchargeDt.row(i).data(tempRow);
                surchargeDt.row(i-1).data(insertedRow);
            }    

            //refresh the current page
            surchargeDt.page(currentPage).draw( false );
        });

        // save surcharge in table
        $('#products_list_surcharge').on('click', '[data-event="save-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = surchargeDt.row($row).data();

            $row.find("[name]").removeClass('error');
            var data = {
                item_code: $row.find("[name='item_code']").val(),
            };
            var rules = {
                item_code: {
                    presence: {allowEmpty: false},
                }
            };
            var errors = validate(data, rules);
            if(errors) {
                $.each(errors, function(fieldName, fieldErrors){
                    $row.find("[name="+fieldName+"]").addClass('error');
                });
                return;
            }
            bootbox.confirm({
                title: "<?php echo $this->lang->line('COMMON_I006'); ?>",
                message: '<h4 style="color:red;">' + rowData.item_code + '</h4>',
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
                        rowData.id                          = $row.find("input[name='item_code']").attr('field');
                        rowData.item_code                   = $row.find("input[name='item_code']").val();
                        rowData.size                        = $row.find("input[name='size']").val();
                        rowData.size_unit                   = $row.find("input[name='size_unit']").val();
                        rowData.color                       = $row.find("input[name='color']").val();
                        rowData.qty_by_color_from           = $row.find("input[name='qty_by_color_from']").val();
                        rowData.qty_by_color_to             = $row.find("input[name='qty_by_color_to']").val();
                        rowData.qty_by_order                = $row.find("input[name='qty_by_order']").val();
                        rowData.po_amount_min_usd           = $row.find("input[name='po_amount_min_usd']").val();
                        rowData.surcharge_unit_color_usd    = $row.find("input[name='surcharge_unit_color_usd']").val();
                        rowData.surcharge_color_usd         = $row.find("input[name='surcharge_color_usd']").val();
                        rowData.po_amount_min_vnd           = $row.find("input[name='po_amount_min_vnd']").val();
                        rowData.surcharge_unit_color_vnd    = $row.find("input[name='surcharge_unit_color_vnd']").val();
                        rowData.surcharge_color_vnd         = $row.find("input[name='surcharge_color_vnd']").val();
                        rowData.po_amount_min_jpy           = $row.find("input[name='po_amount_min_jpy']").val();
                        rowData.surcharge_unit_color_jpy    = $row.find("input[name='surcharge_unit_color_jpy']").val();
                        rowData.surcharge_color_jpy         = $row.find("input[name='surcharge_color_jpy']").val();
                        rowData.surcharge_po                = $row.find("input[name='surcharge_po']").val();
                        if(rowData.add_mode) {
                            rowData.add_mode = false;
                        } else {
                            if(rowData.edit_mode) {
                                rowData.edit_mode = false;
                            }
                        }
                        console.log(rowData);
                        surchargeDt.row($row).data(rowData).invalidate();
                        surchargeDt.draw( false ); 
                    }
                }
            });
        });

		// Click edit in datatable
        $('#products_list_surcharge').on('click', '[data-event="edit-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = surchargeDt.row($row).data();

            rowData.edit_mode = true;
            surchargeDt.row($row).data(rowData).invalidate();
            surchargeDt.draw( false ); 
        });

		// Click close in datatable
        $('#products_list_surcharge').on('click', '[data-event="close-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = surchargeDt.row($row).data();
            if(rowData.add_mode) {
                surchargeDt.row($row).remove();
                surchargeDt.draw( false ); 
            } else {
                rowData.edit_mode = false;
                surchargeDt.row($row).data(rowData).invalidate();
                surchargeDt.draw( false ); 
            }
        });

        var id_del_list = new Array();
        // Delete surcharge in table
        $('#products_list_surcharge').on('click', '[data-event="delete-item"]', function(){
            var $row = $(this).parents('tr'),
                rowData = surchargeDt.row($row).data();

            bootbox.confirm({
                title: "<?php echo ($this->lang->line('MTS0020_I002'));?>",
                message: '<h4 style="color:red;">' + rowData.item_code + '</h4>', 
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
                        if(rowData.id != undefined && rowData.id != '') {
                            id_del_list.push(rowData.id);
                            console.log(id_del_list);
                        }
                        surchargeDt.row($row).remove();
                        surchargeDt.draw(false);
                    }
                }
            });
        });
        console.log(id_del_list);

        // Click button save
        $('#btnSave').click( function() {
            var validate = $("#frm-product").validationEngine('validate');
            if(!validate) { return; }
            var arr = $('#frm-product').serializeArray();
            var dataCheck = {};
            $.each(arr, function(index, item){
                dataCheck[item.name] = item.value;
            });
            if($('#type').val() == '1') {
                var item_code = $('#item_code').val();
                if(dataCheck.item_code.trim() == dataCheck.item_code_old.trim()
                    && dataCheck.item_name.trim() == dataCheck.item_name_old.trim()
                    && dataCheck.item_name_vn.trim() == dataCheck.item_name_vn_old.trim()
                    && dataCheck.jp_code.trim() == dataCheck.jp_code_old.trim()
                    && dataCheck.customer_code.trim() == dataCheck.customer_code_old.trim()
                    && dataCheck.salesman.trim() == dataCheck.salesman_old.trim()
                    && dataCheck.customer.trim() == dataCheck.customer_old.trim()
                    && dataCheck.size.trim() == dataCheck.size_old.trim()
                    && dataCheck.color.trim() == dataCheck.color_old.trim()
                    ) {
                    $('[field=err_exist]').html('');
                    bootbox.confirm({
                        title: "<?php echo $this->lang->line('COMMON_I006'); ?>",
                        message: '<h4 style="color:red;">' + item_code + '</h4>',
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
                                $('input[name=id_del_list]').val(JSON.stringify(id_del_list));
                                $('input[name=data_surcharge]').val(JSON.stringify(surchargeDt.data().toArray()));
                                var url ="<?php echo base_url('products/save');?>";
                                $("#frm-product").attr("action", url).submit();
                            }
                        }
                    });
                } else {
                    $.ajax({
                        url: '<?php echo base_url('products/check_exist_product')?>',
                        type: 'post',
                        data: dataCheck,
                        dataType: 'json',
                    }).done( function (response){
                        if(response.success) {
                            $('[field=err_exist]').html('');
                            bootbox.confirm({
                                title: "<?php echo $this->lang->line('COMMON_I006'); ?>",
                                message: '<h4 style="color:red;">' + item_code + '</h4>',
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
                                        $('input[name=id_del_list]').val(JSON.stringify(id_del_list));
                                        $('input[name=data_surcharge]').val(JSON.stringify(surchargeDt.data().toArray()));
                                        var url ="<?php echo base_url('products/save');?>";
                                        $("#frm-product").attr("action", url).submit();
                                    }
                                }
                            }); 
                        } else {
                            $('[field=err_exist]').html(response.message);
                        }
                    });
                }
            } else {
                if($('#type').val() == '0') {
                    $.ajax({
                        url: '<?php echo base_url('products/check_exist_product')?>',
                        type: 'post',
                        data: dataCheck,
                        dataType: 'json',
                    }).done( function (response){
                        if(response.success) {
                            $('[field=err_exist]').html('');
                            bootbox.confirm({
                                message: '<h4><?php echo $this->lang->line('MTS0010_I002');?></h4>',
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
                                        $('input[name=id_del_list]').val(JSON.stringify(id_del_list));
                                        $('input[name=data_surcharge]').val(JSON.stringify(surchargeDt.data().toArray()));
                                        var url ="<?php echo base_url('products/save');?>";
                                        $("#frm-product").attr("action", url).submit();
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

        $('#btnSaveAs').click( function() {
            var validate = $("#frm-product").validationEngine('validate');
            if(!validate) { return; }
            var item_code = $('#item_code').val();
            var arr = $('#frm-product').serializeArray();
            var dataCheck = {};
            $.each(arr, function(index, item){
                dataCheck[item.name] = item.value;
            });
            if(dataCheck.item_code.trim() == dataCheck.item_code_old.trim()
                    && dataCheck.item_name.trim() == dataCheck.item_name_old.trim()
                    && dataCheck.item_name_vn.trim() == dataCheck.item_name_vn_old.trim()
                    && dataCheck.jp_code.trim() == dataCheck.jp_code_old.trim()
                    && dataCheck.customer_code.trim() == dataCheck.customer_code_old.trim()
                    && dataCheck.salesman.trim() == dataCheck.salesman_old.trim()
                    && dataCheck.customer.trim() == dataCheck.customer_old.trim()
                    && dataCheck.size.trim() == dataCheck.size_old.trim()
                    && dataCheck.color.trim() == dataCheck.color_old.trim()
                    ) {
                $('[field=err_exist]').html('<?php echo $this->lang->line('product_exist')?>');       
            } else {
                $.ajax({
                    url: '<?php echo base_url('products/check_exist_product')?>',
                    type: 'post',
                    data: dataCheck,
                    dataType: 'json',
                }).done( function (response){
                    if(response.success) {
                        $('[field=err_exist]').html('');
                        bootbox.confirm({
                            title: "<?php echo $this->lang->line('COMMON_I007'); ?>",
                            message: '<h4 style="color:red;">' + item_code + '</h4>',
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
                                    $('input[name=id_del_list]').val(JSON.stringify(id_del_list));
                                    $('input[name=data_surcharge]').val(JSON.stringify(surchargeDt.data().toArray()));
                                    var url ="<?php echo base_url('products/save_as');?>";
                                    $("#frm-product").attr("action", url).submit();
                                }
                            }
                        });
                    } else {
                        $('[field=err_exist]').html(response.message);
                    }
                });
            }
        });

        // Click button delete 
        $('#btnDelete').click( function() {
            var item_code = $('#item_code').val();
            bootbox.confirm({
                title: "<?php echo $this->lang->line('MTS0020_I001'); ?>",
                message: '<h4 style="color:red;">' + item_code + '</h4>',
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
                        var url ="<?php echo base_url('products/delete');?>";
                        $("#frm-product").attr("action", url).submit();
                    }
                }
            });
        });
    }
</script>

