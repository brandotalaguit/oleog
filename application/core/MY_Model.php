<?php 
/**
* Filename: Itc_model.php
* Author: Brando Talaguit (ITC Developer)
*/
class MY_Model extends CI_Model
{
	protected $table_name = "";
	protected $primary_key = "Id";
	protected $primary_filter = "intval";
	protected $order_by = "";

	protected $timestamps = TRUE;
	protected $soft_delete = TRUE;

	protected $protected_attribute = array();

	public $rules = array();

	function __construct()
	{
		parent::__construct();
		$this->load->library('user_agent');
	}

	public function is_soft_delete()
	{
		return $this->soft_delete;
	}

	public function array_from_object($fields)
	{
		$data = array();
		foreach ($fields as $key => $value) 
		{
			$data[$key] = $value;
		}

		return $data;
	}

	public function array_from_post($fields)
	{
		$data = array();
		foreach ($fields as $field) 
		{
			$data[$field] = $this->input->post($field);
		}

		return $data;
	}

	public function get($id = NULL, $single = FALSE)
	{
		if ($id != NULL) 
		{
			$filter = $this->primary_filter;
			$id = $filter($id);
			$this->db->where("$this->table_name"."."."$this->primary_key", $id);
			$method = 'row';
		}
		elseif ($single == TRUE) 
		{
			$method = 'row';
		}
		else
		{
			$method = 'result';
		}

		if (!count($this->db->ar_orderby)) 
		{
			$this->db->order_by($this->order_by);
		}

		$this->db->where("$this->table_name.is_actived", 1);

		return $this->db->get($this->table_name)->$method();
	}

	public function get_by($where, $single = FALSE)
	{
		$this->db->where($where);
		
		return $this->get(NULL, $single);
	}

	public function count($where = NULL)
	{
		$this->db->from($this->table_name);

		if (isset($where))
		$this->db->where($where);

		$this->db->where('is_actived', 1);

		return $this->db->count_all_results();
	}

	public function protect_attribute($post)
	{
		if (is_array($post)) 
		{
			foreach ($this->protected_attribute as $attr) 
			{
				if (in_array($attr, $this->protected_attribute)) 
					unset($post[$attr]);
			}
		}

		return $post;
	}

	public function save($post, $id = NULL)
	{

		$post = $this->protect_attribute($post);

		# set timestamps
		if ($this->timestamps == TRUE) 
		{
			$now = date('Y-m-d H:i:s');
			$id || $post['created_at'] = $now;
			$post['updated_at'] = $now;
		}



		if ($id === NULL) 
		{
			!isset($post[$this->primary_key]) || $post[$this->primary_key] = NULL;

			# This is an insert
			$this->db->insert($this->table_name, $post);
			$id = $this->db->insert_id();
			
			// log
			// $msg = "Record Id $id has been successfully saved.";
		}
		else
		{
			$filter = $this->primary_filter;
			
			# This is an update
			$this->db->where($this->primary_key, $filter($id))->update($this->table_name, $post);
			
			// log
			// $msg = "Record Id $id has been successfully updated.";
		}

		// flash data
		$this->db->flush_cache();
		// $this->session->set_flashdata('id', $id);
		// $this->session->set_flashdata('success', $msg);

		// Return the ID
		return $id === NULL ? $this->db->insert_id() : $id;
	}

	public function delete_by($where, $single = FALSE)
	{

		if (empty($where)) 
		{
			return FALSE;
		}

		$this->db->set('is_actived', 0);
		$this->db->set('deleted_at', date('Y-m-d H:i:s'));
		$this->db->where($where);

		if ($this->soft_delete === TRUE) 
		{
			$this->db->update($this->table_name);
			$this->db->flush_cache();
		}
		else
		{
			$this->session->set_flashdata('error', "Sorry this application can not perform a batch permanently deletion.<br/>If still want to continue you have to removed it individually.");
			$this->db->flush_cache();
			return FALSE;
		}
	}

	public function delete($id)
	{
		$filter = $this->primary_filter;
		$id = $filter($id);

		if (!$id) 
		{
			return FALSE;
		}

		
		$this->db->set('is_actived', 0);
		$this->db->set('deleted_at', date('Y-m-d H:i:s'));
		$this->db->where($this->primary_key, $id);
		$this->db->limit(1);

		if ($this->soft_delete === TRUE) 
		{
			
			$this->db->update($this->table_name);
			$this->db->flush_cache();

			// $this->session->set_flashdata('id', $id);
			// $this->session->set_flashdata('success', "Record id $id has been successfully deleted.");
		}
		else
		{
			// $this->session->set_flashdata('success', "Record id $id has been permanently deleted.");
			$this->db->flush_cache();

			// $this->session->set_flashdata('id', $id);
			$this->db->delete($this->table_name);
		}

	}


	/**
     * A method to facilitate easy bulk inserts into a given table.
     * @param string $table_name
     * @param array $column_names A basic array containing the column names
     *  of the data we'll be inserting
     * @param array $rows A two dimensional array of rows to insert into the
     *  database.
     * @param bool $escape Whether or not to escape data
     *  that will be inserted. Default = true.
     * @author Kenny Katzgrau <katzgrau@gmail.com>
     */

    public function insert_rows($column_names, $rows, $escape = true)
    {
        /* Build a list of column names */
        $columns    = array_walk($column_names, array($this, 'prepare_column_name') );
        $columns    = implode(',', $column_names);

        /* Escape each value of the array for insertion into the SQL string */
        if( $escape ) array_walk_recursive( $rows, array( $this, 'escape_value' ) );

        /* Collapse each rows of values into a single string */
        $length = count($rows);
        for($i = 0; $i < $length; $i++) $rows[$i] = implode(',', $rows[$i]);

        /* Collapse all the rows into something that looks like
         *  (r1_val_1, r1_val_2, ..., r1_val_n),
         *  (r2_val_1, r2_val_2, ..., r2_val_n),
         *  ...
         *  (rx_val_1, rx_val_2, ..., rx_val_n)
         * Stored in $values
         */
        $values = "(" . implode( '),(', $rows ) . ")";

        $sql = "INSERT INTO $this->table_name ( $columns ) VALUES $values";

        return $this->db->simple_query($sql);
    }

    function escape_value(& $value)
    {
        if( is_string($value) )
        {
            $value = "'" . mysql_real_escape_string($value) . "'";
        }
    }

    function prepare_column_name(& $name)
    {
        $name = "`$name`";
    }


}

/*Location: ./application/models/MY_Model.php*/
 ?>