<h3>
	Encoding of Grades Statistics
	<br><small>A.Y. <?php echo $this->session->userdata('sy_desc'); ?> - <?php echo $this->session->userdata('sem_code'); ?></small>
</h3>

<?php
	if ($this->session->userdata('DeanId') == FALSE)
	echo anchor('site/hsu_stat/summary', 'HSU Encoding of Grades Statistics', array('class' => 'btn-link hidden-print', 'target' => '_blank'));
	elseif ($this->session->userdata('DeanId') == 9)
	{
	 	echo anchor('site/hsu_stat/summary', 'HSU Encoding of Grades Statistics', array('class' => 'btn-link hidden-print', 'target' => '_blank'));
	}
?>

<div class="row">

<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Courses Offered</h3>
		</div>
		<div class="panel-body">

			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-right">
			<h1 style="margin-bottom: 0px;">
			<strong>
				<?php echo $total_course; ?>
			</strong>
			</h1>
			<span class="label label-danger">Total Courses Offered</span>
			</div>

			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-right">
			<h1 style="margin-bottom: 0px;">
			<strong>
				<?php echo $total_faculty; ?>
			</strong>
			</h1>
			<span class="label label-danger">No. of Faculty</span>
			</div>

			<?php if ($this->session->userdata('DeanId') == FALSE): ?>


			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
				<strong>ACTION</strong>
				<p>
				<?php echo anchor('site/stat/late_encode', 'View Late Encoding', array('class' => 'btn-link label label-primary hidden-print', 'target' => '_blank')); ?>
				<?php echo anchor('site/stat/on_time_encode', 'View On-Time Encoding', array('class' => 'btn-link label label-primary hidden-print', 'target' => '_blank')); ?>
				<?php echo anchor('site/stat/not_encoded', 'View Not Yet Encoded', array('class' => 'btn-link label label-primary hidden-print', 'target' => '_blank')); ?>
				<?php echo anchor('site/stat/download', 'Download Encoding Stat', array('class' => 'btn-link label label-primary hidden-print', 'target' => '_blank')); ?>
				<?php echo anchor('site/stat/download_data', 'Download Data', array('class' => 'btn-link label label-primary hidden-print', 'target' => '_blank')); ?>
				</p>
			</div>

				<?php if ($this->session->userdata('sem_id') == 2): ?>
					<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
						<strong>GRADUATING SECTION</strong>
						<p>
						<?php echo anchor('site/stat/late_encode_graduating_section', 'View Late Encoding', array('class' => 'btn-link label label-primary hidden-print', 'target' => '_blank')); ?>
						<?php echo anchor('site/stat/not_encoded_graduating_section', 'View Not Yet Encoded', array('class' => 'btn-link label label-primary hidden-print', 'target' => '_blank')); ?>
						</p>
					</div>
				<?php endif ?>

			<?php endif ?>
		</div>
	</div>
</div>
</div>


<?php $ctr = 1; ?>
<?php $total_on_time = $total_late = $total_graded = $total_pending = $total_not_yet_graded = $total_no_graded = $total_load = 0; ?>
<?php foreach ($colleges as $college): ?>
<div class="clearfix"></div>

