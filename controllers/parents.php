<?php

class Parents extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$parent_id = $this->session->userdata('userid');

		$this->load->model('User_model');
		$data = array();
		$data['children'] = $this->User_model->get_children($parent_id);
		
		$this->layout->view('parent/index', $data);
	}

	function yo()
	{
		echo 'yo';
	}
}