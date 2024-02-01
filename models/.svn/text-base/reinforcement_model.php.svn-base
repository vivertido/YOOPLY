<?php

define('REINFORCEMENT_STATUS_REMOVED', '0');
define('REINFORCEMENT_STATUS_ACTIVE', '1');

class Reinforcement_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $teacher_id, $student_id, $amount, $reason, $notes, $time_incident)
	{
		$this->db->insert('Reinforcements', array(
			'schoolid' => $school_id,
			'quantity' => $amount,
			'teacherid' => $teacher_id,
			'studentid' => $student_id,
			'reason' => $reason,
			'timecreated' => time(),
			'timeincident' => $time_incident,
			'notes' => $notes,
			'status' => REINFORCEMENT_STATUS_ACTIVE

		));

		return $this->db->insert_id();
	}

	function deduct($school_id, $teacher_id, $student_id, $amount, $reason, $notes)
	{
		$this->db->insert('Reinforcements', array(
			'schoolid' => $school_id,
			'quantity' => "-".$amount,
			'teacherid' => $teacher_id,
			'studentid' => $student_id,
			'reason' => $reason,
			'timecreated' => time(),
			'notes' => $notes,
			'status' => REINFORCEMENT_STATUS_ACTIVE
		));

		return $this->db->insert_id();
	}

	function find($school_id, $teacher_id = 0, $student_id = 0, $grade = 0, $group_id = 0, $start_time = 0, $end_time = 0, $expand_teacher = false, $expand_student = false)
	{
		$where = 'Reinforcements.schoolid = ?';
		$where_values = array($school_id);

		if($teacher_id !== 0)
		{
			$where .= ' AND Reinforcements.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Reinforcements.studentid = ?';
			array_push($where_values, $student_id);
		}

		if($start_time !== 0)
		{
			$where .= " AND IF(Reinforcements.timeincident=0, Reinforcements.timecreated, Reinforcements.timeincident) >= ?";
			array_push($where_values, $start_time);
		}

		if($end_time !== 0)
		{
			$where .= " AND IF(Reinforcements.timeincident=0, Reinforcements.timecreated, Reinforcements.timeincident) <= ?";
			array_push($where_values, $end_time);
		}

		$select = 'Reinforcements.*';
		$from = 'Reinforcements';

		if($expand_student)
		{
			$select .= ', s.firstname as studentfirstname, s.lastname as studentlastname';
			$from .= ", Users s";
			$where .= ' AND s.userid = Reinforcements.studentid';
		}

		if($expand_teacher)
		{
			$select .= ', t.firstname as teacherfirstname, t.lastname as teacherlastname';
			$from .= ", Users t";
			$where .= ' AND t.userid = Reinforcements.teacherid';
		}

		if($grade !== 0)
		{
			if(!$expand_student)
			{
				$from .= ", Users s";
				$where .= ' AND s.userid = Reinforcements.studentid';
			}

			$where = ' AND s.grade = ?';
			array_push($where_values, $grade);
		}

		if($group_id !== 0)
		{
			$from .= ', UserGroups';
			$where .= ' AND Reinforcements.studentid = UserGroups.userid AND UserGroups.groupid = ?';
			array_push($where_values, $group_id);
		}

		$where .= ' AND Reinforcements.status = ?';
		array_push($where_values, REINFORCEMENT_STATUS_ACTIVE);

		$query = $this->db->query('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY IF(Reinforcements.timeincident=0, Reinforcements.timecreated, Reinforcements.timeincident) DESC', $where_values);
		return $query->result();
	}

	/**
	 * Returns the recent reinforcements.
	 *
	 * @param $student_id the student id.
	 * @return array of reinforcement objects.
	 */
	function get_recent_reinforcements($student_id)
	{
		$query = $this->db->query('SELECT Reinforcements.*, Users.firstname as teacherfirstname, Users.lastname as teacherlastname FROM Reinforcements, Users WHERE quantity > 0 AND Users.userid = Reinforcements.teacherid AND Reinforcements.studentid = ? AND Reinforcements.status = ? ORDER BY timecreated DESC', array($student_id, REINFORCEMENT_STATUS_ACTIVE));

		return $query->result();
	}

	/**
	 * Returns the number of dollars per month for the selected student.
	 *
	 * @param $student_id the student id
	 * @return array of objects with month and total
	 */
	function get_dollar_total_months($student_id)
	{
		$query = $this->db->query('SELECT FROM_UNIXTIME(timecreated, "%b") as `month`, SUM(quantity) as total FROM Reinforcements WHERE quantity > 0 AND studentid = ? AND status = ? GROUP BY FROM_UNIXTIME(timecreated, "%y%m") ORDER BY FROM_UNIXTIME(timecreated, "%y%m")', array($student_id, REINFORCEMENT_STATUS_ACTIVE));

		return $query->result();
	}

	/**
	 * Returns the total number of dollars the student has earned during lifetime.
	 *
	 * @param $student_id the student id
	 */
	function get_dollar_total($student_id)
	{
		$query = $this->db->query('SELECT SUM(quantity) as total FROM Reinforcements WHERE studentid = ? AND status = ?', array($student_id, REINFORCEMENT_STATUS_ACTIVE));

		$count = $query->row();
		if(is_null($count->total))
		{
			$count->total = 0;
		}
		return $count->total;
	}

	/**
	 * Returns a total by type for dollars since time period.
	 * @param $school_id school id
	 * @param $perid minimum time of reinforcements to fetch
	 * @param $teacher_id if not == 0, will limit reinforcements to just one teacher
	 */
	function category_totals($period, $school_id, $teacher_id = 0, $student_id = 0, $label_reason = 'reason', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where = ' AND schoolid = ? AND timecreated > ? AND status = ?';
		$where_values = array($school_id, $time, REINFORCEMENT_STATUS_ACTIVE);

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

		$query = $this->db->query('SELECT reason as '.$label_reason.', SUM(quantity) as '.$label_total.' FROM Reinforcements WHERE quantity > 0'.$where.' GROUP BY reason', $where_values);
		return $query->result();
	}

	/**
	 * Returns the number of dollars per day since the time period.
	 * @param $school_id the school id
	 * @param $period the start of the time period
	 * @param $teacher_id if != 0, will limit to just that teacher
	 */
	function count_by_day($period, $school_id, $teacher_id = 0)
	{
		$time = get_time_period($period);

		$where = ' AND timecreated > ? AND schoolid = ? AND status = ?';
		$where_values = array($time, $school_id, REINFORCEMENT_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT SUM(quantity) as total, timecreated as date FROM Reinforcements WHERE quantity > 0'.$where.' GROUP BY DATE(FROM_UNIXTIME(timecreated)) ASC', $where_values);

		return $query->result();
	}

	/**
	 * Returns the top reinforcement counts with student names.
	 * @param $school_id school id
	 * @param $limit number of results to limit by
	 * @param $teacher_id if != 0, will limit to reinforcements assigned by teacher.
	 */
	function top_reinforcements($period, $school_id, $teacher_id = 0, $limit = 5)
	{
		$time = get_time_period($period);

		$where = ' AND Reinforcements.schoolid = ? AND Reinforcements.timecreated > ? AND Reinforcements.status = ?';
		$where_values = array($school_id, $time, REINFORCEMENT_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT SUM(quantity) as total, Users.userid, Users.profileimage, CONCAT(Users.lastname,", ",Users.firstname) as studentname FROM Reinforcements, Users WHERE quantity > 0 AND Reinforcements.studentid = Users.userid'.$where.' GROUP BY Reinforcements.studentid ORDER BY total DESC LIMIT '.$limit, $where_values);

		return $query->result();
	}

	function count_spent($school_id, $student_id)
	{
		$query = $this->db->query('SELECT SUM(ABS(quantity)) as total FROM Reinforcements WHERE quantity < 0 AND schoolid = ? AND studentid = ? AND status = ?', array($school_id, $student_id, REINFORCEMENT_STATUS_ACTIVE));

		$count = $query->row()->total;

		return is_null($count) ? 0 : $count;
	}

	function get_by_student($school_id, $teacher_id = 0, $student_id)
	{
		$where_values = array($school_id, $student_id, REINFORCEMENT_STATUS_ACTIVE);
		$where = '';

		if($teacher_id !== 0)
		{
			$where .= ' AND Reinforcements.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT Reinforcements.*, Users.firstname, Users.lastname FROM Reinforcements, Users WHERE Users.userid = Reinforcements.teacherid AND Reinforcements.schoolid = ? AND Reinforcements.studentid = ? AND Reinforcements.status = ?'.$where.' ORDER BY timecreated DESC', $where_values);

		return $query->result();
	}

	function get_reinforcement($reinforcement_id)
	{
		$query = $this->db->query('SELECT * FROM Reinforcements WHERE reinforcementid = ?', array($reinforcement_id));

		return $query->row();
	}

	function remove($reinforcement_id)
	{
		$this->db->update('Reinforcements', array('status' => REINFORCEMENT_STATUS_REMOVED), array('reinforcementid' => $reinforcement_id));
	}

	function remove_with_user($user_id)
	{
		$this->db->where('teacherid', $user_id);
		$this->db->or_where('studentid', $user_id); 
		$this->db->update('Reinforcements', array('status' => REINFORCEMENT_STATUS_REMOVED));
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

		$query = $this->db->query('SELECT COUNT(*) as total, '.$group_by.' as label FROM Reinforcements WHERE status = 1'.$where.' GROUP BY '.$group_by.' ORDER BY '.$order_by, $where_values);

		return $query->result();
	}

	/**
	 * Returns a total grouped by attribute for reinforcements this year.
	 * @param $school_id school id
	 */
	function category_totals_by($attribute = 'student.grade', $period, $school_id, $teacher_id = 0, $student_id = 0, $label_grade = 'grade', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where_values = array($school_id, $time, REINFORCEMENT_STATUS_ACTIVE);
		$where = 'Reinforcements.schoolid = ? AND Reinforcements.timecreated > ? AND Reinforcements.status = ?';

		list($user_type, $field) = preg_split('/\./', $attribute);
		
		switch($user_type)
		{
			case 'teacher':
				$where .= ' AND Reinforcements.teacherid = Users.userid';
			break;
			case 'student':
				$where .= ' AND Reinforcements.studentid = Users.userid';
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
			$where .= ' AND Reinforcements.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Reinforcements.studentid = ?';
			array_push($where_values, $student_id);
		}

		$query = $this->db->query('SELECT '.$select.' FROM Reinforcements, Users WHERE '.$where.' GROUP BY '.$group_by, $where_values);

		return $query->result();
	}

	function count_today($period, $school_id, $teacher_id = 0)
	{	
		$time = get_time_period($period);

		$where = 'schoolid = ? AND timecreated > ? AND status = ?';
		$where_values = array($school_id, $time, REINFORCEMENT_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND Reinforcements.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total FROM Reinforcements WHERE '.$where, $where_values);

		return $query->row()->total;
	}
}
?>