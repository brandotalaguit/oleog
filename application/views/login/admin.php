<noscript>
  <div id="noscript-warning">No Javascript Detected.This site will not work Properly wihout Javascript. To Enable your Javascript Please follow this <a href="http://www.wikihow.com/Turn-on-Javascript-in-Internet-Browsers">Link</a>  </div>
</noscript>

        

<div class="container">
    <div class="col-md-offset-4 col-md-4">
               <!--Reg Block-->
            <?php echo $form_url ?>
            <div class="reg-block">
                <div class="reg-block-header margin-bottom-10">

                    <div class="text-center">
                        <img src="<?php echo base_url('assets/images/umak-logo2-small.jpg') ?>" alt="University of Makati" class="img-circle" height="90" title="Univeristy of Makati">    
                        <h3 class="text-center">
                            UNIVERSITY OF MAKATI<br>
                            <small><?php echo strtoupper(config_item('site_title')) ?></small>
                        </h3>
                        <div style="color:red; font-weight:bold;" class="text-center">

                        <?php 
                            $late_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate'))); 

                            $first_date = date('F j, Y', strtotime($this->session->userdata('date_start'))); 
                            $last_date = date('F j, Y', strtotime($this->session->userdata('date_end'))); 

                            echo $first_date . ' to ' . $last_date;
                        ?> 
                            <br>(<?php echo date('h:i a', strtotime($this->session->userdata('time_start'))) ?> to <?php echo date('h:i a', strtotime($this->session->userdata('time_end'))) ?>)
                        </div>
                    </div>
                    <!--
                    <h4 class="text-center">
                    Login to your account
                    </h4>
                     -->
                </div>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="row margin-bottom-5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5>The following errors have occurred</h5>
                                <div class="row">
                                    <div class="col-xs-3">
                                    <i class="fa fa-exclamation-triangle fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9">
                                    <strong>
                                    <?php echo $this->session->flashdata('error'); ?>
                                    </strong>
                                    </div>

                                </div>
                        </div>
                    </div>
                <?php endif ?>

                <?php if (validation_errors()): ?>
                    <div class="row margin-bottom-5">
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h5>The following errors have occurred</h5>
                                <div class="row">
                                    <div class="col-xs-3">
                                    <i class="fa fa-exclamation-triangle fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9">
                                    <strong>
                                    <?php echo validation_errors(); ?>
                                    </strong>
                                    </div>

                                </div>
                        </div>
                    </div>
                <?php endif ?>

            
                
            
                    <h6 style="color:red" class="text-center">ENCODING OF GRADES BEYOND THIS SCHEDULE ARE CONSIDER LATE ENCODEES</h6>

                <p class="text-danger">
                    <strong>Note: </strong>Username and Password are case-sensitive, make sure you type them accordingly.
                </p>

                <div class="input-group margin-bottom-20">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input name="username" type="text" class="form-control" placeholder="Username" autofocus>
                </div>
                <div class="input-group margin-bottom-20">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input name="password" type="password" class="form-control" placeholder="Password">
                </div>
                <button type="submit" class="btn-u pull-right">Log In</button>
                <!--<h4>Forget your Password ? </h4>
                <p>no worries, <a id="color-green" class="color-green" href="#">click here</a> for details.</p>-->
                <br>
                <p></p>
                <hr style="margin: 23.5px 0 15.2px 0;">
                <div class="row">
                    <p class="text-center">
                        Coded by Information Technology Center
                        <br>( <span class="color-blue">Solution Section</span> )
                        <br>
                        <img src="<?php echo base_url('assets/images/itc.png') ?>" alt="Information Technology Center" class="img-circle" height="61" title="Information Technology Center">
                    </p>
                </div>

            </div>
            <!--End Reg Block-->
            <?php echo form_close(); ?>
    </div>
    <div class="clearfix"></div>
</div>