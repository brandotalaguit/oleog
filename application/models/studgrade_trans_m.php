<?php
/**
* Filename: studgrade_trans_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Studgrade_Trans_m extends MY_Model
{
	protected $table_name = "tbleogtrans";
	protected $primary_key = "eog_trans_id";
	protected $order_by = "eog_trans_id, tbleogtrans.sched_id";

	protected $protected_attribute = array('eog_trans_id', 'is_actived', 'deleted_at');

	public $rules = array(
		'sched_id' => array('field' => 'sched_id', 'label' => 'Schedule Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
		'is_graded' => array('field' => 'is_graded', 'label' => 'Finalize Button', 'rules' => 'intval|xss_clean'),
	);


	public function get($id = NULL, $single = FALSE)
	{
		$select_arr = array(
			'tbleogtrans.*',
			'`tblsched`.`YearSectionId`',
			'`tblsched`.`faculty_id`',
			'`tblsched`.`subject_id`',
			'`tblsched`.`GradingId`',
			'`tblsched`.`year_section`',
			'`tblsched`.`SemId`',
			'`tblsched`.`SyId`',
			'`tblsched`.`faculty_name`',
			'`tblsched`.`class_size`',
			'`tblsched`.`CollegeId`',
			'`tblsched`.`ProgramId`',
			'`tblsched`.`MajorId`',
			'`tblsched`.`cfn`',
			'`tblcourse`.`leclab`',
			'`tblsched`.`is_actived`',
			'`tblcourse`.`CourseId`',
			'`tblcourse`.`CourseCode`',
			'`tblcourse`.`CourseDesc`',
			'`tblcourse`.`Units`',
		);

		$this->db->select($select_arr, FALSE);
		$this->db->join('tblsched', 'tblsched.sched_id = tbleogtrans.sched_id', 'LEFT');
		$this->db->join('tblcourse', 'tblcourse.CourseId = tblsched.subject_id', 'LEFT');
		
		return parent::get($id, $single);
	}

	public function save($post, $id = NULL)
	{
		if ( ! empty($post['nametable'])) 
		{
			$this->db->where('is_actived', 1);
			$this->db->where('nametable', $post['nametable']);
			$this->db->update('tblstudgrade', array('enabled' => 0));
			unset($post['nametable']);
		}
		
		return parent::save($post, $id);
	}

}

/*Location: ./application/models/studgrade_trans_m.php*/
