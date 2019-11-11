<div class="heading">
	<h2 class="visible-print">
		UNIVERSITY OF MAKATI
		<br><small>A.Y. <?php echo $this->session->userdata('sy_desc'); ?> - <?php echo $this->session->userdata('sem_code'); ?></small>
	</h2>
	<p class="text-right">
	<?php !$download_link || print $download_link; ?>
	<button type="button" class="btn btn-default hidden-print" onclick="window.print()">Print this page</button>
	</p>

	<h3><?php echo $title ?></h3>
	<hr align="left">
	
</div>

<?php if ( ! empty($affected_student)): ?>
		<div class="teaching-load">
			<?php /*
			
			<?php foreach ($colleges as $college): ?>
				<?php if ($courses[$college]): ?>
					
				<?php endif ?>
			<?php endforeach ?>

			*/ ?>

			<div class="courses">

				<div class="panel-default panel">
				  <div class="panel-body">
				    <div class="table-responsive">
				      <table class="table table-striped">
				        <thead>
				          <tr>
				            <th style="width: 1%">No.</th>
				            <th style="width: 10%">Name</th>
				            <th style="width: 8%">Student No</th>
				            <th style="width: 5%">Yr &amp; Level</th>
				            <th style="width: 5%">College</th>
				            <th style="width: 10%">Curriculum</th>
				            <th style="width: 10%">Program</th>
				            <th style="width: 10%">Major</th>
				            <th style="width: 7%">ROG Date</th>
				          </tr>
				        </thead>
				        <tbody>
				        	<?php $total = 0; ?>
				        	<?php $cnt = 1; ?>
				        	<?php foreach ($affected_student as $student): ?>
					          <tr>
					            <td><?php echo $cnt++ ?></td>
					            <td><?php echo $student['Lname'] . ', ' . $student['Fname'] . ' ' . $student['Mname'] ?></td>
					            <td><?php echo $student['StudNo'] ?></td>
					            <td title="<?php echo $student['LengthOfStayBySem'] ?>"><?php echo $student['yr_level'] ?></td>
					            <td title="<?php echo $student['CollegeId'] ?>"><?php echo $student['CollegeCode'] ?></td>
					            <td title="<?php echo $student['CurriculumId'] ?>"><?php echo $student['CurriculumDesc'] ?></td>
					            <td><?php echo $student['ProgramDesc'] ?></td>
					            <td><?php echo $student['MajorDesc'] ?></td>
					            <td><?php if(strtotime($student['d_release'])>0) print date('F j,Y', strtotime($student['d_release'])); ?></td>
					          </tr>
				        	<?php endforeach ?>
						</tbody>
				      </table><!-- table -->
				    </div><!-- table-responsive -->
				  </div><!-- panel-body -->
				  
				</div>




			</div>
			


		</div>
<?php else: ?>
		<div class="alert alert-info text-center">
			<h3>No Record Found <i class="fa fa-exclamation"></i></h3>
		</div>
<?php endif ?>
