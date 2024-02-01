<?php 

class Library extends CI_Controller
{
	var $vars;

	function interpret($matches)
	{
		return $this->vars[$matches[1]];
	}

	function __construct()
	{
		parent::__construct();
		$this->load->model('Library_model');
	}

	function add()
	{
		$school_id = $this->session->userdata('schoolid');
		$error = '';
		if($this->input->post('submit')) 
		{
			$elements = $this->input->post('elements');
			$forms = $this->input->post('forms');
			$charts = $this->input->post('charts');
			$link = $this->input->post('link');
			$role = $this->input->post('role');
			$titles = $this->input->post('titles');

			$items = array();

			$title = $this->input->post('title');
			$description = $this->input->post('description');

			foreach($elements as $k=>$element)
			{
				switch($element)
				{
					case 'form';
						list($type, $object_id) = preg_split('/\//', $forms[$k]);

						$this->load->model('Form_model');
						$form = $this->Form_model->get_form($object_id);

						$this->vars['form/'.$object_id] = 1;

						array_push($items, array(
							'type' => 'form',
							'var' => 'form/'.$object_id,
							'data' => array(
								'title' => $form->title,
								'viewers' => $form->viewers,
								'contributors' => $form->contributors,
								'subject' => $form->subject,
								'formdata' => json_decode($form->formdata),
								'actions' => json_decode($form->actions),
								'indextitle' => $form->indextitle,
								'timetitle' => $form->timetitle
							)
						));
					break;
					case 'charts':
						list($type, $object_id) = preg_split('/\//', $charts[$k]);

						$this->load->model('Report_model');
						$report = $this->Report_model->get_report($object_id);

						$report_data = json_decode($report->report);
						$new_report = array();

						$this->vars['charts/'.$object_id] = 1;

						foreach($report_data as $element)
						{
							if(isset($element->formid) && isset($this->vars['form/'.$element->formid]))
							{
								$element->formid = '{$form/'.$element->formid.'}';
							}
							else
							{
								$error .= 'Form '.$element->formid.' is not referenced in this package.';
								print_r($this->vars);
							}
						}

						array_push($new_report, $element);

						array_push($items, array(
							'type' => 'charts',
							'var' => '',
							'data' => array(
								'subjectid' => $report->subjectid,
								'type' => $report->type,
								'objectid' => $report->objectid,
								'report' => $new_report,
								'title' => $report->title,
								'timeincident' => $report->timeincident,
							)
						));
					break;
					case 'dashboard':
						list($type, $object_id) = preg_split('/\//', $link[$k]);

						array_push($items, array(
							'type' => 'dashboardlink',
							'href' => $link[$k],
							'role' => $role[$k],
							'title' => $titles[$k]
						));
					break;
				}
			}

			print_r($items);exit;
			$this->Library_model->create('', 0, $title, $description, array('items' => $items));
			redirect('library');
		}
		else
		{
			$this->load->model('Form_model');
			$this->load->model('Report_model');
			$forms = $this->Form_model->get_by_school($school_id);
			$reports = $this->Report_model->get_reports($school_id, 'charts', '111');

			$this->layout->view('library/add', array(
				'forms' => $forms,
				'reports' => $reports
			));
		}
	}

	function index()
	{
		$packages = array(0);
		$resources = $this->Library_model->get_resources($packages);

		$this->layout->view('library/index', array('resources' => $resources, 'title_for_layout' => 'Library'));
	}

	function view($resource_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$resource = $this->Library_model->get_resource($resource_id);
		$is_installed = $this->Library_model->is_installed($resource_id, $school_id, $user_id);
		
		if(empty($resource))
		{
			$this->layout->view('library/error_notfound');
			return;
		}

		$this->layout->view('library/view', array(
			'isinstalled' => $is_installed,
			'resource' => $resource, 
			'title_for_layout' => $resource->title
		));
	}

