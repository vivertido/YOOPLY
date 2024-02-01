<?php
class Clever extends CI_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->library('cleverapi');

		parse_str($_SERVER['QUERY_STRING'], $_GET);
	}

	function explore()
	{
		if($this->input->post('submit'))
		{
			$oauth_token = $this->input->post('token');
			$this->session->set_userdata('clevertoken', $oauth_token);

			$this->cleverapi->set_token($oauth_token);
			$districts = $this->cleverapi->get_districts();

			$this->layout->view('partner/clever/districts', array('districts' => $districts->data));
		}
		else
		{
			$this->layout->view('partner/clever/explore');
		}
	}

	function district($district_id)
	{
		$oauth_token = $this->session->userdata('clevertoken');
		
		$this->cleverapi->set_token($oauth_token);
		$schools = $this->cleverapi->get_schools($district_id);

		$this->layout->view('partner/clever/district', array('schools' => $schools->data));
	}

	function school($school_id)
	{
		/*
DELETE FROM Profiles WHERE source = 'clever';
DELETE FROM Groups WHERE metadata LIKE "%clevergroupid%";
DELETE FROM Schools WHERE metadata LIKE "%clever%";
		*/

		$this->load->model('School_model');
		$school = $this->School_model->find_by_clever($school_id);

		$oauth_token = $this->session->userdata('clevertoken');

		$this->cleverapi->set_token($oauth_token);
		$school = $this->cleverapi->get_school($school_id);
		$data = array('school' => $school->data);

		$data['teachercount'] = $this->cleverapi->get_teacher_count($school_id);
		$data['studentcount'] = $this->cleverapi->get_student_count($school_id);
		$data['sections'] = $this->cleverapi->get_sections($school_id)->data;
		$data['admincount'] = $this->cleverapi->get_admin_count($school_id);
		
		$data['existingschool'] = $school;

		$this->layout->view('partner/clever/school', $data);
	}

	function import($clever_school_id)
	{
		$oauth_token = $this->session->userdata('clevertoken');
		$this->cleverapi->set_token($oauth_token);

		$this->load->model('School_model');
		$school = $this->School_model->find_by_clever($clever_school_id);

		$stats = array(
			'newschool' => 0,
			'existingschool' => 0,
			'existingstudents' => 0,
			'newstudents' => 0,
			'existingteachers' => 0,
			'newteachers' => 0,
			'existingadmins' => 0,
			'newadmins' => 0,
			'existinggroups' => 0,
			'newgroups' => 0,
		);

		if(empty($school))
		{
			$oauth_token = $this->session->userdata('clevertoken');

			$this->cleverapi->set_token($oauth_token);
			$school = $this->cleverapi->get_school($clever_school_id)->data;

			$meta_data = array(
				'cleverdistrict' => $school->district,
				'cleverschool' => $school->id,
			);

			$school_id = $this->School_model->create($school->name, $meta_data, '');			

			$stats['newschool']++;

			$this->load->model('Settings_model');
			$this->Settings_model->insert_default($school_id);			
		}
		else
		{
			$school_id = $school->schoolid;
			$stats['existingschool']++;
		}
		
		$stats['schoolid'] = $school_id;

		// Map of clever ids to yooply ids.
		$students_clever_yooply = $teachers_clever_yooply = array();

		$this->load->model('Profile_model');
		$this->load->model('User_model');

		$admins = $this->cleverapi->get_admins($clever_school_id);

		foreach($admins as $admin)
		{
			$clever_user_id = $admin->data->id;
			
			$profile = $this->Profile_model->find_clever_user($clever_user_id);

			if(empty($profile))
			{
				$email = isset($admin->data->email) ? $admin->data->email : '';
				$admin_id = $this->User_model->create_admin($school_id, $admin->data->name->first, $admin->data->name->last, '', '', $email, 'blobsmall.png');
				$this->Profile_model->create_clever_user($admin_id, $admin->data->id, $admin->data);
				$stats['newadmins']++;
			}
			else
			{
				$admin_id = $profile->userid;
				$stats['existingadmins']++;
			}
		}

		$students = $this->cleverapi->get_students($clever_school_id);
		$next_page = '1';

		while(!empty($next_page))
		{
			$next_page = '';

			foreach($students->links as $link)
			{
				if($link->rel == 'next')
				{
					$next_page = $link->uri;
					break;
				}
			}

			foreach($students->data as $student)
			{
				$clever_user_id = $student->data->id;
				
				$profile = $this->Profile_model->find_clever_user($clever_user_id);

				if(empty($profile))
				{
					$dob = strtotime($student->data->dob) == 0 ? '0000-00-00' : date('Y-m-d', strtotime($student->data->dob));
					$email = isset($student->data->email) ? $student->data->email : '';
					$student_id = $this->User_model->create_student($school_id, $student->data->name->first, $student->data->name->last, '', '', $email, 'blobsmall.png', $student->data->grade, '', $student->data->gender, $dob, '');
					$this->Profile_model->create_clever_user($student_id, $student->data->id, $student->data);
					$stats['newstudents']++;
				}
				else
				{
					$student_id = $profile->userid;
					$stats['existingstudents']++;
				}

				$students_clever_yooply[$student->data->id] = $student_id;
			}

			if(!empty($next_page))
			{
				$students = $this->cleverapi->get_next_page($next_page);
			}
			else
			{
				$students = new stdClass();
				$students->data = array();
			}
		}

		$teachers = $this->cleverapi->get_teachers($clever_school_id);
		$next_page = '1';

		while(!empty($next_page))
		{
			$next_page = '';

			foreach($teachers->links as $link)
			{
				if($link->rel == 'next')
				{
					$next_page = $link->uri;
					break;
				}
			}

			foreach($teachers->data as $teacher)
			{
				$clever_user_id = $teacher->data->id;
				
				$profile = $this->Profile_model->find_clever_user($clever_user_id);

				if(empty($profile))
				{
					$email = isset($teacher->data->email) ? $teacher->data->email : '';
					$teacher_id = $this->User_model->create_teacher($school_id, $teacher->data->name->first, $teacher->data->name->last, '', '', $email, 'blobsmall.png');
					$this->Profile_model->create_clever_user($teacher_id, $teacher->data->id, $teacher->data);
					$stats['newteachers']++;
				}
				else
				{
					$teacher_id = $profile->userid;
					$stats['existingteachers']++;
				}

				$teachers_clever_yooply[$teacher->data->id] = $teacher_id;
			}

			if(!empty($next_page))
			{
				$teachers = $this->cleverapi->get_next_page($next_page);
			}
			else
			{
				$teachers = new stdClass();
				$teachers->data = array();
			}
		}

		$sections = $this->cleverapi->get_sections($clever_school_id)->data;

		$this->load->model('Group_model');
		foreach($sections as $section)
		{
			$clever_group_id = $section->data->id;

			$group = $this->Group_model->get_clever_group($clever_group_id);

			if(empty($group))
			{
				$group_meta_data = array('clevergroupid' => $clever_group_id);

				$group_id = $this->Group_model->create($school_id, $section->data->name, $group_meta_data);
				$stats['newgroups']++;
			}
			else
			{
				$group_id = $group->groupid;
				$stats['existinggroups']++;
			}

			echo $group_id." (".count($section->data->students).") students\n";

			foreach($section->data->students as $clever_student_id)
			{
				$student_id = $students_clever_yooply[$clever_student_id];
				if(!$this->Group_model->has_user($group_id, $student_id))
				{
					$this->Group_model->add_student($group_id, $student_id);
				}
			}

			foreach($section->data->teachers as $clever_teacher_id)
			{
				$teacher_id = $teachers_clever_yooply[$clever_teacher_id];
				if(!$this->Group_model->has_teacher($group_id, $teacher_id))
				{
					$this->Group_model->add_teacher($group_id, $teacher_id);
				}
			}			
		}

		$this->layout->view('partner/clever/import', array('summary' => $stats));
	}

	function oldimport($clever_school_id = 0)
	{
		if($clever_school_id == 0)
		{
			$schools = $this->cleverapi->get_schools();
			$this->layout->view('partner/clever/import', array('schools' => $schools));
		}
		else
		{
			if($this->input->post('submit'))
			{
				$this->load->model('School_model');
				$this->load->model('User_model');
				$this->load->model('Group_model');
				$this->load->model('Profile_model');
				$this->load->model('Settings_model');

				$school = $this->cleverapi->get_school($clever_school_id)->data;

				$meta_data = array(
					'cleverdistrict' => $school->district,
					'cleverschool' => $school->id,
				);

				$school_id = $this->School_model->create($school->name, $meta_data);

				$this->Settings_model->insert_default($school_id);

				$this->session->set_userdata('schoolid', $school_id);

				$user_id = $this->session->userdata('userid');
				$this->School_model->add_admin($school_id, $user_id);

				$teachers = $this->cleverapi->get_teachers($clever_school_id);

				foreach($teachers->data as $teacher)
				{
					$profile = $this->Profile_model->find_clever_user($teacher->data->id);

					if(empty($profile))
					{
						$teacher_id = $this->User_model->create_teacher($school_id, $teacher->data->name->first, $teacher->data->name->last, '', '', $teacher->data->email, 'blobsmall.png');
						$this->Profile_model->create_clever_user($teacher_id, $teacher->data->id, $teacher->data, 'blobsmall.png');

						echo 'Created teacher: '.$teacher->data->name->first.', '.$teacher->data->name->last.'<br />';
					}
					else
					{
						$teacher_id = $profile->userid;
					}
				}

				$page = 1;
				$total = 1;
				do
				{
					$students = $this->cleverapi->get_students($clever_school_id, $page);
					$total = $students->paging->total;

					foreach($students->data as $student)
					{
						$profile = $this->Profile_model->find_clever_user($student->data->id);

						if(empty($profile))
						{
							$dob_time = strtotime($student->data->dob);
							if($dob_time == 0)
							{
								$dob = '0000-00-00';
							}
							else
							{
								$dob = date('Y-m-d', $dob_time);
							}

							$student_id = $this->User_model->create_student($school_id, $student->data->name->first, $student->data->name->last, '', '', $student->data->email, 'blobsmall.png', $student->data->grade, $student->data->sis_id, $student->data->gender, $dob);
							$this->Profile_model->create_clever_user($student_id, $student->data->id, $student->data, 'blobsmall.png');

              echo 'Created student: '.$student->data->name->first.', '.$student->data->name->last.'<br />';
						}
						else
						{
							$student_id = $profile->userid;
						}
					}

					$page++;
				} while($page <= $total && $total != 1);

				$page = 1;
				$total = 1;
				do
				{
					$sections = $this->cleverapi->get_sections($clever_school_id);
					$total = $sections->paging->total;

					foreach($sections->data as $section)
					{
						$clever_group_id = $section->data->id;

						$group = $this->Group_model->get_clever_group($clever_group_id);

						if(empty($group))
						{
							$meta_data = array(
								'clever_section' => $section->data->id
							);

							$group_id = $this->Group_model->create($school_id, $section->data->name, $meta_data);

							echo 'Created class: '.$section->data->name.'<br />';
						}
						else
						{
							$group_id = $group->groupid;
						}

						$profile = $this->Profile_model->find_clever_user($section->data->teacher);

						if(!empty($profile))
						{
							$teacher_id = $profile->userid;
							if(!$this->Group_model->has_user($group_id, $teacher_id))
							{
								$this->Group_model->add_teacher($group_id, $teacher_id);
								echo 'Added teacher '.$teacher_id.' to group '.$group_id.'<br />';
							}
						}

						foreach($section->data->students as $clever_student_id)
						{
							$profile = $this->Profile_model->find_clever_user($clever_student_id);

							if(!empty($profile))
							{
								$student_id = $profile->userid;
								if(!$this->Group_model->has_user($group_id, $student_id))
								{
									$this->Group_model->add_student($group_id, $student_id);
									echo 'Added student '.$student_id.' to group '.$group_id.'<br />';
								}
							}
						}
					}

					$page++;
				} while($page <= $total && $total != 1);
			}
			else
			{
				$data = array();

				$data['school'] = $this->cleverapi->get_school($clever_school_id)->data;
				$data['studentcount'] = $this->cleverapi->get_student_count($clever_school_id);
				$data['teachercount'] = $this->cleverapi->get_teacher_count($clever_school_id);
				$data['sectioncount'] = $this->cleverapi->get_section_count($clever_school_id);

				$teachers = $this->cleverapi->get_teachers($clever_school_id);
				$data['teachers'] = $teachers->data;

				$teachers = $this->cleverapi->get_students($clever_school_id);
				$data['students'] = $teachers->data;

				$teachers = $this->cleverapi->get_sections($clever_school_id);
				$data['sections'] = $teachers->data;

				$this->layout->view('partner/clever/import_school_summary', $data);
			}
		}
	}

	function index($redirect = '')
	{
		$security_token = md5(time().rand().$redirect);
		
		$this->session->set_userdata(array(
			'cleververifytoken', $security_token,
			'cleververifyredirect' => $redirect
		));

		$params = array(
			'response_type' => 'code',
			'client_id' => '149b8d1595c5bc873833',
 			'redirect_uri' => base_url().'partner/clever/auth',
 			'state' => $security_token
		);

		header("Location: https://clever.com/oauth/authorize?".http_build_query($params));
	}

	function auth()
	{
		$code = $_GET['code'];
		//$state = $_GET['state'];

		//$verify_state = $this->session->userdata('cleververifytoken');

		//if(empty($state) || $verify_state != $state)
		//{
			//$this->layout->view('partner/google/error_usernotfound');
			//return;		
		//}

		$params = array(
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => base_url().'partner/clever/auth',
		);

		$ch = curl_init('https://clever.com/oauth/tokens');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/x-www-form-urlencoded', 
			'Authorization: Basic '.base64_encode('149b8d1595c5bc873833:a5910e1146cb81fabb4a21767fb1d1823c17f10f'
		)));
		$response = curl_exec($ch);
		$data = json_decode($response);

		if(!isset($data->access_token))
		{
			//$this->layout->view('partner/google/error_usernotfound');
			//return;			
		}

		$ch = curl_init('https://api.clever.com/me');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Bearer '.$data->access_token));
		$response = json_decode(curl_exec($ch));

		$this->load->model('User_model');
		$user = $this->User_model->find_by_email($response->data->email);

		if(empty($user))
		{
			$this->layout->view('partner/google/error_usernotfound');
			return;		
		}

		$school_id = $user->schoolid;

		$this->session->set_userdata(array(
			'userid' => $user->userid, 
			'role' => $user->accounttype,
			'schoolid' => $school_id
		));

		switch($user->accounttype)
		{
			case 'a':
				$this->load->model('School_model');
				if($this->School_model->has_teacher($school_id, $user->userid))
				{
					$this->session->set_userdata('isteacher', true);
				}
			break;
			case 't':
				$this->load->model('School_model');
				if($this->School_model->has_admin($school_id, $user->userid))
				{
					$this->session->set_userdata('isadmin', true);
				}
			break;
		}

		switch($user->accounttype)
		{
			case 'a':
				redirect('admin');
			break;
			case 's':
				redirect('student');
			break;
			case 't':
				redirect('teacher');
			break;
			case 'p':
				redirect('parent');
			break;

		}
	}
}
?>