<?php

class Group_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $title, $meta_data)
	{
		$this->db->insert('Groups', array(
			'title' => $title,
			'schoolid' => $school_id,
			'metadata' => json_encode($meta_data),
			'timecreated' => time(),
			'status' => '1'
		));

		return $this->db->insert_id();
	}

	function get_groups_by_school($school_id)
	{
		$query = $this->db->query('SELECT * FROM Groups WHERE schoolid = ? AND status = 1 ORDER BY title', array($school_id));

		return $query->result();
	}

	function get_user_groups($teacher_id)
	{
		$query = $this->db->query('SELECT Groups.* FROM UserGroup, Groups WHERE UserGroup.status = ? AND UserGroup.userid = ? AND UserGroup.groupid = Groups.groupid AND Groups.status = 1', array(USERGROUP_STATUS_ACTIVE, $teacher_id));
		return $query->result();
	}

	function get_group($group_id)
	{
		$query = $this->db->query('SELECT * FROM Groups WHERE groupid = ?', array($group_id));

		return $query->row();
	}

	function has_user($group_id, $student_id)
	{
		$query = $this->db->query('SELECT * FROM UserGroup WHERE status = ? AND userid = ? AND groupid = ?', array(USERGROUP_STATUS_ACTIVE, $student_id, $group_id));

		$r = $query->row();

		return !empty($r);
	}

	function has_teacher($group_id, $teacher_id)
	{
		$query = $this->db->query('SELECT * FROM UserGroup WHERE status = ? AND userid = ? AND groupid = ? AND role = "t"', array(USERGROUP_STATUS_ACTIVE, $teacher_id, $group_id));

		$r = $query->row();

		return !empty($r);
	}

	function add_student($group_id, $student_id)
	{
		$this->db->insert('UserGroup', array(
			'userid' => $student_id,
			'groupid' => $group_id,
			'role' => 's',
			'status' => USERGROUP_STATUS_ACTIVE
		));
	}

	function remove_student($group_id, $student_id)
	{
		$this->db->update('UserGroup', array(
			'status' => USERGROUP_STATUS_REMOVED
		), array('userid' => $student_id,'groupid' => $group_id));
	}

	function remove_teacher($group_id, $teacher_id)
	{
		$this->db->update('UserGroup', array(
			'status' => USERGROUP_STATUS_REMOVED
		), array('userid' => $teacher_id,'groupid' => $group_id));
	}	

	function remove_with_user($user_id)
	{
		$this->db->where('userid', $user_id);
		$this->db->update('UserGroup', array('status' => USERGROUP_STATUS_REMOVED));
	}			

	function add_teacher($group_id, $teacher_id)
	{
		$this->db->insert('UserGroup', array(
			'userid' => $teacher_id,
			'groupid' => $group_id,
			'role' => 't',
			'status' => USERGROUP_STATUS_ACTIVE
		));
	}

	function get_clever_group($clever_group_id)
	{
		$query = $this->db->query('SELECT * FROM Groups WHERE metadata LIKE "%'.$clever_group_id.'%"');

		return $query->row();
	}

	function update($group_id, $title)
	{
		$this->db->update('Groups', array(
			'title' => $title
		), array('groupid' => $group_id));
	}
}

?>