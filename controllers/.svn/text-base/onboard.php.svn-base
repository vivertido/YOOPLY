<?php

class Onboard extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function add()
	{
		$words = array('diary','bottle','water','packet','tissue','glasses','watch','sweet','photo','camera','stamp','postcard','dictionary','coin','brush','wallet','button','umbrella','pencil','match','purse','case','clip','scissors','rubber','banknote','passport','comb','notebook','laptop','mirror','sunscreen','toothbrush','headphone','player','battery','newspaper','magazine');

		shuffle($words);
		$this->load->model('Onboard_model');
		$password = implode('.', array($words[0].rand(10,99).$words[1].rand(50,99).$words[2]));

		$invite_token = $this->Onboard_model->create($password);
		echo '/onboard/begin/'.$invite_token;
		echo ' Password: '.$password;
	}

	function begin($invite_code)
	{
		$this->load->model('Onboard_model');
		$onboard = $this->Onboard_model->get_invite($invite_code);

		if(empty($onboard) || $onboard->status != 1)
		{
			$this->layout->view('onboard/error_notfound');
			return;
		}

		$error = '';
		if($this->input->post('submit'))
		{
			$verification_code = $this->input->post('verificationcode');
			if(md5($verification_code.$onboard->nonce) != $onboard->verificationcode)
			{
				$error = 'invalidcode';
			}
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$this->session->set_userdata('invitecode', $invite_code);
			redirect('onboard/info/'.$invite_code);
		}
		else
		{
			$data = array('invitecode' => $onboard->invitecode);

			if(!empty($error))
			{
				$data['error'] = $error;
			}

			$this->layout->view('onboard/begin', $data);	
		}
	}

	function info($invite_code)
	{
		if(!$this->session->userdata('invitecode') || $this->session->userdata('invitecode') != $invite_code)
		{
			redirect('onboard/begin/'.$invite_code);
		}

		$this->load->model('Onboard_model');
		$onboard = $this->Onboard_model->get_invite($invite_code);

		if(empty($onboard) || $onboard->status != 1)
		{
			$this->layout->view('onboard/error_notfound');
			return;
		}

		$error = '';
		if($this->input->post('submit'))
		{
			$school_name = $this->input->post('schoolname');
			$school_contact = $this->input->post('schoolcontact');
			$school_email = $this->input->post('schoolemail');

			if(empty($school_name))
			{
				$error = 'schoolname';
			}

			if(empty($error) && empty($school_contact))
			{
				$error = 'schoolcontact';
			}

			if(empty($error) && empty($school_email))
			{
				$error = 'schoolemail';
			}			
		}

		if(empty($error) && $this->input->post('submit'))
		{
			$info = array('name' => $school_name, 'contact' => $school_contact, 'email' => $school_email);
			$this->Onboard_model->save_info($invite_code, $info);

			redirect('onboard/tos/'.$invite_code);
		}
		else
		{
			$data = array('invitecode' => $onboard->invitecode);

			if(!empty($error))
			{
				$data['error'] = $error;
				$data['schoolname'] = $school_name;
				$data['schoolcontact'] = $school_contact;
				$data['schoolemail'] = $school_email;
			}

			$this->layout->view('onboard/info', $data);	
		}		
	}

	function tos($invite_code)
	{
		if(!$this->session->userdata('invitecode') || $this->session->userdata('invitecode') != $invite_code)
		{
			redirect('onboard/begin/'.$invite_code);
		}

		$this->load->model('Onboard_model');
		$onboard = $this->Onboard_model->get_invite($invite_code);

		if(empty($onboard) || $onboard->status != 1)
		{
			$this->layout->view('onboard/error_notfound');
			return;
		}

		if($this->input->post('submit'))
		{
			redirect('onboard/upload/'.$invite_code);
		}
		else
		{
			$data = array('invitecode' => $onboard->invitecode);

			$this->layout->view('onboard/tos', $data);	
		}		
	}

	function upload($invite_code)
	{
		if(!$this->session->userdata('invitecode') || $this->session->userdata('invitecode') != $invite_code)
		{
			redirect('onboard/begin/'.$invite_code);
		}

		$this->load->model('Onboard_model');
		$onboard = $this->Onboard_model->get_invite($invite_code);

		if(empty($onboard) || $onboard->status != 1)
		{
			$this->layout->view('onboard/error_notfound');
			return;
		}

		$errors = array();
		$keys = array();

		if($this->input->post('submit')) 
		{
			if(!is_dir('../uploads/'.$invite_code))
			{
				mkdir('../uploads/'.$invite_code);
			}

			$config['upload_path'] = '../uploads/'.$invite_code;
			$config['allowed_types'] = '*';
			$config['overwrite'] = TRUE;

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('students'))
			{
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
			}
			else
			{
				$file_info = $this->upload->data();
				$student_filename = $file_info['full_path'];


				$results = $this->_parsestudents($student_filename);

				if(isset($results['errors']))
				{
					$errors = $results['errors'];
				}
				else
				{
					$keys = $results['keys'];
					$students = $results['students'];
				}
			}

			if ( ! $this->upload->do_upload('parents'))
			{
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
			}
			else
			{
				$file_info = $this->upload->data();
				$parent_filename = $file_info['full_path'];


				$results = $this->_parseparents($parent_filename, $keys);

				if(isset($results['errors']))
				{
					$errors = $results['errors'];
				}
				else
				{
					$parents = $results['parents'];
				}
			}
			
			$teacher_keys = array();
			if ( ! $this->upload->do_upload('teachers'))
			{
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
			}
			else
			{
				$file_info = $this->upload->data();
				$teacher_filename = $file_info['full_path'];


				$results = $this->_parsestaff($teacher_filename, 'teacher');	

				if(isset($results['errors']))
				{
					$errors = $errors+$results['errors'];
				}
				else
				{
					$teachers = $results['staff'];

					$duplicates = array_intersect_key($keys, $results['keys']);

					if(!empty($duplicates))
					{
						foreach($duplicates as $key=>$value)
						{
							array_push($errors, 'Duplicate student/teacher entry with value '.$key);
						}
					}
					else
					{
						$teacher_keys = $results['keys'];
					}
				}
			}

			$admin_keys = array();
			if ( ! $this->upload->do_upload('admins'))
			{
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
			}
			else
			{
				$file_info = $this->upload->data();
				$admin_filename = $file_info['full_path'];


				$results = $this->_parsestaff($admin_filename, 'admin');	

				if(isset($results['errors']))
				{
					$errors = $errors+$results['errors'];
				}
				else
				{
					$admins = $results['staff'];

					$duplicates = array_intersect_key($keys, $results['keys']);

					if(!empty($duplicates))
					{
						foreach($duplicates as $key=>$value)
						{
							array_push($errors, 'Duplicate student/admin entry with value '.$key);
						}
					}
					else
					{
						$admin_keys = $results['keys'];
					}
				}
			}

			$keys = $keys+$teacher_keys+$admin_keys;

			if(empty($errors))
			{
				$config['file_name'] = $invite_code.'groups.csv';

				$this->load->library('upload', $config);

				if(!$this->upload->do_upload('groups'))
				{
					$error = array('error' => $this->upload->display_errors());
					print_r($error);
				}
				else
				{
					$file_info = $this->upload->data();
					$group_filename = $file_info['full_path'];

					$results = $this->_parseclasses($group_filename, $keys);
					$groups = $results['groups'];

					if(isset($results['errors']))
					{
						$errors = $results['errors'];
					}
				}
			}			
		}

		if(empty($errors) && $this->input->post('submit'))
		{
			$stats = array(
				'groups' => count($groups),
				'groupsfile' => $group_filename,
				'students' => count($students),
				'studentsfile' => $student_filename,
				'teachers' => count($teachers),
				'teachersfile' => $teacher_filename,
				'admins' => count($admins),
				'adminsfile' => $admin_filename,
				'parents' => count($parents),
				'parentsfile' => $parent_filename
			);

			$this->Onboard_model->save_stats($invite_code, $stats);

			redirect('onboard/options/'.$invite_code);
		}
		else
		{
			$data = array('invitecode' => $onboard->invitecode);

			if(!empty($errors))
			{
				$data['errors'] = $errors;
			}

			$this->layout->view('onboard/upload', $data);	
		}
	}

	function options($invite_code)
	{
		if(!$this->session->userdata('invitecode') || $this->session->userdata('invitecode') != $invite_code)
		{
			redirect('onboard/begin/'.$invite_code);
		}

		$this->load->model('Onboard_model');
		$onboard = $this->Onboard_model->get_invite($invite_code);

		if(empty($onboard) || $onboard->status != 1)
		{
			$this->layout->view('onboard/error_notfound');
			return;
		}

		if(empty($errors) && $this->input->post('submit'))
		{
			$features = $this->input->post('feature');

			$this->Onboard_model->save_features($invite_code, $features);
			
			redirect('onboard/finished');
		}
		else
		{
			$data = array('invitecode' => $onboard->invitecode, 'stats' => json_decode($onboard->stats));

			if(!empty($errors))
			{
				$data['errors'] = $errors;
			}

			$this->layout->view('onboard/options', $data);	
		}
	}

	function finished()
	{
		$this->layout->view('onboard/finished');
	}


	function process($invite_code)
	{
		$this->load->model('School_model');

		$this->load->model('Onboard_model');
		$onboard = $this->Onboard_model->get_invite($invite_code);

		if(empty($onboard) || $onboard->status != 1)
		{
			$this->layout->view('onboard/error_notfound');
			return;
		}		

		if($this->input->post('submit'))
		{

			$login_method = $this->input->post('loginmethod');
			$domain = $this->input->post('domain');

			$info = json_decode($onboard->info);
			


			$school_id = $this->School_model->create($info->name, array(), $domain);
			echo $school_id;

			$this->load->model('Settings_model');
			$this->Settings_model->insert_default($school_id);

			$settings_features = json_decode($this->Settings_model->get_settings($school_id, 'features'));

			$features_enabled = json_decode($onboard->features);

			$new_settings_features = array();
			
			foreach($settings_features as $k=>$v)
			{
				$new_settings_features[$k] = (isset($features_enabled->$k) && $features_enabled->$k == '1') ? 'ats' : false;
			}
			
			$this->Settings_model->save($school_id, 'features', $new_settings_features);

			$stats = json_decode($onboard->stats);
			$entities = $role = array();
			
			$results = $this->_parsestudents($stats->studentsfile);

			if(file_exists($stats->studentsfile)) 
			{
				$this->load->model('User_model');
				foreach($results['students'] as $student)
				{
					switch($login_method)
					{
						case 'emailflastname':
							$u = $student[2];
							$p = $this->User_model->encodepassword(strtolower(substr($student[0], 0, 1).$student[1]));
						break;
						case 'emailfirstlastname':
							$u = $student[2];
							$p = $this->User_model->encodepassword(strtolower($student[0].$student[1]));
						break;
						case 'emailfirst.lastname':
							$u = $student[2];
							$p = $this->User_model->encodepassword(strtolower($student[0].'.'.$student[1]));
						break;
						default:
							$u = '';
							$p = '';
						break;
					}

					$student_id = $this->User_model->create_student($school_id, $student[0], $student[1], $u, $p, $student[2], 'blobsmall.png', $student[5], $student[4], $student[6], $student[3], $student[7]);	
			  	$entities[strtolower($student[1].','.$student[0])] = $student_id;
			  	$entities[strtolower($student[0].' '.$student[1])] = $student_id;
			  	$entities[$student[2]] = $student_id;
			  	$entities[$student[4]] = $student_id;
			  	$role[$student_id] = 'student';
				}			
			}
			
			if(file_exists($stats->teachersfile)) 
			{
				$results = $this->_parsestaff($stats->teachersfile, 'teacher');

				foreach($results['staff'] as $teacher)
				{
					switch($login_method)
					{
						case 'emailflastname':
							$u = $teacher[2];
							$p = $this->User_model->encodepassword(strtolower(substr($teacher[0], 0, 1).$teacher[1]));
						break;
						case 'emailfirstlastname':
							$u = $teacher[2];
							$p = $this->User_model->encodepassword(strtolower($teacher[0].$teacher[1]));
						break;
						case 'emailfirst.lastname':
							$u = $teacher[2];
							$p = $this->User_model->encodepassword(strtolower($teacher[0].'.'.$teacher[1]));
						break;
						default:
							$u = '';
							$p = '';
						break;
					}

					$teacher_id = $this->User_model->create_teacher($school_id, $teacher[0], $teacher[1], $u, $p, $teacher[2], 'blobsmall.png');
			  	$entities[strtolower($teacher[1].','.$teacher[0])] = $teacher_id;
			  	$entities[strtolower($teacher[0].' '.$teacher[1])] = $teacher_id;
			  	$entities[strtolower($teacher[2])] = $teacher_id;
			  	$entities[$teacher[4]] = $teacher_id;
			  	$role[$teacher_id] = 'teacher';
				}
			}

			if(file_exists($stats->adminsfile)) 
			{
				$results = $this->_parsestaff($stats->adminsfile, 'admin');

				foreach($results['staff'] as $admin)
				{
					$teacher_id = 0;

					switch($login_method)
					{
						case 'emailflastname':
							$u = $admin[2];
							$p = $this->User_model->encodepassword(strtolower(substr($admin[0], 0, 1).$admin[1]));
						break;
						case 'emailfirstlastname':
							$u = $admin[2];
							$p = $this->User_model->encodepassword(strtolower($admin[0].$admin[1]));
						break;
						case 'emailfirst.lastname':
							$u = $admin[2];
							$p = $this->User_model->encodepassword(strtolower($admin[0].'.'.$admin[1]));
						break;
						default:
							$u = '';
							$p = '';
						break;
					}

					switch(true)
					{
						case isset($entities[strtolower($admin[1].','.$admin[0])]):
							$teacher_id = $entities[strtolower($admin[1].','.$admin[0])];
							$this->School_model->add_admin($school_id, $teacher_id);
						break;
						case isset($entities[strtolower($admin[0].' '.$admin[1])]):
							$teacher_id = $entities[strtolower($admin[0].','.$admin[1])];
							$this->School_model->add_admin($school_id, $teacher_id);
						break;
						case isset($entities[strtolower($admin[2])]):
							$teacher_id = $entities[strtolower($admin[2])];
							$this->School_model->add_admin($school_id, $teacher_id);
						break;
						default:
							$teacher_id = $this->User_model->create_admin($school_id, $admin[0], $admin[1], $u, $p, $admin[2], 'blobsmall.png');
						break;
					}
					
			  	$entities[strtolower($admin[1].','.$admin[0])] = $teacher_id;
			  	$entities[strtolower($admin[2])] = $teacher_id;
			  	$entities[$admin[4]] = $teacher_id;
			  	$role[$teacher_id] = 'admin';
				}		
			}

			if(file_exists($stats->groupsfile)) 
			{		
				$results = $this->_parseclasses($stats->groupsfile, $entities);

				$this->load->model('Group_model');
				foreach($results['groups'] as $group_name=>$members)
				{
					$group_id = $this->Group_model->create($school_id, $group_name, array());
					foreach($members as $member_id)
					{
						$method = 'add_'.$role[$member_id];

						if($method == 'add_admin')
						{
							$method = 'add_teacher';
						}
						
						$this->Group_model->$method($group_id, $member_id);
					}
				}	
			}	
		}
		else
		{
			$data = array('invitecode' => $onboard->invitecode);

			if(!empty($errors))
			{
				$data['errors'] = $errors;
			}

			$this->layout->view('onboard/process', $data);	
		}
	}

	function _parsestudents($filename)
	{
		$students = array();
		$line = 0;
		$errors = array();

		$keys = array();

		$labels = array();

		$file = fopen($filename, 'r');
		while(! feof($file))
	  {
	  	$line++;
	  	$student = fgetcsv($file);

	  	if($line == 1)
	  	{
	  		$labels = $student;
	  		continue;
	  	}

	  	if(count($student) == 1)
	  	{
	  		array_push($errors, "$filename Line: $line: invalid data");
	  		continue;
	  	}

	  	if(count($student) != count($labels))
	  	{
	  		array_push($errors, "$filename Line: $line: column count mismatch");
	  		continue;
	  	}

			$key = md5(implode('', $student));

	  	// Lastname
	  	if(strlen(trim($student[0])) == 0)
	  	{
	  		array_push($errors, "$filename Line: $line: missing ".$labels[0]);
	  	}

	  	// Firstname
	  	if(strlen(trim($student[1])) == 0)
	  	{
	  		array_push($errors, "$filename Line: $line: missing ".$labels[1]);
	  	}

	  	if(isset($keys[strtolower($student[1].','.$student[0])]))
	  	{
	  		array_push($errors, "$filename Line: $line: duplicate name ".$student[1].','.$student[0]);	
	  	}

	  	// Email
	  	$student[2] = strtolower($student[2]);
	  	if(!empty($student[2]) && isset($keys[$student[2]]))
	  	{
	  		array_push($errors, "$filename Line: $line: duplicate email ".$student[2]);	
	  	}

	  	// DOB
	  	if(!in_array($student[3], array('', '0/0/0000','00/00/00','00/00/0000')))
	  	{
	  		if(strtotime($student[3]) === FALSE || strtotime($student[3]) < 0)
	  		{
	  			array_push($errors, "$filename Line: $line: invalid DOB ".$student[3]);	
	  		}
	  		else
	  		{
	  			$student[3] = date('Y-m-d', strtotime($student[3]));
	  		}
	  	}
	  	else
	  	{
	  		$student[3] = '0000-00-00';
	  		//array_push($errors, "$filename Line: $line: empty DOB ".$student[3]);	
	  	}

	  	// Student Id
	  	if(!empty($student[4]))
	  	{
	  		if(isset($keys[$student[4]]))
	  		{
	  			array_push($errors, "$filename Line: $line: duplicate student id ".$student[4]);	
	  		}
	  	}
	  	// Grade

	  	// Gender
	  	if(!in_array($student[6], array('', 'F', 'M')))
	  	{
	  		array_push($errors, "$filename Line: $line: unknown gender ".$student[6]);	
	  	}

	  	$keys[strtolower($student[1].','.$student[0])] = $key;
	  	$keys[strtolower($student[0].' '.$student[1])] = $key;
	  	if(!empty($student[2]))
	  	{
	  		$keys[$student[2]] = $key;	
	  	}
	  	
	  	$keys[$student[4]] = $key;
	  	$students[$key] = $student;
	  }

		fclose($file);

		if(!empty($errors))
		{
			return array('errors' => $errors);
		}
		else
		{
			return array(
				'keys' => $keys,
				'students' => $students
			);
		}
	}

	function _parseparents($filename, $keys)
	{
		$parents = array();
		$line = 0;
		$errors = array();

		$labels = array();

		$file = fopen($filename, 'r');
		while(! feof($file))
	  {
	  	$line++;
	  	$parent = fgetcsv($file);

	  	if($line == 1)
	  	{
	  		$labels = $parent;
	  		continue;
	  	}

	  	if(count($parent) == 1)
	  	{
	  		array_push($errors, "$filename Line: $line: invalid data");
	  		continue;
	  	}

	  	if(count($parent) != count($labels))
	  	{
	  		array_push($errors, "$filename Line: $line: column count mismatch");
	  		continue;
	  	}

			$key = md5(implode('', $parent));

	  	// Lastname
	  	if(strlen(trim($parent[0])) == 0)
	  	{
	  		array_push($errors, "$filename Line: $line: missing ".$labels[0]);
	  	}

	  	// Firstname
	  	if(strlen(trim($parent[1])) == 0)
	  	{
	  		array_push($errors, "$filename Line: $line: missing ".$labels[1]);
	  	}

	  	if(isset($keys[strtolower($parent[1].','.$parent[0])]))
	  	{
	  		array_push($errors, "$filename Line: $line: duplicate name ".$parent[1].','.$parent[0]);	
	  	}

	  	// Email
	  	$parent[2] = strtolower($parent[2]);
	  	if(isset($keys[$parent[2]]))
	  	{
	  		array_push($errors, "$filename Line: $line: duplicate email ".$parent[2]);	
	  	}

	  	// Phone



	  	// Student
	  	if(empty($parent[4]))
	  	{
	  		array_push($errors, "$filename Line: $line: missing child");	
	  	}
	  	else
	  	{
	  		if(!isset($keys[strtolower(preg_replace('/, /', ',', $parent[4]))]))
	  		{
	  			array_push($errors, "$filename Line: $line: unknown child id ".$parent[4]);	
	  		}
	  	}


	  	$keys[strtolower($parent[1].','.$parent[0])] = $key;
	  	$keys[strtolower($parent[0].' '.$parent[1])] = $key;
	  	$keys[$parent[2]] = $key;
	  	$parents[$key] = $parent;
	  }

		fclose($file);

		if(!empty($errors))
		{
			return array('errors' => $errors);
		}
		else
		{
			return array(
				'keys' => $keys,
				'parents' => $parents
			);
		}
	}

	function _parsestaff($filename, $role)
	{
		$teachers = array();
		$line = 0;
		$errors = array();

		$keys = array();

		$labels = array();

		$file = fopen($filename, 'r');
		$filename = $role;
		while(! feof($file))
	  {
	  	$line++;
	  	$teacher = fgetcsv($file);

	  	if($line == 1)
	  	{
	  		$labels = $teacher;
	  		continue;
	  	}

	  	if(count($teacher) == 1)
	  	{
	  		array_push($errors, "$filename Line: $line: invalid data");
	  		continue;
	  	}

	  	if(count($teacher) != count($labels))
	  	{
	  		array_push($errors, "$filename Line: $line: column count mismatch");
	  		continue;
	  	}

			$key = md5(implode('', $teacher));

	  	// Lastname
	  	if(strlen(trim($teacher[0])) == 0)
	  	{
	  		array_push($errors, "$filename Line: $line: missing ".$labels[0]);
	  	}

	  	// Firstname
	  	if(strlen(trim($teacher[1])) == 0)
	  	{
	  		array_push($errors, "$filename Line: $line: missing ".$labels[1]);
	  	}

	  	if(isset($keys[strtolower($teacher[1].','.$teacher[0])]))
	  	{
	  		array_push($errors, "$filename Line: $line: duplicate name ".$teacher[1].','.$teacher[0]);	
	  	}

	  	// Email
	  	$teacher[2] = strtolower($teacher[2]);
	  	if(isset($keys[$teacher[2]]))
	  	{
	  		array_push($errors, "$filename Line: $line: duplicate email ".$teacher[2]);	
	  	}

	  	// DOB
	  	if(!in_array($teacher[3], array('', '0/0/0000','00/00/00','00/00/0000')))
	  	{
	  		if(strtotime($teacher[3]) === FALSE || strtotime($teacher[3]) < 0)
	  		{
	  			array_push($errors, "$filename Line: $line: invalid DOB ".$teacher[3]);	
	  		}
	  		else
	  		{
	  			$teacher[3] = date('Y-m-d', strtotime($teacher[3]));
	  		}
	  	}
	  	else
	  	{
	  		$teacher[3] = '0000-00-00';
	  		//array_push($errors, "$filename Line: $line: empty DOB ".$teacher[3]);	
	  	}

	  	// Teacher Id
	  	if(!empty($teacher[4]))
	  	{
	  		if(isset($keys[preg_replace('/, /', ',', $teacher[4])]))
	  		{
	  			array_push($errors, "$filename Line: $line: duplicate ".$role." id ".$teacher[4]);	
	  		}
	  	}

	  	// Gender
	  	if(!in_array($teacher[5], array('', 'F', 'M')))
	  	{
	  		array_push($errors, "$filename Line: $line: unknown gender ".$teacher[5]);	
	  	}

	  	$keys[strtolower($teacher[1].','.$teacher[0])] = $key;
	  	$keys[strtolower($teacher[0].' '.$teacher[1])] = $key;
	  	$keys[$teacher[2]] = $key;
	  	$keys[$teacher[4]] = $key;
	  	$teachers[$key] = $teacher;
	  }

		fclose($file);

		if(!empty($errors))
		{
			return array('errors' => $errors);
		}
		else
		{
			return array(
				'keys' => $keys,
				'staff' => $teachers
			);
		}
	}	

	function _parseclasses($filename, $keys)
	{
		$file = fopen($filename, 'r');
		$groups = array();
		$line = 0;

		$errors = array();

		while(! feof($file))
	  {
	  	$line++;
	  	$association = fgetcsv($file);

	  	if($line == 1)
	  	{
	  		$labels = $association;
	  		continue;
	  	}

	  	if(!isset($groups[$association[0]]))
	  	{
	  		$groups[$association[0]] = array();
	  	}

	  	$user_id = 0;

	  	if(isset($keys[strtolower($association[1])]))
	  	{
	  		$user_id = $keys[strtolower($association[1])];
	  	}
	  	else
	  	{
	  		array_push($errors, "$filename Line: $line: unknown id ".$association[1]);	
	  	}

	  	if(in_array($user_id, $groups[$association[0]]))
	  	{
	  		continue;
	  	}
	  	array_push($groups[$association[0]], $user_id);
	  }
		fclose($file);		

		if(!empty($errors))
		{
			return array('errors' => $errors);	
		}
		else
		{
			return array('groups' => $groups);
		}
	}
}

?>