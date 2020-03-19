<header>
  <!-- Fixed navbar -->
  <div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <noscript>
      <meta http-equiv="refresh" content="0; url='<?php echo (base_url('site/logout')) ?>'" />

    </noscript>
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">
          <span class="hidden-xs hidden-sm hidden-md">U<small>NIVERSITY OF </small> M<small>AKATI</small></span>
          <span class="hidden-lg pull-right">UMAK</span>
          <span class="hidden-lg">
            <?php echo img(array('src' => 'assets/components/images/misc/umak-logo-v3.png', 'class' => 'pull-left img-responsive', 'title' => 'University of Makati')) ?>
          </span>
          <span class="visible-lg">
            <?php echo img(array('src' => 'assets/components/images/misc/umak-logo-v3.png', 'class' => 'pull-left img-responsive', 'title' => 'University of Makati', 'id' => 'second-logo')) ?>
          </span>
        </a>
      </div><!-- navbar-header -->
      <div class="navbar-collapse collapse">
        <p class="navbar-text">Encoding of Grades</p>
        <ul class="nav navbar-nav navbar-right">
          
          <?php if ($this->session->userdata('faculty_id')): ?>
          <li><a href="<?php echo base_url('site/stat'); ?>"><i class="fa fa-users"></i> Faculty</a></li>
          <li><a href="<?php echo base_url('faculty'); ?>"><i class="fa fa-database"></i> Teaching Load</a></li>
          <?php endif ?>
          <li><a href="#" data-target="#youtubeprocedure" data-toggle="modal" >
            <i class="fa fa-clipboard"></i>
            <!-- <i class="fa fa-youtube"></i> -->
           Tutorial</a></li>
          <li class="btn-success"><a href="#" data-target="#help" data-toggle="modal" ><i class="fa fa-question-circle"></i> MANUAL</a></li>
          <?php if ($this->session->userdata('faculty_id')): ?>
          <li class="btn-danger"><a href="<?php echo base_url('site/user/logout') ?>" style="color:#fff"  data-toggle="tooltip" data-placement="bottom" title="Log Out"><i class="fa fa-power-off"></i></a></li>
          <?php else: ?>
          <li class="btn-danger"><a href="<?php echo base_url('site/user/admin_logout') ?>" style="color:#fff"><i class="fa fa-power-off"></i> Log Out</a></li>
          <?php endif ?>
        </ul>
      </div><!--/.nav-collapse -->
    </div><!-- container -->
  </div><!-- navbar-fixed-top -->
</header>


<div class="container wrap">

    <div class="row">
        <div class="col-md-12 main-content">

                <?php if ($this->session->flashdata('error')): ?>
                        <!--=== Error Message ===-->
                          <div class="alert alert-danger alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>
                              <?php echo $this->session->flashdata('error'); ?>
                            </h4>
                          </div>

                <?php endif ?>

                <?php if ($this->session->flashdata('success')): ?>
                        <!--=== Success Message ===-->
                          <div class="alert alert-success alert-dismissable" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>
                              <?php echo $this->session->flashdata('success'); ?>
                            </h4>
                          </div>

                <?php endif ?>

                <?php if ($this->session->flashdata('dialogbox')): ?>

                        <?php $print_id = $this->session->flashdata('sched_id'); ?>

                        <!-- Dialogbox -->
                        <div class="modal hide fade" data-show="true" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                          <div class="modal-body lead">
                            <h3 class="text-info"><strong><?php echo $this->session->flashdata('dialogbox') ?></strong></h3>
                            <?php echo anchor(base_url("faculty/{$print_id}/print_gradesheet"), '<strong>CLICK HERE PRINT YOUR COPY OF GRADESHEET</strong>', array('class' => 'btn btn-primary')); ?>
                            <button class='btn btn-default' data-dismiss='modal'>CANCEL</button>
                          </div>
                        </div>

                <?php endif ?>
