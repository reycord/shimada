<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url(); ?>images/shimada_icon1.png"/>
    <title>SHIMADA SHOJI (VIETNAM)CO., LTD</title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url(); ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url(); ?>vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- FullCalendar -->
    <link href="<?php echo base_url(); ?>vendors/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>vendors/fullcalendar/dist/fullcalendar.print.css" rel="stylesheet" media="print">
    <!-- Datatables -->
    <link href="<?php echo base_url(); ?>vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>vendors/datatables.net-select/css/dataTables.select.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>vendors/datatables.net-fixedcolumns/css/fixedColumns.bootstrap.min.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="<?php echo base_url(); ?>vendors/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <!-- bootstrap-datepicker -->
    <link href="<?php echo base_url(); ?>vendors/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="<?php echo base_url(); ?>vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-tagsinput -->
    <link href="<?php echo base_url(); ?>vendors/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>vendors/typeahead/typeahead.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="<?php echo base_url(); ?>vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Custom styling plus plugins -->
    <?php if(!isset($theme) || $theme == null || $theme == 1): ?>
    <link href="<?php echo base_url(); ?>build/css/custom.min.css" rel="stylesheet">
    <?php elseif($theme == 2): ?>
    <link href="<?php echo base_url(); ?>build/css/light_theme.min.css" rel="stylesheet">
	<?php elseif($theme == 3): ?>
    <link href="<?php echo base_url(); ?>build/css/playful_theme.min.css" rel="stylesheet">
    <?php elseif($theme == 4): ?>
    <link href="<?php echo base_url(); ?>build/css/vintage_theme.min.css" rel="stylesheet">
    <?php elseif($theme == 5): ?>
    <link href="<?php echo base_url(); ?>build/css/pink_theme.min.css" rel="stylesheet">
    <?php elseif($theme == 6): ?>
    <link href="<?php echo base_url(); ?>build/css/elegant_theme.min.css" rel="stylesheet">
    <?php elseif($theme == 7): ?>
    <link href="<?php echo base_url(); ?>build/css/decode_theme.min.css" rel="stylesheet">
    <?php elseif($theme == 8): ?>
    <link href="<?php echo base_url(); ?>build/css/diaries_theme.min.css" rel="stylesheet">
    <?php elseif($theme == 9): ?>
    <link href="<?php echo base_url(); ?>build/css/witty_theme.min.css" rel="stylesheet">
    <?php elseif($theme == 10): ?>
    <link href="<?php echo base_url(); ?>build/css/angel_theme.min.css" rel="stylesheet">
    <?php endif ?>
    <!-- validationEngine -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>vendors/jQuery-Validation-Engine-master/css/validationEngine.jquery.css" type="text/css"/>
    <script>
        var base_url = "<?php echo base_url(); ?>";
        var controllerName = "<?php echo $controllerName; ?>";
    </script>
</head>

