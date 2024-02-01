<?php
class Detention extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('userid'))
		{
			redirect('login');
			return;
		}
	}


	function index()
	{
		$this->layout->view('detention/index', array(
			'title_for_layout' => 'Detentions'
		));
	}

	function manage()
	{
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Detention_model');
		$detentions = $this->Detention_model->get_outstanding($school_id);
		$active_detentions = $this->Detention_model->get_active_detentions($school_id);

		$reload_detentions = false;

		foreach($active_detentions as $active_detention)
		{
			$mins_elapsed = round((time()-$active_detention->timecreated)/60);
			$student_id = $active_detention->studentid;

			foreach($detentions as $detention)
			{
				if($detention->studentid == $active_detention->studentid)
				{
					if(($detention->servedminutes+$mins_elapsed) > $detention->assignedminutes)
					{
						$served = $detention->assignedminutes-$detention->servedminutes;
						$this->Detention_model->update($active_detention->detentionid, $served);

						$reload_detentions = true;
					}

					break;
				}
			}
		}

		if($reload_detentions)
		{
			$detentions = $this->Detention_model->get_outstanding($school_id);
			$active_detentions = $this->Detention_model->get_active_detentions($school_id);
		}

		if(empty($detentions))
		{
			$this->layout->view('detention/nodetentions', array(
				'title_for_layout' => 'Detentions'
			));
		}
		else
		{
			$this->load->model('User_model');
			$teachers = $this->User_model->get_teachers_from_school($school_id);

			$this->layout->view('detention/manage', array(
				'title_for_layout' => 'Detentions',
				'detentions' => $detentions,
				'teachers' => $teachers,
				'activedetentions' => $active_detentions
			));
		}
	}

	function student($student_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($student_id);

		if(empty($student))
		{
			$this->layout->view('teacher/error_nostudentfound');
			return;
		}

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 't':
				if($this->User_model->has_teacher($student_id, $teacher_id))
				{
					$permission_denied = false;
				}
			break;
			case 'a':
				$this->load->model('School_model');
				if($this->School_model->has_student($school_id, $student_id))
				{
					$permission_denied = false;
				}
			break;
			case 'p':
				if($this->User_model->has_parent($student_id, $teacher_id))
				{
					$permission_denied = false;
				}
			break;			
		}

		if($permission_denied)
		{
			$this->layout->view('student/error_permissiondenied');
			return;
		}

		if($this->session->userdata('role') == 'a')
		{
			$teacher_id = 0;
		}

		$this->load->model('Detention_model');
		$detentions = $this->Detention_model->get_by_student($school_id, $teacher_id, $student_id);		

		$this->layout->view('detention/student', array(
			'detentions' => $detentions,
			'student' => $student,
			'title_for_layout' => $student->firstname.' '.$student->lastname
		));
	}

	function add($student_id)
	{
		if(!($this->session->userdata('role') == 't' || $this->session->userdata('role') == 'a'))
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($student_id);

		if(empty($student))
		{
			$this->layout->view('teacher/error_nostudentfound');
			return;
		}

		if($this->input->post('submit'))
		{
			$reason = $this->input->post('reason');
			$minutes = $this->input->post('minutes');

			$total = $minutes;

			$teacher_id = $this->session->userdata('userid');

			$this->load->model('Detention_model');
			$this->Detention_model->assign($school_id, $student_id, $teacher_id, $total, $reason);

			if(defined('FEATURE_GOALS') && FEATURE_GOALS)
			{
				$this->load->model('Goal_model');
				$goals = $this->Goal_model->get_active_goals($student_id, 'detention');

				if(!empty($goals))
				{
					foreach($goals as $goal)
					{
						$goal_completed = false;
						$details = json_decode($goal->details);

						$details->progress += $total;

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

			redirect('student/view/'.$student_id);
		}
		else
		{
			$this->load->model('Settings_model');
			$settings = json_decode($this->Settings_model->get_settings($school_id, 'quickentry'));
			$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

			$this->layout->view('detention/add', array(
				'studentid' => $student_id,
				'settings' => $settings,
				'labels' => $labels,
				'title_for_layout' => $student->firstname.' '.$student->lastname
			));
		}
	}	

	function view($detention_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Detention_model');
		$detention = $this->Detention_model->get_detention($detention_id);

		if(empty($detention))
		{
			$this->layout->view('reinforcement/error_detentionnotfound');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($detention->studentid);

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 't':
				if($this->User_model->has_teacher($student->userid, $user_id))
				{
					$permission_denied = false;
				}
			break;
			case 'a':
				if($detention->schoolid == $school_id)
				{
					$permission_denied = false;
				}
			break;
			case 'p':
				if($this->User_model->has_parent($student->userid, $user_id))
				{
					$permission_denied = false;
				}
			break;	
		}

		if($permission_denied)
		{
			$this->layout->view('reinforcement/error_permissiondenied');
			return;
		}

		$this->load->model('Settings_model');
		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

		$this->layout->view('detention/view', array(
			'detention' => $detention,
			'student' => $student,
			'labels' => $labels,
			'title_for_layout' => $labels->detention
		));
	}

	function mystudents($print='')
	{
		if($this->session->userdata('role') != 't' && $this->session->userdata('role') != 'a')
		{
			redirect('login');
			return;
		}

		$user_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Detention_model');

		switch($this->session->userdata('role'))
		{
			case 't':
				$detentions = $this->Detention_model->get_balance_from_students($school_id, $user_id);
			break;
			case 'a':
				$detentions = $this->Detention_model->get_balance_from_students($school_id);
			break;
		}

		$this->load->model('Settings_model');
		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

		if($print != 'print')
		{
			$this->layout->view('detention/mystudents', array(
				'detentions' => $detentions,
				'labels' => $labels,
				'title_for_layout' => 'Today\'s '.$labels->detentions
			));
		}
		else
		{
			$this->load->view('detention/mystudents_print', array(
				'detentions' => $detentions,
				'labels' => $labels,
				'title_for_layout' => 'Today\'s '.$labels->detentions
			));
		}
	}

	function remove($detention_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Detention_model');
		$detention = $this->Detention_model->get_detention($detention_id);

		if(empty($detention))
		{
			$this->layout->view('reinforcement/error_detentionnotfound');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($detention->studentid);

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 't':
				if($this->User_model->has_teacher($student->userid, $user_id))
				{
					$permission_denied = false;
				}
			break;
			case 'a':
				if($detention->schoolid == $school_id)
				{
					$permission_denied = false;
				}
			break;
		}

		if($permission_denied)
		{
			$this->layout->view('reinforcement/error_permissiondenied');
			return;
		}


		if($this->input->post('submit'))
		{
			$this->Detention_model->remove($detention_id);

			$balance = $this->Detention_model->get_balance($student->userid);

			// If removing this detention would leave a negative balance, we add an automatic adjustment to zero the balance.
			if($balance < 0)
			{				
				$this->Detention_model->adjust($school_id, $student->userid, $user_id, abs($balance), '[automatic adjustment]');
			}

			redirect('/student/view/'.$detention->studentid);
		}
		else
		{
			$this->load->model('Settings_model');
			$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

			$this->layout->view('detention/remove', array(
				'detention' => $detention,
				'student' => $student,
				'labels' => $labels,
				'title_for_layout' => $labels->detention
			));
		}

	}

}
?>