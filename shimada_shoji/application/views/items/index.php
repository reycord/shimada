<div class="row">
    <form class="form-horizontal form-label-left" id="frm-search-items">
        <div class="form-group">
			<div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
                <label 
                    class="control-label col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right" 
                    for="item_name"><?php echo $this->lang->line('configurator'); ?>
                </label>
                <div class="col-md-8 col-sm-8 col-xs-8">
					<select name="komoku_id" id="komoku_id" class="form-control">
                        <option value=""></option>
						<?php foreach ($items_list as $items): ?>
                        <option value="<?php echo $items['komoku_id']; ?>" 
                            <?php if (set_value('komoku_id') == $items['komoku_id']): ?>
                                selected
                            <?php endif ?>
                        >
                            <?php echo $items['komoku_name_1']; ?>
                        </option>
                        <?php endforeach?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4" style="text-align: center">
                <button type="button" id="btnSearch" class="btn btn-info">
                    <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                <button type='button' onclick="$('#export_excel_form').submit();" id="btn_export_excel" class="btn btn-success">
                    <i class="fa fa-file-excel-o" ></i> Excel </button>
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
                    <li>
                        <button id="btnAdd" type="button" class="btn btn-primary" <?php echo $administrator != '1' ? 'disabled' : ''?> >
                            <i class="fa fa-plus-square"></i> <?php echo $this->lang->line('new_komoku'); ?></button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table 
                        id="items_list" 
                        class="table table-striped table-bordered cssTable display nowrap" 
                        width="100%" 
                        cellspacing="0"
                    >
                        <thead >
                            <tr>
                                <th>
                                    <?php echo $this->lang->line('configurator'); ?>
                                    <span class="required">*</span>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('items_names'); ?>1
                                    <span class="required">*</span>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('items_names'); ?>2
                                </th>
                                <th>
                                    <?php echo $this->lang->line('use'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('sort_order'); ?>
                                </th>
                                <th>
                                    <?php echo $this->lang->line('remarks'); ?>1
                                </th>
                                <th>
                                    <?php echo $this->lang->line('remarks'); ?>2
                                </th>
								<th>
                                    <?php echo $this->lang->line('action'); ?>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="hidden-form">
    <form id="export_excel_form" action = "<?php echo base_url('items/excel')?>" method="GET">
    </form>
</div>

<script>
    var historyPacking = [];
    var company_list = (<?php echo json_encode($company_list) ?> || []);
    var company_branch_list = [];

    // function parse num to char 
    function pad(num, size) {
        var s = num+"";
        while (s.length < size) s = "0" + s;
        return s;
    }

    window.onload = function() 
    {
        var administrator = '<?php echo $administrator;?>';
        $('#komoku_id').focus();
        // create datatable
        var itemDt = $('#items_list').DataTable({
            "data": [],
            "paging": true,
            "filter": true,
            "ordering": false,
            "scrollX" :true,
            "dom": "lBfrtip",
            "buttons": [
                {
                    "extend": 'colvis',
                    "columns": ':gt(1)'
                }
            ],
            // "serverSide": true,
            // "deferLoading": 0,
            "ajax": {
				"url": "<?php echo base_url('items/search'); ?>",
				"type": 'post',
				"data": function ( data ) {
                    data.param = {};
					var arr = $('#frm-search-items').serializeArray();
					$.each(arr, function(index, item) {
						if(item.value != '') {
							data.param[item.name] = item.value;
						}
					});
				}
			},
            "columns": [
                { "data": "komoku_name_1", "className": "text-left", render: function( data, type, row, meta ){
                    if(row.add_mode) {
                        var array = <?php echo json_encode($items_list)?>;
                        var $temp = '';
                        for (let index = 0; index < array.length; index++) {
                            const element = array[index];
                            if(row.komoku_name_1 == element.komoku_name_1) {
                                $temp += '<option selected value='+element.komoku_id+'>'+element.komoku_name_1+'</option>'
                            } else {
                                $temp += '<option komoku_name_1='+element.komoku_name_1+' value='+element.komoku_id+'>'+element.komoku_name_1+'</option>'
                            }
                        }
                        var html = '<select name="komoku_name_1" id="item_listbox" class="form-control" style="min-width:100%; height:30px;">'
                                + '<option value=""></option>'
                                + $temp
                                + '</select>';
                        return html;
                    }
                    return row.komoku_name_1;
                }},
                { "data": "komoku_name_2", "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'text')
                            .attr('class', 'form-control')
                            .attr('name', 'komoku_name_2')
                            .attr('value', row.komoku_name_2)
                            .css('min-width', '100%');
                        if(row.kubun == '000') {
                            $input.attr('class','hidden');
                        }
                        return $input[0].outerHTML;
                    } else {
						var html = '';
						if(row.komoku_name_2){
							html += '<div class="ellipsis" title="'+row.komoku_name_2+'">'+row.komoku_name_2+'</div>';
						}
						return html;
                    }
                }},
                { "data": "komoku_name_3", "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<textarea></textarea>')
                            .attr('type', 'text')
                            .text(row.komoku_name_3)
                            .attr('class', 'form-control')
                            .attr('name', 'komoku_name_3')
                            .attr('spellcheck', false)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
						var html = '';
						if(row.komoku_name_3){
							html += '<div class="ellipsis" title="'+row.komoku_name_3+'">'+row.komoku_name_3+'</div>';
						}
						return html;
                    }
                }},
                { "data": "use", "className": "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('value', row.use)
                            .attr('class', 'form-control')
                            .attr('name', 'use')
                            .css('text-align', 'right')
                            .css('min-width', '90px');
                        return $input[0].outerHTML;
                    } else {
                        return row.use;
                    }
                }},
                { "data": "sort", "className": "text-right", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<input>')
                            .attr('type', 'number')
                            .attr('value', row.sort)
                            .attr('class', 'form-control')
                            .attr('name', 'sort')
                            .css('text-align', 'right')
                            .css('min-width', '90px');
                        return $input[0].outerHTML;
                    } else {
                        return row.sort;
                    }
                }},
                { "data": "note1", "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<textarea></textarea>')
                            .attr('type', 'text')
                            .text(row.note1)
                            .attr('class', 'form-control')
                            .attr('name', 'note1')
                            .attr('spellcheck', false)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        var html = '';
                        if(row.note1){
                            html += '<div class="ellipsis" title="'+row.note1+'">'+row.note1+'</div>';
                        }
                        return html;
                    }
                }},
                { "data": "note2", "className": "text-left", render: function( data, type, row, meta ){
                    if (row.edit_mode || row.add_mode){
                        var $input = $('<textarea></textarea>')
                            .attr('type', 'text')
                            .text(row.note2)
                            .attr('class', 'form-control')
                            .attr('name', 'note2')
                            .attr('spellcheck', false)
                            .css('min-width', '100%');
                        return $input[0].outerHTML;
                    } else {
                        var html = '';
                        if(row.note2){
                            html += '<div class="ellipsis" title="'+row.note2+'">'+row.note2+'</div>';
                        }
                        return html;
                    }
                }},
                { "data": "action", render: function( data, type, row, meta ){
                    var html = '';
                    if(administrator == '1') {
                        if (row.edit_mode || row.add_mode) {
                            html += '<a data-event="save-item" class="btn btn-xs btn-success" title="<?php echo $this->lang->line('save'); ?>">'
                                + '<span class="fa fa-check"></span>'
                                + '</a>';
                            html += '<a data-event="close-item" style="margin-left: 3px;" class="btn btn-xs btn-warning" title="<?php echo $this->lang->line('close'); ?>">'
                                + '<span class="fa fa-close"></span>'
                                + '</a>'
                                + '<input type="hidden" name="edit_date" value="'+ row.edit_date +'"/>';
                        } else {
                            html += '<a data-event="edit-item" class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>">'
                                + '<span class="fa fa-edit"></span>'
                                + '</a>';
                            html += '<a data-event="delete-item" style="margin-left: 3px;" class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>">'
                                + '<span class="fa fa-trash"></span>'
                                + '</a>'
                                + '<input type="hidden" name="edit_date" value="'+ row.edit_date +'"/>';
                        }
                    } else {
                        html += '<a disabled class="btn btn-xs btn-primary" title="<?php echo $this->lang->line('edit'); ?>">'
                            + '<span class="fa fa-edit"></span>'
                            + '</a>';
                        html += '<a disabled style="margin-left: 3px;" class="btn btn-xs btn-danger" title="<?php echo $this->lang->line('delete'); ?>">'
                            + '<span class="fa fa-trash"></span>'
                            + '</a>';
                    }
                    return html;
                }},
            ],
        } );

        $('#items_list').on( 'draw.dt', function(){
        	if (! itemDt.data().any() ) {
				bootbox.alert("<h4 style='color:#0080FF; text-align:center;'><?php echo $this->lang->line('COMMON_E007');?></h4>");
            }
        });
        
        // Click button Search
        $('#btnSearch').on('click', function() {
			// reload datatable
			$('#items_list').DataTable().ajax.reload();
		});

        // Add new row in table
        $('#btnAdd').on('click', function() {
            if(!$(this).attr('disabled')) {
                // console.log('asd');
                var data = itemDt.data(),
                    currentPage = itemDt.page();
                rowData = {
                    komoku_id: null, 
                    company_id: null,
                    branch_id: null,
                    kubun: null, 
                    komoku_name_1: null, 
                    komoku_name_2: null, 
                    komoku_name_3: null,
                    use: null,
                    sort: null,
                    note1: null,
                    note2: null,
                    edit_date: null,
                    add_mode: true,
                }
                itemDt.row.add(rowData).draw(false);

                //move added row to desired index
                var index = currentPage * itemDt.page.len(),
                    rowCount = itemDt.data().length-1,
                    insertedRow = itemDt.row(rowCount).data(),
                    tempRow;

                for (var i=rowCount; i>index; i--) {
                    tempRow = itemDt.row(i-1).data();
                    itemDt.row(i).data(tempRow);
                    itemDt.row(i-1).data(insertedRow);
                }    

                //refresh the current page
                itemDt.page(currentPage).draw( false );
            }
        });

        // Items list box on change
        $('#items_list').on('change', '#item_listbox', function() {
            var $row = $(this).parents('tr'),
                rowData = itemDt.row($row).data();

            rowData.komoku_id = $(this).val();
            rowData.komoku_name_1 = $row.find('select[name="komoku_name_1"]').children(":selected").text();
            rowData.komoku_name_2 = $row.find('td input[name=komoku_name_2]').val();
            rowData.komoku_name_3 = $row.find('td textarea[name=komoku_name_3]').val();
            rowData.use = $row.find('td input[name=use]').val();
            rowData.sort = $row.find('td input[name=sort]').val();
            rowData.note1 = $row.find('td textarea[name=note1]').val();
            rowData.note2 = $row.find('td textarea[name=note2]').val();
            itemDt.row($row).data(rowData);
            itemDt.draw( false );
            if($(this).val() == 'KM0024') {
                $row.find('td textarea[name=note1]').autocomplete({
                    lookup: Object.keys(company_list).map(function(key){
                        return {
                            value: company_list[key]["short_name"],
                            key: company_list[key]["company_id"],
                        };
                    }),
                    minChars: 0,
                    onSelect: function(data){
                        rowData.company_id = data.key;
                        $.ajax({
                            url: '<?php echo base_url("items/getBranchList");?>',
                            data: {note1 : $row.find('td textarea[name=note1]').val()},
                            type: 'POST',
                            dataType: 'json',
                            success: function(response) {
                                company_branch_list = response;
                                $row.find('td textarea[name=note2]').autocomplete({
                                    lookup: Object.keys(company_branch_list).map(function(key){
                                        return {
                                            value: company_branch_list[key]["branch_name"],
                                            key: company_branch_list[key]["branch_id"],
                                        };
                                    }),
                                    minChars: 0,
                                    onSelect: function(data){
                                        rowData.branch_id = data.key;
                                        $(this).change();
                                    }
                                });
                            }
                        });
                        $(this).change();
                    }
                });
                $.ajax({
                    url: '<?php echo base_url("items/getBranchList");?>',
                    data: {note1 : $row.find('td textarea[name=note1]').val()},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        company_branch_list = response;
                        $row.find('td textarea[name=note2]').autocomplete({
                            lookup: Object.keys(company_branch_list).map(function(key){
                                return {
                                    value: company_branch_list[key]["branch_name"],
                                    key: company_branch_list[key]["branch_id"],
                                };
                            }),
                            minChars: 0,
                            onSelect: function(data){
                                rowData.branch_id = data.key;
                                $(this).change();
                            }
                        });
                    }
                });
            }
        });

        // Click edit in datatable
        $('#items_list').on('click', '[data-event="edit-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            historyPacking[$row.index()] = Object.assign({}, rowData) ;
            rowData.edit_mode = true;
            itemDt.row($row).data(rowData).invalidate();
            itemDt.draw( false ); 
            if(rowData.komoku_id == 'KM0024') {
                $row.find('td textarea[name=note1]').autocomplete({
                    lookup: Object.keys(company_list).map(function(key){
                        return {
                            value: company_list[key]["short_name"],
                            key: company_list[key]["company_id"],
                        };
                    }),
                    minChars: 0,
                    onSelect: function(data){
                        rowData.company_id = data.key;
                        $.ajax({
                            url: '<?php echo base_url("items/getBranchList");?>',
                            data: {note1 : $row.find('td textarea[name=note1]').val()},
                            type: 'POST',
                            dataType: 'json',
                            success: function(response) {
                                company_branch_list = response;
                                $row.find('td textarea[name=note2]').autocomplete({
                                    lookup: Object.keys(company_branch_list).map(function(key){
                                        return {
                                            value: company_branch_list[key]["branch_name"],
                                            key: company_branch_list[key]["branch_id"],
                                        };
                                    }),
                                    minChars: 0,
                                    onSelect: function(data){
                                        rowData.branch_id = data.key;
                                        $(this).change();
                                    }
                                });
                            }
                        });
                        $(this).change();
                    }
                });
                $.ajax({
                    url: '<?php echo base_url("items/getBranchList");?>',
                    data: {note1 : $row.find('td textarea[name=note1]').val()},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        company_branch_list = response;
                        $row.find('td textarea[name=note2]').autocomplete({
                            lookup: Object.keys(company_branch_list).map(function(key){
                                return {
                                    value: company_branch_list[key]["branch_name"],
                                    key: company_branch_list[key]["branch_id"],
                                };
                            }),
                            minChars: 0,
                            onSelect: function(data){
                                rowData.branch_id = data.key;
                                $(this).change();
                            }
                        });
                    }
                });
            }
        });

        // Click close in datatable
        $('#items_list').on('click', '[data-event="close-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            if(rowData.add_mode) {
                itemDt.row($row).remove();
                itemDt.draw( false ); 
            } else {
                rowData = historyPacking[$row.index()];
                rowData.edit_mode = false;
                itemDt.row($row).data(rowData).invalidate();
                itemDt.draw( false ); 
            }
        });

        // Click delete in datatable
        $('#items_list').on('click', '[data-event="delete-item"]', function(){
            var $row = $(this).parents('tr'),
            rowData = itemDt.row($row).data(),
            requestData = {};
            requestData.kubun = rowData.kubun;
            requestData.komoku_id = rowData.komoku_id;
            requestData.edit_date = rowData.edit_date;

            bootbox.confirm({
                title: "<h4><?php echo $this->lang->line('MTS0030_I001'); ?></h4>",
                message: '<h4 style="color:red;">' + rowData.komoku_name_1 + ' > '+ rowData.komoku_name_2 +'</h4>', 
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
                            url: "<?php echo base_url('items/delete')?>",
                            data: requestData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function(response) {
                            snackbarShow(response.message);
                            if(response.success == true) { 
                                itemDt.row($row).remove();
                                itemDt.draw( false );
                            } else {
                                itemDt.draw();
                            }
                        });
                    }
                }
            });
        });
        
        // Click save in datatable
        $('#items_list').on('click', '[data-event="save-item"]', function(){
            var $row = $(this).parents('tr');
            var rowData = itemDt.row($row).data();
            
            $row.find("[name]").removeClass('error');
            var data = {
                komoku_name_1: $row.find("[name='komoku_name_1']").val(),
                komoku_name_2: $row.find("[name='komoku_name_2']").val(),
                note1: $row.find("[name='note1']").val(),
                note2: $row.find("[name='note2']").val(),
            };
            if(rowData.add_mode) {
                if(rowData.komoku_id == 'KM0024') {
                    var rules = {
                        komoku_name_1: {
                            presence: {allowEmpty: false},
                        },
                        komoku_name_2: {
                            presence: {allowEmpty: false},
                        },
                        note1: {
                            presence: {allowEmpty: false},
                        },
                        note2: {
                            presence: {allowEmpty: false},
                        },
                    };
                } else {
                    var rules = {
                        komoku_name_1: {
                            presence: {allowEmpty: false},
                        },
                        komoku_name_2: {
                            presence: {allowEmpty: false},
                        }
                    };
                }
            } else {
                if(rowData.edit_mode) {
                    if(rowData.komoku_id == 'KM0024') {
                        var rules = {
                            komoku_name_2: {
                                presence: {allowEmpty: false},
                            },
                            note1: {
                                presence: {allowEmpty: false},
                            },
                            note2: {
                                presence: {allowEmpty: false},
                            },
                        };
                    } else {
                        var rules = {
                            komoku_name_2: {
                                presence: {allowEmpty: false},
                            }
                        };
                    }
                }
            }
            var errors = validate(data, rules);
            if(errors) {
                $.each(errors, function(fieldName, fieldErrors){
                    $row.find("[name="+fieldName+"]").addClass('error');
                });
                return;
            }
            
            // Set requestData to edit or add new
            var requestData = {};
            requestData.komoku_id = rowData.komoku_id;
            requestData.komoku_name_1 = rowData.komoku_name_1;
            requestData.komoku_name_2 = $row.find("input[name='komoku_name_2']").val();
            requestData.komoku_name_3 = $row.find("textarea[name='komoku_name_3']").val();
            requestData.use = $row.find("input[name='use']").val();
            requestData.sort = $row.find("input[name='sort']").val();
            if(rowData.komoku_id == 'KM0024') { 
                if($row.find("textarea[name='note1']").val() != '') {
                    requestData.note1 = rowData.company_id;
                } else {
                    requestData.note1 = $row.find("textarea[name='note1']").val();
                }
                if($row.find("textarea[name='note1']").val() != '') {
                    requestData.note2 = rowData.branch_id;
                } else {
                    requestData.note2 = $row.find("textarea[name='note2']").val();
                }
            } else {
                requestData.note1 = $row.find("textarea[name='note1']").val();
                requestData.note2 = $row.find("textarea[name='note2']").val();
            }
            // console.log(requestData);
            if(rowData.add_mode) {
                bootbox.confirm({
                    message: '<h4><?php echo $this->lang->line('MTS0030_I002');?></h4>', 
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
                                url: "<?php echo base_url('items/save')?>",
                                data: requestData,
                                type: 'post',
                                dataType: 'json'
                            }).done(function(response) {
                                snackbarShow(response.message);
                                if(response.success == true) {
                                    rowData = response.data;
                                    rowData.add_mode = false;
                                    itemDt.row($row).data(rowData).invalidate();
                                    itemDt.draw( false );
                                } else {
                                    itemDt.draw( false );
                                }
                            });
                        }
                    }
                });
            } else {
                if(rowData.edit_mode) {
                    var edit_date = $row.find("input[name='edit_date']").val();
                    if(edit_date == '' || edit_date == undefined) {
                        edit_date = null;
                    }
                    requestData.edit_date = edit_date;
                    requestData.kubun = rowData.kubun;
                    bootbox.confirm({
                        title: "<h4><?php echo $this->lang->line('COMMON_I006'); ?></h4>",
                        message: '<h4 style="color:red;">' + rowData.komoku_name_1 + ' > ' + rowData.komoku_name_2 + '</h4>', 
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
                                    url: "<?php echo base_url('items/save')?>",
                                    data: requestData,
                                    type: 'post',
                                    dataType: 'json'
                                }).done(function(response) {
                                    snackbarShow(response.message);
                                    if(response.success == true) {
                                        rowData = response.data;
                                        rowData.edit_mode = false;
                                        itemDt.row($row).data(rowData).invalidate();
                                        itemDt.draw( false );
                                    } else {
                                        itemDt.draw( false );
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    };

</script>
