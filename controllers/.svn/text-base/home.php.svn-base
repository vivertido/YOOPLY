<?php

class Home extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		if($this->session->userdata('userid'))
		{
			switch($this->session->userdata('role'))
			{
				case 'a':
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

		if(preg_match('/ascend\.yoop\.ly/', $_SERVER["SERVER_NAME"]))
		{
			$this->layout->view('home/login');
		}
		else
		{
			$this->load->view('splash');
		}
	}

	function learnmore()
	{
		$this->load->view('learnmore');
	}

	function faqs()
	{
		$this->load->view('faqs');
	}

	function pricing()
	{
		$this->load->view('pricing');
	}

	function privacy()
	{
		$this->load->view('privacy');
	}	

	function tos(){

		$this->load->view('tos');
	}

	function signup()
	{
		redirect('contact'); //header('Location: https://docs.google.com/forms/d/1Yv0qMb2IbNveE5QQoTwCbsf-v4O_C-GR-KWnOZUqlmI/viewform');
	}

	function contact()
	{
		//$this->layout->setLayout('layout_public');

		if($this->input->post('submit'))
		{
			$department = $this->input->post('department');
			$name = $this->input->post('name');
			$email = $this->input->post('email');
			$message = $this->input->post('message');

			if(empty($department) || empty($name) || !$this->_validEmail($email) || empty($message))
			{
				$this->layout->view('home/contact', array(
					'name' => $name,
					'email' => $email,
					'message' => $message,
					'department' => $department,
					'error' => "Please fill in all fields."
				));

				return;
			}
			$message = 'From: '.$name."\nEmail: ".$email."\nMessage:\n".$message;

			mail('support@yoop.ly', '[Contact] '.$department, $message, 'From: support@yoop.ly');

			$this->layout->view('home/contactthanks', array('email' => $email));
			return;
		}
		else
		{
			$this->layout->view('home/contact');
		}
	}

	/**
	Validate an email address.
	Provide email address (raw input)
	Returns true if the email address has the email
	address format and the domain exists.
	*/
	function _validEmail($email)
	{
	   $isValid = true;
	   $atIndex = strrpos($email, "@");
	   if (is_bool($atIndex) && !$atIndex)
	   {
	      $isValid = false;
	   }
	   else
	   {
	      $domain = substr($email, $atIndex+1);
	      $local = substr($email, 0, $atIndex);
	      $localLen = strlen($local);
	      $domainLen = strlen($domain);
	      if ($localLen < 1 || $localLen > 64)
	      {
	         // local part length exceeded
	         $isValid = false;
	      }
	      else if ($domainLen < 1 || $domainLen > 255)
	      {
	         // domain part length exceeded
	         $isValid = false;
	      }
	      else if ($local[0] == '.' || $local[$localLen-1] == '.')
	      {
	         // local part starts or ends with '.'
	         $isValid = false;
	      }
	      else if (preg_match('/\\.\\./', $local))
	      {
	         // local part has two consecutive dots
	         $isValid = false;
	      }
	      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
	      {
	         // character not valid in domain part
	         $isValid = false;
	      }
	      else if (preg_match('/\\.\\./', $domain))
	      {
	         // domain part has two consecutive dots
	         $isValid = false;
	      }
	      else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
	                 str_replace("\\\\","",$local)))
	      {
	         // character not valid in local part unless
	         // local part is quoted
	         if (!preg_match('/^"(\\\\"|[^"])+"$/',
	             str_replace("\\\\","",$local)))
	         {
	            $isValid = false;
	         }
	      }
	      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
	      {
	         // domain not found in DNS
	         $isValid = false;
	      }
	   }
	   return $isValid;
	}

	function login($redirect = '')
	{
		if($this->input->post('forgot'))
		{
			redirect('forgot');
		}

		$error = '';

		if($this->input->post('submit'))
		{
			$this->load->model('User_model');

			$username = strtolower($this->input->post('username'));
			$password = $this->input->post('password');

			$validated = $this->User_model->validate($username, $password);

			if($validated === false)
			{
				$error = 'invalid';
			}
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$this->session->set_userdata(array(
				'userid' => $validated->userid, 
				'role' => $validated->accounttype,
				'schoolid' => $validated->schoolid
			));

			switch($validated->accounttype)
			{
				case 'a':
					$this->load->model('School_model');
					if($this->School_model->has_teacher($validated->schoolid, $validated->userid))
					{
						$this->session->set_userdata('isteacher', true);
					}
				break;
				case 't':
					$this->load->model('School_model');
					if($this->School_model->has_admin($validated->schoolid, $validated->userid))
					{
						$this->session->set_userdata('isadmin', true);
					}
				break;
			}

			//$this->load->model('Log_model');
			//$this->Log_model->user_login($validated->userid);

			if(!empty($redirect))
			{
				redirect('/'.preg_replace('/\./', '/', $redirect));
				return;
			}

			switch($validated->accounttype)
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
			}
		}
		else
		{
			if($this->session->userdata('userid'))
			{
				if(!empty($redirect))
				{
					redirect('/'.preg_replace('/\./', '/', $redirect));
					return;
				}

				switch($this->session->userdata('role'))
				{
					case 't':
						redirect('teacher');
					break;
					case 's':
						redirect('student');
					break;
					case 'p':
						redirect('parent');
					break;
					case 'a':
						redirect('admin');
					break;
				}
				return;
			}

			$this->load->model('School_model');
			$school = $this->School_model->find_school_by_domain($_SERVER['HTTP_HOST']);
			if(!empty($school))
			{
				$school->metadata = json_decode($school->metadata);
			}
			else
			{
				$school = new stdClass();
				$school->metadata = new stdClass();
				$school->metadata->emailsignin = true;
				$school->metadata->googlesignin = true;
			}
			
			$data = array(
				'redirect' => $redirect,
				'school' => $school
			);

			if(!empty($error)) 
			{
				$data['error'] = $error;
				$data['username'] = $username;
			}

			$this->layout->view('home/login', $data);
		}
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('/');
	}	

	function switchrole()
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('School_model');
		switch($this->session->userdata('role'))
		{
			case 't':
				if($this->School_model->has_admin($school_id, $user_id))
				{
					$this->session->set_userdata(array(
						'isteacher' => true,
						'role' => 'a'
					));

					redirect('admin');
					return;
				}

				redirect('teacher');
			break;
			case 'a':
				if($this->School_model->has_teacher($school_id, $user_id))
				{
					$this->session->set_userdata(array(
						'isadmin' => true,
						'role' => 't'
					));

					redirect('teacher');
					return;
				}

				redirect('admin');
			break;
			case 's':
				redirect('student');
			break;
		}
	}

	function forgot()
	{
		if($this->input->post('submit'))
		{
			$username = $this->input->post('username');

			if(empty($username))
			{
				$this->layout->view('home/forgot', array('error' => 'usernameempty'));
				return;
			}
			
			$this->load->model('User_model');
			$user = $this->User_model->find_by_username($username);

			if(!empty($user))
			{
				$this->load->model('Email_model');

				if(!empty($user->username) && !empty($user->email))
				{
					$user_id = $user->userid;
					$time_created = time();
					$key = md5($user_id.$time_created.$user->nonce);

					$this->User_model->forgot_password($user_id, $time_created, $key);

					$reset = $this->User_model->get_reset($user_id, $key);

					$this->load->model('Log_model');
					$this->Log_model->log($user_id, 'forgotpassword', array('key' => $key));

					$this->Email_model->forgot_password($user, $reset);
					$this->layout->view('home/forgotsent');
				}
				else
				{
					$this->layout->view('home/forgot', array('error' => 'unabletoprocess'));
				}
			}
			else
			{
				$this->layout->view('home/forgot', array('error' => 'unabletoprocess'));
			}
		}
		else
		{
			$this->layout->view('home/forgot');
		}
	}
	
	function reset($user_id, $key, $redirect = '')
	{
		$this->load->model('User_model');
		$reset = $this->User_model->get_reset($user_id, $key);
		
		if(empty($reset) || $reset->userid != $user_id || $reset->status != 1)
		{
			$this->layout->view('home/error_resetinvalid');
			return;
		}
		
		if($this->input->post('submit'))
		{
			$password = trim($this->input->post('password'));
			$confirm = trim($this->input->post('confirm'));
			
			if(empty($password) || empty($confirm))
			{
				$this->layout->view('home/reset', array(
					'reset' => $reset,
					'error' => 'empty',
				));	
				
				return;		
			}
			
			if($password != $confirm)
			{
				$this->layout->view('home/reset', array(
					'reset' => $reset,
					'error' => 'passwordmismatch',
				));	
				
				return;
			}
			
			$user = $this->User_model->get_user($user_id);
			
			$this->User_model->change_password($user_id, $password);
			$this->User_model->reset_used($key);
			
			$this->load->model('Log_model');
		  $this->Log_model->log($user_id, 'passwordreset', array('key' => $key));
		  
	  	$this->session->set_userdata(array(
				'userid' => $user->userid, 
				'role' => $user->accounttype,
				'schoolid' => $user->schoolid
			));

			switch($user->accounttype)
			{
				case 'a':
					$this->load->model('School_model');
					if($this->School_model->has_teacher($user->schoolid, $user->userid))
					{
						$this->session->set_userdata('isteacher', true);
					}
				break;
				case 't':
					$this->load->model('School_model');
					if($this->School_model->has_admin($user->schoolid, $user->userid))
					{
						$this->session->set_userdata('isadmin', true);
					}
				break;
			}

			//$this->load->model('Log_model');
			//$this->Log_model->user_login($validated->userid);

			if(!empty($redirect))
			{
				redirect('/'.preg_replace('/\./', '/', $redirect));
				return;
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
			}
		}
		else
		{
			$this->layout->view('home/reset', array('reset' => $reset));
		}
	}

	function notifications()
	{
		$user_id = $this->session->userdata('userid');

		$this->load->model('Notification_model');
		$notifications = $this->Notification_model->get_notifications($user_id, 30);

		$this->layout->view('home/notifications', array(
			'title_for_layout' => 'Notifications',
			'notifications' => $notifications
		));
	}
}
?>