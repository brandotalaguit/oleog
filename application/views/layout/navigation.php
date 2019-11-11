<style type="text/css">
  body {
    padding-top: 50px;
  }
</style>
<div class="navbar"> <!--navbar-inverse-->
  <div class="navbar-inner navbar-fixed-top">
     <a class="brand" href="<?php echo base_url();?>" onclick="return false;">
        <strong>Encoding of Grades <small>v.2.1.0</small></strong>
        <?php if (!empty($course_info['NAMETABLE'])): ?>
          
          <?php if (substr($course_info['NAMETABLE'], 0, 1) != 'K'): ?>
          <div style="font-size:15px; line-height:20px;" class="label label-important">Enter "6" in Grade to indicate "Incomplete"</div>
          <?php endif ?>

        <?php endif ?>

        <!-- <div style="font-size:15px; line-height:20px; text-transform:uppercase" class="label label-important" title="Graduating Students">
          Graduating Students ONLY
        </div> -->
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
    <a class="brand">
      
    <span class="text-error" style="font-size:17px;font-weight:bold;zoom:90%">
      DEMO VERSION ONLY
    </span>
    </a>

    <ul class="nav pull-right">
      <li class="divider-vertical"></li>
      <li>
          <div align="center">
        <a href="<?php echo base_url();?>index.php/grading/logout" class="btn btn-danger">
            <i style='font-size:25px; margin-top:3px; margin-bottom:4px; ' class="icon-off icon-large"></i>
            <br> <strong>LOG-OUT</strong>
        </a>
          </div>          
      </li>
    </ul>
  </div>
</div>