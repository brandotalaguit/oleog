<thead>
	<tr>
		<th width="10px">#</th>
		<th width="160px" class="text-center">Name of Students</th>
		<?php if ($schedule->leclab == 1): ?>
		<th width="20px" class="text-center">Lec.</th>
		<th width="20px" class="text-center">Lab.</th>
		<?php else: ?>											
		<th width="20px" class="text-center">Grade</th>
		<?php endif ?>
		<th width="40px" class="text-center">Remarks</th>
	</tr>
</thead>
<tbody>
	<?php for ($cnt = $report['row'], $row_cnt = 0; ($row_cnt <= $report['row_limit']) && ($report['row'] <= $report['total_row']); $cnt++, $report['row']++, $row_cnt++): ?>
		<?php if (!empty($grades[$report['row']])): ?>
			<?php $grade = $grades[$report['row']]; ?>
			<tr>
				<td><b><?php echo ($report['row'] != $report['total_row']) ? ($report['row']+1) : "&nbsp;"; ?></b></td>
				<td><b><?php echo "$grade->Lname, $grade->Fname $grade->Mname"; ?></b></td>
				<?php if ($schedule->leclab == 1): ?>
				<td><b><?php echo $grade->StrGrade ?></b></td>
				<td><b><?php echo $grade->StrLab ?></b></td>
				<?php else: ?>
				<td><b><?php echo $grade->StrGrade ?></b></td>
				<?php endif ?>
				<td><b>
					<?php if (ucwords(strtolower($grade->Remarks))=="Unofficially Dropped") {
						echo "UD";
						}elseif (ucwords(strtolower($grade->Remarks))=="Officially Dropped") {
						echo "OD";
						}elseif (ucwords(strtolower($grade->Remarks))=="Incomplete") {
						echo "INC";
						}else echo ucwords(strtolower($grade->Remarks));
					 ?>
				</b></td>
			</tr>
		<?php endif ?>
	<?php endfor ?>

</tbody>