<?php
/**
* Filename: schedule_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Schedule_M extends MY_Model
{
	protected $table_name = "tblsched";
	protected $primary_key = "sched_id";
	protected $order_by = "SyId, SemId, sched_id";

	protected $protected_attribute = array('sched_id');

	public $rules = array(
		'cfn' => array('field' => 'cfn', 'label' => 'Course Filename', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
	);

	public $rules_admin = array(
		'LastName' => array('field' => 'LastName', 'label' => 'LastName', 'rules' => 'trim|required|callback__unique_name|xss_clean'),
		'FirstName' => array('field' => 'FirstName', 'label' => 'FirstName', 'rules' => 'trim|required|xss_clean'),
		'MiddleName' => array('field' => 'MiddleName', 'label' => 'MiddleName', 'rules' => 'trim|required|xss_clean'),
		'Birthday' => array('field' => 'Birthday', 'label' => 'Birthday', 'rules' => 'trim|required|date|xss_clean'),
		'Username' => array('field' => 'Username', 'label' => 'Username', 'rules' => 'trim|required|max_length[20]|xss_clean'),
		'Password' => array('field' => 'Password', 'label' => 'Password', 'rules' => 'trim|matches[ConfirmPassword]'),
		'ConfirmPassword' => array('field' => 'ConfirmPassword', 'label' => 'ConfirmPassword', 'rules' => 'trim|matches[Password]'),
		'EmailAddress' => array('field' => 'EmailAddress', 'label' => 'Email', 'rules' => 'trim|required|valid_email'),
		'AccountType' => array('field' => 'AccountType', 'label' => 'Account Type', 'rules' => 'trim|required|xss_clean'),
	);



	public function get($id = NULL, $single = FALSE)
	{

		$select_arr = array(
				'`tblsched`.`sched_id`',
				'`tblsched`.`YearSectionId`',
				'`tblsched`.`faculty_id`',
				'`tblsched`.`subject_id`',
				'`tblsched`.`year_section`',
				'`tblsched`.`SemId`',
				'`tblsem`.`SemCode`',
				'`tblsem`.`SemDesc`',
				'`tblsched`.`SyId`',
				'`tblsy`.`SyCode`',
				'`tblsy`.`SyDesc`',
				'`tblsched`.`cfn`',
				'`tblcourse`.`leclab`',
				'`tblsched`.`is_actived`',
				'`tblcourse`.`CourseId`',
				'`tblcourse`.`CourseCode`',
				'`tblcourse`.`CourseDesc`',
				'`tblcourse`.`Units`',
				'`tbleogtrans`.`is_graded`',
				'`tbleogtrans`.`IsGradSection`',
				'`tbleogtrans`.`is_printed`',
				'tblcourse.EquivalentCourse',
				'tblyrsec.Year',
				'tblyrsec.Section',
				'tblcollege.CollegeCode',
				'tblcollege.CollegeDesc',
				'tbldean.Prefix',
				'tbldean.Lname',
				'tbldean.Fname',
				'tbldean.Mname',
				'tbldean.Suffix',
				'tblfacultydisplay.Title',
				'tblfacultydisplay.Lastname',
				'tblfacultydisplay.Firstname',
				'tblfacultydisplay.Middlename',
				'tblsched.CollegeId',
				'tblsched.ProgramId',
				'tblsched.MajorId',
			);

		$this->db->select($select_arr, FALSE);
		$this->db->join('tblcourse', 'tblcourse.CourseId = tblsched.subject_id', 'LEFT');
		$this->db->join('tblyrsec', 'tblsched.YearSectionId = tblyrsec.Id', 'LEFT');
		$this->db->join('tbleogtrans', 'tblsched.sched_id = tbleogtrans.sched_id AND tbleogtrans.is_actived = 1', 'LEFT');
		$this->db->join('tblfacultydisplay', 'tblfacultydisplay.faculty_id = tblsched.faculty_id', 'LEFT');
		$this->db->join('tblsem', 'tblsem.SemId = tblsched.SemId', 'LEFT');
		$this->db->join('tblsy', 'tblsy.SyId = tblsched.SyId', 'LEFT');
		$this->db->join('tblcollege', 'tblcollege.CollegeId = tblsched.CollegeId', 'LEFT');
		$this->db->join('tbldean', 'tbldean.college_id = tblsched.CollegeId', 'LEFT');

		return parent::get($id, $single);
	}

	public function get_teacher_program($faculty_id, $sy_id, $sem_id)
	{
		// Teaching Load
		$column = array(
				'tbleogtrans.sched_id as eogSchedId',
				'tbleogtrans.updated_at',
				'tbleogtrans.submitted_at',
				'(
					SELECT count(*)
						FROM tblstudgrade
						WHERE
							nametable = tblsched.cfn AND
							tblstudgrade.remarks IN("UNOFFICIALLY DROPPED", "") AND
							tblstudgrade.is_actived = 1
				) as uds',
			);

		$this->db->select($column, FALSE);
		$this->db->order_by('Year, Section, CourseCode');

		// if ($faculty_id == 516)
		// {
		// 	// $this->db->where_in('tblsched.cfn', array('A1167280','A1167130','A1166387'));
		// 	return parent::get_by(array(
		// 		'tblsched.sched_id' => 13265,
		// 		'tblsched.faculty_id' => $faculty_id
		// 	));
		// }

		// if ($faculty_id == 1042)
		// {
		// 	return parent::get_by(array(
		// 		'tblsched.sched_id' => 13342,
		// 		'tblsched.faculty_id' => $faculty_id
		// 	));
		// }

		// if ($faculty_id == 1033)
		// {
		// 	return parent::get_by(array(
		// 		'tblsched.sched_id' => 14902,
		// 		'tblsched.faculty_id' => $faculty_id
		// 	));
		// }

		return parent::get_by(array(
			'tblsched.SyId' => $sy_id,
			'tblsched.SemId' => $sem_id,
			'tblsched.faculty_id' => $faculty_id
		));

	}

	public function InTeachersProgram($sched_id)
	{
		$data['error'] = FALSE;
		$sched_cnt = parent::count(array('tblsched.sched_id' => $sched_id));

		if ( ! $sched_cnt)
		{
			$data['message'] = '<i class="fa fa-exclamation-circle"></i> Class schedule does not exist!';
			$data['error'] = TRUE;

			return $data;
		}

		$faculty_id = $this->session->userdata('faculty_id');
		$column = array(
				'tbleogtrans.submitted_at',
				'tbleogtrans.updated_at',
				'(
					SELECT count(*)
						FROM tblstudgrade
						WHERE
							nametable = tblsched.cfn AND
							tblstudgrade.remarks IN("UNOFFICIALLY DROPPED", "") AND
							tblstudgrade.is_actived = 1
				) as uds',
			);

		$this->db->select($column, FALSE);
		$schedule = parent::get_by(array('tblsched.sched_id' => $sched_id, 'tblsched.faculty_id' => $faculty_id), TRUE);
		if ( ! count($schedule))
		{
			$data['message'] = '<i class="fa fa-exclamation-circle"></i> Unathorized access denied. This class schedule does not assigned to your teacher&#39;s program.';
			$data['error'] = TRUE;

			return $data;
		}

		$data['schedule'] = $schedule;
		return $data;
	}

	public function in_tp($sched_id)
	{
		// $sched_id = intval($sched_id);
		// $in_tp = FALSE;

		// foreach ($this->session->userdata('teach_load') as $load)
		// {
		// 	if ($load->sched_id == $sched_id)
		// 	{
		// 		$in_tp = $load;
		// 		break;
		// 	}
		// }

		// return $in_tp;

		$this->db->where('tblsched.faculty_id', $this->session->userdata('faculty_id'));
		$data = self::get($sched_id, TRUE);
		return count($data) > 0 ? $data : FALSE;
	}

	public function in_tp2($sched_id)
	{
		$this->db->where('tblsched.faculty_id', $this->session->userdata('faculty_id'));
		$this->db->join('tblcourse', 'tblcourse.CourseId = subject_id','left');
		$this->db->select('tblcourse.leclab, sched_id, cfn');
		$data = parent::get($sched_id, TRUE);
		return count($data) > 0 ? $data : FALSE;
	}

	public function count2($sched_id)
	{
		$this->db->select('sched_id, leclab, cfn');
		$this->db->from($this->table_name);

		$this->db->where('sched_id', $sched_id);
		$this->db->where('faculty_id', $this->session->userdata('faculty_id'));
		$this->db->where('is_actived', 1);

		$result = $this->db->count_all_results();
		$this->db->close();

		return $result;
	}


}

/*Location: ./application/models/schedule_m.php*/
