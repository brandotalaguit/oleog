<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo config_item('site_title') ?></title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo config_item('site_description') ?>">
    <meta name="author" content="<?php echo config_item('site_author0') ?>">
    <meta name="author" content="<?php echo config_item('site_author1') ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/ico/favicon.ico') ?>">

    <!-- CSS Global Compulsory -->
    <?php echo link_tag('assets/css/bootstrap-3.1.1.min.css'); ?>
    <?php echo link_tag('assets/css/font-awesome.min.css'); ?>
    <?php echo link_tag('assets/css/select2.min.css'); ?>

    <!-- CSS Theme -->
    <?php echo link_tag('assets/css/page_log_reg_v2.css'); ?>
    <?php echo link_tag(base_url('assets/css/themes.css')); ?>
    <?php echo link_tag('assets/css/login.css'); ?>

    <style type="text/css">
      #color-green
      {
        color: #72c02c;
      }
    </style>
</head>

<body>

<!-- Modal Dialog -->
<div id="errMsg" class="modal fade bs-example-modal-lg"  tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="text-center">Message</h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
<!-- end -->

<?php $this->load->view($content);?>

<!--=== End Content Part ===-->

<!-- JS Global Compulsory -->
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
<?php if (ENVIRONMENT != 'development'): ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/disable.js') ?>"></script>
<?php endif ?>

<!-- JS Implementing Plugins -->
<script type="text/javascript" src="<?php echo base_url('assets/js/select2.min.js')?>"></script>
<?php if (ENVIRONMENT != 'development'): ?>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.backstretch.min.js') ?>"></script>
<?php endif ?>
<script type="text/javascript">
<?php if (ENVIRONMENT != 'development'): ?>
    $.backstretch([
      '<?php echo base_url("assets/images/5.jpg") ?>',
      '<?php echo base_url("assets/images/4.jpg") ?>',
      ], {
        fade: 1000,
        duration: 7000
    });
<?php endif ?>

    jQuery(document).ready(function() {
        $('.username').select2();
    });
</script>

</body>
</html>

