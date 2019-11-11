<!DOCTYPE html>
<html>
<head>
	<title>University of Makati(Information Technology Center) - Grade Sheet <?php  ?></title>
	
	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo config_item('site_description') ?>">
	<meta name="author" content="<?php echo config_item('site_author0') ?>">
	<meta name="author" content="<?php echo config_item('site_author1') ?>">
	
	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/ico/favicon.ico') ?>">

	<!-- CSS Global Compulsory -->
	<?php echo link_tag('assets/css/print/printer.css'); ?>
	<?php echo link_tag('assets/css/normalize.css'); ?>
	<?php //echo link_tag($print_css_link); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/printreg.css">
        

	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/autonumeric.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/disable.js"></script>
	<style type="text/css" media="print">
	    tr    { page-break-inside:avoid; page-break-after:auto;  }
	    thead { display:table-header-group }
	    tfoot { display:table-footer-group }
	</style>

</head>


<body onload="window.print()">
<div class="print">
<?php for ($page=1; $page <= $report['pages'] ; $page++) : ?>
		<div class="container">
			
			<!-- gradesheet header -->
			<div class="row">
				<div class="col-xs-12">
					<div class="text-center">
						<h2 class="printhide"><b>UNIVERSITY OF MAKATI</b></h2>
						<h5 class="printhide"><b>J.P. Rizal Extension, West Rembo, Makati City</b></h5>
						<h3 class="printhide"><b>GRADE SHEET</b></h3>
						<h5><b class="printhide">College of </b><b style="@page{ margin-left:20px; }"><?php echo $schedule->CollegeDesc;?></b></h5>
						<br>
					</div>
					<div class="info">
						<table class=" info table table-condensed ">
							<tbody>
								<tr>
									<td style="padding-left: 20px;" width="140px"><h5><b class="printhide">Course Code: </b></h5></td>
									<td colspan="3"><h5><b><?php echo $schedule->CourseCode;?></b></h5></td>
									<td width=""></td>
									<td width="" ><h5><b class="printhide">Course File Number: </b><b><?php echo $schedule->cfn;?></b></h5></td>
									
								</tr>
								<!-- <tr>
									<td colspan="6" height="15px;">&nbsp;</td>
								</tr> -->
								<tr>
									<td style="padding-left: 20px;" width=""><h5><b class="printhide">Course Desc: </b></h5></td>
									<td colspan="5"><h5><b><?php echo $schedule->CourseDesc;?></b></h5></td>
								</tr>
								<tr>
									<td style="padding-left: 20px;" width=""><h5><b class="printhide">Faculty: </b></h5></td>
									<td colspan="4"><h5><b><?php echo 'Prof. '.$schedule->Title . ' ' . $schedule->Lastname . ', ' . $schedule->Firstname . ' ' . $schedule->Middlename; ?></b></h5></td>
									
									<td width=""><b class="printhide"><h5><b class="printhide">Units: </b><b><?php echo $schedule->Units;?></b></h5></td>
								</tr>
								<!-- <tr>
									<td width="">&nbsp;</td>
									<td colspan="2">&nbsp;</td>
									<td align="">&nbsp;</td>
									<td width="">&nbsp;</td>
									<td width="" align="right">&nbsp;</td>
								</tr> -->
								<tr>
									<td width=""></td>
									<td colspan="4"><h5><b class=""><?php echo $schedule->SemDesc . ', A.Y. ' . $schedule->SyDesc; ?></b></b></h5></td>
									<td width=""><h5><b class="tdspace">Yr & Sec: </b><b><?php echo $schedule->Year . '-' . $schedule->Section;?></b></h5></td>
									<!-- <td width="" align="right">&nbsp;</td> -->
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<!-- end col xs 12 -->		
			</div>
			<!-- end gradesheet header -->

			<!-- gradesheet -->
			<div class="row">
				
				<?php if ($report['col1'] == 1): ?>
					
						<!-- first column -->
						<div class="col-xs-6 lefttab">
							<table class="table table-bordered table-condensed" id="tblCol1">
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
							</table>
						</div>
						<!-- end col first column -->
				<?php $report['col1'] = 0; $report['col2'] = 1; ?>
				<?php endif ?>


				<?php if ($report['col2'] == 1 && (!empty($grades[$report['row']+1]))): ?>

					
						<!-- second column -->
						<div class="col-xs-6 righttab">
						<table class="table table-bordered table-condensed" id="tblCol2">
							<thead>
								<tr>
									<th width="10px">#</th>
									<th width="160px" class="text-center">Name of Students</th>
									<th width="20px" class="text-center">Lec.</th>
									<th width="20px" class="text-center">Lab.</th>
									<th width="40px" class="text-center">Remarks</th>
								</tr>
							</thead>
							<tbody>
									<?php for ($cnt = $report['row'], $row_cnt = 0; ($row_cnt <= $report['row_limit']) && ($report['row'] <= $report['total_row']); $cnt++, $report['row']++, $row_cnt++): ?>
										<?php if (!empty($grades[$report['row']])): ?>
											<?php 
												$grade = $grades[$report['row']]; 
												?>
											<tr>
												<td><b><?php echo ($report['row'] != $report['total_row']) ? ($report['row']+1) : "&nbsp;"; ?></b></td>
												<td><b><?php echo "$grade->Lname, $grade->Fname $grade->Mname"; ?></b></td>
												<td><b><?php echo $grade->StrGrade ?></b></td>
												<td><b><?php echo $grade->StrGrade ?></b></td>
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
						</table>
						</div>
						<!-- end col second column -->
				<?php $report['col1'] = 1; $report['col2'] = 0; ?>
				<?php endif ?>

			</div>
			<!-- end gradesheet -->

			<div class="row">
				<div class="col-xs-12">
					<p class="text-right ptag"><strong>LEGEND: </strong> <b>INC</b> - Incomplete,  <b>OD</b> - Officially Dropped, <b>UD</b> - Unofficially Dropped</p>

			<div class="printhide"><hr></div>
					<h5><b class="printhide">NOTE: INDICATE WHEN STUDENT DROPPED</b></h5>
					<p class="printtab">
						<span class="col-xs-1 text-right"><b><?php echo $report['total_row']; ?></b></span> <strong class="printhide">TOTAL NO. OF STUDENTS REGISTERED</strong><br>
						<span class="col-xs-1 text-right"><b><?php echo $report['cnt_passed']; ?></b></span> <strong class="printhide">TOTAL NO. OF STUDENTS WITH GRADES OF 3.0 OR BETTER</strong><br>
						<span class="col-xs-1 text-right"><b><?php echo $report['cnt_od']; ?></b></span><strong class="printhide"> TOTAL NO. OF STUDENTS WHO DROPPED OFFICIALLY BEFORE END OF SEM.</strong><br>
						<span class="col-xs-1 text-right"><b><?php echo $report['cnt_ud']; ?></b></span> <strong class="printhide">TOTAL NO. OF STUDENTS WHO DROPPED UNOFFICIALLY BEFORE END OF SEM.</strong><br>
						<span class="col-xs-1 text-right"><b><?php echo $report['cnt_inc']; ?></b></span> <strong class="printhide">TOTAL NO. OF STUDENTS WITH INCOMPLETE GRADES</strong><br>
						<span class="col-xs-1 text-right"><b><?php echo $report['cnt_failed']; ?></b></span><strong class="printhide"> TOTAL NO. OF FAILURES</strong><br>
					</p>
				</div>
				<div class="clearfix"></div>
				
				<div class="col-xs-4 text-center">
					<?php echo date_convert_to_php($trans->submitted_at, "M d, Y h:i:s a"); ?><br>
					<p class="underline">
						<b>Encoded Date</b>
					</p>
				</div>

				<div class="col-xs-4 text-center">
					<?php echo date_convert_to_php($trans->prof_print_date, "M d, Y h:i:s a"); ?><br>
					<p class="underline">
						<b>Printed Date</b>
					</p>
				</div>

			</div>
			<!-- <p class="pull-right printhide">( THIS SERVES AS YOUR DRAFT COPY )</p> -->


			<div class="pagebreak"></div>
		</div>
		<!-- end container -->

<?php endfor; ?>


</div>
</body>
</html>