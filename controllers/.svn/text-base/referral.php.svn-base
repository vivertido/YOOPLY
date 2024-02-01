<?php

class Referral extends MY_Controller
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
			'title_for_layout' => 'Referrals',
			'filter' => $filter
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

		$expand_student = ($student_id == 0);
		$expand_teacher = ($teacher_id == 0);

		$this->load->model('Referral_model');
		$data['referrals'] = $this->Referral_model->find($school_id, $teacher_id, $student_id, 0, 0, $start_time, $end_time, $expand_teacher, $expand_student);

		$this->layout->view('referral/list', $data);
	}


	function student($student_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

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

		$this->load->model('Referral_model');
		$referrals = $this->Referral_model->get_by_student($student_id);

		$this->layout->view('referral/student', array(
			'referrals' => $referrals,
			'student' => $student,
			'title_for_layout' => $student->firstname.' '.$student->lastname
		));
	}

	function view($referral_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Referral_model');
		$referral = $this->Referral_model->get_referral($referral_id);

		if(empty($referral) || $referral->status != REFERRAL_STATUS_ACTIVE)
		{
			$this->layout->view('referral/error_referralnotfound');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($referral->studentid);

		$this->load->model('User_model');
		$teacher = $this->User_model->get_user($referral->teacherid);

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
			case 'p':
				if($this->User_model->has_parent($referral->studentid, $user_id))
				{
					$permission_denied = false;
				}
			break;
		}

		if($permission_denied)
		{
			$this->layout->view('referral/error_permissiondenied');
			return;
		}

		$this->load->model('Consequence_model');
		$consequences = $this->Consequence_model->get_by_incident('referral', $referral->referralid);

		$this->layout->view('referral/view', array(
			'referral' => $referral,
			'student' => $student,
			'teacher' => $teacher,
			'consequences' => $consequences,
			'title_for_layout' => 'Referral'
		));
	}

	// For now, the add method only starts from the teacher's report. Admins have to do this step as well.
	function add($student_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($student_id);

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
				if($student->schoolid == $school_id)
				{
					$permission_denied = false;
				}
			break;
		}

		if($permission_denied)
		{
			$this->layout->view('referral/error_permissiondenied');
			return;
		}
		
		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'referrals'));

		// If submit, teacher submitting the report for admin review. If save, teacher is saving "a draft"
		if($this->input->post('submit') || $this->input->post('save'))
		{
			$teacher_report = process_form('f', $settings->questions);
			$incident = $this->input->post('f'.$settings->keys->incident);

			$other_motivation = $this->input->post('othermotivation');

			array_push($teacher_report, array(
				'label' => 'Possible Motivation', 
				'id' => md5('Possible Motivation'),
				'value' => !empty($other_motivation) ? $this->input->post('othermotivation') : $this->input->post('motivation')
			));
			array_push($teacher_report, array(
				'label' => 'Others Involved', 
				'id' => md5('Others Involved'),
				'value' => $this->input->post('othersinvolved')
			));

			$teacher_report['location'] = $this->input->post('location');

			$consequences = array();
			$consequence = $this->input->post('consequence');

			foreach($consequence as $c)
			{
				if(!empty($c))
				{
					array_push($consequences, $c);
				}
			}

			if($this->input->post('detention') > 0)
			{
				array_push($consequences, 'Detention: '.$this->input->post('detention').' minutes');
			}

			if($this->input->post('suspension') > 0)
			{
				array_push($consequences, 'Suspension: '.$this->input->post('suspension').' days');
			}

			array_push($teacher_report, array(
				'label' => 'Actions Suggested', 
				'id' => md5('Actions Suggested'), 
				'value' => $consequences
			));

			$this->load->model('Referral_model');

			// If teacher submitted the report, we change the teacher save time.
			if($this->input->post('submit'))
			{
				$this->Referral_model->create($school_id, $user_id, $student_id, $incident, $teacher_report, false);
			}
			else
			{
				$this->Referral_model->create($school_id, $user_id, $student_id, $incident, $teacher_report, true);
			}

			redirect('student/view/'.$student_id);
		}
		else
		{
			$teacher = $this->User_model->get_user($user_id);

			$locations = json_decode($this->Settings_model->get_settings($school_id, 'locations'));
			$motivations = json_decode($this->Settings_model->get_settings($school_id, 'motivations'));
			$consequences = json_decode($this->Settings_model->get_settings($school_id, 'consequences'));

			$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

			$this->layout->view('referral/add', array(
				'settings' => $settings,
				'labels' => $labels,
				'motivations' => $motivations,
				'consequences' => $consequences,
				'student' => $student,
				'teacher' => $teacher,
				'locations' => $locations,
				'title_for_layout' => $student->firstname.' '.$student->lastname
			));
		}

	}

	function edit($referral_id)
	{
		if($this->input->post('cancel'))
		{
			redirect('referral/view/'.$referral_id);
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Referral_model');
		$referral = $this->Referral_model->get_referral($referral_id);

		if(empty($referral) || $referral->status != REFERRAL_STATUS_ACTIVE)
		{
			$this->layout->view('referral/error_referralnotfound');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($referral->studentid);

		$this->load->model('User_model');
		$teacher = $this->User_model->get_user($referral->teacherid);

		$data = array(
			'referral' => $referral,
			'student' => $student,
			'teacher' => $teacher,
			'title_for_layout' => 'Referral'
		);

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 't':
				if($this->User_model->has_teacher($student->userid, $user_id))
				{
					$permission_denied = false;
					$role = 'teacher';
				}
			break;
			case 'a':
				if($referral->schoolid == $school_id)
				{
					$permission_denied = false;
					$role = 'admin';
					
					$this->load->model('Settings_model');
					$data['settings'] = json_decode($this->Settings_model->get_settings($school_id, 'adminreview'));
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
			$this->layout->view('referral/error_permissiondenied');
			return;
		}

		switch($this->session->userdata('role'))
		{
			case 'a':
				$this->_edit_admin($referral);
			break;
			case 't':
				$this->_edit_teacher($referral);
			break;			
		}
	}	

	function _edit_admin($referral)
	{
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($referral->studentid);

		if($this->input->post('submit'))
		{
			$admin_id = $this->session->userdata('userid');

			$notes = array(
				'internal' => array(
					'note' => $this->input->post('internalnotes')
				),
				'external' => array(
					'note' => $this->input->post('externalnotes'),
					'actionstaken' => $this->input->post('actionstaken'),
					'detention' => $this->input->post('detention'),
					'suspension' => $this->input->post('suspension'),
				)
			);

			$notify = $this->input->post('notify') === false ? array() : $this->input->post('notify');
			array_push($notify, $referral->teacherid);
			$notify = array_unique($notify);

			$this->load->model('User_model');

			$link = '/referral/view/'.$referral->referralid;
			$object_id = 'referral/'.$referral->referralid;
			$admin = $this->User_model->get_user($admin_id);

			$this->Referral_model->save_admin($referral->referralid, $admin_id, $notes);

			$text = $admin->firstname.' '.$admin->lastname.' reviewed a referral for '.$student->firstname.' '.$student->lastname;

			if(!empty($text))
			{
				$this->load->model('Notification_model');
				foreach($notify as $receiver)
				{
					$this->Notification_model->create($receiver, $text, $link, $object_id);
				}
			}

			$parents = $this->User_model->get_parents($referral->studentid);

			$teacher = $this->User_model->get_user($referral->teacherid);
			foreach($parents as $parent)
			{
				if(!empty($parent->phonenumber))
				{
					$this->load->model('Sms_model');
					$this->Sms_model->send($school_id, $parent->phonenumber, 'referral', array('student' => $student, 'teacher' => $teacher));
				}
			}

			redirect('referral/view/'.$referral->referralid);
		}
		else
		{
			$this->load->model('Settings_model');
			$settings = json_decode($this->Settings_model->get_settings($school_id, 'adminreview'));

			$this->layout->view('referral/edit_admin', array(
				'student' => $student,
				'settings' => $settings,
				'referral' => $referral, 
				'title_for_layout' => $student->firstname.' '.$student->lastname
			));
		}		
	}

	function _edit_teacher($referral)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$student = $this->User_model->get_user($referral->studentid);

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'referrals'));

		// If submit, teacher submitting the report for admin review. If save, teacher is saving "a draft"
		if($this->input->post('submit') || $this->input->post('save'))
		{
			$teacher_report = process_form('f', $settings->questions);
			$incident = $this->input->post('f'.$settings->keys->incident);

			$other_motivation = $this->input->post('othermotivation');

			array_push($teacher_report, array(
				'label' => 'Possible Motivation', 
				'id' => md5('Possible Motivation'),
				'value' => !empty($other_motivation) ? $this->input->post('othermotivation') : $this->input->post('motivation')
			));
			array_push($teacher_report, array(
				'label' => 'Others Involved', 
				'id' => md5('Others Involved'),
				'value' => $this->input->post('othersinvolved')
			));

			$teacher_report['location'] = $this->input->post('location');

			$consequences = array();
			$consequence = $this->input->post('consequence');

			foreach($consequence as $c)
			{
				if(!empty($c))
				{
					array_push($consequences, $c);
				}
			}

			if($this->input->post('detention') > 0)
			{
				array_push($consequences, 'Detention: '.$this->input->post('detention').' minutes');
			}

			if($this->input->post('suspension') > 0)
			{
				array_push($consequences, 'Suspension: '.$this->input->post('suspension').' days');
			}

			array_push($teacher_report, array(
				'label' => 'Actions Suggested', 
				'id' => md5('Actions Suggested'), 
				'value' => $consequences
			));

			$teacher_id = $this->session->userdata('userid');

			$this->load->model('Referral_model');

			// If teacher submitted the report, we change the teacher save time.
			if($this->input->post('submit'))
			{
				$this->Referral_model->submit_teacher($referral->referralid, $incident, $teacher_report);
			}
			else
			{
				$this->Referral_model->save_teacher($referral->referralid, $incident, $teacher_report);
			}

			redirect('student/view/'.$referral->studentid);
		}
		else
		{
			$teacher = $this->User_model->get_user($user_id);

			$locations = json_decode($this->Settings_model->get_settings($school_id, 'locations'));
			$motivations = json_decode($this->Settings_model->get_settings($school_id, 'motivations'));
			$consequences = json_decode($this->Settings_model->get_settings($school_id, 'consequences'));

			$this->layout->view('referral/edit_teacher', array(
				'studentid' => $referral->studentid,
				'settings' => $settings,
				'motivations' => $motivations,
				'consequences' => $consequences,
				'student' => $student,
				'teacher' => $teacher,
				'referral' => $referral,
				'locations' => $locations,
				'title_for_layout' => $student->firstname.' '.$student->lastname
			));
		}
	}

	function remove($referral_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Referral_model');
		$referral = $this->Referral_model->get_referral($referral_id);

		if(empty($referral) || $referral->status != REFERRAL_STATUS_ACTIVE)
		{
			$this->layout->view('referral/error_referralnotfound');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($referral->studentid);

		$this->load->model('User_model');
		$teacher = $this->User_model->get_user($referral->teacherid);

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
			$this->layout->view('referral/error_permissiondenied');
			return;
		}
		
		if($this->input->post('cancel'))
		{
			redirect('referral/view/'.$referral_id);
			return;
		}

		if($this->input->post('submit'))
		{
			$this->Referral_model->remove($referral_id);

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
			$this->layout->view('referral/remove', array(
				'referral' => $referral,
				'student' => $student,
				'teacher' => $teacher,
				'title_for_layout' => 'Referral'
			));
		}
	}	

	function checkinstudent($referral_id)
	{
		if($this->session->userdata('role') != 'a')
		{
			redirect('login');
			return;
		}

		$this->load->model('Referral_model');
		$referral = $this->Referral_model->get_referral($referral_id);

		if(empty($referral))
		{
			$this->layout->view('admin/error_noreferralfound');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		if($referral->schoolid != $school_id)
		{
			$this->layout->view('admin/error_permissiondenied');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($referral->studentid);
		$text = $student->firstname.' '.$student->lastname.' has arrived at the office';
		$object_id = 'referral/'.$referral_id;
		$link = 'referral/view/'.$referral_id;

		$this->load->model('Notification_model');
		$this->Notification_model->create($referral->teacherid, $text, $link, $object_id);

		$this->Referral_model->check_in_student($referral_id);

		redirect('referral/view/'.$referral_id);
	}

	function sendbacktoclass($referral_id)
	{
		if($this->session->userdata('role') != 'a')
		{
			redirect('login');
			return;
		}

		$this->load->model('Referral_model');
		$referral = $this->Referral_model->get_referral($referral_id);

		if(empty($referral))
		{
			$this->layout->view('admin/error_noreferralfound');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		if($referral->schoolid != $school_id)
		{
			$this->layout->view('admin/error_permissiondenied');
			return;
		}

		$this->load->model('User_model');
		$student = $this->User_model->get_user($referral->studentid);
		$text = $student->firstname.' '.$student->lastname.' was sent back to class';
		$object_id = 'referral/'.$referral_id;
		$link = 'referral/view/'.$referral_id;

		$this->load->model('Notification_model');
		$this->Notification_model->create($referral->teacherid, $text, $link, $object_id);

		$this->Referral_model->send_back_to_class($referral_id);

		redirect('referral/view/'.$referral_id);
	}	
}
?>