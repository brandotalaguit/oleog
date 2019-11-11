<?php
/**
* Filename: studgrade_stat_hsu_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Studgrade_stat_hsu_m extends CI_Model
{
	public function __construct()
	{
		$models = array('schedule_hsu_m', 'studgrade_trans_hsu_m');
		$this->load->model($models);
    }

	public function get()
	{
		$this->db->from(HSU_DB . '.schedules as A');
		$this->db->join('tblfacultydisplay as B', 'A.prof_id = B.faculty_id', 'LEFT');
		$this->db->join('tblcollege as C', 'B.CollegeId = C.CollegeId', 'LEFT');
		$this->db->join(HSU_DB . '.tbleogtrans as D', 'A.sched_id = D.sched_id AND D.is_actived = 1', 'LEFT');

		if ( ! count($this->db->ar_orderby))
		$this->db->order_by('C.CollegeCode, Lastname, Firstname, Middlename');

		$this->db->where('A.is_actived', 1);
		// $this->db->where('subcode != ', 'RHGP');
		return $this->db->get()->result();
	}

	public function get_by($condition)
	{
		$this->db->where($condition);
		return self::get();
	}

	public function faculty_encoding_statistics($param)
	{
		$select = array(
			'C.CollegeId',
			'CollegeCode',
			'CollegeDesc',
			'B.faculty_id',
			'Lastname',
			'Firstname',
			'Middlename',
			'COUNT(*) as total',
			'SUM(IF(D.sched_id IS NULL, 1, 0)) as not_yet_graded',
			'SUM(IF(D.is_graded = 0, 1, 0)) as waiting_to_save',
			'SUM(IFNULL(D.is_graded, 0)) as save_courses',
		);

		$this->db->select($select, FALSE);

		if ( ! count($this->db->ar_groupby))
		$this->db->group_by('CollegeCode, B.faculty_id');

		return self::get_by($param);
	}

	public function college_encoding_statistics($sy_id, $sem_id, $late_date, $first_date, $last_date)
	{
		$column = array(
			"SUM(IF(DATE(submitted_at) >= '{$late_date}', 1, 0)) as late",
			"SUM(IF(DATE(submitted_at) BETWEEN '{$first_date}' AND '{$last_date}', 1, 0)) as on_time",
			// "SUM(IF(submitted_at > 0, 1, 0)) as graded",
		);

		$this->db->select($column, FALSE);
		$this->db->group_by('CollegeCode, B.faculty_id');
		$this->db->order_by('CollegeCode, Lastname, Firstname, Middlename');

		// $this->db->where('A.faculty_id >', 0);
		// $this->db->where('IsGraduateSchool', 0);
		// $this->db->where('remark !=', 'DISSOLVE');
		// $this->db->where('remark !=', 'MERGE TO');
		// $this->db->not_like('cfn', 'P', 'after');

		$param = array('A.SyId' => $sy_id, 'A.SemId' => $sem_id);
		return self::faculty_encoding_statistics($param);
	}

	public function get_course($sy_id, $sem_id)
	{
		$select = array(
			'C.CollegeId',
			'CollegeCode',
			'CollegeDesc',
			'subcode as CourseCode',
			"REPLACE(subdes, ',', '') as CourseDesc",
			'units as Units',
			'section as yr_section',
			'B.faculty_id',
			'Lastname',
			'Firstname',
			'Middlename',
			'submitted_at AS Date_Submitted',
			"(SELECT COUNT(F.student_id)
			    FROM
			        " . HSU_DB . ".student_schedules AS F
			            LEFT JOIN
			        " . HSU_DB . ".student_enrollments AS G ON G.stud_id = F.student_id AND G.is_actived = 1
			    WHERE
			        F.CFN = A.CFN
			        AND G.sem_id = {$sem_id}
		            AND G.sy_id = {$sy_id}
		            AND F.is_actived = 1) AS enrollees",
		);

		$this->db->select($select, FALSE);
		// $this->db->where('A.faculty_id >', 0);
		// $this->db->where('IsGraduateSchool', 0);
		// $this->db->where('remark !=', 'DISSOLVE');
		// $this->db->where('remark !=', 'MERGE TO');
		// $this->db->where('subcode !=', 'RHGP');
		$this->db->order_by('C.CollegeCode, Lastname, Firstname, Middlename, yr_section, CourseCode');
		// $this->db->having('enrollees > 0');

		return self::get_by(array('A.SemId' => $sem_id, 'A.SyId' => $sy_id));
	}

	public function unique_faculty($sy_id, $sem_id)
	{
		$this->db->distinct()->select('CollegeCode, CollegeDesc, Lastname, Firstname, Middlename, B.faculty_id');
		return self::get_by(array('A.SemId' => $sem_id, 'A.SyId' => $sy_id, 'B.faculty_id >' => 0));
	}

	public function get_late($sy_id, $sem_id)
	{

		return self::get_course($sy_id, $sem_id);
	}

	public function get_faculty($sy_id, $sem_id)
	{


		return self::get_by(array('A.SyId' => $sy_id, 'A.SemId' => $sem_id));
	}

}

/*Location: ./application/models/studgrade_stat_hsu_m.php*/
