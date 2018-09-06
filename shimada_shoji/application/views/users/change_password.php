<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><?php echo $title?></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><button class="btn btn-info" onclick="window.history.back();"><i class="fa fa-arrow-left"></i> Back</button>
                    </li>
                    <!-- <li><button class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
                    </li> -->
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
            <br />
                <div class="container">
                    <form class="form-horizontal form-label-left">
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="user_id">UserID<span class="required">*</span></label>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                          <input type="text" id="user_id" name="user_id" class="form-control" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="old_password">Old Password<span class="required">*</span></label>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                          <input type="password" id="old_password" name="old_password" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="new_password">New Password<span class="required">*</span></label>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                          <input type="password" id="new_password" name="new_password" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" for="confirm_password">Confirm Password<span class="required">*</span></label>
                        <div class="col-md-8 col-sm-8 col-xs-8">
                          <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                        </div>
                      </div>
                      <div class="form-group">
                            <div class="col-md-6 col-md-offset-3 col-sm-offset-3">
                            <button id="send" type="button" class="btn btn-success"><i class="fa fa-save"></i> Save</button>
                            </div>
                        </div>
                    </form>
                  </div>
            </div>
        </div>
    </div>
    </div>
</div>
