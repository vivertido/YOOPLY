<?php

class Reflections extends MY_Controller
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

	function mine()
	{
		if($this->session->userdata('role') != 's')
		{
			redirect('login');
			return;
		}

		$this->load->model('Referral_model');

		$school_id = $this->session->userdata('schoolid');
		$student_id = $this->session->userdata('userid');

		$reflections = $this->Referral_model->get_reflections($school_id, $student_id);

		$this->layout->view('reflection/mine', array(
			'reflections' => $reflections,
			'title_for_layout' => 'My Reflections'
		));
	}

	function view($referral_id)
	{
		$this->load->model('Referral_model');

		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$referral = $this->Referral_model->get_referral($referral_id);

		if(empty($referral))
		{
			$this->layout->view('reflections/error_reflectionnotfound');
			return;
		}

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
				if($referral->schoolid == $school_id)
				{
					$permission_denied = false;
				}
			break;
			case 's':
				if($referral->studentid == $user_id)
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

		$this->load->model('User_model');
		$teacher = $this->User_model->get_user($referral->teacherid);

		$this->layout->view('reflection/view', array(
			'referral' => $referral,
			'teacher' => $teacher,
			'title_for_layout' => 'Reflection'
		));
	}

	function reflect($referral_id)
	{
		$user_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($user_id);

		$this->load->model('Referral_model');
		$referral = $this->Referral_model->get_referral($referral_id);

		if(empty($referral))
		{
			$this->layout->view('student/error_referralnotfound', array(
				'title_for_layout' => 'Error'
			));
			return;
		}

		if(!empty($referral->reflection))
		{
			redirect('student');
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'reflection'));

		if($this->input->post('submit'))
		{
			$reflection = process_form('f', $settings->questions);

			$this->Referral_model->save_reflection($referral_id, $reflection);

			redirect('student');
		}
		else
		{
			$this->layout->view('reflection/reflect', array(
				'referral' => $referral,
				'student' => $student,
				'questions' => $settings->questions,
				'title_for_layout' => 'Reflection'
			));
		}
	}
}
?>