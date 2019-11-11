<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('migration');
		$this->load->dbforge();
	}

	public function index()
	{

		if (! $this->migration->current()) 
		{
			show_error($this->migration->error_string());
		}
		else
		{
			/**
			 * Note: Migration version of config\migration.php must be the same on the migration version (MYSQL TABLE)
			 * 		if error persist try to FULL backup DB then removed the migration table
			 * 		then set the migration version from config folder.
			 */
			// var_dump($this->db->last_query());
			echo "Migration worked!<br>";
			echo anchor(site_url('dashboard'), '<strong>Back to dashboard</strong>');
		}

	}

}

/* End of file migration.php */
/* Location: ./application/controllers/migration.php */