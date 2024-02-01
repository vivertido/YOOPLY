<?php

class Reinforcement extends MY_Controller
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
		switch($this->session->userdata('role'))
		{
			case 'a':
				$this->school();
			break;
			case 't':
			case 's':
				$this->mine();
			break;
		}		
	}

	function school($period = '')
	{
		$school_id = $this->session->userdata('schoolid');

		if($this->session->userdata('role') != 'a')
		{
			$this->layout->view('student/error_permissiondenied');
			return;
		}

		$this->_list($period, 'school', $school_id);
	}

	function mine($period = '')
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		switch($this->session->userdata('role'))
		{
			case 'a':
			case 't':
				$this->_list($period, 'mine', $school_id, $user_id);
			break;
			case 's':
				$this->_list($period, 'mine', $school_id, 0, $user_id);
			break;
		}
	}

	function _list($period, $filter, $school_id, $teacher_id = 0, $student_id = 0)
	{
		$data = array(
			'title_for_layout' => 'Reinforcements',
			'filter' => $filter
		);

		$start_time = $end_time = 0;
		switch($period)
		{
			case 'year':
				if(intval(date('m')) < 8)
				{
					$end_year = intval(date('Y'));
					$start_year = $end_year-1;
				}
				else
				{
					$start_year = intval(date('Y'));
					$end_year = $start_year+1;
				}

				$start_time = strtotime($start_year.'-08-01 00:00:00');
				$end_time = strtotime($end_year.'-07-31 23:59:59');
				$data['period'] = $period;
			break;			
			case 'month':
				$start_time = strtotime(date('Y-m-01 00:00:00'));
				$end_time = strtotime(date('Y-m-t 23:59:59'));
				$data['period'] = $period;
			break;
			case 'week':
				$start_time = strtotime('last sunday');
				$end_time = strtotime('next sunday')-1;
				$data['period'] = 'week';
			break;
			case 'all':
				$data['period'] = 'all';
			break;
			default:
			case 'today':
				$start_time = strtotime(date('Y-m-d 00:00:00'));
				$data['period'] = 'today';
			break;
		}

		$expand_student = ($student_id == 0);
		$expand_teacher = ($teacher_id == 0);

		if($this->input->post('submit'))
		{
			$expand_student = $expand_teacher = true;
		}

		$this->load->model('Reinforcement_model');
		$data['reinforcements'] = $this->Reinforcement_model->find($school_id, $teacher_id, $student_id, 0, 0, $start_time, $end_time, $expand_teacher, $expand_student);

		$this->load->model('Settings_model');
		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));
	
		$data['reinforcementlabel'] = $labels->reinforcements;
		$data['title_for_layout'] = trim($labels->reinforcements);

		if($this->input->post('submit'))
		{
			$report_ids = preg_split('/\|/', $this->input->post('reportid'));

			$filtered_list = array();
			foreach($data['reinforcements'] as $d)
			{
				if(in_array($d->interventionid, $report_ids))
				{
					array_push($filtered_list, $d);
				}
			}

			$data['reinforcements'] = $filtered_list;
			$this->layout->view('reinforcement/list_print', $data);
			return;
		}

		$this->layout->view('reinforcement/list', $data);
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
		
		$this->load->model('Reinforcement_model');
		$reinforcements = $this->Reinforcement_model->get_by_student($school_id, $teacher_id, $student_id);

		$this->layout->view('reinforcement/student', array(
			'reinforcements' => $reinforcements,
			'student' => $student,
			'title_for_layout' => $student->firstname.' '.$student->lastname
		));
	}

	function view($reinforcement_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Reinforcement_model');
		$reinforcement = $this->Reinforcement_model->get_reinforcement($reinforcement_id);

		if(empty($reinforcement) || $reinforcement->status != REINFORCEMENT_STATUS_ACTIVE)
		{
			$this->layout->view('reinforcement/error_reinforcementnotfound');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($reinforcement->studentid);

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
				if($reinforcement->schoolid == $school_id)
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

		$this->layout->view('reinforcement/view', array(
			'reinforcement' => $reinforcement,
			'student' => $student,
			'title_for_layout' => 'Reinforcement'
		));
	}

	function remove($reinforcement_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Reinforcement_model');
		$reinforcement = $this->Reinforcement_model->get_reinforcement($reinforcement_id);

		if(empty($reinforcement) || $reinforcement->status != REINFORCEMENT_STATUS_ACTIVE)
		{
			$this->layout->view('reinforcement/error_reinforcementnotfound');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($reinforcement->studentid);

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
				if($reinforcement->schoolid == $school_id)
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

		if($this->input->post('cancel'))
		{
			redirect('reinforcement/view/'.$reinforcement_id);
			return;
		}

		if($this->input->post('submit'))
		{
			$this->Reinforcement_model->remove($reinforcement_id);

			switch($this->session->userdata('role'))
			{
				case 'a':
					redirect('admin');
				break;
				case 't':
					redirect('teacher');
				break;
			}
		}
		else
		{
			$this->layout->view('reinforcement/remove', array(
				'reinforcement' => $reinforcement,
				'student' => $student,
				'title_for_layout' => 'Reinforcement'
			));
		}
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

		$this->load->model('Settings_model');
		$reinforcements = json_decode($this->Settings_model->get_settings($school_id, 'reinforcements'));

		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

		if($this->input->post('submit'))
		{
			$reason = $this->input->post('reason');
			$notes = $this->input->post('notes');
			$notify = $this->input->post('notify') === false ? array() : $this->input->post('notify');

			$date = $this->input->post('date');
			$time = $this->input->post('time');

			$time_incident = empty($date) && empty($time) || strtotime($date.' '.$time) === false ? 0 : strtotime($date.' '.$time);

			$teacher_id = $this->session->userdata('userid');

			switch($reinforcements->quantitytype)
			{
				case 'range':
					$amount = $this->input->post('positiveamount');
				break;
				case 'number':
					$amount = $this->input->post('positiveamount');
				break;
				case 'fixed':
					$amount = $reinforcements->awardamount;
				break;
			}

			$this->load->model('Reinforcement_model');
			$reinforcement_id = $this->Reinforcement_model->create($school_id, $teacher_id, $student_id, $amount, $reason, $notes, $time_incident);

			if(!empty($notify))
			{
				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

				$link = 'reinforcement/view/'.$reinforcement_id;
				$object_id = 'reinforcenet/'.$reinforcement_id;
				$text = $student->firstname.' '.$student->lastname.' has received a '.$labels->reinforcement;

				$this->load->model('Notification_model');
				foreach($notify as $receiver)
				{
					$this->Notification_model->create($receiver, $text, $link, $object_id);
				}
			}

			if(defined('FEATURE_GOALS') && FEATURE_GOALS)
			{
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
			$this->layout->view('reinforcement/add', array(
				'studentid' => $student_id,
				'settings' => $reinforcements,
				'labels' => $labels,
				'title_for_layout' => $student->firstname.' '.$student->lastname
			));
		}
	}	
}
?>