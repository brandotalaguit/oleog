<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hsu_stat extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('studgrade_stat_m');
		$this->load->model('studgrade_stat_hsu_m');

		if ($this->router->fetch_method() != 'index')
			!$this->session->userdata('faculty_id') || parent::load_error("Access is denied.");
	}

	public function index()
	{
		// $this->output->enable_profiler(TRUE);

		$faculty = $this->user_m->get_by(array('faculty_id' => $this->session->userdata('faculty_id')), TRUE);
		$this->data['college'] = $this->db->get_where('tblcollege', array('CollegeId' => $faculty->CollegeId))->row();

		$param = array('SemId' => 2, 'SyId' => 7, 'C.CollegeId' => $faculty->CollegeId);

		$college = $this->studgrade_stat_m->faculty_encoding_statistics($param);
		$hsu = $this->studgrade_stat_hsu_m->faculty_encoding_statistics($param);

		$this->data['tot_wait'] = 0;
		$this->data['tot_save'] = 0;
		$this->data['tot_nyg'] = 0;
		$this->data['total'] = 0;
		$this->data['statistics'] = $college;
		$this->data['statistics2'] = $hsu;

		parent::load_view('stat/index');
	}

	# -- List of courses/prof who encoded their grade late -- #
	public function late_encode($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));

		!$faculty_id || $this->db->where('B.faculty_id', $faculty_id);

		$this->db->where('submitted_at >', $late_date);
		$this->data['courses'] = $this->studgrade_stat_hsu_m->get_course($sy_id, $sem_id);
		$this->data['title'] = 'List of courses who encoded their grade late';

		$attr = array('target'=>'_blank', 'class' => 'btn btn-default hidden-print');
		$this->data['download_link'] = anchor('site/hsu_stat/download_late_graded', 'Download this data', $attr);

		return parent::load_view('stat/course_listing');
	}

	public function on_time_encode($faculty_id = 0)
	{
		$this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));

		!$faculty_id || $this->db->where('B.faculty_id', $faculty_id);

		$this->db->where('submitted_at <=', $late_date);
		$this->data['courses'] = $this->studgrade_stat_hsu_m->get_course($sy_id, $sem_id);
		$this->data['title'] = 'List of courses who encoded their grades on-time';


		return parent::load_view('stat/course_listing');
	}


	public function not_encoded($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		!$faculty_id || $this->db->where('B.faculty_id', $faculty_id);

		$this->db->where('(submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->data['courses'] = $this->studgrade_stat_hsu_m->get_course($sy_id, $sem_id);
		$this->data['title'] = 'List of courses who are not yet graded';
		$attr = array('target'=>'_blank', 'class' => 'btn btn-default hidden-print');
		$this->data['download_link'] = anchor('site/hsu_stat/download_not_graded', 'Download this data', $attr);

		return parent::load_view('stat/course_listing');
	}

	public function summary()
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));
		$first_date = date_convert_to_mysql('2017-10-23');
		// $last_date = date('Y-m-d', strtotime('-1 day', strtotime($this->session->userdata('EogLateDate'))));
		$last_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate')));


		$this->data['courses'] = $this->studgrade_stat_hsu_m->get_course($sy_id, $sem_id);
		$this->data['faculty'] = $this->studgrade_stat_hsu_m->unique_faculty($sy_id, $sem_id);

		$this->data['total_course'] = count($this->data['courses']);
		$this->data['total_faculty'] = count($this->data['faculty']);

		$this->db->where('submitted_at >', $late_date);
		$this->data['course_graded'] = $this->studgrade_stat_hsu_m->get_course($sy_id, $sem_id);

		$this->db->where('(submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->data['course_not_graded'] = $this->studgrade_stat_hsu_m->get_course($sy_id, $sem_id);

		$this->data['colleges'] = $this->db->get_where('tblcollege')->result();
		$this->data['colleges'][] = array('CollegeId' => NULL, 'CollegeDesc' => '', 'CollegeCode' => 'NO DEPT');
		$this->data['college_stat'] = $this->studgrade_stat_hsu_m->college_encoding_statistics($sy_id, $sem_id, $late_date, $first_date, $last_date);

		$this->data['title'] = 'Encoding of Grades Hsu_statistics';
		return parent::load_view('stat/hsu_summary');
	}


	public function download($faculty_id = 0)
	{

		$sql_statement = "SELECT SUM(IF(DATE(submitted_at) >= '2017-10-31', 1, 0)) as late, SUM(IF(DATE(submitted_at) BETWEEN '2017-10-23' AND '2017-10-30', 1, 0)) as on_time, C.CollegeId, CollegeCode, CollegeDesc, B.faculty_id, Lastname, Firstname, Middlename, COUNT(*) as total, SUM(IF(submitted_at IS NULL, 1, 0)) as not_yet_graded, SUM(IF(D.is_graded = 0, 1, 0)) as waiting_to_save, SUM(IFNULL(D.is_graded, 0)) as save_courses
				FROM (". HSU_DB .".`schedules` as A)
				LEFT JOIN `tblfacultydisplay` as B ON `A`.`prof_id` = `B`.`faculty_id`
				LEFT JOIN `tblcollege` as C ON `B`.`CollegeId` = `C`.`CollegeId`
				LEFT JOIN ". HSU_DB .".`tbleogtrans` as D ON `A`.`sched_id` = `D`.`sched_id` AND D.is_actived = 1
				WHERE `A`.`SyId` =  '7'
				AND `A`.`SemId` =  '1'
				AND A.is_actived = 1
				AND A.subcode != 'RHGP'
				GROUP BY `CollegeCode`, `B`.`faculty_id`
				ORDER BY `CollegeCode`, `Lastname`, `Firstname`, `Middlename`";


		$query = $this->db->query($sql_statement);
		$filename = date('Y-m-d-H-i-s').'-hsu-eog-summary.csv';
        $delimiter = ";";
        $newline = "\r\n";

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
        $this->load->helper('file');
		$this->load->dbutil();

		$CSV_data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
		$CSV_data = chr(239) . chr(187) . chr(191) . $CSV_data;

		header("Content-type: application/vnd.ms-excel;charset=UTF-8");
		return force_download($filename, $CSV_data);
	}

	public function download_data()
	{
		$sem_id = $this->session->userdata('sem_id');
		$sy_id = $this->session->userdata('sy_id');

		$sql_statement = "SELECT C.CollegeId, CollegeCode, CollegeDesc,  cfn,
			subcode as CourseCode, REPLACE(subdes, ',', '') as CourseDesc, section as year_section, concat(Lastname, ' ', Firstname, ' ', Middlename) FacultyName, B.faculty_id, Lastname, Firstname, Middlename, submitted_at, DateSaveGradSection, remark,
			(SELECT COUNT(F.student_id) FROM " . HSU_DB . ".student_schedules AS F
	            LEFT JOIN " . HSU_DB . ".student_enrollments AS G ON G.stud_id = F.student_id AND G.is_actived = 1
			    WHERE
		        F.CFN = A.CFN
		        AND G.sem_id = {$sem_id}
	            AND G.sy_id = {$sy_id}
	            AND F.is_actived = 1
	        ) AS enrollees
			FROM (". HSU_DB .".`schedules` as A)
			LEFT JOIN `tblfacultydisplay` as B ON `A`.`prof_id` = `B`.`faculty_id`
			LEFT JOIN `tblcollege` as C ON `B`.`CollegeId` = `C`.`CollegeId`
			LEFT JOIN ". HSU_DB .".`tbleogtrans` as D ON `A`.`sched_id` = `D`.`sched_id` AND D.is_actived = 1
			WHERE `A`.`SyId` =  {$sy_id}
			AND `A`.`SemId` =  {$sem_id}
			AND `A`.`is_actived` =  1
			-- GROUP BY `CollegeCode`, `B`.`faculty_id`
			ORDER BY `CollegeCode`, `Lastname`, `Firstname`, `Middlename` COLLATE utf8_general_ci";

			$query = $this->db->query($sql_statement);
			$filename = date('Y-m-d-H-i-s').'-hsu-eog-data.csv';
	        $delimiter = ";";
	        $newline = "\r\n";

			// Load the download helper and send the file to your desktop
			$this->load->helper('download');
	        $this->load->helper('file');
			$this->load->dbutil();

			$CSV_data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
			$CSV_data = chr(239) . chr(187) . chr(191) . $CSV_data;

			header("Content-type: application/vnd.ms-excel;charset=UTF-8");
			return force_download($filename, $CSV_data);
	}

	public function download_not_graded($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		// $sy_id = $this->session->userdata('sy_id');
		// $sem_id = $this->session->userdata('sem_id');

		$sy_id = 7;
		$sem_id = 2;

		!$faculty_id || $this->db->where('B.faculty_id', $faculty_id);

		$this->db->where('(submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->data['courses'] = $this->studgrade_stat_hsu_m->get_course($sy_id, $sem_id);
		$sql = $this->db->last_query();

		$query = $this->db->query($sql);
		$filename = date('Y-m-d-H-i-s').'-hsu-eog-not-graded.csv';
        $delimiter = ";";
        $newline = "\r\n";

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
        $this->load->helper('file');
		$this->load->dbutil();

		$CSV_data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
		$CSV_data = chr(239) . chr(187) . chr(191) . $CSV_data;

		header("Content-type: application/vnd.ms-excel;charset=UTF-8");
		return force_download($filename, $CSV_data);
	}

	public function download_late_graded($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));

		!$faculty_id || $this->db->where('B.faculty_id', $faculty_id);

		$this->db->where('submitted_at >', $late_date);
		$this->data['courses'] = $this->studgrade_stat_hsu_m->get_course($sy_id, $sem_id);
		$sql = $this->db->last_query();

		$query = $this->db->query($sql);
		$filename = date('Y-m-d-H-i-s').'-hsu-eog-late-graded.csv';
        $delimiter = ";";
        $newline = "\r\n";

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
        $this->load->helper('file');
		$this->load->dbutil();

		$CSV_data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
		$CSV_data = chr(239) . chr(187) . chr(191) . $CSV_data;

		header("Content-type: application/vnd.ms-excel;charset=UTF-8");
		return force_download($filename, $CSV_data);

	}


}

/* End of file stat.php */
/* Location: ./application/controllers/stat.php */
