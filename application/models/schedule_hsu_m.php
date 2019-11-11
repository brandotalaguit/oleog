<?php
/**
* Filename: schedule_hsu_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Schedule_hsu_M extends MY_Model
{

	public function __construct()
	{
		$this->table_name = HSU_DB . ".schedules";
		parent::__construct($this->table_name);
    }

	protected $primary_key = "sched_id";
	protected $order_by = "SyId, SemId, sched_id";

	// protected $protected_attribute = array('sched_id');

	public $rules = array(
		'CFN' => array('field' => 'CFN', 'label' => 'Course Filename', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
	);


	public function get($id = NULL, $single = FALSE)
	{

		$select_arr = array(
				$this->table_name . '.sched_id',
				$this->table_name . '.prof_id as faculty_id',
				$this->table_name . '.SemId',
				'tblsem.SemCode',
				'tblsem.SemDesc',
				$this->table_name . '.SyId',
				'tblsy.SyCode',
				'tblsy.SyDesc',
				$this->table_name . '.CFN as cfn',
				$this->table_name . '.leclab',
				$this->table_name . '.is_actived',
				"REPLACE(" . $this->table_name . ".subcode, '\'', '') as CourseCode",
				"REPLACE(" . $this->table_name . ".subdes, '\'', '') as CourseDesc",
				$this->table_name . '.units as Units',
				$this->table_name . '.section as year_section',
				"(SELECT count(*) FROM ".HSU_DB .".student_grades
				 	WHERE nametable = ". HSU_DB .".schedules.nametable
				 	AND ".HSU_DB .".student_grades.remarks = ''
				 	AND ".HSU_DB .".student_grades.is_actived = 1
				 ) as uds",
				'submitted_at',
				'TRANS.is_graded',
				'"HSU" as CollegeCode',
				'"HSU" as CollegeDesc',
				'SYSEM.principal as principal',
				'SYSEM.dean as dean',
				'tblfacultydisplay.Title',
				'tblfacultydisplay.Lastname',
				'tblfacultydisplay.Firstname',
				'tblfacultydisplay.Middlename',
			);

		$this->db->select($select_arr, FALSE);
		$this->db->join(HSU_DB . '.tbleogtrans as TRANS', $this->table_name . '.sched_id = TRANS.sched_id AND TRANS.is_actived = 1', 'LEFT');
		$this->db->join('tblfacultydisplay', 'tblfacultydisplay.faculty_id = ' . $this->table_name . '.prof_id', 'LEFT');
		$this->db->join('tblsem', 'tblsem.SemId = ' . $this->table_name . '.SemId', 'LEFT');
		$this->db->join('tblsy', 'tblsy.SyId = ' . $this->table_name . '.SyId', 'LEFT');
		$this->db->join(HSU_DB . '.tblsysem as SYSEM', 'SYSEM.SyId = ' . $this->table_name . '.SyId AND SYSEM.SemId = ' . $this->table_name . '.SemId', 'LEFT');

		return parent::get($id, $single);
	}

	public function InTeachersProgram($sched_id)
	{
		$data['error'] = FALSE;
		$sched_cnt = parent::count(array($this->table_name . '.sched_id' => $sched_id));

		if ( ! $sched_cnt)
		{
			$data['message'] = '<i class="fa fa-exclamation-circle"></i> Class schedule does not exist!';
			$data['error'] = TRUE;

			return $data;
		}

		$faculty_id = $this->session->userdata('faculty_id');
		$schedule = parent::get_by(array($this->table_name . '.sched_id' => $sched_id, $this->table_name . '.prof_id' => $faculty_id), TRUE);
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
		$sched_id = intval($sched_id);
		$in_tp = FALSE;

		foreach ($this->session->userdata('teach_load_hsu') as $load)
		{
			if ($load->sched_id == $sched_id)
			{
				$in_tp = $load;
				break;
			}
		}

		return $in_tp;
	}


}

/*Location: ./application/models/schedule_hsu_m.php*/
