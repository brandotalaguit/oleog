<?php 
/**
* Name: Migration_Session
* Author: Brando Talaguit
* Date: 01/20/2013
*/
class Migration_Create_eog_trans extends CI_Migration
{
	


	public function up()
	{
		$sql = "CREATE TABLE `tbleogtrans` (
				  `eog_trans_id` INT(11) NOT NULL AUTO_INCREMENT,
				  `sched_id` INT(11) NOT NULL,
				  `submitted_at` DATETIME NOT NULL,
				  `is_graded` TINYINT NOT NULL DEFAULT 0,
				  `created_at` DATETIME NOT NULL,
				  `updated_at` DATETIME NOT NULL,
				  `deleted_at` DATETIME NOT NULL,
				  `is_actived` TINYINT NOT NULL DEFAULT 1,
				  PRIMARY KEY (`eog_trans_id`) ,
				  INDEX `sched_id` (`sched_id` ASC))
				  ;
				";

		$this->db->query($sql);
	}

	public function down()
	{
		$this->dbforge->drop_table('tbleogtrans');
	}
}