<?php

class Goal extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
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

	function student($student_id, $period = '')
	{
		if($this->session->userdata('role') == 's')
		{
			redirect('goals/mine/'.$period);
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('role') == 'a' ? 0 : $this->session->userdata('userid');
		
		$this->_list($period, 'student/'.$student_id, $school_id, $teacher_id, $student_id);
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
			'title_for_layout' => 'Goals',
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
				$end_time = strtotime(date('Y-m-d 23:59:59'));
				$data['period'] = 'today';
			break;
		}

		$expand_student = ($student_id == 0);
		$expand_teacher = ($teacher_id == 0);

		$this->load->model('Goal_model');
		$data['goals'] = $this->Goal_model->find($school_id, $teacher_id, $student_id, $start_time, $end_time, $expand_teacher, $expand_student);

		$this->layout->view('goal/list', $data);
	}	

	function add($student_id)
	{
		$this->load->model('User_model');
		$student = $this->User_model->get_user($student_id);

		$teacher_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');

		$error = '';

		if($this->input->post('submit'))
		{
			$time_due = $this->input->post('timedue');
			$metric = $this->input->post('metric');
			$quantity = $this->input->post('quantity');
			$goal_type = $this->input->post('goaltype');
			$notes = $this->input->post('notes');
			$notify = $this->input->post('notify');

			if(empty($time_due) || strtotime($time_due) === false || strtotime($time_due.' 23:59:59') < time())
			{
				$error = 'timedue';
			}

			$time_due = strtotime($time_due.' 23:59:59');

			if(empty($error) && $quantity == '')
			{
				$error = 'quantity';
			}
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$details = array(
				'quantity' => $quantity,
				'type' => $goal_type,
				'notes' => $notes,
				'progress' => 0,
			);

			if($notify !== false)
			{
				$details['notify'] = array_values($notify);
			}
			
			if(substr($metric, 0, 4) == 'form')
			{
				$object_type = 'form';
				$object_id = substr($metric, 5);

				$this->load->model('Form_model');
				$form = $this->Form_model->get_form($object_id);

				$metric = $form->title.' report';
				$details['metric'] = $metric;
				$objectname = $form->title.' report';
			}
			else
			{
				$object_type = $metric;
				$object_id = '';
				
				$this->load->model('Settings_model');
				$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'), true);
				
				if(isset($labels[$metric]))
				{
					if($metric == 'detention')
					{
						$details['metric'] = strtolower($labels[$metric.'unit']);
						$objectname = strtolower($labels[$metric.'unit']);
					}
					else
					{
						$details['metric'] = strtolower($labels[$metric]);	
						$objectname = strtolower($labels[$metric]);
					}	
				}
			}

			$title = '';
			switch($goal_type)
			{
				case 'atleast':
					$title = 'at least';
				break;
				case 'atmost':
					$title = 'at most';
				break;
			}

			$title = 'Get '.$title.' '.$quantity.' '.$objectname.($quantity == 1 ? '' : 's');

			$this->load->model('Goal_model');
			$goal_id = $this->Goal_model->create($school_id, $teacher_id, $student_id, $object_type, $object_id, $title, $details, $time_due);

			if($notify !== false)
			{
				$receipients = array();

				foreach($notify as $r)
				{
					switch($r)
					{
						case 'teachers':
							$teachers = $this->User_model->get_teachers($student->userid);

							foreach($teachers as $u)
							{
								array_push($receipients, $u->userid);
							}
						break;
						case 'parents':
							$parents = $this->User_model->get_parents($student->userid);

							foreach($parents as $u)
							{
								array_push($receipients, $u->userid);
							}
						break;
						case 'admins':
							$admins = $this->User_model->get_admins_from_school($school_id);

							foreach($admins as $u)
							{
								array_push($receipients, $u->userid);
							}
						break;	
						default:
							array_push($receipients, $r);
						break;
					}
				}

				$this->load->model('User_model');
				$student = $this->User_model->get_user($student_id);

				$this->load->model('Notification_model');

				$receipients = array_unique($receipients);
				foreach($receipients as $receiver)
				{
					// Ignore notifying the person creating this goal.
					if($receiver == $teacher_id)
					{
						continue;
					}

					$who = $receiver == $student->userid ? 'You have' : $student->firstname.' '.$student->lastname.' has';
					$text =  $who.' a new goal.';
					$link = 'goal/view/'.$goal_id;
					$object_id = 'goal/'.$goal_id;
					$this->Notification_model->create($receiver, $text, $link, $object_id);
				}
			}

			redirect('goal/view/'.$goal_id);
		}
		else
		{
			$data = array(
				'student' => $student, 
				'title_for_layout' => 'New Goal'
			);

			if(!empty($error))
			{
				$data['error'] = $error;
			}

			$role = $this->session->userdata('role');

			$this->load->model('Form_model');
			$data['forms'] = $this->Form_model->get_assignable($school_id, $role, 's');

			$this->load->model('Settings_model');
			$data['labels'] = json_decode($this->Settings_model->get_settings($school_id, 'labels'), true);


			$this->layout->view('goal/add', $data);
		}
	}

	function view($goal_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Goal_model');
		$goal = $this->Goal_model->get_goal($goal_id);

		if(empty($goal) || $goal->status == GOAL_STATUS_REMOVED)
		{
			$this->layout->view('goal/error_goalnotfound');
			return;
		}

		$goal->details = json_decode($goal->details);

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 'a':
				if($goal->schoolid == $school_id)
				{
					$permission_denied = false;
				}
			break;
			case 't':
				if($goal->teacherid == $user_id)
				{
					$permission_denied = false;
				}
			break;
			case 's':
				if($goal->studentid == $user_id)
				{
					$permission_denied = false;
				}
			break;			
			case 'p':
				if($this->User_model->has_parent($goal->studentid, $user_id))
				{
					$permission_denied = false;
				}
			break;	
		}

		// If this person is on the notify list, let them see the goal when they wouldn't otherwise see it.
		if($permission_denied && !empty($goal->details->notify))
		{
			if(array_search($user_id, $goal->details->notify) !== false)
			{
				$permission_denied = false;
			}
		}

		if($permission_denied)
		{
			$this->layout->view('goal/error_permissiondenied');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($goal->studentid);
		$teacher = $this->User_model->get_user($goal->teacherid);
		
		$data = array(
			'goal' => $goal, 
			'student' => $student,
			'teacher' => $teacher,
			'title_for_layout' => $goal->title
		);

		switch($this->session->userdata('role'))
		{
			case 'a':
				if($goal->schoolid == $school_id)
				{
					$data['candelete'] = true;
				}
			break;
			case 't':
				if($goal->teacherid == $user_id)
				{
					$data['candelete'] = true;
				}
			break;
		}		

		$data['objectname'] = $goal->details->metric;
		$this->layout->view('goal/view', $data);
	}			

	function remove($goal_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$teacher_id = $this->session->userdata('userid');

		$this->load->model('Goal_model');
		$goal = $this->Goal_model->get_goal($goal_id);

		if(empty($goal) || $goal->status == GOAL_STATUS_REMOVED)
		{
			$this->layout->view('goal/error_goalnotfound');
			return;
		}

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 'a':
				if($goal->schoolid == $school_id)
				{
					$permission_denied = false;
				}
			break;
			case 't':
				if($goal->teacherid == $teacher_id)
				{
					$permission_denied = false;
				}
			break;
		}

		if($permission_denied)
		{
			$this->layout->view('goal/error_permissiondenied');
			return;
		}

		if($this->input->post('cancel'))
		{
			redirect('goal/view/'.$goal->goalid);
			return;
		}

		if($this->input->post('submit'))
		{
			$this->Goal_model->remove($goal_id);

			$this->load->model('Notification_model');
			$this->Notification_model->remove_by_object('goal', $goal_id);

			redirect('goal/student/'.$goal->studentid);
		}
		else
		{
			$this->layout->view('goal/remove', array(
				'goal' => $goal
			));
		}
	}
}