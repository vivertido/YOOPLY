<?php

class Shoutout extends MY_Controller
{
	function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('userid'))
		{
			redirect('login');
		}
	}

	function send()
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'shoutout'));

		if($this->input->post('submit'))
		{
			$to = $this->input->post('to');
			$content = $this->input->post('content');

			$this->load->model('Shoutout_model');
			$this->Shoutout_model->create($user_id, $to, $content);

			redirect('student');
		}
		else
		{
			$this->load->model('User_model');
			$classmates = $this->User_model->get_classmates($user_id);

			$this->layout->view('shoutout/send', array(
				'classmates' => $classmates,
				'title_for_layout' => 'Shoutout',
				'settings' => $settings
			));
		}
	}
}

?>