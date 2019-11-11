<article class="teaching-load-encode">
	
	<ol class="breadcrumb">
		<li>You are here:</li>
		<li><a href="<?php echo base_url('faculty') ?>">Teaching Load</a></li>
		<li><i class="fa fa-angle-right"></i></li>
		<li><?php echo $schedule->year_section;?></li>
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
							<td class="text-right"><?php echo $schedule->year_section;?></td>
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

	<div class="alert alert-success callout" style="margin-bottom:10px;"><strong>REMINDER</strong>: Please <strong>DOUBLE CHECK</strong> the grades of your students <strong>BEFORE</strong> clicking the <strong>SAVE</strong> button.</div>
	<div class="alert alert-info">
		<strong>Legend Observed Values :</strong>
		<p>
			<b>AO </b> - Always Observed <span style="margin-right: 20px;"></span>
			<b>SO </b> - Sometimes Observed <span style="margin-right: 20px;"></span>
			<b>RO </b> - Rarely Observed<span style="margin-right: 20px;"></span>
			<b>NO - Not Observed</b></p>
	</div>

	<div class="list-of-student">
		<div class="panel-default panel">
			<div class="panel-body">
				<div class="table-responsive">
					
							<table id="tblgrd" class="table table-striped">
								<thead>
									<tr>
										<th rowspan="2" width="3%" style="vertical-align: top;" class="text-right">#</th>
										<th class="text-center" style="vertical-align: top;" rowspan="2" width="27%">Name</th>
										<th class="text-center" colspan="2" rowspan="1" width="20%">Maka-Diyos</th>
										<th class="text-center" colspan="2" rowspan="1" width="20%">Makatao</th>
										<th class="text-center" style="vertical-align: top;" rowspan="1" width="10%">Makakalikasan</th>
										<th class="text-center" colspan="2" rowspan="1" width="20%">Makabansa</th>
										<!-- <th width="15%">Remarks</th> -->
									</tr>
									<tr>
										<th width="10%" class="text-center" >A</th>
										<th width="10%" class="text-center" >B</th>
										<th width="10%" class="text-center" >C</th>
										<th width="10%" class="text-center" >D</th>
										<th class="text-center" >E</th>
										<th width="10%" class="text-center" >F</th>
										<th width="10%" class="text-center" >G</th>
									</tr>
								</thead>
								<tbody>

					<?php
									$ctr = 1;
									$ctr1 = 1;
									foreach ($students as $row) :

												if ($row->enabled == 1)
												{
													$Makadiyos_R1  = "<input type='text' name='Makadiyos_R1[]' class='form-control input-mini Grade' tabindex='{$ctr1}' ";
													$Makadiyos_R1 .= " value='{$row->Makadiyos_R1}' maxlength='5' char='5' />";
													
													++$ctr1;
													$Makadiyos_R2  = "<input type='text' name='Makadiyos_R2[]' class='form-control input-mini Grade' tabindex='{$ctr1}' ";
													$Makadiyos_R2 .= " value='{$row->Makadiyos_R2}' maxlength='5' char='5' />";
													++$ctr1;
													$Makatao_R1  = "<input type='text' name='Makatao_R1[]' class='form-control input-mini Grade' tabindex='{$ctr1}' ";
													$Makatao_R1 .= " value='{$row->Makatao_R1}' maxlength='5' char='5' />";
													
													++$ctr1;
													$Makatao_R2  = "<input type='text' name='Makatao_R2[]' class='form-control input-mini Grade' tabindex='{$ctr1}' ";
													$Makatao_R2 .= " value='{$row->Makatao_R2}' maxlength='5' char='5' />";
													
													++$ctr1;
													$Makakalikasan_R1  = "<input type='text' name='Makakalikasan_R1[]' class='form-control input-mini Grade' tabindex='{$ctr1}' ";
													$Makakalikasan_R1 .= " value='{$row->Makakalikasan_R1}' maxlength='5' char='5' />";

													++$ctr1;
													$Makabansa_R1  = "<input type='text' name='Makabansa_R1[]' class='form-control input-mini Grade' tabindex='{$ctr1}' ";
													$Makabansa_R1 .= " value='{$row->Makabansa_R1}' maxlength='5' char='5' />";

													++$ctr1;
													$Makabansa_R2  = "<input type='text' name='Makabansa_R2[]' class='form-control input-mini Grade' tabindex='{$ctr1}' ";
													$Makabansa_R2 .= " value='{$row->Makabansa_R2}' maxlength='5' char='5' />";

													
												}
												else
												{
													$Makadiyos_R1  = "<input type='hidden' name='Makadiyos_R1[]' value='{$row->Makadiyos_R1}' />";
													$Makadiyos_R1 .= "<span class='uneditable-input form-control' disabled>{$row->Makadiyos_R1}</span>";

													$Makadiyos_R2  = "<input type='hidden' name='Makadiyos_R2[]' value='{$row->Makadiyos_R2}' />";
													$Makadiyos_R2 .= "<span class='uneditable-input form-control' disabled>{$row->Makadiyos_R2}</span>";

													$Makatao_R1  = "<input type='hidden' name='Makatao_R1[]' value='{$row->Makatao_R1}' />";
													$Makatao_R1 .= "<span class='uneditable-input form-control' disabled>{$row->Makatao_R1}</span>";

													$Makatao_R2  = "<input type='hidden' name='Makatao_R2[]' value='{$row->Makatao_R2}' />";
													$Makatao_R2 .= "<span class='uneditable-input form-control' disabled>{$row->Makatao_R2}</span>";

													$Makakalikasan_R1  = "<input type='hidden' name='Makakalikasan_R1[]' value='{$row->Makakalikasan_R1}' />";
													$Makakalikasan_R1 .= "<span class='uneditable-input form-control' disabled>{$row->Makakalikasan_R1}</span>";

													$Makabansa_R1  = "<input type='hidden' name='Makabansa_R1[]' value='{$row->Makabansa_R1}' />";
													$Makabansa_R1 .= "<span class='uneditable-input form-control' disabled>{$row->Makabansa_R1}</span>";

													$Makabansa_R2  = "<input type='hidden' name='Makabansa_R2[]' value='{$row->Makabansa_R2}' />";
													$Makabansa_R2 .= "<span class='uneditable-input form-control' disabled>{$row->Makabansa_R2}</span>";
												}

												
					?>

									<tr>
										<td style="text-align:center"><?php echo $ctr; ?></td>
										<td>
											<?php echo $row->Lname.",".$row->Fname." ".$row->Mname;?>
											<br>
											<code><small><?php echo $row->StudNo;?></small></code>
										</td>
										<td>
											<?php echo $Makadiyos_R1; ?>
										</td>
										<td>
											<?php echo $Makadiyos_R2; ?>
										</td>
										<td>
											<?php echo $Makatao_R1; ?>
										</td>
										<td>
											<?php echo $Makatao_R2; ?>
										</td>
										<td>
											<?php echo $Makakalikasan_R1; ?>
										</td>
										<td>
											<?php echo $Makabansa_R1; ?>
										</td>
										<td>
											<?php echo $Makabansa_R2; ?>
											<input class='studgrade_id' type="hidden" name="studgrade_id[]" value="<?php echo $row->studgrade_id;?>">
											<input class='StudNo' type="hidden" name="StudNo[]" value="<?php echo $row->StudNo;?>">
										</td>

										
										<!-- <td>
											
										</td>
										<td>
											
										</td>
										<td>
											<span class="">
												
											</span>
										</td> -->
									</tr>
								<?php
									$ctr++;
									endforeach;
								?>
								</tbody>
							</table>
							<?php echo form_hidden('base_url', base_url('hsu/convert_grade_rhgp')); ?>
							<?php echo form_hidden('confirm_url', base_url("hsu/{$schedule->sched_id}/confirm_grades")); ?>
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
	<div class="alert alert-error" style="margin-bottom:0px;">
		<h3>WARNING: </h3>
		<!-- Clicking <strong>SAVE</strong> button will save these data and cannot be edited without undergoing amendments of grade's procedure. -->
		Missing students in the grading system should be verified and corrected by the Registrar's Office <strong>BEFORE SAVING ANY OF THIS DATA.</strong>
		Any more changes to the grades after saving would have to undergo the process of "<strong>AMENDMENT OF GRADES</strong>".
	</div>
</div>


		<form method="post" id="frmGrd" autocomplete="off">
<div class="form-actions">
	<div class="pull-right">
        <?php echo anchor(base_url('hsu/'.$schedule->sched_id.'/finish_later'), '<strong>Finish Later</strong>', array('class' => 'btn btn-large btn-warning', 'style' => 'margin-right: 20px;')); ?>
		<button id="btnSend" type="button" tabindex="<?php echo $ctr+1; ?>" class="btn btn-large btn-primary">I understand and accept the reminder and warning stated above. <strong>SAVE FINAL DATA</strong></button>
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


