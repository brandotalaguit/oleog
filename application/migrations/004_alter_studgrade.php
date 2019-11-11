<?php
/**
* Name: 004_alter_studgrade.php
* Author: Brando Talaguit
* Date: 09/21/2015
*/
class Migration_Alter_studgrade extends CI_Migration
{

	protected $tablename = 'tblstudgrade';

	public function up()
	{
		$sql = "ALTER TABLE `".$this->tablename."`
				ADD COLUMN `Grade` VARCHAR(5) AFTER `nametable`,
				ADD COLUMN `LabGrade` VARCHAR(5) AFTER `StrGrade`,
				ADD COLUMN `enabled` BOOLEAN NOT NULL default 1 AFTER `Remarks`,
				ADD COLUMN `is_actived` BOOLEAN NOT NULL default 1 AFTER `strgrade2`,
				ADD COLUMN `created_at` DATETIME AFTER `is_actived`,
				ADD COLUMN `updated_at` DATETIME AFTER `created_at`,
				ADD COLUMN `deleted_at` DATETIME AFTER `updated_at`
				";

		$this->db->query($sql);
	}

	public function down()
	{
		$fields = array(
			'Grade',
			'LabGrade',
			'enabled',
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
