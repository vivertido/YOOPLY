<?php

class Student extends MY_Controller
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
		if($this->session->userdata('role') != 's')
		{
			redirect('login');
			return;
		}

		$student_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Reinforcement_model');
		$dollar_total = $this->Reinforcement_model->get_dollar_total($student_id);

		$this->load->model('Referral_model');
		$referrals = $this->Referral_model->get_active_by_student($student_id);

		$this->load->model('Detention_model');
		$detention_minutes = $this->Detention_model->get_balance($student_id);
		$detention_minutes = !empty($detention_minutes) ? $detention_minutes : 0;

		$this->load->model('User_model');
		$student = $this->User_model->get_user($student_id);

		$this->load->model('Settings_model');
		$reinforcement_settings = json_decode($this->Settings_model->get_settings($school_id, 'reinforcements'));
		$features = json_decode($this->Settings_model->get_settings($school_id, SETTINGS_FEATURES));
		$demerit_settings = json_decode($this->Settings_model->get_settings($school_id, 'demerits'));
		$menu = json_decode($this->Settings_model->get_settings($school_id, 'dashstudent'));
		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

		$this->layout->view('student/dashboard', array(
			'referrals' => $referrals,
			'dollars' => $dollar_total,
			'student' => $student,
			'detentionminutes' => $detention_minutes,
			'reinforcementsettings' => $reinforcement_settings,
			'title_for_layout' => 'Yoop.ly',
			'demeritlabel' => $demerit_settings->demeritlabel,
			'features' => $features,
			'dashboardsettings' => $menu,
			'labels' => $labels
		));
	}

	function shoutouts($type = '')
	{
		if($this->session->userdata('role') != 's')
		{
			redirect('login');
			return;
		}

		$user_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Shoutout_model');

		$data = array(
			'title_for_layout'=> 'Shout-outs'
		);

		$subview = '';
		switch($type)
		{
			case 'mine':
				$subview = '_mine';

				$data['shoutouts'] = $this->Shoutout_model->get_with_user($user_id);
				$data['title_for_layout'] = 'My Shout-outs';
			break;
			case 'friend':
				$subview = '_friend';

				$data['shoutouts'] = $this->Shoutout_model->get_with_friend($user_id);
				$data['title_for_layout'] = 'All Shout-outs';
			break;
			default:
				$data['shoutouts'] = $this->Shoutout_model->get_with_user($user_id, 6);
				$data['all_shoutouts'] = $this->Shoutout_model->get_with_friend($user_id, 6);
				$data['school_shoutouts'] = $this->Shoutout_model->get_with_school_today($school_id);
			break;
		}

		$this->layout->view('student/shoutouts'.$subview, $data);
	}

	function awards()
	{
		if($this->session->userdata('role') != 's')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$student_id = $this->session->userdata('userid');

		$this->load->model('Reinforcement_model');
		$reinforcements = $this->Reinforcement_model->get_recent_reinforcements($student_id);
		$dollars = $this->Reinforcement_model->get_dollar_total($student_id);
		$totals = $this->Reinforcement_model->get_dollar_total_months($student_id);
		$spent = $this->Reinforcement_model->count_spent($school_id, $student_id);

		$this->load->model('Settings_model');
		$reinforcement_settings = json_decode($this->Settings_model->get_settings($school_id, 'reinforcements'));
	
		$data = array(
			'reinforcements' => $reinforcements,
			'dollars' => $dollars,
			'spent' => $spent,
			'totals' => $totals,
			'title_for_layout' => 'My '.$reinforcement_settings->awardlabel
		);

		$data['dollarlabel'] = $reinforcement_settings->awardlabel;		

		$this->layout->view('student/awards', $data);
	}

	function incident($referral_id)
	{
		if($this->session->userdata('role') != 's')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'incident'));

		$this->load->model('Referral_model');
		$referral = $this->Referral_model->get_referral($referral_id);

		$user_id = $this->session->userdata('userid');

		if(empty($referral) || $referral->studentid != $user_id)
		{
			$this->layout->view('student/error_referralnotfound');
			return;
		}

		// Has student already completed a reflection, then must have completed incident.
		if(!empty($referral->reflection))
		{
			redirect('reflections/view/'.$referral_id);
			return;
		}

		// Has student already completed incident, send them to reflect.
		if(!empty($referral->studentnotes))
		{
			redirect('reflections/reflect/'.$referral_id);
			return;
		}

		if($this->input->post('submit'))
		{
			$data = array();
			$mode = $this->input->post('mode');

			switch($mode)
			{
				case 'easy':
					$report = process_form('f', $settings->questions->easy);
				break;
				case 'detailed':
					$report = process_form('f', $settings->questions->detailed);
				break;
				default:
					echo "missing mode";exit;
				break;
			}

			$this->load->model('Referral_model');
			$this->Referral_model->save_student($referral_id, $report);

			redirect('reflections/reflect/'.$referral_id);
		}
		else
		{
			$this->layout->view('student/incident', array(
			'title_for_layout' => 'Incident Report',
			'questions' => $settings->questions,
			'referralid' => $referral_id));
		}
	}

	function mydetentions()
	{
		if($this->session->userdata('role') != 's')
		{
			redirect('login');
			return;
		}

		$student_id = $this->session->userdata('userid');

		$this->load->model('Detention_model');
		$detentions = $this->Detention_model->get_assigned_from_student($student_id, true);
		$assigned_totals = $this->Detention_model->get_assigned_total_months($student_id);
		$served_total = $this->Detention_model->count_served_from_student($student_id);
		$balance = $this->Detention_model->get_balance($student_id);

		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');	
		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

		$this->layout->view('student/mydetentions', array(
			'detentions' => $detentions,
			'balance' => $balance,
			'totalserved' => $served_total,
			'assignedtotals' => $assigned_totals,
			'title_for_layout' => 'My '.$labels->detentions
		));
	}

	function bully()
	{
		if($this->session->userdata('role') != 's')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'bully'));

		if($this->input->post('submit'))
		{
			$report = process_form('f', $settings->questions);

			$this->load->model('Report_model');
			$this->Report_model->create($school_id, $user_id, 'bully', $report);

			redirect('student');
		}
		else
		{
			$this->layout->view('student/bully', array(
				'settings' => $settings,
				'title_for_layout' => 'Report Bullying'
			));
		}
	}

	function view($student_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$user = $this->User_model->get_user($student_id);

		if(empty($user))
		{
			$this->layout->view('teacher/error_nostudentfound');
			return;
		}

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 't':
				if($this->User_model->has_teacher($student_id, $user_id))
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
				if($this->User_model->has_parent($student_id, $user_id))
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

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'reinforcements'));

		// Permission checks complete, let's show the view.
		$this->load->model('Reinforcement_model');
		$dollar_total = $this->Reinforcement_model->get_dollar_total($student_id);

		$this->load->model('Referral_model');
		$active_referrals = $this->Referral_model->get_active_by_student($student_id, $user_id);
		$referral_count = $this->Referral_model->get_count_by_student($student_id);

		$this->load->model('Shoutout_model');
		$shoutout_count = $this->Shoutout_model->get_count_to_user($student_id);
		
		$this->load->model('Settings_model');
		$feature_settings = json_decode($this->Settings_model->get_settings($school_id, SETTINGS_FEATURES));

		$data = array(
			'referralcount' => $referral_count,
			'activereferrals' => $active_referrals,
			'shoutouts' => $shoutout_count,
			'dollarlabel' => $settings->reinforcementlabel,
			'user' => $user,
			'role' => $this->session->userdata('role'),
			'title_for_layout' => $user->firstname.' '.$user->lastname.' (Grade '.$user->grade.'; SSID '.$user->studentid.')',
			'dollartotal' => $dollar_total,
			'settings' => $feature_settings,
		);

		$this->load->model('Form_model');
		$data['formsassign'] = $this->Form_model->get_assignable($school_id, $this->session->userdata('role'), 's');
		$data['formsview'] = $this->Form_model->get_viewable($school_id, $this->session->userdata('role'), 's');

		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));
	
		$data['labels'] = $labels;		

		$this->layout->view('student/view', $data);
	}

	function interventions($period = '')
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$data = array(
			'title_for_layout' => 'My Interventions',
		);

		$start_time = $end_time = 0;
		switch($period)
		{
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

		$this->load->model('Intervention_model');
		$data['interventions'] = $this->Intervention_model->get_by_student($school_id, $user_id, 0, true, $start_time, $end_time);

		$this->layout->view('student/interventions', $data);
	}

	function edit($student_id)
	{
		//this is to edit student information /student/edit/<id>

		//grab the session school and user id
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		//we'll be updating both the user and the group, if changed
		$this->load->model('User_model');
		$this->load->model('Group_model');

		$user = $this->User_model->get_user($student_id);

		if(empty($user))
		{
			//no student found
			$this->layout->view('teacher/error_nostudentfound');
			return;
		}

		//initially deny persmission
		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
		/*	case 't':
				if($this->User_model->has_teacher($student_id, $user_id))
				{
					$permission_denied = false;
				}
			break;*/

			//if the logged in user is an admim load the school model
			case 'a':
				$this->load->model('School_model');

				//if the student exists let us edit
				if($this->School_model->has_student($school_id, $student_id))
				{
					$permission_denied = false;
				}
			break;
		}


		if($permission_denied)
		{
			//no student data, so redirect
			$this->layout->view('student/error_permissiondenied');
			return;
		}

		//now that we have established that the student exist, let us edit

		//grab all groups in the school. 
		$all_groups = $this->Group_model->get_groups_by_school($school_id); 
		$user_groups = $this->Group_model->get_user_groups($student_id); //get the groups the student is currently in
		$parents = $this->User_model->get_parents($student_id);

		$this->load->model('School_model');
		$school = $this->School_model->get_school($school_id); //get the right school by its ID

		$school->metadata = json_decode($school->metadata); 

		//not sure why this is here...
		$email_signin_enabled = isset($school->metadata->emailsignin) && $school->metadata->emailsignin;


		//when the user clicks Save updates on edit student view first get all the text input POST values
		if($this->input->post('submit'))
		{
			$error = '';
			$first_name = $this->input->post('firstname'); 
			$last_name = $this->input->post('lastname');
			$email = $this->input->post('email');
			$groups = $this->input->post('group');
			$grade = $this->input->post('grade');
			$studentid = $this->input->post('studentid');
			$gender = $this->input->post('gender');
			$dob = $this->input->post('dob');
			$ethnicity = $this->input->post('ethnicity');
			$parent = $this->input->post('parent');
			$existing_parents = $this->input->post('parents');

			if($existing_parents === false)
			{
				$existing_parents = array(); // when no parens are currently present, create an array to hold potential additions
			}

			if($parent === false)
			{
				$parent = array(); //if no parent exist, create a parent array for new parents
			}

			foreach($parent as $p)
			{
				//save errors  if no first or last name is entered
				if(empty($p['firstname']) && !empty($p['lastname']))
				{
					$error = 'parentfirstname'; 
					break;
				}

				if(!empty($p['firstname']) && empty($p['lastname']))
				{
					$error = 'parentlastname';
					break;
				}
			}

			//when no group is selected...
			if(empty($error) && $groups == false)
			{
				//$error = 'nogroupsslected';

				//need to prompt user to confirm setting student as Unassigned...

				$suggest_unassigned = "Do you want to make this an unassigned student?";
			}

			//set all the rest of the errors if missing from required fields
			if(empty($error) && empty($first_name))
			{
				$error = 'firstname';
			}

			if(empty($error) && empty($last_name))
			{
				$error = 'lastname';
			}

			if(empty($error) && empty($email))
			{
				$error = 'email';
			}

			if(empty($error))
			{	
				//check to see if the entered email is already clamimed by other user
				$email_user = $this->User_model->find_by_email($email);

				if(!empty($email_user) && $email_user->userid != $student_id)
				{
					$error = 'emailinuse';
				}
			}

			if($email_signin_enabled)
			{
				//set the new username and password if the account allows for email login
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				if(empty($username))
				{	
					//user didn't enter a username
					$error = empty($error) ? 'usernameempty' : $error; 
				}
				else
				{	
					//username was entered, now check to see if the username is taken
					$user_check = $this->User_model->find_by_username($username);

					if(!empty($user_check) && $user_check->userid != $student_id)
					{
						$error = empty($error) ? 'usernameinuse' : $error; 
					}
				}
			}
		}
		
		//now that everything checks out...
		if(empty($error) && $this->input->post('submit'))
		{	
			//set the dob to zero if not entered. who cares!
			$dob = strtotime($dob);

			if($dob == 0)
			{
				$dob = '0000-00-00';
			}
			else
			{
				$dob = date('Y-m-d', $dob);
			}

			//we are ready to update the student personal info
			$this->User_model->update($student_id, $first_name, $last_name, $email, $grade, $studentid, $gender, $dob, $ethnicity);

			foreach($parents as $p)
			{
				if(!isset($existing_parents[$p->userid]))
				{
					$this->User_model->remove_parent($student_id, $p->userid);
				}
			}

			foreach($parent as $p)
			{
				if(!empty($p['firstname']) && !empty($p['lastname']))
				{
					$u = empty($p['email']) ? array() : $this->User_model->find_by_email($p['email']);

					if(!empty($u))
					{
						$p_userid = $u->userid;
					}
					else
					{
						$p_userid = $this->User_model->create_parent($school_id, $p['firstname'], $p['lastname'], '', '', $p['email'], $p['phone'], 'blobsmall.png');
					}

					if(!$this->User_model->has_parent($student_id, $p_userid))
					{
						$this->User_model->add_parent($student_id, $p_userid);
					}
				}
			}
			

			// Add to new groups.
			foreach($groups as $k=>$v)
			{
				$in_group = false;
				foreach($user_groups as $ug)
				{
					if($ug->groupid == $k)
					{
						$in_group = true;
					}
				}

				if(!$in_group)
				{
					//add the studnet to the right group by their ID only if not already in the group
					$this->Group_model->add_student($k, $student_id);
				}
			}

			// for all of the curren classes the student is in...
			foreach($user_groups as $ug)
			{
				//first set all as remove true
				$remove = true;
				foreach($groups as $k=>$v)
				{
					if($ug->groupid == $k)
					{
						$remove = false;
					}
				}

				if($remove)
				{
					$this->Group_model->remove_student($ug->groupid, $student_id);
				}
			}

			if($email_signin_enabled && ($user->username != $username || !empty($password)))
			{
				if(empty($password))
				{
					$this->User_model->change_username($student_id, $username);
				}
				else
				{
					$this->User_model->change_login($student_id, $username, $password);
				}
			}

			//after update, send us back to the individual student view
			redirect('student/view/'.$student_id);
		}
		else
		{
			$data = array(
				'user' => $user,
				'allgroups' => $all_groups,
				'usergroups' => $user_groups,
				'title_for_layout' => 'Edit Student',
				'parents' => $parents,
				'emailsigninenabled' => $email_signin_enabled
			);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['user']->firstname = $first_name;
				$data['user']->lastname = $last_name;
				$data['user']->email = $email;
				$data['user']->grade = $grade;
				$data['user']->studentid = $studentid;
				$data['user']->gender = $gender;
				$data['user']->dob = $dob;
				$data['user']->ethnicity = $ethnicity;
				$data['formparents'] = $parent;
				$data['existingparents'] = $existing_parents;

				if($email_signin_enabled)
				{
					$data['user']->username = $username;
				}
			}

			$this->layout->view('student/edit', $data);
		}
	}

	function add()
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$this->load->model('Group_model');

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
		/*	case 't':
				if($this->User_model->has_teacher($student_id, $user_id))
				{
					$permission_denied = false;
				}
			break;*/
			case 'a':
				$permission_denied = false;
			break;
		}

		$all_groups = $this->Group_model->get_groups_by_school($school_id);

		if($permission_denied)
		{
			$this->layout->view('student/error_permissiondenied');
			return;
		}

		$this->load->model('School_model');
		$school = $this->School_model->get_school($school_id);

		$school->metadata = json_decode($school->metadata);
		$email_signin_enabled = isset($school->metadata->emailsignin) && $school->metadata->emailsignin;

		if($this->input->post('submit'))
		{
			$error = '';
			$first_name = $this->input->post('firstname');
			$last_name = $this->input->post('lastname');
			$email = $this->input->post('email');
			$groups = $this->input->post('group');
			$profile_image = 'blobsmall.png';
			$grade = $this->input->post('grade');
			$studentid = $this->input->post('studentid');
			$gender = $this->input->post('gender');
			$dob = $this->input->post('dob');
			$ethnicity = $this->input->post('ethnicity');
			$parent = $this->input->post('parent');

			print_r($groups);
			if($parent === false)
			{
				$parent = array();
			}

			foreach($parent as $p)
			{
				if(empty($p['firstname']) && !empty($p['lastname']))
				{
					$error = 'parentfirstname';
					break;
				}

				if(!empty($p['firstname']) && empty($p['lastname']))
				{
					$error = 'parentlastname';
					break;
				}
			}

			if(empty($error) && $groups == false)
			{
				$error = 'nogroupsslected';
			}

			if(empty($error) && empty($first_name))
			{
				$error = 'firstname';
			}

			if(empty($error) && empty($last_name))
			{
				$error = 'lastname';
			}

			if(empty($error) && empty($email))
			{
				$error = 'email';
			}

			if(empty($error))
			{
				$email_user = $this->User_model->find_by_email($email);

				if(!empty($email_user) && $email_user->userid != $student_id)
				{
					$error = 'emailinuse';
				}
			}

			if($email_signin_enabled)
			{
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				if(empty($username))
				{
					$error = empty($error) ? 'usernameempty' : $error; 
				}
				else
				{
					$user_check = $this->User_model->find_by_username($username);

					if(!empty($user_check))
					{
						$error = empty($error) ? 'usernameinuse' : $error; 
					}
				}

				if(empty($error) && empty($password))
				{
					$error = 'passwordempty';
				}
			}
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$dob = strtotime($dob);

			if($dob == 0)
			{
				$dob = '0000-00-00';
			}
			else
			{
				$dob = date('Y-m-d', $dob);
			}

			if(!$email_signin_enabled)
			{
				$username = $password = '';
			}

			$student_id = $this->User_model->create_student($school_id, $first_name, $last_name, $username, $password, $email, $profile_image, $grade, $studentid, $gender, $dob, $ethnicity);

			foreach($parent as $p)
			{
				if(!empty($p['firstname']) && !empty($p['lastname']))
				{
					$u = empty($p['email']) ? array() : $this->User_model->find_by_email($p['email']);

					if(!empty($u))
					{
						$p_userid = $u->userid;
					}
					else
					{
						$p_userid = $this->User_model->create_parent($school_id, $p['firstname'], $p['lastname'], '', '', $p['email'], $p['phone'], 'blobsmall.png');
					}

					if(!$this->User_model->has_parent($student_id, $p_userid))
					{
						$this->User_model->add_parent($student_id, $p_userid);
					}
				}
			}

			// Add to new groups.
			foreach($groups as $k=>$v)
			{
				$this->Group_model->add_student($k, $student_id);
			}

			redirect('student/view/'.$student_id);
		}
		else
		{
			$data = array(
				'allgroups' => $all_groups,
				'title_for_layout' => 'Add Student',
				'emailsigninenabled' => $email_signin_enabled
			);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['firstname'] = $first_name;
				$data['lastname'] = $last_name;
				$data['email'] = $email;
				$data['grade'] = $grade;
				$data['studentid'] = $studentid;
				$data['gender'] = $gender;
				$data['dob'] = $dob;
				$data['ethnicity'] = $ethnicity;
				$data['formparents'] = $parent;
				$data['groups'] = $groups;

				if($email_signin_enabled)
				{
					$data['username'] = $username;
				}
			}

			$this->layout->view('student/add', $data);
		}
	}

  function summary($student_id)
  {
  	$user_id = $this->session->userdata('userid');

  	if($this->session->userdata('role') != 'a' && $this->session->userdata('role') != 't')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Referral_model');
		$this->load->model('Detention_model');
		$this->load->model('Demerit_model');
		$this->load->model('User_model');
		$this->load->model('School_model');
		$this->load->model('User_model');
		$this->load->model('Settings_model');
		$this->load->model('Form_model');

		$student = $this->User_model->get_user($student_id);

		if(empty($student) || $student->schoolid != $school_id)
		{
			$this->layout->view('student/error_permissiondenied');
			return;			
		}

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 't':
				if($this->User_model->has_teacher($student_id, $user_id))
				{
					$permission_denied = false;
				}
			break;
			case 'a':
				$permission_denied = false;
			break;
		}

		if($permission_denied)
		{
			$this->layout->view('student/error_permissiondenied');
			return;
		}

		$school = $this->School_model->get_school($school_id);
		$incidents = $this->User_model->last_incidents($student_id);
		$viewer = $this->User_model->get_user($user_id);

		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));
		$forms = $this->Form_model->get_viewable($school_id, $this->session->userdata('role'), 's');

		$this->load->model('Settings_model');
		$feature_settings = json_decode($this->Settings_model->get_settings($school_id, SETTINGS_FEATURES));

		$data = array(
			'title_for_layout' => 'Yooply Student Report - '.$student->firstname.' '.$student->lastname,
			'student' => $student,
			'school' => $school,
			'incidents' => $incidents,
			'viewer' => $viewer,
			'labels' => $labels,
			'forms' => $forms,
			'settings' => $feature_settings
		);

		$this->layout->view('student/student_print_report', $data);
  }

  function remove($student_id)
  {
  	$user_id = $this->session->userdata('userid');

  	if($this->session->userdata('role') != 'a')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

  	$this->load->model('User_model');
  	$student = $this->User_model->get_user($student_id);

		if(empty($student) || $student->schoolid != $school_id)
		{
			$this->layout->view('student/error_permissiondenied');
			return;			
		}

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 'a':
				$permission_denied = false;
			break;
		}

		if($permission_denied)
		{
			$this->layout->view('student/error_permissiondenied');
			return;
		}

  	if($this->input->post('submit'))
  	{
	  	$this->load->model('Demerit_model');
	  	$this->Demerit_model->remove_with_user($student_id);

			$this->db->reset_query();

	  	$this->load->model('Detention_model');
	  	$this->Detention_model->remove_with_user($student_id);  	

			$this->db->reset_query();

	  	$this->load->model('Goal_model');
	  	$this->Goal_model->remove_with_user($student_id);  	

			$this->db->reset_query();

	  	$this->load->model('Group_model');
	  	$this->Group_model->remove_with_user($student_id); 

			$this->db->reset_query();

	  	$this->load->model('Hallpass_model');
	  	$this->Hallpass_model->remove_with_user($student_id);   	

			$this->db->reset_query();

	  	$this->load->model('Intervention_model');
	  	$this->Intervention_model->remove_with_user($student_id);     	

			$this->db->reset_query();

	  	$this->load->model('Referral_model');
	  	$this->Referral_model->remove_with_user($student_id);     	

			$this->db->reset_query();

	  	$this->load->model('Reinforcement_model');
	  	$this->Reinforcement_model->remove_with_user($student_id);  

			$this->db->reset_query();

	  	$this->load->model('Report_model');
	  	$this->Report_model->remove_with_user($student_id);  

			$this->db->reset_query();

	  	$this->load->model('Shoutout_model');
	  	$this->Shoutout_model->remove_with_user($student_id);  

			$this->db->reset_query();

	  	$this->load->model('Status_model');
	  	$this->Status_model->remove_with_user($student_id);  

	  	echo 'done';
	  }
	  else
	  {
	  	$this->layout->view('student/remove', array('student' => $student, 'title_for_layout' => 'Remove student?'));
	  }
	}
}

?>