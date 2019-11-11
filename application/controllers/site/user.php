<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends Admin_Controller {

	public $system_maintenance = FALSE;
	public $system_message = "This system is undertaking maintenance";

	public function __construct()
	{
		parent::__construct();

		$this->db->join('tblsem', 'tblsem.SemId = tblsysem.SemId', 'LEFT');
		$this->db->join('tblsy', 'tblsy.SyId = tblsysem.SyId', 'LEFT');
		$school_yr = $this->db->get_where('tblsysem', array('tblsysem.SyId' => 8, 'tblsysem.SemId' => 2))->row();
		// $school_yr = $this->db->get_where('tblsysem', array('tblsysem.SyId' => 7, 'tblsysem.SemId' => 1))->row();
		// $school_yr = $this->db->get_where('tblsysem', array('IsPreviousSem' => 1))->row();
		// $school_yr = $this->db->get_where('tblsysem', array('IsCurrentSem' => 1))->row();

		$sysem = array(
			'sem_id' => $school_yr->SemId,
			'sem_code' => $school_yr->SemCode,
			'sem_desc' => $school_yr->SemDesc,
			'sy_id' => $school_yr->SyId,
			'sy_code' => $school_yr->SyCode,
			'sy_desc' => $school_yr->SyDesc,
			'date_start' => date('Y-m-d', strtotime($school_yr->EogDateStart)),
			'date_end' => date('Y-m-d', strtotime($school_yr->EogDateEnd)),
			'time_start' => date('H:i:s', strtotime($school_yr->EogTimeStart)),
			'time_end' => date('H:i:s', strtotime($school_yr->EogTimeEnd)),
			'EogLateDate' => date('Y-m-d H:i:s', strtotime($school_yr->EogLateDate)),
			'EogGradLateDate' => date('Y-m-d H:i:s', strtotime($school_yr->EogGradLateDate)),
		);

		$maintenance = array(
								"system_maintenance" =>$this->system_maintenance,
								"message"			 =>$this->system_message
						    );
		$this->session->set_userdata($sysem);
		$this->session->set_userdata($maintenance);
		$this->sy = $this->session->userdata('sy_id');
		$this->sem = $this->session->userdata('sem_id');
		$this->faculty = $this->session->userdata('faculty_id');



	}

	// public function index()
	// {
	// 	$this->output->enable_profiler(TRUE);
	// 	$this->session->sess_destroy();
	// 	return redirect('site/user/login');
	// }

	public function login()
	{
		$this->output->enable_profiler(FALSE);

		// Redirect a user if he's already logged in
		$dashboard = 'faculty';
		$this->user_m->loggedin() == FALSE || redirect($dashboard);
		// Set form
		$rules = $this->user_m->rules;
		$this->form_validation->set_rules($rules);
		$now = date('Y-m-d H:i:s');
		// Process form
		if ($this->form_validation->run() == TRUE)
		{
			if ($this->session->userdata('date_start') > $now )
			{
				// $this->logs('Logged Out');
				// $this->session->sess_destroy();
				$this->session->set_flashdata('error', '<ul><li>Invalid Access Please Login within the Given Schedule</li></ul>');
				redirect('site/user/login','refresh');
			}

			// if ($this->session->userdata('date_start') > $now || $this->session->userdata('date_end') < $now )
			// {
			// 	// $this->logs('Logged Out');
			// 	$this->session->sess_destroy();
			// 	$this->session->set_flashdata('error', '<ul><li>Invalid Access Please Login within the Given Schedule</li></ul>');
			// 	redirect('site/user/login','refresh');
			// }
			# authenticated and can now be redirected
			if ($this->user_m->login() == TRUE)
			{
                $this->session->set_flashdata('hlpMsg', TRUE);
				return redirect($dashboard, 'location', 301);
			}
			else
			{
				$this->session->set_flashdata('error', '<ul><li>Username/Password combination does not exists</li></ul>');
				return redirect('site/user/login', 'refresh');
			}
		}

		// setup view
		$this->data['faculty'] = $this->user_m->get_faculty();
		$this->data['content'] = 'login/form';
		$this->data['form_url'] = form_open(NULL, array(
			'role' => 'form',
			'class' => 'form-horizontal',
			'autocomplete' => 'off'));
		$this->load->view('layout/login_template', $this->data);
	}

	public function admin()
	{
		// $this->output->enable_profiler(TRUE);

		// Redirect a user if he's already logged in
		$dashboard = 'site/stat/summary';
		$this->user_m->loggedin() == FALSE || redirect($dashboard);
		// Set form
		$rules = $this->user_m->rules;
		$this->form_validation->set_rules($rules);

		// Process form
		if ($this->form_validation->run() == TRUE)
		{
			# authenticated and can now be redirected
			if ($this->user_m->admin_login() == TRUE)
			{
	            $this->session->set_flashdata('hlpMsg', TRUE);
				return redirect($dashboard, 'location', 301);
			}
			else
			{
				$this->session->set_flashdata('error', '<ul><li>Username/Password combination does not exists</li></ul>');
				return redirect('site/user/admin', 'refresh');
			}
		}

		// setup view
		$this->data['content'] = 'login/admin';
		$this->data['form_url'] = form_open(NULL, array(
			'role' => 'form',
			'class' => 'form-horizontal',
			'autocomplete' => 'off'));
		$this->load->view('layout/login_template', $this->data);
	}

	public function logout()
	{
		$this->user_m->logout();
		return redirect('site/user/login');
	}

	public function admin_logout()
	{
		$this->user_m->admin_logout();
		return redirect('site/user/login');
	}

	public function hash($str = 'hgrunt85')
	{
		return $this->output->set_content_type('application/json')
			->set_output(json_encode($this->user_m->hash('md5', $str . config_item('encryption_key'))));
	}
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
