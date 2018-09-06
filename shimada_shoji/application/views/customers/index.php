<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title ?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><button class="btn btn-primary" onclick="window.location.href='<?php echo base_url(); ?>customers/add/1'"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('new_customer'); ?></button></li>
                    <li><button class="btn btn-success" onclick="$('#export_excel_form').submit();" id="btn_export_excel" type='button' style="margin-right: 0;"> <i class="fa fa-file-excel-o" ></i> Excel </button></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table id="customers_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="no_sort" style="text-align:center !important; padding-left:20px; padding-right:20px;"><?php echo $this->lang->line('no'); ?></th>
                                <th><?php echo $this->lang->line('company_name'); ?></th>
                                <th><?php echo $this->lang->line('short_name'); ?></th>
                                <th><?php echo $this->lang->line('classify'); ?></th>
                                <th><?php echo $this->lang->line('reference'); ?></th>
                                <th><?php echo $this->lang->line('start_day'); ?></th>
                                <th><?php echo $this->lang->line('end_day'); ?></th>
                                <th><?php echo $this->lang->line('item_list'); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class ="hidden-form">
    <form id="export_excel_form" action = "<?php echo base_url('customers/excel')?>" method="GET">
    </form>
</div>

<!-- Modal -->
<div id="items_detail" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Items List</h4>
            </div>
            <div class="modal-body">
                <input type="text" class="hidden" name="company_id">
                <div class="das_content table-scroll">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tbl_items" width="100%">
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
                <div class="col-sm-12 col-md-12 col-xs-12 tile_count no-padding">
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
                        <button class="btn btn-info" id="add_record" style="margin-right: 0;"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $this->lang->line('add_item'); ?></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>