	function install($resource_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$resource = $this->Library_model->get_resource($resource_id);

		if(empty($resource))
		{
			$this->layout->view('library/error_notfound');
			return;
		}
		
		$resource->metadata = json_decode($resource->metadata);

		$is_installed = $this->Library_model->is_installed($resource_id, $school_id, $user_id);

		if($is_installed)
		{
			$this->layout->view('library/error_alreadyinstalled');
			return;
		}

		$install_data = array();

		if($this->input->post('submit'))
		{
			$this->vars = array();

			foreach($resource->metadata->items as $item)
			{
				switch($item->type)
				{
					case 'form':
						$this->load->model('Form_model');
						$form_id = $this->Form_model->create($school_id, $item->data->title, $item->data->viewers, $item->data->contributors, $item->data->subject, $item->data->formdata, $item->data->actions, $item->data->indextitle, $item->data->timetitle);
						
						if(isset($item->var))
						{
							$this->vars[$item->var] = $form_id;
						}

						array_push($install_data, array('type' => 'form', 'id' => $form_id));
					break;
					case 'charts':
						$this->load->model('Report_model');

						// If this is a variable, substitute the value with a new value.
						if(substr($item->data->objectid, 0, 1) == '$')
						{
							$item->data->objectid = (!isset($vars[substr($item->data->objectid, 1)])) ? '' : $this->vars[substr($item->data->objectid, 1)]; 
						}

						$report_data = array();

						foreach($item->data->report as $element)
						{
							if(isset($element->formid))
							{
								$element->formid = preg_replace_callback('/{\$(.*?)}/', array($this, 'interpret'), $element->formid);
							}

							array_push($report_data, $element);
						}

						$report_id = $this->Report_model->create($school_id, 0, $item->data->subjectid, $item->data->type, $item->data->objectid, $report_data, $item->data->title, $item->data->timeincident);

						if(isset($item->var))
						{
							$this->vars[$item->var] = $report_id;
						}

						array_push($install_data, array('type' => 'charts', 'id' => $report_id));
					break;
					case 'dashboardlink':
						$this->load->model('Settings_model'); 
						$menu = json_decode($this->Settings_model->get_settings($school_id, 'dash'.$item->data->role)); 

						$href = preg_replace_callback('/{\$(.*?)}/', array($this, 'interpret'), $item->data->href);
						$menu->menu->$href = preg_replace_callback('/{\$(.*?)}/', array($this, 'interpret'), $item->data->title);

						$this->Settings_model->save($school_id, 'dash'.$item->data->role, $menu);
						array_push($install_data, array('type' => 'dashboard', 'href' => $href));
					break;
				}
			}

			print_r($install_data);exit;
			$this->Library_model->install($resource_id, $school_id, 0, $install_data);
			redirect('library');
		}
		else
		{
			$this->layout->view('library/install', array('resource' => $resource));
		}
	}

	function uninstall($resource_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$install = $this->Library_model->get_install($resource_id, $school_id, 0);
		$resource = $this->Library_model->get_resource($resource_id);
		$install->metadata = json_decode($install->metadata);

		$paths = array();

		if(empty($install) || $install->timeuninstalled != 0)
		{
			$this->layout->view('library/error_notinstalled', array('title_for_layout' => 'Library'));
			return;
		}

		if($this->input->post('submit'))
		{
			foreach($install->metadata as $item)
			{
				switch($item->type)
				{
					case 'form':
						$this->load->model('Form_model');
						$this->load->model('Report_model');

						$this->Form_model->remove($item->id);
						$this->Report_model->remove_form($item->id);

						array_push($paths, '^\/form\/respond\/'.$item->id.'\/*');
						array_push($paths, '^\/report\/form\/'.$item->id.'\/*');
					break;
					case 'charts':
						$this->load->model('Report_model');

						$this->Report_model->remove($item->id);
						array_push($paths, '^\/report\/view\/'.$item->id.'\/*');
					break;
					case 'dashboard':
						array_push($paths, preg_replace('/\//', '\\\/', $item->href));
					break;
				}
			}

			$this->load->model('Settings_model');

			$paths = implode('|', $paths);

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
					
					$this->Settings_model->save($school_id, 'dash'.$role, $settings);
				}
			}

			$this->Library_model->uninstall($resource_id, $school_id, 0);

			redirect('library');
		}
		else
		{
			$resources = array();

			foreach($install->metadata as $item)
			{
				switch($item->type)
				{
					case 'form':
						$this->load->model('Form_model');
						$form = $this->Form_model->get_form($item->id);
						array_push($resources, array('href' => '/form/view/'.$item->id, 'title' => $form->title));

						$this->load->model('Report_model');
						$count = $this->Report_model->count_by_interval('', '', 'form', $item->id, $school_id, 0, 0)[0]->total;
						
						if($count > 0)
						{
							array_push($resources, array('href' => '/report/form/'.$item->id, 'title' => $count.' responses to '.$form->title));
						}
					break;
					case 'charts':
						$this->load->model('Report_model');
						$report = $this->Report_model->get_report($item->id);
						array_push($resources, array('href' => '/report/view/'.$item->id, 'title' => $report->title));
					break;
				}
			}

			$this->layout->view('library/uninstall', array(
				'resource' => $resource, 
				'resources' => $resources,
				'install' => $install
			));
		}
	}
}
?>