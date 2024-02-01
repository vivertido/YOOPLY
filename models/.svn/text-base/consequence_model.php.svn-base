<?php

class Consequence_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($incident_type, $incident_id, $assigned_by, $student_id, $title, $data, $progress = 'Pending')
	{
		$this->db->insert('Consequences', array(
			'incidenttype' => $incident_type,
			'incidentid' => $incident_id,
			'assignedby' => $assigned_by,
			'studentid' => $student_id,
			'timecreated' => time(),
			'timecompleted' => time(),
			'title' => $title,
			'data' => json_encode($data),
			'progress' => $progress,
			'status' => 1
		));

		return $this->db->insert_id();
	}

	function update_status($consequence_id, $title, $progress, $data)
	{
		$this->db->update('Consequences', array(
			'title' => $title,
			'progress' => $progress,
			'data' => json_encode($data)
		), array('consequenceid' => $consequence_id));
	}

	function remove($consequence_id)
	{
		$this->db->update('Consequences', array('status' => 0), array('consequenceid' => $consequence_id));
	}

	function get_consequence($consequence_id)
	{
		$query = $this->db->query('SELECT * FROM Consequences WHERE consequenceid = ?', array($consequence_id));
		return $query->row();
	}

	function find($school_id, $student_id = 0, $teacher_id = 0, $start_time = 0, $end_time = 0)
	{
		$where = '';
		$where_values = array();

		if($student_id !== 0)
		{
			$where .= 'studentid = ?';
			array_push($where_values, $student_id);
		}


		if($teacher_id !== 0)
		{
			$where .= 'teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($start_time !== 0)
		{
			$where .= 'timecreated >= ?';
			array_push($where_values, $start_time);
		}

		if($end_time !== 0)
		{
			$where .= 'timecreated <= ?';
			array_push($where_values, $end_time);
		}		

		$query = $this->db->query('SELECT * FROM Consequences WHERE status = 1'.$where, $where_values);
		return $query->result();
	}

	function get_by_incident($incident_type, $incident_id)
	{
		$query = $this->db->query('SELECT * FROM Consequences WHERE incidenttype = ? AND incidentid = ? AND status = 1', array($incident_type, $incident_id));

		return $query->result();
	}
}