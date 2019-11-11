<?php
/**
* Filename: studgrade_trans_hsu_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Studgrade_Trans_hsu_m extends MY_Model
{
	public function __construct() 
	{
		$this->table_name = HSU_DB . ".tbleogtrans"; 
		parent::__construct($this->table_name);
    }

	protected $primary_key = "eog_trans_id";
	protected $order_by = "eog_trans_id";

	protected $protected_attribute = array('eog_trans_id', 'is_actived', 'deleted_at');

	public $rules = array(
		'sched_id' => array('field' => 'sched_id', 'label' => 'Schedule Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
		'is_graded' => array('field' => 'is_graded', 'label' => 'Finalize Button', 'rules' => 'intval|xss_clean'),
	);


	public function get($id = NULL, $single = FALSE)
	{
		$select_arr = array(
			$this->table_name . '.*',
			'prof_id as faculty_id',
			HSU_DB . '.schedules.SemId',
			HSU_DB . '.schedules.SyId',
			HSU_DB . '.schedules.enrollees',
			'"HSU" as CollegeCode',
			'"HSU" as CollegeDesc',
			HSU_DB . '.schedules.CFN as cfn',
			HSU_DB . '.schedules.leclab',
			HSU_DB . '.schedules.is_actived',
			'subcode as CourseCode',
			'subdes as CourseDesc',
			'units as Units',
			'section as year_section',
			'CONCAT(Lastname, ",", Firstname, " ", LEFT(Middlename,1)) as faculty_name',
		);

		$this->db->select($select_arr, FALSE);
		$this->db->join(HSU_DB . '.schedules', HSU_DB . '.schedules.sched_id = ' . $this->table_name . '.sched_id', 'LEFT');
		$this->db->join('tblfacultydisplay', 'tblfacultydisplay.faculty_id = ' . HSU_DB . '.schedules.prof_id', 'LEFT');
		
		return parent::get($id, $single);
	}

	public function save($post, $id = NULL)
	{
		if ( ! empty($post['nametable'])) 
		{
			$this->db->where('is_actived', 1);
			$this->db->where('nametable', $post['nametable']);
			$this->db->update(HSU_DB . '.student_grades', array('enabled' => 0));
			unset($post['nametable']);
		}
		
		return parent::save($post, $id);
	}

}

/*Location: ./application/models/studgrade_trans_hsu_m.php*/
