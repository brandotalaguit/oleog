<style type="text/css">
  body {
    padding-top: 50px;
  }
</style>
<div class="navbar"> <!--navbar-inverse-->
  <div class="navbar-inner navbar-fixed-top">
     <a class="brand" href="<?php echo base_url();?>" onclick="return false;">
        <strong>Encoding of Grades <small>v.2.1.0</small></strong>
        <!-- <div style="font-size:15px; line-height:20px;" class="label label-important">Enter "6" in Grade to indicate "Incomplete"</div> -->
    </a>
    <ul class="nav">
      <li class="divider-vertical"></li>
      <li <?php echo (strtolower($this->uri->segment(2,'index')) == 'index' ?  "class='active'": "");?>>
        <a href="<?php echo base_url();?>index.php/grading/">
          <div align="center">
            <i style='font-size:25px; margin-top:3px; margin-bottom:4px; ' class="icon-list-alt icon-large"></i>
            <p>Teaching Load</p>
          </div>
        </a>
      </li>
      <li class="divider-vertical"></li>
      <li <?php echo (strtolower($this->uri->segment(2,'index')) == 'encoded' ?  "class='active'": "");?>>
        <a href="<?php echo base_url();?>index.php/grading/encoded">
          <div align="center">
            <i style='font-size:25px; margin-top:3px; margin-bottom:4px; ' class="icon-folder-open icon-large"></i>
            <p>Encoded Graded</p>
          </div>
        </a>
      </li>
      <li class="divider-vertical"></li> 
      <li <?php echo (((strtolower($this->uri->segment(2,'')) == 'class_list') || (strtolower($this->uri->segment(2,'')) == 'confirm_grades')) ?  "class='active'": "");?>>
        <a href="<?php echo base_url();?>index.php/grading/prof_info" onclick="return false;">
          <div align="center">
            <i style='font-size:25px; margin-top:3px; margin-bottom:4px; ' class="icon-user icon-large"></i>
            <p><?php echo $title . " " . $lastname . ", " . substr($firstname,0,1) . " " . substr($initials,0,1); ?></p>
          </div>
        </a>
      </li>
      <li class="divider-vertical"></li>
    </ul>
    <ul class="nav pull-right">
      <li class="divider-vertical"></li>
      <li>
        <a href="<?php echo base_url();?>">
          <div align="center">
            <i style='font-size:25px; margin-top:3px; margin-bottom:4px; ' class="icon-off icon-large"></i>
            <h4 class="text-error">Log Out</h4>
          </div>          
        </a>
      </li>
    </ul>
  </div>
</div>