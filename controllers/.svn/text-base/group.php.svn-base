<?php
class Group extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function view($group_id, $page = 0)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('User_model');

		$page_size = 10;
		if($group_id == 'unassigned')
		{	
			$users = $this->User_model->get_unassigned($school_id, 's', $page*$page_size, $page_size+1);
			$teachers = $this->User_model->get_unassigned($school_id, 't', $page*$page_size, $page_size+1);
			$group = new stdClass();
			$group->title = 'Unassigned';
			$group->groupid = 'unassigned';
		}
		else
		{
			$this->load->model('Group_model');
			$group = $this->Group_model->get_group($group_id);

			if(empty($group))
			{
				$this->layout->view('group/error_groupnotfound');
				return;
			}

			$permission_denied = true;
			switch($this->session->userdata('role'))
			{
				case 't':
					if($this->Group_model->has_teacher($group_id, $user_id))
					{
						$permission_denied = false;
					}
				break;
				case 'a':
					if($group->schoolid == $school_id)
					{
						$permission_denied = false;
					}
				break;
			}

			if($permission_denied)
			{
				$this->layout->view('group/error_permissiondenied');
				return;
			}

			$users = $this->User_model->get_from_group($group_id);
			$teachers = $this->User_model->get_teachers_from_group($group_id);
		}

		$this->layout->view('group/view', array(
			'title_for_layout' => $group->title,
			'group' => $group,
			'page' => $page,
			'pagesize' => $page_size,
			'students' => $users,
			'teachers' => $teachers
		));
	}

	function edit($group_id)
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$this->load->model('Group_model');
		$group = $this->Group_model->get_group($group_id);

		if(empty($group))
		{
			$this->layout->view('group/error_groupnotfound');
			return;
		}

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 'a':
				if($group->schoolid == $school_id)
				{
					$permission_denied = false;
				}
			break;
		}

		if($permission_denied)
		{
			$this->layout->view('group/error_permissiondenied');
			return;
		}

		$error = '';
		if($this->input->post('submit'))
		{
			$title = $this->input->post('title');

			if(empty($title))
			{
				$error = 'emptytitle';
			}
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$title = $this->input->post('title');

			$this->Group_model->update($group_id, $title);

			redirect('group/view/'.$group_id);
		}
		else
		{
			$data = array(
				'title_for_layout' => 'Edit Group',
				'group' => $group
			);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['title'] = $title;
			}

			$this->layout->view('group/edit', $data);
		}
	}

	function add()
	{
		$school_id = $this->session->userdata('schoolid');
		$user_id = $this->session->userdata('userid');

		$permission_denied = true;
		switch($this->session->userdata('role'))
		{
			case 'a':
				$permission_denied = false;
			break;
		}

		if($permission_denied)
		{
			$this->layout->view('group/error_permissiondenied');
			return;
		}

		$error = '';
		if($this->input->post('submit'))
		{
			$title = $this->input->post('title');
			$teachers = $this->input->post('teachers');

			if(empty($title))
			{
				$error = 'emptytitle';
			}
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$title = $this->input->post('title');

			$meta_data = array();

			$this->load->model('Group_model');
			$group_id = $this->Group_model->create($school_id, $title, $meta_data);

			if(!empty($teachers)) 
			{
				foreach($teachers as $k=>$v)
				{
					$this->Group_model->add_teacher($group_id, $k);
				}
			}
			
			redirect('group/view/'.$group_id);
		}
		else
		{
			$data = array(
				'title_for_layout' => 'Add Group'
			);

			$this->load->model('User_model');
			$data['teachers'] = $this->User_model->get_teachers_from_school($school_id);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['title'] = $title;
			}

			$this->layout->view('group/add', $data);
		}
	}
}
?>