<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faculty extends Admin_Controller {
	protected $under_grad_date = '2019-04-01';
	public function __construct()
	{
		parent::__construct();
		$models = array('schedule_m', 'student_schedule_m', 'studgrade_m', 'studgrade_trans_m','late_m');
		$this->load->model($models);
	}

	public function index()
	{
		// $this->output->enable_profiler(TRUE);

		$this->data['date_now'] = date('Y-m-d');
		// $this->data['grad_date_start'] = '2018-03-19';
		// $this->data['grad_date_end'] = '2018-03-28';

		$this->data['grad_date_start'] = '2019-03-18';
		$this->data['grad_date_end'] = '2019-03-28';
		$this->data['under_grad_date'] = $this->under_grad_date;

		$faculty_id = $this->session->userdata('faculty_id');
		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		$condition = array('faculty_id'=>$faculty_id,'SyId'=>$sy_id,'Semid' =>$sem_id);
		$this->db->where($condition);
		$get_agree = $this->late_m->get();
		$complete_grading ;

		$now = date('Y-m-d');


		if ($this->session->userdata('IsCollege') == TRUE)
		{
			$this->data['teach_load'] = $this->schedule_m->get_teacher_program($faculty_id, $sy_id, $sem_id);
			$this->data['teach_load_hsu'] = array();
			$complete_grading = complete_grading($this->data['teach_load']);
		}
		else
		{
			$this->data['teach_load'] = array();
			$this->data['teach_load_hsu'] = $this->session->userdata('teach_load_hsu');
			$complete_grading = complete_grading($this->data['teach_load_hsu']);
		}
		// dump($this->data['teach_load']);
		$this->user_m->logs('View Teaching Load Page');
		//check late
		// if (!(count($get_agree) || $complete_grading))
		// {
		// 	redirect('confirmation');
		// }

		parent::load_view('faculty/teach_load');
	}

	public function test($uri)
	{
		$grade = (float) $this->uri->segment(3, 0);
		$sched_id = (int) $this->uri->segment(4, 0);
		// dump(round($grade, 0, PHP_ROUND_HALF_DOWN));
		dump(round($grade, 1, PHP_ROUND_HALF_DOWN));

		dump('Sched Id => ' . $sched_id);
		dump($this->schedule_m->in_tp($sched_id));
	}


}

/* End of file faculty.php */
/* Location: ./application/controllers/faculty.php */
