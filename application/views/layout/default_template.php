<?php $this->output->set_header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>
    <?php echo config_item('site_title') ?> <?php echo $this->session->userdata('sem_code') ?> - A.Y. <?php echo $this->session->userdata('sy_desc') ?>
    </title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo config_item('site_description') ?>">
    <meta name="author" content="<?php echo config_item('site_author0') ?>">
    <meta name="author" content="<?php echo config_item('site_author1') ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/ico/favicon.ico') ?>">

    <SCRIPT TYPE="text/javascript" LANGUAGE="javascript">

    <!-- PreLoad Wait - Script -->
    <!-- This script and more from http://www.rainbow.arch.scriptmania.com

    function waitPreloadPage() { //DOM
    if (document.getElementById){
    document.getElementById('prepage').style.visibility='hidden';
    }else{
    if (document.layers){ //NS4
    document.prepage.visibility = 'hidden';
    }
    else { //IE4
    document.all.prepage.style.visibility = 'hidden';
    }
    }
    }
    // End -->
    </SCRIPT>



    <!-- CSS Global Compulsory -->
    <?php echo link_tag('assets/css/bootstrap-3.1.1.min.css'); ?>
    <?php echo link_tag('assets/components/css/fonts.css'); ?>
    <?php echo link_tag('assets/css/font-awesome.min.css'); ?>
    <?php echo link_tag('assets/components/css/custom.css'); ?>
    <?php echo link_tag('assets/css/login.css'); ?>

</head>

<body id="teaching-load" onLoad="waitPreloadPage();">

<div id="prepage" style="position:absolute; font-size:16; left:0px; top:0px; background-color:white; layer-background-color:white; height:100%; width:100%;">
<TABLE width=100%><TR><TD><B>Loading ... ... Please wait!</B></TD></TR></TABLE>
</div>

<?php $this->load->view('layout/disable_modal'); ?>
<?php $this->load->view('layout/help_modal'); ?>
<?php $this->load->view('layout/youtube_modal'); ?>





<?php $this->load->view('layout/header'); ?>
<?php $this->load->view($content);?>
<?php $this->load->view('layout/footer');?>



<!-- JS Global Compulsory -->
<script src="<?php echo base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
<!-- <script src="<?php echo base_url('assets/js/jquery.formnavigation.js') ?>"></script> -->
<script type="text/javascript">
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<?php if (ENVIRONMENT != 'development'): ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/disable.js') ?>"></script>
<?php endif ?>

<?php if($this->session->flashdata('hlpMsg')) echo "<script>$('#help').modal('show')</script>"; ?>

<?php if(isset($javascript)) $this->load->view($javascript); ?>

</body>
</html>