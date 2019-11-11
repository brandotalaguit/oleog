<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{

	public $data = array();

	function __construct()
	{
		parent::__construct();
		$this->data['errors'] = array();
		$this->data['site_name'] = config_item('site_title');
	}

	public function load_view($page)
	{
		$this->data['content'] = $page;
		$this->load->view('layout/default_template', $this->data);
	}

	public function load_error($msg, $page = 'faculty', $error = TRUE)
	{
		if ($error == TRUE)
		{
			$this->session->set_flashdata('error', $msg);
			$this->session->set_flashdata('success', NULL);
		}
		else
		{
			$this->session->set_flashdata('error', NULL);
			$this->session->set_flashdata('success', $msg);
		}
		redirect(base_url($page), 'location', 302);
	}

}

/* End of file Admin_Controller.php */
/* Location: ./application/controllers/site.php */