<?php

/*$realm = 'Restricted area';

//user => password
$users = array('francisco' => 'bbf853f3ad1b357ae67e91e24a5776da', 'jeancarl' => '17bce48a803ed322a2a4700f02b87e73');


if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Text to send if user hits Cancel button');
}


// analyze the PHP_AUTH_DIGEST variable
//echo $_SERVER['PHP_AUTH_DIGEST'];exit;
if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) ||
    !isset($users[$data['username']]))
{
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Text to send if user hits Cancel button');	
}


// generate the valid response
//$A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
$A1 = $users[$data['username']];
//echo $A1;
//exit;
$A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
$valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

if ($data['response'] != $valid_response)
{
    header('HTTP/1.1 401 Unauthorized');
    header('WWW-Authenticate: Digest realm="'.$realm.
           '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

    die('Text to send if user hits Cancel button');

}



// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}*/

class Dev extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function permissionsetting20151011()
	{
		$query = $this->db->query('SELECT * FROM Schools');
		$schools = $query->result();

		$this->load->model('Settings_model');
		foreach($schools as $school)
		{
			echo $school->schoolid;
			$this->Settings_model->save($school->schoolid, 'permissions', new stdClass());
		}
	}

	function userfix20150919()
	{
		$this->db->simple_query('ALTER TABLE `Users` CHANGE `grade` `grade` VARCHAR(3) NOT NULL;');
	}

	function processgoals()
	{
		$this->load->model('Goal_model');
		$query = $this->db->query('SELECT * FROM Goals WHERE status = ? AND timedue <= '.time(), array(GOAL_STATUS_ACTIVE));

		$goals = $query->result();
		$log = '';
		$sent = 0;

		if(!empty($goals))
		{
			foreach($goals as $goal)
			{
				$school_id = $goal->schoolid;
				$details = json_decode($goal->details);

				$this->load->model('User_model');
				$student = $this->User_model->get_user($goal->studentid);

				$this->Goal_model->update($goal->goalid, $details, GOAL_STATUS_COMPLETED, $goal->timedue);

				$log .= $goal->goalid.' '.date('Y-m-d H:i:s', $goal->timedue).' '.$goal->title."\n";
				$sent++;

				if(!empty($details->notify))
				{
					$recipients = array();
					$this->load->model('User_model');

					foreach($details->notify as $r)
					{
						switch($r)
						{
							case 'teachers':
								$teachers = $this->User_model->get_teachers($student->userid);

								foreach($teachers as $u)
								{
									array_push($recipients, $u->userid);
								}
							break;
							case 'parents':
								$parents = $this->User_model->get_parents($student->userid);

								foreach($parents as $u)
								{
									array_push($recipients, $u->userid);
								}
							break;
							case 'admins':
								$admins = $this->User_model->get_admins_from_school($school_id);

								foreach($admins as $u)
								{
									array_push($recipients, $u->userid);
								}
							break;	
							default:
								array_push($recipients, $r);
							break;
						}
					}

					$this->load->model('Notification_model');

					$recipients = array_unique($recipients);
					$objectname = $details->metric;
					foreach($recipients as $receiver)
					{
						$who = ($receiver == $student->userid) ? 'You' : $student->firstname.' '.$student->lastname;
						$ownership = ($receiver == $student->userid) ? 'your' : 'their';

						switch(true)
						{
							case $details->type == 'atmost': 
								$text = 'Congrats! '.$who.' met '.$ownership.' goal of at most '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's').'.';
							break;					
							case $details->type == 'atleast':
								$text = $who.' did not meet '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
								$text .= (($details->progress-$details->quantity) > 1) ? ' by '.($details->progress-$details->quantity).' '.$objectname.'s.' : '';
							break;
						}

						$link = 'goal/view/'.$goal->goalid;
						$object_id = 'goal/'.$goal->goalid;
						$this->Notification_model->create($receiver, $text, $link, $object_id);
					}
				}
			}
		}	

		mail('jeancarl@readwithmeapp.com', $sent.' goals completed', $log, 'From: support@readwithmeapp.com');	
	}

	function test()
	{
		var_export(json_decode('{"questions":[{"type":"select","id":"124617f0ad4a4ecb40ac0b9dcad97ed9","label":"Incident","options":["Disrespectful","Unsupportive of Others","Unkind comments","Throwing Things","Poor Attitude\/Language","Unapproved Tardy","Minor Damage","Major Damage","Incomplete Work","Gum\/candy\/seeds","Using Mobile","Out of Uniform","Verbal Harassment","Minor Fighting","Major Fighting","Minor Stealing","Major Stealing","Lying","Encouraging Fight","Sexual conduct-mutual","Defiance","Alcohol\/Tobacco\/Drugs","Knowledge of Substances","Physical Assault","Serious Weapon"]},{"type":"textarea","id":"f4c6f851b00d5518bf888815de279aba","label":"Notes","placeholder":""}],"keys":{"incident":"124617f0ad4a4ecb40ac0b9dcad97ed9"}}'));
	}

	function login($school_id = 0, $user_id = 0)
	{
		if($user_id == 0)
		{
			$query = $this->db->query('SELECT * FROM Users WHERE schoolid = ? ORDER BY schoolid, accounttype, lastname, firstname', array($school_id));
			$results = $query->result();

			foreach($results as $user)
			{
?>
<a <?php if($user->accounttype == 'a'): ?>style="font-size: 14pt; font-weight:bold" <?php endif; ?>target="_blank" href="/dev/login/<?= $user->schoolid ?>/<?= $user->userid ?>"><?= $user->accounttype ?> | <?= $user->lastname ?>, <?= $user->firstname ?></a><br />
<?php
			}
		}
		else
		{
			$query = $this->db->query('SELECT * FROM Users WHERE userid = ?', $user_id);

			$user = $query->row();

			$school_id = $user->schoolid;


			$this->session->set_userdata('userid', $user->userid);
			$this->session->set_userdata('role', $user->accounttype);
			$this->session->set_userdata('schoolid', $school_id);

			switch($user->accounttype)
			{
				case 'a':
					$this->load->model('School_model');
					if($this->School_model->has_teacher($school_id, $user->userid))
					{
						$this->session->set_userdata('isteacher', true);
					}
				break;
				case 't':
					$this->load->model('School_model');
					if($this->School_model->has_admin($school_id, $user->userid))
					{
						$this->session->set_userdata('isadmin', true);
					}
				break;
			}

			switch($user->accounttype)
			{
				case 'a':
					redirect('admin');
				break;
				case 's':
					redirect('student');
				break;
				case 't':
					redirect('teacher');
				break;
				case 'p':
					redirect('parent');
				break;
			}
		}
	}
}
?>