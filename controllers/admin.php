<?php

class Admin extends MY_Controller
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

	function rungoals()
	{
		$time = time();

		$query = $this->db->query('SELECT * FROM Goals WHERE status = ? AND timedue < ?', array(1, $time));
		$goals = $query->result();

		echo count($goals);

	}

	function index()
	{
		if($this->session->userdata('role') != 'a')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, SETTINGS_FEATURES));

		$this->load->model('Settings_model'); 
		$demerit_settings = json_decode($this->Settings_model->get_settings($school_id, 'demerits')); 

		$this->load->model('Referral_model');
		$active_referrals = $this->Referral_model->get_active_referrals($school_id);

		$this->load->model('Settings_model'); 
		$menu = json_decode($this->Settings_model->get_settings($school_id, 'dashadmin')); 

		$this->layout->view('admin/dashboard', array(
			'title_for_layout' => 'Admin Notification Center',
			'referrals' => $active_referrals,
			'demeritlabel' => $demerit_settings->demeritlabel,
			'settings' => $settings,
			'menu' => $menu
		));
	}

	function referral($referral_id)
	{
		return;
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

		$this->load->model('User_model');
		$student = $this->User_model->get_user($referral->studentid);
		$teacher = $this->User_model->get_user($referral->teacherid);

		$this->layout->view('admin/referral', array(
			'title_for_layout' => $student->firstname.' '.$student->lastname,
			'teacher' => $teacher,
			'referral' => $referral
		));
	}

	function settings($sub = 'default', $param1 = '', $param2 = '')
	{
		if($this->session->userdata('role') != 'a')
		{
			redirect('login');
			return;
		}

		switch($sub)
		{
			case 'school':
			case 'user':
			case 'interventions':
			case 'reinforcements':
			case 'referrals':
			case 'reflection':
			case 'bully':
			case 'incident':
			case 'demerits':
			case 'consequences':
			case 'detentions':
			case 'status':
			case 'features':
			case 'motivations';
			case 'locations';
			case 'authentication':
			case 'permissions':
				$sub = '_settings_'.$sub;
				$this->$sub();
			break;
			case 'form':
			case 'sms':			
				$sub = '_settings_'.$sub;
				$this->$sub($param1, $param2);
			break;			
			case 'dashboard':						
				$sub = '_settings_'.$sub;
				$this->$sub($param1);
			break;			
			case 'default':
				$school_id = $this->session->userdata('schoolid');
				$this->load->model('Settings_model');
				$features = json_decode($this->Settings_model->get_settings($school_id, SETTINGS_FEATURES));

				$this->layout->view('admin/settings', array('title_for_layout' => 'Settings', 'features' => $features));
			break;
		}
	}

	function _settings_features()
	{
		$this->load->model('Settings_model');

		$school_id = $this->session->userdata('schoolid');

		$settings = json_decode($this->Settings_model->get_settings($school_id, SETTINGS_FEATURES));

		$features = array(
			'detentions' => array(
				'title' => 'Detentions'
			),
			'referrals' => array(
				'title' => 'Referrals'
			),
			'demerits' => array(
				'title' => 'Negative Behaviors'
			),
			'interventions' => array(
				'title' => 'Interventions'
			),
			'reinforcements' => array(
				'title' => 'Positive Reinforcements'
			),			
			'shoutouts' => array(
				'title' => 'Shoutouts'
			),
			'goals' => array(
				'title' => 'Goals'
			)
		); 

		if($this->input->post('submit'))
		{

			$new_settings = array();
			$features_enabled = $this->input->post('feature');

			foreach($features as $k=>$feature)
			{
			 	if(isset($features_enabled[$k]) && $features_enabled[$k] == '1')
			 	{
			 		$new_settings[$k] = 'ats';
			 	}
			 	else
			 	{
			 		$new_settings[$k] = false;
			 	}
			}

			$this->Settings_model->save($school_id, SETTINGS_FEATURES, $new_settings);

			redirect('admin/settings');
		}
		else
		{
			$this->layout->view('admin/settings_features', array('title_for_layout' => 'Features', 'settings' => $settings, 'features' => $features));
		}
	}

	function _settings_school()
	{
		if($this->input->post('submit'))
		{

		}
		else
		{
			$this->layout->view('admin/settings_school', array('title_for_layout' => 'School Settings'));
		}
	}

	function _settings_interventions()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$i = $this->input->post('intervention');

			$interventions = array();

			foreach($i as $intervention)
			{
				$intervention = trim($intervention);
				if(!empty($intervention))
				{
					array_push($interventions, $intervention);
				}
			}

			$quick_settings = json_decode($this->Settings_model->get_settings($school_id, 'quickentry'));
			$quick_settings->interventions = $interventions;
			$this->Settings_model->save($school_id, 'quickentry', $quick_settings);

			$this->Settings_model->save($school_id, 'interventions', $interventions);

			redirect('admin/settings');
		}
		else
		{
			$interventions = json_decode($this->Settings_model->get_settings($school_id, 'interventions'));

			$this->layout->view('admin/settings_interventions', array(
				'interventions' => $interventions,
				'title_for_layout' => 'Interventions'
			));
		}
	}

	function _settings_motivations()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$i = $this->input->post('motivation');

			$motivations = array();

			foreach($i as $motivation)
			{
				$motivation = trim($motivation);
				if(!empty($motivation))
				{
					array_push($motivations, $motivation);
				}
			}

			$this->Settings_model->save($school_id, 'motivations', $motivations);

			redirect('admin/settings');
		}
		else
		{
			$motivations = json_decode($this->Settings_model->get_settings($school_id, 'motivations'));

			$this->layout->view('admin/settings_motivations', array(
				'motivations' => $motivations,
				'title_for_layout' => 'Motivations'
			));
		}
	}

	function _settings_locations()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$i = $this->input->post('location');

			$locations = array();

			foreach($i as $location)
			{
				$location = trim($location);
				if(!empty($location))
				{
					array_push($locations, $location);
				}
			}

			$this->Settings_model->save($school_id, 'locations', $locations);

			redirect('admin/settings');
		}
		else
		{
			$locations = json_decode($this->Settings_model->get_settings($school_id, 'locations'));

			$this->layout->view('admin/settings_locations', array(
				'locations' => $locations,
				'title_for_layout' => 'Locations'
			));
		}
	}	

	function _settings_reinforcements()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$i = $this->input->post('reinforcement');

			$reinforcements = array();

			foreach($i as $reinforcement)
			{
				$reinforcement = trim($reinforcement);
				if(!empty($reinforcement))
				{
					array_push($reinforcements, $reinforcement);
				}
			}

			$award_type = $this->input->post('quantitytype');

			$quick_settings = json_decode($this->Settings_model->get_settings($school_id, 'quickentry'));
			$quick_settings->reinforcements = $reinforcements;

			$settings = array(
				'reinforcementlabel' => $this->input->post('reinforcementname'),
				'awardlabel' => $this->input->post('awardname'),
				'options' => $reinforcements,
				'quantitytype' => $award_type
			);

			$quick_settings->reinforcementsoptions = array(
				'awardlabel' => $this->input->post('awardname'),
				'quantitytype' => $award_type
			);

			$label_settings = json_decode($this->Settings_model->get_settings($school_id, 'labels'));
			$label_settings->reinforcement = $this->input->post('reinforcementname');
			$label_settings->reinforcements = $this->input->post('reinforcementsname');
			$this->Settings_model->save($school_id, 'labels', $label_settings);

			switch($award_type)
			{
				case 'fixed':
					$quick_settings->reinforcementsoptions['awardamount'] = $this->input->post('awardamount');
					$settings['awardamount'] = $this->input->post('awardamount');
				break;
				case 'range':
					$quick_settings->reinforcementsoptions['awardamountmin'] = $this->input->post('awardamountmin');
					$quick_settings->reinforcementsoptions['awardamountmax'] = $this->input->post('awardamountmax');

					$settings['awardamountmin'] = $this->input->post('awardamountmin');
					$settings['awardamountmax'] = $this->input->post('awardamountmax');
				break;
				case 'number':
					$quick_settings->reinforcementsoptions['awardamountmax'] = $this->input->post('awardamountmax');
		 			$settings['awardamountmax'] = $this->input->post('awardamountmax');
				break;
			}

			$this->Settings_model->save($school_id, 'quickentry', $quick_settings);
			$this->Settings_model->save($school_id, 'reinforcements', $settings);

			redirect('admin/settings');
		}
		else
		{
			$reinforcements = json_decode($this->Settings_model->get_settings($school_id, 'reinforcements'));
			$labels = $label_settings = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

			$this->layout->view('admin/settings_reinforcements', array(
				'reinforcements' => $reinforcements,
				'title_for_layout' => 'Positive Reinforcement',
				'labels' => $labels
			));
		}
	}

	function _settings_demerits()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$i = $this->input->post('demerit');

			$demerits = array();

			foreach($i as $demerit)
			{
				$demerit = trim($demerit);
				if(!empty($demerit))
				{
					array_push($demerits, $demerit);
				}
			}

			$quick_settings = json_decode($this->Settings_model->get_settings($school_id, 'quickentry'));
			$quick_settings->negatives = $demerits;
			$this->Settings_model->save($school_id, 'quickentry', $quick_settings);

			$settings = array(
				'demeritlabel' => $this->input->post('demeritname'),
				'demerits' => $demerits
			);

			$this->Settings_model->save($school_id, 'demerits', $settings);

			$label_settings = json_decode($this->Settings_model->get_settings($school_id, 'labels'));
			$label_settings->demerit = $this->input->post('demeritname');
			$label_settings->demerits = $this->input->post('demeritsname');
			$this->Settings_model->save($school_id, 'labels', $label_settings);

			redirect('admin/settings');
		}
		else
		{
			$demerits = json_decode($this->Settings_model->get_settings($school_id, 'demerits'));
			$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

			$this->layout->view('admin/settings_demerits', array(
				'demerits' => $demerits,
				'title_for_layout' => 'Negative Behaviors',
				'labels' => $labels
			));
		}
	}

	function _settings_referrals()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$keys = $settings = array();
			$settings['questions'] = $this->_process_edit_form('f', $keys);
			$settings['keys'] = $keys;

			$this->Settings_model->save($school_id, 'referrals', $settings);

			redirect('admin/settings');
		}
		else
		{
			$settings = json_decode($this->Settings_model->get_settings($school_id, 'referrals'));

			$this->layout->view('admin/settings_referrals', array(
				'settings' => $settings,
				'title_for_layout' => 'Referrals'
			));
		}
	}

	function _settings_reflection()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		$settings = json_decode($this->Settings_model->get_settings($school_id, 'reflection'));

		if($this->input->post('submit'))
		{
			$keys = array();
			$new_settings = array('questions' => $this->_process_edit_form('f', $keys));

			$this->Settings_model->save($school_id, 'reflection', $new_settings);

			redirect('admin/settings');
		}
		else
		{
			$this->layout->view('admin/settings_reflection', array(
				'settings' => $settings,
				'title_for_layout' => 'Reflection'
			));
		}
	}

	function _settings_user()
	{
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('User_model');
		$users = $this->User_model->get_school_staff($school_id);

		$this->layout->view('admin/settings_user', array(
			'title_for_layout' => 'User Settings',
			'users' => $users
		));
	}

	function _settings_bully()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$settings = array();
			$keys = array();
			$settings['questions'] = $this->_process_edit_form('f', $keys);

			$this->Settings_model->save($school_id, 'bully', $settings);

			redirect('admin/settings');
		}
		else
		{
			$settings = json_decode($this->Settings_model->get_settings($school_id, 'bully'));

			$this->layout->view('admin/settings_bully', array(
				'settings' => $settings,
				'title_for_layout' => 'Bullying'
			));
		}
	}

	function _settings_incident()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$settings = array();
			$settings['questions'] = array(
				'easy' => $this->_process_edit_form('easy'),
				'detailed' => $this->_process_edit_form('detailed')
			);

			$this->Settings_model->save($school_id, 'incident', $settings);

			redirect('admin/settings');
		}
		else
		{
			$settings = json_decode($this->Settings_model->get_settings($school_id, 'incident'));

			$this->layout->view('admin/settings_incident', array(
				'settings' => $settings,
				'title_for_layout' => 'Incident Student Response'
			));
		}
	}

	function _settings_consequences()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		$admin_review = json_decode($this->Settings_model->get_settings($school_id, 'adminreview'));

		if($this->input->post('submit'))
		{
			$i = $this->input->post('consequence');

			$consequences = array();

			foreach($i as $consequence)
			{
				$demerit = trim($consequence);
				if(!empty($consequence))
				{
					array_push($consequences, $consequence);
				}
			}

			$admin_review->consequences = $consequences;

			$this->Settings_model->save($school_id, 'adminreview', $admin_review);
			$this->Settings_model->save($school_id, 'consequences', $consequences);

			redirect('admin/settings');
		}
		else
		{
			$this->layout->view('admin/settings_consequences', array(
				'consequences' => $admin_review->consequences,
				'title_for_layout' => 'Consequences'
			));
		}
	}

	function _settings_detentions()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		$this->load->model('Settings_model');
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'quickentry'));
		$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

		if($this->input->post('submit'))
		{
			$i = $this->input->post('detention');
			$detention_name = $this->input->post('detentionname');
			$detentions_name = $this->input->post('detentionsname');
			$detention_unit_name = $this->input->post('detentionunitname');
			$detention_units_name = $this->input->post('detentionunitsname');

			$labels->detention = $detention_name;
			$labels->detentions = $detentions_name;
			$labels->detentionunit = $detention_unit_name;
			$labels->detentionunits = $detention_units_name;

			$detentions = array();

			foreach($i as $detention)
			{
				$detention = trim($detention);
				if(!empty($detention))
				{
					array_push($detentions, $detention);
				}
			}

			$settings->detentions = $detentions;

			$this->Settings_model->save($school_id, 'quickentry', $settings);
			$this->Settings_model->save($school_id, 'labels', $labels);

			redirect('admin/settings');

		}
		else
		{
			$this->layout->view('admin/settings_detentions', array(
				'detentions' => $settings->detentions,
				'labels' => $labels,
				'title_for_layout' => 'Detentions'
			));
		}

	}

	function _settings_status()
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		if($this->input->post('submit'))
		{
			$i = $this->input->post('status');

			$statuses = array();

			foreach($i as $status)
			{
				$status = trim($status);
				if(!empty($status))
				{
					array_push($statuses, $status);
				}
			}

			$quick_settings = json_decode($this->Settings_model->get_settings($school_id, 'quickentry'));
			$quick_settings->statuses = $statuses;
			$this->Settings_model->save($school_id, 'quickentry', $quick_settings);

			$settings = array(
				'statuses' => $statuses
			);

			$this->Settings_model->save($school_id, 'statuses', $settings);

			redirect('admin/settings');
		}
		else
		{
			$statuses = json_decode($this->Settings_model->get_settings($school_id, 'statuses'));

			$this->layout->view('admin/settings_statuses', array(
				'statuses' => $statuses,
				'title_for_layout' => 'Statuses'
			));
		}
	}	

	function _settings_dashboard($role = '')
	{
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Settings_model');

		if(!empty($role))
		{
			$settings = $this->Settings_model->get_settings($school_id, 'dash'.$role);
			$menu = json_decode($settings);

			if($this->input->post('submit'))
			{
				$titles = $this->input->post('menu');
				$path = $this->input->post('path');

				$i = 0;

				$menu = array();
				foreach($titles as $title)
				{
					if(!empty($title))
					{
						$menu[$path[$i]] = $title;	
					}
					
					$i++;
				}

				$this->Settings_model->save($school_id, 'dash'.$role, array('menu' => $menu));

				redirect('admin/settings');
			}
			else
			{
				$this->load->model('Form_model');
				$forms = $this->Form_model->get_by_school($school_id);
				
				$permissions = array('admin' => 1000, 'teacher' => 100, 'student' => 10);

				$this->load->model('Report_model');
				$reports = $this->Report_model->get_reports($school_id, 'charts', $permissions[$role]);

				$this->layout->view('admin/settings_dashboard_edit', array('role' => $role, 'menu' => $menu, 'forms' => $forms, 'reports' => $reports));
			}
		}
		else
		{
			$this->layout->view('admin/settings_dashboard');
		}
	}

	function _settings_sms($action = '', $type = '')
	{
		$school_id = $this->session->userdata('schoolid');
		$this->load->model('Settings_model');

		$messages = json_decode(json_encode(array(
			'referral' => array(
				'enabled' => true,
				'title' => 'New Referral',
				'message' => 'this is a test'
			)
		)));

		$messages = json_decode($this->Settings_model->get_settings($school_id, 'sms'));

		if(!empty($action) && ($action == 'add' || isset($messages->$type)))
		{
			if($this->input->post('submit'))
			{	
				switch($action)
				{
					case 'add':
					case 'edit';
						if($action == 'add')
						{
							$type = 'sms'.md5($title);
							$messages->$type = new stdClass();
						}

						$enabled = $this->input->post('enabled') !== false && $this->input->post('enabled') == '1' ? true : false;
						$message = $this->input->post('message');
						$title = $this->input->post('title');						

						$messages->$type->title = $title;
						$messages->$type->enabled = $enabled;
						$messages->$type->message = $message;

						$this->Settings_model->save($school_id, 'sms', $messages);
					break;
					case 'remove':
						if(isset($messages->$type))
						{
							unset($messages->$type);
							
							$this->Settings_model->save($school_id, 'sms', $messages);
						}
					break;
				}


				redirect('admin/settings/sms');
			}
			else
			{
				switch($action)
				{
					case 'add':
						$message = new stdClass();
						$message->enabled = false;
						$message->message = '';
						$message->title = '';

						$view = 'edit';
					break;
					case 'remove':
						$message = $messages->$type;
						$view = 'remove';
					break;
					default:
						$message = $messages->$type;

						$view = 'edit';
					break;
				}

				$this->layout->view('admin/settings_sms'.$view, array('action' => $action, 'type' => $type, 'message' => $message));
			}
		}
		else
		{
			$this->load->model('Sms_model');
			$sms_used = $this->Sms_model->count_sent($school_id);

			$this->layout->view('admin/settings_sms', array('messages' => $messages, 'smssent' => $sms_used, 'title_for_layout' => 'SMS Messages'));
		}
	}	

	function _settings_authentication()
	{
		$available_options = array(
			'googlesignin' => 'Their Google/Google Plus account', 
			'emailsignin' => 'Their email and password'
		);

		$school_id = $this->session->userdata('schoolid');
		$this->load->model('School_model');
		$school = $this->School_model->get_school($school_id);
		$school->metadata = json_decode($school->metadata);

		$error = '';

		if($this->input->post('submit'))
		{
			$options = $this->input->post('option');

			if($options === false)
			{
				$error = 'nooptionselected';
			}
		}
		
		if(empty($error) && $this->input->post('submit'))
		{
			foreach($available_options as $k=>$v)
			{
				$school->metadata->$k = isset($options[$k]) && $options[$k] == '1';
			}

			$this->School_model->update_metadata($school_id, $school->metadata);

			redirect('admin/settings');
		}
		else
		{
			$data = array('school' => $school,
				'availableoptions' => $available_options);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['options'] = $options === false ? array() : $options;
			}

			$this->layout->view('admin/settings_authentication', $data);	
		}
		
	}

	function _settings_permissions()
	{
		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Settings_model');
		
		$settings = json_decode($this->Settings_model->get_settings($school_id, 'permissions'));

		if($settings === false)
		{
			$settings = new stdClass();
		}
				
		$options = array('teacherviewall' => 'Teachers can view activities about their student created by other teachers');

		if($this->input->post('submit'))
		{
			foreach($options as $k=>$option)
			{
				if($this->input->post($k) == '1')
				{
					$settings->$k = true;
				}
				else
				{
					if(isset($settings->$k))
					{
						unset($settings->$k);
					}
				}
			}

			$this->Settings_model->save($school_id, 'permissions', $settings);
			redirect('admin/settings');
		}
		else
		{
			$data = array(
				'title_for_layout' => 'Permissions', 
				'settings' => $settings,
				'options' => $options
			);
			
			$this->layout->view('admin/settings_permissions', $data);			
		}
	}

	function _process_edit_form($prefix = '', &$keys = array())
	{
		$labels = $this->input->post($prefix.'label');
		$placeholders = $this->input->post($prefix.'placeholder');
		$required = $this->input->post($prefix.'required');
		$multiple = $this->input->post($prefix.'multiple');

		$form_key = $this->input->post('key');
		if($form_key === false)
		{
			$form_key = array();
		}

//		header('Content-type: text/plain');
//		print_r($placeholders);

		$elements = array();
		foreach($labels as $key=>$value)
		{
			list($element, $id) = preg_split('/_/', $key);

			$is_required = !empty($required) && array_key_exists($id, $required) ? 1 : 0;
			$new_id = md5($value);

			if(array_key_exists($id, $form_key))
			{
				$keys[$form_key[$id]] = $new_id;
			}

			switch($element)
			{
				case 'instruction':
					array_push($elements, array(
						'type' => 'instruction',
						'id' => md5($value),
						'text' => $value
					));
				break;
				case 'textarea':
					$placeholder = isset($placeholders[$key]) ? $placeholders[$key] : '';
					array_push($elements, array(
						'type' => 'textarea',
						'id' => md5($value),
						'label' => $value,
						'placeholder' => $placeholder,
						//'required' => $is_required,
					));
				break;
				case 'list':
					$opt = $this->input->post($id.'option');

					$options = array();

					foreach($opt as $o)
					{
						if(empty($o))
						{
							continue;
						}

						array_push($options, $o);
					}

					array_push($elements, array(
						'type' => 'multicheckbox',
						'id' => md5($value),
						'label' => $value,
						//'required' => $is_required,
						'options' => $options
					));
				break;
				case 'select':
					$opt = $this->input->post($id.'option');

					$options = array();

					foreach($opt as $o)
					{
						if(empty($o))
						{
							continue;
						}

						array_push($options, $o);
					}

					array_push($elements, array(
						'type' => 'select',
						'id' => md5($value),
						'label' => $value,
						//'required' => $is_required,
						'options' => $options
					));
				break;
				case 'personsearch':
					$allow_multiple = !empty($multiple) && $multiple[$id] == '1' ? 'true' : 'false';

					array_push($elements, array(
						'type' => 'personsearch',
						'label' => $value,
						'id' => md5($value),
						'multiple' => $allow_multiple,
						//'required' => $is_required,
					));
				break;
				case 'controlgroup':
					array_push($elements, array(
						'type' => 'controlgroup',
						'id' => md5($value),
						'label' => $value,
						'elements' => $this->_process_edit_form($id)
					));
				break;
				case 'collapsible':
					array_push($elements, array(
						'type' => 'collapsible',
						'id' => md5($value),
						'label' => $value,
						'elements' => $this->_process_edit_form($id)
					));
				break;
			}
		}

		return $elements;
	}

	function students()
	{
		if($this->session->userdata('role') != 'a')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Group_model');
		$groups = $this->Group_model->get_groups_by_school($school_id);

		$data = array(
			'title_for_layout' => 'Students',
			'groups' => $groups
		);

		$this->load->model('User_model');
		$total_unassigned = $this->User_model->count_unassigned($school_id);

		if($total_unassigned > 0)
		{
			$data['showunassigned'] = true;
		}

		$this->layout->view('admin/students', $data);
	}

	function users($page = 0)
	{
		$this->load->model('User_model');

		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$firstname = $this->input->post('firstname');
		$lastname = $this->input->post('lastname');
		$email = $this->input->post('email');

		$saved = false;

		if($this->input->post('submit'))
		{
			foreach($firstname as $k=>$v)
			{
				$this->db->update('Users', array(
					'firstname' => $firstname[$k],
					'lastname' => $lastname[$k],
					'email' => $email[$k]
				), array('userid' => $k));
			}

			$saved = true;
		}

		$users = $this->User_model->get_students_from_school($school_id, 50, $page*50);

		$total = $this->User_model->count_students_from_school($school_id);

		$data = array(
			'users' => $users,
			'page' => $page
		);

		if($saved)
		{
			$data['saved'] = true;
		}

		if(($page+1)*50 < $total)
		{
			$data['next'] = $page+1;
		}

		if($page > 0)
		{
			$data['previous'] = $page-1;
		}

		$this->layout->view('admin/users', $data);
	}

	function sendsms()
	{
		$this->load->model('Sms_model');
		$this->Sms_model->send(12, "650-267-4831", 'referral');
	}

	function view($admin_id)
	{
		if($this->session->userdata('role') != 'a')
		{
			redirect('login');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$this->load->model('User_model');
		$user = $this->User_model->get_user($admin_id);

		if(empty($user) || $user->accounttype != 'a')
		{
			$this->layout->view('admin/error_noadminfound');
			return;
		}

		$permission_denied = true;

		$this->load->model('School_model');
		if($user->schoolid != $school_id)
		{
			$permission_denied = false;
		}

		if($permission_denied)
		{
			$this->layout->view('admin/error_noadminfound');
			return;
		}	

		$this->layout->view('admin/view', array(
			'admin' => $user, 
			'title_for_layout' => $user->firstname.' '.$user->lastname
		));
	}
}
?>