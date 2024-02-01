<?php
class Hallpass_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $teacher_id, $student_id, $reason, $notes)
	{
		$this->db->insert('Hallpasses', array(
			'schoolid' => $school_id,
			'teacherid' => $teacher_id,
			'studentid' => $student_id,
			'reason' => $reason,
			'notes' => $notes,
			'timecreated' => time()
		));
	}

	function get_hallpass_total($school_id, $student_id)
	{
		$query = $this->db->query('SELECT COUNT(*) FROM Hallpasses WHERE schoolid = ? AND studentid = ?', array($school_id, $student_id));

		$count = $query->row()->total;

		return is_null($count) ? 0 : $count;
	}

	function get_by_student($school_id, $teacher_id, $student_id)
	{
		$query = $this->db->query('SELECT * FROM Hallpasses WHERE schoolid = ? AND teacherid = ? AND studentid = ?', array($school_id, $teacher_id, $student_id));

		return $query->result();
	}

	function get_hallpass($hallpass_id)
	{
		$query = $this->db->query('SELECT * FROM Hallpasses WHERE hallpassid = ?', array($hallpass_id));

		return $query->row();
	}

	function remove_with_user($user_id)
	{
		$this->db->where('teacher', $user_id);
		$this->db->or_where('studentid', $user_id); 
		//$this->db->update('Hallpasses', array('status' => 0));
	}	

}
?>