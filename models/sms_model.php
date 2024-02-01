<?php
 
require "../libraries/Twilio.php";

class Sms_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function send($school_id, $to, $type, $data)
	{
		$query = $this->db->query("SELECT settings FROM Settings WHERE schoolid = ? AND type = ? ORDER BY timecreated DESC LIMIT 1", array($school_id, 'sms'));

		$messages = json_decode($query->row()->settings);

		if(!isset($messages->enabled) || !$messages->enabled)
		{
			return;
		}

		if(isset($messages->quota))
		{
			// Check that the school hasn't reached the max numebr of SMS messages in their plan.
			$sent = intval($this->count_sent($school_id));
			if($sent >= intval($messages->quota))
			{
				return;
			}
		}

		if(isset($messages->$type) && $messages->$type->enabled)
		{
			$message = $messages->$type->message;

			$student_name = isset($data['student']) ? $data['student']->firstname.' '.$data['student']->lastname : 'a student';
			$reporter_name = isset($data['reporter']) ? $data['reporter']->firstname.' '.$data['reporter']->lastname : 'a teacher/admin';

			$message = preg_replace(
				array('/^%%STUDENT%%/i', '/%%STUDENT%%/i', '/^%%REPORTER%%/i', '/%%REPORTER%%/i'), 
				array(ucfirst($student_name), $student_name, ucfirst($reporter_name), $reporter_name),
				$message
			);

			$this->db->insert('Logs', array(
				'type' => 'smssent',
				'report' => json_encode(array('to' => $to, 'message' => $message, 'type' => $type)),
				'userid' => $school_id,
				'timelogged' => time(),
				'useragent' => json_encode(array())
			));


			$this->_send($to, $message);
		}
	}

	function _send($to, $message)
	{
		// set your AccountSid and AuthToken from www.twilio.com/user/account
		$AccountSid = "AC9ea3897ad5635885f5e8e5bcfaea22c7";
		$AuthToken = "21801019ed4496b6d7d464fcc4ec1bdd";
		 
		$client = new Services_Twilio($AccountSid, $AuthToken);
		 
		$message = $client->account->messages->create(array(
		    "From" => "202-796-6759",
		    "To" => $to,
		    "Body" => $message,
		));
		 
		// Display a confirmation message on the screen
		//echo "Sent message {$message->sid}";
	}

	function count_sent($school_id)
	{
		$query = $this->db->query('SELECT COUNT(*) as total FROM Logs WHERE userid = ? AND type = "smssent"', array($school_id));
		return $query->row()->total;
	}
} 
