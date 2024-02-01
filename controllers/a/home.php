<?php

class Home extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function addlibrary()
	{
		$this->db->simple_query('CREATE TABLE `Library` (`libraryid` int(11) NOT NULL,`resourcetype` varchar(10) NOT NULL,`package` int(11) NOT NULL,`title` text NOT NULL,`description` text NOT NULL,`metadata` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$this->db->simple_query('ALTER TABLE `Library` ADD PRIMARY KEY (`libraryid`);');
		$this->db->simple_query('ALTER TABLE `Library` MODIFY `libraryid` int(11) NOT NULL AUTO_INCREMENT');
		$this->db->simple_query('CREATE TABLE `LibraryInstall` (`installid` int(11) NOT NULL, `libraryid` int(11) NOT NULL, `schoolid` int(11) NOT NULL, `userid` int(11) NOT NULL, `timeinstalled` int(11) NOT NULL, `timeuninstalled` int(11) NOT NULL, `metadata` text NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
		$this->db->simple_query('ALTER TABLE `LibraryInstall` ADD PRIMARY KEY (`installid`);');
		$this->db->simple_query('ALTER TABLE `LibraryInstall` MODIFY `installid` int(11) NOT NULL AUTO_INCREMENT');
		print_r($this->db->queries);
	}

	function adduserlogin()
	{
		$this->db->simple_query('CREATE TABLE IF NOT EXISTS `Reset` (`userid` int(11) NOT NULL,`timecreated` int(11) NOT NULL,`timereset` int(11) NOT NULL,`hashkey` varchar(32) NOT NULL,`status` tinyint(4) NOT NULL);');
		$this->db->simple_query('ALTER TABLE `Users` ADD `nonce` VARCHAR(32) NOT NULL AFTER `userid`');
		$this->db->simple_query('UPDATE `Users` SET `nonce` = MD5(CONCAT(firstname, lastname))');
		$this->db->simple_query('ALTER TABLE `Logs` CHANGE `type` `type` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;');
	}

	function adddetentionlabels()
	{
		$this->load->model('Settings_model');
		
		$query = $this->db->query('SELECT * FROM Schools');
		$schools = $query->result();

		foreach($schools as $school)
		{
			$school_id = $school->schoolid;
			$labels = json_decode($this->Settings_model->get_settings($school_id, 'labels'));
			$labels->detentionunit = 'minute';
			$labels->detentionunits = 'minutes';
			$labels->detention = 'Detention';
			$labels->detentions = 'Detentions';
			$this->Settings_model->save($school_id, 'labels', $labels);
		}
	}

	function addmotivationsetting()
	{
		$motivations = array(
			'Get Peer Attention',
			'Get Adult Attention',
			'Avoid Peer Attention',
			'Avoid Adult Attention',
			'Item/Activity',
		); 

		$locations = array (
			'Class', 
			'School Yard'
		);

		$this->load->model('Settings_model');
		
		$query = $this->db->query('SELECT * FROM Schools');
		$schools = $query->result();

		foreach($schools as $school)
		{
			$school_id = $school->schoolid;
			$this->Settings_model->save($school_id, 'motivations', $motivations);
			$this->Settings_model->save($school_id, 'locations', $locations);
		}
	}

	function addteachersave()
	{
		$this->db->query("ALTER TABLE `Referrals` ADD `timeteachersave` INT NOT NULL AFTER `timecheckin`;");
		$this->db->query("UPDATE `Referrals` SET timeteachersave = timecreated");
	}

	function addconsequencesettings()
	{
		$school_id = 12;
		$this->load->model('Settings_model');
		$adminreview_settings = json_decode($this->Settings_model->get_settings($school_id, 'adminreview'));

		print_r($adminreview_settings->consequences);

		$settings = array();
		foreach($adminreview_settings->consequences as $c)
		{
			array_push($settings, array('type' => 'label', 'label' => $c));
		}

		$this->Settings_model->save($school_id, 'consequences', $settings);
	}

	function addformidelements()
	{
		$query = $this->db->query('SELECT Forms.formdata, Reports.report, Reports.reportid FROM Reports, Forms WHERE Reports.formid = Forms.formid');
		$responses = $query->result();

		foreach($responses as $response)
		{
			$r = json_decode($response->report);
			$f = json_decode($response->formdata);

			$new_response = array();

			foreach($r as $a)
			{
				foreach($f->questions as $e)
				{
					if($a->label == $e->label)
					{
						$a->id = $e->id;
						break;
					}
					
				}

				if(!isset($a->id))
				{
					echo $response->reportid.' '.$a->label;
				}
				array_push($new_response, $a);

				$this->db->update('Reports', array('report' => json_encode($new_response)), array('reportid' => $response->reportid));
			}
		}
	}

	function addlabels()
	{
		$this->load->model('Settings_model');
		
		$query = $this->db->query('SELECT * FROM Schools');
		$schools = $query->result();

		foreach($schools as $school)
		{
			$school_id = $school->schoolid;
			$demerit_settings = json_decode($this->Settings_model->get_settings($school_id, 'demerits'));
			$reinforcements_settings = json_decode($this->Settings_model->get_settings($school_id, 'reinforcements'));

			$labels = array(
				'demerit' => isset($demerits_settings) ? $demerits_settings->demeritlabel : 'Demerit',
				'demerits' => isset($demerits_settings) ? $demerits_settings->demeritlabel : 'Demerits',
				'reinforcements' => isset($reinforcements_settings) ? $reinforcements_settings->reinforcementlabel.'s' : 'C.A.R.E.S.',
				'reinforcement' => isset($reinforcements_settings) ? $reinforcements_settings->reinforcementlabel : 'C.A.R.E.',				
				'referrals' => 'Referrals',
				'referral' => 'Referral',
				'detentions' => 'Detentions',
				'detention' => 'Detention'
			);

			$this->Settings_model->save($school_id, 'labels', $labels);
		}
	}

	function generate()
	{
		$statuses = array('statuses' =>
			array(
				'In class',
				'In bathroom',
				'In office referral',
				'In detention',
				'Absent'
			),
		);

		$this->db->insert('Settings', array(
			'schoolid' => '12',
			'timecreated' => time(),
			'type' => 'statuses',
			'settings' => json_encode($statuses)
		));
	}

	function fixemails()
	{
		$file = file('students.txt');

		$i = 0;
		foreach($file as $student)
		{
			list($email, $firstname, $lastname) = preg_split("/\t/", trim($student));

			$query = $this->db->query('SELECT * FROM Users WHERE schoolid = 13 AND LOWER(firstname) = ? AND LOWER(lastname) = ? LIMIT 1', array(strtolower($firstname), strtolower($lastname)));
			$found = $query->row();

			if(empty($found))
			{
				$query = $this->db->query('SELECT * FROM Users WHERE schoolid = 13 AND LOWER(firstname) LIKE "%'.strtolower($firstname).'%" AND LOWER(lastname) LIKE "%'.strtolower($lastname).'%" LIMIT 1');
				$found = $query->row();
			}

			if(empty($found))
			{
				echo ''.$lastname.',',$firstname.'<br />';
				continue;
			}

			if($found->email != $email)
			{
				$this->db->update('Users', array('email' => $email), array('userid' => $found->userid));
				//echo 'Changed '.$lastname.',',$firstname.' to '.$email.'<br />';
			}
			else
			{
				//echo 'Nothing to change '.$lastname.',',$firstname.' to '.$email.'<br />';
			}
		}
	}

	function login()
	{
		$users = array('jeancarl', 'reina', 'francisco');
		if($this->input->post('submit'))
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			if(in_array($username, $users) && md5($password) == '5e7823bc59d77321b45c11e1323a5e22')
			{
				$this->session->set_userdata('admin', $username);

				redirect('a/dashboard');
			}
			else
			{
				$this->session->sess_destroy();
				redirect('a/login');
			}
		}
		else
		{
			$this->session->sess_destroy();
			$this->layout->view('a/home/login');
		}
	}

	function dashboard()
	{
		if(!$this->session->userdata('admin'))
		{
			redirect('a/login');
			return;
		}

		$query = $this->db->query('SELECT * FROM Schools');
		$schools = $query->result();

		$this->layout->view('a/home/dashboard', array('schools' => $schools));
	}

	function addschool()
	{
		if($this->input->post('submit'))
		{
			$school_name = $this->input->post('schoolname');

			$this->load->model('School_model');
			$this->load->model('Settings_model');

			$school_id = $this->School_model->create($school_name, array());
			$this->Settings_model->insert_default($school_id);

			redirect('a/explorer/school/'.$school_id);
		}
		else
		{
			$this->layout->view('a/home/addschool');
		}
	}

	function addgroup($school_id)
	{
		$this->load->model('Group_model');
		$this->load->model('School_model');

		$school = $this->School_model->get_school($school_id);

		if($this->input->post('submit'))
		{
			$title = $this->input->post('groupname');

			$meta_data = array();
			$group_id = $this->Group_model->create($school_id, $title, $meta_data);

			redirect('a/explorer/group/'.$group_id);
		}
		else
		{
			$this->layout->view('a/home/addgroup', array('school' => $school));
		}
	}

	function addstudent($group_id)
	{
		$this->load->model('Group_model');
		$this->load->model('User_model');

		$group = $this->Group_model->get_group($group_id);

		if($this->input->post('submit'))
		{
			$first_name = $this->input->post('firstname');
			$last_name = $this->input->post('lastname');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$email = $this->input->post('email');
			$grade = $this->input->post('grade');
			$student_id = $this->input->post('studentid');
			$dob = $this->input->post('dob');
			$ethnicity = $this->input->post('ethnicity');

			$profile_image = 'blobsmall.png';
			$school_id = $group->schoolid;

			$student_id = $this->User_model->create_student($school_id, $first_name, $last_name, $username, $password, $email, $profile_image, $grade, $student_id, $gender, $dob, $ethnicity);

			$this->Group_model->add_student($group_id, $student_id);

			redirect('a/explorer/user/'.$student_id);
		}
		else
		{
			$this->layout->view('a/home/addstudent', array('group' => $group));
		}
	}

	function addteacher($group_id)
	{
		$this->load->model('Group_model');
		$this->load->model('User_model');
		$this->load->model('School_model');

		$group = $this->Group_model->get_group($group_id);

		if($this->input->post('submit'))
		{
			$role = $this->input->post('role');
			$first_name = $this->input->post('firstname');
			$last_name = $this->input->post('lastname');
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$email = $this->input->post('email');

			$profile_image = 'blobsmall.png';
			$school_id = $group->schoolid;

			switch($role)
			{
				case 'teacher':
					$user_id = $this->User_model->create_teacher($school_id, $first_name, $last_name, $username, $password, $email, $profile_image);

					$this->Group_model->add_teacher($group_id, $user_id);
				break;
				case 'hybrid':
					$user_id = $this->User_model->create_teacher($school_id, $first_name, $last_name, $username, $password, $email, $profile_image);

					if(!$this->School_model->has_admin($school_id, $user_id))
					{
						$this->School_model->add_admin($school_id, $user_id);
					}
				break;
				case 'admin':
					$user_id = $this->User_model->create_admin($school_id, $first_name, $last_name, $username, $password, $email, $profile_image);

					if(!$this->School_model->has_admin($school_id, $user_id))
					{
						$this->School_model->add_admin($school_id, $user_id);
					}
				break;
			}

			redirect('a/explorer/user/'.$user_id);
		}
		else
		{
			$this->layout->view('a/home/addteacher', array('group' => $group));
		}
	}

	function stats($stat = '')
	{
		if(!$this->session->userdata('admin'))
		{
			redirect('a/login');
			return;
		}

		$data = array();
		switch($stat)
		{
			case 'detentions':
				$query = $this->db->query('SELECT Detentions.*, student.firstname as studentfirstname, student.lastname as studentlastname, teacher.firstname as teacherfirstname, teacher.lastname as teacherlastname FROM Detentions, Users student, Users teacher WHERE Detentions.studentid = student.userid AND Detentions.adminid = teacher.userid AND Detentions.type = "assigned" ORDER BY timecreated DESC');

				$data['detentions'] = $query->result();
			break;
			case 'shoutouts':
				$query = $this->db->query('SELECT Shoutouts.*, from.firstname as fromfirstname, from.lastname as fromlastname, to.firstname as tofirstname, to.lastname as tolastname FROM Shoutouts, Users `from`, Users `to` WHERE Shoutouts.touserid = to.userid AND Shoutouts.fromuserid = from.userid ORDER BY Shoutouts.timecreated DESC');

				$data['shoutouts'] = $query->result();
			break;
		}

		$this->layout->view('a/home/stats_'.$stat, $data);
	}

	function import()
	{
		$school_id = 19;

		$students = array(
		//array("520","Adriazola-Saunders","Aiyana","Karmencita A","352379","F","2","07/29/2007","500","1","467","1","4059234854","mayasoluna@gmail.com"),
		array("520","Ayres","Eliza","J","350664","F","2","10/13/2007","700","1","467","1","5635132874",""),
		array("520","Beyah","Nasira","Sabrin","355792","F","2","01/11/2007","600","1","467","1","8619537982","taimabeyah@gmail.com"),
		array("520","Blackburn","Rowan","Clinton","361779","M","1","07/20/2008","700","1","467","1","4244586096","juliagoldinblackburn@gmail.com"),
		array("520","Bozick","Trevor","Callahan","343635","M","2","08/28/2006","700","1","467","1","1448833184",""),
		array("520","Carter","Zoey","Sage","343637","F","3","06/29/2006","700","1","467","1","5626138858","liz.carter0@gmail.com"),
		array("520","Cox","Connor","Matthew Shiple","357221","M","1","03/23/2008","700","1","467","1","5018633234","sarah_cox@rocket.com"),
		array("520","Crane-Brown","Calliope","Francis","355797","F","2","01/15/2007","700","1","467","1","5935858402","audcrane@gmail.com"),
		array("520","Grant","Spencer","Lloyd Randall","344945","M","3","10/06/2006","600","1","467","1","7034116083","steadyintegrity@yahoo.com"),
		array("520","Hernandez","Nataani","Izel","362559","f","2","11/21/2007","500","1","467","2","4480476210",""),
		array("520","Herron","Aniyah","Latrice","347307","F","3","10/24/2005","600","1","467","1","1551493866","inayah_noor102405@yahoo.com"),
		array("520","James Phillips","Amari","Khalil","358608","M","1","02/09/2008","700","1","467","1","2063449473","aelbers@gmail.com"),
		array("520","Johnson","Brianna","KaLynn Marie","357741","F","2","12/10/2006","600","1","467","1","9693357658","tura.johnson@att.net"),
		array("520","Jonas","Jonathan","Rhylee","355806","M","2","03/02/2007","600","1","467","1","9562265627","r3kpope@aol.com"),
		array("520","Kaba","Mohamed","","276536","M","1","12/06/2007","600","1","467","2","3411593302",""),
		array("520","Latu","Lamata","Apaau-Yvette","349627","F","2","08/17/2007","303","1","467","1","8324587214","julie.latu@us.whg.com"),
		array("520","Leung","Tommy","Paul","357259","M","1","07/26/2008","500","1","467","1","1906725734","mcarranz1@yahoo.com"),
		array("520","Mader-Johnson","Piza","","358207","F","1","01/24/2008","600","1","467","1","9160277938","nmader40@gmail.com"),
		array("520","Marroquin Castro","Daniela","","274684","F","2","06/18/2007","500","1","467","3","5570768676","marvilo@sbcglobal.net"),
		array("520","Nguyen","Quynh-Anh","","364393","F","3","01/16/2006","204","1","467","1","5584305767",""),
		array("520","Olmeda","Jahseed","Issac","355894","M","3","02/22/2006","500","1","467","3","5173825588","cddiaz_85@hotmail.com"),
		array("520","Pulgarin","Zahira","","355892","F","3","10/27/2006","500","1","467","3","6049008808","mariac@mujeresunidas.net"),
		array("520","Ramirez Gonzalez","Viridiana","","362561","F","3","12/24/2005","500","1","467","5","3732284553",""),
		array("520","Reynolds","Jordan","Olivia","355817","F","2","08/12/2007","999","1","467","1","9018912172","rebeccadreynolds@gmail.com"),
		array("520","Ross","King","Jamir","346892","M","3","05/25/2006","600","1","467","1","8306138621","wordslanger@gmail.com"),
		array("520","Sabeh","Isabella","","370074","F","1","03/09/2008","600","1","467","1","6706041636","gsabeh13@arr.net"),
		array("520","Sebhat","Sham","Gabriel","355818","M","2","12/31/2006","600","1","467","3","1454453008","tegsti@aol.com"),
		array("520","Shaheed","Zayd","Ahmad","350798","M","1","08/20/2007","600","1","467","1","9730037867","khalidsheheed@yahoo.com"),
		array("520","Sidhu","Mira","Kaur","355910","F","2","11/05/2006","700","1","467","1","3506232958","raminder22@yahoo.com"),
		array("520","Strong","Kaleb","Eric","355952","M","3","04/27/2006","600","1","467","3","7215080297","earthmoonsunlove@yahoo.com"),
		array("520","Thomas","Kobi","","369751","M","1","05/24/2008","203","1","467","1","4591966410","hthomas@efcps.net"),
		array("520","Thompson","Kyle","Jameel","355790","M","3","08/16/2006","600","1","467","1","2407967741","yolanda.oliver@yahoo.com"),
		array("520","Achee","Genevieve","Juliette","351835","F","2","03/16/2007","700","4","468","1","5737422791","jmachee7@yahoo.com"),
		array("520","Adams","Ileana","Lyda","361769","F","1","07/13/2008","700","4","468","3","6132843123","flo_ovich@yahoo.com"),
		array("520","Baer-Bukowski","Wade","Joseph","338142","M","3","10/11/2005","700","4","468","1","6710191492","ktmbaer@gmail.com"),
		array("520","Bradshaw","Hattie","Anne Wise","355834","F","3","05/11/2006","700","4","468","1","4229164001","frazer@frazerbradshaw.com"),
		array("520","Briskman","Oakley","Skye","351358","F","2","09/19/2006","700","4","468","5","4938466285",""),
		array("520","Brown","Elijah","Finneaus","344468","M","3","05/15/2006","700","4","468","1","8261334988","lisabrown2000@gmail.com"),
		array("520","Burke","Djuna","Josephine","355836","F","3","05/04/2006","700","4","468","1","7969907338","Michele Burke"),
		array("520","Chu","Jordan","","276420","M","1","09/02/2008","201","4","468","3","8543094298","love0106life@hotmail.com"),
		array("520","Cooper-Bates","Acacio","Lee","361800","M","1","05/07/2008","700","4","468","1","4099143093","sadieray@gmail.com/joshuabates@gmail.com"),
		array("520","Cooper-Robinson","Shahira","Sya'ltzayar","352896","F","3","11/22/2005","100","4","468","1","6163221363","Rachl_Cooper1@yahoo.com"),
		array("520","Dhillon","Taye","Julian","355702","M","2","02/05/2007","600","4","468","1","4212393794","jenniferdhillon@gmail.com"),
		array("520","Dillemuth","Jamison","Finch","344260","M","2","06/14/2006","299","4","468","1","1244398228","babyandjamie@gmail.com"),
		array("520","Fredericks","Amirah","Sujei-Allen","358615","F","1","03/18/2008","500","4","468","5","1563294497","maximizingpowerwithin@gmail.com"),
		array("520","Ha","Tyler","Tran","359431","M","1","02/15/2008","204","4","468","2","8462784914","tylucmom@gmail.com"),
		array("520","Jewell","Charlotte","Egeria","355879","F","3","03/17/2006","700","4","468","1","3541791238","kiki@kiki.org"),
		array("520","Jung","Zoe","Taiog","345185","F","3","07/14/2006","203","4","468","1","1269884507","mirandahoffmanjung@gmail.com"),
		array("520","Laub-Sabater","Santiago","Daniel","351415","M","2","04/13/2007","500","4","468","3","1188389507",""),
		array("520","Lowe","Jamir","Dorsett","362562","m","3","05/17/2006","600","4","468","1","1509913697",""),
		array("520","Macon-Bennett","Marcel","","357278","M","1","01/29/2008","700","4","468","1","8281353003","jamacon@hotmail.com"),
		array("520","Mevi","Lucia","Rose","352142","F","2","09/29/2007","700","4","468","1","8442664705","clairebo74@gmail.com"),
		array("520","Mitchell","Lanai","","361824","F","1","07/06/2008","600","4","468","1","9710421524","naynay_twoods@yahoo.com"),
		array("520","Montraix","Dalton","Robert","351880","M","2","03/25/2007","700","4","468","1","5993521468","montraix@gmail.com"),
		array("520","Murua","Ximena","Inabel","362787","f","1","06/01/2008","500","4","468","3","5644725658","nummy59@hotmail.com"),
		array("520","Olmeda","Jahshua","Imani","361826","M","1","01/05/2008","500","4","468","3","3485067131","cddiaz_85@hotmail.com"),
		array("520","Perales-Acosta","Camila","Nicolle","349915","F","2","02/13/2007","500","4","468","3","6685730403","luluacme71@hotmail.com"),
		array("520","Robinson","Bishop","Marcel Walter Samuel","272502","M","3","04/19/2006","600","4","468","1","4044189778",""),
		array("520","Samson","Kirubel","","343521","M","3","09/07/2006","600","4","468","3","7546301594","tery5us@yahoo.com"),
		array("520","Sidhu","Tej","","361844","M","1","06/02/2008","999","4","468","1","1401738164","raminder22@yahoo.com/mstollman@hotmail.com"),
		array("520","Stratton","Nicolas","","356507","M","2","03/18/2007","700","4","468","1","1243384547","kaciestratton@gmail.com"),
		array("520","Stratton","Riley","Thomas","355823","M","2","03/18/2007","700","4","468","1","7530439746","kaciestratton@gmail.com"),
		array("520","Tank","Ashley","Marie","357220","F","1","07/17/2008","700","4","468","1","2201796652","support@anyboat.net"),
		array("520","White","Jaelyn","Deaunte","354400","M","2","12/20/2006","600","4","468","1","9347421328","jhendrix84@yahoo.com"),
		array("520","Adams","Lucija","","355827","F","3","03/15/2006","700","5","470","2","1290267865","badesignlab@yahoo.com"),
		array("520","Arana Baldi","Alexus","Romeio","355682","M","2","03/07/2007","100","5","470","3","8306312141","catherinebaldi@gmail.com"),
		array("520","Arango","Julia","","364209","F","1","07/22/2008","500","5","470","3","2072124156","jimena@jimenamosquera.com"),
		array("520","Avalos","Nickolas","Daniel","349011","M","1","03/24/2008","500","5","470","3","8652925214",""),
		array("520","Baguio","Alan","Lee","358087","M","1","05/04/2008","999","5","470","1","8722081855",""),
		array("520","Chavez","Zaretzi","Vargas","360234","F","1","08/12/2008","500","5","470","3","3793843078",""),
		array("520","Chiu","Yun","Sheng","363812","m","2","12/26/2007","201","5","470","5","8266586905","chatzeng@msn.com"),
		array("520","Dright","Curtis","Louis","355783","M","2","08/22/2007","600","5","470","1","9107433404","ayeshalouis79@gmail.com"),
		array("520","Flemming","Ephraim","Jose","355802","M","2","10/17/2007","600","5","470","1","2185315132","revndocflemming@yahoo.com"),
		array("520","Goux King","Ermie","Sophia","350789","F","3","10/14/2005","700","5","470","1","8151547274","marjorie.goux@clorox.com"),
		array("520","Goux King","Julian","Howard","350793","M","3","10/14/2005","700","5","470","1","1796099332","marjorie.goux@clorox.com"),
		array("520","Green","Evan","Melissa","351484","F","1","11/04/2007","600","5","470","1","5470617925","cnotedoriginal1@yahoo.com"),
		array("520","Green","Romi","Shana","368908","F","1","09/30/2008","700","5","470","1","4467335692",""),
		array("520","Jacobs","Louis","Samuel","355880","M","3","06/06/2006","500","5","470","3","7629469582","showersoflove2u@aol.com"),
		array("520","Khrabrov","Edward","","359080","M","1","04/16/2008","700","5","470","3","5722518431","olga.bashlacheva@gmail.com"),
		array("520","Macaraeg-Nathanson","Aditi","Kumari","348289","F","3","10/01/2006","700","5","470","1","3277257467","thereisnodoer@yahoo.com"),
		array("520","Mendoza Perez","Ashley","Krystine","361332","F","1","03/21/2008","500","5","470","3","2543594861",""),
		array("520","Metzner","Isa","Medow","359859","F","1","07/26/2008","700","5","470","1","9537697350","kmetzr@sbcglobal.net"),
		array("520","Mitchell","Lance","Robert","353480","M","2","03/16/2007","600","5","470","1","2653282721","naynay_twoods@yahoo.com"),
		array("520","Pagani","Kaden","Lawrence","361074","M","1","01/03/2008","600","5","470","1","8291050672",""),
		array("520","Pirker Moreira","Asher","Maddox","356105","M","2","12/03/2006","700","5","470","3","1767188340","rebmarekrip@hotmail.com"),
		array("520","Rizzetta","Ruby","Wilder","357493","F","1","09/27/2008","700","5","470","1","1560504374","rebeccarizzeha@yahoo.com"),
		array("520","Samson","Surafel","","361842","M","1","07/23/2008","999","5","470","1","4795764660","tery5us@yahoo.com"),
		array("520","Smith","Chase","Stephen Ian","362190","M","1","07/19/2008","500","5","470","1","2375543136","themonica56@yahoo.com"),
		array("520","Sow","Saliou","James","345855","M","3","11/20/2006","600","5","470","1","1381025654",""),
		array("520","Suh","Luke","","357371","M","2","08/23/2007","203","5","470","2","2210108275","ssupark@gmail.com;"),
		array("520","Thomas","Aliyah","","355824","F","2","06/04/2007","600","5","470","3","4515142656","perezvictoria958@yahoo.com"),
		array("520","Toutjian","Isaac","Wilde","359573","M","1","05/31/2008","500","5","470","1","9728034941","spacerose99@hotmail.com"),
		array("520","Upton","Leah","Catherine Dorringt","345183","F","3","04/03/2006","700","5","470","1","3964958684","martha.lyman@gmail.com"),
		array("520","Webb","Ruben","Lamont","363178","M","1","07/10/2008","600","5","470","1","7368244773","jopeskris@att.net"),
		array("520","Wegis","Ella","Madison","362544","F","1","06/23/2008","201","5","470","1","9716537204",""),
		array("520","Adams","Jonah","Edward","359347","M","1","11/26/2007","700","6","472","1","9577678116","juliefeinstein@mac.com"),
		array("520","Cantero","Kailia","Rachelle","350966","F","2","12/03/2006","700","6","472","1","5588168714",""),
		array("520","Cattermole","Henry","Hatcher","355796","M","2","01/31/2007","700","6","472","1","6543659092","kchatcher@gmail.com"),
		array("520","Chirino","Esmeralda","","276360","F","1","07/12/2008","500","6","472","3","3750551043","cechirino@gmail.com"),
		array("520","Cooper-Bates","Vida","Sunshine","355821","F","3","05/27/2006","700","6","472","1","6705880896","sadieray@gmail.com"),
		array("520","DePaz","Q'orianka","Elena","362571","f","3","10/17/2005","100","6","472","1","6571649553","zoe.depaz@gmail.com"),
		array("520","French","Benjamin","Telford","355706","M","3","08/23/2006","500","6","472","3","2908513811","jkfcostarica@yahoo.com"),
		array("520","French","Kevin","A","362553","M","2","09/20/2007","500","6","472","3","5571965351","jkfcostarica@yahoo.com"),
		array("520","Galka","Tulsi","Music Maeve Thomas","351595","F","2","02/16/2007","600","6","472","1","9434382568","eurydice@sbcglobal.net"),
		array("520","Heeter","Tug","Thomas","362547","M","1","11/15/2007","999","6","472","1","6700334973","boatpeople11@hotmail.com"),
		array("520","Hood","Olivia","Sarieh","360257","F","1","09/25/2008","700","6","472","1","5083161028","Hoodlum71@mac.com"),
		array("520","Ignacio Grimes","Nathan","Bay","351968","M","2","08/24/2007","400","6","472","1","9024809231","slbignacio@gmail.com"),
		array("520","Iguardia","Victoria","Aylani","352466","F","2","08/26/2004","500","6","472","3","4147510083","rgbethel13@gmail.com"),
		array("520","Jeanpierre","Charlton","Antoine","345147","M","3","07/31/2006","600","6","472","1","1784657305","shannonmurphy94544@gmail.com"),
		array("520","Joshi","Sahira","","359090","F","1","12/09/2007","205","6","472","2","6541926388","sonajoshi@gmail.com"),
		array("520","Lam","Erick","David","355811","M","2","01/30/2007","206","6","472","1","2025479867","gricel_lam@yahoo.com"),
		array("520","McAdams","Isabella","A.V","361818","F","1","01/02/2008","999","6","472","1","7319293784","mmvisaya@gmail.com"),
		array("520","Monjaraz","Yonatli","","361825","F","1","03/09/2008","500","6","472","3","3103465498","amonjaraz@yahoo.com"),
		array("520","Nunes","Fletcher","Mason","350673","M","2","12/11/2006","700","6","472","1","6906981014","susanbnunes@gmail.com"),
		array("520","Nunes","Sebastian","Morrissette","350670","M","2","12/11/2006","700","6","472","1","5996710721","susanbnunes@gmail.com"),
		array("520","Osarelli","Rowan","Patrick","351726","M","2","05/10/2007","700","6","472","1","2475757348","mahateis@yahoo.com"),
		array("520","Paniagua","Sabrina","","276183","F","1","12/07/2007","500","6","472","3","2594616052",""),
		array("520","Robinson","Majesty","","274842","F","2","08/27/2007","600","6","472","1","5366745202",""),
		array("520","Sheen","Michael","Sangyoun","355888","M","3","07/25/2006","203","6","472","1","2315776162","bschi@comcast.net"),
		array("520","Shiga","Adan","Gladding","355887","M","3","02/23/2006","202","6","472","3","5611309086","kohki.shiga@gmail.com"),
		array("520","Swartz","Oona","Grace","350347","F","2","03/03/2007","700","6","472","1","8094774295","jason@bks2.com"),
		array("520","Tien","Brandon","Hao","362550","M","2","08/18/2007","201","6","472","3","5519099171","dannyt1107@yahoo.com"),
		array("520","Urbano-Paras","Marcelo","","362205","m","1","01/02/2008","400","6","472","1","5439547506","melissaurbano@hotmail.com"),
		array("520","Voss","Lily","Nokomis","355895","F","3","07/10/2006","700","6","472","1","6487117964","Qamar.sara@gmail.com"),
		array("520","Washington","Leneil","Donnovan","355912","M","3","11/29/2006","202","6","472","1","8272284232","percilla.ortega@gmail.com"),
		array("520","Zavala Zuniga","Darian","","370010","m","1","12/18/2007","500","6","472","5","1788717669","adrianazng@yahoo.com"),
		array("520","Arana-Baldi","Sofia","","330601","F","4","08/12/2004","100","10","471","2","7193107686","catherinebaldi@gmail.com"),
		array("520","Balestreri","Anna","Jane","331856","F","4","11/17/2004","700","10","471","1","6374353296","joe@metaman.us"),
		array("520","Bejines","Alexis","","330688","M","4","04/13/2004","500","10","471","3","9292064940",""),
		array("520","Bernal","Siclali","","355878","F","4","06/05/2005","500","10","471","3","1672808995","oliviamonjaraz@gmail.com"),
		array("520","Birru","Tiya","Deresse","339907","F","4","05/22/2005","500","10","471","2","4554670972","enat/feleke@yahoo.com"),
		array("520","Bozick","Jack","Alexander","329984","M","4","02/13/2004","700","10","471","1","8219558316","jlcabroad@yahoo.com"),
		array("520","Burnett","Sarah","Angelina","338550","F","4","07/08/2005","600","10","471","1","7537032360",""),
		array("520","Collins","Terrion","R","348268","M","4","10/01/2005","600","10","471","1","6584438621",""),
		array("520","Cook","Laurel","Payton","362569","f","4","05/15/2005","100","10","471","1","7069269194","blessedinfinitly@gmail.com"),
		array("520","Crane-Brown","Clementine","Praza","355707","F","4","03/12/2005","700","10","471","1","8085066175","audcrane@gmail.com"),
		array("520","Dahl","Mark","David","332508","M","4","07/18/2004","700","10","471","1","2420454374",""),
		array("520","Duncan","Israel","Andrew-Isaiah","355922","m","4","11/25/2005","600","10","471","1","5581415406","lrt4fun@yahoo.com"),
		array("520","Hernandez Salgado","Mario","","339216","M","4","09/27/2005","500","10","471","3","5419904725","hrdz.karla@gmail.com"),
		array("520","Hunter","Isaiah","Kai","362570","m","4","01/23/2005","700","10","471","1","8970730830","ray.hunter@gmail.com"),
		array("520","Lampe","Mary","Caitlyn","355921","F","4","08/20/2005","201","10","471","1","2348568415",""),
		array("520","Latu","Anahiva","Kakala","342009","F","4","09/12/2005","399","10","471","1","8567237834",""),
		array("520","Laub-Sabater","Nicolas","William John","338078","M","4","02/18/2005","500","10","471","4","5082379457","phaub@yahoo.com"),
		array("520","Mc Vey","Zachary","Insung","330643","M","4","09/23/2004","700","10","471","1","7359878845","caroraro@hotmail.com"),
		array("520","McDonald","Cory","Alexander James","337650","M","4","05/18/2005","600","10","471","1","7732840634","t4cory@gmail.com"),
		array("520","Murry","Carrie Evangeline","Graves","330631","F","4","11/01/2004","700","10","471","1","7737969183","lgravesmurry@gmail.com"),
		array("520","Olorin","Julianna","Simone","337641","F","4","08/30/2005","700","10","471","1","6628977551","alesiapmessey@gmail.com"),
		array("520","Ortiz","Diego","Sebastian","355920","M","4","03/13/2005","500","10","471","1","1510958957","aortiz1101@yahoo.com"),
		array("520","Overstreet","Julian","Elijah","350429","M","4","12/19/2005","600","10","471","1","3653634330","JahnandChristina@SBCglobal.net"),
		array("520","Padilla","Manuel","Armando","341212","M","4","08/19/2004","500","10","471","1","6194964644",""),
		array("520","Parkinson","Jasper","Davis","358379","m","4","05/22/2005","700","10","471","1","3536174103",""),
		array("520","Pulgarin","Noel","Jesus","355923","M","4","12/25/2004","500","10","471","3","1607735024","mariac@mujeresunidas.net"),
		array("520","Roach","Faith","Nehemyah","270771","F","4","01/17/2005","600","10","471","1","7664175088","tasheenh@yahoo.com"),
		array("520","Robertson","Jamarr","Dashae","341734","M","4","08/14/2005","600","10","471","1","5482169728","caprice2753@yahoo.com"),
		array("520","Robinson","Shea","","355959","M","4","03/17/2006","700","10","471","1","8198746806","robinsonbh@aol.com"),
		array("520","Sanchez","Alexis","","337884","M","4","03/09/2005","500","10","471","3","6624334585",""),
		array("520","Sanchez","Jesus","Alberto","337883","M","4","05/31/2005","500","10","471","3","7279044748",""),
		array("520","Stratton","Jonah","Anthony","355822","M","4","01/10/2005","700","10","471","1","6797664977","kaciestratton@gmail.com"),
		array("520","Wysinger","Armon","Omega-King","354770","M","4","08/14/2005","600","10","471","1","8022586792",""),
		array("520","Zeng","Zhi","Hao","363811","m","4","08/07/2004","201","10","471","3","2196477685","chatzeng@msn.com"),
		array("520","Barbe-Yochelson","Holle","Erika-Shiri","355828","M","3","12/25/2005","700","11","466","1","1045359764","troy.yochelson@gmail.com"),
		array("520","Bautista","Payton","Ninalga","362564","m","3","05/02/2006","400","11","466","1","8290955362","ninachalene.ninalga@gmail.com"),
		array("520","Benitez-Edwards","Lulah","","274173","F","2","07/31/2007","600","11","466","1","9325708391",""),
		array("520","Buzick","Dagen","Cypress","276786","M","1","07/27/2008","700","11","466","1","1735600904",""),
		array("520","Chung","Ashia","Aileen","344984","F","3","07/19/2006","500","11","466","1","1606299873","angelique.puckett@gmail.com"),
		array("520","Crocker","Dorian","Roy","349771","M","2","10/16/2007","700","11","466","1","2037624412",""),
		array("520","Crockett","Nassir","Jaylen","343126","M","3","05/23/2006","600","11","466","1","9638428151","tiffcrocket@yahoo.com"),
		array("520","Dibble","Jasper","Ellis Hull","350215","M","2","03/24/2007","700","11","466","1","5034173747","danahull@gmail.com"),
		array("520","Drees","Elijah","William Martin","345609","M","3","12/23/2005","700","11","466","1","6183255669",""),
		array("520","Harmon","Tuolu","","351117","M","2","08/16/2007","202","11","466","4","6187128851","urbanmontessori@daishi.fastmail.fm"),
		array("520","Hubbard","Royal","Camron","355881","M","3","05/28/2006","600","11","466","1","1593897816","twinsisterlena@yahoo.com (personal)"),
		array("520","Hurst","Jahanara","Marie Renee","355805","F","1","05/15/2007","100","11","466","1","6276357674","ddarby24@aol.com"),
		array("520","Johnson-Booth","Holden","Edward","361802","M","1","05/05/2008","700","11","466","1","4673891158","mslindaj@gmail.com/tod.booth@gmail.com"),
		array("520","Kaba","Fatoumata","","346604","F","3","06/25/2006","600","11","466","3","3952987143","mariamdiarra76@yahoo.com"),
		array("520","Komery","Mateo","Cordova","351866","M","3","10/07/2006","400","11","466","1","8324309564","lkomery@yahoo.com"),
		array("520","McMahon","Declan","James","355814","M","2","10/20/2007","700","11","466","1","6021301667","jillehartman@yahoo.com"),
		array("520","McVey","Alina","Lee","362557","f","2","02/13/2007","700","11","466","1","5561998413",""),
		array("520","Orterry","Chance","Ryan","351901","M","2","10/02/2006","700","11","466","1","5673737788","cateysears@yahoo.com"),
		array("520","Osejo Calderon","Cristian","","350396","M","2","04/28/2007","500","11","466","3","3923200945","osejo415@yahoo.com"),
		array("520","Parkinson","Adeline","Rose","361827","F","1","11/29/2007","999","11","466","1","4983990107","gwparkinson@gmail.com"),
		array("520","Parkinson","Benjamin","Elliot","361828","M","1","11/29/2007","999","11","466","1","2075023506","gwparkinson@gmail.com"),
		array("520","Phillips","Robert","Samuel","362887","m","1","08/12/2007","999","11","466","1","1470630148","hpaulahernandez@aol.com"),
		array("520","Robinson","Verda","Elexis Annamae","272503","F","3","04/19/2006","600","11","466","1","6210360876",""),
		array("520","Townsend","Ana","Helen","337553","F","3","02/18/2005","700","11","466","1","5049778577","curlymoon6@yahoo.com"),
		array("520","Witham","Dominic","Price","352237","M","2","08/09/2007","700","11","466","1","9318026395","karenwitham@hotmail.com"),
		array("520","Wong","Summer","Gillies Hsia","338764","F","3","04/12/2006","201","11","466","1","7620329788","anna_m_johnson@yahoo.com"),
		array("520","Wu","Leon","Kabo","362566","m","3","06/01/2006","999","11","466","3","7262449097","dalia.liang@gmail.com"),
		array("520","Alexander","Izel","Tiya","369142","F","0","09/24/2009","700","16","464","1","8657059274","yonicko80@gmail.com/ dacialarae@gmail.com"),
		array("520","Bernal","Natalie","","368448","F","0","07/12/2009","500","16","464","5","3130702277",""),
		array("520","Brown","Grayson","Amir","363087","M","0","01/30/2009","600","16","464","1","5495419743","LRT4fun@yahoo.com"),
		array("520","Brown","Lukas","Mattias","368451","M","0","05/16/2009","700","16","464","1","8928524002",""),
		array("520","Carter","Max","Danger","365509","M","0","04/14/2009","700","16","464","1","7366369320",""),
		array("520","Flemming","Jonas","","368456","M","0","03/06/2009","600","16","464","1","2986174372",""),
		array("520","Hall","Nathaniel","James","352601","M","0","12/24/2008","700","16","464","1","7143867595",""),
		array("520","Harmon","Inyo","","368457","M","0","07/20/2009","202","16","464","5","1348103272",""),
		array("520","Hoffer","Talya","Claire","365667","F","0","09/15/2009","700","16","464","5","9381125092",""),
		array("520","Hope","Althea","","364964","F","0","05/27/2009","700","16","464","1","3662526598",""),
		array("520","Houwse","Aarronae","Ny'Zearra","368461","F","0","04/16/2009","600","16","464","1","8279009616",""),
		array("520","Jacobs","Troy","","368460","M","0","12/10/2008","500","16","464","1","3010098363",""),
		array("520","Lainez Escalante","Alexa","Mariah","360423","F","0","11/30/2008","500","16","464","3","6397244943","Evelinescalante76@gmail.com"),
		array("520","Mondragon Guzman","Leonardo","Abraham","360476","M","0","06/09/2009","500","16","464","5","9726556500",""),
		array("520","Nguyen","Quynh-Ly","","368463","F","0","10/19/2009","204","16","464","1","6983942040",""),
		array("520","Overstreet","Cameron","","368468","M","0","04/18/2009","600","16","464","1","3938739017",""),
		array("520","Peele","Abigail","Sayre","364621","F","0","05/04/2009","700","16","464","5","2685324701",""),
		array("520","Peele","Isabel","Kib","364627","F","0","05/04/2009","700","16","464","1","5432613243",""),
		array("520","Perales Acosta","Omar","Alonso","277787","M","0","08/13/2009","500","16","464","5","9673968611",""),
		array("520","Pirker moreira","Xiomar","","368469","M","0","07/15/2009","700","16","464","5","9714681898",""),
		array("520","Richards","Savannah","","366493","f","0","11/13/2008","700","16","464","1","7324906770","pamrichards69@gmail.com"),
		array("520","Rodriguez Hernandez","Kirbby","","277875","M","0","08/07/2009","500","16","464","5","8134055043",""),
		array("520","Ryan","Tova","Finch","364980","F","0","09/23/2009","700","16","464","1","7975479330",""),
		array("520","Sebhat","Sheden","","368915","M","0","01/10/2009","600","16","464","5","7516820880",""),
		array("520","Shiga","Skye","Gladding","368474","F","0","12/11/2008","700","16","464","5","8396998097",""),
		array("520","Sklar","Levi","Bay","368477","M","0","07/12/2009","700","16","464","1","8127467447",""),
		array("520","Taylor Collins","Samantha","James","364241","F","0","09/22/2009","700","16","464","1","5714424235","rhondadawncollins@gmail.com"),
		array("520","Valverde","Toussaint","Trac Turner","365138","M","0","04/13/2009","500","16","464","1","1157819253",""),
		array("520","Volpe","James","Edward","365448","M","0","01/10/2009","700","16","464","1","4158419005",""),
		array("520","Weldemichael","Hermela","Menghisteab","364710","F","0","08/06/2009","600","16","464","5","9356330114",""),
		array("520","Zavala","Braulio","","368485","M","0","10/03/2009","500","16","464","1","7982319971",""),
		array("520","Anthony","Miriam","Diane","352344","F","2","07/09/2007","700","17","469","1","7081323118","sabinaholber@gmail.com"),
		array("520","Bristol","Aaron","Michael","345073","M","3","03/02/2006","600","17","469","1","6130116718","kate@kbristol.com"),
		array("520","Burnett","William","Alexander","345303","M","3","10/12/2006","600","17","469","1","5430793724","bear.burnett@gmail.com"),
		array("520","de Oro","Naomi","Gabriella Camarena","366983","f","3","12/16/2005","500","17","469","1","9793291559","deorojackie@gmail.com"),
		array("520","Forder","Kiowa","Rose","358105","F","1","12/29/2007","700","17","469","1","8005056704","jennaforder@gmail.com"),
		array("520","Gore-Perez","Maximilian","Cosmo","364492","M","2","08/26/2007","700","17","469","1","8471283377","arielgore@earthlink.net"),
		array("520","Haghighi","Azam","Marie","361801","F","1","09/11/2008","500","17","469","3","7031534152","amyhaghighi@aol.com"),
		array("520","Harrison","Liliana","Sofia","355847","F","3","05/26/2006","700","17","469","1","7143616214","sendjasonmail@gmail.com"),
		array("520","Henry","Niavayah","Ameeriah","363205","F","1","06/02/2008","600","17","469","1","9671058112",""),
		array("520","Hernandez","David","","274756","M","2","09/26/2007","500","17","469","3","4638767128",""),
		array("520","Johns","Tohaana","Sgarlata","360025","F","1","02/12/2008","100","17","469","1","5099375790","wahleah@gmail.com / billyparish@gmail.com"),
		array("520","Krueger","Avery","Riordan","365021","M","3","01/25/2006","700","17","469","1","4261502924","dorinda.grandbois@gmail.com"),
		array("520","Middle","Nicholas","John Loveless","358390","M","1","12/18/2007","700","17","469","1","3240873257","annette500watts@yahoo.com"),
		array("520","Musson","Malia","Rae","345686","F","3","02/03/2006","301","17","469","1","3584796996","jrmusson@hotmail.com"),
		array("520","Perez Heredia","Jonathan","Samuel","370013","m","1","06/20/2008","500","17","469","5","8220436615","samuel_800@yahoo.com / lyla_heredia_620@yahoo.com"),
		array("520","Ranck-Ross","Dayne","Asher","364192","M","2","05/16/2007","999","17","469","1","7520212765","swttpie22@yahoo.com"),
		array("520","Randle","Elijah Baraka","Michel-Henri Lacocque","368926","M","2","04/20/2007","700","17","469","1","5226902280","chris@ocaclinic.com"),
		array("520","Romero","Darian","Avery","361841","M","1","04/28/2008","999","17","469","1","4253355066","sidheknoll@gmail.com"),
		array("520","Rubio-Castillo","Betzabeth","Jazmin","350836","F","2","04/08/2007","500","17","469","3","5253925241","rubio1920@att.net"),
		array("520","Sarria","Diego","Horacio","357508","M","1","03/11/2008","500","17","469","3","3494542061",""),
		array("520","Sharma","Yash","","344830","M","3","10/11/2006","201","17","469","1","2732036095",""),
		array("520","Sklar","Elijah","Jude","364235","M","1","09/28/2007","700","17","469","1","5604277465","rachel@viaparenting.com"),
		array("520","St. James","Randolph","","362204","m","1","02/27/2008","700","17","469","1","1161413076","beckacriss@gmail.com"),
		array("520","Thomas","Hana","Carol","355924","F","3","02/06/2006","203","17","469","1","6345147341","hthomas@efcps.net"),
		array("520","Upton","Ona","Grace","364223","F","1","11/04/2008","700","17","469","1","3774274290","martha.lyman@gmail.com"),
		array("520","Volpe","Evan","Rose","351450","F","2","11/01/2006","700","17","469","1","5414694783","jason.volpe@am.jll.com"),
		array("520","Weyhmiller","Mathias","Isaac","358337","M","1","05/30/2008","500","17","469","1","6099453854","madcela.gonzalez@gmail.com"),
		array("520","Whitlock","Reina","Louise","356954","F","1","01/14/2008","600","17","469","1","5161201964","azwhitlock@gamil.com"),
		array("520","Yanez","Aiden","James","360376","M","1","03/21/2008","500","17","469","1","7545116867",""),
		array("520","Balestreri","Joseph","John","364791","M","0","02/10/2009","700","20","465","1","3973776647","laurabug@yahoo.com"),
		array("520","Bentley Tammero","Finley","Lenore","365573","F","0","12/02/2008","700","20","465","1","5337047946","loren.bentley@gmail.com"),
		array("520","Crawford","Johnathon","Londen-Darnell","367378","M","0","12/18/2008","600","20","465","1","5239056396",""),
		array("520","Dahl","Shae","Olivia","358831","F","0","08/13/2008","700","20","465","1","8365974928","alindsay22@yahoo.com"),
		array("520","Dean","Calliope","Rose","364653","F","0","03/25/2009","700","20","465","1","2381161241",""),
		array("520","DeSantis","Shamus","Charlie","368453","M","0","07/02/2009","700","20","465","1","6541024241",""),
		array("520","Desmond","Dekker","Thomas","364349","M","0","02/21/2009","700","20","465","1","1749350"),
		array("520","Douglas","Roland","","364954","M","0","12/19/2008","500","20","465","1","9933427394","marksearch@sbcglobal.net"),
		array("520","Duros","Cadence","Elizabeth","366394","F","0","09/30/2009","700","20","465","1","5246606305",""),
		array("520","Fitsum","Heran","Tsegai","368917","F","0","11/03/2008","600","20","465","5","8129856418",""),
		array("520","Florence","Samuel","David","366640","M","0","02/28/2009","700","20","465","1","1341289080",""),
		array("520","Ha","Lucas","Tran","365827","M","0","10/07/2009","399","20","465","1","9900259740",""),
		array("520","Hernandez-Romero","Mia","Guadalupe","365245","F","0","10/16/2009","500","20","465","5","7790906779",""),
		array("520","Kemnitz","Mackenzie","Skye","365886","F","0","05/25/2009","700","20","465","1","5072979464",""),
		array("520","Khadir","Xavier","Ayoub","357291","M","0","10/03/2008","500","20","465","1","4095304650","jkhadir@gmail.com"),
		array("520","Lopez","Marco","Octavio","277599","M","0","05/16/2009","500","20","465","5","4080862912","eneidavm@hotmail.com"),
		array("520","Martin","Roan","Hendrix","356799","M","0","04/06/2009","700","20","465","1","5465613115",""),
		array("520","Mendez-Knox","Dominic","Elijah","368464","M","0","12/16/2008","500","20","465","1","1723982128",""),
		array("520","Mondragon Guzman","Jeremias","Alejandro","360478","M","0","06/09/2009","500","20","465","5","5286699573",""),
		array("520","Montraix","Colette","","368467","F","0","06/17/2009","700","20","465","1","1636582263",""),
		array("520","Murray","Joaquin","Elliott","367020","M","0","11/23/2008","500","20","465","5","1687561672",""),
		array("520","Ortiz","Cesar","Krishna","368465","M","0","11/08/2009","500","20","465","5","9938622791",""),
		array("520","Osejo Calderon","Daniel","","368466","M","0","11/20/2009","500","20","465","5","2219157933",""),
		array("520","Randle","Aliya","Louise","368471","F","0","10/06/2008","600","20","465","1","2566050377",""),
		array("520","Richardson","Tristyn","Ryan-William","368472","M","0","06/02/2009","600","20","465","1","4315264470",""),
		array("520","Santiago","Ada","Rae","365068","F","0","12/09/2008","700","20","465","1","7123652357",""),
		array("520","Shaheed","Zuri","Mizaan","364856","F","0","07/11/2009","600","20","465","1","6402094503",""),
		array("520","Sharma","Jia","","365586","F","0","03/15/2009","299","20","465","1","1281838893",""),
		array("520","Taylor Collins","Abigail","Olena","364240","F","0","09/22/2009","700","20","465","1","9114840268","rhondadawncollins@gmail.com"),
		array("520","West","Simone","Murphy","365429","F","0","03/28/2009","700","20","465","1","3458509986","jennifer.murphy7778@gmail.com"),
		array("520","Whitlock","Robert","","368482","M","0","05/12/2009","600","20","465","1","8695877938",""),
		array("520","Witham","Genevieve","Price","368484","F","0","02/22/2009","700","20","465","1","6916490594",""));

		$this->load->model('User_model');
		$this->load->model('Group_model');

		foreach($students as $student)
		{
			list($school, $last_name, $first_name, $middle_name, $id, $gender, $grade, $birthdate, $ethnicity, $teacher_id, $group_id, $lang, $state, $parent) = $student;

			$dob = date('Y-m-d', strtotime($birthdate));

			$user_id = $this->User_model->create_student($school_id, $first_name, $last_name, '', '', '', 'blobsmall.png', $grade, $id, $gender, $dob, $ethnicity);
			
			$this->Group_model->add_student($group_id, $user_id);
		}


	}

	function fix1130update()
	{
		$this->load->model('Settings_model');
		$query = $this->db->query('SELECT schoolid FROM Schools');
		$schools = $query->result();

		foreach($schools as $school)
		{
			$school_id = $school->schoolid;

			$bully_form_data = array(
				'questions' => array(
					array(
						'type' => 'textarea',
						'id' => '88511e39d14788aca450f7668dc0ef9a',
						'label' => 'Name of Bully',
						'placeholder' => ''
					),
					array(
						'type' => 'textarea',
						'id' => '93cd4161135be7a6692e01ddd580cf58',
						'label' => 'Name of Victim',
						'placeholder' => ''
					),
					array(
						'type' => 'textarea',
						'id' => '39523836c294e365e60a189aec21fa2c',
						'label' => 'Name of Witnesses',
						'placeholder' => ''
					),
					array (
						'type' => 'multicheckbox',
						'id' => '43b2dc9c7d22f5f47c0dd3df4c3b72e5',
						'label' => 'Type of Bullying',
						'options' => array(
							'Called Mean names',
							'Kicked',
							'Punched',
							'Threatened',
							'Told Lies',
							'Racial Comments',
							'False Rumors',
							'Sexual Comments',
							'Took/Damaged Possessions'
						),
					),
					array(
						'type' => 'textarea',
						'id' => 'b1c9ae65aab8240f5c9f7d9b243cd18a',
						'label' => 'Where did this happen',
						'placeholder' => ''
					),
					array( 'type' => 'textarea',
						'id' => '4705756a4bc04ef30fd237f347f792cb',
						'label' => 'Describe What happened',
						'placeholder' => ''
					),
					array(
						'type' => 'textarea',
						'id' => '124318193ae08177c87b7a93ac727269',
						'label' => 'When did this happen?',
						'placeholder' => ''
					),
					array (
						'type' => 'textarea',
						'id' => '8e7d99dd0d95ec77d495f44beef5168f',
						'label' => 'How long has this been going on for?',
						'placeholder' => ''
					),
				),
			);

			$this->load->model('Form_model');
			$bully_form_id = $this->Form_model->create($school_id, 'Bully Report', 'ast', 'st', '', $bully_form_data, array(), 'Name of Bully');

			$admin_menu = array(
				'/detention/mystudents' => 'Today\'s Detentions',
				'/admin/reports' => 'Reports',
				'/admin/interventions' => 'Interventions',
				'/demerit/school' => 'Demerits',
				'/referral/school' => 'Referrals'
			);

			$this->Settings_model->save($school_id, 'dashadmin', array('menu' => $admin_menu));

			$student_menu = array(
				'/student/awards' => 'My Classroom Bucks',
				'/student/mydetentions' => 'My Detentions',
				'/student/shoutouts' => 'Shout-outs!',
				'/student/interventions' => 'My Interventions',
				'/demerit/mine' => 'My Demerits',
				'/referral/mine' => 'My Referrals',
				'/form/respond/'.$bully_form_id => 'Report Bullying',
				'/reflections/mine' => 'My Reflections'
			);

			$this->Settings_model->save($school_id, 'dashstudent', array('menu' => $student_menu));

			$teacher_menu = array(
				'/teacher/reports' => 'Reports',
				'/detention/mystudents' => 'Today\'s Detentions',
				'/teacher/interventions' => 'Interventions',
				'/demerit/mine' => 'Demerits',
				'/referral/mine' => 'Referrals',
				'/form/respond/'.$bully_form_id => 'Report Bullying',
			);

			$this->Settings_model->save($school_id, 'dashteacher', array('menu' => $teacher_menu));
		}
	}
}

?>