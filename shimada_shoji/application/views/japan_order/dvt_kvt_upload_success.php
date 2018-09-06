<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
				<h2><?php echo $title; ?></h2>
				<ul class="nav navbar-right panel_toolbox">
					<li><button class="btn btn-info" onclick="window.location.href='<?php echo base_url(); ?>japan_order/dvt_kvt_upload'"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></button>
                    </li>
                    <li><button type="button" data-event="save" class="btn btn-primary"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button></li>
				</ul>
				<div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form id="save_pv_upload_form" class="form-horizontal myform" action="<?php echo base_url('/japan_order/save_dvt_kvt_upload') ?>" method="post">
                    <!-- <input type="hidden" name="uploaded_order_receives" value='<?php //echo json_encode($order_receives) ?>' /> -->
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <?php foreach ($order_receives['dvt'] as $index => $order_receive) : ?>
                            <?php $rootName = "order_receives[$index]" ?>
                            <?php
                                $getFieldName = function($field) use ($index) {
                                    return "order_receives[dvt][$index][$field]";
                                };
                                $getFieldValue = function($field, $default = '', $html_escape = TRUE) use ($index, $getFieldName, $order_receive) {
                                    $fieldName = $getFieldName($field);
                                    return set_value($fieldName, isset($order_receive[$field]) ? $order_receive[$field] : $default, $html_escape);
                                };
                                $echoField = function ($field) use ($index, $getFieldName, $getFieldValue) {
                                    $fieldName = $getFieldName($field);
                                    $value = $getFieldValue($field);
                                    echo "name='$fieldName' value='$value'";
                                };
                            ?>
                            <?php
                                $orderReceiveErrors = [];
                                foreach($this->form_validation->error_array() as $key => $value) {
                                    if(strpos($key, "order_receives[dvt][$index]") === 0) {
                                        $orderReceiveErrors[$key] = $value;
                                    }
                                }
                            ?>
                            <div style="border: 1px solid blue; border-radius: 5px;">
                            <div class="panel-body">
                                <!-- DVT info -->
                                <div class="form-group">
                                    <div class="col-sm-4" >
                                        <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="dvt_no" ><?php echo $this->lang->line('dvt_no'); ?><span class="required">*</span></label>
                                        <div class="col-sm-5 no-padding-right" style="padding-left: 10px;">
                                            <input type="hidden" id="kubun" <?php $echoField('kubun') ?>>
                                            <input type="hidden" id="buyer" <?php $echoField('buyer') ?>>
                                            <input type="hidden" id="salesman" <?php $echoField('salesman') ?>>
                                            <input type="hidden" id="delivery_require_date" <?php $echoField('delivery_require_date') ?>>
                                            <input type="hidden" id="status" <?php $echoField('status') ?>>
                                            <input type="text" class="form-control margin-left-3 validate[required]" id="dvt_no" <?php $echoField('dvt_no') ?>>
                                            <!-- <?php echo form_error($getFieldName('dvt_no')); ?> -->
                                            <?php
                                            if(isset($insert_result_array)) {
                                                foreach($insert_result_array as $key => $val) {
                                                    if($order_receive['dvt_no'] == $val['dvt_no'] && $order_receive['order_date'] == $val['order_date']) {
                                                        echo '<span style="display: inline-block;width: auto;font-size: 12px; color: red">' . $val['message'] . '</span>';
                                                    }
                                                }
                                            }
                                        ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="order_date" ><?php echo $this->lang->line('order_date'); ?><span class="required">*</span></label>
                                        <div class="col-sm-8" >
                                            <input type="text" class="form-control hasDatepicker date validate[required]" id="order_date" <?php $echoField('order_date') ?>>
                                            <!-- <?php echo form_error($getFieldName('order_date')); ?> -->
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="staff" ><?php echo $this->lang->line('sales_man'); ?></label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="staff" <?php $echoField('staff') ?>>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control" id="staff_id" <?php $echoField('staff_id') ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-4" >
                                        <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="pv_infor" ><?php echo $this->lang->line('pv_information'); ?></label>
                                        <div class="col-sm-8 no-padding-right" style="padding-left: 10px;">
                                            <input type="text" class="form-control margin-left-3" id="pv_infor" <?php $echoField('pv_infor') ?>>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="delivery_method" ><?php echo $this->lang->line('delivery_method'); ?></label>
                                        <div class="col-sm-8" >
                                            <input type="text" class="form-control" id="delivery_method" <?php $echoField('delivery_method') ?>>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="assistance" ><?php echo $this->lang->line('assistance'); ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="assistance" <?php $echoField('assistance') ?>>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="factory" ><?php echo $this->lang->line('factory_name'); ?></label>
                                        <div class="col-sm-8 no-padding-right" style="padding-left: 10px;">
                                            <input type="text" class="form-control margin-left-3" id="factory" <?php $echoField('factory') ?>>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="control-label col-sm-2 form-title no-padding-left no-padding-right" for="address" ><?php echo $this->lang->line('factory_address'); ?></label>
                                            <div class="col-sm-10">
                                                <textarea spellcheck="false" class="form-control form-rounded" rows="3" style="margin-left: -3px;" id="address" name="<?php echo $getFieldName('address') ?>"><?php echo $getFieldValue('address', '', FALSE) ?></textarea>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="table-responsive">
                                        <?php
                                        $jsonVal = $getFieldValue('details');
                                        if(is_array($jsonVal)){
                                            $jsonVal = json_encode($jsonVal);
                                        }
                                        ?>
                                        <input type="hidden" name="<?php echo $getFieldName('details') ?>" value='<?php echo $jsonVal; ?>' >
                                        <table id="dvt_table" class="table table-striped table-bordered display nowrap" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo $this->lang->line('kvt_no'); ?></th>
                                                    <th><?php echo $this->lang->line('contract_no'); ?></th>
                                                    <th><?php echo $this->lang->line('style_no'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($order_receive['details'] as $key => $detail) { ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php echo ($key + 1) ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $detail['kvt_no']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $detail['contract_no']; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $detail['style_no']; ?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php foreach ($order_receives['kvt'] as $kvt_index => $kvt_order_receive) : ?>
                                <?php if($order_receive['dvt_no'] === $kvt_order_receive['dvt_no']): ?>
                                <?php
                                    $getFieldName = function($field) use ($kvt_index) {
                                        return "order_receives[kvt][$kvt_index][$field]";
                                    };
                                    $getFieldValue = function($field, $default = '', $html_escape = TRUE) use ($kvt_index, $getFieldName, $kvt_order_receive) {
                                        $fieldName = $getFieldName($field);
                                        return set_value($fieldName, isset($kvt_order_receive[$field]) ? $kvt_order_receive[$field] : $default, $html_escape);
                                    };
                                    $echoField = function ($field) use ($kvt_index, $getFieldName, $getFieldValue) {
                                        $fieldName = $getFieldName($field);
                                        $value = $getFieldValue($field);
                                        echo "name='$fieldName' value='$value'";
                                    };
                                ?>
                                <?php
                                    $orderReceiveErrors = [];
                                    foreach($this->form_validation->error_array() as $key => $value) {
                                        if(strpos($key, "order_receives[kvt][$kvt_index]") === 0) {
                                            $orderReceiveErrors[$key] = $value;
                                        }
                                    }
                                ?>
                                <div class="panel panel-default <?php if(count($orderReceiveErrors) > 0): ?>panel-danger<?php endif ?>">
                                    <div class="panel-heading" role="tab" id="heading-<?php echo $kvt_index ?>">
                                        <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-order-receive-<?php echo $kvt_index ?>" aria-expanded="true" aria-controls="collapseOne">
                                            <span style="display: inline-block;width: 50px;">#<?php echo ($kvt_index + 1) ?></span>
                                            <span style="display: inline-block;width: 80px;font-size: 12px"><?php echo $getFieldValue('kvt_no'); ?></span>
                                            <?php
                                                if(count($orderReceiveErrors) > 0) {
                                                    echo '<span style="display: inline-block;width: auto;font-size: 12px">' . array_values($orderReceiveErrors)[0] . '</span>';
                                                }
                                            ?>
                                            <?php
                                                $itemCodeNotExist = array();
                                                foreach ($kvt_order_receive['details'] as $key => $detail) {
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
                                    <div id="collapse-order-receive-<?php echo $kvt_index ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-<?php echo $kvt_index ?>">
                                        <div class="panel-body">
                                            <!-- KVT info -->
                                            <div class="form-group">
                                                <div class="col-sm-4" >
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="kvt_no" ><?php echo $this->lang->line('kvt_no'); ?><span class="required">*</span></label>
                                                    <div class="col-sm-5 no-padding-right" style="padding-left: 10px;">
                                                        <input type="text" class="form-control margin-left-3 validate[required]" id="kvt_no" <?php $echoField('kvt_no') ?>>
                                                        <?php echo form_error($getFieldName('kvt_no')); ?>
                                                        <input type="hidden" class="form-control margin-left-3" id="kvt_dvt_no" <?php $echoField('dvt_no') ?>>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="kvt_order_date" ><?php echo $this->lang->line('order_date'); ?><span class="required">*</span></label>
                                                    <div class="col-sm-8" >
                                                        <input type="text" class="form-control hasDatepicker date validate[required]" id="kvt_order_date" <?php $echoField('order_date') ?>>
                                                        <?php echo form_error($getFieldName('order_date')); ?>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="kvt_staff" ><?php echo $this->lang->line('sales_man'); ?></label>
                                                    <div class="col-sm-5">
                                                        <input type="text" class="form-control" id="kvt_staff_<?php echo $kvt_index;?>" <?php $echoField('staff') ?>>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <input type="text" class="form-control" id="kvt_staff_id" <?php $echoField('staff_id') ?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-4" >
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="kvt_contract_no" ><?php echo $this->lang->line('contract_no'); ?></label>
                                                    <div class="col-sm-8 no-padding-right" style="padding-left: 10px;">
                                                        <input type="text" class="form-control margin-left-3" id="kvt_contract_no" <?php $echoField('contract_no') ?>>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="kvt_style_no" ><?php echo $this->lang->line('style_no'); ?></label>
                                                    <div class="col-sm-8" >
                                                        <input type="text" class="form-control" id="kvt_style_no" <?php $echoField('style_no') ?>>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="kvt_assistance" ><?php echo $this->lang->line('assistance'); ?></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control" id="kvt_assistance" <?php $echoField('assistance') ?>>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-4">
                                                    <div class="form-group no-padding-left no-padding-right">
                                                        <div class="col-sm-12" style="padding-left: inherit; padding-right: inherit;">
                                                            <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="kvt_o_no" ><?php echo $this->lang->line('o_no'); ?></label>
                                                            <div class="col-sm-8 no-padding-right" style="padding-left: 10px; ">
                                                                <input type="text" class="form-control margin-left-3" id="kvt_o_no" <?php $echoField('o_no') ?>>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12" style="margin-top: 10px; padding-left: inherit; padding-right: inherit;">
                                                            <label class="control-label col-sm-4 form-title no-padding-left no-padding-right" for="kvt_factory" ><?php echo $this->lang->line('factory_name'); ?></label>
                                                            <div class="col-sm-8 no-padding-right" style="padding-left: 10px;">
                                                                <input type="text" class="form-control margin-left-3" id="kvt_factory" <?php $echoField('factory') ?>>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8">
                                                    <label class="control-label col-sm-2 form-title no-padding-left no-padding-right" for="kvt_address" ><?php echo $this->lang->line('factory_address'); ?></label>
                                                        <div class="col-sm-10">
                                                            <textarea spellcheck="false" class="form-control form-rounded" rows="3" id="kvt_address" style="margin-left: -3px;" name="<?php echo $getFieldName('address') ?>"><?php echo $getFieldValue('address', '', FALSE) ?></textarea>
                                                        </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <?php
                                                    $jsonVal = $order_receives['kvt'][$kvt_index]['details'];
                                                    if(is_array($jsonVal)){
                                                        $jsonVal = json_encode($jsonVal);
                                                    }
                                                    ?>
                                                    <input type="hidden" name="<?php echo $getFieldName('details') ?>" value='<?php echo $jsonVal; ?>' >
                                                    <table id="items_orders_list_<?php echo $kvt_index ?>" class="table table-striped table-bordered display nowrap" width="100%" cellspacing="0">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th><?php echo $this->lang->line('jp_code'); ?></th>
                                                                <th><?php echo $this->lang->line('item_code'); ?></th>
                                                                <th><?php echo $this->lang->line('item_name'); ?></th>
                                                                <th><?php echo $this->lang->line('size'); ?></th>
                                                                <th><?php echo $this->lang->line('color'); ?></th>
                                                                <th><?php echo $this->lang->line('quantity'); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($order_receives['kvt'][$kvt_index]['details'] as $key => $detail) { ?>
                                                                <?php if(!$detail['item_code_flg']): ?>
                                                                <tr style="background-color: rgb(255, 232, 217);" data-item='<?php echo json_encode($detail)?>'>
                                                                <?php else: ?>
                                                                <tr>
                                                                <?php endif ?>
                                                                    <td class="text-center">
                                                                        <?php echo $detail['detail_no']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $detail['jp_code']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if(!$detail['item_code_flg']): ?>
                                                                            <a class="<?php echo $kvt_index;?>" href="#" data-event="add-product" data-toggle="modal" data-target="#add_product_modal" style="color: blue; text-decoration: underline;"><?php echo $detail['item_code']; ?></a>
                                                                        <?php else: ?>
                                                                            <?php echo $detail['item_code']; ?>
                                                                        <?php endif ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $detail['item_name']; ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo $detail['size']; ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <?php echo $detail['color']; ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo $detail['quantity']; ?>
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
                                <?php endif ?>
                            <?php endforeach ?>
                            </div>
                            <hr />
                        <?php endforeach ?>
                    </div>
                </form>
                <?php
                // echo "<pre>";
                // print_r ($order_receives['kvt']);
                // echo "</pre>";
                // echo "<pre>";
                // print_r ($textpdf);
                // echo "</pre>";
                ?>
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
                                maxlength="50"
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
                                maxlength="50"
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
                                maxlength="50"
                                value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label"><?php echo $this->lang->line('sales_man'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control validate[required]" id="salesman" name="salesman" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="vendor"><?php echo $this->lang->line('vender'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control validate[required]" id="vendor" name="vendor" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="customer_code"><?php echo $this->lang->line('customer_code'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="hidden" class="form-control" id="customer_code" name="customer_code" value="003">
                            <input type="text" class="form-control validate[required] text-uppercase" id="customer_name" name="customer_name" value="COM">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="size"><?php echo $this->lang->line('size'); ?></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control" id="size" name="size" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="unit"><?php echo $this->lang->line('unit'); ?><span class="required">*</span></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control validate[required]" id="unit" name="unit" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                    </div>
                    <div class="form-group  ">
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="color"><?php echo $this->lang->line('color'); ?></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control" id="color" name="color" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="size_unit"><?php echo $this->lang->line('size_unit'); ?></label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2 has-clear has-feedback">
                            <input type="text" class="form-control" id="size_unit" name="size_unit" value="">
                            <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <label class="control-label" for="buy_price_usd"> <?php echo $this->lang->line('buy_price'); ?> US$</label>
                        </div>
                        <div class="col-md-2 col-xs-2 col-sm-2">
                            <input
                                type="text" 
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
                                type="text" 
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
                                type="text" 
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
            if (!$('#save_pv_upload_form').validationEngine('validate')) {
                return;
            }
            bootbox.confirm({
                message: '<?php echo $this->lang->line('dvt_kvt_modal'); ?>',
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
                        $('table[id^="items_orders_list_" ').each(function(){
                            let datatable = $(this).DataTable();
                            let data = datatable.rows().data();
                            data.each(function(value, index){
                                value = value.map(function(x){return x.replace("\"","\\\"");});
                                data[index] = value;
                            });
                            datatable.clear()
                                    .rows.add(data);
                        });

                        $('#save_pv_upload_form').submit();
                    }
                }
            });
        });

        $('table[id^="items_orders_list_" ').DataTable({
            "scrollX": true,
            ordering: false,
            filter: false,
            pageLength: 5,
            lengthChange: false,
        });
        $('table[id="dvt_table"').DataTable({
            "scrollX": true,
            ordering: false,
            filter: false,
            paging: false,
            info: false,
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
            var kvt_index = $(this).attr('class');
            var kvt_salesman = $('#kvt_staff_'+kvt_index+'').val();

            $form.find('[name="item_code"]').val(item.item_code);
            $form.find('[name="item_name"]').val(item.item_name);
            $form.find('[name="jp_code"]').val(item.jp_code);
            $form.find('[name="sell_price_usd"]').val(item.sell_price);
            $form.find('[name="salesman"]').val(kvt_salesman);
            $form.find('[name="size"]').val(item.size);
            $form.find('[name="unit"]').val(item.unit);
            $form.find('[name="color"]').val(item.color);
            $form.find('[name="size_unit"]').val(item.size_unit);
        });
        $('#add_product_modal').on('hidden.bs.modal', function(){
            $(this).find('input').val('');
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
                            url: '<?php echo base_url('japan_order/do_save_product') ?>',
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
