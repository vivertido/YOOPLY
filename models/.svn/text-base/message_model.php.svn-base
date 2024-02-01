<?php
class Message_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($from_user, $to_user, $body)
	{
		$this->db->insert('Messages', array(
			'fromuserid' => $from_user,
			'touserid' => $to_user,
			'body' => $body,
			'timecreated' => time(),
			'timeread' => 0,
			'status' => '1'
		));
	}

	function get_messages($user_id)
	{
		$query = $this->db->query('SELECT Messages.*, Users.firstname, Users.lastname FROM Messages, Users WHERE Messages.fromuserid = Users.userid AND touserid = ? AND status != 0 ORDER BY timecreated DESC');

		return $query->result();
	}
}
?>