<?php
define('DEMERIT_STATUS_REMOVED', 0);
define('DEMERIT_STATUS_ACTIVE', 1);

class Demerit_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $teacher_id, $student_id, $reason, $notes, $time_incident)
	{
		$this->db->insert('Demerits', array(
			'schoolid' => $school_id,
			'teacherid' => $teacher_id,
			'studentid' => $student_id,
			'reason' => $reason,
			'notes' => $notes,
			'timecreated' => time(),
			'timeincident' => $time_incident,
			'status' => DEMERIT_STATUS_ACTIVE
		));

		return $this->db->insert_id();
	}

	function update($demerit_id, $reason, $notes, $time_incident)
	{
		$this->db->update('Demerits', array(
			'reason' => $reason,
			'notes' => $notes,
			'timeincident' => $time_incident
		), array('demeritid' => $demerit_id));
	}

	function get_demerit_total($school_id, $student_id)
	{
		$query = $this->db->query('SELECT COUNT(*) total FROM Demerits WHERE schoolid = ? AND studentid = ? AND status = ?', array($school_id, $student_id, DEMERIT_STATUS_ACTIVE));

		$count = $query->row();

		return empty($count) ? 0 : $count->total;
	}

	function get_by_student($school_id, $student_id, $teacher_id = 0)
	{
		$where = 'Demerits.teacherid = Users.userid AND Demerits.schoolid = ? AND Demerits.studentid = ? AND Demerits.status = ?';
		$where_values = array($school_id, $student_id, DEMERIT_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND Demerits.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT Demerits.*, Users.firstname, Users.lastname FROM Demerits, Users WHERE '.$where.' ORDER BY IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) DESC', $where_values);

		return $query->result();
	}

	function get_demerit($demerit_id)
	{
		$query = $this->db->query('SELECT * FROM Demerits WHERE demeritid = ?', array($demerit_id));

		return $query->row();
	}

	function find($school_id, $teacher_id = 0, $student_id = 0, $grade = 0, $group_id = 0, $start_time = 0, $end_time = 0, $expand_teacher = false, $expand_student = false)
	{
		$where = 'Demerits.schoolid = ?';
		$where_values = array($school_id);

		if($teacher_id !== 0)
		{
			$where .= ' AND Demerits.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Demerits.studentid = ?';
			array_push($where_values, $student_id);
		}

		if($start_time !== 0)
		{
			$where .= " AND IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) >= ?";
			array_push($where_values, $start_time);
		}

		if($end_time !== 0)
		{
			$where .= " AND IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) <= ?";
			array_push($where_values, $end_time);
		}

		$select = 'Demerits.*';
		$from = 'Demerits';

		if($expand_student)
		{
			$select .= ', s.firstname as studentfirstname, s.lastname as studentlastname';
			$from .= ", Users s";
			$where .= ' AND s.userid = Demerits.studentid';
		}

		if($expand_teacher)
		{
			$select .= ', t.firstname as teacherfirstname, t.lastname as teacherlastname';
			$from .= ", Users t";
			$where .= ' AND t.userid = Demerits.teacherid';
		}

		if($grade !== 0)
		{
			if(!$expand_student)
			{
				$from .= ", Users s";
				$where .= ' AND s.userid = Demerits.studentid';
			}

			$where = ' AND s.grade = ?';
			array_push($where_values, $grade);
		}

		if($group_id !== 0)
		{
			$from .= ', UserGroups';
			$where .= ' AND Demerits.studentid = UserGroups.userid AND UserGroups.groupid = ?';
			array_push($where_values, $group_id);
		}

		$where .= ' AND Demerits.status = ?';
		array_push($where_values, DEMERIT_STATUS_ACTIVE);

		$query = $this->db->query('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) DESC', $where_values);
		return $query->result();
	}

	function remove($demerit_id)
	{
		$this->db->update('Demerits', array('status' => DEMERIT_STATUS_REMOVED), array('demeritid' => $demerit_id));
	}

	function remove_with_user($user_id)
	{
		$this->db->where('teacherid', $user_id);
		$this->db->or_where('studentid', $user_id); 
		$this->db->update('Demerits', array('status' => DEMERIT_STATUS_REMOVED));
	}

	function count_today($period, $school_id, $teacher_id = 0)
	{
		$time = get_time_period($period);

		$where = 'schoolid = ? AND IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) > ? AND status = ?';
		$where_values = array($school_id, $time, 1);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total FROM Demerits WHERE '.$where, $where_values);

		return $query->row()->total;
	}

	/**
	 * Returns a total by type for referrals today.
	 * @param $school_id school id
	 * @param $perid minimum time of referrals to fetch
	 * @param $teacher_id if not == 0, will limit referrals to just one teacher
	 */
	function category_totals($period, $school_id, $teacher_id = 0, $student_id = 0, $label_reason = 'reason', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where = 'schoolid = ? AND IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) > ? AND status = ?';
		$where_values = array($school_id, $time, DEMERIT_STATUS_ACTIVE);

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

		$query = $this->db->query('SELECT reason as '.$label_reason.', COUNT(*) as '.$label_total.' FROM Demerits WHERE '.$where.' GROUP BY reason', $where_values);
		return $query->result();
	}

	/**
	 * Returns a total grouped by attribute for referrals this year.
	 * @param $school_id school id
	 */
	function category_totals_by($attribute = 'student.grade', $period, $school_id, $teacher_id = 0, $student_id = 0, $label_grade = 'grade', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where_values = array($school_id, $time, DEMERIT_STATUS_ACTIVE);
		$where = 'Demerits.schoolid = ? AND IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) > ? AND Demerits.status = ?';

		list($user_type, $field) = preg_split('/\./', $attribute);
		
		switch($user_type)
		{
			case 'teacher':
				$where .= ' AND Demerits.teacherid = Users.userid';
			break;
			case 'student':
				$where .= ' AND Demerits.studentid = Users.userid';
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
			$where .= ' AND Demerits.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Demerits.studentid = ?';
			array_push($where_values, $student_id);
		}

		$query = $this->db->query('SELECT '.$select.' FROM Demerits, Users WHERE '.$where.' GROUP BY '.$group_by, $where_values);

		return $query->result();
	}

	function count_by_interval($period, $interval, $school_id, $teacher_id = 0, $student_id = 0)
	{
		$time = get_time_period($period);
		list($group_by, $order_by) = get_time_interval('IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident)', $period, $interval);

		$where = ' AND IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) >= ? AND schoolid = ?';
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

		$query = $this->db->query('SELECT COUNT(*) as total, '.$group_by.' as label FROM Demerits WHERE status = 1'.$where.' GROUP BY '.$group_by.' ORDER BY '.$order_by, $where_values);

		return $query->result();
	}

	/**
	 * Returns the top demerit counts with student names.
	 * @param $school_id school id
	 * @param $limit number of results to limit by
	 * @param $teacher_id if != 0, will limit to demerits assigned by teacher.
	 */
	function top_demerits($period, $school_id, $teacher_id = 0, $limit = 5)
	{
		$time = get_time_period($period);

		$where = ' AND Demerits.schoolid = ? AND IF(Demerits.timeincident=0, Demerits.timecreated, Demerits.timeincident) > ? AND Demerits.status = ?';
		$where_values = array($school_id, $time, DEMERIT_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where = ' AND adminid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total, Users.userid, Users.profileimage, CONCAT(Users.lastname,", ",Users.firstname) as studentname FROM Demerits, Users WHERE Demerits.studentid = Users.userid '.$where.' GROUP BY Demerits.studentid ORDER BY total DESC LIMIT '.$limit, $where_values);

		return $query->result();
	}	
}
?>