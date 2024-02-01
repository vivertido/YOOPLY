<?php 

class Account extends CI_Controller
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
		$this->personal();
	}

	function personal()
	{
		$user_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$user = $this->User_model->get_user($user_id);

		// Disable name changes for students. TODO: make this a setting
		$name_disabled = $this->session->userdata('role') == 's';

		$error = '';
		if($this->input->post('submit'))
		{
			$first_name = $this->input->post('firstname');
			$last_name = $this->input->post('lastname');
			$phone = $this->input->post('phone');
			$gender = $this->input->post('gender');
			$dob = $this->input->post('dob');

			if(!$name_disabled && empty($first_name))
			{
				$error = 'firstname';
			}

			if(!$name_disabled && empty($error) && empty($last_name))
			{
				$error = 'lastname';
			}			
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$dob = (empty($dob) || strtotime($dob) == 0) ? '0000-00-00' : date('Y-m-d', strtotime($dob));

			// If name changing if disabled, use prior value.
			if($name_disabled)
			{
				$first_name = $user->firstname;
				$last_name = $user->lastname;
			}

			$this->User_model->update($user_id, $first_name, $last_name, $user->email, $user->grade, $user->studentid, $gender, $dob, $user->ethnicity, $phone);

			switch($user->accounttype)
			{
				case 'a';
					redirect('admin');
				break;
				case 't':
					redirect('teacher');
				break;
				case 's':
					redirect('student');
				break;
			}
		}
		else
		{
			$data = array('user' => $user, 'title_for_layout' => 'Personal Information');

			if(!empty($error))
			{
				$data['user']->firstname = $first_name;
				$data['user']->lastname = $last_name;
				$data['user']->phone = $phone;
				$data['user']->gender = $gender;
				$data['error'] = $error;
			}

			$this->load->model('School_model');
			$school_id = $this->session->userdata('schoolid');
			$school = $this->School_model->get_school($school_id);

			$school->metadata = json_decode($school->metadata);
			if(isset($school->metadata->emailsignin) && $school->metadata->emailsignin)
			{
				$data['menushowlogin'] = true;
			}

			$data['namedisabled'] = $name_disabled;

			$this->layout->view('account/personal', $data);
		}
	}

	function notifications()
	{
		$user_id = $this->session->userdata('userid');

		$this->load->model('Profile_model');
		$preferences = $this->Profile_model->get_notifications($user_id);

		$this->load->model('User_model');
		$user = $this->User_model->get_user($user_id);

		if($this->input->post('submit'))
		{
			$sms = $this->input->post('sms') !== false && $this->input->post('sms') == '1' ? '1' : '0';
			$email = $this->input->post('email') !== false && $this->input->post('email') == '1' ? '1' : '0';

			if(empty($user->phone))
			{
				$sms = 0;
			}

			$preferences->sms = $sms;
			$preferences->email = $email;

			$this->Profile_model->save_notification($user_id, $preferences);

			switch($user->accounttype)
			{
				case 'a';
					redirect('admin');
				break;
				case 't':
					redirect('teacher');
				break;
				case 's':
					redirect('student');
				break;
			}
		}
		else
		{
			$data = array(
				'preferences' => $preferences, 
				'user' => $user, 
				'title_for_layout' => 'Notification Preferences'
			);

			$this->load->model('School_model');
			$school_id = $this->session->userdata('schoolid');
			$school = $this->School_model->get_school($school_id);

			$school->metadata = json_decode($school->metadata);
			if(isset($school->metadata->emailsignin) && $school->metadata->emailsignin)
			{
				$data['menushowlogin'] = true;
			}

			$this->layout->view('account/notifications', $data);
		}
	}

	function avatar()
	{
		if($this->session->userdata('role') != 's')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'avatars'));

		$student_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($student_id);

		if($this->input->post('submit'))
		{
			$profile_image = $this->input->post('avatar');

			if(!in_array($profile_image, $settings->avatars))
			{
				$this->layout->view('student/error_general', array(
					'title_for_layout' => 'Error'
				));

				return;
			}

			$profile_image .= '.png';
			$this->User_model->update_avatar($student_id, $profile_image);

			redirect('student');
		}
		else
		{
			$data = array(
				'title_for_layout' => 'Settings',
				'avatars' => $settings->avatars,
				'student' => $student
			);

			$this->load->model('School_model');
			$school_id = $this->session->userdata('schoolid');
			$school = $this->School_model->get_school($school_id);

			$school->metadata = json_decode($school->metadata);
			if(isset($school->metadata->emailsignin) && $school->metadata->emailsignin)
			{
				$data['menushowlogin'] = true;
			}

			$this->layout->view('account/avatar', $data);
		}
	}

	function login()
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('School_model');
		$school = $this->School_model->get_school($school_id);

		$school->metadata = json_decode($school->metadata);
		
		if(!(isset($school->metadata->emailsignin) && $school->metadata->emailsignin))
		{
			$this->layout->view('account/error_emaillogindisabled');
			return;
		}

		$this->load->model('User_model');
		$user = $this->User_model->get_user($user_id);

		$error = '';

		if($this->input->post('submit')) {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$confirm_password = $this->input->post('confirmpassword');

			if(empty($username))
			{
				$error = 'emptyusername';
			}
			else
			{
				$user_check = $this->User_model->find_by_username($username);

				if(empty($error) && !empty($user_check) && $user_check->userid != $user->userid)
				{
					$error = 'usernametaken';
				}
			}

			if(empty($error) && empty($password))
			{
				$error = 'emptypassword';
			}			

			if(empty($error) && empty($confirm_password))
			{
				$error = 'emptypassword';
			}

			if(empty($error) && $password != $confirm_password)
			{
				$error = 'passwordsnotequal';
			}
		}

		if(empty($error) && $this->input->post('submit')) {
			$this->User_model->change_login($user_id, $username, $password);

			switch($user->accounttype)
			{
				case 'a';
					redirect('admin');
				break;
				case 't':
					redirect('teacher');
				break;
				case 's':
					redirect('student');
				break;
			}
		}		
		else
		{
			$data = array(
				'user' => $user,
				'menushowlogin' => true,
				'title_for_layout' => 'Settings'
			);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['user']->username = $username;
			}

			$this->layout->view('account/login', $data);	
		}
		
	}

}