<?php
class Explorer extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		if(!$this->session->userdata('admin'))
		{
			redirect('a/login');
		}
	}

	function settings($setting_id = 0)
	{
		if($setting_id == 0)
		{
			$query = $this->db->query('SELECT * FROM Settings');
			$settings = $query->result();

			$this->layout->view('a/explorer/settings_list', array('settings' => $settings));
		}
		else
		{
			$query = $this->db->query('SELECT * FROM Settings WHERE settingsid = ?', array($setting_id));
			$setting = $query->row();

			$this->layout->view('a/explorer/settings_view', array('setting' => $setting));
		}
	}

	function schools()
	{
		$query = $this->db->query('SELECT * FROM Schools');
		$schools = $query->result();

		$this->layout->view('a/explorer/schools', array('schools' => $schools));
	}

	function school($school_id)
	{
		$this->load->model('School_model');
		$this->load->model('User_model');

		$school = $this->School_model->get_school($school_id);

		$query = $this->db->query('SELECT * FROM Groups WHERE schoolid = ?', array($school_id));
		$groups = $query->result();

		$query = $this->db->query('SELECT * FROM Users, UserSchool WHERE Users.userid = UserSchool.userid AND UserSchool.schoolid = ? ORDER BY lastname', array($school_id));
		$teacher_admins = $query->result();

		$query = $this->db->query('SELECT * FROM Users WHERE Users.schoolid = ? AND Users.accounttype = "t" ORDER BY lastname', array($school_id));
		$teachers = $query->result();

		$admins = $this->User_model->get_admins_from_school($school_id);

		$this->layout->view('a/explorer/school', array(
			'school' => $school,
			'groups' => $groups,
			'admins' => $admins,
			'teacheradmins' => $teacher_admins,
			'teachers' => $teachers
		));
	}

	function group($group_id)
	{
		$this->load->model('Group_model');
		$group = $this->Group_model->get_group($group_id);

		$this->load->model('User_model');
		$students = $this->User_model->get_students_from_group($group_id);
		$teachers = $this->User_model->get_teachers_from_group($group_id);

		$this->load->model('School_model');
		$school = $this->School_model->get_school($group->schoolid);

		$this->layout->view('a/explorer/group', array(
			'group' => $group,
			'students' => $students,
			'teachers' => $teachers,
			'school' => $school
		));
	}

	function user($user_id)
	{
		$this->load->model('User_model');
		$this->load->model('Group_model');

		$user = $this->User_model->get_user($user_id);
		$groups = $this->Group_model->get_user_groups($user_id);

		$this->layout->view('a/explorer/user', array(
			'user' => $user,
			'groups' => $groups,
		));
	}
}
?>