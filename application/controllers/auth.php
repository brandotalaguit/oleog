<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_m');
	}

	public function index()
	{
		return $this->output->set_content_type('application/json')
					->set_output(json_encode($this->user_m->loggedin() === TRUE ? 1 : 0));

		/*if ($this->user_m->loggedin() !== TRUE) 
		{
			$this->session->flashdata('error', 'Due to none activity the system has automatically logged you out for security purposes, 
												please enter your credential and log-in again.');
			// return redirect('/','refresh');
			return $this->output->set_content_type('application/json')->set_output(json_encode(0));
		}

		return $this->output->set_content_type('application/json')->set_output(json_encode(1));*/
	}

}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */