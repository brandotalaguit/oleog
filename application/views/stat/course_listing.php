<div class="heading">
	<h2 class="visible-print">
		UNIVERSITY OF MAKATI
		<br><small>A.Y. <?php echo $this->session->userdata('sy_desc'); ?> - <?php echo $this->session->userdata('sem_code'); ?></small>
	</h2>
	<p class="text-right">
	<?php !empty($download_link) || print $download_link; ?>
	<button type="button" class="btn btn-default hidden-print" onclick="window.print()">Print this page</button>
	</p>

	<?php 
		if ( ! empty($late_date)) {
			echo '<b>Late Date: ' . date('F j, Y', strtotime($late_date));
		}
	 ?>
	<h3><?php echo $title ?></h3>
	<hr align="left">
	

</div>

<?php if ( ! empty($courses)): ?>
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
				            <?php /*
				            <th style="width: 5%">College</th>
				            */ ?>
				            <th style="width: 1%">No.</th>
				            <th style="width: 10%">Course Code</th>
				            <th style="width: 15%" class="hidden-print">Description</th>
				            <th style="width: 5%">Yr &amp; Section</th>
				            <th style="width: 5%" class="hidden-print">Units</th>
				            <th style="width: 10%">Faculty</th>
				            <th style="width: 5%">Class Size</th>
				            <th style="width: 5%">Date</th>
				          </tr>
				        </thead>
				        <tbody>
				        	<?php $total = 0; ?>
				        	<?php $cnt = 1; ?>
				        	<?php foreach ($courses as $stat): ?>
					          <tr>
					            <?php /*
					            <td><?php echo $stat->CollegeCode ?></td>
					            */ ?>
					            <td><?php echo $cnt++ ?></td>
					            <td><?php echo $stat->CourseCode ?></td>
					            <td class="hidden-print"><small><?php echo $stat->CourseDesc ?></small></td>
					            <td><?php echo $stat->yr_section ?></td>
					            <td class="hidden-print"><?php echo $stat->Units ?></td>
					            <td><?php echo $stat->Lastname . ', ' . $stat->Firstname . ' ' . $stat->Middlename ?></td>
					            <td><?php echo $stat->enrollees ?></td>
					            <td><?php if(strtotime($stat->Date_Submitted)>0) print date('F j,Y', strtotime($stat->Date_Submitted)); ?></td>
					            <?php /*
					            <?php $total += $stat->enrollees ?>
					            */ ?>
					          </tr>
				        	<?php endforeach ?>
						</tbody>
						<?php /*
						<!-- <tfoot>
							<tr>
								<td colspan="6" class="text-right">Sub-Total</td>
								<td><?php echo $total ?></td>
								<td>&nbsp;</td>
							</tr>
						</tfoot> -->
						*/ ?>
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
