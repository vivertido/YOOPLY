<?php

define('INTERVENTION_STATUS_REMOVED', '0');
define('INTERVENTION_STATUS_ACTIVE', '1');

class Intervention_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $teacher_id, $student_id, $intervention, $notes, $time_incident)
	{
		$this->db->insert('Interventions', array(
			'schoolid' => $school_id,
			'teacherid' => $teacher_id,
			'studentid' => $student_id,
			'intervention' => $intervention,
			'timecreated' => time(),
			'timeincident' => $time_incident,
			'notes' => $notes,
			'status' => INTERVENTION_STATUS_ACTIVE
		));

		return $this->db->insert_id();
	}

	function update($intervention_id, $intervention, $notes, $time_incident)
	{
		$this->db->update('Interventions', array(
			'intervention' => $intervention,
			'timeincident' => $time_incident,
			'notes' => $notes,
		), array('interventionid' => $intervention_id));
	}	

	function find($school_id, $teacher_id = 0, $student_id = 0, $grade = 0, $group_id = 0, $start_time = 0, $end_time = 0, $expand_teacher = false, $expand_student = false)
	{
		$where = 'Interventions.schoolid = ?';
		$where_values = array($school_id);

		if($teacher_id !== 0)
		{
			$where .= ' AND Interventions.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Interventions.studentid = ?';
			array_push($where_values, $student_id);
		}

		if($start_time !== 0)
		{
			$where .= " AND IF(Interventions.timeincident=0, Interventions.timecreated, Interventions.timeincident) >= ?";
			array_push($where_values, $start_time);
		}

		if($end_time !== 0)
		{
			$where .= " AND IF(Interventions.timeincident=0, Interventions.timecreated, Interventions.timeincident) <= ?";
			array_push($where_values, $end_time);
		}

		$select = 'Interventions.*';
		$from = 'Interventions';

		if($expand_student)
		{
			$select .= ', s.firstname as studentfirstname, s.lastname as studentlastname';
			$from .= ", Users s";
			$where .= ' AND s.userid = Interventions.studentid';
		}

		if($expand_teacher)
		{
			$select .= ', t.firstname as teacherfirstname, t.lastname as teacherlastname';
			$from .= ", Users t";
			$where .= ' AND t.userid = Interventions.teacherid';
		}

		if($grade !== 0)
		{
			if(!$expand_student)
			{
				$from .= ", Users s";
				$where .= ' AND s.userid = Interventions.studentid';
			}

			$where = ' AND s.grade = ?';
			array_push($where_values, $grade);
		}

		if($group_id !== 0)
		{
			$from .= ', UserGroups';
			$where .= ' AND Interventions.studentid = UserGroups.userid AND UserGroups.groupid = ?';
			array_push($where_values, $group_id);
		}

		$where .= ' AND Interventions.status = ?';
		array_push($where_values, INTERVENTION_STATUS_ACTIVE);

		$query = $this->db->query('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY IF(Interventions.timeincident=0, Interventions.timecreated, Interventions.timeincident) DESC', $where_values);
		return $query->result();
	}

	function get_by_student($school_id, $student_id, $teacher_id = 0, $extra_teacher = false, $start_time = 0, $end_time = 0)
	{
		$where = 'Interventions.schoolid = ? AND Interventions.studentid = ? AND Interventions.status = ?';
		$where_values = array($school_id, $student_id, INTERVENTION_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= " AND Interventions.teacherid = ?";
			array_push($where_values, $teacher_id);
		}

		if($start_time !== 0)
		{
			$where .= " AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) >= ?";
			array_push($where_values, $start_time);
		}

		if($end_time !== 0)
		{
			$where .= " AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) <= ?";
			array_push($where_values, $end_time);
		}

		$select = '*';
		$from = "Interventions";

		if($extra_teacher)
		{
			$select .= ', Users.firstname as teacherfirstname, Users.lastname as teacherlastname';
			$from .= ', Users';
			$where .= ' AND Users.userid = Interventions.teacherid';
		}

		$query = $this->db->query('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) ASC', $where_values);

		return $query->result();
	}

	function get_by_teacher($school_id, $teacher_id, $student_id = 0, $extra_student = false, $start_time = 0, $end_time = 0)
	{
		$where = 'Interventions.schoolid = ? AND Interventions.teacherid = ? AND Interventions.status = ?';
		$where_values = array($school_id, $teacher_id, INTERVENTION_STATUS_ACTIVE);

		if($student_id !== 0)
		{
			$where .= " AND Interventions.studentid = ?";
			array_push($where_values, $student_id);
		}

		if($start_time !== 0)
		{
			$where .= " AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) >= ?";
			array_push($where_values, $start_time);
		}

		if($end_time !== 0)
		{
			$where .= " AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) <= ?";
			array_push($where_values, $end_time);
		}

		$select = '*';
		$from = "Interventions";

		if($extra_student)
		{
			$select .= ', Users.firstname as studentfirstname, Users.lastname as studentlastname';
			$from .= ', Users';
			$where .= ' AND Users.userid = Interventions.studentid';
		}

		$query = $this->db->query('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident)', $where_values);

		return $query->result();
	}

	function get_by_school($school_id, $teacher_id = 0, $student_id = 0, $extra_student = false, $extra_teacher = false, $start_time = 0, $end_time = 0)
	{
		$where = 'Interventions.schoolid = ? AND Interventions.status = ?';
		$where_values = array($school_id, INTERVENTION_STATUS_ACTIVE);

		if($student_id !== 0)
		{
			$where .= " AND Interventions.studentid = ?";
			array_push($where_values, $student_id);
		}

		if($teacher_id !== 0)
		{
			$where .= " AND Interventions.teacherid = ?";
			array_push($where_values, $teacher_id);
		}

		if($start_time !== 0)
		{
			$where .= " AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) >= ?";
			array_push($where_values, $start_time);
		}

		if($end_time !== 0)
		{
			$where .= " AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) <= ?";
			array_push($where_values, $end_time);
		}

		$select = 'Interventions.*';
		$from = "Interventions";

		if($extra_student)
		{
			$select .= ', s.firstname as studentfirstname, s.lastname as studentlastname';
			$from .= ', Users s';
			$where .= ' AND s.userid = Interventions.studentid';
		}

		if($extra_teacher)
		{
			$select .= ', t.firstname as teacherfirstname, t.lastname as teacherlastname';
			$from .= ', Users t';
			$where .= ' AND t.userid = Interventions.teacherid';
		}

		$query = $this->db->query('SELECT '.$select.' FROM '.$from.' WHERE '.$where.' ORDER BY IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) DESC', $where_values);

		return $query->result();
	}

	function get_intervention($intervention_id)
	{
		$query = $this->db->query('SELECT * FROM Interventions WHERE interventionid = ?', array($intervention_id));

		return $query->row();
	}

	function remove($intervention_id)
	{
		$this->db->update('Interventions', array('status' => INTERVENTION_STATUS_REMOVED), array('interventionid' => $intervention_id));
	}

	function remove_with_user($user_id)
	{
		$this->db->where('teacherid', $user_id);
		$this->db->or_where('studentid', $user_id); 
		$this->db->update('Interventions', array('status' => INTERVENTION_STATUS_REMOVED));
	}	

	/**
	 * Returns a total by type for interventions since period.
	 * @param $school_id school id
	 * @param $perid minimum time of interventions to fetch
	 * @param $teacher_id if not == 0, will limit intervention to just one teacher
	 */
	function category_totals($period, $school_id, $teacher_id = 0, $student_id = 0, $label_incident = 'incident', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where = 'schoolid = ? AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) > ? AND status = ?';
		$where_values = array($school_id, $time, INTERVENTION_STATUS_ACTIVE);

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

		$query = $this->db->query('SELECT intervention as '.$label_incident.', COUNT(*) as '.$label_total.' FROM Interventions WHERE '.$where.' GROUP BY intervention', $where_values);
		return $query->result();
	}

	/**
	 * Returns a total grouped by attribute for interventions this year.
	 * @param $school_id school id
	 */
	function category_totals_by($attribute = 'student.grade', $period, $school_id, $teacher_id = 0, $student_id, $label_grade = 'grade', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where_values = array($school_id, $time, INTERVENTION_STATUS_ACTIVE);
		$where = 'Interventions.schoolid = ? AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) > ? AND Interventions.status = ?';

		list($user_type, $field) = preg_split('/\./', $attribute);
		
		switch($user_type)
		{
			case 'teacher':
				$where .= ' AND Interventions.teacherid = Users.userid';
			break;
			case 'student':
				$where .= ' AND Interventions.studentid = Users.userid';
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
			$where .= ' AND Interventions.teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Interventions.studentid = ?';
			array_push($where_values, $student_id);
		}		

		$query = $this->db->query('SELECT '.$select.' FROM Interventions, Users WHERE '.$where.'GROUP BY '.$group_by, $where_values);

		return $query->result();
	}

	function count_today($period, $school_id, $teacher_id = 0)
	{	
		$time = get_time_period($period);

		$where = 'schoolid = ? AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) > ? AND status = ?'; 
		$where_values = array($school_id, $time, INTERVENTION_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where .= ' AND teacherid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total FROM Interventions WHERE '.$where, $where_values);

		return $query->row()->total;
	}	

	/**
	 * Returns the top intervention counts with student names.
	 * @param $school_id school id
	 * @param $limit number of results to limit by
	 * @param $teacher_id if != 0, will limit to interventions assigned by teacher.
	 */
	function top_interventions($period, $school_id, $teacher_id = 0, $limit = 5)
	{
		$time = get_time_period($period);

		$where = ' AND Interventions.schoolid = ? AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) > ? AND Interventions.status = ?';
		$where_values = array($school_id, $time, INTERVENTION_STATUS_ACTIVE);

		if($teacher_id !== 0)
		{
			$where = ' AND adminid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total, Users.userid, Users.profileimage, CONCAT(Users.lastname,", ",Users.firstname) as studentname FROM Interventions, Users WHERE Interventions.studentid = Users.userid '.$where.' GROUP BY Interventions.studentid ORDER BY total DESC LIMIT '.$limit, $where_values);

		return $query->result();
	}

	function count_by_interval($period, $interval, $school_id, $teacher_id = 0, $student_id = 0)
	{
		$time = get_time_period($period);
		list($group_by, $order_by) = get_time_interval('IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident)', $period, $interval);

		$where = ' AND IF(Interventions.timeincident=0,Interventions.timecreated,Interventions.timeincident) >= ? AND schoolid = ?';
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

		$query = $this->db->query('SELECT COUNT(*) as total, '.$group_by.' as label FROM Interventions WHERE status = 1'.$where.' GROUP BY '.$group_by.' ORDER BY '.$order_by, $where_values);

		return $query->result();
	}

}
?>