<?php

class Form extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model('Form_model');
	}

	function index()
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/form');
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		
		$forms = $this->Form_model->get_by_school($school_id);

		$data = array(
			'forms' => $forms,
			'title_for_layout' => 'Forms'
		);

		$this->layout->view('form/index', $data);
	}

	function view($form_id)
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/form.view.'.$form_id);
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$form = $this->Form_model->get_form($form_id);

		if($this->session->userdata('role') != 'a' || empty($form) || $form->status != 1 || $form->schoolid != $school_id)
		{
			$this->layout->view('form/error_permissiondenied');
			return;
		}

		$data = array('form' => $form, 'title_for_layout' => $form->title);
		$this->layout->view('form/view', $data);
	}

	function actions($form_id)
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/form.actions.'.$form_id);
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$form = $this->Form_model->get_form($form_id);

		if($this->session->userdata('role') != 'a' || empty($form) || $form->status != 1 || $form->schoolid != $school_id)
		{
			$this->layout->view('form/error_permissiondenied');
			return;
		}

		$roles = array('a' => 'Admin', 't' => 'Teacher', 's' => 'Student', 'p' => 'Parent');
		$actions = array();

		$this->load->model('Settings_model');
		$sms_messages = json_decode($this->Settings_model->get_settings($school_id, 'sms'));

		if($this->input->post('submit'))
		{
			foreach(str_split($form->contributors) as $k)
			{
				$actions[$k] = array();
				$user_actions = $this->input->post(strtolower($roles[$k])) === false ? array() : $this->input->post(strtolower($roles[$k]));

				foreach($user_actions as $a_k=>$a_v)
				{
					$value = array();
					switch($a_k)
					{
						case 'smsparent':
							$value = array('message' => $this->input->post(strtolower($roles[$k]).'smsparentmessage'));
						break;
						case 'smsteacher':
							$value = array('message' => $this->input->post(strtolower($roles[$k]).'smsteachermessage'));
						break;						
						case 'smsadmin':
							$value = array('message' => $this->input->post(strtolower($roles[$k]).'smsadminmessage'));
						break;
						default:
							
						break;
					}
					
					$actions[$k][$a_k] = $value;
				}

				$notify_users = $this->input->post('notify'.strtolower($roles[$k]));

				if($notify_users !== false)
				{
					// Filter users.
					//print_r($notify_users);
					$actions[$k]['notify'] = $notify_users;
				}
			}

			$this->Form_model->set_actions($form_id, $actions);

			redirect('form/view/'.$form_id);
		}
		else
		{
			$data = array(
				'form' => $form, 
				'title_for_layout' => 'Actions for '.$form->title,
				'roles' => $roles,
				'sms' => $sms_messages
			);

			$form->actions = $actions = json_decode($form->actions, true);

			$notify_userids = array();
			foreach($actions as $k=>$options)
			{
				if(isset($options['notify']))
				{
					$notify_userids = array_merge($notify_userids, $options['notify']);
				}
			}

			$notify_userids = array_unique($notify_userids);

			if(!empty($notify_userids))
			{
				$this->load->model('User_model');
				$users = $this->User_model->get_users($notify_userids);

				$data['notifyusers'] = array();
				foreach($users as $u)
				{
					$data['notifyusers'][$u->userid] = $u;
				}
			}

			$this->layout->view('form/actions', $data);
		}
	}

	function respond($form_id, $subject_id = '0')
	{
		//if not logged in redirect to login and keep id and form id
		if(!$this->session->userdata('userid'))
		{
			redirect('login/form.respond.'.$form_id.($subject_id != '0' ? '.'.$subject_id : ''));
			return;
		}

		//get the user and school
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');
		 

		//get the form by ID
		$form = $this->Form_model->get_form($form_id);

		if(empty($form) || $form->status != 1 || $form->schoolid != $school_id)
		{

			$this->layout->view('form/error_permissiondenied');
			return;
		}

		//deny permission to those who are not on the list of contributors or if not logged in 
		if(strpos($form->contributors, $this->session->userdata('role')) === false)
		{
			$this->layout->view('form/error_permissiondenied');
			return;	
		}

		if($this->session->userdata('role') == 's')
		{
			$subject_id = $user_id;
		}

		$form_data = json_decode($form->formdata);

		$custom_notifications_enabled = isset($form_data->options) && isset($form_data->options->customnotifications);

		if($this->input->post('submit'))
		{
			$questions = json_decode($form->formdata);
			$report = process_form('f', $questions->questions);

			$title = '';
			$time_incident = time();

			if(!empty($form->indextitle))
			{
				foreach($report as $q)
				{
					if($q['label'] == $form->indextitle)
					{
						$title = is_array($q['value']) ? implode(',', $q['value']) : $q['value'];
					}
				}
			}

			if(!empty($form->timetitle))
			{
				foreach($report as $q)
				{
					if($q['label'] == $form->timetitle)
					{
						$time_incident = strtotime($q['value']);
					}
				}
			}			

			$this->load->model('Report_model');
			$response_id = $this->Report_model->create($school_id, $user_id, $subject_id, 'form', $form_id, $report, $title, $time_incident);

			$actions = json_decode($form->actions, true);

			$role = $this->session->userdata('role');

			$notify = array();

			// If form has per person notifications enabled, add these to the people who we should notify.
			if($custom_notifications_enabled)
			{
				$receivers = $this->input->post('notify');

				if(is_array($receivers))
				{
					$this->load->model('School_model');
					foreach($receivers as $uid)
					{
						if($this->School_model->has_user($school_id, $uid))
						{
							array_push($notify, $uid);
						}
					}	
				}
			}

			if(!empty($actions) && isset($actions[$role]))
			{
				foreach($actions[$role] as $type => $action)
				{
					switch($type)
					{
						case 'notify':
							if(!empty($action))
							{
								$notify = array_merge($notify, $action);
							}
						break;
						case 'emailparent':
							if($subject_id !== '0')
							{
								$this->load->model('Email_model');
								$this->load->model('User_model');
								$this->load->model('Log_model');

								$report = $this->Report_model->get_response($response_id);
								$parents = $this->User_model->get_parents($subject_id);
								$student = $this->User_model->get_user($subject_id);
								$reporter = $this->User_model->get_user($user_id);

								foreach($parents as $parent)
								{
									if(!empty($parent->email))
									{
										$this->Email_model->email_report($parent, $student, $reporter, $form, $report);
										$this->Log_model->email_set($user_id, $parent->email, $parent->userid, $student->userid, $report->reportid);
									}
								}
							}
						break;
						case 'smsparent':
							$type = $action['message'];
							$data = array();
							
							$this->load->model('User_model');

							if($subject_id !== '0')
							{
								$data['student'] = $this->User_model->get_user($subject_id);
							}

							$data['reporter'] = $this->User_model->get_user($user_id);							

							$parents = $this->User_model->get_parents($subject_id);

							$this->load->model('Sms_model');
							$this->load->model('Profile_model');

							foreach($parents as $parent)
							{
								if(!empty($parent->phone))
								{
									$notification_preference = $this->Profile_model->get_notifications($parent->userid);

									// Check that the receiver wants SMS sent to them.
									if(isset($notification_preference->sms) && $notification_preference->sms == '1')
									{
										$to = $parent->phone;
										$this->Sms_model->send($school_id, $to, $type, $data);
									}
								}
							}
						break;
						case 'smsteacher':
							$type = $action['message'];
							$data = array();

							if($subject_id !== '0')
							{
								$data['student'] = $this->User_model->get_user($subject_id);
							}

							$data['reporter'] = $this->User_model->get_user($user_id);							

							$teachers = $this->User_model->get_teachers($subject_id);

							$this->load->model('Sms_model');
							$this->load->model('Profile_model');

							foreach($teachers as $teacher)
							{
								if(!empty($teacher->phone))
								{
									$notification_preference = $this->Profile_model->get_notifications($teacher->userid);

									// Check that the receiver wants SMS sent to them.
									if(isset($notification_preference->sms) && $notification_preference->sms == '1')
									{									
										$to = $teacher->phone;
										$this->Sms_model->send($school_id, $to, $type, $data);
									}
								}
							}
						break;	
						case 'smsadmin':
							$type = $action['message'];
							$data = array();

							if($subject_id !== '0')
							{
								$data['student'] = $this->User_model->get_user($subject_id);
							}

							$data['reporter'] = $this->User_model->get_user($user_id);							

							$admins = $this->User_model->get_admins_from_school($school_id);

							$this->load->model('Sms_model');
							$this->load->model('Profile_model');

							foreach($admins as $admin)
							{
								if(!empty($admin->phone))
								{
									$notification_preference = $this->Profile_model->get_notifications($admin->userid);

									// Check that the receiver wants SMS sent to them.
									if(isset($notification_preference->sms) && $notification_preference->sms == '1')
									{	
										$to = $admin->phone;
										$this->Sms_model->send($school_id, $to, $type, $data);
									}
								}
							}
						break;													
					}
				}
			}

			$notify = array_unique($notify);

			if(!empty($notify))
			{
				$this->load->model('User_model');
				$reporter = $this->User_model->get_user($user_id);
				
				$this->load->model('Notification_model');
				foreach($notify as $receiver)
				{
					$text = $reporter->firstname.' '.$reporter->lastname.' has submitted a '.$form->title;
					$link = 'report/response/'.$response_id;
					$object_id = 'report/'.$response_id;
					
					$this->Notification_model->create($receiver, $text, $link, $object_id);
				}
			}

			if(defined('FEATURE_GOALS') && FEATURE_GOALS)
			{
				$this->load->model('Goal_model');
				$goals = $this->Goal_model->get_active_goals($subject_id, 'form', $form_id);

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

			redirect('report/response/'.$response_id);
		}
		else
		{
			$data = array(
				'title_for_layout' => $form->title,
				'form' => $form,	
				'customnotificationsenabled' => $custom_notifications_enabled
			);

			if($subject_id != '0')
			{
				$data['subjectid'] = $subject_id;
			}

			

			$this->load->model('User_model');

			$subject_first_name  = $this->User_model->get_user($subject_id)->firstname;
			$subject_last_name = $this->User_model->get_user($subject_id)->lastname;
				
			$data['name'] =$subject_first_name;
			$data['lastname'] = $subject_last_name;

			$this->layout->view('form/respond', $data);
		}
	}

	function add()
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/form.add');
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$error = '';

		if($this->input->post('submit'))
		{
			$title = $this->input->post('title');
			$viewers = $this->input->post('viewers') === false ? array() : $this->input->post('viewers');
			$contributors = $this->input->post('contributors') === false ? array() : $this->input->post('contributors');
			$subjects = $this->input->post('subject') === false ? array() : $this->input->post('subject');
			$index_title = $this->input->post('indextitle');
			$time_title = $this->input->post('timetitle');

			$contributors_string = $viewers_string = $subjects_string = '';
			$acceptable_values = array('s', 't', 'a', 'p');

			if(strlen(trim($title)) == 0)
			{
				$error = 'title';
			}

			foreach($contributors as $k=>$v)
			{
				if(in_array($k, $acceptable_values))
				{
					$contributors_string .= $k;
				}
			}

			if(empty($error) && empty($contributors_string))
			{
				$error = 'nocontributors';
			}

			foreach($viewers as $k=>$v)
			{
				if(in_array($k, $acceptable_values))
				{
					$viewers_string .= $k;
				}
			}

			if(empty($error) && empty($contributors_string))
			{
				$error = 'noviewers';
			}

			if(in_array($subjects, $acceptable_values))
			{
				$subjects_string = $subjects;
			}

			$options = array();
			if($this->input->post('notify'))
			{
				$options['customnotifications'] = true;
			}

			$form_data = array(
				'questions' => process_edit_form('f', $this->input->post('key')),
				'options' => $options
			);
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$form_id = $this->Form_model->create($school_id, $title, $viewers_string, $contributors_string, $subjects_string, $form_data, array(), $index_title, $time_title);

			redirect('form/view/'.$form_id);
		}		
		else
		{
			$data = array(
				'title_for_layout' => 'Add', 
				'form' => new stdClass()
			);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['form']->contributors = $contributors_string;
				$data['form']->viewers = $viewers_string;
				$data['form']->subject = $subjects_string;
				$data['form']->title = $title;
				$data['form']->formdata = json_decode(json_encode($form_data));
				$data['form']->indextitle = $index_title;
				$data['form']->timetitle = $time_title;		
				$data['customnotificationsenabled'] = $this->input->post('notify');
			}

			$this->layout->view('form/add', $data);
		}

	}

	function edit($form_id)
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/form.edit.'.$form_id);
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$form = $this->Form_model->get_form($form_id);

		if($this->session->userdata('role') != 'a' || empty($form) || $form->status != 1 || $form->schoolid != $school_id)
		{
			$this->layout->view('form/error_permissiondenied');
			return;
		}

		$form->formdata = json_decode($form->formdata);

		$error = '';

		if($this->input->post('submit'))
		{
			$title = $this->input->post('title');
			$viewers = $this->input->post('viewers') === false ? array() : $this->input->post('viewers');
			$contributors = $this->input->post('contributors') === false ? array() : $this->input->post('contributors');
			$subjects = $this->input->post('subject') === false ? array() : $this->input->post('subject');
			$index_title = $this->input->post('indextitle');
			$time_title = $this->input->post('timetitle');

			$contributors_string = $viewers_string = $subjects_string = '';
			$acceptable_values = array('s', 't', 'a', 'p');

			if(empty($error) && strlen(trim($title)) == 0)
			{
				$error = 'title';
			}

			foreach($contributors as $k=>$v)
			{
				if(in_array($k, $acceptable_values))
				{
					$contributors_string .= $k;
				}
			}

			if(empty($contributors_string))
			{
				$error = 'nocontributors';
			}

			foreach($viewers as $k=>$v)
			{
				if(in_array($k, $acceptable_values))
				{
					$viewers_string .= $k;
				}
			}

			if(empty($error) && empty($contributors_string))
			{
				$error = 'noviewers';
			}

			if(in_array($subjects, $acceptable_values))
			{
				$subjects_string = $subjects;
			}

			$form_data = array(
				'questions' => process_edit_form('f', $this->input->post('key')),
				'options' => array('customnotifications' => $this->input->post('notify'))
			);
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$actions = json_decode($form->actions, true);
			$this->Form_model->update($form_id, $title, $viewers_string, $contributors_string, $subjects_string, $form_data, $actions, $index_title, $time_title);

			redirect('form/view/'.$form_id);
		}		
		else
		{
			$data = array(
				'title_for_layout' => 'Edit', 
				'form' => $form
			);

			$data['customnotificationsenabled'] = isset($form->formdata->options) && isset($form->formdata->options->customnotifications);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['form']->contributors = $contributors_string;
				$data['form']->viewers = $viewers_string;
				$data['form']->subject = $subjects_string;
				$data['form']->title = $title;
				$data['form']->formdata = json_decode(json_encode($form_data));
				$data['form']->indextitle = $index_title;
				$data['form']->timetitle = $time_title;

				$data['customnotificationsenabled'] = $this->input->post('notify');
			}

			$this->layout->view('form/edit', $data);
		}
	}

	function remove($form_id)
	{
		$school_id = $this->session->userdata('schoolid');

		if(!$this->session->userdata('userid'))
		{
			redirect('login/form.remove.'.$form_id);
			return;
		}

		$this->load->model('Form_model');
		$form = $this->Form_model->get_form($form_id);

		if($this->session->userdata('role') != 'a' || empty($form) || $form->status != 1 || $form->schoolid != $school_id)
		{
			$this->layout->view('form/error_permissiondenied');
			return;
		}

		if($this->input->post('cancel'))
		{
			redirect('form');
			return;
		}

		if($this->input->post('submit'))
		{
			$this->load->model('Settings_model');
			

			$paths = implode('|', array(
				'^\/form\/respond\/'.$form_id.'\/*',
				'^\/report\/form\/'.$form_id.'\/*'
			));

			foreach(array('admin', 'teacher', 'student') as $role)
			{
				$settings = json_decode($this->Settings_model->get_settings($school_id, 'dash'.$role));

				$new_menu = array();
				$changed = false;

				foreach($settings->menu as $k=>$v)
				{
					if(preg_match('/'.$paths.'/', $k))
					{
						$changed = true;
						continue;
					}

					$new_menu[$k] = $v;
				}

				$this->load->model('Report_model');

				$this->Form_model->remove($form_id);
				$this->Report_model->remove_form($form_id);

				if($changed)
				{
					$settings->menu = $new_menu;


					$this->Settings_model->save($school_id, 'dash'.$role, $settings);
				}
			}

			redirect('form');			
		}
		else
		{
			$this->layout->view('form/remove', array('title_for_layout' => 'Remove Form', 'form' => $form));
		}
	}

}