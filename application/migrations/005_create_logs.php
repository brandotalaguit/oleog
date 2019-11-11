<?php 
/**
* Name: Migration_Session
* Author: Brando Talaguit
* Date: 01/20/2013
*/
class Migration_Create_logs extends CI_Migration
{
	


	public function up()
	{
		$sql = "CREATE TABLE `tbleoglogs` (
				  `id` INT(11) NOT NULL AUTO_INCREMENT,
				  `faculty_id` INT(11) NOT NULL,
				  `action` VARCHAR(80) NOT NULL,
				  `created_at` DATETIME NOT NULL,
				  `updated_at` DATETIME NOT NULL,
				  `deleted_at` DATETIME NOT NULL,
				  `is_actived` TINYINT NOT NULL DEFAULT 1,
				  PRIMARY KEY (`id`) ,
				  INDEX `faculty_id` (`faculty_id` ASC))
				  ;
				";

		$this->db->query($sql);
	}

	public function down()
	{
		$this->dbforge->drop_table('tbleoglogs');
	}
}