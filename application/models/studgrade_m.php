<?php
/**
* Filename: studgrade_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class studgrade_m extends MY_Model
{
	protected $table_name = "tblstudgrade";
	protected $primary_key = "studgrade_id";
	protected $order_by = "StudNo, CourseId, Remarks";

	protected $protected_attribute = array('studgrade_id', 'RVal', 'RValLab');

	public $rules = array(
		'CourseId' => array('field' => 'CourseId', 'label' => 'Course Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
		'nametable' => array('field' => 'nametable', 'label' => 'Course Filename', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
		'StudNo' => array('field' => 'StudNo', 'label' => 'Student No.', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
		'Grade' => array('field' => 'Grade', 'label' => 'Grade Input', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean'),
		'StrGrade' => array('field' => 'StrGrade', 'label' => 'Grade Output', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean'),
		'LabGrade' => array('field' => 'LabGrade', 'label' => 'Lab. Grade Input', 'rules' => 'trim|strtoupper|xss_clean'),
		'StrLab' => array('field' => 'StrLab', 'label' => 'Lab. Grade Output', 'rules' => 'trim|strtoupper|xss_clean'),
		'Remarks' => array('field' => 'Remarks', 'label' => 'Remarks', 'rules' => 'trim|strtoupper|required|min_length[2]|xss_clean'),
	);

	public $rules_grade_sys = array(
		'SchedId' => array('field' => 'SchedId', 'label' => 'Schedule Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
		'studgrade_id' => array('field' => 'studgrade_id', 'label' => 'Student Grade Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
		'Grade' => array('field' => 'Grade', 'label' => 'Grade Input', 'rules' => 'trim|strtoupper|xss_clean'),
		'StrGrade' => array('field' => 'StrGrade', 'label' => 'Grade Output', 'rules' => 'trim|strtoupper|min_length[1]|xss_clean'),
		'StudNo' => array('field' => 'StudNo', 'label' => 'Student No', 'rules' => 'trim|strtoupper|required|min_length[7]|xss_clean'),
	);

	public $rules_grade_lec = array(
		'LabGrade' => array('field' => 'LabGrade', 'label' => 'Lab. Grade Input', 'rules' => 'trim|strtoupper|min_length[1]|xss_clean'),
	);

	public function autofill($sched_id)
	{
		$sem_id = $this->session->userdata('sem_id');
		$sy_id = $this->session->userdata('sy_id');

			$sql = "INSERT INTO tblstudgrade(StudNo, CourseId, nametable,
								StrGrade,
								Grade,
								StrLab,
								LabGrade,
								Remarks,
								enabled,
								created_at, updated_at)
							SELECT a.StudNo, subject_id, a.Cfn,
								CASE a.Status WHEN 'LOA' THEN 'LOA' WHEN 'HD' THEN 'HD' WHEN 'OD' THEN 'OD' WHEN 'WC' THEN 'WC' ELSE '' END,
								CASE a.Status WHEN 'LOA' THEN '0.00' WHEN 'HD' THEN '0.00' WHEN 'OD' THEN '0.00' WHEN 'WC' THEN '0.00' ELSE '' END,
								if(leclab = 1,
									CASE a.Status WHEN 'LOA' THEN 'LOA' WHEN 'HD' THEN 'HD' WHEN 'OD' THEN 'OD' WHEN 'WC' THEN 'WC' ELSE '' END,
									''),
								if(leclab = 1,
									CASE a.Status WHEN 'LOA' THEN '0.00' WHEN 'HD' THEN '0.00' WHEN 'OD' THEN '0.00' WHEN 'WC' THEN '0.00' ELSE '' END,
									''),
								CASE a.Status WHEN 'LOA' THEN 'LOA' WHEN 'HD' THEN 'HD' WHEN 'OD' THEN 'OFFICIALLY DROPPED' WHEN 'WC' THEN 'WITHDRAW CREDENTIAL' ELSE '' END,
								if(a.Status > '', 0, 1),
								NOW(), NOW()
							FROM tblstudentschedule as a
								LEFT JOIN tblenrollmenttrans as b ON a.StudNo = b.StudNo AND b.SemId  = ? AND b.SyId = ?
								LEFT JOIN tblsched as c ON a.Cfn = c.cfn
							WHERE a.StudNo NOT IN(SELECT StudNo FROM tblstudgrade WHERE nametable = c.cfn)
								AND (IsPrinted = 1 OR IsPaid = 1 OR IsPromissory = 1 OR IsScholar = 1 OR IsOnSite = 1)
								AND IsActive = 1
								AND sched_id = ? ";

			$this->db->query($sql, array($sem_id, $sy_id, $sched_id));

			return $this->db->affected_rows();
	}

	public function enable_default_grades($cfn)
	{
		if ( ! empty($cfn))
		{
			$this->db->where('nametable', $cfn);
			// $this->db->where('Remarks', 'UNOFFICIALLY DROPPED');
			$arr_remarks = ['UNOFFICIALLY DROPPED', ''];
			$this->db->where_in('Remarks', $arr_remarks);
			$this->db->update($this->table_name, array('enabled' => 1, 'updated_at' => date('Y-m-d H:i:s')));
			return $this->db->affected_rows();
		}

		return FALSE;
	}

	public function get($id = NULL, $single = FALSE)
	{
		$select_arr = array(
				'tblstudinfo.new_grade_sys',
				'tblstudinfo.Lname',
				'tblstudinfo.Fname',
				'LEFT(tblstudinfo.Mname,1) as Mname',
				'IsPrinted',
				'tblstudgrade.*',
			);

		$this->db->select($select_arr, FALSE);
		$this->db->join('tblstudinfo', 'tblstudinfo.StudNo = tblstudgrade.StudNo', 'LEFT');
		$this->db->join('tblsched', 'tblsched.cfn = tblstudgrade.nametable', 'LEFT');
		$this->db->join('tblenrollmenttrans', 'tblenrollmenttrans.StudNo = tblstudgrade.StudNo AND tblsched.SyId = tblenrollmenttrans.SyId AND tblsched.SemId = tblenrollmenttrans.SemId', 'LEFT');
		$this->db->order_by('tblsched.cfn, Lname, Fname, Mname');

		return parent::get($id, $single);
	}

	public function get_student_grade_system($id)
	{
		$this->db->select('new_grade_sys');
		$this->db->join('tblstudinfo', 'tblstudinfo.StudNo = tblstudgrade.StudNo', 'LEFT');
		$this->db->order_by('tblstudgrade.StudNo');
		return (bool) parent::get($id, TRUE)
				->new_grade_sys;
	}

	public function get_rules($leclab, $new_grade_sys, $input)
	{
		switch ($leclab)
		{
			case 3:
			case 6:
				return '|callback__masteral_rule|min_length[1]|less_than[8]';
				break;
			case 4:
				return '|callback__character_rule';
				break;
			default:
				return $new_grade_sys === TRUE ? "|callback__20162017_grade_rule[$input]|min_length[1]|less_than[101]" : '|callback__lecture_rule|min_length[1]|less_than[101]';
				break;
		}
	}

	public function get_none_board_program($cfn, $new_nametable)
	{
		if (self::is_pe1_subject($cfn))
		{
			$subject_id = 7;
		}
		elseif (self::is_pe3_subject($cfn))
		{
			$subject_id = 8;
		}
		else
		{
			return FALSE;
		}

		if (parent::count(array('nametable' => $new_nametable)))
		{
			return FALSE;
		}


		$this->db->select("$this->table_name.StudNo, Grade, StrGrade, Remarks, '$subject_id' as CourseId, '$new_nametable' as nametable, NOW() as created_at, NOW() as updated_at", FALSE);
		// $this->db->join('tblstudcurriculum', 'tblstudcurriculum.StudNo = ' . $this->table_name . '.StudNo', 'left');
		// $this->db->join('tblcurriculum', 'tblcurriculum.CurriculumId = tblstudcurriculum.CurriculumId', 'left');
		$this->db->join('tblstudinfo', 'tblstudinfo.StudNo = ' . $this->table_name . '.StudNo', 'left');
		// $hsu = "(SELECT DISTINCT stud_id FROM ".HSU_DB.".student_enrollments WHERE is_actived = 1) as K12";
		// $this->db->join($hsu, $this->table_name . '.StudNo = K12.stud_id', 'left');
		$this->db->where('nametable', $cfn);
		// $this->db->where('Remarks', 'PASSED');
		$this->db->where_in('Remarks', array('PASSED', 'INCOMPLETE', 'FAILED'));
		/*$this->db->where("SUBSTR(" . $this->table_name . ".StudNo, 2, 1) != '6'", NULL, FALSE); 	// transferee not included
		$this->db->where('IsBoardProgram', 0);														// non-board program
		$this->db->where('IsTransferee', 0);														// transferee not included
		$this->db->where('(IsAccelerated = 1 OR K12.stud_id IS NOT NULL)');
		$student_schedule = "NOT EXISTS (SELECT 1 FROM tblstudentschedule as stud
											LEFT JOIN tblsched ON stud.Cfn = tblsched.cfn
											WHERE StudNo = ". $this->table_name .".StudNo AND
											IsActive = 1 AND
											Status = '' AND
											subject_id = {$subject_id})";*/
		$student_schedule = "EXISTS (SELECT 1 FROM student_with_pe_subject as stud
											WHERE newstudid = ". $this->table_name .".StudNo)";
		$this->db->where($student_schedule, NULL, FALSE);
		return parent::get();

	}

	public function get_pe_subjects()
	{
		$pe1_pe2_ids = array(6, 2143, 20, 2470);
		$tp_subj_ids = get_column($this->session->userdata('teach_load'), 'subject_id');
		foreach ($pe1_pe2_ids as $row)
		{
			if (in_array($row, $tp_subj_ids)) return TRUE;
		}

		return FALSE;
	}

	public function cfn_in_subject_id($cfn, $array)
	{
		$this->db->where('cfn', $cfn);
		$this->db->select('subject_id');
		$sched = $this->db->get('tblsched')->row();
		$this->db->flush_cache();

		return in_array($sched->subject_id, $array);

	}

	// PE 1 Course/Subject Id
	public function is_pe1_subject($cfn)
	{
		return self::cfn_in_subject_id($cfn, array('6', '2143'));
	}

	// PE 3 Course/Subject Id
	public function is_pe3_subject($cfn)
	{
		return self::cfn_in_subject_id($cfn, array('20', '2470'));
	}

	public function get_curriculum_pe2_course_id($curriculum_id)
	{
		// PE 2 Course/Subject Id
		$this->db->where('CourseId', 7);
		$this->db->where('CurriculumId', $curriculum_id);
		return $this->db->get('tblcurriculumdetails')->row();
	}

	public function get_curriculum_pe4_course_id($curriculum_id)
	{
		// PE 4 Course/Subject Id
		$this->db->where('CourseId', 8);
		$this->db->where('CurriculumId', $curriculum_id);
		return $this->db->get('tblcurriculumdetails')->row();
	}

	public function save_pe_non_board($cfn, $new_nametable)
	{
		// Disable PE 2 and PE 4 auto grades
		return FALSE;

		$student_grade = self::get_none_board_program($cfn, $new_nametable);
		if ( ! empty($student_grade))
		{
			// dump($student_grade);
			foreach ($student_grade as $key => $value)
			{
				$data[] = (array) $value;
			}

			// grades
			if (!empty($data))
			{
			$this->db->insert_batch('tblstudgrade', $data);

			$sched = $this->schedule_m->get_by(array('cfn' => $cfn), TRUE);
			$sched_data = array('cfn' => $new_nametable,
				'subject_id' =>  $data[0]['CourseId'],
				'faculty_id' => $sched->faculty_id,
				'SyId' => $sched->SyId,
				'SemId' => $sched->SemId,
				'year_section' => $sched->year_section,
				'YearSectionId' => $sched->YearSectionId,
				'CollegeId' => $sched->CollegeId,
				'ProgramId' => $sched->ProgramId,
				'MajorId' => $sched->MajorId,
			);

			// schedule
			$sched_id = $this->schedule_m->save($sched_data);

			$is_late = 0;
			$late_date = '0000-00-00 00:00:00';
			$eog_late_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate')));
			if ( ! empty($eog_late_date))
			{
				if (date('Y-m-d') >= $eog_late_date)
				{
					$is_late = 1;
					$late_date = date('Y-m-d H:i:s');
				}
			}

			$now = date('Y-m-d H:i:s');
			$trans_data = array('sched_id' => $sched_id,
				'submitted_at' => $now,
				'is_graded' => 1,
				'is_late' => $is_late,
				'late_encoded_at' => $late_date,
			);

			// eog trans
			$this->studgrade_trans_m->save($trans_data);
			} // end save
		}
	}

}

/*Location: ./application/models/studgrade_m.php*/
