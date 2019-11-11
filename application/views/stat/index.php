
<div class="heading">
	<h3><?php echo $college->CollegeDesc ?> Encoding of Grades Statistics</h3>
	<hr align="left">
</div>

<?php if ( ! empty($statistics)): ?>
		<div class="teaching-load">
			<div class="courses">
				<h3>College Faculty</h3>
				<div class="panel-default panel">
				  <div class="panel-body">
				    <div class="table-responsive">
				      <table class="table table-striped">
				        <thead>
				          <tr>
				            <th style="width: 10%">Lastname</th>
				            <th style="width: 10%">Firstname</th>
				            <th style="width: 5%">M.I.</th>
				            <th style="width: 10%"><small>Waiting to save the encoded grade</small></th>
				            <th style="width: 5%"><small>Save Courses</small></th>
				            <th style="width: 5%"><small>Courses not yet graded</small></th>
				            <th style="width: 5%">Total</th>
				          </tr>
				        </thead>
				        <tbody>

				        	<?php foreach ($statistics as $stat): ?>
					          <tr>
					            <td><?php echo $stat->Lastname ?></td>
					            <td><?php echo $stat->Firstname ?></td>
					            <td><?php echo substr($stat->Middlename, 1, 0) ?></td>
					            <td><?php echo $stat->waiting_to_save ?></td>
					            <td><?php echo $stat->save_courses ?></td>
					            <td><?php echo $stat->not_yet_graded ?></td>
					            <td><?php echo $stat->total ?></td>
					            <?php $tot_wait += $stat->waiting_to_save ?>
					            <?php $tot_save += $stat->save_courses ?>
					            <?php $tot_nyg += $stat->not_yet_graded ?>
					            <?php $total += $stat->total ?>
					          </tr>
				        	<?php endforeach ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3" class="text-right">Sub-Total</td>
								<td><?php echo $tot_wait ?></td>
								<td><?php echo $tot_save ?></td>
								<td><?php echo $tot_nyg ?></td>
								<td><?php echo $total ?></td>
							</tr>
						</tfoot>
				      </table><!-- table -->
				    </div><!-- table-responsive -->
				  </div><!-- panel-body -->
				  
				</div>
			</div>
			
		</div>
<?php endif ?>

<?php if ( ! empty($statistics)): ?>
<div class="teaching-load">
	<div class="courses">
		<h3>HSU Faculty</h3>
		<div class="panel-default panel">
		  <div class="panel-body">
		    <div class="table-responsive">
		      <table class="table table-striped">
		        <thead>
		          <tr>
		            <th style="width: 10%">Lastname</th>
		            <th style="width: 10%">Firstname</th>
		            <th style="width: 5%">M.I.</th>
		            <th style="width: 10%"><small>Waiting to save the encoded grade</small></th>
		            <th style="width: 5%"><small>Save Courses</small></th>
		            <th style="width: 5%"><small>Courses not yet graded</small></th>
		            <th style="width: 5%">Total</th>
		          </tr>
		        </thead>
		        <tbody>

		        	<?php foreach ($statistics2 as $stat): ?>
			          <tr>
			            <td><?php echo $stat->Lastname ?></td>
			            <td><?php echo $stat->Firstname ?></td>
			            <td><?php echo substr($stat->Middlename, 1, 0) ?></td>
			            <td><?php echo $stat->waiting_to_save ?></td>
			            <td><?php echo $stat->save_courses ?></td>
			            <td><?php echo $stat->not_yet_graded ?></td>
			            <td><?php echo $stat->total ?></td>
			            <?php $tot_wait += $stat->waiting_to_save ?>
			            <?php $tot_save += $stat->save_courses ?>
			            <?php $tot_nyg += $stat->not_yet_graded ?>
			            <?php $total += $stat->total ?>
			          </tr>
		        	<?php endforeach ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="3" class="text-right">Sub-Total</td>
						<td><?php echo $tot_wait ?></td>
						<td><?php echo $tot_save ?></td>
						<td><?php echo $tot_nyg ?></td>
						<td><?php echo $total ?></td>
					</tr>
				</tfoot>
		      </table><!-- table -->
		    </div><!-- table-responsive -->
		  </div><!-- panel-body -->
		  
		</div>
	</div>
	
</div>
<?php endif ?>