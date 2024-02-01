<?php

class Notification_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($user_id, $text, $link, $object_id)
	{
		$this->db->insert('Notifications', array(
			'userid' => $user_id,
			'message' => $text,
			'link' => $link,
			'objectid' => $object_id,
			'timecreated' => time(),
			'timeread' => 0,
			'status' => 1
		));
	}

	function remove($notification_id)
	{
		$this->db->update('Notifications', array('status' => 0), array('notificationid' => $notification_id));
	}

	function mark_read($user_id)
	{
		$this->db->update('Notifications', array('timeread' => time()), array('userid' => $user_id, 'timeread' => 0));
	}

	function get_unread_count($user_id)
	{
		$query = $this->db->query('SELECT COUNT(*) as total FROM Notifications WHERE userid = ? AND timeread = 0 AND status = 1', array($user_id));

		return $query->row()->total;
	}

	function get_notifications($user_id, $limit = 0)
	{
		$limit = $limit != 0 ? ' LIMIT '.$limit : '';

		$query = $this->db->query('SELECT * FROM Notifications WHERE userid = ? AND status = 1 ORDER BY timecreated DESC'.$limit, array($user_id));

		return $query->result();
	}

	function remove_by_object($object, $object_id)
	{
		$this->db->delete('Notifications', array('objectid' => $object.'/'.$object_id));
	}
}