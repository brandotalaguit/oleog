<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Student extends Admin_Controller {

	public function __construct()
	{
            parent::__construct();


            // load models
            $this->load->model('students');
            $this->load->model('stud_collschedules');
            $this->load->model('stud_logs');

            $this->data['total_units'] = 0.0;

            $student_id = $this->session->userdata('username');
            
            // disable survey
            /*
            // check if student fill up the survey form
            $survey = $this->survey_m->get_by(['student_id' => $student_id], TRUE);
            if ( ! count($survey)) 
            {
            	redirect(base_url('survey'));
            }
            */
	}


	public function index()
	{
		// $this->output->enable_profiler(TRUE);
		
		$studno = $this->session->userdata('StudNo');
	
		
		// initializes student
		$this->db->join('tblstudcurriculum as a ','a.StudNo = tblstudinfo.StudNo','left')
				 ->join('tblcurriculum as b','b.CurriculumId=a.CurriculumId','left')
				 ->join('tblcollege as c', 'c.CollegeId=b.CollegeId','left');
		$student = $this->students->get_by(['tblstudinfo.StudNo' => $studno]);
		
		// pass student to view

		$SyId = $this->session->userdata('SyId');
		$SemId = $this->session->userdata('SemId');

		$Sem = $this->db->get_where('tblsem',array('SemId' =>$SemId))->row();
		$Sy = $this->db->get_where('tblsy',array('SyId' =>$SyId))->row();

		$this->data['stud'] = $student;
		
		// load view
		$this->load_view('rog/show');

	}

    public function printGrade()
	{
		$this->output->enable_profiler(TRUE);
		
		$studno = $this->session->userdata('StudNo');
		$flagPrint=$this->uri->segment(3, 1);
		
		// initializes student
		$this->db->join('tblstudcurriculum as a ','a.StudNo = tblstudinfo.StudNo','left')
				 ->join('tblcurriculum as b','b.CurriculumId=a.CurriculumId','left')
				 ->join('tblcollege as c', 'c.CollegeId=b.CollegeId','left')
				 ->join('tblprogram as d','d.ProgramId=b.ProgramId','left')
				 ->join('tblmajor as e','e.MajorId=b.MajorId','left');
		$student = $this->students->get_by(['tblstudinfo.StudNo' => $studno]);
		
		$SyId = $this->session->userdata('SyId');
		$SemId = $this->session->userdata('SemId');

		$Sem = $this->db->get_where('tblsem',array('SemId' =>$SemId))->row();
		$Sy = $this->db->get_where('tblsy',array('SyId' =>$SyId))->row();

		
		if ($SemId==2 ) 
		{
			$sched_year = $this->stud_collschedules->get_student_schedule_year($studno, $SyId);
			$sched_first_sem = $this->stud_collschedules->get_student_schedule($studno, $SyId,"1");
			$sched = $this->stud_collschedules->get_student_schedule($studno, $SyId, 2);
			$this->data['sched_year']=$sched_year;
			$this->data['sched_first_sem']=$sched_first_sem;
			// var_dump($sched_first_sem);
			$SemDesc= $Sem->SemCode." ".$Sy->SyCode;
		}
		else
		{
			$sched = $this->stud_collschedules->get_student_schedule($studno, $SyId, $SemId);
			$SemDesc= $Sem->SemCode." ".$Sy->SyCode;	
		}

		// pass student to view
		$this->data['stud'] = $student;
		$this->data['SemDesc'] = $SemDesc;
		$this->data['sched']=$sched;
		$this->data['sem'] = $Sem->SemDesc;
		// load view
		$this->load_view('rog/print');

		// // log access to page
		$log = array(
				'newstudid' => $this->session->userdata('StudNo'), 
				'remarks' => 'Print Report of Grades',
				'SyId' => $this->session->userdata('SyId'),
				'SemId' => $this->session->userdata('SemId'),
			);
		$this->stud_logs->save($log);

		$stud_log = array('d_release' => date('Y-m-d H:i:s'));
		$this->user_m->save($stud_log, $this->session->userdata('id'));
	}
	

}



/* End of file student.php */
/* Location: ./application/controllers/student.php */