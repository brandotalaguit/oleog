<script type="text/javascript" src="<?php echo base_url();?>assets/js/autonumeric.min.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/php/equivalent.php"></script> -->

<script type="text/javascript">
    var url = "<?php echo base_url();?>index.php/grading/check_cfn",
    cfn = $('input:hidden[name=cfn]').val();

    // $.post( url, { "cfn": cfn },
    // function(data) {
    //     console.log(data.msg);
		// console.log(data.error);
		// error = data.error;
    //     if (data.error == true) {
    //         $(".myModalLabel").empty().append(data.title);
    //         $(".modal-body").empty().append(data.msg);
    //         $(".modal-footer").empty().append("<a href='<?php echo base_url();?>index.php/grading' class='btn btn-danger btn-large'>Close</a>");
    //         $("#modal_dialogbox").modal('show');
    //     }
    //  }, "json");
	<?php if ($schedule->leclab != 4): ?>
		$('input[type=text]').autoNumeric({aSep: ''});
	<?php endif; ?>

</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/script.js') ?>"></script>