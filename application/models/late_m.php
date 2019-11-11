<?php
/**
* Filename: schedule_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class Late_M extends MY_Model
{
	protected $table_name = "tbleoglogs_lates";
	protected $primary_key = "eoglog_late_id";
	protected $order_by = "eoglog_late_id";

	protected $protected_attribute = array('eoglog_late_id');

	


}

/*Location: ./application/models/schedule_m.php*/
