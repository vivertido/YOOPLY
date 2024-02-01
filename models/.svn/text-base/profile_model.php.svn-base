<?php
class Profile_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($user_id, $source, $key, $meta_data)
	{
		$this->db->insert('Profiles', array(
			'userid' => $user_id,
			'source' => $source,
			'profilekey' => $key,
			'metadata' => json_encode($meta_data),
			'timecreated' => time()
		));
	}

	function find_by_key($key)
	{
		$query = $this->db->query('SELECT * FROM Profiles WHERE profilekey = ?', array($key));

		return $query->row();
	}

	function create_clever_user($user_id, $clever_user_id, $meta_data)
	{
		$this->db->insert('Profiles', array(
			'userid' => $user_id,
			'source' => 'clever',
			'profilekey' => 'user_'.$clever_user_id,
			'metadata' => json_encode($meta_data),
			'timecreated' => time()
		));
	}

	function find_clever_user($clever_user_id)
	{
		$source = 'clever';
		$key = 'user_'.$clever_user_id;

		$query = $this->db->query('SELECT * FROM Profiles WHERE source = ? AND profilekey = ?', array($source, $key));

		return $query->row();
	}

	function find_google_user($google_user_id)
	{
		$source = 'google';
		$key = 'user_'.$google_user_id;

		$query = $this->db->query('SELECT * FROM Profiles WHERE source = ? AND profilekey = ?', array($source, $key));

		return $query->row();
	}

	function create_google_user($user_id, $google_user_id, $meta_data)
	{
		$this->db->insert('Profiles', array(
			'userid' => $user_id,
			'source' => 'google',
			'profilekey' => 'user_'.$google_user_id,
			'metadata' => json_encode($meta_data),
			'timecreated' => time()
		));
	}

	function save_notification($user_id, $options)
	{
		$this->db->insert('Profiles', array(
			'userid' => $user_id,
			'source' => 'notifications',
			'profilekey' => 'notify'.$user_id,
			'metadata' => json_encode($options),
			'timecreated' => time()
		));
	}

	function get_notifications($user_id)
	{
		$query = $this->db->query('SELECT * FROM Profiles WHERE userid = ? AND source = "notifications" ORDER BY timecreated DESC LIMIT 1', array($user_id));

		$row = $query->row();

		if(empty($row))
		{
			$o = new stdClass();
			$o->sms = '0';

			return $o;
		}
		else
		{
			return json_decode($row->metadata);
		}
	}
}
?>