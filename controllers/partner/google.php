<?php

define('GOOGLE_CLIENT_ID', '1002789460941-laqkt0eqhhr77sac3ocud6i0o42soak1.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', 'EnRfq9CDOLjHrfqY64L68qCM');
define('GOOGLE_EMAIL', '1002789460941-laqkt0eqhhr77sac3ocud6i0o42soak1@developer.gserviceaccount.com');


class Google extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		parse_str($_SERVER['QUERY_STRING'], $_GET);
	}

	function index($redirect = '')
	{
		$security_token = md5(time().rand().$redirect);
		$this->session->set_userdata(array(
			'googleverifytoken', $security_token,
			'googleverifyredirect' => $redirect
		));

		$params = array(
			'scope' => 'openid email profile',
			'client_id' => GOOGLE_CLIENT_ID,
 			'response_type' => 'code',
 			'redirect_uri' => base_url().'partner/google/auth',
 			'state' => $security_token,
 			'openid.realm' => 'https://'.$_SERVER['HTTP_HOST'],
			'openid_shutdown_ack' => '2015-04-20'
		);

		header("Location: https://accounts.google.com/o/oauth2/auth?".http_build_query($params));
	}

	function auth()
	{
		$security_token = $_GET['state'];
		$code = $_GET['code'];

		$user_token = $this->session->userdata('googleverifytoken');
		$redirect = $this->session->userdata('googleverifyredirect');		

		if(isset($_GET['error']))// || empty($security_token) || $security_token != $user_token)
		{
			$this->layout->view('partner/google/error_usernotfound');
			return;	
		}

		$params = array(
			'grant_type' => 'authorization_code',
			'code' => $code,
			'client_id' => GOOGLE_CLIENT_ID,
			'client_secret' => GOOGLE_CLIENT_SECRET,
			'redirect_uri' => base_url().'partner/google/auth',
		);

		$ch = curl_init('https://www.googleapis.com/oauth2/v3/token');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
		$response = curl_exec($ch);

		$data = json_decode($response);

		if(isset($data->error))
		{
			$this->layout->view('partner/google/error_usernotfound');
			return;				
		}

		$ch = curl_init('https://www.googleapis.com/plus/v1/people/me/openIdConnect');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$data->access_token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$user_data = json_decode(curl_exec($ch));

		if(!isset($user_data->email) || empty($user_data->email))
		{
			$this->layout->view('partner/google/error_usernotfound');
			return;			
		}

		$email = $user_data->email;

		$this->load->model('User_model');

		$user = $this->User_model->find_by_email($email);

		if(empty($user))
		{
			$this->layout->view('partner/google/error_usernotfound');
			return;		
		}

		$school_id = $user->schoolid;

		$this->session->set_userdata(array(
			'userid' => $user->userid, 
			'role' => $user->accounttype,
			'schoolid' => $school_id
		));

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
		}
	}
}

?>