<script>

    window.onload = function() {
        var partnerDt = $('#customers_list').DataTable({
            "data": [],
            "paging": true,
            "filter": true,
            "ordering": true,
            "scrollX" : true,
            "dom": "lBfrtip",
            "buttons": ['colvis'],
            "columnDefs": [ {
                "targets": 'no_sort',
                "orderable": false,
                "searchable": false, 
                "visible": true,
            } ],
            // set value for input search
            <?php if($this->session->flashdata('data') != null):?>
            "oSearch": {"sSearch": <?php echo $this->session->flashdata('data');?>},
            <?php endif?>
            "ajax": { "url": "<?php echo base_url('customers/search'); ?>" },
            "columns": [
                { "data": "no" , "render": function( data, type, row, meta ) {
                        return '' + (meta.row + 1);
                    }
                },
                { "data": "company_name", className: "text-left", "render": function( data, type, row, meta ) {
                        return '<a class="edit" href="<?php echo base_url(); ?>customers/edit/'+ row.company_id +'" style="color:#428bca;">'+row.company_name+'</a>'
                    }
                },
                { "data": "short_name", className: "text-left" },
                { "data": "classify", className: "text-left", 
                    "render": function( data, type, row, meta ) {
                        if(row.type == '1') {
                            return 'Customer';
                        } 
                        if(row.type == '2') {
                            return 'Supplier';
                        }
                    }
                },
                { "data": "reference", className: "text-left" },
                { "data": "contract_from_date", className: "text-right" },
                { "data": "contract_end_date", className: "text-right" },
                { "data": "items_list",  
                    "className": "text-left", 
                    "render": function ( data, type, row, meta ) {
                        var item_list = row.list_explode;
                        if(item_list.length > 1) {
                            return '<a id="btn_item_detail" class="edit">'+item_list[0]+',...</a>';
                        } else {
                            if(item_list.length == 1 && item_list[0] != " " && item_list[0] != "") {
                                return '<a id="btn_item_detail" class="edit">'+item_list[0]+'</a>'; 
                            } else {
                                return  '<a id="btn_item_detail" title="Add">'
                                    + '<span class="glyphicon glyphicon-plus"></span>'
                                    + '</a>'; 
                            }
                        }   
                    }
                }
            ],
        });

        $("#customers_list").on('click', '#btn_item_detail', function(){
            var $row = $(this).parents('tr'),
                rowData = partnerDt.row($row).data(),
                requestData = {};
                requestData.items_list = rowData.items_list;
                requestData.company_id = rowData.company_id;
            $('#items_detail input[name="company_id"]').val(rowData.company_id);
            $('#items_detail').modal('show');
            $.ajax({
                url: "<?php echo base_url('customers/get_item_list')?>",
                data: requestData,
                type: 'post',
                dataType: 'json',
            }).done( function(response) {
                itemDt.clear();
                if(response != null || response != undefined) {
                    for (var index = 0; index < response.length; index++) {
                        const element = response[index];
                        itemDt.row.add(element);
                    }
                }
                itemDt.draw();
            });
        });
        $('#btn_export_excel').on('click', function() {
            $('#frm_search_product').attr("action",'<?php echo base_url("customers/excel") ?>');
            $('#frm_search_product').submit();
        });
        // Xu ly bang items
        // Items datatable
        var itemDt = $('#tbl_items').DataTable({
            "paging": false,
            "filter": false,
            "ordering": false,
            "scrollX" : false,
            "info": false,  
            "data": [],
            "columns": [
                {data: 'no', className: "text-center", render: function( data, type, row, meta){
                    return '' + (meta.row + 1);
                }},
                {data: 'item_code', className: "text-center"},
                {data: 'item_name'},
                {data: 'action', class: 'text-center', render: function(){
                    return  '<a data-event="delete-item" href="#" class="btn btn-default btn-sm btn-custom" title="Delete">'
                        + '<span class="glyphicon glyphicon-trash"></span>'
                        + '</a>';
                }},
            ]
        });

        // add new record items
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
                    bootbox.alert('<h4 style="text-align:center; color:red;">Item is exist</h4>');
                    return;
                }
                var company_id = $('#items_detail input[name="company_id"]').val();

                itemDt.row
                    .add({
                        item_code: item_code,
                        item_name: item_name,
                    }).draw();

                var requestData = {};
                requestData.mode = '0';
                requestData.company_id = company_id;
                requestData.item_code = itemDt.data().toArray();

                $.ajax({
                    url: "<?php echo base_url('customers/update_item_list')?>",
                    type: 'post',
                    data: requestData,
                    dataType: 'json',
                }).done(function (response) {
                    snackbarShow(response.message);
                    if(response.success) {
                        $('#customers_list').DataTable().ajax.reload(null, false);
                    } else {
                        $('#customers_list').DataTable().ajax.reload(null, false);
                        $('#items_detail').modal('hide');
                    }
                });
            }
        });
        
        // delete item from tbl_items
        $('#tbl_items').on('click', '[data-event=delete-item]', function() {
            var $row = $(this).parents('tr'),
                rowData = itemDt.row($row).data();
            
            bootbox.confirm({
                title: "Do you want to delete this item?",
                message: '<h4 style="color:red;">' + rowData.item_code + '</h4>', 
                buttons: {
                    confirm: {
                        label: '<?php echo $this->lang->line('yes');?>',
                        className: 'btn-danger',
                    },
                    cancel: {
                        label: '<?php echo $this->lang->line('no');?>',
                    }
                },
                callback: function(result) {
                    if(result) {
                        itemDt
                            .row($row)
                            .remove();
                        itemDt.rows().invalidate('data')
                        .draw(false);

                        var company_id = $('#items_detail input[name="company_id"]').val();
                        var requestData = {};
                        requestData.mode = '1';
                        requestData.company_id = company_id;
                        if(itemDt.data().length > 0) {
                            requestData.item_code = itemDt.data().toArray();
                        } else {
                            requestData.item_code = "";
                        }
                        
                        $.ajax({
                            url: "<?php echo base_url('customers/update_item_list')?>",
                            type: 'post',
                            data: requestData,
                            dataType: 'json',
                        }).done(function (response) {
                            snackbarShow(response.message);
                            if(response.success) {
                                $('#customers_list').DataTable().ajax.reload(null, false);
                            } else {
                                $('#customers_list').DataTable().ajax.reload(null, false);
                                $('#items_detail').modal('hide');
                            }
                        });
                    }
                }
            });
        });

    }
    
</script>


