<?php

define('GOAL_STATUS_REMOVED', 0);
define('GOAL_STATUS_ACTIVE', 1);
define('GOAL_STATUS_COMPLETED', 2);
define('GOAL_STATUS_DISMISSED', 3);

class Goal_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}	

	function create($school_id, $teacher_id, $student_id, $object_type, $object_id, $title, $details, $time_due)
	{
		$this->db->insert('Goals', array(
			'schoolid' => $school_id,
			'teacherid' => $teacher_id,
			'studentid' => $student_id,
			'object' => $object_type,
			'objectid' => $object_id,
			'timedue' => $time_due,
			'title' => $title,
			'details' => json_encode($details),
			'timecreated' => time(),
			'status' => GOAL_STATUS_ACTIVE
		));

		return $this->db->insert_id();
	}

	function update($goal_id, $details, $status, $time_completed)
	{
		$this->db->update('Goals', array(
			'details' => json_encode($details),
			'status' => $status,
			'timecompleted' => $time_completed
			), array('goalid' => $goal_id));
	}

	function find($school_id, $teacher_id, $student_id, $time_start = 0, $time_end = 0, $expand_teacher = false, $expand_student = false)
	{
		$where = 'Goals.schoolid = ? AND Goals.status != 0';
		$where_values = array($school_id);
		$select = 'Goals.*';
		$from = 'Goals';

		if($teacher_id !== 0)
		{
			$where .= ' AND Goals.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Goals.studentid = ?';
			array_push($where_values, $student_id);
		}

		if($time_start !== 0)
		{
			$where .= ' AND Goals.timedue >= ?';
			array_push($where_values, $time_start);
		}				

		if($time_end !== 0)
		{
			$where .= ' AND Goals.timedue <= ?';
			array_push($where_values, $time_end);
		}						

		if($expand_teacher)
		{
			$select .= ', t.firstname as teacherfirstname, t.lastname as teacherlastname';
			$from .= ', Users t';
			$where .= ' AND t.userid = Goals.teacherid';
		}

		if($expand_student)
		{
			$select .= ', s.firstname as studentfirstname, s.lastname as studentlastname';
			$from .= ', Users s';
			$where .= ' AND s.userid = Goals.studentid';
		}

		$query = $this->db->query('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY timedue DESC, status', $where_values);
		return $query->result();
	}

	function remove($goal_id)
	{
		$this->db->update('Goals', array('status' => GOAL_STATUS_REMOVED), array('goalid' => $goal_id));
	}

	function remove_with_user($user_id)
	{
		$this->db->where('teacherid', $user_id);
		$this->db->or_where('studentid', $user_id); 
		$this->db->update('Goals', array('status' => GOAL_STATUS_REMOVED));
	}		

	function get_goal($goal_id)
	{
		$query = $this->db->query('SELECT * FROM Goals WHERE goalid = ?', array($goal_id));
		return $query->row();
	}

	function get_active_goals($student_id, $object_type, $object_id = 0)
	{
		$where = 'studentid = ? AND object = ? AND status = ?';
		$where_values = array($student_id, $object_type, GOAL_STATUS_ACTIVE);

		if($object_id !== 0)
		{
			$where .= ' AND objectid = ?';
			array_push($where_values, $object_id);
		}

		$query = $this->db->query('SELECT * FROM Goals WHERE '.$where, $where_values);
		return $query->result();
	}

}

?>