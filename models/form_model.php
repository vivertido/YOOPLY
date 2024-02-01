<?php

class Form_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $title, $viewers, $contributors, $subject, $form_data, $actions, $index_title, $time_title)
	{
		$this->db->insert('Forms', array(
			'schoolid' => $school_id,
			'viewers' => $viewers,
			'contributors' => $contributors,
			'subject' => $subject,
			'title' => $title,
			'formdata' => json_encode($form_data),
			'actions' => json_encode($actions),
			'indextitle' => $index_title,
			'timetitle' => $time_title,
			'status' => 1
		));

		return $this->db->insert_id();
	}

	function update($form_id, $title, $viewers, $contributors, $subject, $form_data, $actions, $index_title, $time_title)
	{
		$this->db->update('Forms', array(
			'viewers' => $viewers,
			'contributors' => $contributors,
			'subject' => $subject,
			'title' => $title,
			'formdata' => json_encode($form_data),
			'actions' => json_encode($actions),
			'indextitle' => $index_title,
			'timetitle' => $time_title,
		), array('formid' => $form_id));
	}

	function set_actions($form_id, $actions)
	{
		$this->db->update('Forms', array(
			'actions' => json_encode($actions),
		), array('formid' => $form_id));		
	}

	function remove($form_id)
	{
		$this->db->update('Forms', array(
			'status' => 0
		), array('formid' => $form_id));
	}

	function get_by_school($school_id)
	{
		$query = $this->db->query('SELECT * FROM Forms WHERE schoolid = ? AND status = 1', array($school_id));
		return $query->result();
	}

	function get_form($form_id)
	{
		$query = $this->db->query('SELECT * FROM Forms WHERE formid = ?', array($form_id));
		return $query->row();
	}

	function get_assignable($school_id, $role, $subject)
	{
		$query = $this->db->query('SELECT formid, title FROM Forms WHERE schoolid = ? AND INSTR(subject, ?) != 0 AND INSTR(contributors, ?) != 0 AND Forms.status = 1 ORDER BY title', array($school_id, $subject, $role));

		return $query->result();
	}

	function get_viewable($school_id, $role, $subject = '')
	{
		$where = 'schoolid = ? AND INSTR(viewers, ?) != 0 AND Forms.status = ?';
		$where_values = array($school_id, $role, 1);

		if(!empty($subject))
		{
			$where .= ' AND INSTR(subject, ?) != 0';
			array_push($where_values, $subject);
		}

		$query = $this->db->query('SELECT formid, title FROM Forms WHERE '.$where.' ORDER BY title', $where_values);

		return $query->result();
	}
}
?>