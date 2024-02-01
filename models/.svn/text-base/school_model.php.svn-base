<?php

class School_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($title, $meta_data, $domain)
	{
		$this->db->insert('Schools', array(
			'title' => $title,
			'metadata' => json_encode($meta_data),
			'domain' => $domain,
			'timecreated' => time()
		));

		return $this->db->insert_id();
	}

	function get_school($school_id)
	{
		$query = $this->db->query('SELECT * FROM Schools WHERE schoolid = ?', array($school_id));

		return $query->row();
	}

	function find_school_by_domain($domain)
	{
		$query = $this->db->query('SELECT * FROM Schools WHERE domain = ?', array($domain));
		return $query->row();
	}

	function find_by_clever($clever_school_id)
	{
		$this->db->like('metadata', $clever_school_id);
		$query = $this->db->get('Schools');
	
		return $query->row();
	}

	function has_student($school_id, $student_id)
	{
		$query = $this->db->query('SELECT userid FROM Users WHERE accounttype = "s" AND userid = ? AND schoolid = ?', array($student_id, $school_id));
		$row = $query->row();

		return !empty($row);
	}

	function has_admin($school_id, $user_id)
	{
		$query = $this->db->query('SELECT userid FROM UserSchool WHERE schoolid = ? AND userid = ? AND status = "1"', array($school_id, $user_id));
		$user = $query->row();

		return !empty($user);
	}

	function add_admin($school_id, $user_id)
	{
		$this->db->insert('UserSchool', array(
			'schoolid' => $school_id,
			'userid' => $user_id,
			'role' => 'a',
			'status' => '1'
		));
	}

	function remove_admin($school_id, $user_id)
	{
		$this->db->update('UserSchool', array(
			'status' => '0'
		), array('userid' => $user_id));
	}

	function has_teacher($school_id, $teacher_id)
	{
		$query = $this->db->query('SELECT userid FROM Users WHERE accounttype = "t" AND userid = ? AND schoolid = ?', array($teacher_id, $school_id));
		$user = $query->row();

		return !empty($user);
	}

	function has_user($school_id, $user_id)
	{
		$query = $this->db->query('SELECT userid FROM Users WHERE userid = ? AND schoolid = ?', array($user_id, $school_id));
		$user = $query->row();

		return !empty($user);
	}

	function update_metadata($school_id, $meta_data)
	{
		$this->db->update('Schools', array('metadata' => json_encode($meta_data)), array('schoolid' => $school_id));
	}
}

?>