<div class="panel panel-default">

	<!-- Default panel contents -->
	<div class="panel-heading">
	<h3><?php echo get_key($college, 'CollegeDesc'); ?><br><small>(<?php echo get_key($college, 'CollegeCode'); ?>)</small></h3>
	</div>
	<!-- Table -->
	<table class="table table-bordered table-condensed table-responsive">
			<thead>
				<tr>
					<th rowspan="2">No.</th>
					<th rowspan="2">Faculty</th>
					<th rowspan="2" class="text-center">No. of Load</th>
					<th colspan="3" class="text-center">Graded</th>
					<th colspan="3" class="text-center">Not Graded</th>
				</tr>
				<tr>
					<th>On-Time</th>
					<th>Late</th>
					<th>TOTAL</th>
					<th><small>Not yet finalize</small></th>
					<th><small>Not yet Graded</small></th>
					<th>TOTAL</th>
				</tr>
			</thead>

			<tbody>
			<?php $cnt_on_time = $cnt_late = $cnt_graded = $cnt_pending = $cnt_not_yet_graded = $cnt_no_graded = $total = 0; ?>
			<?php foreach ($college_stat as $value): ?>
				<?php if (get_key($value, 'CollegeCode') == get_key($college, 'CollegeCode')): ?>
					<?php $faculty_id = $value->faculty_id; $attr = array('target' => '_blank'); ?>
				<tr>
					<td><?php echo $ctr++ ?></td>
					<td title="Faculty Id: <?php echo $faculty_id ?>"><?php echo $value->Lastname ?>, <?php echo $value->Firstname ?><?php echo $value->Middlename ?></td>
					<td data-toggle="tooltip" title="Total" data-container="body" class="text-center" >
					<?php echo $value->total ?>

					</td>
					<td data-toggle="tooltip" title="On-Time Encoding" data-container="body" class="text-success text-center">
					<?php echo anchor("site/stat/{$faculty_id}/on_time_encode", $value->on_time, $attr);  ?>
					</td>
					<td data-toggle="tooltip" title="Late Encoding" data-container="body" class="text-danger text-center">
					<?php echo anchor("site/stat/{$faculty_id}/late_encode", $value->late, $attr);  ?>
					</td>
					<th  data-toggle="tooltip" title="Graded" data-container="body" class="text-center">
					<?php echo $value->on_time + $value->late ?>

					</th>
					<td  data-toggle="tooltip" title="Waiting to save" data-container="body" class="text-info text-center">
					<?php echo $value->waiting_to_save ?>

					</td>
					<td  data-toggle="tooltip" title="Not yet graded" data-container="body" class="text-danger text-center">
					<?php echo $value->not_yet_graded ?>

					</td>
					<th data-toggle="tooltip" title="Not Graded" data-container="body" class="text-center" >
					<?php echo $value->waiting_to_save + $value->not_yet_graded ?>

					</th>

				</tr>
				<?php $cnt_on_time += $value->on_time; ?>
				<?php $cnt_late += $value->late; ?>
				<?php $cnt_graded += $value->on_time + $value->late; ?>
				<?php $cnt_pending += $value->waiting_to_save; ?>
				<?php $cnt_not_yet_graded += $value->not_yet_graded; ?>
				<?php $cnt_no_graded += $value->waiting_to_save + $value->not_yet_graded; ?>
				<?php $total += $value->total; ?>

				<?php $total_on_time += $value->on_time; ?>
				<?php $total_late += $value->late; ?>
				<?php $total_graded += $value->on_time + $value->late; ?>
				<?php $total_pending += $value->waiting_to_save; ?>
				<?php $total_not_yet_graded += $value->not_yet_graded; ?>
				<?php $total_no_graded += $value->waiting_to_save + $value->not_yet_graded; ?>
				<?php $total_load += $value->total; ?>

				<?php endif ?>
			<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" class="text-right"><strong><?php echo get_key($college, 'CollegeCode'); $ctr = 1; ?></strong></th>
					<th>Total Load: <?php echo $total; ?></th>
					<th>On-Time: <?php echo $cnt_on_time; ?></th>
					<th>Late: <?php echo $cnt_late; ?></th>
					<th>Total: <?php echo $cnt_graded; ?></th>
					<th><small>Not yet finalize</small>: <?php echo $cnt_pending; ?></th>
					<th><small>Not yet graded</small>: <?php echo $cnt_not_yet_graded; ?></th>
					<th>Total: <?php echo $cnt_no_graded; ?></th>
				</tr>
			</tfoot>

	</table>
</div>
		<?php endforeach ?>

<div class="row">


<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Graded</h3>
		</div>
		<div class="panel-body">

			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right">
			<h1 style="margin-bottom: 0px;">
			<strong>
				<?php echo $total_on_time; ?>
			</strong>
			</h1>
			<span class="label label-danger">Total On-Time</span>
			</div>

			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right">
			<h1 style="margin-bottom: 0px;">
			<strong>
				<?php echo $total_late; ?>
			</strong>
			</h1>
			<span class="label label-danger">Total Late</span>
			</div>

			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right">
			<h1 style="margin-bottom: 0px;">
			<strong>
				<?php echo $total_graded; ?>
			</strong>
			</h1>
			<span class="label label-danger">Total Graded</span>
			</div>


		</div>
	</div>
</div>

<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title">Not Graded</h3>
		</div>
		<div class="panel-body">

			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right">
			<h1 style="margin-bottom: 0px;">
			<strong>
				<?php echo $total_pending; ?>
			</strong>
			</h1>
			<span class="label label-danger">Total Waiting to Save</span>
			</div>

			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right">
			<h1 style="margin-bottom: 0px;">
			<strong>
				<?php echo $total_not_yet_graded; ?>
			</strong>
			</h1>
			<span class="label label-danger">Total Not Yet Graded</span>
			</div>

			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right">
			<h1 style="margin-bottom: 0px;">
			<strong>
				<?php echo $total_no_graded; ?>
			</strong>
			</h1>
			<span class="label label-danger">Total No Grade</span>
			</div>


		</div>
	</div>
</div>
</div>
