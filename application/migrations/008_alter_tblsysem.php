<?php
/**
* Name: Migration_Session
* Author: Brando Talaguit
* Date: 01/20/2013
*/
class Migration_Alter_tblsysem extends CI_Migration
{

	protected $tablename = 'tblsysem';

	public function up()
	{
		$sql = "ALTER TABLE `".$this->tablename."`
				ADD COLUMN `EogDateStart` DATE NOT NULL AFTER `AddChangeDateEnd`,
				ADD COLUMN `EogDateEnd` DATE NOT NULL AFTER `EogDateStart`,
				ADD COLUMN `EogTimeStart` TIME NOT NULL AFTER `EogDateEnd`,
				ADD COLUMN `EogTimeEnd` TIME NOT NULL AFTER `EogTimeStart`
				";
		$this->db->query($sql);

		$data = array(
			'EogTimeStart' => '07:00:00',
			'EogTimeEnd' => '22:00:00',
			'EogDateStart' => '2015-10-16',
			'EogDateEnd' => '2015-10-23'
		);
		$this->db->where('SyId', 5);
		$this->db->where('SemId', 1);
		$this->db->update('tblsysem', $data);
	}

	public function down()
	{
		$fields = array(
			'EogDateStart',
			'EogDateEnd',
			'EogTimeStart',
			'EogTimeEnd',
		);

		foreach ($fields as $field)
		{
			if ($this->db->field_exists($field, $this->tablename))
			$this->dbforge->drop_column($this->tablename, $field);
		}
	}
}
