 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gradebook extends Admin_Controller {

	protected $special_grades = array(6 => array('INCOMPLETE', 'INC'), 7 => array('UNOFFICIALLY DROPPED', 'UD'), 5 => array('FAILED','5.00'));
	protected $grad_date_start = '2019-03-18';
	// protected $grad_date_end = '2019-03-22';
	protected $grad_date_end = '2019-03-25';
	protected $undergraduate = TRUE;
	protected $allow_blank_grades = FALSE;
	protected $under_grad_date = '2019-04-01';

	/*
	* Sched Id's allowed to be grade
	* @int
	*/
	protected $sched_ids = [

	];

	/*
	* Faculty Id's allowed to be grade
	* @int
	*/
	protected $faculty_id = [

	];

	public function __construct()
	{
		parent::__construct();

		$models = array('schedule_m', 'student_schedule_m', 'studgrade_m', 'user_m', 'studgrade_trans_m', 'thesis_m','late_m');
		$this->load->model($models);

		//nocache() method
        $this->output->nocache();
	}

	public function gradesheet($sched_id)
	{
		// $this->output->enable_profiler(TRUE);
		$sched_ids = $this->sched_ids;
		// dump($this->session->userdata('faculty_id'));
		$sysem = array('SyId' => $this->sy, 'SemId' => $this->sem);

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

		if ($date_now == '2017-03-18' || $date_now == '2017-03-19' )
		{
			$time_start	= "00:01:00";
			$time_end = '23:00:00';
		}

		if ($date_now == '2017-03-17')
		{
			$time_end = '23:59:00';
		}

		if (in_array($sched_id, $sched_ids)) {
			$date_end = $date_now;
		}

		if ($this->CollegeId == 21) {
			$date_end = $date_now;
		}

		if (in_array($this->session->userdata('faculty_id'), $this->faculty_id)) {
			$date_end = $date_now;
			$time_start	= "00:01:00";
			$time_end = '23:00:00';
		}

		$date_end = $date_now;

		$condition = array('faculty_id'=>$faculty_id,'SyId'=>$sy_id,'Semid' =>$sem_id);
		$this->db->where($condition);
		$get_agree = $this->late_m->get();

		// if (!(count($get_agree)))
		// {
		// 	redirect('confirmation');
		// }


		$teacher_program = $this->schedule_m->InTeachersProgram($sched_id);
		if ($teacher_program['error'] == TRUE)
		{
			parent::load_error($teacher_program['message']);
		}

		if ( ! ($date_now >= $date_start && $date_now <= $date_end))
		{
			parent::load_error('The online-encoding of grades is now closed.<br>Please visit this site in this schedule ' . $date_start . ' to ' . $date_end );
		}

		if ( ! ($time_now >= $time_start && $time_now <= $time_end))
		{
			parent::load_error('The online-encoding of grades is now closed.<br>This site is only available on this schedule ' . $time_start . ' to ' . $time_end . ' current time:' . $time_now);
		}

		// if ( ! in_array($this->session->userdata('faculty_id'), array(187, 121)))
		// {
		// 	if ($date_now < '2016-03-28')
		// 		parent::load_error('The online encoding of grades (College graduating students) is now <strong class="lead">CLOSED</strong>.<br> Encoding of grades for non-graduating students (College) is on March 28-31, 2016.');
		// }

		// if ($date_now >= $grad_date_end && ! in_array($this->session->userdata('faculty_id'), array(284, 20, 333)))
		// if ($date_now >= $grad_date_end)
		// {
		// 	parent::load_error('The online encoding of grades (College graduating students) is now <strong class="lead">CLOSED</strong>.<br> Encoding of grades for non-graduating students (College) is on March 28-31, 2016.');
		// }


		$schedule = $teacher_program['schedule'];


		$trans = $this->studgrade_trans_m->get_by(array('tblsched.sched_id' => $sched_id), TRUE);
		$last_encode_date = substr($schedule->submitted_at, 0, 10);
		if ( ! empty($trans))
		{
			// if ($trans->is_graded == 1 && ! ($date_now >= $grad_date_start && $date_now <= $grad_date_end) && $schedule->uds == 0)
			if ($trans->is_graded == 1 && $schedule->uds == 0)
			{
				if ( ! ($last_encode_date < $this->under_grad_date))
				{
					parent::load_error('You have already finalized the student grades. If you want to update the grades please seek the APPROVAL of the University Registrar!');
				}
			}

			if ($trans->is_graded == 1)
			{

				// if ($last_encode_date > $date_end && $last_encode_date > $grad_date_end );
				if ( ! ($last_encode_date < $this->under_grad_date))
				{
					parent::load_error('You have already confirmed and finalized the student grades. If you want to update the grades please seek the APPROVAL of the University Registrar');
				}
			}


		}
		// dump($schedule->leclab);
		$this->studgrade_m->autofill($schedule->sched_id);
		// if ($date_now >= $grad_date_start && $date_now <= $grad_date_end);
		if ($this->undergraduate == TRUE)
		{
			$grades_enabled = $this->studgrade_m->enable_default_grades($schedule->cfn);
		}


		$students = $this->studgrade_m->get_by(array('nametable' => $schedule->cfn));

		// pass variables to view
		$this->data['schedule'] = $schedule;
		$this->data['students'] = $students;
		$this->data['trans'] = $trans;

		$this->data['javascript'] = 'faculty/gradebook_script';

		// lecture / masteral / character
		if (in_array($schedule->leclab, array(NULL, 0, 3, 4)))
			parent::load_view('faculty/gradebook');

		$this->data['javascript'] = 'faculty/gradebook_thesis_script';
		if ($schedule->leclab == 6)
			parent::load_view('faculty/gradebook_thesis');

		if ($schedule->leclab == 1)
		{
			$this->data['javascript'] = 'faculty/gradebook_leclab_script';
			parent::load_view('faculty/gradebookleclab');
		}


		// log transaction
		$this->user_m->logs('View Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);
	}


	public function thesis_title()
	{

		$this->data['hidden'] = array(
				'thesis_id' => $this->input->post('thesis_id', TRUE) ,
				'thesis_sched_id' => $this->input->post('thesis_sched_id', TRUE) ,
				'thesis_studno' => $this->input->post('thesis_studno', TRUE) ,
				'thesis_uri' => base_url('gradebook/save_thesis_title') ,
			);
		$this->load->view('faculty/modal_thesis', $this->data);
	}

	public function get_thesis_title()
	{
		$studno = $this->input->post('StudNo', TRUE);
		$sched_id = intval($this->input->post('sched_id', TRUE));

		$json['thesis'] = '';
		$json['thesis_id'] = NULL;
		$json['colorcode'] = 'btn-default';

		$thesis = $this->thesis_m->get_by(array('sched_id' => $sched_id, 'studno' => $studno), TRUE);
		if (count($thesis))
		{
			$json['thesis'] = $thesis->title;
			$json['thesis_id'] = $thesis->id;
			$json['colorcode'] = 'btn-primary';
		}

		$this->data['json'] = json_encode($json);
		$this->load->view('faculty/json', $this->data);
	}

	public function save_thesis_title()
	{
		$this->form_validation->set_rules('thesis_title', 'Thesis Title', 'strtoupper|trim|xss_clean');
		$this->form_validation->set_rules('thesis_id', 'Id', 'intval|xss_clean');
		$this->form_validation->set_rules('thesis_sched_id', 'Sched Id', 'intval|required');
		$this->form_validation->set_rules('thesis_studno', 'Student Id', 'strtoupper|trim|required');

		$title = $this->input->post('thesis_title', TRUE);
		$data = array(
				'title' => $title,
				'sched_id' => $this->input->post('thesis_sched_id', TRUE),
				'studno' => $this->input->post('thesis_studno', TRUE),
			);

		// Process form
		$id = $this->_is_unique($this->input->post('thesis_id', TRUE));
		$this->thesis_m->save($data, $id);

		$json = array('colorcode' => ! empty($title) ? 'btn-primary' : 'btn-default', '
			thesis' => strtoupper($title),
			'validation' => validation_errors(),
			'id' => $id);

		$this->data['json'] = json_encode($json);
		$this->load->view('faculty/json', $this->data);
	}



	public function convert_grade()
	{
		// $date_now = date('Y-m-d');

		// $grad_date_start = $this->grad_date_start;
		// $grad_date_end = $this->grad_date_end;
		// if ($date_now >= $grad_date_start && $date_now <= $grad_date_end))
		// {
		// 	$json['error' => TRUE, 'message' => 'Encoding is now closed.'];
		// 	return json_encode($json);
		// }

		$sched_id = $this->input->post('sched_id', TRUE);
		$sgid = $this->input->post('studgrade_id', TRUE);
		$grade = $this->input->post('grade', TRUE);
		$error = FALSE;
		$status = NULL;
		$flagLab= FALSE;
		$flagGrade=FALSE;
		// validate schedule
		$schedule = $this->schedule_m->in_tp2($sched_id);
		if ($schedule === FALSE)
		{
			if ( ! $this->schedule_m->count2($sched_id))
			{
				parent::load_error('<i class="fa fa-exclamation-circle"></i> Unathorized access denied. This class schedule does not assigned to your teacher&#39;s program.');
			}
		}

		// Set up form
		$new_grade_sys = $this->studgrade_m->get_student_grade_system($sgid);

		$rules = $this->studgrade_m->rules_grade_sys;
		$grade_rule = $this->studgrade_m->get_rules($schedule->leclab, $new_grade_sys, 'Grade');
		$rules['Grade']['rules'] .= $grade_rule;

		if ($schedule->leclab == 1)
		{
			$lab_rules = $this->studgrade_m->rules_grade_lec;
			$lab_rules['LabGrade']['rules'] .= $new_grade_sys ? "|callback__20162017_grade_rule[LabGrade]" : "|callback__lab_rule";
			$this->form_validation->set_rules($lab_rules);
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

			if ($schedule->leclab == 1)
			{
				$_POST['StrLab'] = $StrGrade;
				$_POST['LabGrade'] = $Grade;
			}

			$_POST['Remarks'] = '';
			$status = validation_errors();
			$c_code = 'label label-warning';
			$c_code2 = 'warning';

		}

		if ($schedule->leclab == 1)
		{

			if (isset($GLOBALS['flagLab']))
			{
				$StrLab="UD";
				$LabGrade=nf(7);
				if ($GLOBALS['flagLab'])
				{
					$StrLab = $_POST['StrLab'];
					$LabGrade= nf($_POST['LabGrade']);
				}
				$_POST['StrLab'] = $StrLab;
				$_POST['LabGrade'] = $LabGrade;
			}

			if ($_POST['StrLab']=="5" || $_POST['StrGrade']=="5"  )
			{
				$_POST['Remarks']="FAILED";
				$c_code = 'label label-danger';
				$c_code2 = 'danger';
			}
			elseif ( $_POST['StrLab']=="UD" || $_POST['StrGrade']=="UD" )
			{
				$_POST['Remarks']="UNOFFICIALLY DROPPED";
				$c_code = 'label label-warning';
				$c_code2 = 'warning';
			}
			elseif ($_POST['StrLab']=="INC" || $_POST['StrGrade']=="INC" )
			{
				$_POST['Remarks']="INCOMPLETE";
				$c_code = 'label label-info';
				$c_code2 = 'info';
			}

			$tmpLab = '';
			$tmpLabStr = '';
			if (nf($_POST['LabGrade']) != 0)
			{
				$tmpLab = nf($_POST['LabGrade']);
				$tmpLabStr = $_POST['StrLab'];
			}

			$json = array(
				'nametable' => $schedule->cfn,
				'error' => $error,
				'StudNo' => $_POST['StudNo'],
				'Grade' => nf($_POST['Grade']),
				'StrGrade' => $_POST['StrGrade'],
				// 'LabGrade' => nf($_POST['LabGrade']),
				// 'StrLab' => $_POST['StrLab'],
				'LabGrade' => $tmpLab,
				'StrLab' => $tmpLabStr,
				'Remarks' => $_POST['Remarks'],
				'status' => $status,
				'c_code' => $c_code,
				'c_code2' => $c_code2,
			);
		}
		else
		{
			$json = array(
				'nametable' => $schedule->cfn,
				'error' => $error,
				'StudNo' => $_POST['StudNo'],
				'Grade' => $schedule->leclab == 4 ?$_POST['Grade']:nf($_POST['Grade']),
				'StrGrade' => $_POST['StrGrade'],
				'Remarks' => $_POST['Remarks'],
				'status' => $status,
				'c_code' => $c_code,
				'c_code2' => $c_code2,
			);
		}

		$stud_grade = $json;

		// remove unneccessary array value
		unset($stud_grade['error']);
		unset($stud_grade['status']);
		unset($stud_grade['c_code']);
		unset($stud_grade['c_code2']);

		// auto save
		// if (intval($sgid))
		$this->studgrade_m->save($stud_grade, $sgid);

		if ( ! $this->studgrade_trans_m->count(array('sched_id' => $schedule->sched_id)))
		{
			$this->studgrade_trans_m->save(array('sched_id' => $schedule->sched_id));
		}

		// echo json_encode($json);
		$json['grade_rule'] = $grade_rule;
		$this->data['json'] = json_encode($json);
		$this->load->view('faculty/json', $this->data);
	}

	private function _adjectival_grade($grade, $equal, $remarks, $input)
	{
		if (empty($grade))
		{
			$_POST[$input] = '';
			$_POST[$input == 'Grade' ? 'StrGrade' : 'StrLab'] = '';
			$_POST['Remarks'] = '';
		}
		else
		{
			$_POST[$input] = nf($grade);
			$_POST[$input == 'Grade' ? 'StrGrade' : 'StrLab'] = $equal;
			$_POST['Remarks'] = $remarks;
		}
	}

	public function _20162017_grade_rule($str, $input)
	{
		$grade = $this->input->post($input, TRUE);

		if (empty($grade))
		{
			self::_adjectival_grade($grade, nf($grade), '', $input);
			return TRUE;
		}

		$grade = (float) $grade;

		$special_grades = $this->special_grades;

		// if (in_array((int) $grade, array_keys($special_grades)))
		if (in_array($grade, array_keys($special_grades)))
		{
			self::_adjectival_grade($grade, $special_grades[$grade][1], $special_grades[$grade][0], $input);
			return TRUE;
		}

		$remarks = '';
		if (in_between($grade, 1, 3))
		{
			$remarks = 'PASSED';
			$grade = is_valid_grade($grade);
			if ($grade != FALSE)
			{
				self::_adjectival_grade($grade, nf($grade), $remarks, $input);
				return TRUE;
			}
		}

		if (in_between($grade, 75, 100) || in_between($grade, 8, 69.49))
		{
			$remarks = 'PASSED';
			if (in_between($grade, 97, 100)) 		self::_adjectival_grade($grade, '1.00', $remarks, $input);
			elseif (in_between($grade, 94, 96.99)) 	self::_adjectival_grade($grade, '1.25', $remarks, $input);
			elseif (in_between($grade, 91, 93.99)) 	self::_adjectival_grade($grade, '1.50', $remarks, $input);
			elseif (in_between($grade, 88, 90.99)) 	self::_adjectival_grade($grade, '1.75', $remarks, $input);
			elseif (in_between($grade, 85, 87.99)) 	self::_adjectival_grade($grade, '2.00', $remarks, $input);
			elseif (in_between($grade, 82, 84.99)) 	self::_adjectival_grade($grade, '2.25', $remarks, $input);
			elseif (in_between($grade, 79, 81.99)) 	self::_adjectival_grade($grade, '2.50', $remarks, $input);
			elseif (in_between($grade, 76, 78.99)) 	self::_adjectival_grade($grade, '2.75', $remarks, $input);
			elseif (in_between($grade, 75, 75.99)) 	self::_adjectival_grade($grade, '3.00', $remarks, $input);
			else
				self::_adjectival_grade($grade, '5.00', 'FAILED', $input);

			return TRUE;
		}

		$this->form_validation->set_message('_20162017_grade_rule', "THE GRADE YOU ENTERED $grade IS NOT ALLOWED");
		$GLOBALS['flagLab']=TRUE;
		$GLOBALS['flagGrade']=FALSE;
		return FALSE;
	}


	public function _lecture_rule()
	{
		$output = 0.00;
		$grade = $this->input->post('Grade', TRUE);

		if (empty($grade))
		{
			$_POST['Grade'] = '';
			$_POST['StrGrade'] = '';
			$_POST['Remarks'] = '';
			return TRUE;
		}

		// if the program reached here meaning the schedule is valid
		// now proceed to convertion
		$grade = (float) $grade;
		$valid_grades = array(5, 6, 7);

		if ( ! (($grade >= 1 && $grade <= 3.00) || ($grade >= 40 && $grade <= 100) || in_array($grade, $valid_grades)))
		{
			$this->form_validation->set_message('_lecture_rule', "INVALID GRADE ENTERED. \rRefer to the MANUAL available at the TOP portion of the page.");
			$GLOBALS['flagLab']=TRUE;
			$GLOBALS['flagGrade']=FALSE;
			return FALSE;
		}


		$special_grade = array(6 => array('INCOMPLETE', 'INC'), 7 => array('UNOFFICIALLY DROPPED', 'UD'), 5 => array('FAILED','5.00'));
		if (array_key_exists((int) $grade, $special_grade))
		{
			$_POST['Grade'] = nf($grade);
			$_POST['StrGrade'] = $special_grade[$grade][1];
			$_POST['Remarks'] = $special_grade[$grade][0];
			return TRUE;
		}

		$isPercentSys = FALSE;

		// Determine the grading system whether percentage/point system
		if ($grade >= 40)
		{
			// Grades greather than or equal 95 will be have an equivalent of 1.00
			if ($grade >= 95)
			{
				$_POST['Grade'] = nf(1);
				$_POST['StrGrade'] = $_POST['Grade'];
				$_POST['Remarks'] = 'PASSED';
				return TRUE;
			}

			if ($grade > 39 && $grade < 75)
			{
				$_POST['Grade'] = nf(5);
				$_POST['StrGrade'] = $_POST['Grade'];
				$_POST['Remarks'] = 'FAILED';
				return TRUE;
			}

			// USE THE PERCENTAGE WITH ROUND UP/DOWN FOR DECIMAL GRADE

			// since the grading system of umak has incrementation of 0.10 and grade greather than or equal 95 is equivalent to 1.00
			// we are going to substract the grade to 95 and then multiply it to 0.10 plus 1.00 to get the final output
			$output = round($grade, 0, PHP_ROUND_HALF_DOWN);
			$output = abs(95 - $output);
			$output = (float) ($output * 0.10) + 1;
			$isPercentSys = TRUE;
		}

		// point system apply the round up/down for decimal grade
		if ( ! $isPercentSys)
		$output = round($grade, 1, PHP_ROUND_HALF_DOWN);

		$_POST['StrGrade'] = nf(round($output, 1, PHP_ROUND_HALF_DOWN));
		$_POST['Grade'] = $_POST['StrGrade'];
		$_POST['Remarks'] = 'PASSED';

		return TRUE;
	}

	public function _lab_rule()
	{
		$output = 0.00;

		$labgrade = $this->input->post('LabGrade', TRUE);

		if (empty($labgrade))
		{
			$GLOBALS['flagGrade']=TRUE;
			$GLOBALS['flagLab']=FALSE;
			$_POST['LabGrade'] = '';
			$_POST['StrLab'] = '';
			$_POST['Remarks'] = '';
			return TRUE;
		}

		// if the program reached here meaning the schedule is valid
		// now proceed to convertion
		// $grade = (float) $grade;
		$labgrade = (float) $labgrade;
		$valid_grades = array(5, 6, 7);


		if ( ! (($labgrade >= 1 && $labgrade <= 3.00) || ($labgrade >= 40 && $labgrade <= 100) || in_array($labgrade, $valid_grades)))
		{
			$this->form_validation->set_message('_lab_rule', "INVALID GRADE ENTERED. \rRefer to the MANUAL available at the TOP portion of the page.");
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


	public function _masteral_rule()
	{
		$output = 0.00;
		$grade = $this->input->post('Grade', TRUE);

		// if the program reached here meaning the schedule is valid
		// now proceed to convertion
		$grade = (float) $grade;
		$valid_grades = array(5, 6, 7);

		if ( ! (($grade >= 1 && $grade <= 3.00) || in_array($grade, $valid_grades)))
		{
			$this->form_validation->set_message('_masteral_rule', "INVALID GRADE ENTERED. \rRefer to the MANUAL available at the TOP portion of the page.");
			$GLOBALS['flagLab']=TRUE;
			$GLOBALS['flagGrade']=FALSE;
			return FALSE;
		}


		$special_grade = array(6 => array('INCOMPLETE', 'INC'), 7 => array('UNOFFICIALLY DROPPED', 'UD'), 5 => array('FAILED','5.00'));
		if (array_key_exists((int) $grade, $special_grade))
		{
			$_POST['Grade'] = nf($grade);
			$_POST['StrGrade'] = $special_grade[$grade][1];
			$_POST['Remarks'] = $special_grade[$grade][0];
			return TRUE;
		}

		$_POST['StrGrade'] = nf($grade);
		$_POST['Grade'] =$_POST['StrGrade'];
		$_POST['Remarks'] = 'PASSED';

		return TRUE;
	}


	public function _character_rule()
	{
		$output = 0.00;
		$grade = strtoupper($this->input->post('Grade', TRUE));

		// if the program reached here meaning the schedule is valid
		// now proceed to convertion
		$grade = strtoupper($grade);
		$valid_grades = array(5, 6, 7);

		$special_grade = array('6.00' => array('INCOMPLETE', 'INC'), '7.00' => array('UNOFFICIALLY DROPPED', 'UD'), '6' => array('INCOMPLETE', 'INC'), '7' => array('UNOFFICIALLY DROPPED', 'UD'), 'P' => array('PASSED', 'P'), 'F' => array('FAILED', 'F'));
		if (array_key_exists($grade, $special_grade))
		{
			$_POST['Grade'] = $grade;
			$_POST['StrGrade'] = $special_grade[$grade][1];
			$_POST['Remarks'] = $special_grade[$grade][0];

			return TRUE;
		}
		$_POST['Grade'] = nf(7);
		$_POST['StrGrade'] = 'UD';
		$_POST['Remarks'] = 'UNOFFICIALLY DROPPED';
		$this->form_validation->set_message('_character_rule', "INVALID GRADE ENTERED. \rRefer to the MANUAL available at the TOP portion of the page.");
		return FALSE;
	}


	public function _hsu_rule()
	{
		$output = 0.00;
		$grade = $this->input->post('Grade', TRUE);

		// if the program reached here meaning the schedule is valid
		// now proceed to convertion
		$grade = floor($grade);

		if ( ! ($grade >= 40 && $grade <= 100))
		{
			$this->form_validation->set_message('_hsu_rule', "INVALID GRADE ENTERED. \rRefer to the MANUAL available at the TOP portion of the page.");
			$GLOBALS['flagLab']=TRUE;
			$GLOBALS['flagGrade']=FALSE;
			return FALSE;
		}

		$output = nf($grade);

		$_POST['StrGrade'] = nf($output);
		$_POST['Grade'] = $_POST['StrGrade'];
		$_POST['Remarks'] = $_POST['StrGrade'] >= 75 ? 'PASSED' : 'FAILED';

		return TRUE;
	}

	private function _is_unique()
	{
		$sched_id = $this->input->post('thesis_sched_id', TRUE);
		$studno = $this->input->post('thesis_studno', TRUE);
		$thesis = $this->thesis_m->get_by(array(
			'sched_id' => $sched_id,
			'studno' => $studno), TRUE);

		return count($thesis) ? $thesis->id : NULL;
	}


	public function finish_later($sched_id)
	{
		$sched_id = intval($sched_id);

		$date_now = date('Y-m-d');
		$grad_date_start = '2017-03-10';
		$grad_date_end = '2017-03-27';

		if ( ! $sched_id)
		parent::load_error('Access denied! Unathorized access is not allowed');

		$trans = $this->studgrade_trans_m->get_by(array('tbleogtrans.sched_id' => $sched_id), TRUE);

		if (empty($trans))
		parent::load_error('Access denied! Unathorized access is not allowed');

		// if ($trans->is_graded == 1  && ! ($date_now >= $grad_date_start && $date_now <= $grad_date_end))
		// parent::load_error('You have already finalized the student grades. If you want to update the grades, file an AMMENDMENT OF GRADES at the Office of the University Registrar.');


		$teacher_program = $this->schedule_m->InTeachersProgram($sched_id);
		if ($teacher_program['error'] == TRUE)
		parent::load_error($teacher_program['message']);

		$schedule = $teacher_program['schedule'];

		$message  = "<strong>Draft Saved!</strong> You may edit this data({$schedule->CourseCode} - {$schedule->year_section}) anytime. <br /><span class='label label-danger'>NOTE: THESE DATA IS NOT YET OFFICIAL.</span>";
		// $message .= "<br>Please be reminded that encoding period is from " . date('M j, Y', strtotime($this->session->userdata('date_start'))) . ' to ' . date('M j, Y', strtotime($this->session->userdata('date_end'))) . ' (' . date('h:i:s a', strtotime($this->session->userdata('time_start'))) . '-' . date('h:i:s a', strtotime($this->session->userdata('time_end'))) . ")</strong>.";

		$this->session->set_flashdata('success', $message );
		$this->session->set_flashdata('sched_id', $schedule->sched_id);
		$this->user_m->logs('Draft Saved Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);

		redirect(base_url('faculty'));
	}

	public function confirm_grades($sched_id)
	{
		// $this->output->enable_profiler(TRUE);
		$sched_id = intval($sched_id);

		if ( ! $sched_id)
		parent::load_error('Access denied! Unathorized access is not allowed');

		$trans = $this->studgrade_trans_m->get_by(array('tblsched.sched_id' => $sched_id), TRUE);

		if ( ! count($trans))
		parent::load_error('Operation is not allowed. System detected that you have not graded atleast one of your student(s).');

		$trans_id = $trans->eog_trans_id;


		$teacher_program = $this->schedule_m->InTeachersProgram($sched_id);
		if ($teacher_program['error'] == TRUE)
		parent::load_error($teacher_program['message']);

		// count blank grades
		$this->db->where('Remarks', '');
		$blank_grades = $this->studgrade_m->count(array('nametable' => $teacher_program['schedule']->cfn));

		if ($this->allow_blank_grades == FALSE)
		{
			if ($teacher_program['schedule']->leclab != 6)
			{
				if ($blank_grades)
				{
					$this->user_m->logs('Failed to finalized Due to BLANK GRADES CFN - ' . $teacher_program['schedule']->cfn . ' Sched Id - ' . $sched_id);
					$url = "gradebook/{$sched_id}/gradesheet";
					parent::load_error('Operation Failed. Gradesheet cannot be finalized due to blank grade(s)', $url);
				}
			}
		}

		$stud_grade_data = array(
				'is_graded' => 1,
				'submitted_at' => date('Y-m-d H:i:s'),
				'nametable' => $teacher_program['schedule']->cfn,
			);

		$eog_late_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate')));
		if ( ! empty($eog_late_date))
		{
			$current_date = date('Y-m-d');
			if ( $current_date >= $eog_late_date)
			{
				$stud_grade_data['is_late'] = 1;
				$stud_grade_data['late_encoded_at'] = date('Y-m-d H:i:s');
			}
		}

		// save transaction logs
		$this->studgrade_trans_m->save($stud_grade_data, $trans_id);

		// count UD's
		$uds = $this->studgrade_m->count(array(
			'nametable' => $teacher_program['schedule']->cfn,
			'Remarks' => 'UNOFFICIALLY DROPPED'
			));

		// foreach ($this->session->userdata('teach_load') as $load)
		// {
		// 	if ($load->sched_id == $sched_id)
		// 	{
		// 		$load->uds = $uds;
		// 		$load->is_graded = 1;
		// 		$load->updated_at = date('Y-m-d H:i:s');
		// 		break;
		// 	}
		// }

		$pe1 = $this->studgrade_m->is_pe1_subject($teacher_program['schedule']->cfn);
		$pe3 = $this->studgrade_m->is_pe3_subject($teacher_program['schedule']->cfn);
		if ($pe1 || $pe3)
		{
			$cfn1 = $teacher_program['schedule']->cfn;
			$cfn2 = 'P' . substr($cfn1, 1, 7);
			$this->studgrade_m->save_pe_non_board($cfn1, $cfn2);
		}

		$schedule = $teacher_program['schedule'];

		$message  = 'You have successfully saved the student grades: ';
		$message .= '<p>Course Code: ' . $schedule->CourseCode;
		$message .= '<br>Description: ' . $schedule->CourseDesc;
		$message .= '<br>Section: ' . $schedule->year_section;
		$message .= anchor(base_url("gradebook/{$sched_id}/print_gradesheet"), '<strong>CLICK HERE TO PRINT YOUR DRAFT COPY</strong>', array('class' => 'btn btn-primary pull-right', 'target' => '_blank'));
		$message .= '</p>';

		$this->session->set_flashdata('success', $message );
		$this->session->set_flashdata('sched_id', $schedule->sched_id);
		$this->user_m->logs('Save Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);

		redirect(base_url('faculty'));
	}

	public function confirm_graduate_grades($sched_id)
	{
		$this->output->enable_profiler(TRUE);
		$sched_id = intval($sched_id);

		if ( ! $sched_id)
		parent::load_error('Access denied! Unathorized access is not allowed');

		$trans = $this->studgrade_trans_m->get_by(array('tblsched.sched_id' => $sched_id), TRUE);

		if ( ! count($trans))
		parent::load_error('Operation is not allowed. System detected that you have not graded atleast one of your student(s).');

		$trans_id = $trans->eog_trans_id;


		$teacher_program = $this->schedule_m->InTeachersProgram($sched_id);
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

		// save transaction logs
		$this->studgrade_trans_m->save($stud_grad_grade_data, $trans_id);

		// count UD's
		$uds = $this->studgrade_m->count(array(
			'nametable' => $teacher_program['schedule']->cfn,
			'Remarks' => 'UNOFFICIALLY DROPPED'
			));

		// foreach ($this->session->userdata('teach_load') as $load)
		// {
		// 	if ($load->sched_id == $sched_id)
		// 	{
		// 		$load->uds = $uds;
		// 		$load->is_graded = 1;
		// 		$load->updated_at = date('Y-m-d H:i:s');
		// 		break;
		// 	}
		// }

		$pe1 = $this->studgrade_m->is_pe1_subject($teacher_program['schedule']->cfn);
		$pe3 = $this->studgrade_m->is_pe3_subject($teacher_program['schedule']->cfn);
		if ($pe1 || $pe3)
		{
			$cfn1 = $teacher_program['schedule']->cfn;
			$cfn2 = 'P' . substr($cfn1, 1, 7);
			$this->studgrade_m->save_pe_non_board($cfn1, $cfn2);
		}

		$schedule = $teacher_program['schedule'];

		$message  = 'You have successfully saved the student grades: ';
		$message .= '<p>Course Code: ' . $schedule->CourseCode;
		$message .= '<br>Description: ' . $schedule->CourseDesc;
		$message .= '<br>Section: ' . $schedule->year_section;
		$message .= anchor(base_url("gradebook/{$sched_id}/print_gradesheet"), '<strong>CLICK HERE TO PRINT YOUR DRAFT COPY</strong>', array('class' => 'btn btn-primary pull-right', 'target' => '_blank'));
		$message .= '</p>';

		$this->session->set_flashdata('success', $message );
		$this->session->set_flashdata('sched_id', $schedule->sched_id);
		$this->user_m->logs('Save Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);

		redirect(base_url('faculty'));
	}

	public function print_gradesheet($sched_id)
	{
		// $this>load->helper('text');

		$teacher_program = $this->schedule_m->InTeachersProgram($sched_id);

		if ($teacher_program['error'] == TRUE)
			parent::load_error($teacher_program['message']);

		$schedule = $teacher_program['schedule'];
		$trans = $this->studgrade_trans_m->get_by(array('tblsched.sched_id' => $sched_id), TRUE);

		if ($trans->is_graded == 0)
			parent::load_error('<i class="fa fa-exclamation-circle"></i> Page cannot be loaded. Please save and confirm the grades then try again.');

		$prof_print_date = date('Y-m-d H:i:s');
		// save prof date print
		$this->studgrade_trans_m->save(array(
				'is_prof_printed' => 1,
				'prof_date_print' => $prof_print_date,
			), $trans->eog_trans_id);

		$trans->is_prof_printed = 1;
		$trans->prof_print_date = $prof_print_date;

		$this->db->where_not_in('Remarks', array('LOA', 'WITHDRAW CREDENTIAL', 'HD'));
		$grades = $this->studgrade_m->get_by(array('nametable' => $schedule->cfn));

		// stats figures
		$this->data['report']['cnt_passed'] = $this->studgrade_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'PASSED'));
		$this->data['report']['cnt_od'] = $this->studgrade_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'OFFICIALLY DROPPED'));
		$this->data['report']['cnt_ud'] = $this->studgrade_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'UNOFFICIALLY DROPPED'));
		$this->data['report']['cnt_inc'] = $this->studgrade_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'INCOMPLETE'));
		$this->data['report']['cnt_failed'] = $this->studgrade_m->count(array('nametable' => $schedule->cfn, 'Remarks' => 'FAILED'));

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

		$this->load->view('faculty/print/print_gradesheet', $this->data);
		// $this->load->view('faculty/print/print_rgradesheet', $this->data);

		// log transaction
		$this->user_m->logs('Print Gradesheet CFN - ' . $schedule->cfn . ' Sched Id - ' . $sched_id);
	}


}

/* End of file gradebook.php */
/* Location: ./application/controllers/gradebook.php */
