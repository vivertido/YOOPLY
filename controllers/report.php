<?php

class Report extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/report');
			return;
		}

		$school_id = $this->session->userdata('schoolid');

		$this->load->model('Report_model');

		switch($this->session->userdata('role'))
		{
			case 'a';
				$viewer = bindec('1000');
			break;
			case 't';
				$viewer = bindec('0100');
			break;			
			case 's';
				$viewer = bindec('0010');
			break;						
		}

		$reports = $this->Report_model->get_reports($school_id, 'charts', $viewer);

		$this->layout->view('report/index', array('reports' => $reports, 'title_for_layout' => 'Reports'));
	}

	function response($report_id, $public_token = '')
	{
		$has_token = false;
		$show_edit_button = false;

		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Report_model');

		$report = $this->Report_model->get_response($report_id);

		$token = preg_split('/-/', $public_token);

		if(empty($report) || $report->status != 1)
		{
			$this->layout->view('report/error_permissiondenied');
			return;
		}

		if(!$this->session->userdata('userid') && !empty($public_token))
		{
			$parent_hash = $token[0];
	 		$view_token = md5($report->reportid.'YLPOOY'.$parent_hash.'SECURE'.$report->nonce);

			if($token[1] != $view_token)
			{
				$this->layout->view('report/error_permissiondenied');
				return;
			}
			else
			{
				$has_token = true;
			}
		}

		if(!$has_token && $report->schoolid != $school_id)
		{
			$this->layout->view('report/error_permissiondenied');
			return;
		}

		$this->load->model('Form_model');
		$form = $this->Form_model->get_form($report->objectid);
		
		if(!$has_token && strpos($form->viewers, $this->session->userdata('role')) === false)
		{
			$this->layout->view('form/error_permissiondenied');
			return;	
		}	

		// Students can only see their own responses.
		if(!$has_token && $this->session->userdata('role') == 's' && $report->userid != $user_id)
		{
			//$this->layout->view('form/error_permissiondenied');
			//return;				
		}

		if(!$has_token)
		{
			switch($this->session->userdata('role'))
			{
				case 'a':
				case 't':
					$show_edit_button = true;
				break;
			}
		}

		$data = array(
			'report' => $report,
			'showeditbutton' => $show_edit_button
		);

		$this->load->model('User_model');
		$data['user'] = $this->User_model->get_user($report->userid);

		if($report->subjectid != '0')
		{
			$data['subject'] = $this->User_model->get_user($report->subjectid);
		}
		
		$data['ispublic'] = !$this->session->userdata('userid') && $has_token;

		$this->load->model('Consequence_model');
		$data['consequences'] = $this->Consequence_model->get_by_incident('report', $report->reportid);

		$this->layout->view('report/view_form', $data);
	}

	function form($form_id, $period = 'today', $subject = '-', $order = 'd')
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/report.form.'.$form_id.'.'.$period.($subject != '-1' ? '.'.$subject : ''));
			return;
		}

		$this->load->model('Report_model');

		$this->load->model('Form_model');
		$form = $this->Form_model->get_form($form_id);

		if(empty($form) || $form->status != 1)
		{
			$this->layout->view('report/error_permissiondenied');
			return;
		}

		if(strpos($form->viewers, $this->session->userdata('role')) === false)
		{
			$this->layout->view('form/error_permissiondenied');
			return;	
		}		

		if($this->session->userdata('role') == 's')
		{
			$subject = $this->session->userdata('userid');
		}

		$data = array(
			'title_for_layout' => $form->title,
			'form' => $form
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

		$order_by = $order == 'd' ? 'DESC' : 'ASC';

		$include_name = $form->subject == 's' && $subject == '-';
		$data['responses'] = $this->Report_model->get_by_form($form_id, $subject, $start_time, $end_time, $include_name, $order_by);

		if($subject != '-')
		{
			$data['subjectid'] = $subject;
		}

		$this->layout->view('report/list_by_form', $data);
	}

	function add($type)
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/report.add.'.$type);
			return;
		}

		switch($type)
		{
			case 'charts':
				$this->_edit_charts();
			break;
		}
	}

	function edit($report_id)
	{
		if(!$this->session->userdata('userid'))
		{

			redirect('login/report.edit.'.$report_id);
			return;
		}

		$this->load->model('Report_model');
		$this->load->model('Form_model');

		$report = $this->Report_model->get_report($report_id);
		$user_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');
		
		if(empty($report) || $report->status != 1)
		{
			$this->layout->view('form/error_permissiondenied');
			return;				
		}

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 'a':
				if($report->schoolid == $school_id)
				{
					$permission_denied = false;
				}
			break;
			case 't':
				if($report->userid == $user_id)
				{
					$permission_denied = false;
				}
			break;
		}
		
		if($permission_denied)
		{
			$this->layout->view('form/error_permissiondenied');
			return;				
		}

		switch($report->type)
		{
			case 'form':
			case 'charts':
				$type = '_edit_'.$report->type;
				$this->$type($report);
			break;
		}
	}

	function _edit_form($report)
	{
		$user_id = $this->session->userdata('userid');
		$school_id = $this->session->userdata('schoolid');

		$form = $this->Form_model->get_form($report->objectid);

		if(strpos($form->contributors, $this->session->userdata('role')) === false)
		{
			$this->layout->view('form/error_permissiondenied');
			return;	
		}			

		if($this->input->post('submit'))
		{
			$questions = json_decode($form->formdata);
			$new_report = process_form('f', $questions->questions);

			$title = '';
			$time_incident = $report->timeincident;

			if(!empty($form->indextitle))
			{
				foreach($new_report as $q)
				{
					if($q['label'] == $form->indextitle)
					{
						$title = is_array($q['value']) ? implode(',', $q['value']) : $q['value'];
					}				
				}
			}

			if(!empty($form->timetitle))
			{
				foreach($new_report as $q)
				{
					if($q['label'] == $form->timetitle)
					{
						$time_incident = strtotime($q['value']);
					}					
				}
			}

			$this->load->model('Report_model');
			
			$this->Report_model->update($report->reportid, $new_report, $title, $time_incident);
			redirect('report/response/'.$report->reportid);
		}
		else
		{
			$this->layout->view('report/edit_form', array(
				'report' => $report, 
				'form' => $form
			));
		}
	}

	function _edit_charts($report = null)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$role = $this->session->userdata('role');
		$permissions = array('a' => 1000, 't' => 100, 's' => 10);

		if(!empty($report))
		{
			if(!(isset($permissions[$role]) && $permissions[$role] & intval($report->objectid)))
			{
				$this->layout->view('report/error_permissiondenied');
				return;
			}
		}

		if($this->input->post('submit'))
		{
			$keys = $this->input->post('key');

			$template = array();
			foreach($keys as $k=>$type)
			{
				switch($type)
				{
					case 'gauge':
						$source = $this->input->post($k.'source');
						if(substr($source, 0, 4) == 'form')
						{
							list($object, $form_id, $viewer, $group_by, $when) = preg_split('/\//', $source);
						}
						else
						{
							list($object, $viewer, $group_by, $when) = preg_split('/\//', $source);	
						}

						$title = $this->input->post($k.'title') ? $this->input->post($k.'title') : '';
						$scale = $this->input->post($k.'scale');

						$element = array('type' => 'gauge',
							'object' => $object,
							'when' => $when,
							'title' => $title,
							'scale' => $scale,
							'scope' => $viewer
						);

						if($object == 'form')
						{
							$element['formid'] = $form_id;
						}

						array_push($template, $element);
					break;
					case 'pie':
						$source = $this->input->post($k.'source');
						if(substr($source, 0, 4) == 'form')
						{
							list($object, $form_id, $viewer, $group_by, $when) = preg_split('/\//', $source);
						}
						else
						{
							list($object, $viewer, $group_by, $when) = preg_split('/\//', $source);
						}

						$title = $this->input->post($k.'title') ? $this->input->post($k.'title') : '';

						$element = array(
							'type' => 'pie',
							'object' => $object,
							'when' => $when,
							'groupby' => $group_by,
							'title' => $title,
							'scope' => $viewer,
						);

						if($object == 'form')
						{
							$element['formid'] = $form_id;
						}

						array_push($template, $element);
					break;
					case 'leaderboard':
						$source = $this->input->post($k.'source');
						if(substr($source, 0, 4) == 'form')
						{
							list($object, $form_id, $viewer, $group_by, $when) = preg_split('/\//', $source);
						}
						else
						{
							list($object, $viewer, $group_by, $when) = preg_split('/\//', $source);
						}

						$title = $this->input->post($k.'title') ? $this->input->post($k.'title') : '';

						$element = array(
							'type' => 'leaderboard',
							'object' => $object,
							'when' => $when,
							'title' => $title,
							'scope' => $viewer,
						);

						if($object == 'form')
						{
							$element['formid'] = $form_id;
						}

						array_push($template, $element);						
					break;
					case 'line':
						$source = $this->input->post($k.'source');
						if(substr($source, 0, 4) == 'form')
						{
							list($object, $form_id, $viewer, $blank, $when, $interval) = preg_split('/\//', $source);
						}
						else
						{
							list($object, $viewer, $group_by, $blank, $when, $interval) = preg_split('/\//', $source);
						}

						$title = $this->input->post($k.'title') ? $this->input->post($k.'title') : '';

						$element = array(
							'type' => 'line',
							'object' => $object,
							'when' => $when,
							'interval' => $interval,
							'title' => $title,
							'scope' => $viewer,
						);

						if($object == 'form')
						{
							$element['formid'] = $form_id;
						}

						array_push($template, $element);	
					break;
					case 'section':
						$title = $this->input->post($k.'title');
						array_push($template, array(
							'type' => 'section',
							'title' => $title
						));
					break;
					case 'spacer':
						array_push($template, array(
							'type' => 'spacer'
						));
					break;
				}
			}

			$title = $this->input->post('reporttitle');

			$viewers = $this->input->post('viewers');

			$permission = 0;
			foreach($viewers as $p)
			{
				$permission = $permission | bindec($p);
			}

			if(empty($report))
			{
				$this->load->model('Report_model');
				$report_id = $this->Report_model->create($school_id, $user_id, 0, 'charts', $permission, $template, $title);	
			}
			else
			{
				$this->load->model('Report_model');
				$this->Report_model->update_charts($report->reportid, $template, $title, $permission);	

				$report_id = $report->reportid;
			}

			redirect('report/view/'.$report_id);
		}
		else
		{
			if(!empty($report))
			{
				$elements = json_decode($report->report);	
			}
			else
			{
				$elements = array();
			}
			
			$results = $this->_buildtemplate($elements);
			

			$this->load->model('Settings_model');
			$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));

			$settings = json_decode($this->Settings_model->get_settings($school_id, 'features'));

			$this->load->model('Form_model');
			$forms = $this->Form_model->get_viewable($school_id, 'a', '');

			$this->layout->view('report/edit_charts', array(
				'results' => $results, 
				'reportid' => empty($report) ? 'add' : $report->reportid,
				'title_for_layout' => empty($report) ? 'New Report' : $report->title,
				'reporttitle' => empty($report) ? 'New Report' : $report->title,
				'labels' => $labels,
				'settings' => $settings,
				'forms' => $forms,
				'viewers' => empty($report) ? bindec('1000') : $report->objectid // This is a bitwise value representing admin,teacher,student,0. If bit is 1, they can see it.
			));
		}
	}		

	function remove($report_id)
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/report.remove.'.$report_id);
			return;
		}

		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Report_model');

		$report = $this->Report_model->get_response($report_id);

		if(empty($report) || $report->status != 1)
		{
			$this->layout->view('report/error_permissiondenied');
			return;
		}

		if($report->schoolid != $school_id)
		{
			$this->layout->view('report/error_permissiondenied');
			return;
		}

		switch($report->type)
		{
			case 'form':
			case 'charts':
				$type = '_remove_'.$report->type;
				$this->$type($report);
			break;
		}
	}

	function _remove_form($report)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Form_model');
		$form = $this->Form_model->get_form($report->objectid);

		if(strpos($form->viewers, $this->session->userdata('role')) === false)
		{
			$this->layout->view('form/error_permissiondenied');
			return;	
		}	

		// Students can only see their own responses.
		if($this->session->userdata('role') == 's' && $report->userid != $user_id)
		{
			$this->layout->view('form/error_permissiondenied');
			return;				
		}

		if($this->input->post('cancel'))
		{
			redirect('report/response/'.$report->reportid);
			return;
		}

		if($this->input->post('submit'))
		{
			$this->Report_model->remove($report->reportid);

			$this->load->model('Settings_model');

			$paths = implode('|', array(
				'^\/report\/view\/'.$report->reportid.'\/*'
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

				if($changed)
				{
					$settings->menu = $new_menu;

					$this->load->model('Report_model');
					
					$this->Settings_model->save($school_id, 'dash'.$role, $settings);
				}
			}

			switch($this->session->userdata('role'))
			{
				case 'a':
					redirect('admin');
					return;
				break;
				case 't':
					redirect('teacher');
					return;
				break;				
			}
		}
		else
		{
			$data = array(
				'report' => $report
			);

			$this->load->model('User_model');
			$data['user'] = $this->User_model->get_user($report->userid);

			if($report->subjectid != '0')
			{
				$data['subject'] = $this->User_model->get_user($report->subjectid);
			}

			$this->layout->view('report/remove_form', $data);
		}		
	}

	function _remove_charts($report)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$role = $this->session->userdata('role');
		$permissions = array('a' => 1000, 't' => 100, 's' => 10);

		if(!(!empty($report) && isset($permissions[$role]) && $permissions[$role] & intval($report->objectid)))
		{
			$this->layout->view('report/error_permissiondenied');
			return;
		}

		if($this->input->post('cancel'))
		{
			redirect('report/view/'.$report->reportid);
			return;
		}

		if($this->input->post('submit'))
		{
			$this->Report_model->remove($report->reportid);

			switch($this->session->userdata('role'))
			{
				case 'a':
					redirect('report');
					return;
				break;				
			}
		}
		else
		{
			$data = array(
				'report' => $report
			);

			$this->layout->view('report/remove_charts', $data);
		}		
	}	

	function view($report_id)
	{
		if(!$this->session->userdata('userid'))
		{
			redirect('login/report.view.'.$report_id);
			return;
		}

		$this->load->model('Report_model');
		$report = $this->Report_model->get_report($report_id);

		switch($report->type)
		{
			case 'charts':
				$this->_view_charts($report);
			break;
		}
	}

	function _view_charts($report)
	{
		$elements = json_decode($report->report);
			
		$school_id = $this->session->userdata('schoolid');
		$role = $this->session->userdata('role');
		$permissions = array('a' => 1000, 't' => 100, 's' => 10);

		if(!(!empty($report) && $report->status == 1 && isset($permissions[$role]) && $permissions[$role] & intval($report->objectid)))
		{
			$this->layout->view('report/error_permissiondenied');
			return;
		}

		$results = $this->_buildtemplate($elements);
		
		$this->layout->view('report/view_charts', array('results' => $results, 'title_for_layout' => $report->title, 'reportid' => $report->reportid));
	}

	function _buildtemplate($elements)
	{
		$school_id = $this->session->userdata('schoolid');

		$results = array();

		foreach($elements as $element)
		{
			switch($element->type)
			{
				case 'spacer':
					array_push($results, array('type' => 'spacer'));
					continue 2;
				break;
				case 'section':
					array_push($results, array('type' => 'section', 'title' => $element->title));
					continue 2;
				break;
			}

			$teacher_id = isset($element->scope) && $element->scope == 'teacher' ? $this->session->userdata('userid') : 0;

			$months = array('1' => 'Jan', '2' => 'Feb', '3' => 'Mar', '4' => 'Apr', '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Aug', '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec');

			switch($element->object)
			{
				case 'demerit':
					$this->load->model('Demerit_model');

					switch($element->type)
					{
						case 'gauge':
							array_push($results, array(
								'type' => 'gauge',
								'source' => 'demerit/'.$element->scope.'/count/'.$element->when,
								'total' => $this->Demerit_model->count_today($element->when, $school_id, $teacher_id),
								'title' => $element->title,
								'scale' => $element->scale,
							));
						break;
						case 'leaderboard':
							array_push($results, array(
								'type' => 'leaderboard', 	
								'source' => 'demerit/'.$element->scope.'/leaderboard/'.$element->when,
								'title' => $element->title,					
								'results' => $this->Demerit_model->top_demerits($element->when, $school_id, $teacher_id, 5)
							));
						break;
						case 'line':
							array_push($results, array(
								'type' => 'line',
								'source' => 'demerit/'.$element->scope.'/interval/'.$element->when.'/'.$element->interval,
								'title' => $element->title,
								'results' => $this->_flatten($this->Demerit_model->count_by_interval($element->when, $element->interval, $school_id, $teacher_id, 0), $element->interval == 'month' ? $months : array())
							));
						break;						
						case 'pie':
							switch($element->groupby)
							{
								case 'reason':
									switch($element->when)
									{
										case 'today':
										case 'week':
										case 'year':
											array_push($results, array(
												'type' => 'pie', 
												'source' => 'demerit/'.$element->scope.'/reason/'.$element->when,
												'title' => $element->title,
												'results' => $this->Demerit_model->category_totals($element->when, $school_id, $teacher_id, 0, 'label', 'total')
											));
										break;
									}
								break;
								case 'teacher.name':
								case 'teacher.ethnicity':
								case 'teacher.gender':
								case 'student.name':								
								case 'student.grade':
								case 'student.ethnicity':
								case 'student.gender':
									array_push($results, array(
										'type' => 'pie', 
										'source' => 'demerit/'.$element->scope.'/'.$element->groupby.'/'.$element->when,
										'title' => $element->title,						
										'results' => $this->Demerit_model->category_totals_by($element->groupby, $element->when, $school_id, $teacher_id, 0, 'label', 'total')
									));
								break;
							}
						break;						
					}
				break;
				case 'referral':
					$this->load->model('Referral_model');

					switch($element->type)
					{
						case 'gauge':
							array_push($results, array(
								'type' => 'gauge',
								'source' => 'referral/'.$element->scope.'/count/'.$element->when,
								'total' => $this->Referral_model->count_today($element->when, $school_id, $teacher_id),
								'title' => $element->title,
								'scale' => $element->scale								
							));
						break;
						case 'leaderboard':
							array_push($results, array(
								'type' => 'leaderboard', 	
								'source' => 'referral/'.$element->scope.'/leaderboard/'.$element->when,
								'title' => $element->title,					
								'results' => $this->Referral_model->top_referrals($element->when, $school_id, $teacher_id, 5)
							));
						break;
						case 'line':
							array_push($results, array(
								'type' => 'line',
								'source' => 'referral/'.$element->scope.'/interval/'.$element->when.'/'.$element->interval,
								'title' => $element->title,
								'results' => $this->_flatten($this->Referral_model->count_by_interval($element->when, $element->interval, $school_id, $teacher_id, 0), $element->interval == 'month' ? $months : array())
							));
						break;						
						case 'pie':
							switch($element->groupby)
							{
								case 'reason':
									switch($element->when)
									{
										case 'today':
										case 'week':
										case 'year':
											array_push($results, array(
												'type' => 'pie',
												'source' => 'referral/'.$element->scope.'/reason/'.$element->when,
												'title' => $element->title, 
												'results' => $this->Referral_model->category_totals($element->when, $school_id, $teacher_id, 0, 'label', 'total')
											));
										break;
									}
								break;
								case 'teacher.name':
								case 'teacher.ethnicity':
								case 'teacher.gender':
								case 'student.name':								
								case 'student.grade':
								case 'student.ethnicity':
								case 'student.gender':
									array_push($results, array(
										'type' => 'pie', 	
										'source' => 'referral/'.$element->scope.'/'.$element->groupby.'/'.$element->when,
										'title' => $element->title,					
										'results' => $this->Referral_model->category_totals_by($element->groupby, $element->when, $school_id, $teacher_id, 0, 'label', 'total')
									));
								break;
							}
						break;
					}
				break;
				case 'intervention':
					$this->load->model('Intervention_model');

					switch($element->type)
					{
						case 'gauge':
							array_push($results, array(
								'type' => 'gauge',
								'source' => 'intervention/'.$element->scope.'/count/'.$element->when,
								'total' => $this->Intervention_model->count_today($element->when, $school_id, $teacher_id),
								'title' => $element->title,
								'scale' => $element->scale								
							));
						break;
						case 'leaderboard':
							array_push($results, array(
								'type' => 'leaderboard', 	
								'source' => 'intervention/'.$element->scope.'/leaderboard/'.$element->when,
								'title' => $element->title,					
								'results' => $this->Intervention_model->top_interventions($element->when, $school_id, $teacher_id, 5)
							));
						break;
						case 'line':
							array_push($results, array(
								'type' => 'line',
								'source' => 'intervention/'.$element->scope.'/interval/'.$element->when.'/'.$element->interval,
								'title' => $element->title,
								'results' => $this->_flatten($this->Intervention_model->count_by_interval($element->when, $element->interval, $school_id, $teacher_id, 0), $element->interval == 'month' ? $months : array())
							));
						break;						
						case 'pie':
							switch($element->groupby)
							{
								case 'reason':
									switch($element->when)
									{
										case 'today':
										case 'week':
										case 'year':
											array_push($results, array(
												'type' => 'pie',
												'source' => 'intervention/'.$element->scope.'/reason/'.$element->when,
												'title' => $element->title, 
												'results' => $this->Intervention_model->category_totals($element->when, $school_id, $teacher_id, 0, 'label', 'total')
											));
										break;
									}
								break;
								case 'teacher.name':
								case 'teacher.ethnicity':
								case 'teacher.gender':
								case 'student.name':
								case 'student.grade':
								case 'student.ethnicity':
								case 'student.gender':
									array_push($results, array(
										'type' => 'pie', 	
										'source' => 'intervention/'.$element->scope.'/'.$element->groupby.'/'.$element->when,
										'title' => $element->title,					
										'results' => $this->Intervention_model->category_totals_bygrade($element->groupby, $element->when, $school_id, $teacher_id, 0, 'label', 'total')
									));
								break;
							}
						break;
					}
				break;	
				case 'reinforcement':
					$this->load->model('Reinforcement_model');

					switch($element->type)
					{
						case 'gauge':
							array_push($results, array(
								'type' => 'gauge',
								'source' => 'reinforcement/'.$element->scope.'/count/'.$element->when,
								'total' => $this->Reinforcement_model->count_today($element->when, $school_id, $teacher_id),
								'title' => $element->title,
								'scale' => $element->scale								
							));
						break;			
						case 'leaderboard':
							array_push($results, array(
								'type' => 'leaderboard', 	
								'source' => 'reinforcement/'.$element->scope.'/leaderboard/'.$element->when,
								'title' => $element->title,					
								'results' => $this->Reinforcement_model->top_reinforcements($element->when, $school_id, $teacher_id, 5)
							));
						break;				
						case 'line':
							array_push($results, array(
								'type' => 'line',
								'source' => 'reinforcement/'.$element->scope.'/interval/'.$element->when.'/'.$element->interval,
								'title' => $element->title,
								'results' => $this->_flatten($this->Reinforcement_model->count_by_interval($element->when, $element->interval, $school_id, $teacher_id, 0), $element->interval == 'month' ? $months : array())
							));
						break;													
						case 'pie':
							switch($element->groupby)
							{
								case 'reason':
									switch($element->when)
									{
										case 'today':
										case 'week':
										case 'year':
											array_push($results, array(
												'type' => 'pie',
												'source' => 'reinforcement/'.$element->scope.'/reason/'.$element->when,
												'title' => $element->title, 
												'results' => $this->Reinforcement_model->category_totals($element->when, $school_id, $teacher_id, 0, 'label', 'total')
											));
										break;
									}
								break;
								case 'teacher.name':
								case 'teacher.ethnicity':
								case 'teacher.gender':
								case 'student.name':
								case 'student.grade':
								case 'student.ethnicity':
								case 'student.gender':
									array_push($results, array(
										'type' => 'pie', 	
										'source' => 'reinforcement/'.$element->scope.'/'.$element->groupby.'/'.$element->when,
										'title' => $element->title,					
										'results' => $this->Reinforcement_model->category_totals_by($element->groupby, $element->when, $school_id, $teacher_id, 0, 'label', 'total')
									));
								break;
							}
						break;					
					}
				break;	
				case 'detention':
					$this->load->model('Detention_model');

					switch($element->type)
					{
						case 'gauge':						
							array_push($results, array(
								'type' => 'gauge',
								'source' => 'detention/'.$element->scope.'/count/'.$element->when,
								'total' => $this->Detention_model->count_today($element->when, $school_id, $teacher_id),
								'title' => $element->title,
								'scale' => $element->scale								
							));
						break;
						case 'leaderboard':
							array_push($results, array(
								'type' => 'leaderboard',
								'source' => 'detention/'.$element->scope.'/leaderboard/'.$element->when,
								'title' => $element->title,
								'results' => $this->Detention_model->top_detentions($element->when, $school_id, $teacher_id, 5)
							));
						break;
						case 'line':
							array_push($results, array(
								'type' => 'line',
								'source' => 'detention/'.$element->scope.'/interval/'.$element->when.'/'.$element->interval,
								'title' => $element->title,
								'results' => $this->_flatten($this->Detention_model->count_by_interval($element->when, $element->interval, $school_id, $teacher_id, 0), $element->interval == 'month' ? $months : array())
							));
						break;
						case 'pie':
							switch($element->groupby)
							{
								case 'reason':
									array_push($results, array(
										'type' => 'pie',
										'source' => 'detention/'.$element->scope.'/reason/'.$element->when,
										'title' => $element->title, 
										'results' => $this->Detention_model->category_totals($element->when, $school_id, $teacher_id, 0, 'label', 'total')
									));
								break;
								case 'teacher.name':
								case 'teacher.ethnicity':
								case 'teacher.gender':
								case 'student.name':
								case 'student.grade':
								case 'student.ethnicity':
								case 'student.gender':
									array_push($results, array(
										'type' => 'pie', 	
										'source' => 'detention/'.$element->scope.'/'.$element->groupby.'/'.$element->when,
										'title' => $element->title,					
										'results' => $this->Detention_model->category_totals_by($element->groupby, $element->when, $school_id, $teacher_id, 0, 'label', 'total')
									));
									
								break;
							}
						break;
					}
				break;	
				case 'form':
					$this->load->model('Report_model');

					switch($element->type)
					{
						case 'gauge':
							array_push($results, array(
								'type' => 'gauge',
								'source' => 'form/'.$element->formid.'/'.$element->scope.'/count/'.$element->when,
								'total' => $this->Report_model->count_today($element->when, 'form', $element->formid, $school_id, $teacher_id),
								'title' => $element->title,
								'scale' => $element->scale,
							));
						break;
						case 'leaderboard':
							array_push($results, array(
								'type' => 'leaderboard', 	
								'source' => 'form/'.$element->formid.'/'.$element->scope.'/leaderboard/'.$element->when,
								'title' => $element->title,					
								'results' => $this->Report_model->top_reports($element->when, $school_id, 'form', $element->formid, $teacher_id, 5)
							));
						break;
						case 'line':
							array_push($results, array(
								'type' => 'line',
								'source' => 'form/'.$element->formid.'/'.$element->scope.'/interval/'.$element->when.'/'.$element->interval,
								'title' => $element->title,
								'results' => $this->_flatten($this->Report_model->count_by_interval($element->when, $element->interval, 'form', $element->formid, $school_id, $teacher_id, 0), $element->interval == 'month' ? $months : array())
							));

						//	print_r($results);exit;
						break;						
						case 'pie':
							switch($element->groupby)
							{
								case 'reason':
									switch($element->when)
									{
										case 'today':
										case 'week':
										case 'year':
											array_push($results, array(
												'type' => 'pie',
												'source' => 'form/'.$element->formid.'/'.$element->scope.'/reason/'.$element->when,
												'title' => $element->title, 
												'results' => $this->Report_model->category_totals($element->when, 'form', $element->formid, $school_id, $teacher_id, 0, 'label', 'total')
											));
										break;
									}
								break;
								case 'teacher.name':
								case 'teacher.ethnicity':
								case 'teacher.gender':
								case 'student.name':
								case 'student.grade':
								case 'student.ethnicity':
								case 'student.gender':
									array_push($results, array(
										'type' => 'pie', 	
										'source' => 'form/'.$element->formid.'/'.$element->scope.'/'.$element->groupby.'/'.$element->when,
										'title' => $element->title,					
										'results' => $this->Report_model->category_totals_by($element->groupby, $element->when, 'form', $element->formid, $school_id, $teacher_id, 0, 'label', 'total')
									));
								break;
							}
						break;
					}
				break;												
			}
		}		

		return $results;
	}

	function _flatten($results, $labels = array())
	{
		$r = array();

		foreach($results as $row)
		{
			$row->label = isset($labels[$row->label]) ? $labels[$row->label] : $row->label;
			array_push($r, $row);
		}

		return $r;	
	}	
}
?>