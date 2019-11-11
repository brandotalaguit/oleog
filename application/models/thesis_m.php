<?php
/**
* Filename: thesis_m.php
* Author: Brando Talaguit (ITC Developer)
*/
class thesis_m extends MY_Model
{
	protected $table_name = "tblthesis";
	protected $primary_key = "id";
	protected $order_by = "id, sched_id";

	protected $protected_attribute = array('id');

	public $rules = array(
		'CourseId' => array('field' => 'CourseId', 'label' => 'Course Id', 'rules' => 'intval|is_natural_no_zero|xss_clean'),
	);

	
}

/*Location: ./application/models/thesis_m.php*/
