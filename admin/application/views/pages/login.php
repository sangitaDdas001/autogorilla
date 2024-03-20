<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Admin</b>Panel</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <?php $errorMsg = $this->session->userdata('login_error'); 
      if(!empty($errorMsg)) { ?>
            <div class="alert alert-danger alert-dismissible">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Fail!</strong> <?php echo $errorMsg; ?>
            </div>
    <?php } ?>

   <?php echo form_open('login',array('class'=>"login100-form validate-form")); ?>
      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="password" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-4"></div>
        <!-- /.col -->
        <div class="col-xs-4">
          <input type="submit" class="btn btn-primary btn-block btn-flat login100-form-btn" name="submit" value="Login">
        </div>
        <div class="col-xs-4"></div>
        <!-- /.col -->
      </div>
    <?php echo form_close(); ?>
  </div>
  <!-- /.login-box-body -->
</div>