<body <?php if ($this->session->userdata('body_class')) { ?>
            class="<?php echo $this->session->userdata('body_class');?>"
    <?php } else {?>
            class="nav-md"
    <?php } ?>>
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?php echo base_url(); ?>dashboard" class="site_title" style="margin-top: 5px !important;"> <i class="fa fa-shimada"></i><span>SHIMADA SHOJI (VIETNAM)CO., LTD</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">
                                <li class=""><a href="<?php echo base_url(); ?>dashboard"><i class="fa fa-dashboard"></i><?php echo $this->lang->line('dashboard'); ?></a>
                                </li>
                                <li><a><i class="fa fa-random"></i> <?php echo $this->lang->line('order_received'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="<?php echo base_url(); ?>order_received"><?php echo $this->lang->line('order_received_list'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>order_received/add"><?php echo $this->lang->line('order_received_input'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>order_received/pv_upload"><?php echo $this->lang->line('pv_upload'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-mail-reply-all"></i><?php echo $this->lang->line('ordering'); ?><span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="<?php echo base_url(); ?>orders"><?php echo $this->lang->line('ordering_list'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>orders/add"><?php echo $this->lang->line('ordering_input'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-home"></i> <?php echo $this->lang->line('inventory'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="<?php echo base_url(); ?>inventory"><?php echo $this->lang->line('inventory_list'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>inventory/uploads"><?php echo $this->lang->line('inventory_upload'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url(); ?>schedule"><i class="fa fa-calendar"></i> <?php echo $this->lang->line('schedule'); ?></a></li>
                                <li><a><i class="fa fa-first-order"></i> <?php echo $this->lang->line('japan_order'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="<?php echo base_url(); ?>japan_order"><?php echo $this->lang->line('export_schedule_list'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>japan_order/detail"><?php echo $this->lang->line('export_schedule_detail'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>japan_order/in_out_summary"><?php echo $this->lang->line('in_out_summary'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>japan_order/dvt_kvt_upload"><?php echo $this->lang->line('upload_dvt_kvt'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-shopping-cart"></i> <?php echo $this->lang->line('another_order'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="<?php echo base_url(); ?>another_order"><?php echo $this->lang->line('export_schedule_list'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>another_order/in_out_summary"><?php echo $this->lang->line('in_out_summary'); ?></a></li>
                                    </ul>
                                </li>
                                <li class=""><a><i class="fa fa-cubes"></i><?php echo $this->lang->line('P/L'); ?><span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="<?php echo base_url(); ?>packing_list"><?php echo $this->lang->line('packing_list'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>packing_list/add"><?php echo $this->lang->line('packing_input'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url(); ?>inv_pl_print"><i class="fa fa-print"></i> <?php echo $this->lang->line('cont_inv_pl_export'); ?></a></li>
                                <li class=""><a><i class="fa fa-newspaper-o"></i><?php echo $this->lang->line('sales'); ?><span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="<?php echo base_url(); ?>sales"><?php echo $this->lang->line('japan_order'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>sales/sales_another"><?php echo $this->lang->line('another_order'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a><i class="fa fa-gears"></i> <?php echo $this->lang->line('master'); ?> <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="<?php echo base_url(); ?>products"><?php echo $this->lang->line('item_master'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>partners"><?php echo $this->lang->line('partner_master'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>customers"><?php echo $this->lang->line('customer_master'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>users"><?php echo $this->lang->line('user_master'); ?></a></li>
                                        <li><a href="<?php echo base_url(); ?>items"><?php echo $this->lang->line('configurator'); ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="<?php echo base_url(); ?>analysis"><i class="fa fa-bar-chart"></i> <?php echo $this->lang->line('analysis'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle" style="padding-bottom: 2px;">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <div class="nav navbar-left">
                            <h1 class="mybreadcrumb"></h1>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo base_url(); ?>images/icon/<?php echo ($user['icon'] != null && $user['icon'] != '' ? $user['icon'] : 'img.jpg') ?>" alt=""><?php if (isset($user) && $user != null) {echo ($user['first_name'] . $user['last_name']);}?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="<?php echo base_url('users/profile/'.$user['employee_id'])?>"><i class="fa fa-file-text pull-right"></i> <?php echo $this->lang->line('profile'); ?></a></li>
                                    <li><a href="<?php echo base_url(); ?>login/logout"><i class="fa fa-sign-out pull-right"></i><?php echo $this->lang->line('logout'); ?></a></li>
                                </ul>
                            </li>
                            <li class="">
                                <a href="<?php echo base_url() ?>language_switcher/switchLang/english" class="site_title" style="padding: 8px 2px">
                                    <i class="fa fa-us"></i>
                                </a>
                            </li>
                            <li class="">
                                <a href="<?php echo base_url() ?>language_switcher/switchLang/japanese" class="site_title" style="padding: 8px 2px">
                                    <i class="fa fa-jp"></i>
                                </a>
                            </li>
                            <li class="">
                                 <a href="<?php echo base_url() ?>language_switcher/switchLang/vietnamese" class="site_title" style="padding: 8px 2px">
                                     <i class="fa fa-vn"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                <?php echo $content; ?>
                </div>
                <!-- to show message, notification -->
                <div id="snackbar" style="z-index: 9999"></div>
            </div>
            <!-- /page content -->

            <!-- footer content -->
            <footer>
                <div class="pull-right">
                <?php echo $screen_id; ?></a>
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->
        </div>
    </div>
    <script>var base_url="<?php echo base_url() ?>";</script>
    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url(); ?>vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url(); ?>vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="<?php echo base_url(); ?>vendors/nprogress/nprogress.js"></script>
    <!-- FullCalendar -->
    <script src="<?php echo base_url(); ?>vendors/moment/min/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/fullcalendar/dist/fullcalendar.min.js"></script>
    <!-- Chart.js -->
    <script src="<?php echo base_url(); ?>vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- ECharts -->
    <script src="<?php echo base_url(); ?>vendors/echarts/dist/echarts.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/echarts/map/js/world.js"></script>
    <!-- iCheck -->
    <script src="<?php echo base_url(); ?>vendors/iCheck/icheck.min.js"></script>
    <!-- NProgress -->
    <script src="<?php echo base_url(); ?>vendors/nprogress/nprogress.js"></script>
    <!-- Datatables -->
    <script src="<?php echo base_url(); ?>vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-buttons/js/buttons.colVis.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables.net-fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/datatables-rowsgroup/js/dataTables.rowsGroup.js"></script>
    <!-- bootstrap-datepicker -->
    <script src="<?php echo base_url(); ?>vendors/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="<?php echo base_url(); ?>vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/bootbox/bootbox.min.js"></script>
    <script>　var lang = <?php echo json_encode($this->lang->language); ?>;</script>
    <!-- validationEngine -->
    <script src="<?php echo base_url(); ?>vendors/jQuery-Validation-Engine-master/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url(); ?>vendors/jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-ja.js" type="text/javascript" charset="utf-8"></script>
    <!-- Scroll by dragging -->
    <script src="<?php echo base_url(); ?>vendors/dragScollJs/dragscoll.js" type="text/javascript"></script>
    <!-- Autocomplete with devbridge -->
    <script src="<?php echo base_url(); ?>vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js" type="text/javascript"></script>
    <!-- Jquery Input mask -->
    <script src="<?php echo base_url(); ?>vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js" type="text/javascript"></script>
    <!-- custom js -->
    <script src="<?php echo base_url(); ?>build/js/custom.min.js"></script>
    <!-- bootstrap-tagsinput -->
    <script src="<?php echo base_url(); ?>vendors/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="<?php echo base_url(); ?>vendors/typeahead/typeahead.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url(); ?>vendors/select2/dist/js/select2.min.js"></script>
    <script>
        <?php if ($this->session->flashdata('success_msg')) { ?>
            snackbarShow('<?php echo $this->session->flashdata('success_msg') ?>');
        <?php } ?>
        <?php if ($this->session->flashdata('error_msg')) { ?>
            snackbarShow('<?php echo $this->session->flashdata('error_msg') ?>');
        <?php } ?>
        
        addBreadcrumb = function(){
            firstLevel = $("ul.side-menu li.active a").first().text().trim();
            // secondLevel = $(".current-page").first().text();
            secondLevel = $(".x_title h2").first().text().trim();
            strTitle = "";
            if(firstLevel){
                strTitle = firstLevel + " / " + secondLevel;
            }else{
                strTitle = secondLevel;
            }
            if($(".current-page")[0] == $(".side-menu li")[0]){
                strTitle = "<?php echo $this->lang->line('system_name'); ?>";
            }
            $(".mybreadcrumb").css({"font-weight": "bold", "text-align": "left"});
            $(".mybreadcrumb").text(strTitle);
        };
        function addOnLoad(fn)
        { 
            var old = window.onload;
            window.onload = function()
            {
                if(old){
                    old();
                }
                fn();
            };
        }
        addOnLoad(addBreadcrumb);
        
    </script>
</body>

</html>
