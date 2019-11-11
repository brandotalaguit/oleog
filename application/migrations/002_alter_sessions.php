<?php 
/**
* Name: Migration_Session
* Author: Brando Talaguit
* Date: 01/20/2013
*/
class Migration_Alter_sessions extends CI_Migration
{
	
	protected $tablename = 'tblfacultydisplay';

	public function up()
	{	
		$sql = "ALTER TABLE `tblfacultydisplay` 
				CHARACTER SET = utf8 , COLLATE = utf8_general_ci , ENGINE = InnoDB ,
				ADD COLUMN `username` VARCHAR(50) NOT NULL COMMENT '' AFTER `faculty_id`,
				ADD COLUMN `password` VARCHAR(200) NOT NULL COMMENT '' AFTER `username`,
				ADD COLUMN `is_actived` BOOLEAN NOT NULL default 1 AFTER `CollegeId`,
				ADD COLUMN `created_at` DATETIME AFTER `is_actived`,
				ADD COLUMN `updated_at` DATETIME AFTER `created_at`,
				ADD COLUMN `deleted_at` DATETIME AFTER `updated_at`;
				";

		$this->db->query($sql);
	}

	public function down()
	{
		$fields = array(
			'username',
			'password',
			'is_actived',
			'created_at',
			'updated_at',
			'deleted_at',
			'is_active',
		);

		foreach ($fields as $field) 
		{
			if ($this->db->field_exists($field, $this->tablename))
			$this->dbforge->drop_column($this->tablename, $field);
		}
	}
}