<?php
class API extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function removenotification($notification_id)
	{
		$this->load->model('Notification_model');
		$this->Notification_model->remove($notification_id);
	}

	function notifications()
	{
		$user_id = $this->session->userdata('userid');

		$this->load->model('Notification_model');
		$notifications = $this->Notification_model->get_notifications($user_id, 10);
		$this->Notification_model->mark_read($user_id);
		
		if(empty($notifications))
		{
?>
<script>
$('#nonotification').show();
</script>
<?php
			return;
		}
?>

	<?php
$header = '';
foreach($notifications as $notification):
		

		    $date = date('d/m/Y', $notification->timecreated);
		    switch(true)
		    {
		    	case $date == date('d/m/Y'):
		      		$h = 'Today';
		      	break;
		    	case $date == date('d/m/Y', time() - (24 * 60 * 60)):
		      		$h = 'Yesterday';
		      	break;
		      	default:
		      		$h = date('m/d');
		      	break;
		    }

		    if($h != $header):
		    	$header = $h;
?>

<li data-role="list-divider" data-group="<?= md5($header) ?>"><?= $header ?></li>
<?php
		    endif;
?>
<li data-group="<?= md5($header) ?>" data-id="<?= $notification->notificationid ?>"><a href="/<?= $notification->link ?>" data-ajax="false">
						<p><?= $notification->message ?></p>
						<p class="ui-li-aside" style="position: absolute;
  	top: 2.9em;"><?= date('n/j - g:i a', $notification->timecreated); ?></p>
					</a><a href="#" data-rel="popup" data-position-to="window" data-transition="pop" class="_removenotification">Remove</a></li>
<?php endforeach; ?>
				<?php 

	}

	function findconnected($search, $type = 'student')
	{
		$search = urldecode($search);
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('User_model');

		switch($type)
		{
			case 'student':
				$users = $this->User_model->find_students_in_school($school_id, $search);
			break;
			default:
				$users = $this->User_model->find_in_school($school_id, $search);
			break;
		}
		
		echo json_encode(array('names' => $users));
	}

	function adddetentiontime($student_id, $minutes)
	{
		$admin_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->load->model('Detention_model');
			$this->Detention_model->assign($school_id, $student_id, $admin_id, $minutes);

			$time_assigned = $this->Detention_model->count_assigned_from_student($student_id);
			$time_served = $this->Detention_model->count_served_from_student($student_id);

			$active_detention = $this->Detention_model->get_active($student_id);

			$data = array(
				'status' => 200,
				'studentid' => $student_id,
				'timeassigned' => $time_assigned*60,
				'timeserved' => $time_served*60
			);

			if(!empty($active_detention))
			{
				$data['timeelapsed'] = intval(time()-$active_detention->timecreated);
				$data['active'] = 1;
			}
			else
			{
				$data['active'] = 0;
				$data['timeelapsed'] = 0;
			}

			array_push($return, $data);
		}

		echo json_encode($return);		
	}

	function subtractdetentiontime($student_id, $minutes)
	{
		$admin_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->load->model('Detention_model');
			$existing_balance = $this->Detention_model->get_balance($student_id);

			$served = min($existing_balance, $minutes);

			if($served >= 0)
			{
				$this->load->model('Detention_model');
				$this->Detention_model->serve($school_id, $student_id, $admin_id, $served);
			}

			$time_assigned = $this->Detention_model->count_assigned_from_student($student_id);
			$time_served = $this->Detention_model->count_served_from_student($student_id);

			$active_detention = $this->Detention_model->get_active($student_id);

			$data = array(
				'status' => 200,
				'studentid' => $student_id,
				'timeassigned' => $time_assigned*60,
				'timeserved' => $time_served*60
			);

			if(!empty($active_detention))
			{
				$data['timeelapsed'] = intval(time()-$active_detention->timecreated);
				$data['active'] = 1;
			}
			else
			{
				$data['active'] = 0;
				$data['timeelapsed'] = 0;
			}

			array_push($return, $data);
		}

		echo json_encode($return);
	}

	function startdetention($student_id)
	{
		$admin_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Detention_model');

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$detention = $this->Detention_model->get_active_detention($student_id);

			$time_assigned = $this->Detention_model->count_assigned_from_student($student_id);
			$time_served = $this->Detention_model->count_served_from_student($student_id);

			if($time_assigned == $time_served)
			{
				array_push($return, array(
					'studentid' => $student_id,
					'status' => 500
				));

				continue;
			}

			if(empty($detention))
			{
				$this->Detention_model->serve($school_id, $student_id, $admin_id, 0);
				$time_elapsed = 0;
			}
			else
			{
				$time_elapsed = time()-$detention->timecreated;
			}

			$time_left = ($time_assigned*60)-$time_served;

			array_push($return, array(
				'studentid' => $student_id,
				'timeserved' => $time_served*60,
				'timeelapsed' => $time_elapsed,
				'timeassigned' => $time_assigned*60,
				'active' => 1
			));
		}

		echo json_encode($return);
	}

	function stopdetention($student_id)
	{
		$admin_id = $this->session->userdata('userid');

		$this->load->model('Detention_model');

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$detention = $this->Detention_model->get_active_detention($student_id);

			if(!empty($detention))
			{
				$time_assigned = $this->Detention_model->count_assigned_from_student($student_id);
				$time_served = $this->Detention_model->count_served_from_student($student_id);

				$time_left = $time_assigned-$time_served;

				// Make sure that the amount served doesn't exceed the current balance.
				$minutes = max(min(ceil((time()-$detention->timecreated)/60), $time_left), 0);

				$this->Detention_model->update($detention->detentionid, '-'.$minutes);

				$time_assigned = $this->Detention_model->count_assigned_from_student($student_id);
				$time_served = $this->Detention_model->count_served_from_student($student_id);

				array_push($return, array(
					'studentid' => $student_id,
					'timeserved' => $time_served*60,
					'timeelapsed' => 0,
					'timeassigned' => $time_assigned*60,
					'active' => 0
				));
			}
			else
			{
				array_push($return, array(
					'studentid' => $student_id,
					'status' => 500
				));
			}
		}

		echo json_encode($return);
	}

	function resetdetention($student_id)
	{
		$admin_id = $this->session->userdata('userid');

		$this->load->model('Detention_model');

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$detention = $this->Detention_model->get_active_detention($student_id);

			if(!empty($detention))
			{
				$this->Detention_model->reset($detention->detentionid);

				$time_assigned = $this->Detention_model->count_assigned_from_student($student_id);
				$time_served = $this->Detention_model->count_served_from_student($student_id);

				array_push($return, array(
					'studentid' => $student_id,
					'timeelapsed' => 0,
					'timeserved' => $time_served*60,
					'timeassigned' => $time_assigned*60,
					'active' => '1'
				));
			}
		}

		echo json_encode($return);
	}

	function addreinforcement()
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Reinforcement_model');

		$reason = $this->input->post('reinforcement');
		$student_id = $this->input->post('studentid');
		
		$this->load->model('User_model');

		$this->load->model('Settings_model');
		$reinforcements = json_decode($this->Settings_model->get_settings($school_id, 'reinforcements'));

		switch($reinforcements->quantitytype)
		{
			case 'number':
			case 'range':
				$amount = $this->input->post('amount');
			break;
			default:
				$amount = $reinforcements->awardamount;
			break;
		}

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->Reinforcement_model->create($school_id, $teacher_id, $student_id, $amount, $reason, '', time());

			array_push($return, array(
				'student' => $student_id,
				'dollars' => $this->Reinforcement_model->get_dollar_total($student_id)
			));

			if(defined('FEATURE_GOALS') && FEATURE_GOALS)
			{
				$student = $this->User_model->get_user($student_id);

				$this->load->model('Goal_model');
				$goals = $this->Goal_model->get_active_goals($student_id, 'reinforcement');

				if(!empty($goals))
				{
					foreach($goals as $goal)
					{
						$goal_completed = false;
						$details = json_decode($goal->details);

						$details->progress += $amount;

						if($details->progress > $details->quantity || ($details->type == 'atleast' && $details->progress >= $details->quantity))
						{
							$goal->status = GOAL_STATUS_COMPLETED;
							$goal->timecompleted = time();
							$goal_completed = true;
						}

						$this->Goal_model->update($goal->goalid, $details, $goal->status, $goal->timecompleted);

						if($goal_completed && !empty($details->notify))
						{
							$recipients = array();

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
									case $details->type == 'atleast' && $details->progress == $details->quantity: 
										$text = 'Congrats! '.$who.' met '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's').'.';
									break;
									case $details->type == 'atleast' && $details->progress > $details->quantity:
										$text = 'Congrats! '.$who.' surpassed '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
										$text .= (($details->progress-$details->quantity) > 1) ? ' by '.($details->progress-$details->quantity).' '.$objectname.'.' : '';
									break;							
									case $details->type == 'atmost' && $details->progress > $details->quantity:
										$text = $who.' exceeded '.$ownership.' goal of at most '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
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
			}
		}
			
		 
		echo json_encode($return);
	}

	function adddemerit()
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Demerit_model');
		$this->load->model('User_model');

		$reason = $this->input->post('demerit');
		$student_id = $this->input->post('studentid');

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->Demerit_model->create($school_id, $teacher_id, $student_id, $reason, '', time());

			$demerit_total = $this->Demerit_model->get_demerit_total($school_id, $student_id);

			array_push($return, array(
				'student' => $student_id,
				'total' => $demerit_total,
			));

			if(defined('FEATURE_GOALS') && FEATURE_GOALS)
			{
				$student = $this->User_model->get_user($student_id);

				$this->load->model('Goal_model');
				$goals = $this->Goal_model->get_active_goals($student_id, 'demerit');

				if(!empty($goals))
				{
					foreach($goals as $goal)
					{
						$goal_completed = false;
						$details = json_decode($goal->details);

						$details->progress++;

						if($details->progress > $details->quantity || ($details->type == 'atleast' && $details->progress== $details->quantity))
						{
							$goal->status = GOAL_STATUS_COMPLETED;
							$goal->timecompleted = time();
							$goal_completed = true;
						}

						$this->Goal_model->update($goal->goalid, $details, $goal->status, $goal->timecompleted);

						if($goal_completed && !empty($details->notify))
						{
							$recipients = array();

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
									case $details->type == 'atleast' && $details->progress == $details->quantity: 
										$text = 'Congrats! '.$who.' met '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's').'.';
									break;
									case $details->type == 'atleast' && $details->progress > $details->quantity:
										$text = 'Congrats! '.$who.' surpassed '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
										$text .= (($details->progress-$details->quantity) > 1) ? ' by '.($details->progress-$details->quantity).' '.$objectname.'.' : '';
									break;							
									case $details->type == 'atmost' && $details->progress > $details->quantity:
										$text = $who.' exceeded '.$ownership.' goal of at most '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
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
			}
		}

		echo json_encode($return);
	}

	function adddetention()
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Detention_model');
		$this->load->model('User_model');

		$reason = $this->input->post('reason');
		$student_id = $this->input->post('studentid');
		$minutes = $this->input->post('time');
		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->Detention_model->assign($school_id, $student_id, $teacher_id, $minutes, $reason);

			array_push($return, array(
				'student' => $student_id,
				'minutes' => $this->Detention_model->get_balance($student_id)
			));

			if(defined('FEATURE_GOALS') && FEATURE_GOALS)
			{
				$student = $this->User_model->get_user($student_id);

				$this->load->model('Goal_model');
				$goals = $this->Goal_model->get_active_goals($student_id, 'detention');

				if(!empty($goals))
				{
					foreach($goals as $goal)
					{
						$goal_completed = false;
						$details = json_decode($goal->details);

						$details->progress += $minutes;

						if($details->progress > $details->quantity || ($details->type == 'atleast' && $details->progress >= $details->quantity))
						{
							$goal->status = GOAL_STATUS_COMPLETED;
							$goal->timecompleted = time();
							$goal_completed = true;
						}

						$this->Goal_model->update($goal->goalid, $details, $goal->status, $goal->timecompleted);

						if($goal_completed && !empty($details->notify))
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
									case $details->type == 'atleast' && $details->progress == $details->quantity: 
										$text = 'Congrats! '.$who.' met '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's').'.';
									break;
									case $details->type == 'atleast' && $details->progress > $details->quantity:
										$text = 'Congrats! '.$who.' surpassed '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
										$text .= (($details->progress-$details->quantity) > 1) ? ' by '.($details->progress-$details->quantity).' '.$objectname.'.' : '';
									break;							
									case $details->type == 'atmost' && $details->progress > $details->quantity:
										$text = $who.' exceeded '.$ownership.' goal of at most '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
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
			}	
		}

		echo json_encode($return);
	}

	function addreferral()
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Referral_model');

		$reason = $this->input->post('reason');
		$notes = $this->input->post('notes');

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'referrals'));

		$teacher_report = array(
			array('label' => 'Incident', 'id' => $settings->keys->incident, 'value' => $reason),
			array('label' => 'Notes', 'id' => $settings->keys->notes, 'value' => $notes),
		);

		$student_id = $this->input->post('studentid');
		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->Referral_model->create($school_id, $teacher_id, $student_id, $reason, $teacher_report);

			array_push($return, array(
				'student' => $student_id,
			));
		}

		echo json_encode($return);
	}

	function addintervention()
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Intervention_model');
		$this->load->model('User_model');

		$reason = $this->input->post('reason');
		$notes = $this->input->post('notes');

		$student_id = $this->input->post('studentid');
		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->Intervention_model->create($school_id, $teacher_id, $student_id, $reason, $notes, time());

			array_push($return, array(
				'student' => $student_id,
			));

			if(defined('FEATURE_GOALS') && FEATURE_GOALS)
			{
				$student = $this->User_model->get_user($student_id);

				$this->load->model('Goal_model');
				$goals = $this->Goal_model->get_active_goals($student_id, 'intervention');

				if(!empty($goals))
				{
					foreach($goals as $goal)
					{
						$goal_completed = false;
						$details = json_decode($goal->details);

						$details->progress++;

						if($details->progress > $details->quantity || ($details->type == 'atleast' && $details->progress== $details->quantity))
						{
							$goal->status = GOAL_STATUS_COMPLETED;
							$goal->timecompleted = time();
							$goal_completed = true;
						}

						$this->Goal_model->update($goal->goalid, $details, $goal->status, $goal->timecompleted);

						if($goal_completed && !empty($details->notify))
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
									case $details->type == 'atleast' && $details->progress == $details->quantity: 
										$text = 'Congrats! '.$who.' met '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's').'.';
									break;
									case $details->type == 'atleast' && $details->progress > $details->quantity:
										$text = 'Congrats! '.$who.' surpassed '.$ownership.' goal of at least '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
										$text .= (($details->progress-$details->quantity) > 1) ? ' by '.($details->progress-$details->quantity).' '.$objectname.'.' : '';
									break;							
									case $details->type == 'atmost' && $details->progress > $details->quantity:
										$text = $who.' exceeded '.$ownership.' goal of at most '.$details->quantity.' '.$objectname.($details->quantity == 1 ? '' : 's');
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
			}
		}

		echo json_encode($return);
	}

	function addhallpass()
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Hallpass_model');

		$reason = $this->input->post('reason');
		$notes = $this->input->post('notes');

		$student_id = $this->input->post('studentid');
		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->Hallpass_model->create($school_id, $teacher_id, $student_id, $reason, $notes);

			array_push($return, array(
				'student' => $student_id,
			));
		}

		echo json_encode($return);
	}

	function addstatus()
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Status_model');

		$status = $this->input->post('status');
		$student_id = $this->input->post('studentid');

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$this->Status_model->create($school_id, $teacher_id, $student_id, $status, '');

			array_push($return, array(
				'student' => $student_id,
				'status' => $status
			));
		}

		echo json_encode($return);
	}

	function adjustdetention($student_id, $new_balance)
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Detention_model');

		$ids = preg_split('/_/', $student_id);

		$return = array();
		foreach($ids as $student_id)
		{
			$balance = $this->Detention_model->get_balance($student_id);

			$adjustment = $new_balance - $balance;

			if($adjustment != 0)
			{
				$active_detention = $this->Detention_model->get_active($student_id);

				if(!empty($active_detention))
				{
					$this->Detention_model->remove($active_detention->detentionid);
				}

				$this->Detention_model->adjust($school_id, $student_id, $teacher_id, $adjustment, 'adjustment');

				array_push($return, array(
					'studentid' => $student_id,
					'minutes' => $this->Detention_model->get_balance($student_id)*60,
					'timeassigned' => $this->Detention_model->count_assigned_from_student($student_id)*60
				));
			}
		}

		echo json_encode($return);
	}

	function reportgraph($student_id, $report_type, $etc = '')
	{
		$months = array('Jan', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan');
		$school_id = $this->session->userdata('schoolid');

		$teacher_id = $this->session->userdata('userid');

		if($this->session->userdata('role') == 'a')
		{
			$teacher_id = 0;
		}

		switch($report_type)
		{
			case 'demerits':
				$this->load->model('Demerit_model');
				$demerits_by_month = $this->Demerit_model->count_by_interval('year', 'month', $school_id, $teacher_id, $student_id);

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Month', $labels->demerits));
				$current_month = intval(date('m')); 

				$totals = array(); 
				for($i=1; $i<=12; $i++)
				{
					$totals[$i] = 0; 
				}

				foreach($demerits_by_month as $month)
				{
					$totals[$month->label] = $month->total;
				}

				$totals = ($current_month < 8) ? array_slice($totals, 7, null, true)+array_slice($totals, 0, $current_month, true) : array_slice($totals, 7, $current_month-7, true); 
		
				foreach($totals as $m=>$t)
				{
					array_push($return_data, array($months[$m], intval($t)));
				}

				echo json_encode(array('title' => $labels->demerits.' to date', 'data' => $return_data));
			break;
			case 'detentions':
				$this->load->model('Detention_model');
				$detentions_by_month = $this->Detention_model->count_by_interval('year', 'month', $school_id, $teacher_id, $student_id);

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Month', $labels->detentions));
				$current_month = intval(date('m')); 

				$totals = array(); 
				for($i=1; $i<=12; $i++)
				{
					$totals[$i] = 0; 
				}

				foreach($detentions_by_month as $month)
				{
					$totals[$month->label] = $month->total;
				}

				$totals = ($current_month < 8) ? array_slice($totals, 7, null, true)+array_slice($totals, 0, $current_month, true) : array_slice($totals, 7, $current_month-7, true); 
		
				foreach($totals as $m=>$t)
				{
					array_push($return_data, array($months[$m], intval($t)));
				}

				echo json_encode(array('title' => $labels->detention.' '.$labels->detentionunits.' to date', 'data' => $return_data));				
			break;
			case 'reinforcements':
				$this->load->model('Reinforcement_model');
				$reinforcements_by_month = $this->Reinforcement_model->count_by_interval('year', 'month', $school_id, $teacher_id, $student_id);

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Month', $labels->reinforcements));
				$current_month = intval(date('m')); 

				$totals = array(); 
				for($i=1; $i<=12; $i++)
				{
					$totals[$i] = 0; 
				}

				foreach($reinforcements_by_month as $month)
				{
					$totals[$month->label] = $month->total;
				}

				$totals = ($current_month < 8) ? array_slice($totals, 7, null, true)+array_slice($totals, 0, $current_month, true) : array_slice($totals, 7, $current_month-7, true); 
		
				foreach($totals as $m=>$t)
				{
					array_push($return_data, array($months[$m], intval($t)));
				}

				echo json_encode(array('title' => $labels->reinforcements.' to date', 'data' => $return_data));				
			break;	
			case 'referrals':
				$this->load->model('Referral_model');
				$referrals_by_month = $this->Referral_model->count_by_interval('year', 'month', $school_id, $teacher_id, $student_id);

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Month', $labels->referrals));
				$current_month = intval(date('m')); 

				$totals = array(); 
				for($i=1; $i<=12; $i++)
				{
					$totals[$i] = 0; 
				}

				foreach($referrals_by_month as $month)
				{
					$totals[$month->label] = $month->total;
				}

				$totals = ($current_month < 8) ? array_slice($totals, 7, null, true)+array_slice($totals, 0, $current_month, true) : array_slice($totals, 7, $current_month-7, true); 
		
				foreach($totals as $m=>$t)
				{
					array_push($return_data, array($months[$m], intval($t)));
				}

				echo json_encode(array('title' => $labels->referrals.' to date', 'data' => $return_data));				
			break;	
			case 'interventions':
				$this->load->model('Intervention_model');
				$interventions_by_month = $this->Intervention_model->count_by_interval('year', 'month', $school_id, $teacher_id, $student_id);

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Month', $labels->interventions));
				$current_month = intval(date('m')); 

				$totals = array(); 
				for($i=1; $i<=12; $i++)
				{
					$totals[$i] = 0; 
				}

				foreach($interventions_by_month as $month)
				{
					$totals[$month->label] = $month->total;
				}

				$totals = ($current_month < 8) ? array_slice($totals, 7, null, true)+array_slice($totals, 0, $current_month, true) : array_slice($totals, 7, $current_month-7, true); 
		
				foreach($totals as $m=>$t)
				{
					array_push($return_data, array($months[$m], intval($t)));
				}

				echo json_encode(array('title' => $labels->interventions.' to date', 'data' => $return_data));				
			break;	
			case 'demeritsbyreason':
				$this->load->model('Demerit_model');
				$demerits = $this->Demerit_model->category_totals('year', $school_id, $teacher_id, $student_id);

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));				

				$return_data = array(array($labels->demerits, '# Incidents'));

				foreach($demerits as $demerit)
				{
					array_push($return_data, array($demerit->reason, intval($demerit->total)));
				}

				echo json_encode(array('title' => $labels->demerits.' by reason', 'data' => $return_data));				
			break;
			case 'demeritsbyteacher':
				$this->load->model('Demerit_model');
				$demerits = $this->Demerit_model->category_totals_by('teacher.name', 'year', $school_id, $teacher_id, $student_id, 'teachername', 'total');

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Teacher', '# Incidents'));

				foreach($demerits as $demerit)
				{
					array_push($return_data, array($demerit->teachername, intval($demerit->total)));
				}

				echo json_encode(array('title' => $labels->demerits.' by teacher', 'data' => $return_data));				
			break;
			case 'referralsbyreason':
				$this->load->model('Referral_model');
				$referrals = $this->Referral_model->category_totals('year', $school_id, $teacher_id, $student_id);

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array($labels->referral, '# Incidents'));

				foreach($referrals as $referral)
				{
					array_push($return_data, array($referral->incident, intval($referral->total)));
				}

				echo json_encode(array('title' => $labels->referrals.' by reason', 'data' => $return_data));				
			break;
			case 'referralsbyteacher': 
				$this->load->model('Referral_model');
				$referrals = $this->Referral_model->category_totals_by('teacher.name', 'year', $school_id, $teacher_id, $student_id, 'teachername', 'total');

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Teacher', '# Incidents'));

				foreach($referrals as $referral)
				{
					array_push($return_data, array($referral->teachername, intval($referral->total)));
				}

				echo json_encode(array('title' => $labels->referrals.' by teacher', 'data' => $return_data));				
			break;
			case 'reinforcementsbyreason':
				$this->load->model('Reinforcement_model');
				$reinforcements = $this->Reinforcement_model->category_totals('year', $school_id, $teacher_id, $student_id);
				
				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array($labels->reinforcements, '# Incidents'));

				foreach($reinforcements as $reinforcement)
				{
					array_push($return_data, array($reinforcement->reason, intval($reinforcement->total)));
				}

				echo json_encode(array('title' => $labels->reinforcements.' by reason', 'data' => $return_data));				
			break;
			case 'reinforcementsbyteacher': 
				$this->load->model('Reinforcement_model');
				$reinforcements = $this->Reinforcement_model->category_totals_by('teacher.name', 'year', $school_id, $teacher_id, $student_id, 'teachername', 'total');

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Teacher', '# Incidents'));

				foreach($reinforcements as $reinforcement)
				{
					array_push($return_data, array($reinforcement->teachername, intval($reinforcement->total)));
				}

				echo json_encode(array('title' => $labels->reinforcements.' by teacher', 'data' => $return_data));				
			break;		
			case 'interventionsbyreason':
				$this->load->model('Intervention_model');
				$interventions = $this->Intervention_model->category_totals('year', $school_id, $teacher_id, $student_id);
				
				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array($labels->interventions, '# Incidents'));

				foreach($interventions as $intervention)
				{
					array_push($return_data, array($intervention->incident, intval($intervention->total)));
				}

				echo json_encode(array('title' => $labels->interventions.' by reason', 'data' => $return_data));				
			break;
			case 'interventionsbyteacher': 
				$this->load->model('Intervention_model');
				$interventions = $this->Intervention_model->category_totals_by('teacher.name', 'year', $school_id, $teacher_id, $student_id, 'teachername', 'total');

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Teacher', '# Incidents'));

				foreach($interventions as $intervention)
				{
					array_push($return_data, array($intervention->teachername, intval($intervention->total)));
				}

				echo json_encode(array('title' => $labels->reinforcements.' by teacher', 'data' => $return_data));				
			break;					
			case 'detentionsbyreason':
				$this->load->model('Detention_model');
				$detentions = $this->Detention_model->category_totals('year', $school_id, $teacher_id, $student_id);
				
				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array($labels->detention, '# Incidents'));

				foreach($detentions as $detention)
				{
					if($detention->reason == '')
					{
						$detention->reason = 'other';
					}

					// Ignore the adjustment entries.
					if($detention->reason == 'adjustment')
					{
						continue;
					}

					array_push($return_data, array($detention->reason, intval($detention->total)));
				}

				echo json_encode(array('title' => $labels->detention.' '.$labels->detentionunits.' by reason', 'data' => $return_data));				
			break;
			case 'detentionsbyteacher': 
				$this->load->model('Detention_model');
				$detentions = $this->Detention_model->category_totals_by('teacher.name', 'year', $school_id, $teacher_id, $student_id, 'teachername', 'total');

				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$return_data = array(array('Teacher', '# Incidents'));

				foreach($detentions as $detention)
				{
					array_push($return_data, array($detention->teachername, intval($detention->total)));
				}

				echo json_encode(array('title' => $labels->detention.' '.$labels->detentionunits.' by teacher', 'data' => $return_data));				
			break;		
			case 'formbyteacher': 
				$form_id = $etc; 
				$this->load->model('Report_model');
				$detentions = $this->Report_model->category_totals_by('teacher.name', 'year', 'form', $form_id, $school_id, $teacher_id, $student_id, 'teachername', 'total');

				$this->load->model('Form_model');
				$form = $this->Form_model->get_form($form_id);

				$return_data = array(array('Teacher', '# Incidents'));

				foreach($detentions as $detention)
				{
					array_push($return_data, array($detention->teachername, intval($detention->total)));
				}

				echo json_encode(array('title' => $form->title.' by teacher', 'data' => $return_data));				
			break;					
			case 'formbyreason': 
				$form_id = $etc; 

				$this->load->model('Report_model');
				$reports = $this->Report_model->category_totals('year', 'form', $form_id, $school_id, $teacher_id, $student_id);
				
				$this->load->model('Form_model');
				$form = $this->Form_model->get_form($form_id);

				$return_data = array(array($form->title, '# Reports'));

				foreach($reports as $report)
				{
					if($report->title == '')
					{
						$report->title = 'other';
					}

					array_push($return_data, array($report->title, intval($report->total)));
				}

				echo json_encode(array('title' => $form->title.' by reason', 'data' => $return_data));			
			break;					
			case 'form': 
				$form_id = $etc;

				$this->load->model('Form_model');
				$this->load->model('Report_model');
				$reports_by_month = $this->Report_model->count_by_interval('year', 'month', 'form', $form_id, $school_id, $teacher_id, $student_id);

				$form = $this->Form_model->get_form($form_id);

				$return_data = array(array('Month', $form->title));
				$current_month = intval(date('m')); 
				
				$totals = array(); 
				for($i=1; $i<=12; $i++)
				{
					$totals[$i] = 0; 
				}

				foreach($reports_by_month as $month)
				{
					$totals[$month->label] = $month->total;
				}

				$totals = ($current_month < 8) ? array_slice($totals, 7, null, true)+array_slice($totals, 0, $current_month, true) : array_slice($totals, 7, $current_month-7, true); 
		
				foreach($totals as $m=>$t)
				{
					array_push($return_data, array($months[$m], $t));
				}

				echo json_encode(array('title' => $form->title.' to date', 'data' => $return_data));
			break;		
		}
	}

	function enablesms($enabled)
	{
		$school_id = $this->session->userdata('schoolid');

		if($this->session->userdata('role') != 'a')
		{
			return;
		}		

		$this->load->model('Settings_model');

		$messages = json_decode($this->Settings_model->get_settings($school_id, 'sms'));

		$new_change = ($enabled == 'on');

		if($messages->enabled != $new_change)
		{
			$messages->enabled = $new_change;
			$this->Settings_model->save($school_id, 'sms', $messages);
		}
	}

	function chartdata($object, $scope, $type, $when, $etc = '', $etc2 = '')
	{
		$school_id = $this->session->userdata('schoolid');

		if($object == 'form')
		{
			$form_id = $scope;
			$scope = $type;
			$type = $when;
			$when = $etc;
			$etc = $etc2;
		}

		$teacher_id = $scope == 'teacher' ? $this->session->userdata('userid') : 0;

		header('Content-type: application/json');

		function flatten($results, $labels = array())
		{
			$r = array();

			foreach($results as $row)
			{
				array_push($r, array(isset($labels[$row->label]) ? $labels[$row->label] : $row->label, intval($row->total)));
			}

			return $r;	
		};

		function flattenmap($results, $mappings)
		{
			$new_results = array();

			foreach($results as $r)
			{
				$d = array();

				foreach($mappings as $new_name => $old_name)
				{
					$d[$new_name] = $r->$old_name;
				}

				array_push($new_results, $d);
			}

			return $new_results;
		}

		$months = array('1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');
		switch($object)
		{
			case 'demerit':
				$this->load->model('Demerit_model');

				switch($type)
				{
					case 'leaderboard':
						echo json_encode(array('results' => flattenmap($this->Demerit_model->top_demerits($when, $school_id, $teacher_id, 5), array('name' => 'studentname', 'profileimg' => 'profileimage', 'id' => 'userid', 'count' => 'total'))));
					exit;		
					case 'interval':
						$labels = $etc == 'month' ? $months : array();
						echo json_encode(array('results' => flatten($this->Demerit_model->count_by_interval($when, $etc, $school_id, $teacher_id, 0), $labels)));
					exit;	
					case 'count':
						echo json_encode(array('total' => intval($this->Demerit_model->count_today($when, $school_id, $teacher_id))));
					exit;
					case 'reason':
						echo json_encode(array('results' => flatten($this->Demerit_model->category_totals($when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
					case 'teacher.name':
					case 'teacher.ethnicity':
					case 'teacher.gender':
					case 'student.name':
					case 'student.grade':
					case 'student.ethnicity':
					case 'student.gender':
						echo json_encode(array('results' => flatten($this->Demerit_model->category_totals_by($type, $when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
				}
			break;
			case 'referral':
				$this->load->model('Referral_model');

				switch($type)
				{
					case 'leaderboard':
						echo json_encode(array('results' => flattenmap($this->Referral_model->top_referrals($when, $school_id, $teacher_id, 5), array('name' => 'studentname', 'profileimg' => 'profileimage', 'id' => 'userid', 'count' => 'total'))));
					exit;	
					case 'interval':
						$labels = $etc == 'month' ? $months : array();
						echo json_encode(array('results' => flatten($this->Referral_model->count_by_interval($when, $etc, $school_id, $teacher_id, 0), $labels)));
					exit;																				
					case 'count':
						echo json_encode(array('total' => intval($this->Referral_model->count_today($when, $school_id, $teacher_id))));
					exit;
					case 'reason':
						echo json_encode(array('results' => flatten($this->Referral_model->category_totals($when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
					case 'teacher.name':
					case 'teacher.ethnicity':
					case 'teacher.gender':
					case 'student.name':
					case 'student.grade':
					case 'student.ethnicity':
					case 'student.gender':
						echo json_encode(array('results' => flatten($this->Referral_model->category_totals_by($type, $when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
				}
			break;
			case 'intervention':
				$this->load->model('Intervention_model');

				switch($type)
				{
					case 'interval':
						$labels = $etc == 'month' ? $months : array();
						echo json_encode(array('results' => flatten($this->Intervention_model->count_by_interval($when, $etc, $school_id, $teacher_id, 0), $labels)));
					exit;					
					case 'leaderboard':
						echo json_encode(array('results' => flattenmap($this->Intervention_model->top_interventions($when, $school_id, $teacher_id, 5), array('name' => 'studentname', 'profileimg' => 'profileimage', 'id' => 'userid', 'count' => 'total'))));
					exit;			
					case 'count':
						echo json_encode(array('total' => intval($this->Intervention_model->count_today($when, $school_id, $teacher_id))));
					exit;
					case 'reason':
						echo json_encode(array('results' => flatten($this->Intervention_model->category_totals($when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
					case 'teacher.name':
					case 'teacher.ethnicity':
					case 'teacher.gender':
					case 'student.name':
					case 'student.grade':
					case 'student.ethnicity':
					case 'student.gender':
						echo json_encode(array('results' => flatten($this->Intervention_model->category_totals_by($type, $when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
				}
			break;	
			case 'reinforcement':
				$this->load->model('Reinforcement_model');

				switch($type)
				{
					case 'leaderboard':
						echo json_encode(array('results' => flattenmap($this->Reinforcement_model->top_reinforcements($when, $school_id, $teacher_id, 5), array('name' => 'studentname', 'profileimg' => 'profileimage', 'id' => 'userid', 'count' => 'total'))));
					exit;			
					case 'interval':
						$labels = $etc == 'month' ? $months : array();
						echo json_encode(array('results' => flatten($this->Reinforcement_model->count_by_interval($when, $etc, $school_id, $teacher_id, 0), $labels)));
					exit;											
					case 'count':
						echo json_encode(array('total' => intval($this->Reinforcement_model->count_today($when, $school_id, $teacher_id))));
					exit;
					case 'reason':
						echo json_encode(array('results' => flatten($this->Reinforcement_model->category_totals($when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
					case 'teacher.name':
					case 'teacher.ethnicity':
					case 'teacher.gender':
					case 'student.name':
					case 'student.grade':
					case 'student.ethnicity':
					case 'student.gender':
						echo json_encode(array('results' => flatten($this->Reinforcement_model->category_totals_by($type, $when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
				}
			break;	
			case 'detention':
				$this->load->model('Detention_model');

				switch($type)
				{
					case 'interval':
						$labels = $etc == 'month' ? $months : array();
						echo json_encode(array('results' => flatten($this->Detention_model->count_by_interval($when, $etc, $school_id, $teacher_id, 0), $labels)));
					exit;
					case 'leaderboard':
						echo json_encode(array('results' => flattenmap($this->Detention_model->top_detentions($when, $school_id, $teacher_id, 5), array('name' => 'studentname', 'profileimg' => 'profileimage', 'id' => 'userid', 'count' => 'total'))));
					exit;
					case 'count':
						echo json_encode(array('total' => intval($this->Detention_model->count_today($when, $school_id, $teacher_id))));
					exit;					
					case 'reason':
						echo json_encode(array('results' => flatten($this->Detention_model->category_totals($when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
					case 'teacher.name':
					case 'teacher.ethnicity':
					case 'teacher.gender':
					case 'student.name':
					case 'student.grade':
					case 'student.ethnicity':
					case 'student.gender':
						echo json_encode(array('results' => flatten($this->Detention_model->category_totals_by($type, $when, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
				}
			break;	
			case 'form':
				$this->load->model('Report_model');

				switch($type)
				{
					case 'leaderboard':
						echo json_encode(array('results' => flattenmap($this->Report_model->top_reports($when, $school_id, 'form', $form_id, $teacher_id, 5), array('name' => 'studentname', 'profileimg' => 'profileimage', 'id' => 'userid', 'count' => 'total'))));
					exit;		
					case 'interval':
						$labels = $etc == 'month' ? $months : array();
						echo json_encode(array('results' => flatten($this->Report_model->count_by_interval($when, $etc, 'form', $form_id, $school_id, $teacher_id, 0), $labels)));
					exit;								
					case 'count':
						echo json_encode(array('total' => intval($this->Report_model->count_today($when, 'form', $form_id, $school_id, $teacher_id))));					
					exit;
					case 'reason':
						echo json_encode(array('results' => flatten($this->Report_model->category_totals($when, 'form', $form_id, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
					case 'teacher.name':
					case 'teacher.ethnicity':
					case 'teacher.gender':
					case 'student.name':
					case 'student.grade':
					case 'student.ethnicity':
					case 'student.gender':
						echo json_encode(array('results' => flatten($this->Report_model->category_totals_by($type, $when, 'form', $form_id, $school_id, $teacher_id, 0, 'label', 'total'))));
					exit;
				}
			break;												
		}
	}

	function servealldetention($student_id)
	{		
		$admin_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Detention_model');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($student_id);

		if(empty($student) || $student->schoolid != $school_id)
		{
			header('Content-type: application/json');
			echo json_encode(array('status' => 'error'));	
			exit;		
		}

		$balance = $this->Detention_model->get_balance($student_id);

		if($balance > 0)
		{	
			$this->Detention_model->serve($school_id, $student_id, $admin_id, $balance);
		}

		header('Content-type: application/json');
		echo json_encode(array('status' => 'success'));
		exit;
	}

	function servedetention($student_id, $minutes)
	{
		if(!$this->session->userdata('userid'))
		{
			header('Content-type: application/json');
			echo json_encode(array('status' => 'error'));	
			exit;		
		}

		$admin_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Detention_model');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($student_id);

		if(empty($student) || $student->schoolid != $school_id)
		{
			header('Content-type: application/json');
			echo json_encode(array('status' => 'error'));	
			exit;		
		}

		$balance = $this->Detention_model->get_balance($student_id);

		if($balance > 0 && $balance >= $minutes)
		{	
			$this->Detention_model->serve($school_id, $student_id, $admin_id, $minutes);
		}

		$balance = $this->Detention_model->get_balance($student_id);

		header('Content-type: application/json');
		echo json_encode(array('status' => 'success', 'amount' => intval($balance)));
		exit;	
	}
}
?>