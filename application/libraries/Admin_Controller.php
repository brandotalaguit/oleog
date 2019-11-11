<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends MY_Controller 
{
	public $sy, $sem, $faculty;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_m');

		$this->form_validation->set_error_delimiters('<li>', '</li>');
		
		// Login Check 
		$exception_uris = array(
			'site/user/login',
			'site/user/logout',
			'site/user/admin',
			'site/user/admin_logout',
		);

		if (in_array(uri_string(), $exception_uris) == FALSE) 
		{
			if ($this->user_m->loggedin() == FALSE) 
			{
				$this->session->flashdata('error', 'Due to none activity the system has automatically logged you out, 
													please enter your credential and log-in again.');
				return redirect('site/user/login');
			}
		}

		$attribute = array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off');
		$this->data['form_url'] = form_open(NULL, $attribute);
		$this->data['counter'] = $this->uri->segment(3, 0);
	}

}

/* End of file Admin_Controller.php */
/* Location: ./application/controllers/Admin_Controller.php */