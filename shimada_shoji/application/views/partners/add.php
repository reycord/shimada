<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button id="btnBack" class="btn btn-info" onclick="window.history.back()">
                            <i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?>
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-primary" id="btnSave" type="submit">
                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?>
                        </button>
                    </li>
                    <li >
                        <button type="button" id="btnDelete" class="btn btn-danger" <?php echo $type == '0' ? 'disabled' : ''?>>
                            <i class="fa fa-trash"></i> <?php echo $this->lang->line('delete')?>
                        </button>
					</li>
                </ul>
                <h5 field="err_exist" class="text-center" style="color:red"></h5>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class = "form-group no-padding-left no-padding-right">
                    <form class="form-horizontal form-label-left" action="" method="post" id="frm-partners">
                        <input type="hidden" name="items" value="" />
                        <input 
                            type="hidden" 
                            name="edit_date" 
                            value="<?php echo set_value('edit_date', $type == '1' ? $partners['edit_date'] : '')?>" 
                        />
                        <div class="form-group">
                            <input id="type" type="hidden" name="type" value="<?php echo $type ?>" >
                            <label 
                                class="control-label col-md-2 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                for="company_id"
                            ><?php echo $this->lang->line('company_id'); ?><span class="required">*</span>
                            </label>
                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <input 
                                    type="text" 
                                    id="company_id" 
                                    name="company_id" 
                                    class="validate[required] form-control" 
                                    value="<?php echo isset($partners['company_id']) ? $partners['company_id'] : set_value('company_id');?>"
                                    readonly
                                >
                                <?php echo form_error('company_id'); ?>
                            </div>
                            <label 
                                class="control-label col-md-2 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                for="short_name"
                            ><?php echo $this->lang->line('short_name'); ?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 has-clear has-feedback">
                                <input 
                                    type="text" 
                                    id="short_name" 
                                    name="short_name" 
                                    class="form-control validate[required] text-uppercase"
                                    value="<?php echo set_value('short_name', $type == '1' ? trim($partners['short_name']) : '')?>"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                <?php echo form_error('short_name'); ?>
                            </div>
                            <label 
                                class="control-label col-md-1 col-sm-3 col-xs-3 no-padding-left no-padding-right" 
                                for="reference"
                            ><?php echo $this->lang->line('reference'); ?>
                            </label>
                            <div class="col-md-3 col-sm-3 col-xs-3 has-clear has-feedback">
                                <input 
                                    type="text"
                                    id="reference"
                                    name="reference"
                                    maxlength="50"
                                    class="form-control text-uppercase"
                                    value="<?php echo set_value('reference', $type == '1' ? $partners['reference'] : '')?>"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" 
                                for="company_name"
                            ><?php echo $this->lang->line('company_name'); ?><span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6 has-clear has-feedback">
                                <input 
                                    type="text" 
                                    id="company_name" 
                                    name="company_name" 
                                    class="form-control validate[required]" 
                                    maxlength="100"
                                    value="<?php echo set_value('company_name', $type == '1' ? $partners['company_name'] : '')?>"
                                >
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                                <?php echo form_error('company_name'); ?>
                            </div>
                            <label class="control-label col-md-1 col-sm-1 col-xs-1 no-padding-left no-padding-right" for="contract_end_flg">End of Flag</label>
                            <div class="col-md-1 col-sm-1 col-xs-1" style="padding-top: 5px; padding-left: -5px;">
                                <p>
                                    <input class="hidden" value="0" name="contract_end_flg">
                                    <input 
                                        type="checkbox" 
                                        name="contract_end_flg" 
                                        id="contract_end_flg" 
                                        value="1" 
                                        class="flat" 
                                        <?php if (set_value('contract_end_flg', $type == '1' ? $partners['contract_end_flg'] : '') == '1') : ?>
                                            checked
                                        <?php endif ?>
                                    />
                                </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" 
                                for="contract_from_date"
                            ><?php echo $this->lang->line('start_day'); ?>
                            </label>
                            <div class="col-md-2 col-sm-3 col-xs-3">
                                <div class="input-group date">
                                    <input 
                                        type="text" 
                                        id="contract_from_date"
                                        class="form-control" 
                                        name="contract_from_date" 
                                        maxlength="10" 
                                        value="<?php echo set_value('contract_from_date', $type == '1' ? ($partners['contract_from_date'] ? date_format(date_create($partners['contract_from_date']), 'd M, Y') : '' ) : '')?>"
                                    />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                            <label 
                                class="control-label col-md-1 col-sm-2 col-xs-2 no-padding-left no-padding-right" 
                                for="contract_end_date"
                            ><?php echo $this->lang->line('end_day'); ?>
                            </label>
                            <div class="col-md-2 col-sm-3 col-xs-3">
                                <div class="input-group date">
                                    <input 
                                        type="text" 
                                        id="contract_end_date"
                                        class="form-control validate[funcCall[check_end_date]] clear" 
                                        name="contract_end_date" 
                                        maxlength="10" 
                                        value="<?php echo set_value('contract_end_date', $type == '1' ? ($partners['contract_end_date'] ? date_format(date_create($partners['contract_end_date']), 'd M, Y') : '' ) : '')?>"
                                    />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" 
                                for="transportation"
                            ><?php echo $this->lang->line('transportation'); ?>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-6 has-clear has-feedback">
                                <input 
                                    type="text"
                                    name="transportation"
                                    id="transportation"
                                    class="form-control clear text-uppercase"
                                    maxlength="30"
                                    value="<?php echo set_value('transportation', $type == '1' ? $partners['transportation'] : '')?>"
                                /> 
                                <span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" for="payment_term">
								<?php echo $this->lang->line('payment_term');?>
							</label>
							<div class="col-md-10 col-sm-10 col-xs-10">
								<select class="form-control" id="payment_term" name="payment_term">
									<option></option>
									<?php foreach ($payment_term_list as $payment_term): ?>
                                    <option
                                        value="<?php echo $payment_term['kubun']; ?>"
                                        <?php if (set_value('payment_term', $type == '1' ? $partners['payment_term'] : '') == $payment_term['kubun']): ?>
                                            selected
                                        <?php endif ?>
                                    ><?php echo $payment_term['komoku_name_3']; ?></option>
                                    <?php endforeach?>
								</select>
							</div>
                        </div>
                        <div class="form-group">
                            <label 
                                class="control-label col-md-2 col-sm-2 col-xs-2 no-padding-left no-padding-right" 
                                for="note"
                            ><?php echo $this->lang->line('comment'); ?>
                            </label>
                            <div class="col-md-10 col-sm-10 col-xs-10">
                                <textarea 
                                    spellcheck="false"
                                    type="text" 
                                    id="note" 
                                    name="note" 
                                    class="form-control" 
                                    maxlength="100"
                                    rows="2"><?php echo set_value('note', $type == '1' ? $partners['note'] : '')?></textarea>
                            </div>
                        </div>
                    </form>
                <!-- </div>
                <div class="col-md-5 col-sm-5 col-xs-12 no-padding-left no-padding-right">
                    <div class="das_panel" style="padding-top:0px;">
                        <div class="das_title">
                            <?php echo $this->lang->line('item_list'); ?>
                        </div>
                        <div class="das_content table-scroll" style="padding-top:0px; max-height: 210px;">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tbl_posts">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th><?php echo $this->lang->line('item_code'); ?></th>
                                            <th><?php echo $this->lang->line('item_name'); ?></th>
                                            <th><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbl_posts_body">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-xs-12 no-padding" style="margin-top:10px;">
                            <div class="col-sm-6 col-md-6 col-xs-6 no-padding-left">
                                <select name="items_list" id="items_list" class="form-control">
                                    <option value=""></option>
                                    <?php foreach ($items_list as $items): ?>
                                    <option
                                        value="<?php echo $items['item_code']; ?>"
                                    ><?php echo $items['item_name']; ?></option>
                                    <?php endforeach?>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-6 col-xs-6 no-padding-right" style="text-align: right;">
                                <button 
                                    class="btn btn-info" 
                                    id="add_record" style="margin-right: 0;"
                                ><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $this->lang->line('add_item'); ?>
                                </button>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div id="group-table" style="display:<?php echo $type == '1' ? 'block' : 'none'?>">
                <h2><?php echo $this->lang->line('headoffice_branch')?></h2>
                <div class="x_content">
                    <div id="table_head_branch" class="panel-content table-responsive">
                        <table id="tbl_head_branch" class="table table-striped table-bordered cssTable display nowrap" width="100%">
                            <thead>
                                <th><?php echo $this->lang->line('type')?><span class="required">*</span></th>
                                <th><?php echo $this->lang->line('id')?><span class="required">*</span></th>
                                <th><?php echo $this->lang->line('name')?><span class="required">*</span></th>
                                <th><?php echo $this->lang->line('address')?><span class="required">*</span></th>
                                <th><?php echo $this->lang->line('name_address_vn')?></th>
                                <th><?php echo $this->lang->line('phone')?></th>
                                <th><?php echo $this->lang->line('tel')?></th>
                                <th><?php echo $this->lang->line('fax')?></th>
                                <th><?php echo $this->lang->line('contract_name')?></th>
                                <th><?php echo $this->lang->line('position')?></th>
                                <th>
                                    <a style="margin: 0px;" data-event="add_new_row" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('add'); ?>">
                                        <span class="fa fa-plus"></span>
                                    </a>
                                </th>
                            </thead>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function check_end_date(field, rules, i, options){
        var contract_from_date = $('#contract_from_date').val();
        if (new Date(contract_from_date) > new Date(field.val())) {  
            return options.allrules.checkContractDate.alertText;
        }
    }

    // function parse num to char 
    function pad(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }
    // Create by: thanh
    window.onload = function() 
    {

        $('#contract_type').focus();
        var type = $('[name=type]').val();
        if(type == '1') {
            $("#group-table").css("display", "block");
        }

        // Back Delete and Save Partners
        // Click button save
        $('#btnSave').click( function() {
            // Check validate
            var validate = $("#frm-partners").validationEngine('validate');
            if(!validate) { return; }
            
            var company_id = $('#company_id').val();
            if($('#type').val() == '1') {
                bootbox.confirm({
                    title: "<?php echo $this->lang->line('COMMON_I006'); ?>",
                    message: '<h4 style="color:red;">' + company_id + '</h4>',
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
                            var url ="<?php echo base_url('partners/save');?>";
                            var data_item = [];
                            for (var index = 0; index < itemDt.data().length; index++) {
                                var item = itemDt.data()[index];
                                data_item.push(item);
                            }

                            $('#frm-partners input[name="items"]').val(JSON.stringify(data_item));
                            $("#frm-partners").attr("action", url).submit();
                        }
                    }
                });
            } else {
                if($('#type').val() == '0') {
                    var dataCheck = {company_id: company_id}
                    $.ajax({
                        url: '<?php echo base_url('partners/check_partners_exists')?>',
                        type: 'post',
                        data: dataCheck,
                        dataType: 'json',
                    }).done( function (response){
                        if(response.success) {
                            bootbox.confirm({
                                message: '<h4><?php echo $this->lang->line('MTS0070_I002');?></h4>',
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
                                        var url ="<?php echo base_url('partners/save');?>";
                                        var data_item = [];
                                        for (var index = 0; index < itemDt.data().length; index++) {
                                            var item = itemDt.data()[index];
                                            data_item.push(item);
                                        }

                                        $('#frm-partners input[name="items"]').val(JSON.stringify(data_item));
                                        $("#frm-partners").attr("action", url).submit();
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

        // Click button delete 
        $('#btnDelete').click( function() {
            var company_id = $('#company_id').val();
            bootbox.confirm({
                title: '<?php echo $this->lang->line('MTS0070_I001'); ?>',
                message: '<h4 style="color:red;">' + company_id + '</h4>',  
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
                        var url ="<?php echo base_url('partners/delete');?>";
                        $("#frm-partners").attr("action", url).submit();
                    }
                }
            });
        });
        
        // Xu ly bang items
        // Items datatable
        var itemDt = $('#tbl_posts').DataTable({
            "paging": false,
            "filter": false,
            "ordering": false,
            "scrollX" : false,
            "info": false,  
            "data": <?php echo set_value('items', $type == 1 ? json_encode($data_items) : '[]', false) ?>,
            "columns": [
                {data: 'no', render: function( data, type, row, meta){
                    return '' + (meta.row + 1);
                }},
                {data: 'item_code'},
                {data: 'item_name'},
                {data: 'action', class: 'text-center', render: function(){
                    return  '<a data-event="delete-item" href="#" class="btn btn-default btn-sm btn-custom" title="Delete">'
                        + '<span class="glyphicon glyphicon-trash"></span>'
                        + '</a>';
                }},
            ]
        });

        // add items
        $('#add_record').click(function(e) {
            e.preventDefault();  
            var item_name = $("#items_list :selected").text(),
            item_code = $("#items_list").val();
            if(item_code != '') {
                
                var itemData = itemDt.data();
                var findItem = false;
                for (var index = 0; index < itemData.length; index++) {
                    var item = itemData[index];
                    if (item.item_code == item_code) {
                        findItem = true;
                        break;
                    }
                }

                if (findItem) {
                    bootbox.alert('<h4 style="text-align:center; color:red;"><?php echo $this->lang->line('POD0020_E003')?></h4>');
                    return;
                }

                itemDt.row
                    .add({
                        item_code: item_code,
                        item_name: item_name,
                    }).draw();
            }
        });

        // remove items
        $('#tbl_posts').on('click', '[data-event="delete-item"]', function() {
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            console.log(rowData);
            
            bootbox.confirm({
                title: "<?php echo $this->lang->line('MTS0030_I001')?>",
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
                        itemDt
                            .row($row)
                            .remove();
                        itemDt.rows().invalidate('data')
                        .draw(false);
                    }
                }
            });
        });
        
        // Xu ly bang head office
        // Head datatable
        var headbranchDt = $('#tbl_head_branch').DataTable({
            "paging": true,
            "filter": false,
            "ordering": false,
            "scrollX" : true,
            "info": true,  
            "data": <?php 
                if($type == '1') {
                    $headDtNew = array();
                    $branchDtNew = array();
                    $shipperDtNew = array();
                    if(!empty($head_office_list)) {
                        foreach($head_office_list as $head_office) {
                            $head_arr = array();
                            $head_arr['company_id'] = $head_office['company_id'];
                            $head_arr['id'] = $head_office['head_office_id'];
                            $head_arr['name'] = $head_office['head_office_name'];
                            $head_arr['address'] = $head_office['head_office_address'];
                            $head_arr['name_address_vn'] = $head_office['head_office_address_vn'];
                            $head_arr['phone'] = $head_office['head_office_phone'];
                            $head_arr['fax'] = $head_office['head_office_fax'];
                            $head_arr['tel'] = $head_office['head_office_tel'];
                            $head_arr['contract_name'] = $head_office['head_office_contract_name'];
                            $head_arr['position'] = $head_office['head_office_position'];
                            $head_arr['type'] = '0';
                            array_push($headDtNew, $head_arr);
                        }
                    }
                    if(!empty($branch_list)) {
                        foreach($branch_list as $branch) {
                            $branch_arr = array();
                            $branch_arr['company_id'] = $branch['company_id'];
                            $branch_arr['id'] = $branch['branch_id'];
                            $branch_arr['name'] = $branch['branch_name'];
                            $branch_arr['address'] = $branch['branch_address'];
                            $branch_arr['name_address_vn'] = $branch['branch_address_vn'];
                            $branch_arr['phone'] = $branch['branch_phone'];
                            $branch_arr['fax'] = $branch['branch_fax'];
                            $branch_arr['tel'] = $branch['branch_tel'];
                            $branch_arr['contract_name'] = $branch['branch_contract_name'];
                            $branch_arr['position'] = $branch['branch_contract_position'];
                            $branch_arr['type'] = '1';
                            array_push($branchDtNew, $branch_arr);
                        }
                    }
                    if(!empty($shipper_list)) {
                        foreach($shipper_list as $shipper) {
                            $shipper_arr = array();
                            $shipper_arr['company_id'] = $shipper['company_id'];
                            $shipper_arr['id'] = $shipper['shipper_id'];
                            $shipper_arr['name'] = $shipper['shipper_name'];
                            $shipper_arr['address'] = $shipper['shipper_address'];
                            $shipper_arr['name_address_vn'] = $shipper['shipper_address_vn'];
                            $shipper_arr['phone'] = $shipper['shipper_phone'];
                            $shipper_arr['fax'] = $shipper['shipper_fax'];
                            $shipper_arr['tel'] = $shipper['shipper_tel'];
                            $shipper_arr['contract_name'] = $shipper['shipper_contract_name'];
                            $shipper_arr['position'] = $shipper['shipper_position'];
                            $shipper_arr['type'] = '2';
                            array_push($branchDtNew, $shipper_arr);
                        }
                    }
                    echo json_encode(array_merge($headDtNew, $branchDtNew, $shipperDtNew));
                } else {
                    echo '[]';
                }
                    ?>,
            "columns": [
                {data: 'type', "className": "text-left", render: function( data, type, row, meta ){
                    var head = '',
                        branch = '',
                        shipper = '';
                    if(row.type == '0'){
                        head = 'selected';
                    } else {
                        if(row.type == '1') {
                            branch = 'selected';
                        } 
                        if(row.type == '2') {
                            shipper = 'selected';
                        }
                    }
                    if(row.add_mode) {
                        var html = '<select class="form-control datatable-input" id="sl_head_branch" name="type" style="min-width: 100%">'
                            + '<option value=""></option>'
                            + '<option value="0" '+ head +'>Head Office</option>'
                            + '<option value="2" '+ shipper +'>Shipper</option>'
                            + '</select>';
                        return html;
                    } else {
                        if(row.type == '0') {
                            return 'Head Office';
                        } else {
                            if(row.type == '1') {
                                return 'Factory';
                            } else {
                                if(row.type == '2') {
                                    return 'Shipper';
                                }
                            }   
                        }
                    }
                }},
                {data: 'id'},
                {data: 'name', "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'name')
                            .attr('spellcheck', false)
                            .attr('value', row.name)
                            .attr('maxlength', 100)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.name;
                    }
                }},
                {data: 'address', "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<textarea></textarea>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'address')
                            .attr('spellcheck', false)
                            .text(row.address)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        var html = '';
                        if(row.address){
                            html += '<div class="ellipsis" title="'+row.address+'">'+row.address+'</div>';
                        }
                        return html;
                    }
                }},
                {data: 'name_address_vn', "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<textarea></textarea>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'name_address_vn')
                            .attr('spellcheck', false)
                            .text(row.name_address_vn)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        var html = '';
                        if(row.name_address_vn){
                            html += '<div class="ellipsis" title="'+row.name_address_vn+'">'+row.name_address_vn+'</div>';
                        }
                        return html;
                    }
                }},
                {data: 'phone', "className": "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'phone')
                            .attr('value', row.phone)
                            .attr('maxlength', 50)
                            .css('text-align', 'right')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.phone;
                    }
                }},
                {data: 'tel', "className": "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'tel')
                            .attr('value', row.tel)
                            .attr('maxlength', 50)
                            .css('text-align', 'right')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.tel;
                    }
                }},
                {data: 'fax', "className": "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'fax')
                            .attr('value', row.fax)
                            .attr('maxlength', 30)
                            .css('text-align', 'right')
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.fax;
                    }
                }},
                {data: 'contract_name', "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'contract_name')
                            .attr('value', row.contract_name)
                            .attr('maxlength', 50)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.contract_name;
                    }
                }},
                {data: 'position', "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control datatable-input')
                            .attr('name', 'position')
                            .attr('value', row.position)
                            .attr('maxlength', 50)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        return row.position;
                    }
                }},
                {data: 'action', render: function( data, type, row, meta ){
                    var html = '';
                    if (row.edit_mode || row.add_mode) {
                        html += '<a data-event="save" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
                            + '<span class="fa fa-check"></span>'
                            + '</a>';
                        html += '<a data-event="close" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
                            + '<span class="fa fa-close"></span>'
                            + '</a>';
                    } else {
                        html += '<a data-event="edit" class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>">'
					        + '<span class="fa fa-edit"></span>'
					        + '</a>';
                        html += '<a data-event="delete" style="margin-left: 3px;" class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>">'
                            + '<span class="fa fa-trash"></span>'
                            + '</a>';
                    }
                    return html;
                }},
            ]
        });

        // Add new row in head table
        $('[data-event="add_new_row"]').on('click', function() {    
            var currentPage = headbranchDt.page();
            var data = headbranchDt.data();
            var requestData = {};
            requestData.company_id = $("#frm-partners input[name='company_id']").val();
            var countAdding = 0;
                for (var index = 0; index < data.length; index++) {
                    const element = data[index];
                    if (element.add_mode) {
                        countAdding += 1;
                    }
                }
            if(countAdding < 1) {
                // insert new row head office
                headbranchDt.row
                .add({
                    company_id: requestData.company_id,
                    id: null, 
                    name: null, 
                    address: null, 
                    name_address_vn: null, 
                    phone: null, 
                    tel: null,
                    fax: null,
                    contract_name: null,
                    position: null,
                    add_mode: true,
                    type: null,
                })
                .draw();

                // move added row to desired index
                var index = currentPage * headbranchDt.page.len(),
                    rowCount = headbranchDt.data().length-1,
                    insertedRow = headbranchDt.row(rowCount).data(),
                    tempRow;

                for (var i=rowCount;i>index;i--) {
                    tempRow = headbranchDt.row(i-1).data();
                    headbranchDt.row(i).data(tempRow);
                    headbranchDt.row(i-1).data(insertedRow);
                }    

                // refresh the current page
                headbranchDt.page(currentPage).draw(false);
            } else {
                snackbarShow('<?php echo $this->lang->line('COMMON_E008');?>');
            }
        });

        $('#tbl_head_branch').on('change', '#sl_head_branch', function() {
            var $row = $(this).parents('tr');
            var rowData = headbranchDt.row($row).data();
            var data = headbranchDt.data();
            var requestData = {};
                requestData.company_id = rowData.company_id;
                requestData.type = $(this).val();
            
            $.ajax({
                url: '<?php echo base_url('partners/get_last_id')?>',
                data: requestData,
                type: 'post',
                dataType: 'json'
            }).done( function(response) {
                console.log(response);
                rowData.type = requestData.type;
                var id = '001';
                var countAdding = 0;
                for (var index = 0; index < data.length; index++) {
                    const element = data[index];
                    if (element.add_mode) {
                        countAdding += 1;
                    }
                }
                if(response.success) { 
                    if(response.data.type == '0'){
                        id = parseInt(response.data.head_office_id) + countAdding;
                    } else {
                        if(response.data.type =='1') {
                            id = parseInt(response.data.branch_id) + countAdding;
                        } else {
                            if(response.data.type =='2') {
                                id = parseInt(response.data.shipper_id) + countAdding;
                            }
                        }
                    }
                } else {
                    id = parseInt(id);
                }
                id = pad(id, 3);
                rowData.id = id;
                if($row.find('td [name=type]').val() == '') {
                    rowData.id = null;
                }
                rowData.name = $row.find('td input[name=name]').val();
                rowData.address = $row.find('td textarea[name=address]').val();
                rowData.name_address_vn = $row.find('td textarea[name=name_address_vn]').val();
                rowData.fax = $row.find('td input[name=fax]').val();
                rowData.tel = $row.find('td input[name=tel]').val();
                rowData.contract_name = $row.find('td input[name=contract_name]').val();
                rowData.phone = $row.find('td input[name=phone]').val();
                rowData.position = $row.find('td input[name=position]').val();
                headbranchDt.row($row).data(rowData).invalidate('data');
                headbranchDt.draw( false );
            });
        });
        
        // Edit Head Office in row
        $('#tbl_head_branch').on('click', '[data-event="edit"]', function(){
            var $row = $(this).parents('tr');
            var rowData = headbranchDt.row($row).data();
            rowData.edit_mode = true;
            headbranchDt.row($row).data(rowData).invalidate();
            headbranchDt.draw( false );
            $row.find('input[name=name]').focus();
        });

        // Close row head office
        $('#tbl_head_branch').on('click', '[data-event="close"]', function(){
            var $row = $(this).parents('tr');
            var rowData = headbranchDt.row($row).data();
            if(rowData.add_mode) {
                headbranchDt.row($row).remove();
                headbranchDt.draw( false );
            } else {
                rowData.edit_mode = false;
                headbranchDt.row($row).data(rowData).invalidate();
                headbranchDt.draw( false );
            }
        });

        // Delete company head office
        $('#tbl_head_branch').on('click', '[data-event="delete"]', function(){
            var $row = $(this).parents('tr'),
            rowData = headbranchDt.row($row).data(),
            requestData = {};
            requestData.company_id = rowData.company_id,
            requestData.type = rowData.type,
            title = '';

            if(rowData.type == '0') {
                requestData.head_office_id = rowData.id;
                title = "<?php echo $this->lang->line('COMMON_I004'); ?>";
            } else {
                if(rowData.type == '1') {
                    requestData.branch_id = rowData.id;
                    title = "<?php echo $this->lang->line('COMMON_I003'); ?>";
                } else {
                    if(rowData.type == '2') {
                        requestData.shipper_id = rowData.id;
                        title = "<?php echo $this->lang->line('COMMON_I005'); ?>";
                    }
                }
            }
            
            bootbox.confirm({
                title: title,
                message: '<h4 style="color:red;">' + rowData.name + '</h4>', 
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
                            url: "<?php echo base_url('partners/delete_head_branch')?>",
                            data: requestData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function(response) {
                            console.log(response);
                            snackbarShow(response.message);
                            if(response.success == true) { 
                                headbranchDt.row($row).remove();
                                headbranchDt.draw( false );
                            } else {
                                headbranchDt.draw();
                            }
                        });
                    }
                }
            });
        });

        // Click save row into table branch
        $('#tbl_head_branch').on('click', '[data-event="save"]', function(){
            var $row = $(this).parents('tr');
            var rowData = headbranchDt.row($row).data();
            
            // Validate required
            $row.find("[name]").removeClass('error');
            var data = {
                type: rowData.type,
                id: rowData.id,
                name: $row.find("[name='name']").val(),
                address: $row.find("[name='address']").val(),
            };
            if(rowData.add_mode) {
                var rules = { type: {
                        presence: {allowEmpty: false},
                    }, id: {
                        presence: {allowEmpty: false},
                    }, name: {
                        presence: {allowEmpty: false},
                    }, address: {
                        presence: {allowEmpty: false},
                    }
                };
            } else {
                if(rowData.edit_mode) {
                    var rules = { name: {
                            presence: {allowEmpty: false},
                        }, address: {
                            presence: {allowEmpty: false},
                        }
                    };
                }
            }
            var errors = validate(data, rules);
            if(errors) {
                $.each(errors, function(fieldName, fieldErrors){
                    $row.find("[name="+fieldName+"]").addClass('error');
                });
                return;
            }
            
            // Set RequestData to edit or add new head office, branch
            var requestData = {};
            requestData.type = rowData.type;
            requestData.company_id = rowData.company_id;
            requestData.name = $row.find("input[name='name']").val();
            requestData.address = $row.find("textarea[name='address']").val();
            requestData.name_address_vn = $row.find("textarea[name='name_address_vn']").val();
            requestData.phone = $row.find("input[name='phone']").val();
            requestData.tel = $row.find("input[name='tel']").val();
            requestData.fax = $row.find("input[name='fax']").val();
            requestData.contract_name = $row.find("input[name='contract_name']").val();
            requestData.position = $row.find("input[name='position']").val();
            console.log(requestData);

            if(rowData.add_mode) {
                requestData.add_mode = rowData.add_mode;
                requestData.id = rowData.id;
                $.ajax({
                    url: "<?php echo base_url('partners/save_head_branch')?>",
                    data: requestData,
                    type: 'post',
                    dataType: 'json'
                }).done(function(response) {
                    console.log(response);
                    snackbarShow(response.message);
                    if(response.success == true) {
                        rowData = requestData;
                        rowData.add_mode = false;
                        headbranchDt.row($row).data(rowData).invalidate();
                        headbranchDt.draw( false );
                    } else {
                        headbranchDt.draw( false );
                    }
                });
            } else {
                if(rowData.edit_mode) {
                    requestData.edit_mode = rowData.edit_mode;
                    requestData.id = rowData.id;
                    // Popup
                    bootbox.confirm({
                        title: '<h4><?php echo $this->lang->line('COMMON_I006'); ?></h4>',
                        message: '<h4 style="color:red;">' + $row.find("input[name='name']").val() + '</h4>', 
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
                                $.ajax({
                                    url: "<?php echo base_url('partners/save_head_branch')?>",
                                    data: requestData,
                                    type: 'post',
                                    dataType: 'json'
                                }).done(function(response) {
                                    console.log(response);
                                    snackbarShow(response.message);
                                    if(response.success == true) {
                                        rowData = requestData;
                                        rowData.edit_mode = false;
                                        headbranchDt.row($row).data(rowData).invalidate();
                                        headbranchDt.draw( false );
                                    } else {
                                        headbranchDt.draw( false );
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    }

</script>

