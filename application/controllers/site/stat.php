<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stat extends Admin_Controller {

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

		$param = array('SemId' => 1, 'SyId' => 9, 'C.CollegeId' => $faculty->CollegeId);

		// $college = $this->studgrade_stat_m->faculty_encoding_statistics($param);
		// $hsu = $this->studgrade_stat_hsu_m->get_by($param);

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
		!$this->session->userdata('faculty_id') || parent::load_error("Access is denied.");

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		$this->db->where('submitted_at >', $late_date);
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);
		$this->data['title'] = 'List of courses who encoded their grade late';

		$attr = array('target'=>'_blank', 'class' => 'btn btn-default hidden-print');
		$this->data['download_link'] = anchor('site/stat/download_late_graded', 'Download this data', $attr);

		return parent::load_view('stat/course_listing');
	}

	# -- List of courses/prof who encoded their grade late -- #
	public function late_encode_graduating_section($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);
		!$this->session->userdata('faculty_id') || parent::load_error("Access is denied.");

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogGradLateDate'));
		$this->data['late_date'] = $late_date;

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		$this->db->where('submitted_at >=', $late_date);
		$this->db->having("yr_section IN('II-A BACSM', 'IV-PSYCH-PET', 'II-ABSEM', 'IV-MKTG PET', 'III-BR 1', 'III-BR 2', 'II-ACNA', 'II-BCNA', 'II-ACSAD', 'II-BCSAD', 'IV-ACSAD PET', 'II-AITSM', 'II-BITSM', 'II-CITSM', 'III-ABSMT-A', 'III-ABSMT-B', 'III-ABSMT-C', 'IV-AN', 'IV-N IRREG', 'II-A BSP', 'II-B BSP', 'IV-A BSP', 'IV-BSP-PET', 'IV-A RT', 'MSRT-A', 'IV-ECE PET', 'IV-SPED PET', 'IV-BIO PET', 'II-ABTM', 'III-AET', 'II-ETTA', 'III-AIFT')");
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);
		$this->data['title'] = 'List of graduating sections with courses who encoded their grade late';

		$attr = array('target'=>'_blank', 'class' => 'btn btn-default hidden-print');
		$this->data['download_link'] = anchor('site/stat/download_late_graded_graduating_section', 'Download this data', $attr);

		return parent::load_view('stat/course_listing');
	}

	public function on_time_encode($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));

		$first_date = date_convert_to_mysql('2017-10-23');
		$last_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate')));

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		// $this->db->where('submitted_at <= ', $late_date);
		$this->db->where("submitted_at BETWEEN '{$first_date}' AND '{$last_date}'", NULL, FALSE);
		// $this->db->where('! (submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);
		$this->data['title'] = 'List of courses who encoded their grades on-time';


		return parent::load_view('stat/course_listing');
	}


	public function not_encoded($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		$this->db->where('(submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);
		$this->data['title'] = 'List of courses who are not yet graded';
		$attr = array('target'=>'_blank', 'class' => 'btn btn-default hidden-print');
		$this->data['download_link'] = anchor('site/stat/download_not_graded', 'Download this data', $attr);

		return parent::load_view('stat/course_listing');
	}

	public function not_encoded_graduating_section($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		$this->db->where('(submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->db->having("yr_section IN('II-A BACSM', 'IV-PSYCH-PET', 'II-ABSEM', 'IV-MKTG PET', 'III-BR 1', 'III-BR 2', 'II-ACNA', 'II-BCNA', 'II-ACSAD', 'II-BCSAD', 'IV-ACSAD PET', 'II-AITSM', 'II-BITSM', 'II-CITSM', 'III-ABSMT-A', 'III-ABSMT-B', 'III-ABSMT-C', 'IV-AN', 'IV-N IRREG', 'II-A BSP', 'II-B BSP', 'IV-A BSP', 'IV-BSP-PET', 'IV-A RT', 'MSRT-A', 'IV-ECE PET', 'IV-SPED PET', 'IV-BIO PET', 'II-ABTM', 'III-AET', 'II-ETTA', 'III-AIFT')");
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);

		$this->data['title'] = 'List of graduating sections with courses who are not yet graded';
		$attr = array('target'=>'_blank', 'class' => 'btn btn-default hidden-print');
		$this->data['download_link'] = anchor('site/stat/download_not_graded_graduating_section', 'Download this data', $attr);

		return parent::load_view('stat/course_listing');
	}

	public function summary()
	{
		$this->output->enable_profiler(FALSE);
		// dump($this->session->all_userdata());

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		$session = $this->session->all_userdata();
		$college_id = NULL;

		if ( ! empty($session['row']))
		{
			$college_id = $session['row']->CollegeId;
		}

		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));
		$first_date = date_convert_to_mysql('2017-10-23');
		// $last_date = date('Y-m-d', strtotime('-1 day', strtotime($this->session->userdata('EogLateDate'))));
		$last_date = date('Y-m-d', strtotime($this->session->userdata('EogLateDate')));

		if ( ! empty($session['row']))
		{
			$college_id = $session['row']->CollegeId;
			$this->db->where('C.CollegeId', $college_id);
		}
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);

		if ( ! empty($session['row']))
		{
			$college_id = $session['row']->CollegeId;
			$this->db->where('C.CollegeId', $college_id);
		}
		$this->data['faculty'] = $this->studgrade_stat_m->unique_faculty($sy_id, $sem_id);

		$this->data['total_course'] = count($this->data['courses']);
		$this->data['total_faculty'] = count($this->data['faculty']);

		// $this->db->where('submitted_at >', $late_date);
		if ( ! empty($session['row']))
		{
			$college_id = $session['row']->CollegeId;
			$this->db->where('C.CollegeId', $college_id);
		}
		$this->db->where("submitted_at BETWEEN '{$first_date}' AND '{$last_date}'", NULL, FALSE);
		$this->data['course_graded'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);

		if ( ! empty($session['row']))
		{
			$college_id = $session['row']->CollegeId;
			$this->db->where('C.CollegeId', $college_id);
		}
		$this->db->where('(submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->data['course_not_graded'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);

		if ( ! empty($session['row']))
		{
			$college_id = $session['row']->CollegeId;
			$this->db->where('CollegeId', $college_id);
		}
		$this->data['colleges'] = $this->db->get_where('tblcollege')->result();
		$this->data['colleges'][] = array('CollegeId' => NULL, 'CollegeDesc' => '', 'CollegeCode' => 'NO DEPT');
		if ( ! empty($session['row']))
		{
			$college_id = $session['row']->CollegeId;
			$this->db->where('C.CollegeId', $college_id);
		}
		$this->data['college_stat'] = $this->studgrade_stat_m->college_encoding_statistics($sy_id, $sem_id, $late_date, $first_date, $last_date);

		$this->data['title'] = 'Encoding of Grades Statistics';
		return parent::load_view('stat/summary');
	}


	public function download($faculty_id = 0)
	{

		$sem_id = $this->session->userdata('sem_id');
		$sy_id = $this->session->userdata('sy_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));

		$sql_statement = "SELECT SUM(IF(DATE(submitted_at) >= ?, 1, 0)) as late, SUM(IF(DATE(submitted_at) BETWEEN '2018-03-19' AND '2018-03-21', 1, 0)) as on_time, C.CollegeId, CollegeCode, CollegeDesc, B.faculty_id, Lastname, Firstname, Middlename, COUNT(*) as total, SUM(IF(submitted_at IS NULL, 1, 0)) as not_yet_graded, SUM(IF(D.is_graded = 0, 1, 0)) as waiting_to_save,
			SUM(IF(D.IsGradSection = 0, 1, 0)) as save_grad_students,
			SUM(IFNULL(D.is_graded, 0)) as save_courses
				FROM (`tblsched` as A)
				LEFT JOIN `tblcourse` ON `A`.`subject_Id` = `tblcourse`.`CourseId`
				LEFT JOIN `tblfacultydisplay` as B ON `A`.`faculty_id` = `B`.`faculty_id`
				LEFT JOIN `tblcollege` as C ON `B`.`CollegeId` = `C`.`CollegeId`
				LEFT JOIN `tbleogtrans` as D ON `A`.`sched_id` = `D`.`sched_id` AND D.is_actived = 1
				WHERE `remark` NOT IN ('DISSOLVE', 'DISSOLVED')
				AND `remark` NOT LIKE 'MERGE TO%'
				AND NOT(CollegeCode = 'COAHS' AND CourseCode = 'NSA 213' AND CourseDesc LIKE 'THESIS WRITTING%')
				AND `A`.`SyId` =  ?
				AND `A`.`SemId` =  ?
				AND  `cfn` NOT LIKE 'P%'
				AND A.is_actived = 1
				GROUP BY `CollegeCode`, `A`.`faculty_id`
				ORDER BY `CollegeCode`, `Lastname`, `Firstname`, `Middlename`";


			$query = $this->db->query($sql_statement, [$late_date, $sy_id, $sem_id]);
			$filename = date('Y-m-d-H-i-s').'-eog-summary.csv';
	        $delimiter = ";";
	        $newline = "\r\n";

			// Load the download helper and send the file to your desktop
			$this->load->helper('download');
			$this->load->dbutil();
	        $this->load->helper('file');

			header("Content-type: application/vnd.ms-excel;charset=UTF-8");
			header("Content-Disposition: attachment;Filename=$filename");

			$CSV_data = $this->dbutil->csv_from_result($query, $delimiter, $newline);
			$CSV_data = chr(239) . chr(187) . chr(191) . $CSV_data;

			header("Content-type: application/vnd.ms-excel;charset=UTF-8");
			return force_download($filename, $CSV_data);
	}

	public function download_data($faculty_id = 0)
	{
		$sem_id = $this->session->userdata('sem_id');
		$sy_id = $this->session->userdata('sy_id');

		$sql_statement = "SELECT C.CollegeId, CollegeCode, CollegeDesc, cfn, CourseCode, REPLACE(CourseDesc, ',', '') as CourseDesc, year_section, concat(Lastname, ' ', Firstname, ' ', Middlename) FacultyName, B.faculty_id, Lastname, Firstname, Middlename, regis_date_print, returned_date, DateSaveGradSection, submitted_at, remark, (SELECT COUNT(F.StudNo)
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
		            OR G.IsPromissory = 1)) AS enrollees
			FROM (`tblsched` as A)
			LEFT JOIN `tblcourse` ON `A`.`subject_Id` = `tblcourse`.`CourseId`
			LEFT JOIN `tblfacultydisplay` as B ON `A`.`faculty_id` = `B`.`faculty_id`
			LEFT JOIN `tblcollege` as C ON `B`.`CollegeId` = `C`.`CollegeId`
			LEFT JOIN `tbleogtrans` as D ON `A`.`sched_id` = `D`.`sched_id` AND D.is_actived = 1
			WHERE `remark` NOT IN ('DISSOLVE', 'DISSOLVED')
			AND `remark` NOT LIKE 'MERGE TO%'
			AND  `cfn` NOT LIKE 'P%'
			AND NOT(CollegeCode = 'COAHS' AND CourseCode = 'NSA 213' AND CourseDesc LIKE 'THESIS WRITTING%')
			AND `A`.`SyId` =  {$sy_id}
			AND `A`.`SemId` =  {$sem_id}
			AND `A`.`is_actived` =  1
			-- GROUP BY `CollegeCode`, `A`.`faculty_id`
			ORDER BY `CollegeCode`, `Lastname`, `Firstname`, `Middlename` COLLATE utf8_general_ci";


			$query = $this->db->query($sql_statement);
			$filename = date('Y-m-d-H-i-s').'-eog-data.csv';
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

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		$this->db->where('(submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);
		$sql = $this->db->last_query();

		// $this->data['colleges'] = $this->db->get_where('tblcollege')->result();
		// $this->data['colleges'][] = array('CollegeId' => NULL, 'CollegeDesc' => '', 'CollegeCode' => 'NO DEPT');

		// header("Content-type: application/vnd.ms-excel;charset=UTF-8");
		// header("Content-Disposition: attachment;Filename=$filename.xls");

		// // $this->data['title'] = 'List of courses who are not yet graded';
		// return $this->load->view('stat/print_listing', $this->data);

		$query = $this->db->query($sql);
		$filename = date('Y-m-d-H-i-s').'-eog-not-graded.csv';
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

	public function download_not_graded_graduating_section($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		$this->db->where('(submitted_at = 0 || submitted_at IS NULL)', NULL, FALSE);
		$this->db->having("yr_section IN('II-A BACSM', 'IV-PSYCH-PET', 'II-ABSEM', 'IV-MKTG PET', 'III-BR 1', 'III-BR 2', 'II-ACNA', 'II-BCNA', 'II-ACSAD', 'II-BCSAD', 'IV-ACSAD PET', 'II-AITSM', 'II-BITSM', 'II-CITSM', 'III-ABSMT-A', 'III-ABSMT-B', 'III-ABSMT-C', 'IV-AN', 'IV-N IRREG', 'II-A BSP', 'II-B BSP', 'IV-A BSP', 'IV-BSP-PET', 'IV-A RT', 'MSRT-A', 'IV-ECE PET', 'IV-SPED PET', 'IV-BIO PET', 'II-ABTM', 'III-AET', 'II-ETTA', 'III-AIFT')");
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);
		$sql = $this->db->last_query();

		$query = $this->db->query($sql);
		$filename = date('Y-m-d-H-i-s').'-eog-not-graded-graduating-section.csv';
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

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		$this->db->where('submitted_at >', $late_date);
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);
		$sql = $this->db->last_query();
		// $this->data['colleges'] = $this->db->get_where('tblcollege')->result();
		// $this->data['colleges'][] = array('CollegeId' => NULL, 'CollegeDesc' => '', 'CollegeCode' => 'NO DEPT');


		// $filename = date('Y-m-d-H-i-s').'-late-graded';
		// header("Content-type: application/vnd.ms-excel;charset=UTF-8");
		// header("Content-Disposition: attachment;Filename=$filename.xls");

		// // $this->data['title'] = 'List of courses who are not yet graded';
		// return $this->load->view('stat/print_listing', $this->data);

		$query = $this->db->query($sql);
		$filename = date('Y-m-d-H-i-s').'-eog-late-graded.csv';
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

	public function download_late_graded_graduating_section($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogGradLateDate'));

		!$faculty_id || $this->db->where('A.faculty_id', $faculty_id);

		$this->db->where('submitted_at >=', $late_date);
		$this->db->having("yr_section IN('II-A BACSM', 'IV-PSYCH-PET', 'II-ABSEM', 'IV-MKTG PET', 'III-BR 1', 'III-BR 2', 'II-ACNA', 'II-BCNA', 'II-ACSAD', 'II-BCSAD', 'IV-ACSAD PET', 'II-AITSM', 'II-BITSM', 'II-CITSM', 'III-ABSMT-A', 'III-ABSMT-B', 'III-ABSMT-C', 'IV-AN', 'IV-N IRREG', 'II-A BSP', 'II-B BSP', 'IV-A BSP', 'IV-BSP-PET', 'IV-A RT', 'MSRT-A', 'IV-ECE PET', 'IV-SPED PET', 'IV-BIO PET', 'II-ABTM', 'III-AET', 'II-ETTA', 'III-AIFT')");
		$this->data['courses'] = $this->studgrade_stat_m->get_course($sy_id, $sem_id);
		$sql = $this->db->last_query();

		$query = $this->db->query($sql);
		$filename = date('Y-m-d-H-i-s').'-eog-late-graded-graduating-section.csv';
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

	public function affected_student_by_late_encoding($faculty_id = 0)
	{
		// $this->output->enable_profiler(TRUE);
		$this->load->model('m_enroll');

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));

		$late_course = $this->studgrade_stat_m->get_late_cfn($late_date, $sy_id, $sem_id);
		$affected_student = $this->studgrade_stat_m->get_affected_student_by_late_encoding($late_course, $sy_id, $sem_id);
		$affected_student = $this->m_enroll->add_yrlevel($affected_student);

		$this->data['late_course'] = $late_course;
		$this->data['affected_student'] = $affected_student;
		$this->data['title'] = 'Affected Student By Late Encoding';

		$attr = array('target'=>'_blank', 'class' => 'btn btn-default hidden-print');
		$this->data['download_link'] = anchor('site/stat/download_affected_student_by_late_encoding', 'Download this data', $attr);

		return parent::load_view('stat/affected_student');
	}

	public function download_affected_student_by_late_encoding($faculty_id = 0)
	{
		$this->load->model('m_enroll');
		$filename = date('Y-m-d-H-i-s').'-affected-student-by-late-encoding.xls';
        $delimiter = ";";
        $newline = "\r\n";

		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');
		$late_date = date_convert_to_mysql($this->session->userdata('EogLateDate'));

		$late_course = $this->studgrade_stat_m->get_late_cfn($late_date, $sy_id, $sem_id);
		$affected_student = $this->studgrade_stat_m->get_affected_student_by_late_encoding($late_course, $sy_id, $sem_id);
		$data['affected_student'] = $this->m_enroll->add_yrlevel($affected_student);

		$page = $this->load->view('stat/download_affected_student', $data, TRUE);

		// Load the download helper and send the file to your desktop
		$this->load->helper('download');
        $this->load->helper('file');
		$this->load->dbutil();


		header("Content-type: application/vnd.ms-excel;");
		header("Content-Disposition: attachment; filename: $filename; charset=UTF-8");
		return force_download($filename, $page);
	}


}

/* End of file stat.php */
/* Location: ./application/controllers/stat.php */
