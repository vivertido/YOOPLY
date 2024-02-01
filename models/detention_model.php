<?php

define('DETENTION_STATUS_ACTIVE', 1);

class Detention_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function assign($school_id, $student_id, $admin_id, $minutes, $reason = '')
	{
		$this->db->insert('Detentions', array(
			'schoolid' => $school_id,
			'studentid' => $student_id,
			'adminid' => $admin_id,
			'type' => 'assigned',
			'minutes' => $minutes,
			'reason' => $reason,
			'timecreated' => time(),
			'status' => '1'
		));
	}

	function serve($school_id, $student_id, $admin_id, $minutes)
	{
		$this->db->insert('Detentions', array(
			'schoolid' => $school_id,
			'studentid' => $student_id,
			'adminid' => $admin_id,
			'type' => 'served',
			'minutes' => '-'.$minutes,
			'timecreated' => time(),
			'status' => '1'
		));
	}

	function adjust($school_id, $student_id, $admin_id, $minutes, $reason)
	{
		$this->db->insert('Detentions', array(
			'schoolid' => $school_id,
			'studentid' => $student_id,
			'adminid' => $admin_id,
			'type' => 'adjust',
			'minutes' => $minutes,
			'reason' => $reason,
			'timecreated' => time(),
			'status' => '1'
		));	
	}

	function reset($detention_id)
	{
		$this->db->update('Detentions', array(
			'timecreated' => time()
		), array('detentionid' => $detention_id));
	}

	/**
	 * Returns the detentions assigned to student.
	 *
	 * @param $student_id the student id
	 * @param $expand_teacher if true, will include teacherfirstname and teacherlastname.
	 * @return array of detention objects.
	 */
	function get_assigned_from_student($student_id, $expand_teacher = false)
	{
		if($expand_teacher)
		{
			$select = ', teacher.firstname as teacherfirstname, teacher.lastname as teacherlastname';
			$from = ', Users as teacher';
			$where = ' AND teacher.userid = Detentions.adminid';
		}
		else
		{
			$from = $where = '';
		}

		$query = $this->db->query('SELECT Detentions.*'.$select.' FROM Detentions'.$from.' WHERE type = "assigned" AND Detentions.studentid = ?'.$where.' ORDER BY timecreated DESC', array($student_id));

		return $query->result();
	}

	function get_served_from_student($student_id)
	{
		$query = $this->db->query('SELECT detentionid, type, ABS(minutes) as minutes, reason, timecreated FROM Detentions WHERE type = "served" AND studentid = ? AND status = 1 ORDER BY timecreated DESC', array($student_id));

		return $query->result();
	}

	function count_served_from_student($student_id)
	{
		$query = $this->db->query('SELECT SUM(ABS(minutes)) as total FROM Detentions WHERE type = "served" AND studentid = ? AND status = 1 ORDER BY timecreated DESC', array($student_id));

		return $query->row()->total;
	}

	function count_assigned_from_student($student_id)
	{
		$query = $this->db->query('SELECT SUM(minutes) as total FROM Detentions WHERE (type = "assigned" OR type = "adjust") AND studentid = ?  AND status = 1 ORDER BY timecreated DESC', array($student_id));

		return $query->row()->total;
	}

	function get_assigned_total_months($student_id)
	{
		$query = $this->db->query('SELECT FROM_UNIXTIME(timecreated, "%b") as `month`, SUM(minutes) as total FROM Detentions WHERE (type = "assigned" OR type = "adjust") AND studentid = ?  AND status = 1 GROUP BY FROM_UNIXTIME(timecreated, "%y%m") ORDER BY FROM_UNIXTIME(timecreated, "%y%m")', array($student_id));

		return $query->result();
	}

	function get_outstanding($school_id)
	{
		$query = $this->db->query('SELECT assigned.firstname, assigned.lastname, assigned.grade, assigned.studentid, assigned.total as assignedminutes, served.total as servedminutes FROM (SELECT Users.firstname, Users.lastname, Users.grade, Detentions.studentid, SUM(minutes) as total FROM Detentions, Users WHERE (Detentions.type = "assigned" OR Detentions.type = "adjust") AND Users.schoolid = ? AND Users.userid = Detentions.studentid AND Detentions.status = 1  GROUP BY studentid) as assigned LEFT JOIN (SELECT studentid, ABS(SUM(minutes)) as total FROM Detentions WHERE type = "served" GROUP BY studentid) as served ON served.studentid = assigned.studentid WHERE assigned.total > served.total OR served.total IS NULL ORDER BY grade, lastname, firstname', array($school_id));

		return $query->result();
	}

	function get_active($student_id)
	{
		$query = $this->db->query('SELECT * FROM Detentions WHERE type = "assigned" AND minutes = 0 AND studentid = ? AND status = 1', array($student_id));

		return $query->row();
	}

	function get_active_detention($student_id)
	{
		$query = $this->db->query('SELECT * FROM Detentions WHERE type = "served" AND minutes = 0 AND studentid = ? AND status = 1', array($student_id));

		return $query->row();
	}

	function get_active_detentions($school_id)
	{
		$query = $this->db->query('SELECT * FROM Detentions WHERE type = "served" AND minutes = 0 AND schoolid = ? AND status = 1', array($school_id));

		return $query->result();
	}

	function update($detention_id, $minutes)
	{
		$this->db->update('Detentions', array('minutes' => $minutes), array('detentionid' => $detention_id));
	}

	function count_today($period, $school_id, $teacher_id = 0)
	{
		$time = get_time_period($period);

		$where = 'schoolid = ? AND type = "assigned" AND timecreated > ? AND status = ?';
		$where_values = array($school_id, $time, DETENTION_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND adminid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total FROM Detentions WHERE '.$where, $where_values);

		return $query->row()->total;
	}

	/**
	 * Returns a total by type for detentions since time period.
	 * @param $school_id school id
	 * @param $perid minimum time of referrals to fetch
	 * @param $teacher_id if not == 0, will limit referrals to just one teacher
	 */
	function category_totals($period, $school_id, $teacher_id = 0, $student_id = 0, $label_reason = 'reason', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where = 'schoolid = ? AND timecreated > ? AND status = ?';
		$where_values = array($school_id, $time, DETENTION_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND adminid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND studentid = ?';
			array_push($where_values, $student_id);
		}

		$query = $this->db->query('SELECT reason as '.$label_reason.', SUM(minutes) as '.$label_total.' FROM Detentions WHERE '.$where.' AND type = "assigned" GROUP BY reason', $where_values);
		return $query->result();
	}

	/**
	 * Returns the number of detentions per day since the time period.
	 * @param $school_id the school id
	 * @param $period the start of the time period
	 * @param $teacher_id if != 0, will limit to just that teacher
	 */
	function count_by_day($period, $school_id, $teacher_id = 0)
	{
		$time = get_time_period($period);

		$where = '';
		$where_values = array($time, $school_id);

		if($teacher_id !== 0)
		{
			$where = ' AND adminid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total, timecreated as date FROM Detentions WHERE timecreated > ? AND schoolid = ? AND status = 1'.$where.' GROUP BY DATE(FROM_UNIXTIME(timecreated)) ASC', $where_values);

		return $query->result();
	}

	/**
	 * Returns a total grouped by attribute for detentions since the time period.
	 * @param $school_id school id
	 */
	function category_totals_by($attribute = 'student.grade', $period, $school_id, $teacher_id = 0, $student_id = 0, $label_grade = 'grade', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where_values = array($school_id, $time, DETENTION_STATUS_ACTIVE);
		$where = 'Detentions.schoolid = ? AND Detentions.timecreated > ? AND Detentions.status = ?';

		list($user_type, $field) = preg_split('/\./', $attribute);
		
		switch($user_type)
		{
			case 'teacher':
				$where .= ' AND Detentions.adminid = Users.userid';
			break;
			case 'student':
				$where .= ' AND Detentions.studentid = Users.userid';
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
			$where .= ' AND Detentions.adminid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Detentions.studentid = ?';
			array_push($where_values, $student_id);
		}		

		$query = $this->db->query('SELECT '.$select.' FROM Detentions, Users WHERE '.$where.' GROUP BY '.$group_by, $where_values);

		return $query->result();
	}

	/**
	 * Returns the top detention counts with student names.
	 * @param $school_id school id
	 * @param $limit number of results to limit by
	 * @param $teacher_id if != 0, will limit to detentions assigned by teacher.
	 */
	function top_detentions($period, $school_id, $teacher_id = 0, $limit = 5)
	{
		$time = get_time_period($period);

		$where = ' AND Detentions.schoolid = ? AND Detentions.timecreated > ?';
		$where_values = array($school_id, $time);

		if($teacher_id !== 0)
		{
			$where = ' AND adminid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT SUM(minutes) as total, Users.userid, Users.profileimage, CONCAT(Users.lastname,", ",Users.firstname) as studentname FROM Detentions, Users WHERE Detentions.studentid = Users.userid AND type = "assigned" AND Detentions.status = 1'.$where.' GROUP BY Detentions.studentid ORDER BY total DESC LIMIT '.$limit, $where_values);

		return $query->result();
	}

	function get_balance($student_id)
	{
		$query = $this->db->query('SELECT SUM(minutes) as total FROM Detentions WHERE studentid = ? AND status = 1', array($student_id));

		return $query->row()->total;
	}

	function get_by_student($school_id, $teacher_id = 0, $student_id)
	{
		$where_values = array($school_id, $student_id);
		$where = '';

		if($teacher_id !== 0)
		{
			$where = ' AND adminid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT * FROM Detentions WHERE schoolid = ? AND studentid = ? AND minutes != 0 AND status = 1'.$where, $where_values);

		return $query->result();
	}

	function get_detention($detention_id)
	{
		$query = $this->db->query('SELECT * FROM Detentions WHERE detentionid = ? AND status = 1', array($detention_id));

		return $query->row();
	}

	/** Returns the detention balance from students in the teacher's class.
	 * @param $teacher_id userid of the teacher
	 * @return array of students with detention balances.
	 */
	function get_balance_from_students($school_id, $teacher_id = 0)
	{
		$where_values = array($school_id);

		if($teacher_id !== 0)
		{
			$from = ', UserGroup t';
			$where = ' AND t.userid = ? AND s.groupid = t.groupid';
			array_push($where_values, $teacher_id);
		}
		else
		{
			$from = '';
			$where = '';
		}

		$query = $this->db->query('SELECT DISTINCT detentions.total, Users.userid, Users.firstname, Users.lastname, Users.grade FROM (SELECT SUM(minutes) as total, studentid FROM Detentions WHERE schoolid = ? AND Detentions.status = 1 GROUP BY studentid) detentions, Users, UserGroup s'.$from.' WHERE detentions.studentid = Users.userid AND s.userid = detentions.studentid AND detentions.total != 0'.$where.' ORDER BY grade, lastname, firstname', $where_values);

		return $query->result();
	}

	function remove($detention_id)
	{
		$this->db->update('Detentions', array('status' => 0), array('detentionid' => $detention_id));
	}

	function remove_with_user($user_id)
	{
		$this->db->where('adminid', $user_id);
		$this->db->or_where('studentid', $user_id); 
		$this->db->update('Detentions', array('status' => 0));
	}	

	function count_by_interval($period, $interval, $school_id, $teacher_id = 0, $student_id = 0)
	{
		$time = get_time_period($period);
		list($group_by, $order_by) = get_time_interval('timecreated', $period, $interval);

		$where = ' AND timecreated >= ? AND schoolid = ?';
		$where_values = array($time, $school_id);

		if($teacher_id !== 0)
		{
			$where .= ' AND adminid = ?';
			array_push($where_values, $teacher_id);
		}		

		if($student_id !== 0)
		{
			$where .= ' AND studentid = ?';
			array_push($where_values, $student_id);
		}		

		$query = $this->db->query('SELECT SUM(minutes) as total, '.$group_by.' as label FROM Detentions WHERE type = "assigned" AND status = 1'.$where.' GROUP BY '.$group_by.' ORDER BY '.$order_by, $where_values);

		return $query->result();
	}
}
?>