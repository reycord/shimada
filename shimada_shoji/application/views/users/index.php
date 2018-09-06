<div class="row">
    <form class="form-horizontal form-label-left" id="frm-search-users" action="" method="get">
        <input type="hidden" name="search" value="1" />
        <div class="form-group">
            <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
                <label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left no-padding-right" for="position"><?php echo $this->lang->line('position'); ?></label>
                <div class="col-md-7 col-sm-7 col-xs-7">
					<select id="position" name="position" class="form-control">
                    <option value="">ALL</option>
						<?php foreach ($position_list as $position): ?>
						<option
							value="<?php echo $position['kubun']; ?>"
                            <?php if ($this->input->get('position') == $position['kubun']) : ?>
                                selected
                            <?php endif; ?>
						><?php echo $position['komoku_name_2']; ?></option>
						<?php endforeach?>
					</select>
                </div>
            </div>
            <div class="col-md-4 col-sm-4 col-xs-4 no-padding-left no-padding-right">
                <label class="control-label col-md-5 col-sm-5 col-xs-5 no-padding-left" for="item_name"><?php echo $this->lang->line('available_only'); ?></label>
                <div class="col-md-7 col-sm-7 col-xs-7" style="padding: 5px;">
                    <input type='hidden' value='1' name='available_only'>
					<input 
                        type="checkbox" 
                        id="available_only" 
                        name="available_only" 
                        class="flat" 
                        value="0"
                        <?php echo ($this->input->get('available_only') == '0') || ($this->input->get('available_only') == '') ? 'checked' : ''?>
                    >
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3" style="text-align: right">
                <button type="button" id="btnSearch" class="btn btn-info"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                <button type='button' onclick="$('#export_excel_form').submit();" class="btn btn-success"> <i class="fa fa-file-excel-o" ></i> Excel </button>
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
                    <li><button class="btn btn-primary" <?php echo $administrator != '1' ? 'disabled' : '' ?> onclick="window.location.href='<?php echo base_url(); ?>users/add'"><i class="fa fa-plus-square"></i> <?php echo $this->lang->line('new_user'); ?></button></li>
                    
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table id="users_list" class="table table-striped table-bordered cssTable display nowrap" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th><?php echo $this->lang->line('user_id'); ?></th>
                                <th><?php echo $this->lang->line('family_name'); ?></th>
                                <th><?php echo $this->lang->line('given_name'); ?></th>
                                <th><?php echo $this->lang->line('company_email'); ?></th>
                                <th><?php echo $this->lang->line('department'); ?></th>
                                <th><?php echo $this->lang->line('position'); ?></th>
                                <th><?php echo $this->lang->line('telephone'); ?></th>
                                <th><?php echo $this->lang->line('birthday'); ?></th>
                                <th><?php echo $this->lang->line('admin'); ?></th>
                                <th><?php echo $this->lang->line('retire'); ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <p style="color:#d42a38; text-align: center; width: 364px;"><?php echo(isset($err_message) ? $err_message : '')?></p>
    </div>
</div>
<div class ="hidden-form">
    <form id="export_excel_form" action = "<?php echo base_url('users/excel')?>" method="GET">
    </form>
</div>
<script>

    window.onload = function() {
        $('#classify').focus();
		$('#btnSearch').on('click', function(event) {
			// submit form
			$('#frm-search-users').submit();
		});
        
		var userDt = $('#users_list').DataTable({
			"data": [],
			"paging": true,
			"filter": true,
			"ordering": true,
            "scrollX" : true, 
            "dom": "lBfrtip",
            "buttons": ['colvis'],
            <?php if($this->session->flashdata('data') != null):?>
            "oSearch": {"sSearch": <?php echo $this->session->flashdata('data');?>},
            <?php endif?>
            "drawCallback": function() {
                // iCheck
                $("#users_list input.flat").iCheck({
                    checkboxClass: "icheckbox_flat-green",
                    radioClass: "iradio_flat-green",   
                });
            },
			"ajax": {
				"url": "<?php echo base_url('users/search'); ?>",
				"data": function ( data ) {
                    data.param = {};
					var arr = $('#frm-search-users').serializeArray();
					$.each(arr, function(index, item) {
						if(item.value != '') {
							data.param[item.name] = item.value;
						}
					});
                    console.log(data);
				}
			},
			"columns": [
				{ "data": "employee_id", className: 'text-left',
					"render": function( data, type, row, meta ) {
                        return '<a class="edit" href="<?php echo base_url(); ?>users/edit/'+ row.employee_id +'" style="color:#428bca;">'+row.employee_id+'</a>'
					}
				},
				{ "data": "first_name", className: 'text-left' },
				{ "data": "last_name", className: 'text-left' },
				{ "data": "email_job", className: "text-left" },
				{ "data": "department", className: "text-left" },
                { "data": "position", className: "text-left" },
                { "data": "phone", className: "text-right", type: "num" },
				{ "data": "birthday", className: "text-right" },
                { "data": "admin_flg",
                    "render": function( data, type, row, meta ) {
                        if(row.admin_flg == 1) {
                            return '<input type="checkBox" class="flat" name="admin_flg" checked disabled>';
                        } else {
                            return '<input type="checkBox" class="flat" disabled>';
                        }
                    }
                },
                { "data": "active_flg",
                    "render": function( data, type, row, meta ) {
                        if(row.active_flg == 1) {
                            return '<input type="checkBox" class="flat" name="active_flg" checked disabled>';
                        } else {
                            return '<input type="checkBox" class="flat" disabled>';
                        }
                    }
                }
			],
        });
        
        $('#users_list').on( 'draw.dt', function(){
        	if (! userDt.data().any() ) {
				bootbox.alert("<h4 style='color:#0080FF; text-align:center;'><?php echo $this->lang->line('COMMON_E007');?></h4>");
			}
        });
    }

</script>