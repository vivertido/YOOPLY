<?php

class Onboard_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($password)
	{
		$invite_code = UUID::v4();

		$nonce = md5(time().rand(0, 5000000));

		$verification_code = md5($password.$nonce);

		$this->db->insert('Onboard', array(
			'invitecode' => $invite_code,
			'nonce' => $nonce,
			'verificationcode' => $verification_code,
			'status' => 1
		));

		return $invite_code;
	}

	function get_invite($invite_code)
	{
		$query = $this->db->query('SELECT * FROM Onboard WHERE invitecode = ?', array($invite_code));
		return $query->row();
	}

	function save_info($invite_code, $info)
	{
		$this->db->update('Onboard', array(
			'info' => json_encode($info)
		), array('invitecode' => $invite_code));
	}

	function save_stats($invite_code, $stats)
	{
		$this->db->update('Onboard', array(
			'stats' => json_encode($stats)
		), array('invitecode' => $invite_code));
	}	

	function save_features($invite_code, $features)
	{
		$this->db->update('Onboard', array(
			'features' => json_encode($features)
		), array('invitecode' => $invite_code));
	}	
}
?>