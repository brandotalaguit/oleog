<script type="text/javascript" src="<?php echo base_url();?>assets/js/autonumeric.min.js"></script>
<script type="text/javascript">
    var url = "<?php echo base_url();?>index.php/grading/check_cfn",
    cfn = $('input:hidden[name=cfn]').val();
	$('input[type=text]').autoNumeric({aSep: ''});
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/script.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/script_thesis.js') ?>"></script>
