



<article class="teaching-load-encode">
	
	<ol class="breadcrumb">
		<li>You are here:</li>
		<li><a href="<?php echo base_url('faculty') ?>">Teaching Load</a></li>
		<li><i class="fa fa-angle-right"></i></li>
		<li><?php echo $schedule->Year . ' - ' . $schedule->Section;?></li>
	</ol>

	<div class="course-information">
		<div class="heading">
			<h3>Course Information</h3>
			<hr align="left">
		</div>


		<div class="row">
			
			<div class="col-sm-6">
				<div class="table-responsive">
					<table class="table">
						<tbody>
						<tr>
							<td><i class="fa fa-graduation-cap"></i> <small>Course Description</small></td>
							<td class="text-right"><?php echo $schedule->CourseDesc ?></td>
						</tr>
						<tr>
							<td><i class="fa fa-graduation-cap"></i> <small>Course Code</small></td>
							<td class="text-right"><?php echo $schedule->CourseCode ?></td>
						</tr>
						</tbody>
					</table><!-- table -->
				</div><!-- table-responsive -->
			</div><!-- col-sm-6 -->

			<div class="col-sm-6">
				<div class="table-responsive">
					<table class="table">
					<tbody>
						<tr>
							<td><i class="fa fa-bookmark"></i> <small>CFN</small></td>
							<td class="text-right"><?php echo $schedule->cfn ?></td>
						</tr>
						<tr>
							<td><i class="fa fa-tags"></i> <small>Section</small></td>
							<td class="text-right"><?php echo $schedule->Year . ' - ' . $schedule->Section;?></td>
						</tr>												
						<tr>
							<td><i class="fa fa-database"></i> <small>No. of Units</small></td>
							<td class="text-right"><?php echo $schedule->Units ?></td>
						</tr>
					</tbody>
					</table><!-- table -->
				</div><!-- table-responsive -->			
			</div><!-- col-sm-6 -->
		</div><!-- row -->
	</div><!-- course-information -->

	<div class="alert alert-info callout" style="margin-bottom:10px;">
		<p>Student with <code>COR NOT PRINTED</code> beside the name of your student are student who did not print their COR's yet included in Encoding of Grades as long as they made their Payment.</p>
		<p>Students who did print their COR are student included in classlist, please advise your student to print their COR.</p>
		<hr>
		<p><strong>REMINDER</strong>: Please <strong>DOUBLE CHECK</strong> the grades of your students <strong>BEFORE</strong> clicking the <strong>SAVE</strong> button.</p>

	</div>

	<div class="list-of-student">
		<div class="panel-default panel">
			<div class="panel-body">
				<div class="table-responsive">
					
							<table id="tblgrd" class="table table-striped">
								<thead>
									<tr>
										<th width="3%" class="text-right">#</th>
										<th width="20%">Lastname</th>
										<th width="20%">Firstname</th>
										<th width="15%">Initials</th>
										<th width="10%">Student Id</th>
										<th width="7%">Grade</th>
										<th class="5%">Output</th>
										<th width="15%">Remarks</th>
									</tr>
								</thead>
								<tbody>

					<?php
									$ctr = 1;
									$point_grade_sys = $schedule->leclab == 3;
									foreach ($students as $row) :

												if ($row->enabled == 1)
												{
													$input  = "<input type='text' name='grade[]' class='form-control input-mini Grade' tabindex='{$ctr}' ";
													
													$new_old = $row->new_grade_sys == 1 ? 'New Grading ' : 'Old Grading';
													! $point_grade_sys || $new_old = 'Point';

													$input .= 'data-toggle="tooltip" data-placement="top" title="Input ' . $new_old . ' System"';
													$input .= " value='{$row->Grade}' maxlength='5' char='5' />";
												}
												else
												{
													$input  = "<input type='hidden' name='grade[]' value='{$row->Grade}' />";
													$input .= "<span class='uneditable-input form-control' disabled>{$row->Grade}</span>";
												}

												$output = "<input type='hidden' name='StrGrade[]' value='{$row->StrGrade}' class='StrGrade' />";
												$output.= "<span class='uneditable-input output form-control' disabled>{$row->StrGrade}</span>";
					?>

									<tr>
										<td width="3%" style="text-align:center"><?php echo $ctr; ?></td>
										<td width="20%"><?php echo $row->Lname;?><?php if($row->IsPrinted == 0) echo '<br><code>COR NOT PRINTED</code>'; ?></td>
										<td width="20%"><?php echo $row->Fname;?></td>
										<td width="15%"><?php echo $row->Mname;?></td>
										<td width="5%">
											<?php echo $row->StudNo;?>
											<input class='StudNo' type="hidden" name="StudNo[]" value="<?php echo $row->StudNo;?>">
											<input class='studgrade_id' type="hidden" name="studgrade_id[]" value="<?php echo $row->studgrade_id;?>">
										</td>
										<td width="7%">
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
							<?php echo form_hidden('base_url', base_url('gradebook/convert_grade')); ?>
							<?php echo form_hidden('confirm_url', base_url("gradebook/{$schedule->sched_id}/confirm_grades")); ?>
							<?php echo form_hidden('confirm_url_grad', base_url("gradebook/{$schedule->sched_id}/confirm_graduate_grades")); ?>
							<?php echo form_hidden('sched_id', $schedule->sched_id); ?>
							<?php echo form_hidden('cfn', $schedule->cfn); ?>

				</div><!-- table-responsive -->
			</div><!-- panel-body -->
			<div class="panel-footer">
				<p class="text-center">*** Nothing Follows ***</p>
			</div><!-- panel-footer -->
		</div><!-- panel-default -->
		<div class="oval-shadow"></div>		
	</div><!-- list-of-student -->
</article><!-- teaching-load -->



<div class="row-fluid">
	
	<div class="alert alert-info" style="margin:10px 0; font-size: 17px;">
		<h3 style="margin-top: 5px;">Reminders: </h3>
		<!-- Clicking <strong>SAVE</strong> button will save these data and cannot be edited without undergoing amendments of grade's procedure. -->
		Missing students in the grading system should be verified and corrected by the Registrar's Office <strong>BEFORE SAVING ANY OF THIS DATA.</strong>
		Any more changes to the grades after saving would have to undergo the process of "<strong>AMENDMENT OF GRADES</strong>".
	</div>
</div>


		<form method="post" id="frmGrd" autocomplete="off">
<div class="form-actions">
	<div class="pull-right">
        <?php echo anchor(base_url('gradebook/'.$schedule->sched_id.'/finish_later'), '<strong>Finish Later</strong>', array('class' => 'btn btn-large btn-lg btn-warning', 'style' => 'margin-right: 20px;')); ?>
        <!-- button id="btnSendGrad" type="button" tabindex="<?php echo $ctr+1; ?>" class="btn btn-large btn-primary"> <strong>SAVE GRADUATING STUDENTS</strong></button> -->
		<button id="btnSend" type="button" tabindex="<?php echo $ctr+1; ?>" class="btn btn-large btn-lg btn-primary">I understand and accept the reminders stated above. <strong>SAVE FINAL DATA</strong></button>
	</div>
</div>
		</form>

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


