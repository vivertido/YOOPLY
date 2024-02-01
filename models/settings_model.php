<?php

class Settings_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function save($school_id, $type, $settings)
	{
		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'type' => $type,
			'settings' => json_encode($settings),
			'timecreated' => time()
		));
	}

	function get_settings($school_id, $type)
	{
		$query = $this->db->query("SELECT settings FROM Settings WHERE schoolid = ? AND type = ? ORDER BY settingsid DESC LIMIT 1", array($school_id, $type));
		return $query->row()->settings;
	}

	function get_forms($school_id)
	{
		$query = $this->db->query('SELECT s.* FROM (SELECT * FROM Settings WHERE schoolid = ? AND type LIKE "form%" ORDER BY settingsid DESC) s GROUP BY s.type', array($school_id));
		return $query->result();
	}

	function insert_default($school_id)
	{
		$hall_passes = array('reasons' =>
			array(
				'Bathroom',
				'Help Teacher',
				'Deliver Message',
				'Illness',
				'Office Call',
				'Think Time',
				'Buddy Teacher',
				'Teacher Request',
				'Counselor',
				'School Services',
				'Other',
			),
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'hallpasses',
			'settings' => json_encode($hall_passes)
		));

		//************************************************************************//

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
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'statuses',
			'settings' => json_encode($statuses)
		));

		//************************************************************************//

		$avatars = array('avatars' =>
			array(
				'blobsmall',
				'batgirlsmall',
				'bluebirdsmall',
				'bombsmall',
				'cavemansmall',
				'clonebotsmall',
				'eyeballzsmall',
				'grayninjasmall',
				'greeneyesmall',
				'greenninjasmall',
				'heatsmall',
				'jawssmall',
				'jokersmall',
				'kimonosmall',
				'kittysmall',
				'luchadorasmall',
				'luchadorsmall',
				'mushroomsmall',
				'peacesmall',
				'princesssmall',
				'rocketshipsmall',
				'skeletonsmall',
				'spacemonkeysmall',
				'strawberrysmall',
				'teddysmall',
				'vampiresmall',
				'vikingsmall',
				'zombiesmall',
			),
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'avatars',
			'settings' => json_encode($avatars)
		));

		$demerit_options = array(
			'Late',
			'Willful Defiance',
			'Disrespect',
			'Late',
			'Chewing Gum',
			'Off Task',
			'Not Following Directions',
			'Fighting',
			'Using inappropriate language',
			'Harassment of Peer',
			'Unsafe Behavior',
			'Disruptive Behavior',
			'Needs Attention',
			'Serious Problem (Unknown)',
		);

		$demerits = array(
			'demeritlabel' => 'Demerit',
			'demerits' => $demerit_options,
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'demerits',
			'settings' => json_encode($demerits)
		));

		$consequences = array (
			'Loss of Privilege',
			'Detention After School',
			'Lunch Detention',
			'Parent Shadow',
			'Community Service',
			'Restorative Justice Circle',
			'In-school Suspension',
			'Suspension',
			'Parent Conference',
			'Other (see below)',
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'consequences',
			'settings' => json_encode($consequences)
		));


		$admin_review = array(
			'reasons' => array (
				'Disruption/willful defiance',
				'Threats',
				'Fighting',
				'Weapon/Dangerous Object',
				'Drug/Alcohol Use or Possession',
				'Drug/Alcohol Sales',
				'Vandalism',
				'Tobacco Use or Possession',
				'Drug Paraphernalia',
				'Receiving Stolen Property',
				'Possession of Imitation Firearm',
				'Bullying',
			),
			'consequences' => $consequences,
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'adminreview',
			'settings' => json_encode($admin_review)
		));

		$shoutout = array (
			'messages' => 	array (
				array (
					'title' => 'Good job!',
					'options' =>
					array (
						'I think you did a great job today!',
						'You Rocked it today',
						'Awesome stuff!',
						'You did an awesome job!',
					),
				),
				array (
					'title' => 'Thanks!',
					'options' =>
					array (
						'Thanks for helping me out',
						'Appreciate what you did today',
						'Good looking out for me',
						'I owe you one',
					),
				),
				array (
					'title' => 'Leader',
					'options' =>
					array (
						'Way to take charge',
						'You showed me something new today!',
						'We are lost without you!',
						'You never quit!',
					),
				),
			),
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'shoutout',
			'settings' => json_encode($shoutout)
		));

		$features = array(
			"detentions" => "ats",
			"referrals" => "ats",
			"demerits" => "ats",
			"interventions" => "ats",
			"reinforcements" => "ats",
			"shoutouts" => "ats",
			"bullying" => "ats",
			"goals" => "ats"
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'features',
			'settings' => json_encode($features)
		));		

		$incident = array ('questions' =>
			array ('easy' =>
				array (
					array (
						'type' => 'personsearch',
						'label' => 'Who was involved',
						'id' => '982a1b79424db380c15165316f780187',
						'multiple' => 'true',
					),
					array (
						'type' => 'select',
						'label' => 'Where did this happen?',
						'id' => '982a1b79424db380c15165316f780186',
						'options' => array (
							'In Class',
							'Hallway',
							'Yard',
							'Cafeteria',
							'Bathroom',
							'Off school grounds',
						),
					),
					array (
						'type' => 'controlgroup',
						'label' => 'Why do you think you got a referral?',
						'id' => '9de28a9fc862b75d955bbb6df808269a',
						'elements' => array (
							array (
								'type' => 'collapsible',
								'id' => '594a09c56a1cb65542e4b415d76e75cf',
								'label' => 'Class behavior',
								'elements' => array (
									array (
										'type' => 'multicheckbox',
										'id' => '235f00464f862e83566e736dd40a7318',
										'label' => '',
										'options' => array (
											'I was arguing',
											'Someone was talking to me',
											'I was talking to someone',
											'I was messing around',
											'I wasn\'t doing my work',
											'I didn\'t follow instructions',
											'I was out of my seat',
											'I was late to class',
											'I was being disruptive',
											'I was making too much noise',
											'I don\'t know why I\'m here',
										),
									),
								),
							),
							array (
								'type' => 'collapsible',
								'id' => '2bef2f2e8db2710f9025dc185c691d95',
								'label' => 'Yard behavior',
								'elements' => array (
									array (
										'type' => 'multicheckbox',
										'id' => 'd2e51000158d0cc56f0fd672d61bdc5f',
										'label' => '',
										'options' => array (
											'I hit someone',
											'Someone hit me',
											'I was playing around',
											'I wasn\'t following the rules',
											'I didn\'t listen to the yard supervisor',
											'I was playing rough',
											'I was pushing someone',
											'I was saying innapropriate things',
											'I was just helping my friend',
											'I didn\'t do anything wrong',
											'I don\'t know why I\'m here',
										),
									),
								),
							),
							array (
								'type' => 'collapsible',
								'id' => '85fb9a20bd56b478704602e56d19ae68',
								'label' => 'Other Reason',
								'elements' => array (
									array (
										'type' => 'textarea',
										'id' => '0a02531aa337957c6cac98682ef2ad36',
										'label' => 'Do you have another reason',
										'placeholder' => '',
									),
								),
							),
						),
					),
				),
				'detailed' => array (
					array (
						'type' => 'textarea',
						'id' => '1a8a7708f4cbcf421e52d37190cbdb22',
						'label' => 'Describe what happened in more detail',
						'placeholder' => '',
					),
					array (
						'type' => 'textarea',
						'id' => 'eaf5241071a822c0815c57841b10c5fa',
						'label' => 'Who else was involved? (include first and last names)',
						'placeholder' => '',
					),
					array (
						'type' => 'textarea',
						'id' => '514402a7bf2b02ddfabdf4a183637092',
						'label' => 'Anything else about where it happened?',
						'placeholder' => '',
					),
					array (
						'type' => 'textarea',
						'id' => 'fc1858e766ad35ccd1e3aaa0766a7fea',
						'label' => 'Other:',
						'placeholder' => '',
					),
					array (
						'type' => 'textarea',
						'id' => 'c42cf1a1bc387e69af5a9d8ad9225882',
						'label' => 'Are there any witnesses?',
						'placeholder' => '',
					),
				),
			),
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'incident',
			'settings' => json_encode($incident)
		));
		
		$labels = array(
			'demerit' => 'Behavior Incident',
			'demerits' => 'Behavior Incidents',
			'reinforcements' => 'Scholar Bucks',
			'reinforcement' => 'Scholar Buck',
			'referrals' => 'Referrals',
			'referral' => 'Referral',
			'detentions' => 'Detentions',
			'detention' => 'Detention',
			'interventions' => 'Interventions',
			'intervention' => 'Intervention',
			'detentionunit' => 'minute',
			'detentionunits' => 'minutes'
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'labels',
			'settings' => json_encode($labels)
		));

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

		$CI =& get_instance();
		$CI->load->model('Form_model');
		$bully_form_id = $this->Form_model->create($school_id, 'Bully Report', 'ast', 'st', '', $bully_form_data, array(), 'Name of Bully', '');
		$admin_menu = array(
			'/detention/mystudents' => 'Today\'s Detentions',
			'/report' => 'Reports',
			'/intervention/school' => 'Interventions',
			'/demerit/school' => 'Demerits',
			'/referral/school' => 'Referrals'
		);

		$this->save($school_id, 'dashadmin', array('menu' => $admin_menu));

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

		$this->save($school_id, 'dashstudent', array('menu' => $student_menu));

		$teacher_menu = array( 
			'/report' => 'Reports', 
			'/detention/mystudents' => 'Today\'s Detentions', 
			'/intervention/mine' => 'Interventions', 
			'/demerit/mine' => 'Demerits', 
			'/referral/mine' => 'Referrals',
			'/form/respond/'.$bully_form_id => 'Report Bullying', 
		);

		$this->save($school_id, 'dashteacher', array('menu' => $teacher_menu));

		$reflection = array (
			'questions' => array (
				array (
					'id' => 'e1b778b349798260a4cb8de37f1263b6',
					'type' => 'instruction',
					'text' => 'In the space below explain what happened from your point of view. Try to answer all the questions as best as possible. They are only meant to help you think about this problem in a new way.',
				),
				array (
					'id' => '20ca237252242ee896720bdf07da9880',
					'type' => 'textarea',
					'label' => 'My side of the story',
					'placeholder' => 'It all began...',
				),
				array (
					'id' => '95133e39c893ff3ac62b126dc645d1cd',
					'type' => 'textarea',
					'label' => 'What would the other person or persons say was the problem?',
					'placeholder' => '',
				),
				array (
					'id' => '0b82c87ccc2c304d0ff0f9c0103972f8',
					'type' => 'textarea',
					'label' => 'What other choices could you have made?',
					'placeholder' => '',
				),
				array (
					'id' => '0d1d9cdd42a70771590aa811a4042d7a',
					'type' => 'textarea',
					'label' => 'What led up to this problem?',
					'placeholder' => '',
				),
				array (
					'id' => 'ad94691c31b8a5882f3f82a489f81d3c',
					'type' => 'textarea',
					'label' => 'Does this happen often? Please explain.',
					'placeholder' => '',
				),
				array (
					'id' => '2185570da40b6106f0af3ad6d273ca17',
					'type' => 'textarea',
					'label' => 'How does this affect your learning?',
					'placeholder' => '',
				),
				array (
					'type' => 'collapsible',
					'label' => 'How do you feel about what happend?',
					'elements' => array (
						array (
							'id' => '9ad4bb07b09cf51288668d42e13a41e8',
							'label' => 'How do you feel about what happend?',
							'type' => 'multicheckbox',
							'options' => array (
								'Upset',
								'Like it\'s not my fault',
								'Angry',
								'I don\'t care',
								'Worried',
								'Fine',
								'Not sure',
								'Glad',
								'A bit scared',
								'I think it\'s funny',
								'Worried about the other person',
								'A little sorry',
								'Really Sorry',
								'Other (see below)',
							),
						),
						array (
							'id' => '6311ae17c1ee52b36e68aaf4ad066387',
							'type' => 'text',
							'label' => 'Other',
							'placeholder' => 'I feel...',
						),
						array (
							'id' => 'db87d14b344cd36bc0c535ab3c941794',
							'type' => 'textarea',
							'label' => 'Is there something else you want to say about how you feel?',
							'placeholder' => 'I think...',
						),
					),
				),
			),
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'reflection',
			'settings' => json_encode($reflection)
		));

		//************************************************************************//
		$referral_options = array (
			'Disrespectful',
			'Unsupportive of Others',
			'Unkind comments',
			'Throwing Things',
			'Poor Attitude/Language',
			'Unapproved Tardy',
			'Minor Damage',
			'Major Damage',
			'Incomplete Work',
			'Gum/candy/seeds',
			'Using Mobile',
			'Out of Uniform',
			'Verbal Harassment',
			'Minor Fighting',
			'Major Fighting',
			'Minor Stealing',
			'Major Stealing',
			'Lying',
			'Encouraging Fight',
			'Sexual conduct-mutual',
			'Defiance',
			'Alcohol/Tobacco/Drugs',
			'Knowledge of Substances',
			'Physical Assault',
			'Serious Weapon',
		);

		$referrals = array(
			'questions' => array(
				array(
					'type' => 'select',
					'id' => '124617f0ad4a4ecb40ac0b9dcad97ed9',
					'label' => 'Incident',
					'options' => $referral_options,
				),
				array(
					'type' => 'textarea',
					'id' => 'f4c6f851b00d5518bf888815de279aba',
					'label' => 'Notes',
					'placeholder' => '',
				)
			),
			'keys' => array(
				'incident' => '124617f0ad4a4ecb40ac0b9dcad97ed9',
			)
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'referrals',
			'settings' => json_encode($referrals)
		));

		$reinforcement_options = array(
			'Self-awareness and self-respect',
			'Emotional understanding of self and others',
			'Social competence and constructive peer relationships',
			'Self-control',
			'Empathy and kindness towards others',
			'Problem solving and anger management skills',
			'Respect for individual differences',
			'Healthy living choices',
			'Perseverance and resiliency',
			'Refusal skills',
			'Personal safety',
			'Character traits such as honesty and responsibility',
		);

		$motivations = array(
			'Get Peer Attention',
			'Get Adult Attention',
			'Avoid Peer Attention',
			'Avoid Adult Attention'
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'motivations',
			'settings' => json_encode($motivations)
		));

		$reinforcements = array (
			'reinforcementlabel' => 'Positive',
			'awardlabel' => 'Reinforcements',
			'quantitytype' => 'range',
			'awardamountmin' => '1',
			'awardamountmax' => '10',
			'options' => $reinforcement_options,
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'reinforcements',
			'settings' => json_encode($reinforcements)
		));

		$interventions = array (
			'Non Verbal Warning',
			'Verbal Warning',
			'Seat Change',
			'Time Out',
			'Stop and Reflect',
			'Call home',
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'interventions',
			'settings' => json_encode($interventions)
		));

		$locations = array (
			'Class', 
			'School Yard'
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'locations',
			'settings' => json_encode($locations)
		));

		$quick_entry = array(
			'reinforcements' => $reinforcement_options,
			'negatives' => $demerit_options,
			'detentions' => $referral_options,
			'referrals' => $referral_options,
			'interventions' => $interventions,
			'hallpasses' => $hall_passes['reasons'],
			'reinforcementsoptions' => array(
				'awardlabel' => 'Scholar Dollar',
				'quantitytype' => 'range',
				'awardamountmin' => '1',
				'awardamountmax' => '10'
			),
			'statuses' => array(
				'In class',
				'In bathroom',
				'In office referral',
				'In detention',
				'Absent'
			)
		);

		$this->db->insert('Settings', array(
			'schoolid' => $school_id,
			'timecreated' => time(),
			'type' => 'quickentry',
			'settings' => json_encode($quick_entry)
		));

	}

}

?>