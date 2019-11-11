<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Confirmation extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$models = array('schedule_m', 'student_schedule_m', 'studgrade_m', 'studgrade_trans_m','late_m');
		$this->load->model($models);
	}

	public function index()
	{
		
		parent::load_view('faculty/late-encoding');
	}

	function agree()
	{
		$faculty_id = $this->session->userdata('faculty_id');
		$sy_id = $this->session->userdata('sy_id');
		$sem_id = $this->session->userdata('sem_id');

		$save = array('faculty_id'=>$faculty_id,'SyId'=>$sy_id,'Semid' =>$sem_id);

		$id = $this->late_m->save($save);

		if($id)
		{
			redirect('faculty');
		}
	}
	



}

/* End of file faculty.php */
/* Location: ./application/controllers/faculty.php */
