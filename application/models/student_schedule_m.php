<?php
/**
* Filename: student_schedule_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Student_schedule_M extends MY_Model
{
	protected $table_name = "tblstudentschedule";
	protected $primary_key = "TransId";
	protected $order_by = "TransId, SyId, SemId, StudNo";

  protected $soft_delete = FALSE;

  protected $protected_attribute = array(
    'TransId',
    'SyId',
    'SemId',
    'StudNo',
    'Cfn',
    'IsActive',
    'Status'
  );

	public $rules = array(
		'TransId' => array('field' => 'TransId', 'label' => 'Transaction Id', 'rules' => 'intval|required|xss_clean'),
		'SyId' => array('field' => 'SyId', 'label' => 'School Yr Id', 'rules' => 'intval|required|xss_clean'),
		'SemId' => array('field' => 'SemId', 'label' => 'Semester Id', 'rules' => 'intval|required|xss_clean'),
		'StudNo' => array('field' => 'StudNo', 'label' => 'Student No.', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
		'Cfn' => array('field' => 'Cfn', 'label' => 'Course Filename', 'rules' => 'trim|strtoupper|required|min_length[8]|xss_clean'),
		'IsActive' => array('field' => 'IsActive', 'label' => 'Is Active', 'rules' => 'intval|required|xss_clean'),
		'Status' => array('field' => 'Status', 'label' => 'Status', 'rules' => 'trim|strtoupper|xss_clean'),
	);


	public function get($id = NULL, $single = FALSE)
	{
		$select_arr = array(
				'tblstudentschedule.StudNo',
				'tblstudinfo.Lname',
				'tblstudinfo.Fname',
				'tblstudinfo.Mname',
				'Status',
				'if(Status != "", "0.00", if(tblsched.leclab = 5, "70.00", "7.00")) as Grade',
				'if(Status != "", Status, if(tblsched.leclab = 5, "70.00", "UD")) as StrGrade',
				'if(Status != "", "0.00", if(tblsched.leclab = 1, "7.00", "0.00")) as LabGrade',
				'if(Status != "", Status, if(tblsched.leclab = 1, "UD", "")) as StrLab',
				'if(Status IS NULL or Status = "", "UNOFFICIALLY DROPPED", if(Status = "OD", "OFFICIALLY DROPPED", Status)) as Remarks',
				'"1" as IsAllowed',
			);

    $this->db->where('IsActive', 1);
    $this->db->where_not_in('Status', array('WC','LOA'));

    $this->db->select($select_arr, FALSE);
		$this->db->join('tblstudinfo', 'tblstudinfo.StudNo = tblstudentschedule.StudNo', 'LEFT');
		$this->db->join('tblsched', 'tblsched.cfn = tblstudentschedule.Cfn', 'LEFT');
    $this->db->order_by('tblsched.cfn, Lname, Fname, Mname');

		return parent::get($id, $single);
	}

}

/*Location: ./application/models/student_schedule_m.php*/
