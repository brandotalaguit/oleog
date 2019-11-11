<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/autonumeric.js"></script>
<!-- <script type="text/javascript" src="<?php echo base_url();?>assets/php/equivalent.php"></script> -->


<div class="panel panel-default-dark">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-file-text"></i> Grade Sheet - <?php echo $schedule->cfn ?></h3>
	</div>
	<!-- List group -->
  <ul class="list-group">
    <li class="list-group-item">

				<div class="row">

					<div class="col-xs-3">
						<strong>Course Code:</strong>
						<h2><?php echo $schedule->CourseCode ?></h2>
					</div>

					<div class="col-xs-6">
						<strong>Course Description:</strong>
						<h2>
							<?php echo $schedule->CourseDesc; ?>
						</h2>
					</div>


					<div class="col-xs-1 text-center">

						<span class="badge badge-blue rounded">
							<span style="font-weight: bold; border-bottom: 1px solid #fff; line-height: 20px; ">NO. OF UNITS</span><br>
							<span class="lead" style="font-weight: bold; line-height: 30px; ">
								<?php echo $schedule->Units;?>
							</span>
							<hr style="margin: 10px 0;">
							CLASS SIZE: <?php echo count($students) ?>
						</span>

					</div>

					<div class="col-xs-1 text-center">
						<span class="badge badge-blue rounded">
							<span style="font-weight: bold; border-bottom: 1px solid #fff; line-height: 20px; ">SECTION</span><br>
							<span class="lead">
							<strong><?php echo $schedule->Year . ' - ' . $schedule->Section;?></strong>
							</span>
							<hr style="margin: 10px 0;">
							CFN: <?php echo $schedule->cfn ?>
						</span>
					</div>

				</div><!--end row-->

		</li>
    <li class="list-group-item">

		</li>
  </ul>
	<div class="margin-bottom-10"></div>
	<?php echo $form_url; ?>
	<!-- <div class="panel-body"> -->
		<table id="tblgrd" class="table table-striped">
			<thead>
				<tr>
					<th width="3%" class="text-right">#</th>
					<th width="20%">Lastname</th>
					<th width="20%">Firstname</th>
					<th width="15%">Initials</th>
					<th width="10%">Student Id</th>
					<th width="5%">Grade</th>
					<th class="5%">Output</th>
					<th width="15%">Remarks</th>
				</tr>
			</thead>
			<tbody>

<?php
				$ctr = 1;
				foreach ($students as $row) :

							if ($row->enabled == 1)
							{
								$input  = "<input type='text' name='grade[]' class='form-control input-mini Grade' tabindex='{$ctr}' ";
								$input .= " value='{$row->Grade}' maxlength='5' char='5' />";
							}
							else
							{
								$input  = "<input type='hidden' name='grade[]' value='{$row->Grade}' />";
								$input .= "<span class='uneditable-input form-control' disabled>{$row->Grade}</span>";
							}

							$output = "<input type='hidden' name='StrGrade[]' value='{$row->StrGrade}' class='StrGrade' />";
							$output.= "<span class='uneditable-input output lead form-control' disabled>{$row->StrGrade}</span>";
?>

				<tr>
					<td width="3%" style="text-align:center"><?php echo $ctr; ?></td>
					<td width="20%"><?php echo $row->Lname;?></td>
					<td width="20%"><?php echo $row->Fname;?></td>
					<td width="15%"><?php echo $row->Mname;?></td>
					<td width="5%">
						<?php echo $row->StudNo;?>
						<input class='StudNo' type="hidden" name="StudNo[]" value="<?php echo $row->StudNo;?>">
						<input class='studgrade_id' type="hidden" name="studgrade_id[]" value="<?php echo $row->studgrade_id;?>">
					</td>
					<td width="5%">
						<?php echo $input; ?>
					</td>
					<td width="5%">
						<?php echo $output; ?>
					</td>
					<td width="15%">
						<span class="">
							<?php echo $row->Remarks; ?>
						</span>
					</td>
				</tr>
			<?php
				$ctr++;
				endforeach;
			?>
			</tbody>
		</table>
		<?php echo form_hidden('base_url', base_url('faculty/convert_grade')); ?>
		<?php echo form_hidden('confirm_url', base_url("faculty/{$schedule->sched_id}/{$trans->eog_trans_id}/grades_confirm")); ?>
		<?php echo form_hidden('sched_id', $schedule->sched_id); ?>
		<?php echo form_close(); ?>

		<div class="row-fluid">
			<div class="alert alert-success lead" style="margin-bottom:10px;"><strong>REMINDER</strong>: Please <strong>DOUBLE CHECK</strong> the grades of your students <strong>BEFORE</strong> clicking the <strong>SAVE</strong> button.</div>
			<div class="alert alert-error lead" style="margin-bottom:0px;">
				<h3>WARNING: </h3>
				<!-- Clicking <strong>SAVE</strong> button will save these data and cannot be edited without undergoing amendments of grade's procedure. -->
				Missing students in the grading system should be verified and corrected by the Registrar's Office <strong>BEFORE SAVING ANY OF THIS DATA.</strong>
				Any more changes to the grades after saving would have to undergo the process of "<strong>AMENDMENT OF GRADES</strong>".
			</div>
		</div>

		<div class="form-actions">
			<div class="pull-right">
				<button id="btnSend" type="button" tabindex="<?php echo $ctr+1; ?>" class="btn btn-large btn-primary">I understand and accept the reminder and warning stated above. <strong>SAVE THESE DATA</strong></button>

			</div>
		</div>


	<!-- </div>  -->
	<!--end panel-body -->



</div>

<div>
</div>

<div class="row-fluid" style="background-color:white;">
	<table class="table table-condensed table-striped table-hover" style="background-color:#005580; margin-bottom:0; padding-right:15px;">
		<thead>

		</thead>
	</table>
</div>

<div class="row-fluid">
	<div class="span12" style="height:410px;overflow:auto;margin-bottom:5px; border:1px solid #DDDDDD" >
		<!-- <form method="post" id="frmGrd" action="<?php echo base_url();?>index.php/grading/confirm_grades" autocomplete="off"> -->
		<form method="post" id="frmGrd" autocomplete="off">
			<input type="hidden" name="cfn" value="<?php echo $schedule->cfn;?>">
			<!-- <table id="tblgrd" class="table table-bordered table-condensed" style="background-color:white"> -->


		</form>

	</div>

</div>


<!-- Dialogbox -->
<div id="modal_dialogbox" class="modal dialogbox hide fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-header"><h3 class="text-danger"></h3></div>
  <div class="modal-body lead"></div>
  <div class="modal-footer"></div>

</div>

<!-- confirm dialog -->

<div class="modal fade" id="modal_confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"><h3 class="text-primary"><strong></strong></h3></div>
      <div class="modal-body lead"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>

<!-- message dialog -->
<div class="modal fade" id="modal_message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header"><h3><strong></strong></h3></div>
      <div class="modal-body lead"></div>
      <div class="modal-footer"></div>
    </div>
  </div>
</div>
<script type="text/javascript">

	    var url = "<?php echo base_url();?>index.php/grading/check_cfn",
	    cfn = "<?php echo $schedule->cfn;?>";

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

		$('input[type=text]').autoNumeric();



		// $("input:text:enabled:first").focus();
		// $("input:text:enabled:first").select();

</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/script.js') ?>"></script>
