<?php

class User_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function get_user($user_id)
	{
		$query = $this->db->query('SELECT * FROM Users WHERE userid = ?', array($user_id));

		return $query->row();
	}

	function get_users($user_ids)
	{
		$this->db->select('*');
		$this->db->from('Users');
		$this->db->where_in('userid', $user_ids);
		
		return $this->db->get()->result();
	}

	function create_teacher($school_id, $first_name, $last_name, $username, $password, $email, $profile_image)
	{
		$this->db->insert('Users', array(
			'accounttype' => 't',
			'schoolid' => $school_id,
			'firstname' => $first_name,
			'lastname' => $last_name,
			'username' => $username,
			'password' => $password,
			'email' => $email,
			'timecreated' => time(),
			'profileimage' => $profile_image,
			'nonce' => md5(implode('|', array($first_name, $last_name, $email, time())))
		));

		return $this->db->insert_id();
	}

	function create_student($school_id, $first_name, $last_name, $username, $password, $email, $profile_image, $grade, $student_id, $gender, $dob, $ethnicity = '')
	{
		$this->db->insert('Users', array(
			'accounttype' => 's',
			'schoolid' => $school_id,
			'firstname' => $first_name,
			'lastname' => $last_name,
			'username' => $username,
			'password' => $password == '' ? '' : $this->encodepassword($password),
			'email' => $email,
			'timecreated' => time(),
			'profileimage' => $profile_image,
			'grade' => $grade,
			'studentid' => $student_id,
			'dob' => $dob,
			'gender' => $gender,
			'ethnicity' => $ethnicity,
			'nonce' => md5(implode('|', array($first_name, $last_name, $email, time())))
		));

		return $this->db->insert_id();
	}

	function create_admin($school_id, $first_name, $last_name, $username, $password, $email, $profile_image)
	{
		$this->db->insert('Users', array(
			'accounttype' => 'a',
			'schoolid' => $school_id,
			'firstname' => $first_name,
			'lastname' => $last_name,
			'username' => $username,
			'password' => $password,
			'email' => $email,
			'timecreated' => time(),
			'profileimage' => $profile_image,
			'nonce' => md5(implode('|', array($first_name, $last_name, $email, time())))
		));

		return $this->db->insert_id();
	}

	function create_parent($school_id, $first_name, $last_name, $username, $password, $email, $phone, $profile_image)
	{
		$this->db->insert('Users', array(
			'accounttype' => 'p',
			'schoolid' => $school_id,
			'firstname' => $first_name,
			'lastname' => $last_name,
			'username' => $username,
			'password' => $password,
			'email' => $email,
			'phone' => $phone,
			'timecreated' => time(),
			'profileimage' => $profile_image,
			'nonce' => md5(implode('|', array($first_name, $last_name, $email, time())))
		));

		return $this->db->insert_id();
	}

	function get_from_group($group_id)
	{
		$query = $this->db->query('SELECT Users.* FROM Users, UserGroup WHERE UserGroup.groupid = ? AND UserGroup.userid = Users.userid AND Users.accounttype = "s" AND UserGroup.status = ? ORDER BY lastname, firstname', array($group_id, USERGROUP_STATUS_ACTIVE));

		return $query->result();
	}

	function get_unassigned($school_id, $account_type = '', $start = -1, $limit = 0)
	{
		$where = 'u.schoolid = ? AND ug.groupid IS NULL'; 
		$where_values = array(USERGROUP_STATUS_ACTIVE, $school_id);

		if(!empty($account_type))
		{
			$where .= ' AND u.accounttype = ?';
			array_push($where_values, $account_type);
		}

		$where .= ' ORDER BY u.lastname, u.firstname';
		if($limit > 0)
		{
			$where .= ($start >= 0) ? ' LIMIT '.$start.','.$limit : ' LIMIT '.$limit;
		}
		
		$query = $this->db->query('SELECT u.* FROM Users u LEFT JOIN (SELECT * FROM UserGroup WHERE status = ?) ug ON u.userid = ug.userid WHERE '.$where, $where_values);

		return $query->result();
	}

	function count_unassigned($school_id, $account_type = '')
	{
		$where = 'u.schoolid = ? AND ug.groupid IS NULL'; 
		$where_values = array(USERGROUP_STATUS_ACTIVE, $school_id);

		if(!empty($account_type))
		{
			$where .= ' AND u.accounttype = ?';
			array_push($where_values, $account_type);
		}

		$query = $this->db->query('SELECT COUNT(*) as total FROM Users u LEFT JOIN (SELECT * FROM UserGroup WHERE status = ?) ug ON u.userid = ug.userid WHERE '.$where, $where_values);

		return $query->row()->total;
	}

	function get_teachers_from_group($group_id)
	{
		$query = $this->db->query('SELECT DISTINCT Users.* FROM Users, UserGroup WHERE UserGroup.groupid = ? AND UserGroup.userid = Users.userid AND UserGroup.role = ? ORDER BY lastname, firstname', array($group_id, 't'));

		return $query->result();
	}

	function get_students_from_group($group_id)
	{
		$query = $this->db->query('SELECT DISTINCT Users.* FROM Users, UserGroup WHERE UserGroup.groupid = ? AND UserGroup.userid = Users.userid AND UserGroup.role = ? ORDER BY lastname, firstname', array($group_id, 's'));

		return $query->result();
	}

	function get_admins_from_school($school_id)
	{
		$query = $this->db->query('SELECT * FROM Users WHERE Users.schoolid = ? AND Users.accounttype = ? ORDER BY lastname, firstname', array($school_id, 'a'));

		return $query->result();
	}

	function get_students_from_school($school_id, $limit = -1, $offset = -1)
	{
		$query_limit = ($limit > 0) ? ' LIMIT '.$limit : '';
		$query_limit .= ($limit > 0 && $offset >= 0) ? ' OFFSET '.$offset : '';

		$query = $this->db->query('SELECT * FROM Users WHERE Users.schoolid = ? AND Users.accounttype = ? ORDER BY lastname, firstname'.$query_limit, array($school_id, 's'));

		return $query->result();
	}


	function get_teachers_from_school($school_id, $limit = -1, $offset = -1)
	{
		$query_limit = ($limit > 0) ? ' LIMIT '.$limit : '';
		$query_limit .= ($limit > 0 && $offset >= 0) ? ' OFFSET '.$offset : '';

		$query = $this->db->query('SELECT * FROM Users WHERE Users.schoolid = ? AND Users.accounttype = ? ORDER BY lastname, firstname'.$query_limit, array($school_id, 't'));

		return $query->result();
	}

	function count_students_from_school($school_id)
	{
		$query = $this->db->query('SELECT COUNT(*) as total FROM Users WHERE Users.schoolid = ? AND Users.accounttype = ?', array($school_id, 's'));

		return $query->row()->total;
	}


	function get_from_teacher($teacher_id)
	{
		$query = $this->db->query('SELECT Users.*, s.groupid FROM Users, UserGroup t, UserGroup s WHERE t.status = ? AND s.status = ? AND t.userid = ? AND t.groupid = s.groupid AND s.userid = Users.userid AND Users.accounttype = "s" ORDER BY groupid, lastname, firstname', array(USERGROUP_STATUS_ACTIVE, USERGROUP_STATUS_ACTIVE, $teacher_id));

		return $query->result();
	}

	function get_classmates($user_id)
	{
		$query = $this->db->query('SELECT DISTINCT Users.* FROM Users, UserGroup s, UserGroup c WHERE s.status = ? AND c.status = ? AND s.userid = ? AND s.groupid = c.groupid AND c.userid = Users.userid AND c.userid != ? ORDER BY lastname, firstname', array(USERGROUP_STATUS_ACTIVE, USERGROUP_STATUS_ACTIVE, $user_id, $user_id));

		return $query->result();
	}

	function find_students($user_id, $search)
	{
		$this->db->distinct();
		$this->db->select('CONCAT(Users.firstname," ",Users.lastname) as name, Users.userid as studentid', false);
		$this->db->from('Users, UserGroup s, UserGroup c');
		//$this->db->where('Users.accounttype', "s");
		$this->db->where('s.userid', $user_id);
		$this->db->where('s.status', USERGROUP_STATUS_ACTIVE);
		$this->db->where('c.status', USERGROUP_STATUS_ACTIVE);
		$this->db->where('s.groupid = c.groupid AND c.userid = Users.userid');
		$this->db->where('c.userid !=', $user_id);
		$this->db->like('CONCAT(firstname," ",lastname)', $search);
		$this->db->order_by('lastname, firstname');
		$this->db->limit(10);

		return $this->db->get()->result();
	}

	function find_students_in_school($school_id, $search)
	{
		$this->db->distinct();
		$this->db->select('CONCAT(Users.firstname," ",Users.lastname) as name, Users.userid as studentid', false);
		$this->db->from('Users');
		$this->db->where('accounttype', "s");
		$this->db->where('schoolid', $school_id);
		$this->db->like('CONCAT(firstname," ",lastname)', $search);
		$this->db->order_by('lastname, firstname');
		$this->db->limit(10);

		return $this->db->get()->result();
	}

	function find_in_school($school_id, $search)
	{
		$this->db->distinct();
		$this->db->select('CONCAT(Users.firstname," ",Users.lastname) as name, Users.userid, CASE accounttype WHEN "a" THEN "admin" WHEN "t" THEN "teacher" WHEN "s" THEN "student" ELSE "" END as type', false);
		$this->db->from('Users');
		//$this->db->where('accounttype', "s");
		$this->db->where('schoolid', $school_id);
		$this->db->like('CONCAT(firstname," ",lastname)', $search);
		$this->db->order_by('lastname, firstname');
		$this->db->limit(10);

		return $this->db->get()->result();
	}
	function get_school_admins($school_id)
	{
		$query = $this->db->query('SELECT Users.* FROM Users, UserSchool WHERE UserSchool.schoolid = ? AND UserSchool.userid = Users.userid ORDER BY lastname, firstname', array($school_id));

		return $query->result();
	}

	function get_school_staff($school_id)
	{
		$query = $this->db->query('SELECT DISTINCT Users.* FROM Users, UserGroup, Groups WHERE UserGroup.status = ? AND Groups.schoolid = ? AND Groups.groupid = UserGroup.groupid AND UserGroup.userid AND Users.userid AND Users.accounttype IN ("a", "t") ORDER BY lastname, firstname', array(USERGROUP_STATUS_ACTIVE, $school_id));

		return $query->result();

	}

	function update_avatar($user_id, $profile_image)
	{
		$this->db->update('Users', array(
			'profileimage' => $profile_image
		), array('userid' => $user_id));
	}

	function update($user_id, $first_name, $last_name, $email, $grade, $student_id, $gender, $dob, $ethnicity = '', $phone = '')
	{
		$this->db->update('Users', array(
			'firstname' => $first_name,
			'lastname' => $last_name,
			'email' => $email,
			'grade' => $grade,
			'studentid' => $student_id,
			'dob' => $dob,
			'gender' => $gender,
			'ethnicity' => $ethnicity,
			'phone' => $phone
		), array('userid' => $user_id));
	}

	function find_by_email($email)
	{
		$query = $this->db->query('SELECT * FROM Users WHERE email = ?', array($email));

		return $query->row();
	}

	function find_by_username($username)
  {
		$query = $this->db->query('SELECT * FROM Users WHERE username = ?', array($username));
		
		return $query->row();
	}

	function get_overview_from_group($group_id)
	{
		$query = $this->db->query('SELECT Users.*, IF(detentions.total IS NULL, 0, detentions.total) as detentionminutes, IF(reinforcements.total IS NULL, 0, reinforcements.total) as dollars, IF(demerits.total IS NULL, 0, demerits.total) as negativepoints, IF(statuses.value IS NULL, "", statuses.value) as statusvalue FROM UserGroup, Users LEFT JOIN (SELECT SUM(minutes) total, studentid FROM Detentions WHERE status = 1 GROUP BY studentid) detentions ON Users.userid = detentions.studentid LEFT JOIN (SELECT SUM(quantity) as total, studentid FROM Reinforcements WHERE status = 1 GROUP BY studentid) reinforcements ON Users.userid = reinforcements.studentid LEFT JOIN (SELECT COUNT(*) as total, studentid FROM Demerits WHERE status = 1 GROUP BY studentid) demerits ON Users.userid = demerits.studentid LEFT JOIN (SELECT s.value, s.studentid FROM Statuses s INNER JOIN (SELECT studentid, MAX(timecreated) time FROM Statuses GROUP BY studentid) t ON t.studentid = s.studentid AND t.time = s.timecreated) statuses ON Users.userid = statuses.studentid WHERE UserGroup.groupid = ? AND UserGroup.status = ? AND UserGroup.userid = Users.userid AND Users.accounttype = "s" ORDER BY lastname, firstname', array($group_id, USERGROUP_STATUS_ACTIVE));

		return $query->result();
	}

	function has_teacher($student_id, $teacher_id)
	{
		$query = $this->db->query('SELECT * FROM UserGroup s, UserGroup t WHERE s.status = ? AND t.status = ? AND s.userid = ? AND s.role = "s" AND s.groupid = t.groupid AND t.userid = ? AND t.role = "t"', array(USERGROUP_STATUS_ACTIVE, USERGROUP_STATUS_ACTIVE, $student_id, $teacher_id));
		$row = $query->row();

		return !empty($row);
	}

	function has_parent($student_id, $parent_id)
	{
		$query = $this->db->query('SELECT * FROM UserParent WHERE studentid = ? AND parentid = ? AND status = 1', array($student_id, $parent_id));
		$row = $query->row();

		return !empty($row);
	}

	function add_parent($student_id, $parent_id)
	{
		$this->db->insert('UserParent', array(
			'studentid' => $student_id, 
			'parentid' => $parent_id, 
			'status' => '1'
		));
	}

	function remove_parent($student_id, $parent_id)
	{
		$this->db->update('UserParent', array('status' => '0'), array('studentid' => $student_id, 'parentid' => $parent_id, 'status' => '1'));
	}

	function get_parents($student_id)
	{
		$query = $this->db->query('SELECT Users.* FROM UserParent, Users WHERE UserParent.studentid = ? AND UserParent.status = 1 AND UserParent.parentid = Users.userid', array($student_id));

		return $query->result();
	}

	function get_children($parent_id)
	{
		$query = $this->db->query('SELECT Users.* FROM UserParent, Users WHERE UserParent.parentid = ? AND UserParent.status = 1 AND UserParent.studentid = Users.userid', array($parent_id));

		return $query->result();
	}

	function get_teachers($student_id)
	{
		$query = $this->db->query('SELECT DISTINCT Users.* FROM Users, UserGroup t, UserGroup s WHERE s.userid = ? AND s.status = 1 AND s.groupid = t.groupid AND t.status = 1 AND t.userid = Users.userid AND Users.accounttype = "t"', array($student_id));

		return $query->result();
	}

	function last_incidents($student_id, $teacher_id = 0)
	{
		// Set the month that the school year begins.
		$month_start_of_school_year = 8;

		$start_year = intval(date('m')) < $month_start_of_school_year ? intval(date('Y'))-1 : intval(date('Y'));
		$start_time = strtotime($start_year.'-'.$month_start_of_school_year.'-01 00:00:00');

		$where_values = array($student_id, $student_id, $student_id, $student_id, $student_id, $student_id, $start_time);
		$where = '';

		if($teacher_id !== 0)
		{
			$where = ' AND incidents.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		//echo 'SELECT incidents.*, Users.firstname, Users.lastname FROM ((SELECT Forms.title as incidenttype, Reports.timecreated, Reports.title as label, userid as teacherid FROM Forms, Reports WHERE Forms.formid = Reports.formid AND subjectid = ?) UNION (SELECT "intervention" as incidenttype, timecreated, intervention as label, teacherid FROM Interventions WHERE studentid = ?) UNION (SELECT "referral" as incidenttype, timecreated, incident as label, teacherid FROM Referrals WHERE studentid = ?) UNION (SELECT "reinforcement" as incidenttype, timecreated, reason as label, teacherid FROM Reinforcements WHERE studentid = ?) UNION (SELECT "demerit" as incidenttype, timecreated, reason as label, teacherid FROM Demerits WHERE studentid = ?) UNION (SELECT "detention" as incidenttype, timecreated, CONCAT(minutes, '|', reason) as label, adminid as teacherid FROM Detentions WHERE type = "assigned" AND studentid = ?)) incidents, Users WHERE incidents.teacherid = Users.userid AND incidents.timecreated > ?'.$where.' ORDER BY timecreated DESC LIMIT 10';
		$query = $this->db->query('SELECT incidents.*, Users.firstname, Users.lastname FROM ((SELECT Forms.title as incidenttype, IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident) as timecreated, Reports.title as label, userid as teacherid FROM Forms, Reports WHERE Reports.status = 1 AND Forms.formid = Reports.objectid AND Reports.type = "form" AND subjectid = ?) UNION (SELECT "detention" as incidenttype, timecreated, CONCAT(minutes,"|",reason) as label, adminid as teacherid FROM Detentions WHERE type = "assigned" AND studentid = ?) UNION (SELECT "intervention" as incidenttype, IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) as timecreated, intervention as label, teacherid FROM Interventions WHERE studentid = ?) UNION (SELECT "referral" as incidenttype, timecreated, incident as label, teacherid FROM Referrals WHERE studentid = ?) UNION (SELECT "reinforcement" as incidenttype, IF(Reinforcements.timeincident=0,Reinforcements.timecreated,Reinforcements.timeincident) as timecreated, reason as label, teacherid FROM Reinforcements WHERE studentid = ?) UNION (SELECT "demerit" as incidenttype, IF(timeincident=0,timecreated,timeincident) as timecreated, reason as label, teacherid FROM Demerits WHERE studentid = ?)) incidents, Users WHERE incidents.teacherid = Users.userid AND incidents.timecreated > ?'.$where.' ORDER BY timecreated DESC LIMIT 10', $where_values);

		return $query->result();
	}

	function validate($username, $password)
	{
		$username = strtolower($username);

		$encrypted = $this->encodepassword($password);

		$query = $this->db->query('SELECT * FROM Users WHERE username = ? AND password = ?', array($username, $encrypted));
		$result = $query->row();

		if(empty($result))
		{
			return false;
		}
		else
		{
			return $result;
		}

	}
  
  function check_password($user_id, $password)
  {
		$encrypted = $this->encodepassword($password);

		$query = $this->db->query('SELECT * FROM Users WHERE userid = ? AND password = ?', array($user_id, $encrypted));
		$result = $query->row();

		if(empty($result))
		{
			return false;
		}
		else
		{
			return true;
		}  
  }

  function forgot_password($user_id, $time_created, $key)
	{
		$this->db->insert('Reset', array(
			'userid' => $user_id,
			'timecreated' => $time_created,
			'timereset' => 0,
			'hashkey' => $key,
			'status' => '1'
		));
	}
	
	function get_reset($user_id, $key)
	{
		$query = $this->db->query('SELECT * FROM Reset WHERE userid = ? AND hashkey = ?', array($user_id, $key));
		
		return $query->row();
	}
	
	function reset_used($key)
	{
		$this->db->update('Reset', array(
			'timereset' => time(),
			'status' => '2'
		), array('hashkey' => $key));		
	}
  
  function change_password($user_id, $password)
  {
		$encrypted = $this->encodepassword($password);
    $this->db->update('Users', array('password' => $encrypted), array('userid' => $user_id));
  }	

  function change_username($user_id, $username)
  {
    $this->db->update('Users', array('username' => $username), array('userid' => $user_id));
  }	

  function change_login($user_id, $username, $password)
  {
		$encrypted = $this->encodepassword($password);
    $this->db->update('Users', array('username' => $username, 'password' => $encrypted), array('userid' => $user_id));
  }	

  function encodepassword($password)
  {
  	return md5('f8bbfff4cbc1f83ce14859c6f1fddbdd'.$password.'6/9/2012');
  }
}

?>