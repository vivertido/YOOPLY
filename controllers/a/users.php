<?php

class Users extends CI_Controller
{

	function adduser()
	{

		if($this->input->post('submit'))
		{
			$account_type = $this->input->post('accounttype');
			$first_name = $this->input->post('firstname');
			$last_name = $this->input->post('lastname');
			$email = $this->input->post('email');
			$school_id = $this->input->post('schoolid');

			$profile_image = 'blobsmall.png';
			$this->load->model('User_model');

			switch($account_type)
			{
				case 't':
					$user_id = $this->User_model->create_teacher($school_id, $first_name, $last_name, '', '', $email, $profile_image);
				break;
				case 'a':
					$user_id = $this->User_model->create_admin($school_id, $first_name, $last_name, '', '', $email, $profile_image);
				break;
				case 's':
					$grade = $this->input->post('grade');
					$student_id = '';
					$gender = '';
					$dob = '';
					$username='';
					$password='';

					$user_id = $this->User_model->create_student($school_id, $first_name, $last_name, $username, $password, $email, $profile_image, $grade, $student_id, $gender, $dob);
				break;
			}

			echo 'User id '.$user_id.' created';
		}
		else
		{
			$query = $this->db->query('SELECT * FROM Schools');
			$schools = $query->result();

			$this->layout->view('a/users/adduser', array(
				'schools' => $schools
			));
		}
	}
}

?>
