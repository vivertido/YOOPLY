<?php
class Log_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function page_load($data)
	{
		$this->log(0, 'pageload', $data);
	}

	function error_load($data)
	{
		$this->log(0, 'errorload', $data);
	}

	function log($user_id, $type, $data)
	{
		$agent = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : '';
		$this->db->insert("Logs", array(
			'userid' => $user_id,
			'type' => $type,
			'timelogged' => time(),
			'report' => json_encode($data),
			'useragent' => json_encode(array('ip' => $_SERVER["REMOTE_ADDR"], 'agent' => $agent, 'cookie' => isset($_SERVER["HTTP_COOKIE"]) ? $_SERVER["HTTP_COOKIE"] : ''))
		));
	}

	function email_set($user_id, $email, $parent_id, $student_id, $report_id)
	{
		$this->log($user_id, 'emailsent', array(
			'email' => $email,
			'parentid' => $parent_id,
			'studentid' => $student_id,
			'reportid' => $report_id
		));
	}



}
?>