<?php
/**
* Filename: studgrade_trans_hsu_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Studgrade_stat_m extends CI_Model
{
	protected $ignore = 'NOT((CollegeCode = "COAHS" AND CourseCode = "NSA 213" AND CourseDesc LIKE "THESIS WRITTING%") OR A.leclab = 6)';

	public function __construct()
	{
		$models = array(
			'schedule_m',
			'student_schedule_m',
			'studgrade_trans_m',
			'studgrade_trans_hsu_m'
		);
		$this->load->model($models);
    }

	public function get()
	{
		$this->db->from('tblsched as A');
		$this->db->join('tblfacultydisplay as B', 'A.faculty_id = B.faculty_id', 'LEFT');
		$this->db->join('tblcollege as C', 'B.CollegeId = C.CollegeId', 'LEFT');
		$this->db->join('tbleogtrans as D', 'A.sched_id = D.sched_id AND D.is_actived = 1', 'LEFT');


		if ( ! count($this->db->ar_orderby))
		$this->db->order_by('C.CollegeCode, Lastname, Firstname, Middlename');

		$this->db->where('A.is_actived', 1);

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
			"(SELECT COUNT(F.StudNo)
			    FROM
			        tblstudentschedule AS F
			            LEFT JOIN
			        tblenrollmenttrans AS G ON G.StudNo = F.StudNo AND F.IsActive = 1 AND F.Status = ''
			    WHERE
			        F.Cfn = A.Cfn
			        AND G.SemId = {$sem_id}
		            AND G.SyId = {$sy_id}
		            AND (G.ISPAID = 1 OR G.IsPrinted = 1
		            OR G.IsScholar = 1
		            OR G.IsPromissory = 1)) AS enrollees",
		);

		$this->db->select($column, FALSE);
		$this->db->join('tblcourse', 'A.subject_Id = tblcourse.CourseId', 'LEFT');
		$this->db->group_by('CollegeCode, A.faculty_id');
		$this->db->order_by('CollegeCode, Lastname, Firstname, Middlename');

		// $this->db->where('A.faculty_id >', 0);
		// $this->db->where('IsGraduateSchool', 0);
		$this->db->where('C.CollegeId !=', 21);
		$this->db->where_not_in('remark', array('DISSOLVE', 'DISSOLVED'));
		// $this->db->where('remark !=', 'DISSOLVE');
		// $this->db->where('remark !=', 'MERGE TO');
		// $this->db->not_like('remark', 'MERGE TO', 'after');
		$this->db->not_like('cfn', 'P', 'after');
		$this->db->where('NOT((CollegeCode = "COAHS" AND CourseCode = "NSA 213" AND CourseDesc LIKE "THESIS WRITTING%") or A.leclab = 6)', NULL, FALSE);
		$this->db->having('enrollees > 0');

		$param = array('A.SyId' => $sy_id, 'A.SemId' => $sem_id);
		return self::faculty_encoding_statistics($param);
	}

	public function get_course($sy_id, $sem_id)
	{
		$select = array(
			'C.CollegeId',
			'CollegeCode',
			'CollegeDesc',
			'CourseCode',
			"REPLACE(CourseDesc, ',', '') as CourseDesc",
			'tblcourse.Units',
			'CONCAT(Year,"-",Section) as yr_section',
			'B.faculty_id',
			'Lastname',
			'Firstname',
			'Middlename',
			'remark',
			'submitted_at AS Date_Submitted',
			"(SELECT COUNT(F.StudNo)
			    FROM
			        tblstudentschedule AS F
			            LEFT JOIN
			        tblenrollmenttrans AS G ON G.StudNo = F.StudNo AND F.IsActive = 1 AND F.Status = ''
			    WHERE
			        F.Cfn = A.Cfn
			        AND G.SemId = {$sem_id}
		            AND G.SyId = {$sy_id}
		            AND (G.ISPAID = 1 OR G.IsPrinted = 1
		            OR G.IsScholar = 1
		            OR G.IsPromissory = 1)) AS enrollees",
		);

		$this->db->select($select, FALSE);
		$this->db->join('tblyrsec', 'A.YearSectionId = tblyrsec.Id', 'LEFT');
		$this->db->join('tblcourse', 'A.subject_Id = tblcourse.CourseId', 'LEFT');

		// $this->db->where('A.faculty_id >', 0);
		// $this->db->where('IsGraduateSchool', 0);
		// $this->db->where('remark !=', 'DISSOLVE');
		// $this->db->where('remark !=', 'MERGE TO');
		$this->db->where('C.CollegeId !=', 21);
		$this->db->where_not_in('remark', array('DISSOLVE', 'DISSOLVED'));
		// $this->db->not_like('remark', 'MERGE TO', 'after');
		$this->db->not_like('cfn', 'P', 'after');
		$this->db->where('NOT((CollegeCode = "COAHS" AND CourseCode = "NSA 213" AND CourseDesc LIKE "THESIS WRITTING%") or A.leclab = 6 )', NULL, FALSE);

		$this->db->order_by('C.CollegeCode, Lastname, Firstname, Middlename, yr_section, CourseCode');
		$this->db->having('enrollees > 0');

		return self::get_by(array('A.SemId' => $sem_id, 'A.SyId' => $sy_id));
	}

	public function unique_faculty($sy_id, $sem_id)
	{
		$this->db->distinct();
		$this->db->select('CollegeCode, CollegeDesc, Lastname, Firstname, Middlename, B.faculty_id');
		$this->db->join('tblcourse', 'A.subject_Id = tblcourse.CourseId', 'LEFT');
		$ignore = 'NOT((CollegeCode = "COAHS" AND CourseCode = "NSA 213" AND CourseDesc LIKE "THESIS WRITTING%") or A.leclab = 6)';
		$this->db->where('A.faculty_id >', 0);
		$this->db->where('IsGraduateSchool', 0);
		// $this->db->where_not_in('remark', array('DISSOLVE', 'MERGE TO'));
		$this->db->where('C.CollegeId !=', 21);
		$this->db->where_not_in('remark', ['DISSOLVE', 'DISSOLVED']);
		$this->db->not_like('cfn', 'P', 'after');

		$this->db->where($ignore, NULL, FALSE);

		return self::get_by(array('A.SemId' => $sem_id, 'A.SyId' => $sy_id));
	}

	public function get_late($sy_id, $sem_id)
	{

		return self::get_course($sy_id, $sem_id);
	}

	public function get_faculty($sy_id, $sem_id)
	{


		return self::get_by(array('A.SyId' => $sy_id, 'A.SemId' => $sem_id));
	}

	public function get_affected_student_by_late_encoding($cfn, $sy_id, $sem_id, $result = FALSE)
	{
		if ( ! is_array($cfn))
			return FALSE;

		foreach ($cfn as $row)
		$nametable[] = "'" . $row['cfn'] . "'";
		$nametable = implode(",", $nametable);

		$sql = "SELECT DISTINCT A.StudNo, d_release FROM tblstudentschedule as A
				LEFT JOIN stud_info as B ON B.newstudid = A.StudNo AND A.SyId = B.SyId AND A.SemId = B.SemId
				LEFT JOIN tblsched as C ON A.Cfn = C.cfn AND A.IsActive = 1
				LEFT JOIN tbleogtrans as D ON C.sched_id = D.sched_id AND D.is_actived = 1
				WHERE A.SyId = {$sy_id} AND A.SemId = {$sem_id} AND
				d_release > 0 AND
				submitted_at > 0 AND
				# d_release < submitted_at AND
				A.Cfn IN($nametable)";

		$student = $this->db->query($sql)->result_array();

		if (count($student))
		{
			foreach ($student as $data)
			$studno[] = "'" . $data['StudNo'] . "'";
			$studno = implode(",", $studno);

			$sql = "SELECT Lname, Fname, Mname, A.StudNo, CurriculumDesc, CollegeCode, ProgramDesc, MajorDesc, LengthOfStayBySem,
							C.CollegeId, C.ProgramId, C.MajorId, C.CurriculumId , d_release
						FROM (tblstudinfo as A)
						LEFT JOIN tblstudcurriculum as B ON A.StudNo = B.StudNo
						LEFT JOIN tblcurriculum as C ON B.CurriculumId = C.CurriculumId
						LEFT JOIN tblcollege as D ON C.CollegeId = D.CollegeId
						LEFT JOIN tblprogram as E ON C.ProgramId = E.ProgramId
						LEFT JOIN tblmajor as F ON C.MajorId = F.MajorId
						INNER JOIN stud_info as G ON NEWSTUDID = A.StudNo AND G.SyId = {$sy_id} AND G.SemId = {$sem_id}
						WHERE A.StudNo IN($studno) AND IsGraduateProgram = 0 AND IsTCP = 0
						ORDER BY Lname, Fname, Mname";

			if ($result == TRUE) return $this->db->query($sql);

			return $this->db->query($sql)->result_array();
		}

		return FALSE;

	}

	public function get_late_cfn($late_date, $sy_id, $sem_id)
	{
		$sql = "SELECT A.cfn, submitted_at
					FROM (tblsched as A)
					LEFT JOIN tblyrsec ON A.YearSectionId = tblyrsec.Id
					LEFT JOIN tblcourse ON A.subject_Id = tblcourse.CourseId
					LEFT JOIN tblfacultydisplay as B ON A.faculty_id = B.faculty_id
					LEFT JOIN tblcollege as C ON B.CollegeId = C.CollegeId
					LEFT JOIN tbleogtrans as D ON A.sched_id = D.sched_id AND D.is_actived = 1
					WHERE submitted_at > '{$late_date}'
					AND remark NOT IN ('DISSOLVE', 'DISSOLVED')
					-- AND remark NOT LIKE 'MERGE TO%'
					AND C.CollegeId != 21
					AND {$this->ignore}
					AND A.SemId = {$sem_id}
					AND A.SyId =  {$sy_id}
					AND A.is_actived =  1
					AND  cfn NOT LIKE 'P%'
					HAVING (SELECT COUNT(F.StudNo)
								FROM
								 tblstudentschedule AS F
								 LEFT JOIN
								 tblenrollmenttrans AS G ON G.StudNo = F.StudNo AND F.IsActive = 1 AND F.Status = ''
								WHERE
								 F.Cfn = A.Cfn
								 AND G.SemId = {$sem_id}
								 AND G.SyId = {$sy_id}
								 AND (G.IsPaid = 1 OR G.IsPrinted = 1 OR G.IsScholar = 1 OR G.IsPromissory = 1)
							) > 0;";

		return $this->db->query($sql)->result_array();
	}

}

/*Location: ./application/models/studgrade_trans_hsu_m.php*/
