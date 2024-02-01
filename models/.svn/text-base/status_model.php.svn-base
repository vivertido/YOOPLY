<?php
class Status_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $teacher_id, $student_id, $value, $notes)
	{
		$this->db->insert('Statuses', array(
			'schoolid' => $school_id,
			'value' => $value,
			'teacherid' => $teacher_id,
			'studentid' => $student_id,
			'notes' => $notes,
			'status' => '1',
			'timecreated' => time()
		));

		return $this->db->insert_id();
	}

	function get_last_status($school_id, $student_id, $teacher_id = 0)
	{
		$this->db->select('*');
		$this->db->from('Statuses');
		$this->db->where(array('schoolid' => $school_id, 'studentid' => $student_id));

		if($teacher_id != 0)
		{
			$this->db->where('teacherid', $teacher_id);
		}

		$this->db->order_by('timecreated DESC');
		$this->db->limit('1');

		$query = $this->db->get();

		return $query->row();
	}

	function get_status($status_id)
	{
		$query = $this->get_where('Statuses', array('statusid' => $status_id));
		return $query->row();
	}

	function remove($status_id)
	{
		$this->db->update('Statuses', array('status' => '0'), array('statusid' => $status_id));
	}

	function remove_with_user($user_id)
	{
		$this->db->where('teacherid', $user_id);
		$this->db->or_where('studentid', $user_id); 
		$this->db->update('Statuses', array('status' => 0));
	}		
}
?>