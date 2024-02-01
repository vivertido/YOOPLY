<?php

define('REFERRAL_STATUS_REMOVED', '0');
define('REFERRAL_STATUS_ACTIVE', '1');

class Referral_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $teacher_id, $student_id, $incident, $notes, $draft = true)
	{
		$data = array(
			'schoolid' => $school_id,
			'teacherid' => $teacher_id,
			'studentid' => $student_id,
			'incident' => $incident,
			'timecreated' => time(),
			'teachernotes' => json_encode($notes),
			'status' => REFERRAL_STATUS_ACTIVE
		);

		if(!$draft)
		{
			$data['timeteachersave'] = time();
		}

		$this->db->insert('Referrals', $data);

		return $this->db->insert_id();
	}

	function get_active_referrals($school_id)
	{
		$query = $this->db->query('SELECT Referrals.*, student.firstname, student.lastname, teacher.firstname as teacherfirstname, teacher.lastname as teacherlastname FROM Referrals, Users student, Users teacher WHERE Referrals.studentid = student.userid AND Referrals.teacherid = teacher.userid AND Referrals.timecheckout = 0 AND Referrals.schoolid = ? AND Referrals.status = ? AND timeteachersave > 0 ORDER BY Referrals.timecreated DESC', array($school_id, REFERRAL_STATUS_ACTIVE));

		return $query->result();
	}

	function get_pending_referrals($school_id, $teacher_id = 0)
	{
		$where = '';
		$where_values = array($school_id, REFERRAL_STATUS_ACTIVE);

		if($teacher_id != 0)
		{
			$where = ' AND Referrals.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT Referrals.*, student.firstname, student.lastname FROM Referrals, Users student WHERE Referrals.studentid = student.userid AND Referrals.timeteachersave = 0 AND Referrals.schoolid = ? AND Referrals.status = ?'.$where.' ORDER BY Referrals.timecreated DESC', $where_values);

		return $query->result();
	}

	function get_referral($referral_id)
	{
		$query = $this->db->query('SELECT * FROM Referrals WHERE referralid = ?', array($referral_id));

		return $query->row();
	}

	function get_count_by_student($student_id)
	{
		$query = $this->db->query('SELECT COUNT(*) as total FROM Referrals WHERE studentid = ? AND status = ? AND timeteachersave > 0', array($student_id, REFERRAL_STATUS_ACTIVE));

		$count = $query->row();

		return $count->total;
	}

	// $include_drafts_by = 0 to not show draft referrals, or a userid to include drafts by 
	function get_active_by_student($student_id, $include_drafts_by = 0)
	{
		$where_values = array($student_id, REFERRAL_STATUS_ACTIVE);

		if($include_drafts_by == 0)
		{
			$where = ' AND timeteachersave > 0';
		}
		else
		{
			$where = ' AND teacherid = ?';
			array_push($where_values, $include_drafts_by);
		}
		
		$query = $this->db->query('SELECT * FROM Referrals WHERE studentid = ? AND timecheckin = 0 AND status = ?'.$where, $where_values);

		return $query->result();
	}

	function get_by_student($student_id)
	{
		$query = $this->db->query('SELECT * FROM Referrals WHERE studentid = ? AND status = ? AND timeteachersave > 0', array($student_id, REFERRAL_STATUS_ACTIVE));

		return $query->result();
	}

	function save_student($referral_id, $data)
	{
		$this->db->update('Referrals', array(
				'timestudentsave' => time(),
				'studentnotes' => json_encode($data)
			), array('referralid' => $referral_id));
	}

	function save_teacher($referral_id, $incident, $notes)
	{
		$this->db->update('Referrals', array(
			'incident' => $incident,
			'teachernotes' => json_encode($notes),
		), array('referralid' => $referral_id));
	}

	function submit_teacher($referral_id, $incident, $notes)
	{
		$this->db->update('Referrals', array(
			'incident' => $incident,
			'teachernotes' => json_encode($notes),
			'timeteachersave' => time()
		), array('referralid' => $referral_id));
	}

	function save_admin($referral_id, $admin_id, $notes)
	{
		$this->db->update('Referrals', array(
				'timeadminsave' => time(),
				'adminid' => $admin_id,
				'adminnotes' => json_encode($notes),
			), array('referralid' => $referral_id));
	}

	/**
	 * Saves the student reflection to the referral.
	 *
	 * @param $referral_id the id of the referral
	 * @param $reflection array of array('label' =>, 'value' => ) of the question/answers.
	 */
	function save_reflection($referral_id, $reflection)
	{
		$this->db->update('Referrals', array(
			'reflection' => json_encode($reflection),
			'timereflection' => time()
		), array('referralid' => $referral_id));
	}


	/**
	 * Returns a total by type for referrals today.
	 * @param $school_id school id
	 * @param $perid minimum time of referrals to fetch
	 * @param $teacher_id if not == 0, will limit referrals to just one teacher
	 */
	function category_totals($period, $school_id, $teacher_id = 0, $student_id = 0, $label_incident = 'incident', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where = 'timeteachersave > 0 AND schoolid = ? AND timecreated > ? AND status = ?';
		$where_values = array($school_id, $time, REFERRAL_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND studentid = ?';
			array_push($where_values, $student_id);
		}

		$query = $this->db->query('SELECT incident as '.$label_incident.', COUNT(*) as '.$label_total.' FROM Referrals WHERE '.$where.' GROUP BY incident', $where_values);
		return $query->result();
	}

	/**
	 * Returns a total by grade for referrals this year.
	 * @param $school_id school id
	 */
	function category_totals_by($attribute = 'student.grade', $period, $school_id, $teacher_id = 0, $student_id = 0, $label_grade = 'grade', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where_values = array($school_id, $time, REFERRAL_STATUS_ACTIVE);
		$where = 'Referrals.schoolid = ? AND Referrals.timecreated > ? AND Referrals.status = ?';
		$where .= ' AND timeteachersave > 0';

		list($user_type, $field) = preg_split('/\./', $attribute);
		
		switch($user_type)
		{
			case 'teacher':
				$where .= ' AND Referrals.teacherid = Users.userid';
			break;
			case 'student':
				$where .= ' AND Referrals.studentid = Users.userid';
			break;			
		}

		if($field == 'name')
		{
			$select = 'CONCAT(Users.lastname,", ",Users.firstname) as '.$label_grade;
			$group_by = 'CONCAT(Users.lastname,", ",Users.firstname)';
		}
		else
		{
			$select = 'Users.'.$field.' as '.$label_grade;
			$group_by = $field;
		}

		$select .= ', COUNT(*) as '.$label_total;

		if($teacher_id !== 0)
		{
			$where .= ' AND Referrals.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Referrals.studentid = ?';
			array_push($where_values, $student_id);
		}		

		$query = $this->db->query('SELECT '.$select.' FROM Referrals, Users WHERE '.$where.' GROUP BY '.$group_by, $where_values);

		return $query->result();
	}

	function count_today($period, $school_id, $teacher_id = 0)
	{	
		$time = get_time_period($period);

		$where = 'schoolid = ? AND timecreated > ? AND status = ? AND timeteachersave > 0';
		$where_values = array($school_id, $time, REFERRAL_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total FROM Referrals WHERE '.$where, $where_values);

		return $query->row()->total;
	}

	/**
	 * Returns the number of referrals per day since the time period.
	 * @param $school_id the school id
	 * @param $period the start of the time period
	 * @param $teacher_id if != 0, will limit to just that teacher
	 */
	function count_by_day($period, $school_id, $teacher_id = 0)
	{
		$time = get_time_period($period);

		$where = 'Referrals.timeteachersave > 0 AND timecreated > ? AND schoolid = ? AND status = ?';
		$where_values = array($time, $school_id, REFERRAL_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total, timecreated as date FROM Referrals WHERE '.$where.' GROUP BY DATE(FROM_UNIXTIME(timecreated)) ASC', $where_values);

		return $query->result();
	}

	/**
	 * Returns the top referral counts with student names.
	 * @param $school_id school id
	 * @param $limit number of results to limit by
	 * @param $teacher_id if != 0, will limit to referral assigned by teacher.
	 */
	function top_referrals($period, $school_id, $teacher_id = 0, $limit = 5)
	{
		$time = get_time_period($period);

		$where = ' AND Referrals.timeteachersave > 0 AND Referrals.timecreated > ? AND Referrals.status = ?';
		$where_values = array($school_id, $time, REFERRAL_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total, Users.userid, Users.profileimage, CONCAT(Users.lastname,", ",Users.firstname) as studentname FROM Referrals, Users WHERE Referrals.studentid = Users.userid AND Referrals.schoolid = ?'.$where.' GROUP BY Referrals.studentid ORDER BY total DESC LIMIT '.$limit, $where_values);

		return $query->result();
	}

	function get_reflections($school_id, $student_id)
	{
		$query = $this->db->query('SELECT * FROM Referrals WHERE schoolid = ? AND studentid = ? AND reflection != "" AND status = ?', array($school_id, $student_id, REFERRAL_STATUS_ACTIVE));

		return $query->result();
	}

	function send_back_to_class($referral_id)
	{
		$this->db->update('Referrals', array(
			'timecheckout' => time()
		), array(
			'referralid' => $referral_id
		));
	}

	function check_in_student($referral_id)
	{
		$this->db->update('Referrals', array(
			'timecheckin' => time()
		), array(
			'referralid' => $referral_id
		));
	}

	function find($school_id, $teacher_id = 0, $student_id = 0, $grade = 0, $group_id = 0, $start_time = 0, $end_time = 0, $expand_teacher = false, $expand_student = false)
	{
		$where = 'Referrals.timeteachersave > 0 AND Referrals.schoolid = ? AND Referrals.status = ?';
		$where_values = array($school_id, REFERRAL_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND Referrals.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Referrals.studentid = ?';
			array_push($where_values, $student_id);
		}

		if($start_time !== 0)
		{
			$where .= " AND Referrals.timecreated >= ?";
			array_push($where_values, $start_time);
		}

		if($end_time !== 0)
		{
			$where .= " AND Referrals.timecreated <= ?";
			array_push($where_values, $end_time);
		}

		$select = 'Referrals.*';
		$from = 'Referrals';

		if($expand_student)
		{
			$select .= ', s.firstname as studentfirstname, s.lastname as studentlastname';
			$from .= ", Users s";
			$where .= ' AND s.userid = Referrals.studentid';
		}

		if($expand_teacher)
		{
			$select .= ', t.firstname as teacherfirstname, t.lastname as teacherlastname';
			$from .= ", Users t";
			$where .= ' AND t.userid = Referrals.teacherid';
		}

		if($grade !== 0)
		{
			if(!$expand_student)
			{
				$from .= ", Users s";
				$where .= ' AND s.userid = Referrals.studentid';
			}

			$where .= ' AND s.grade = ?';
			array_push($where_values, $grade);
		}

		if($group_id !== 0)
		{
			$from .= ', UserGroups';
			$where .= ' AND Referrals.studentid = UserGroups.userid AND UserGroups.groupid = ?';
			array_push($where_values, $group_id);
		}

		$query = $this->db->query('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY Referrals.timecreated DESC', $where_values);
		return $query->result();
	}

	function remove($referral_id)
	{
		$this->db->update('Referrals', array('status' => REFERRAL_STATUS_REMOVED), array('referralid' => $referral_id));
	}

	function count_by_interval($period, $interval, $school_id, $teacher_id = 0, $student_id = 0)
	{
		$time = get_time_period($period);
		list($group_by, $order_by) = get_time_interval('timecreated', $period, $interval);

		$where = ' AND timecreated >= ? AND schoolid = ?';
		$where_values = array($time, $school_id);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}		

		if($student_id !== 0)
		{
			$where .= ' AND studentid = ?';
			array_push($where_values, $student_id);
		}		

		$select = 'COUNT(*) as total';
		$select .= !empty($group_by) ? ', '.$group_by.' as label' : '';
		$group_by = !empty($group_by) ? ' GROUP BY '.$group_by : '';
		$order_by = !empty($order_by) ? ' ORDER BY '.$order_by : '';

		$query = $this->db->query('SELECT '.$select.' FROM Referrals WHERE timeteachersave > 0 AND status = 1'.$where.$group_by.$order_by, $where_values);

		return $query->result();
	}	

	function remove_with_user($user_id)
	{
		$this->db->where('adminid', $user_id);
		$this->db->or_where('teacherid', $user_id); 
		$this->db->or_where('studentid', $user_id); 
		$this->db->update('Referrals', array('status' => REFERRAL_STATUS_REMOVED));
	}	

}