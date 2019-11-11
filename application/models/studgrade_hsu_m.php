<?php
/**
* Filename: studgrade_hsu_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Studgrade_hsu_m extends MY_Model
{
	public function __construct()
	{
		$this->table_name = HSU_DB . ".student_grades";
		parent::__construct($this->table_name);
    }

	protected $primary_key = "studgrade_id";
	protected $order_by = "StudNo, nametable, Remarks";

	protected $protected_attribute = array('studgrade_id', 'RVal', 'RValLab');

	public $rules = array(
		'nametable' => array('field' => 'nametable', 'label' => 'Course Filename', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
		'StudNo' => array('field' => 'StudNo', 'label' => 'Student No.', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
		'Grade' => array('field' => 'Grade', 'label' => 'Grade Input', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean'),
		'StrGrade' => array('field' => 'StrGrade', 'label' => 'Grade Output', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean'),
		'LabGrade' => array('field' => 'LabGrade', 'label' => 'Lab. Grade Input', 'rules' => 'trim|strtoupper|xss_clean'),
		'StrLab' => array('field' => 'StrLab', 'label' => 'Lab. Grade Output', 'rules' => 'trim|strtoupper|required|xss_clean'),
		'Remarks' => array('field' => 'Remarks', 'label' => 'Remarks', 'rules' => 'trim|strtoupper|required|min_length[2]|xss_clean'),
	);

	public $rules_grade_sys = array(
		'SchedId' => array('field' => 'SchedId', 'label' => 'Schedule Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
		'studgrade_id' => array('field' => 'studgrade_id', 'label' => 'Student Grade Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
		'Grade' => array('field' => 'Grade', 'label' => 'Grade Input', 'rules' => 'trim|strtoupper|required|xss_clean'),
		'StrGrade' => array('field' => 'StrGrade', 'label' => 'Grade Output', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean'),
		'StudNo' => array('field' => 'StudNo', 'label' => 'Student No', 'rules' => 'trim|strtoupper|required|min_length[7]|xss_clean'),
	);

	public $rules_grade_lec = array(
		'LabGrade' => array('field' => 'LabGrade', 'label' => 'Lab. Grade Input', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean|callback__lab_rule'),
	);

	public function autofill($sched_id)
	{
			$sql = "INSERT INTO " . $this->table_name . "(StudNo, nametable, StrGrade, Grade, StrLab, LabGrade, Remarks, enabled, created_at, updated_at)
							SELECT a.student_id, a.CFN, '', '',
								if(leclab = 1, '', ''), if(leclab = 1, '', ''), '', 1, NOW(), NOW()
							FROM " . HSU_DB . ".student_schedules as a
								LEFT JOIN " . HSU_DB . ".schedules as c ON a.CFN = c.CFN
							WHERE a.student_id NOT IN(SELECT StudNo FROM " . $this->table_name . " WHERE " . $this->table_name . ".nametable = c.CFN)
								AND a.is_actived = 1
								AND sched_id = ? ";

			$this->db->query($sql, array($sched_id));

			return $this->db->affected_rows();
	}

	public function get($id = NULL, $single = FALSE)
	{
		$select_arr = array(
				HSU_DB . '.examinees.family_name as Lname',
				HSU_DB . '.examinees.first_name as Fname',
				'LEFT(' . HSU_DB . '.examinees.middle_name,1) as Mname',
				$this->table_name . '.*',
			);

		$this->db->select($select_arr, FALSE);
		$this->db->join(HSU_DB . '.examinees', HSU_DB . '.examinees.stud_id = ' . $this->table_name . '.StudNo AND ' . HSU_DB . '.examinees.is_actived = 1', 'LEFT');
		$this->db->order_by('nametable, Lname, Fname, Mname');

		return parent::get($id, $single);
	}

	public function enable_default_grades($cfn)
	{
		if ( ! empty($cfn))
		{
			$this->db->where('nametable', $cfn);
			// $this->db->where('Remarks', 'UNOFFICIALLY DROPPED');
			// $arr_remarks = ['0.00', '0', ''];
			// $this->db->where('StrGrade', '');
			$this->db->where('remarks', '');
			$this->db->update($this->table_name, array('enabled' => 1, 'updated_at' => date('Y-m-d H:i:s')));
			return $this->db->affected_rows();
		}

		return FALSE;
	}

}

/*Location: ./application/models/studgrade_hsu_m.php*/
