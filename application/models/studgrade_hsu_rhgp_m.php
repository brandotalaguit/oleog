<?php
/**
* Filename: studgrade_hsu_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Studgrade_hsu_rhgp_m extends MY_Model
{
	public function __construct() 
	{
		$this->table_name = HSU_DB . ".student_grades_rhgp"; 
		parent::__construct($this->table_name);
    }

	protected $primary_key = "studgrade_id";
	protected $order_by = "StudNo, nametable";

	protected $protected_attribute = array('studgrade_id');

	public $rules = array(
		'nametable' => array('field' => 'nametable', 'label' => 'Course Filename', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
		'StudNo' => array('field' => 'StudNo', 'label' => 'Student No.', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
		// 'Grade' => array('field' => 'Grade', 'label' => 'Grade Input', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean'),
		// 'StrGrade' => array('field' => 'StrGrade', 'label' => 'Grade Output', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean'),
		// 'LabGrade' => array('field' => 'LabGrade', 'label' => 'Lab. Grade Input', 'rules' => 'trim|strtoupper|xss_clean'),
		// 'StrLab' => array('field' => 'StrLab', 'label' => 'Lab. Grade Output', 'rules' => 'trim|strtoupper|required|xss_clean'),
		// 'Remarks' => array('field' => 'Remarks', 'label' => 'Remarks', 'rules' => 'trim|strtoupper|required|min_length[2]|xss_clean'),
	);

	public $rules_grade_sys = array(
		'SchedId' => array('field' => 'SchedId', 'label' => 'Schedule Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
		'studgrade_id' => array('field' => 'studgrade_id', 'label' => 'Student Grade Id', 'rules' => 'intval|is_natural_no_zero|xss_clean|callback__rhgp_rule'),
		'Makadiyos_R1' => array('field' => 'Makadiyos_R1', 'label' => 'Makadiyos A Input', 'rules' => 'trim|strtoupper|required|xss_clean|strtoupper'),
		'Makadiyos_R2' => array('field' => 'Makadiyos_R2', 'label' => 'Makadiyos B Input', 'rules' => 'trim|strtoupper|required|xss_clean|strtoupper'),
		'Makatao_R1' => array('field' => 'Makatao_R1', 'label' => 'Makatao C Input', 'rules' => 'trim|strtoupper|required|xss_clean|strtoupper'),
		'Makatao_R2' => array('field' => 'Makatao_R2', 'label' => 'Makatao D Input', 'rules' => 'trim|strtoupper|required|xss_clean|strtoupper'),
		'Makakalikasan_R1' => array('field' => 'Makakalikasan_R1', 'label' => 'Makakalikasan E Input', 'rules' => 'trim|strtoupper|required|xss_clean|strtoupper'),
		'Makabansa_R1' => array('field' => 'Makabansa_R1', 'label' => 'Makabansa F Input', 'rules' => 'trim|strtoupper|required|xss_clean|strtoupper'),
		'Makabansa_R2' => array('field' => 'Makabansa_R2', 'label' => 'Makabansa G Input', 'rules' => 'trim|strtoupper|required|xss_clean|strtoupper'),
		'StudNo' => array('field' => 'StudNo', 'label' => 'Student No', 'rules' => 'trim|strtoupper|required|min_length[7]|xss_clean'),
	);

	public $rules_grade_lec = array(
		'LabGrade' => array('field' => 'LabGrade', 'label' => 'Lab. Grade Input', 'rules' => 'trim|strtoupper|required|min_length[1]|xss_clean|callback__lab_rule'),
	);

	public function autofill($sched_id)
	{
			$sql = "INSERT INTO " . $this->table_name . "(StudNo, nametable, Makadiyos_R1, Makadiyos_R2, Makatao_R1, Makatao_R2, Makakalikasan_R1,Makabansa_R1,Makabansa_R2, enabled, created_at, updated_at)
							SELECT a.student_id, a.CFN, 'AO','AO','AO','AO','AO','AO','AO', 1, NOW(), NOW()
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

}

/*Location: ./application/models/studgrade_hsu_m.php*/
