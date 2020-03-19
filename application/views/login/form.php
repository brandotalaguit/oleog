<?php
    $now = date('Y-m-d H:i:s');
    $disabled = "";
    // if ($this->session->userdata('date_start') > $now || $this->session->userdata('date_end') <= $now )
    // $disabled = "disabled";
 ?>

<noscript>
  <div id="noscript-warning">No Javascript Detected.This site will not work Properly wihout Javascript. To Enable your Javascript Please follow this <a href="http://www.wikihow.com/Turn-on-Javascript-in-Internet-Browsers">Link</a>  </div>
</noscript>



<div class="container">
    <div class="col-md-8">
        <div class="col-md-12 vidlog">
            <p class="pull-right" style="margin-top: 7px;"><?php echo $this->session->userdata('sem_desc').', A.Y. '.$this->session->userdata('sy_desc'); ?></p>
            <h4>ONLINE ENCODING OF GRADES</h4>
            <hr style="margin: 15px 0 8px 0;">
            <div class="responsive-video">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                      <li data-target="#myCarousel" data-slide-to="1"></li>
                      <li data-target="#myCarousel" data-slide-to="2"></li>
                      <li data-target="#myCarousel" data-slide-to="3"></li>
                      <li data-target="#myCarousel" data-slide-to="4"></li>
                      <!-- <li data-target="#myCarousel" data-slide-to="5"></li> -->
                    </ol>

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                      <div class="item active">
                        <img src="<?php echo base_url('/assets/images/step1.jpg') ?>"  style="width:100%;">
                      </div>

                      <div class="item">
                        <img src="<?php echo base_url('/assets/images/step2.jpg') ?>" style="width:100%;">
                      </div>
                    
                      <div class="item">
                        <img src="<?php echo base_url('/assets/images/step3.jpg') ?>"  style="width:100%;">
                      </div>
                      <div class="item">
                        <img src="<?php echo base_url('/assets/images/step4.jpg') ?>"  style="width:100%;">
                      </div>
                      <div class="item">
                        <img src="<?php echo base_url('/assets/images/step5.jpg') ?>"  style="width:100%;">
                      </div>
                    </div>
                        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                          <span class="icon-prev" aria-hidden="true"></span>
                          <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                          <span class="icon-next" aria-hidden="true"></span>
                          <span class="sr-only">Next</span>
                        </a>
                </div>
            </div>
            <div  style="margin:20px auto;">
                <hr style="margin:0px;">
                <h4> Encoding of Grades Schedule from <b style="color:#a50909;">
                    November 11, 2019 to November 15, 2019
                </b>
                <br>    The Releasing of Grades Schedule is from  <b style="color:#a50909;">November 18, 2019 to November 20, 2019</b> (HSU & Tertiary)   
                </h4>
                    
            </div>
        </div>
    </div>

    <div class="col-md-4">
               <!--Reg Block-->
            <?php //dump($this->session->userdata() ) ?>
            <?php if ($this->session->userdata('system_maintenance') == TRUE): ?>
                <form>
                <?php $disabled = "disabled" ?>
            <?php else: ?>
                <?php echo $form_url ?>
            <?php endif ?>
            <div class="reg-block">
                <div class="reg-block-header margin-bottom-10">

                    <div class="text-center">
                        <img src="<?php echo base_url('assets/images/umak-logo2-small.jpg') ?>" alt="University of Makati" class="img-circle" height="90" title="Univeristy of Makati">
                        <h3 class="text-center">
                            UNIVERSITY OF MAKATI<br>
                            <small><?php echo strtoupper(config_item('site_title')) ?></small>
                            <!-- <br> -->
                            <!-- <small>(HSU and COLLEGE)</small> -->
                        </h3>
                        <div style="color:red; font-weight:bold;" class="text-center">
                            <!-- <p><strong>Schedules</strong></p> -->
                        <?php
                           // $late_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate')));
                            //$first_date = date('F j, Y', strtotime($this->session->userdata('date_start')));
                            // $last_date = date('F j, Y', strtotime('-1 day', strtotime($this->session->userdata('date_end'))));
                            //echo $first_date . ' to ' . $last_date;
                             // echo "March 19, 2018 to March 21, 2018 <br>(Graduating Students)
                             //        <br>April 02, 2018 to April 06, 2018 <br>(Non-Graduating Students)";
                           // echo "<em><u>HSU - April 2, 2018 to April 4, 2018</u></em>";
                            //echo "<br>College - April 2, 2018 to April 9, 2018";
                       // echo "March 18, 2019 to March 20, 2019 (Graduating Students) <br>March 20, 2019 to March 22, 2019 <br>(HSU for Grade 12)";
                        ?>
                       <!--  June 11, 2019 to June 25, 2019 <br>(College taking a Summer classes)<br>
                        June 14, 2019 to June 16, 2019 (SOL taking a 2nd Semester classes)    -->

                            <!-- <br>( -->
                           <?php //if (date('Y-m-d') == '2017-03-18' || date('Y-m-d') == '2017-03-19'): ?>
                            <!-- 12:01 am to 11:00 pm -->
                            <!-- until 11:00 PM -->
                           <?php // else: ?>
                            <?php // echo date('h:i a', strtotime($this->session->userdata('time_start'))) ?> 
                            <!-- to  -->
                            <?php //echo date('h:i a', strtotime($this->session->userdata('time_end')))
                                // echo date('h:i a', strtotime("22:00:00"))
                             ?>

                           <?php// endif ?>
                           
                           <!--  07:00 AM to 11:59 PM
                            )
 -->
                        <?php if ($this->session->userdata('system_maintenance')): ?>
                            <?php echo $this->session->userdata('message') ?>
                        <?php endif ?>
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




                    <!-- <h6 style="color:red" class="text-center">ENCODING OF GRADES BEYOND THIS SCHEDULE ARE CONSIDER LATE ENCODEES</h6> -->

                <p class="text-danger">
                    <strong>Note: </strong>Umak Email and Password are case-sensitive, make sure you type them accordingly.
                </p>

                <div class="input-group margin-bottom-20">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <!-- <input name="username" type="text" class="form-control" placeholder="Username" autofocus> -->
                    <?php
                     // $attrib="class='username ' id='username' style='width:100%; heigth:100%;' ".$disabled;
                     // echo form_dropdown('username',$faculty,"",$attrib);
                    ?>
                    <input class='username form-control' name="username" id='username' placeholder="Username" style='width:100%; heigth:100%'  <?php echo $disabled ?>>

                </div>
                <div class="input-group margin-bottom-20">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input name="password" type="password" class="form-control" placeholder="Password" <?php echo $disabled ?>>
                </div>
                <button type="submit" class="btn-u pull-right" <?php echo $disabled ?>>Log In</button>
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
            <?php if ($this->session->userdata('system_maintenance') == TRUE): ?>
                <form>
            <?php else: ?>
                <?php echo $form_url ?>
            <?php endif ?>
    </div>
</div>
