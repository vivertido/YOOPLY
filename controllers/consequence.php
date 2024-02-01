<?php

class Consequence extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function add($incident_type, $incident_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Settings_model');
		$consequences = json_decode($this->Settings_model->get_settings($school_id, 'consequences'));

		switch($incident_type)
		{
			case 'demerit':
				$this->load->model('Demerit_model');
				$demerit = $this->Demerit_model->get_demerit($incident_id);
				$student_id = $demerit->studentid;
			break;
			case 'report':
				$this->load->model('Report_model');
				$report = $this->Report_model->get_response($incident_id);
				$student_id = $report->subjectid;
			break;
			case 'intervention':
				$this->load->model('Intervention_model');
				$report = $this->Intervention_model->get_intervention($incident_id);
				$student_id = $report->studentid;
			break;			
			case 'referral':
				$this->load->model('Referral_model');
				$referral = $this->Referral_model->get_referral($incident_id);
				$student_id = $referral->studentid;
			break;			

		}

		if($this->input->post('submit'))
		{
			$title = $this->input->post('consequence');
			$progress = $this->input->post('status');
			$notes = $this->input->post('notes');
			$notify = $this->input->post('notify') === false ? array() : $this->input->post('notify');
			
			$data = array('notes' => $notes, 'notify' => $notify);

			if(!empty($notify))
			{
				$this->load->model('User_model');

				switch($incident_type)
				{
					case 'demerit':
					case 'intervention':
					case 'referral':
						$link = $incident_type.'/view/'.$incident_id;
						$object_id = $incident_type.'/'.$incident_id;
					break;
					case 'report':
						$link = 'report/response/'.$incident_id;
						$object_id = 'report/'.$incident_id;
					break;			
				}

				$student = $this->User_model->get_user($student_id);
				$text = $student->firstname.' '.$student->lastname.' was given the consequence '.$title;

				if(!empty($text))
				{
					$this->load->model('Notification_model');
					foreach($notify as $receiver)
					{
						$this->Notification_model->create($receiver, $text, $link, $object_id);
					}
				}
			}	

			$this->load->model('Consequence_model');
			$this->Consequence_model->create($incident_type, $incident_id, $user_id, $student_id, $title, $data, $progress);

			switch($incident_type)
			{
				case 'demerit':
				case 'intervention':
				case 'referral':
					redirect($incident_type.'/view/'.$incident_id);
				break;
				case 'report':
					redirect('report/response/'.$incident_id);
				break;			
			}
		}
		else
		{
			$this->layout->view('consequence/add', array(
				'incidenttype' => $incident_type, 
				'incidentid' => $incident_id,
				'consequences' => $consequences,
			));			
		}
	}

	function edit($consequence_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Consequence_model');
		$this->load->model('Settings_model');

		$consequence = $this->Consequence_model->get_consequence($consequence_id);

		if(empty($consequence) || $consequence->status == 0)
		{
			$this->layout->view('consequence/error_notfound');
			return;
		}

		$consequences = json_decode($this->Settings_model->get_settings($school_id, 'consequences'));

		if($this->input->post('submit'))
		{
			$data = json_decode($consequence->data, true);

			$title = $this->input->post('consequence');
			$progress = $this->input->post('status');
			$notes = $this->input->post('notes');
			$notify = $this->input->post('notify') === false ? array() : $this->input->post('notify');
			
			$new_note = empty($data['notes']);
			$data['notes'] = $notes;
			$data['notify'] = $notify;
			
			array_push($notify, $consequence->assignedby);
			$notify = array_unique($notify);

			if(!empty($notify))
			{
				$this->load->model('User_model');

				switch($consequence->incidenttype)
				{
					case 'demerit':
					case 'intervention':
					case 'referral':
						$link = $consequence->incidenttype.'/view/'.$consequence->incidentid;
						$object_id = $consequence->incidenttype.'/'.$consequence->incidentid;
					break;
					case 'report':
						$link = 'report/response/'.$consequence->incidentid;
						$object_id = 'report/'.$consequence->incidentid;
					break;			
				}

				$text = '';
				switch(true)
				{
					// Status changed
					case $progress != $consequence->progress && in_array($progress, array('Completed')):
						$student = $this->User_model->get_user($consequence->studentid);
						$text = $student->firstname.' '.$student->lastname.' has completed their consequence';
					break;
					case $progress != $consequence->progress && in_array($progress, array('Dismissed')):
						$student = $this->User_model->get_user($consequence->studentid);
						$teacher = $this->User_model->get_user($user_id);
						$text = $teacher->firstname.' '.$teacher->lastname.' has dismissed '.$student->firstname.' '.$student->lastname."'s consequence";
					break;

					// Notes added
					case (!isset($consequence->notes) || $notes != $consequence->notes) && $new_note:
						$student = $this->User_model->get_user($consequence->studentid);
						$teacher = $this->User_model->get_user($user_id);
						$text = $teacher->firstname.' '.$teacher->lastname.' added a note to '.$student->firstname.' '.$student->lastname."'s consequence";
					break;

					// Notes changed
					case (!isset($consequence->notes) || $notes != $consequence->notes) && !$new_note:
						$student = $this->User_model->get_user($consequence->studentid);
						$teacher = $this->User_model->get_user($user_id);
						$text = $teacher->firstname.' '.$teacher->lastname.' updated a note for '.$student->firstname.' '.$student->lastname."'s consequence";
					break;
				}

				if(!empty($text))
				{
					$this->load->model('Notification_model');
					foreach($notify as $receiver)
					{
						if($receiver == $user_id)
						{
							continue;
						}

						$this->Notification_model->create($receiver, $text, $link, $object_id);
					}
				}
			}	


			$this->Consequence_model->update_status($consequence_id, $title, $progress, $data);

			switch($consequence->incidenttype)
			{
				case 'demerit':
				case 'intervention':
				case 'referral':
					redirect($consequence->incidenttype.'/view/'.$consequence->incidentid);
				break;
				case 'report':
					redirect('report/response/'.$consequence->incidentid);
				break;
			}	
		}
		else
		{
			$data = json_decode($consequence->data);

			if(isset($data->notify) && !empty($data->notify))
			{
				$this->load->model('User_model');
				$notify = $this->User_model->get_users($data->notify);
			}
			else
			{
				$notify = array();
			}

			$this->layout->view('consequence/edit', array(
				'consequence' => $consequence,
				'notify' => $notify,
				'consequences' => $consequences,
				'title_for_layout' => $consequence->title,
			));
		}
	}

	function remove($consequence_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Consequence_model');
		$this->load->model('Settings_model');

		$consequence = $this->Consequence_model->get_consequence($consequence_id);

		if(empty($consequence) || $consequence->status == 0)
		{
			$this->layout->view('consequence/error_notfound');
			return;
		}

		if($this->input->post('submit'))
		{
			$this->Consequence_model->remove($consequence_id);

			switch($consequence->incidenttype)
			{
				case 'demerit':
				case 'intervention':
				case 'referral':
					redirect($consequence->incidenttype.'/view/'.$consequence->incidentid);
				break;
				case 'report':
					redirect('report/response/'.$consequence->incidentid);
				break;
			}	
		}
		else
		{
			$data = json_decode($consequence->data);

			if(isset($data->notify) && !empty($data->notify))
			{
				$this->load->model('User_model');
				$notify = $this->User_model->get_users($data->notify);
			}
			else
			{
				$notify = array();
			}

			$this->layout->view('consequence/remove', array(
				'consequence' => $consequence,
				'notify' => $notify,
				'title_for_layout' => $consequence->title,
			));
		}
	}	
}