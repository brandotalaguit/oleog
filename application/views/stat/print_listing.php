<!DOCTYPE html>
<html>
<head>
	<title>List of Courses</title>
	<meta charset="utf-8">
	
	<style type="text/css">
		table {	white-space: nowrap; }
	</style>

</head>
<body>

	<table>
		<?php foreach ($colleges as $college) { ?>
			<tr>
			<thead>
			<th colspan="8"><h3><?php echo $college->CollegeDesc ?></h3></th>
			</thead>
			</tr>
			<tbody>
				<tr>
					<table style="white-space: nowrap;">
						<thead>
							<th>No.</th>
							<th>Faculty</th>
							<th>Course Code</th>
							<th>Description</th>
							<th>Yr &amp; Section</th>
							<th>Units</th>
							<th>Class Size</th>
							<th>Date</th>
						</thead>
						<tbody>
						<?php $ctr = 1; ?>
						<?php foreach ($courses as $row): ?>
							<?php if ($row->CollegeCode == $college->CollegeCode): ?>
								<tr>
								<td><?php echo $ctr++ ?></td>
								<td><?php echo $row->Lastname . ', ' . $row->Firstname . ' ' . $row->Middlename ?></td>
								<td><?php echo $row->CourseCode ?></td>
								<td><?php echo $row->CourseDesc ?></td>
								<td><?php echo $row->yr_section ?></td>
								<td><?php echo $row->Units ?></td>
								<td><?php echo $row->enrollees ?></td>
								<td><?php if(strtotime($row->Date_Submitted)>0) print date('F j,Y', strtotime($row->Date_Submitted)); ?></td>
								</tr>
							<?php endif ?>
						<?php endforeach ?>
						</tbody>
					</table>
				</tr>
			</tbody>
			<tfoot>
				<tr><td>&nbsp;</td></tr>
			</tfoot>
		<?php } // endforeach ?>
	</table>




</body>
</html>