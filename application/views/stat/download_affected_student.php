<style type="text/css">
	td{
	  vertical-align: top;
	  text-align: left;
	  word-wrap: break-word;
	}
</style>
<?php if ( ! empty($affected_student)): ?>
<table border="1">
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
<?php else: ?>
<h3>No Record Found <i class="fa fa-exclamation"></i></h3>
<?php endif ?>
