<?php
/**
* Filename: user_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class User_M extends MY_Model
{
	protected $table_name = "tblfacultydisplay";
	protected $primary_key = "faculty_id";
	protected $order_by = "Lastname, Firstname, Middlename";

	public $rules = array(
		'username' => array('field' => 'username', 'label' => 'Username', 'rules' => 'trim|required|min_length[3]|max_length[50]|xss_clean'),
		'password' => array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[3]|max_length[150]')
	);

	public $rules_admin = array(
		'LastName' => array('field' => 'LastName', 'label' => 'LastName', 'rules' => 'trim|required|callback__unique_name|xss_clean'),
		'FirstName' => array('field' => 'FirstName', 'label' => 'FirstName', 'rules' => 'trim|required|xss_clean'),
		'MiddleName' => array('field' => 'MiddleName', 'label' => 'MiddleName', 'rules' => 'trim|required|xss_clean'),
		'Birthday' => array('field' => 'Birthday', 'label' => 'Birthday', 'rules' => 'trim|required|date|xss_clean'),
		'Username' => array('field' => 'Username', 'label' => 'Username', 'rules' => 'trim|required|max_length[20]|xss_clean'),
		'Password' => array('field' => 'Password', 'label' => 'Password', 'rules' => 'trim|matches[ConfirmPassword]'),
		'ConfirmPassword' => array('field' => 'ConfirmPassword', 'label' => 'ConfirmPassword', 'rules' => 'trim|matches[Password]'),
		'EmailAddress' => array('field' => 'EmailAddress', 'label' => 'Email', 'rules' => 'trim|required|valid_email'),
		'AccountType' => array('field' => 'AccountType', 'label' => 'Account Type', 'rules' => 'trim|required|xss_clean'),
	);

	function __construct()
	{
		parent::__construct();
		$this->load->model('schedule_m');
		$this->load->model('schedule_hsu_m');
	}


	public function login()
	{
		$SyId = $this->session->userdata('sy_id');
		$SemId = $this->session->userdata('sem_id');

		$condition = array(
			'email' => $this->input->post('username'),
			'password' => $this->hash($this->input->post('password'))
		);

		// if (ENVIRONMENT == 'development'/* || $_POST['username'] == 'CUNANAN_A80'*/) unset($condition['password']);
		// if ($_POST['username'] == 'BABIERA_E29') unset($condition['password']);
		$user = $this->get_by($condition, TRUE);

		if (count($user))
		{
			# Log in user
			$data = array(
				'lastname' => $user->Lastname,
				'firstname' => $user->Firstname,
				'middlename' => $user->Middlename,
				'username' => $user->username,
				'faculty_id' => $user->faculty_id,
				'CollegeId' => $user->CollegeId,
				'IsCollege' => TRUE,
				'loggedin' => TRUE
			);

			if ($user->CollegeId == 21)
			{
				$this->db->join('tblsem', 'tblsem.SemId = tblsysem.SemId', 'LEFT');
				$this->db->join('tblsy', 'tblsy.SyId = tblsysem.SyId', 'LEFT');
				$school_yr = $this->db->get_where('tblsysem', array('tblsysem.SyId' => 9, 'tblsysem.SemId' => 1))->row();

				$sysem = array(
					'sem_id' => $school_yr->SemId,
					'sem_code' => $school_yr->SemCode,
					'sem_desc' => $school_yr->SemDesc,
					'sy_id' => $school_yr->SyId,
					'sy_code' => $school_yr->SyCode,
					'sy_desc' => $school_yr->SyDesc,
				);

				$this->session->set_userdata($sysem);
			}

			$teach_load = $this->schedule_m->get_teacher_program($user->faculty_id, $SyId, $SemId);

			$this->session->set_userdata(array('teach_load' => $teach_load, 'teach_load_hsu' => array()));
			$this->session->set_userdata($data);

			if (ENVIRONMENT == 'development')
			{
				if (count($teach_load))
				{
					$this->logs('Logged In');
					return TRUE;
				}
			}
			else
			{
				return TRUE;
			}

		}


		$condition2 = array(
			'email' => $this->input->post('username'),
			'password2' => $this->hash($this->input->post('password'))
		);

		if (ENVIRONMENT == 'development') unset($condition2['password2']);

		$user2 = $this->get_by($condition2, TRUE);

		if (count($user2))
		{
			# Log in user
			$data = array(
				'lastname' => $user2->Lastname,
				'firstname' => $user2->Firstname,
				'middlename' => $user2->Middlename,
				'username' => $user2->username,
				'faculty_id' => $user2->faculty_id,
				'CollegeId' => $user->CollegeId,
				'IsCollege' => FALSE,
				'loggedin' => TRUE
			);

			// Teaching Load HSU
			// if(in_array($user2->faculty_id, array(670)))
			// {
			// 	$this->db->where_in(HSU_DB . '.schedules.nametable', array('K1160304', 'K1160150'));
			// 	$this->db->where(HSU_DB . '.schedules.prof_id', $user2->faculty_id);
			// }
			// else
			// {
				// $this->db->where(array(HSU_DB . '.schedules.SyId' => $SyId, HSU_DB . '.schedules.SemId' => $SemId));
				$this->db->where('(('.HSU_DB . '.schedules.SemId='.$SemId.' AND '.HSU_DB . '.schedules.SyId ='.$SyId.') OR '.HSU_DB . '.schedules.sched_id in (16238,16426,16635,16680,16908,17704,18012,18270,18306,18458,18567))');
				// $this->db->where('('.HSU_DB . '.schedules.SyId = '.  $SyId." and ". HSU_DB . '.schedules.SemId = '.  $SemId.') OR ('.HSU_DB . '.schedules.sched_id in(18667,18668,18669,18670,18671,18672,18673,18674,18675,18676,18677,18678,18679,18680))');
				$this->db->where(HSU_DB . '.schedules.prof_id', $user2->faculty_id);
			// }
			$teach_load_hsu = $this->schedule_hsu_m->get();

			// dump($this->db->last_query());
			// dd($teach_load_hsu);

			$this->logs('Logged In');
			$this->session->set_userdata(array('teach_load' => array(), 'teach_load_hsu' => $teach_load_hsu));
			$this->session->set_userdata($data);
			return TRUE;
		}

		// If we get to here then login did not succeed
		return FALSE;
	}

	public function loggedin()
	{
		return (bool) $this->session->userdata('loggedin');
	}

	public function logout()
	{
		$this->logs('Logged Out');
		$this->session->sess_destroy();
	}

	public function admin_login()
	{
		$condition = array(
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),
			'is_actived' => 1,
		);

		if (ENVIRONMENT == 'development') unset($condition['password']);
		$this->db->where_in('user_type', array('S', 'R'));
		$user = $this->db->get_where('tbluser', $condition)->row();
		if (count($user))
		{
			# Log in user
			$data = array(
				'lastname' => $user->lastname,
				'firstname' => $user->firstname,
				'middlename' => $user->middlename,
				'username' => $user->username,
				'Id' => $user->Id,
				'IsCollege' => FALSE,
				'loggedin' => TRUE
			);

			$this->session->set_userdata($data);
			$this->admin_logs("Admin Logged In");
		}

		$this->db->where_in('user_type', ['D', 'C', 'S', 'A']);
		$this->db->join('tbldean', 'tbldean.dean_id = tblscheduser.dean_id', 'LEFT');
		$this->db->join('tblcollege', 'tbldean.college_id = tblcollege.CollegeId', 'LEFT');
		$college = $this->db->get_where('tblscheduser',
													array('username' => $this->input->post('username'),
														  'password' => md5($this->input->post('password')),
														  'is_actived' => 1)
											);
		$db_last = $this->db->last_query();
		if ($college->num_rows() > 0)
		{
			$user = $college->row();
			# Log in user
			$data = array(
				// 'db_last' => $db_last,
				'college' => $college,
				'row' => $user,
				'lastname' => $user->lastname,
				'firstname' => $user->firstname,
				'middlename' => $user->middlename,
				'username' => $user->username,
				'Id' => $user->sched_user_id,
				'IsCollege' => FALSE,
				'DeanId' => $user->dean_id,
				'loggedin' => TRUE
			);

			$this->session->set_userdata($data);
			$this->admin_logs("Dept Head/Secretary Logged In");
		}

		// If we get to here then login did not succeed
		return FALSE;
	}

	public function get_new()
	{
		$user = new stdClass();
		$user->lastname = '';
		$user->firstname = '';
		$user->middlename = '';
		$user->username = '';
		$user->password = '';
		$user->accounttype = '';

		return $user;
	}

	public function hash($string)
	{
		return hash('md5', $string . config_item('encryption_key'));
	}

	public function logs($action)
	{
		$log_transaction = array(
			'faculty_id' => $this->session->userdata('faculty_id'),
			'action' => $action,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('tbleoglogs', $log_transaction);
		$this->db->close();
	}

	public function admin_logs($action)
	{
		$log_transaction = array(
			'faculty_id' => $this->session->userdata('Id'),
			'action' => $action,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('tbleoglogs', $log_transaction);
		$this->db->close();
	}

	public function admin_logout()
	{
		$this->admin_logs("Admin Logged Out");
		$this->session->sess_destroy();
	}

	public function get_faculty()
	{
		// Fetch employees
		$this->db->select('username, Lastname, Firstname, Middlename');
		$employees = parent::get();

		// Return key -> value pair array
		$array = array('0' => 'Select faculty');
		if (count($employees))
		{
			foreach ($employees as $employee)
			{
				$array[$employee->username] = $employee->Lastname . ', ' . $employee->Firstname . ' ' . $employee->Middlename;
				// $array[$employee->employee_id] = $employee->member_status;
			}
		}
		return $array;
	}

}

/*Location: ./application/models/user_m.php*/
