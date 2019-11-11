<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hsu extends Admin_Controller {

	protected $sched_ids = [16238,16426,16635,16680,16908,17704,18012,18270,18306,18458,18567];
	protected $allow_blank_grades = FALSE;
	protected $grad_date_start = '2019-03-18';
	// protected $grad_date_end = '2019-03-22';
	protected $grad_date_end = '2019-03-25';
	protected $undergraduate = TRUE;
	protected $under_grad_date = '2019-04-01';

	public function __construct()
	{
		parent::__construct();

		$models = array('schedule_hsu_m', 'studgrade_hsu_m', 'studgrade_hsu_rhgp_m', 'user_m', 'studgrade_trans_hsu_m','late_m');

		$this->load->model($models);

		//nocache() method
        $this->output->nocache();
	}

	public function gradesheet($sched_id)
	{
		$sched_ids = $this->sched_ids;

		$time_start = $this->session->userdata('time_start');
		$time_end = $this->session->userdata('time_end');
		$date_start = $this->session->userdata('date_start');
		$date_end = $this->session->userdata('date_end');
		$date_now = date('Y-m-d');
		$time_now = date('H:i:s');

		$faculty_id = $this->session->userdata('faculty_id');
		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		$grad_date_start = $this->grad_date_start;
		$grad_date_end = $this->grad_date_end;

		if (in_array($sched_id, $sched_ids)) {
			$date_end = $date_now;
		}

		// if ($date_now >= '2019-04-01' )
		// {
		// 	$date_end = '2019-05-29';
		// }

		$condition = array('faculty_id'=>$faculty_id,'SyId'=>$sy_id,'Semid' =>$sem_id);
		$this->db->where($condition);
		$get_agree = $this->late_m->get();

		// if (!(count($get_agree)))
		// {
		// 	redirect('confirmation');
		// }


		$teacher_program = $this->schedule_hsu_m->InTeachersProgram($sched_id);
		if ($teacher_program['error'] == TRUE)
		{
			parent::load_error($teacher_program['message']);
		}

		if ( ! ($date_now >= $date_start && $date_now <= $date_end))
		{
			parent::load_error('The online-encoding of grades is now closed.<br>Please visit this site in this schedule ' . $date_start . ' to ' . $date_end);
		}

		if ( ! ($time_now >= $time_start && $time_now <= $time_end))
		{
			parent::load_error('The online-encoding of grades is now closed.<br>This site is only available on this schedule ' . $time_start . ' to ' . $time_end . ' current time:' . $time_now);
		}



		$trans = $this->studgrade_trans_hsu_m->get_by(array(HSU_DB . '.schedules.sched_id' => $sched_id), TRUE);
		$schedule = $teacher_program['schedule'];
		$last_encode_date = substr($schedule->submitted_at, 0, 10);
		if ( ! empty($trans))
		{
			// if ($trans->is_graded == 1);
			/*dump($date_now);
			dump($grad_date_start);
			dump($grad_date_end);
			dump($schedule);
			die();*/
			if ($trans->is_graded == 1 && $schedule->uds == 0)
			{
				if ( ! ($last_encode_date < $this->under_grad_date))
				{
					parent::load_error('You have already finalized the student grades. If you want to update the grades please seek the APPROVAL of the University Registrar!');
				}
			}

			if ($trans->is_graded == 1)
			{
				// if ($last_encode_date > $date_end && $last_encode_date > $grad_date_end )
				if ( ! ($last_encode_date < $this->under_grad_date))
				{
					parent::load_error('You have already confirmed and finalized the student grades. If you want to update the grades please seek the APPROVAL of the University Registrar');
				}
			}
		}

		if ($schedule->CourseCode == "RHGP")
		{
			$this->studgrade_hsu_rhgp_m->autofill($schedule->sched_id);
			$students = $this->studgrade_hsu_rhgp_m->get_by(array('nametable' => $schedule->cfn));
		}
		else
		{
			$this->studgrade_hsu_m->autofill($schedule->sched_id);
			$students = $this->studgrade_hsu_m->get_by(array('nametable' => $schedule->cfn));
		}

		if ($this->undergraduate == TRUE)
		{
			$grades_enabled = $this->studgrade_hsu_m->enable_default_grades($schedule->cfn);
		}

		// pass variables to view
		$this->data['schedule'] = $schedule;
		$this->data['students'] = $students;
		$this->data['trans'] = $trans;
		$this->data['javascript'] = 'faculty/gradebook_hsu_script';

		// display view
		// display view
		if ($schedule->CourseCode == "RHGP")
		{
			$this->data['javascript'] = 'faculty/gradebook_hsu_rhgp_script';
			parent::load_view('faculty/gradebook_rhgp');
		}
		else
		{
			$this->data['javascript'] = 'faculty/gradebook_hsu_script';
			parent::load_view('faculty/gradebook_hsu');
		}

		// log transaction
		$this->user_m->logs('View HSU Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);
	}



	public function convert_grade()
	{
		$sched_id = $this->input->post('sched_id', TRUE);
		$sgid = $this->input->post('studgrade_id', TRUE);
		$grade = $this->input->post('grade', TRUE);
		$error = FALSE;
		$status = NULL;
		$flagLab= FALSE;
		$flagGrade=FALSE;
		// validate schedule
		$schedule = $this->schedule_hsu_m->in_tp($sched_id);
		$last_encode_date = substr($schedule->submitted_at, 0, 10);
		if ($schedule === FALSE)
		{
			if ( ! $this->schedule_hsu_m->count(array('sched_id' => $sched_id)))
			{
				parent::load_error('<i class="fa fa-exclamation-circle"></i> Unathorized access denied. This class schedule does not assigned to your teacher&#39;s program.');
			}
		}

		$trans = $this->studgrade_trans_hsu_m->get_by(array(HSU_DB . '.tbleogtrans.sched_id' => $sched_id), TRUE);

		if ( ! empty($trans))
		{
			// if ($trans->is_graded == 1)
			if ($trans->is_graded == 1 && $schedule->uds == 0)
			{
				if ( ! ($last_encode_date < $this->under_grad_date))
				parent::load_error('You have already finalized the student grades. If you want to update the grades please seek the APPROVAL of the University Registrar');
			}
		}


		// Set up form
		$rules = $this->studgrade_hsu_m->rules_grade_sys;
		$rules['Grade']['rules'] .= '|callback__hsu_rule|min_length[1]|less_than[101]|greater_than[69]';

		if ($schedule->leclab == 1)
		{
			$rules['Grade']['rules'] .= '|callback__lecture_rule|min_length[1]|less_than[101]';
			$this->form_validation->set_rules($this->studgrade_hsu_m->rules_grade_lec);
		}

		$this->form_validation->set_rules($rules);
		$this->form_validation->set_error_delimiters('', '');

		$c_code = "";
		$c_code2= "";

		if ($this->form_validation->run() == TRUE)
		{
			switch ($_POST['Remarks']) {
				case 'INCOMPLETE':
					$c_code = 'label label-primary';
					$c_code2 = 'info';
					break;
				case 'UNOFFICIALLY DROPPED':
					$c_code = 'label label-warning';
					$c_code2 = 'warning';
					break;
				case 'FAILED':
					$c_code = 'label label-danger';
					$c_code2 = 'danger';
					break;
				case 'PASSED':
					$c_code = 'label label-success';
					$c_code2 = 'success';
					break;
			}

		}
		else
		{
			$error = TRUE;
			$StrGrade="";
			$Grade="";
			if (isset($GLOBALS['flagGrade']))
			{
				if ($GLOBALS['flagGrade'])
				{
					$StrGrade= $_POST['StrGrade'];
					$Grade = $_POST['Grade'];
				}
			}
			$_POST['StrGrade'] = $StrGrade;
			$_POST['Grade'] = $Grade;
			$_POST['Remarks'] = '';
			$status = validation_errors();
			$c_code = 'label label-danger';
			$c_code2 = 'danger';

		}


			$json = array(
				'nametable' => $schedule->cfn,
				'error' => $error,
				'StudNo' => $_POST['StudNo'],
				'Grade' => $_POST['Grade'],
				'StrGrade' => $_POST['StrGrade'],
				'Remarks' => $_POST['Remarks'],
				'status' => $status,
				'c_code' => $c_code,
				'c_code2' => $c_code2,
			);


		$stud_grade = $json;

		// remove unneccessary array value
		unset($stud_grade['error']);
		unset($stud_grade['status']);
		unset($stud_grade['c_code']);
		unset($stud_grade['c_code2']);

		// auto save
		// if (intval($sgid))
		$this->studgrade_hsu_m->save($stud_grade, $sgid);

		if ( ! $this->studgrade_trans_hsu_m->count(array('sched_id' => $schedule->sched_id)))
		{
			$this->studgrade_trans_hsu_m->save(array('sched_id' => $schedule->sched_id));
		}

		// header("Content-type: application/json");
		// echo json_encode($json);
		$this->data['json'] = json_encode($json);
		$this->load->view('faculty/json', $this->data);
	}

	public function convert_grade_rhgp()
	{
		$sched_id = $this->input->post('sched_id', TRUE);
		$sgid = $this->input->post('studgrade_id', TRUE);
		$Makadiyos_R1 = $this->input->post('Makadiyos_R1', TRUE);
		$Makadiyos_R2 = $this->input->post('Makadiyos_R2', TRUE);
		$Makatao_R1 = $this->input->post('Makatao_R1', TRUE);
		$Makatao_R2 = $this->input->post('Makatao_R2', TRUE);
		$Makakalikasan_R1 = $this->input->post('Makakalikasan_R1', TRUE);
		$Makabansa_R1 = $this->input->post('Makabansa_R1', TRUE);
		$Makabansa_R2 = $this->input->post('Makabansa_R2', TRUE);
		$error = FALSE;
		$status = NULL;
		$flagLab= FALSE;
		$flagGrade=FALSE;
		$c_code = 'label label-success';
		$c_code2 = 'success';
		// validate schedule
		$schedule = $this->schedule_hsu_m->in_tp($sched_id);
		if ($schedule === FALSE)
		{
			// if ( ! $this->schedule_hsu_m->count(array('sched_id' => $sched_id)))
			if ( ! $this->schedule_hsu_m->count2($sched_id))
			{
				parent::load_error('<i class="fa fa-exclamation-circle"></i> Unathorized access denied. This class schedule does not assigned to your teacher&#39;s program.');
			}
		}

		$trans = $this->studgrade_trans_hsu_m->get_by(array(HSU_DB . '.tbleogtrans.sched_id' => $sched_id), TRUE);

		if ( ! empty($trans))
		{
			if ($trans->is_graded == 1)
			parent::load_error('You have already finalized the student grades. If you want to update the grades please seek the APPROVAL of the University Registrar');
		}


		// Set up form
		$rules = $this->studgrade_hsu_rhgp_m->rules_grade_sys;
		// $rules['Grade']['rules'] .= '|callback__character_rule|min_length[1]|less_than[101]|greater_than[69]';

		// dump($rules);
		$this->form_validation->set_rules($rules);
		$this->form_validation->set_error_delimiters('', '');

		if ($this->form_validation->run() == FALSE)
		{

			$error = TRUE;
			$StrGrade="70";
			$Grade=nf(70, 0);
			// $_POST['Makadiyos_R1'] = $Makadiyos_R1;
			// $_POST['Makadiyos_R2'] = $Makadiyos_R2;
			// $_POST['Makatao_R1'] = $Makatao_R1;
			// $_POST['Makatao_R2'] = $Makatao_R2;
			// $_POST['Makakalikasan_R1'] = $Makakalikasan_R1;
			// $_POST['Makabansa_R1'] = $Makabansa_R1;
			// $_POST['Makabansa_R2'] = $Makabansa_R2;
			$status = validation_errors();
			$c_code = 'label label-danger';
			$c_code2 = 'danger';

		}


			$json = array(
				'nametable' => $schedule->cfn,
				'error' => $error,
				'StudNo' => $_POST['StudNo'],
				'Makadiyos_R1' => $Makadiyos_R1,
				'Makadiyos_R2' => $Makadiyos_R2,
				'Makatao_R1' => $Makatao_R1,
				'Makatao_R2' => $Makatao_R2,
				'Makakalikasan_R1' => $Makakalikasan_R1,
				'Makabansa_R1' => $Makabansa_R1,
				'Makabansa_R2' => $Makabansa_R2,
				'status' => $status,
				'c_code' => $c_code,
				'c_code2' => $c_code2,
			);


		$stud_grade = $json;

		// remove unneccessary array value
		unset($stud_grade['error']);
		unset($stud_grade['status']);
		unset($stud_grade['c_code']);
		unset($stud_grade['c_code2']);

		// auto save
		if (intval($sgid))
		$this->studgrade_hsu_rhgp_m->save($stud_grade, $sgid);

		if ( ! $this->studgrade_trans_hsu_m->count(array('sched_id' => $schedule->sched_id)))
		{
			$this->studgrade_trans_hsu_m->save(array('sched_id' => $schedule->sched_id));
		}

		// header("Content-type: application/json");
		// echo json_encode($json);
		$this->data['json'] = json_encode($json);
		$this->load->view('faculty/json', $this->data);
	}


	public function _lab_rule()
	{
		$output = 0.00;

		$labgrade = $this->input->post('LabGrade', TRUE);
		// if the program reached here meaning the schedule is valid
		// now proceed to convertion
		// $grade = (float) $grade;
		$labgrade = (float) $labgrade;
		$valid_grades = array(5, 6, 7);


		if ( ! (($labgrade >= 1 && $labgrade <= 3.00) || ($labgrade >= 40 && $labgrade <= 100) || in_array($labgrade, $valid_grades)))
		{
			$this->form_validation->set_message('_lab_rule', "INVALID GRADE ENTERED");
			$GLOBALS['flagGrade']=TRUE;
			$GLOBALS['flagLab']=FALSE;
			return FALSE;
		}

		$special_grade = array(6 => array('INCOMPLETE', 'INC'), 7 => array('UNOFFICIALLY DROPPED', 'UD'), 5 => array('FAILED','5.00'));

		if (array_key_exists((int) $labgrade, $special_grade))
		{
			$_POST['LabGrade'] = nf($labgrade);
			$_POST['StrLab'] = $special_grade[$labgrade][1];
			$_POST['Remarks'] = $special_grade[$labgrade][0];

			return TRUE;
		}

		$isPercentSysLab = FALSE;
		// Determine the grading system whether percentage/point system
		if ($labgrade >= 40)
		{
			// Grades greather than or equal 95 will be have an equivalent of 1.00
			if ($labgrade >= 95)
			{
				$_POST['LabGrade'] = nf(1);
				$_POST['StrLab']  = $_POST['LabGrade'];
				$_POST['Remarks'] = 'PASSED';
				return TRUE;
			}

			if ($labgrade > 39 && $labgrade < 75)
			{
				$_POST['LabGrade']= nf(5);
				$_POST['StrLab']  = $_POST['LabGrade'];
				$_POST['Remarks'] = 'FAILED';
				return TRUE;
			}

			// USE THE PERCENTAGE WITH ROUND UP/DOWN FOR DECIMAL GRADE

			// since the grading system of umak has incrementation of 0.10 and grade greather than or equal 95 is equivalent to 1.00
			// we are going to substract the grade to 95 and then multiply it to 0.10 plus 1.00 to get the final output
			$outputLab = round($labgrade, 0, PHP_ROUND_HALF_DOWN);
			$outputLab = abs(95 - $outputLab);
			$outputLab = (float) ($outputLab * 0.10) + 1;
			$isPercentSysLab = TRUE;
		}

		// point system apply the round up/down for decimal grade


		if ( ! $isPercentSysLab)
		$outputLab = round($labgrade, 1, PHP_ROUND_HALF_DOWN);


		$_POST['StrLab'] = nf(round($outputLab, 1, PHP_ROUND_HALF_DOWN));
		$_POST['LabGrade'] =$_POST['StrLab'];
		$_POST['Remarks'] = 'PASSED';

		return TRUE;
	}


	public function _hsu_rule()
	{
		$output = 0.00;
		$grade = $this->input->post('Grade', TRUE);

		// if the program reached here meaning the schedule is valid
		// now proceed to convertion
		$grade = floor($grade);

		if ( ! ($grade >= 70 && $grade <= 100))
		{
			$this->form_validation->set_message('_hsu_rule', "INVALID GRADE ENTERED");
			$GLOBALS['flagLab']=TRUE;
			$GLOBALS['flagGrade']=FALSE;
			return FALSE;
		}

		$output = nf($grade);

		$_POST['StrGrade'] = nf($output, 0);
		$_POST['Grade'] = $_POST['StrGrade'];
		$_POST['Remarks'] = $_POST['StrGrade'] >= 75 ? 'PASSED' : 'FAILED';

		return TRUE;
	}


	public function finish_later($sched_id)
	{
		$sched_id = intval($sched_id);

		if ( ! $sched_id)
		parent::load_error('Access denied! Unathorized access is not allowed');

		$trans = $this->studgrade_trans_hsu_m->get_by(array(HSU_DB . '.tbleogtrans.sched_id' => $sched_id), TRUE);

		if ($trans->is_graded == 1)
		parent::load_error('You have already finalized the student grades. If you want to update the grades please seek the APPROVAL of the University Registrar');


		$teacher_program = $this->schedule_hsu_m->InTeachersProgram($sched_id);
		if ($teacher_program['error'] == TRUE)
		parent::load_error($teacher_program['message']);

		$schedule = $teacher_program['schedule'];

		$message  = "<strong>Draft Saved!</strong> You may edit this data({$schedule->CourseCode} - {$schedule->year_section}) anytime.";
		// $message .= "<br>Please be reminded that encoding period is from " . date('M j, Y', strtotime($this->session->userdata('date_start'))) . ' to ' . date('M j, Y', strtotime($this->session->userdata('date_end'))) . ' (' . date('h:i:s a', strtotime($this->session->userdata('time_start'))) . '-' . date('h:i:s a', strtotime($this->session->userdata('time_end'))) . ")</strong>.";

		$this->session->set_flashdata('success', $message );
		$this->session->set_flashdata('sched_id', $schedule->sched_id);
		$this->user_m->logs('Draft Saved HSU Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);

		redirect(base_url('faculty'));
	}

	public function confirm_grades($sched_id)
	{
		// $this->output->enable_profiler(TRUE);
		$sched_id = intval($sched_id);

		if ( ! $sched_id)
		parent::load_error('Access denied! Unathorized access is not allowed');

		$trans = $this->studgrade_trans_hsu_m->get_by(array(HSU_DB . '.schedules.sched_id' => $sched_id), TRUE);

		if ( ! count($trans))
		parent::load_error('Operation is not allowed. System detected that you have not graded atleast one of your student(s).');

		if ($trans->is_graded == 1)
		parent::load_error('You have already finalized the student grades. If you want to update the grades, file an AMMENDMENT OF GRADES at the Office of the University Registrar.');


		$trans_id = $trans->eog_trans_id;

		$teacher_program = $this->schedule_hsu_m->InTeachersProgram($sched_id);
		if ($teacher_program['error'] == TRUE)
		parent::load_error($teacher_program['message']);


		// count blank grades
		$this->db->where('Remarks', '');
		$blank_grades = $this->studgrade_hsu_m->count(array('nametable' => $teacher_program['schedule']->cfn));

		if ($this->allow_blank_grades == FALSE)
		{
			if ($blank_grades)
			{
				$this->user_m->logs('Failed to finalized Due to BLANK GRADES CFN - ' . $teacher_program['schedule']->cfn . ' Sched Id - ' . $sched_id);
				$url = "hsu/{$sched_id}/gradesheet";
				parent::load_error('Gradesheet cannot be finalized due to blank grade(s)', $url);
			}
		}


		$stud_grade_data = array(
				'is_graded' => 1,
				'submitted_at' => date('Y-m-d H:i:s'),
				'nametable' => $teacher_program['schedule']->cfn,
			);

		$eog_late_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate')));
		$eog_late_date = '2018-04-04';
		if ( ! empty($eog_late_date))
		{
			$current_date = date('Y-m-d');
			if ( $current_date >= $eog_late_date)
			{
				$stud_grade_data['is_late'] = 1;
				$stud_grade_data['late_encoded_at'] = date('Y-m-d H:i:s');
			}
		}

		$this->studgrade_trans_hsu_m->save($stud_grade_data, $trans_id);

		/*$this->studgrade_trans_hsu_m->save(array(
				'is_graded' => 1,
				'submitted_at' => date('Y-m-d H:i:s'),
				'nametable' => $teacher_program['schedule']->cfn
			), $trans_id);*/

		foreach ($this->session->userdata('teach_load_hsu') as $load)
		{
			if ($load->sched_id == $sched_id)
			{
				$load->is_graded = 1;
				break;
			}
		}

		$schedule = $teacher_program['schedule'];

		$message  = 'You have successfully saved the student grades: ';
		$message .= '<p>Course Code: ' . $schedule->CourseCode;
		$message .= '<br>Description: ' . $schedule->CourseDesc;
		$message .= '<br>Section: ' . $schedule->year_section;
		if ($schedule->CourseCode != "RHGP")
		$message .= anchor(base_url("hsu/{$sched_id}/print_gradesheet"), '<strong>CLICK HERE TO PRINT YOUR DRAFT COPY</strong>', array('class' => 'btn btn-primary pull-right', 'target' => '_blank'));
		$message .= '</p>';

		$this->session->set_flashdata('success', $message );
		$this->session->set_flashdata('sched_id', $schedule->sched_id);
		$this->user_m->logs('Save Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);

		redirect(base_url('faculty'));
	}

	public function confirm_graduate_grades($sched_id)
	{
		// $this->output->enable_profiler(TRUE);
		$sched_id = intval($sched_id);

		if ( ! $sched_id)
		parent::load_error('Access denied! Unathorized access is not allowed');

		$trans = $this->studgrade_trans_hsu_m->get_by(array(HSU_DB . '.schedules.sched_id' => $sched_id), TRUE);

		if ( ! count($trans))
		parent::load_error('Operation is not allowed. System detected that you have not graded atleast one of your student(s).');

		if ($trans->is_graded == 1)
		parent::load_error('You have already finalized the student grades. If you want to update the grades, file an AMMENDMENT OF GRADES at the Office of the University Registrar.');


		$trans_id = $trans->eog_trans_id;

		$teacher_program = $this->schedule_hsu_m->InTeachersProgram($sched_id);
		if ($teacher_program['error'] == TRUE)
		parent::load_error($teacher_program['message']);


		$stud_grad_grade_data = array(
				'IsGradSection' => 1,
				'DateSaveGradSection' => date('Y-m-d H:i:s'),
				'nametable' => $teacher_program['schedule']->cfn,
			);

		$eog_late_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate')));
		if ( ! empty($eog_late_date))
		{
			$current_date = date('Y-m-d');
			if ( $current_date >= $eog_late_date)
			{
				$stud_grad_grade_data['is_late'] = 1;
				$stud_grad_grade_data['late_encoded_at'] = date('Y-m-d H:i:s');
			}
		}

		$this->studgrade_trans_hsu_m->save($stud_grad_grade_data, $trans_id);


		/*$this->studgrade_trans_hsu_m->save(array(
				'is_graded' => 1,
				'submitted_at' => date('Y-m-d H:i:s'),
				'nametable' => $teacher_program['schedule']->cfn
			), $trans_id);*/

		foreach ($this->session->userdata('teach_load_hsu') as $load)
		{
			if ($load->sched_id == $sched_id)
			{
				$load->is_graded = 1;
				break;
			}
		}

		$schedule = $teacher_program['schedule'];

		$message  = 'You have successfully saved the student grades: ';
		$message .= '<p>Course Code: ' . $schedule->CourseCode;
		$message .= '<br>Description: ' . $schedule->CourseDesc;
		$message .= '<br>Section: ' . $schedule->year_section;
		if ($schedule->CourseCode != "RHGP")
		$message .= anchor(base_url("hsu/{$sched_id}/print_gradesheet"), '<strong>CLICK HERE TO PRINT YOUR DRAFT COPY</strong>', array('class' => 'btn btn-primary pull-right', 'target' => '_blank'));
		$message .= '</p>';

		$this->session->set_flashdata('success', $message );
		$this->session->set_flashdata('sched_id', $schedule->sched_id);
		$this->user_m->logs('Save Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);

		redirect(base_url('faculty'));
	}

	public function print_gradesheet($sched_id)
	{
		$this->load->helper('text');
		$teacher_program = $this->schedule_hsu_m->InTeachersProgram($sched_id);
		if ($teacher_program['error'] == TRUE)
		{
			parent::load_error($teacher_program['message']);
		}

		$schedule = $teacher_program['schedule'];
		$trans = $this->studgrade_trans_hsu_m->get_by(array(HSU_DB . '.schedules.sched_id' => $sched_id), TRUE);

		if ($trans->is_graded == 0)
			parent::load_error('<i class="fa fa-exclamation-circle"></i> Page cannot be loaded. Please save and confirm the grades then try again.');

		$prof_print_date = date('Y-m-d H:i:s');
		// save prof date print
		$this->studgrade_trans_hsu_m->save(array(
				'is_prof_printed' => 1,
				'prof_date_print' => $prof_print_date,
			), $trans->eog_trans_id);

		$trans->is_prof_printed = 1;
		$trans->prof_print_date = $prof_print_date;

		$this->db->where_not_in('Remarks', array('LOA', 'WITHDRAW CREDENTIAL'));
		$grades = $this->studgrade_hsu_m->get_by(array('nametable' => $schedule->cfn));

		// stats
		$this->data['report']['cnt_passed'] = $this->studgrade_hsu_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'PASSED'));
		$this->data['report']['cnt_od'] = $this->studgrade_hsu_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'OFFICIALLY DROPPED'));
		$this->data['report']['cnt_ud'] = $this->studgrade_hsu_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'UNOFFICIALLY DROPPED'));
		$this->data['report']['cnt_inc'] = $this->studgrade_hsu_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'INCOMPLETE'));
		$this->data['report']['cnt_failed'] = $this->studgrade_hsu_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'FAILED'));

		// pass variables to view
		$this->data['schedule'] = $schedule;
		$this->data['grades'] = $grades;
		$this->data['trans'] = $trans;
		$this->data['ctr'] = 0;

		$this->data['report']['total_row'] = count($grades);
		$this->data['report']['pages'] = ceil((count($grades)+1)/80);
		$this->data['report']['col1'] = 1;
		$this->data['report']['col2'] = 0;
		$this->data['report']['row'] = 0;
		$this->data['report']['row_limit'] = 39;


		$end_note = new stdClass();
		$end_note->Lname = 'XXXXX';
		$end_note->Fname = ' NOTHING FOLLOWS ';
		$end_note->Mname = 'XXXXX';
		$end_note->StudNo = '';
		$end_note->StrGrade = '';
		$end_note->StrLab = '';
		$end_note->Remarks = '';

		$this->data['grades'][] = $end_note;

		$link = array(
		          'href' => 'assets/css/print.css',
		          'rel' => 'stylesheet',
		          'type' => 'text/css',
		          'media' => 'print'
		);

		$this->data['print_css_link'] = $link;

		$this->load->view('faculty/print/print_hsu_gradesheet', $this->data);
		// $this->load->view('faculty/print/print_gradesheet', $this->data);
		// $this->load->view('faculty/print/print_rgradesheet', $this->data);

		// log transaction
		$this->user_m->logs('Print HSU Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);
	}

	public function _rhgp_rule()
	{
		// $output = 0.00;
		$Makadiyos_R1 = $this->input->post('Makadiyos_R1', TRUE);
		$Makadiyos_R2 = $this->input->post('Makadiyos_R2', TRUE);
		$Makatao_R1 = $this->input->post('Makatao_R1', TRUE);
		$Makatao_R2 = $this->input->post('Makatao_R2', TRUE);
		$Makakalikasan_R1 = $this->input->post('Makakalikasan_R1', TRUE);
		$Makabansa_R1 = $this->input->post('Makabansa_R1', TRUE);
		$Makabansa_R2 = $this->input->post('Makabansa_R2', TRUE);

		// if the program reached here meaning the schedule is valid
		// now proceed to convertion
		$Makadiyos_R1 = strtoupper($Makadiyos_R1);
		$Makadiyos_R2 = strtoupper($Makadiyos_R2);
		$Makatao_R1 = strtoupper($Makatao_R1);
		$Makatao_R2 = strtoupper($Makatao_R2);
		$Makakalikasan_R1 = strtoupper($Makakalikasan_R1);
		$Makabansa_R1 = strtoupper($Makabansa_R1);
		$Makabansa_R2 = strtoupper($Makabansa_R2);
		$valid_grades = array("AO","SO","RO","NO");

		if (!in_array($Makadiyos_R1, $valid_grades))
		{
			$_POST['Makadiyos_R1'] = "NO";
			$this->form_validation->set_message('_rhgp_rule', "INVALID GRADE ENTERED");
			return FALSE;
		}
		if (!in_array($Makadiyos_R2, $valid_grades))
		{
			$_POST['Makadiyos_R2'] = "NO";
			$this->form_validation->set_message('_rhgp_rule', "INVALID GRADE ENTERED");
			return FALSE;
		}
		if (!in_array($Makatao_R1, $valid_grades))
		{
			$_POST['Makatao_R1'] = "NO";
			$this->form_validation->set_message('_rhgp_rule', "INVALID GRADE ENTERED");
			return FALSE;
		}
		if (!in_array($Makatao_R2, $valid_grades))
		{
			$_POST['Makatao_R2'] = "NO";
			$this->form_validation->set_message('_rhgp_rule', "INVALID GRADE ENTERED");
			return FALSE;
		}
		if (!in_array($Makakalikasan_R1, $valid_grades))
		{
			$_POST['Makakalikasan_R1'] = "NO";
			$this->form_validation->set_message('_rhgp_rule', "INVALID GRADE ENTERED");
			return FALSE;
		}
		if (!in_array($Makabansa_R1, $valid_grades))
		{
			$_POST['Makabansa_R1'] = "NO";
			$this->form_validation->set_message('_rhgp_rule', "INVALID GRADE ENTERED");
			return FALSE;
		}
		if (!in_array($Makabansa_R2, $valid_grades))
		{
			$_POST['Makabansa_R2'] = "NO";
			$this->form_validation->set_message('_rhgp_rule', "INVALID GRADE ENTERED");
			return FALSE;
		}

		return TRUE;
	}


}

/* End of file hsu.php */
/* Location: ./application/controllers/hsu.php */
