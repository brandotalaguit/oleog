<?php
/**
* Name: Migration_Session
* Author: Brando Talaguit
* Date: 01/20/2013
*/
class Migration_Alter_sched extends CI_Migration
{

	protected $tablename = 'tblsched';

	public function up()
	{
		$sql = "ALTER TABLE `".$this->tablename."`
				ADD COLUMN `IsAllowed` BOOLEAN AFTER `leclab`,
				ADD COLUMN `is_actived` BOOLEAN NOT NULL default 1 AFTER `IsAllowed`,
				ADD COLUMN `created_at` DATETIME AFTER `is_actived`,
				ADD COLUMN `updated_at` DATETIME AFTER `created_at`,
				ADD COLUMN `deleted_at` DATETIME AFTER `updated_at`;
				";

		$this->db->query($sql);
	}

	public function down()
	{
		$fields = array(
			'IsAllowed',
			'is_actived',
			'created_at',
			'updated_at',
			'deleted_at',
		);

		foreach ($fields as $field)
		{
			if ($this->db->field_exists($field, $this->tablename))
			$this->dbforge->drop_column($this->tablename, $field);
		}
	}
}
