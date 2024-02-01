<?php

class Shoutout_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($user_id, $to, $content)
	{
		$this->db->insert('Shoutouts', array(
			'fromuserid' => $user_id,
			'touserid' => $to,
			'content' => $content,
			'timecreated' => time(),
			'status' => '1'
		));

		return $this->db->insert_id();
	}

	function get_to_user($user_id)
	{
		$query = $this->db->query('SELECT * FROM Shoutouts WHERE touserid = ?', array($user_id));
		return $query->result();
	}

	function get_with_user($user_id, $limit = 0, $max_id = PHP_INT_MAX)
	{
		$query_limit = ($limit > 0) ? ' LIMIT '.$limit : '';

		$query = $this->db->query('SELECT Shoutouts.*, f.firstname as fromfirstname, f.lastname as fromlastname, f.profileimage, t.firstname as tofirstname, t.lastname as tolastname FROM Shoutouts, Users f, Users t WHERE shoutoutid < ? AND t.userid = touserid AND f.userid = fromuserid AND (touserid = ? OR fromuserid = ?) ORDER BY timecreated DESC'.$query_limit, array($max_id, $user_id, $user_id));
		return $query->result();
	}

	function get_with_friend($user_id, $limit = 0, $max_id = PHP_INT_MAX)
	{
		$query_limit = ($limit > 0) ? ' LIMIT '.$limit : '';

		$query = $this->db->query('SELECT DISTINCT Shoutouts.*, f.firstname as fromfirstname, f.lastname as fromlastname, f.profileimage, t.firstname as tofirstname, t.lastname as tolastname FROM Shoutouts, Users f, Users t, UserGroup me, UserGroup friend WHERE me.groupid = friend.groupid AND shoutoutid < ? AND t.userid = touserid AND f.userid = fromuserid AND me.userid = ? AND (touserid = friend.userid OR fromuserid = friend.userid) AND fromuserid != ? AND touserid != ? ORDER BY timecreated DESC'.$query_limit, array($max_id, $user_id, $user_id, $user_id));
		return $query->result();
	}


	function get_count_to_user($user_id)
	{
		$query = $this->db->query('SELECT COUNT(*) as total FROM Shoutouts WHERE touserid = ?', array($user_id));
		return $query->row()->total;
	}

	function get_with_school_today($school_id, $limit = 0, $max_id = PHP_INT_MAX)
	{
		$query_limit = ($limit > 0) ? ' LIMIT '.$limit : '';

		$today = strtotime(date('Y-m-d 00:00:00'));

		$query = $this->db->query('SELECT DISTINCT Shoutouts.*, f.profileimage as fromprofileimage, f.firstname as fromfirstname, f.lastname as fromlastname, t.firstname as tofirstname, t.lastname as tolastname FROM Shoutouts, Users f, Users t, UserGroup, Groups WHERE Groups.schoolid = ? AND Groups.groupid = UserGroup.groupid AND (touserid = UserGroup.userid OR fromuserid = UserGroup.userid) AND shoutoutid < ? AND t.userid = touserid AND f.userid = fromuserid AND Shoutouts.timecreated >= ? ORDER BY timecreated DESC'.$query_limit, array($school_id, $max_id, $today));
		return $query->result();
	}

	function remove_with_user($user_id)
	{
		$this->db->where('fromuserid', $user_id);
		$this->db->or_where('touserid', $user_id); 
		$this->db->update('Shoutouts', array('status' => 0));
	}		
}

?>