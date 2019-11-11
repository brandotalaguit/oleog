<?php
/**
* Name: Migration_Session
* Author: Brando Talaguit
* Date: 01/20/2013
*/
class Migration_Alter_faculty_display extends CI_Migration
{

	protected $tablename = 'tblfacultydisplay';

	public function up()
	{
		$sql = "ALTER TABLE `".$this->tablename."`
				ADD COLUMN `Title` VARCHAR(45) NOT NULL AFTER `TitleId`;";
		$this->db->query($sql);
	}

	public function down()
	{
		$fields = array(
			'Title',
		);

		foreach ($fields as $field)
		{
			if ($this->db->field_exists($field, $this->tablename))
			$this->dbforge->drop_column($this->tablename, $field);
		}
	}
}
