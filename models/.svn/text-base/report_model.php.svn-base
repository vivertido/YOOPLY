<?php

define('REPORT_STATUS_ACTIVE', 1);

class Report_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function create($school_id, $user_id, $subject_id, $type, $object_id, $report, $title, $time_incident = 0)
	{
		$this->db->insert('Reports', array(
			'schoolid' => $school_id,
			'userid' => $user_id,
			'subjectid' => $subject_id,
			'type' => $type,
			'objectid' => $object_id,
			'report' => json_encode($report),
			'title' => $title,
			'timecreated' => time(),
			'timeincident' => $time_incident,
			'status' => 1,
			'nonce' => md5(time().$title.rand())
		));

		return $this->db->insert_id();
	}

	function update($report_id, $report, $title, $time_incident)
	{
		$this->db->update('Reports', array(
			'report' => json_encode($report),
			'title' => $title,
			'timeincident' => $time_incident,
		), array('reportid' => $report_id));		
	}

	function update_charts($report_id, $report, $title, $permission)
	{
		$this->db->update('Reports', array(
			'report' => json_encode($report),
			'title' => $title,
			'objectid' => $permission,
		), array('reportid' => $report_id));		
	}	

	function get_reports($school_id, $type, $object_id)
	{
		$where = '';
		$where_values = array($school_id, $type);

		switch($type)
		{
			case 'charts':
				// Bitwise operation. If viewer bit is set, we show it.
				$where = ' AND BIT_COUNT(objectid & '.$object_id.') > 0';
			break;
			default:
				$where = ' AND objectid = ?';
				array_push($where_values, $object_id);
			break;
		}

		$query = $this->db->query('SELECT * FROM Reports WHERE status = 1 AND schoolid = ? AND type = ?'.$where, $where_values);

		return $query->result();
	}

	function get_by_form($form_id, $subject = '-', $start = 0, $end = 0, $include_name = false, $order = 'DESC')
	{
		$where = 'Reports.status = 1 AND type = "form" AND objectid = ?';
		$where_values = array($form_id);

		if($subject != '-')
		{
			$where .= ' AND subjectid = ?';
			array_push($where_values, $subject);
		}

		if($start !== 0)
		{
			$where .= ' AND IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident) >= ?';
			array_push($where_values, $start);
		}

		if($end !== 0)
		{
			$where .= ' AND IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident) <= ?';
			array_push($where_values, $end);
		}

		$order_by = ' ORDER BY timecreated '.($order == 'DESC' ? 'DESC' : 'ASC');

		if($include_name)
		{
			$where .= ' AND s.userid = Reports.subjectid AND u.userid = Reports.userid';
			$query = $this->db->query('SELECT Reports.*, s.firstname as subjectfirstname, s.lastname as subjectlastname, u.firstname, u.lastname FROM Reports, Users s, Users u WHERE '.$where.$order_by, $where_values);
		}
		else
		{
			$where .= ' AND u.userid = Reports.userid';
			$query = $this->db->query('SELECT Reports.*, u.firstname, u.lastname FROM Reports, Users u WHERE '.$where.$order_by, $where_values);
		}

		return $query->result();
	}	

	function get_report($report_id)
	{
		return $this->get_response($report_id);
	}
	
	function get_response($response_id)
	{
		$query = $this->db->query('SELECT * FROM Reports WHERE reportid = ?', array($response_id));

		return $query->row();	
	}

	function remove_form($form_id)
	{
		$this->db->update('Reports', array('status' => 0), array('type' => 'form', 'objectid' => $form_id, 'status' => '1'));
	}

	function remove($response_id)
	{
		$this->db->update('Reports', array('status' => 0), array('reportid' => $response_id, 'status' => '1'));
	}

	function remove_with_user($user_id)
	{
		$this->db->where('userid', $user_id);
		$this->db->or_where('subjectid', $user_id); 
		$this->db->update('Reports', array('status' => 0));
	}	

	function count_by_interval($period, $interval, $type, $form_id, $school_id, $teacher_id = 0, $student_id = 0)
	{
		$time = get_time_period($period);
		list($group_by, $order_by) = get_time_interval('IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident)', $period, $interval);

		$where = ' AND IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident) >= ? AND schoolid = ? AND type = ? AND objectid = ?';
		$where_values = array($time, $school_id, $type, $form_id);

		if($teacher_id !== 0)
		{
			$where .= ' AND userid = ?';
			array_push($where_values, $teacher_id);
		}		

		if($student_id !== 0)
		{
			$where .= ' AND subjectid = ?';
			array_push($where_values, $student_id);
		}		

		$select = 'COUNT(*) as total';
		$select .= !empty($group_by) ? ', '.$group_by.' as label' : ''; 
		
		$group_by = !empty($group_by) ? ' GROUP BY '.$group_by : '';
		$order_by = !empty($order_by) ? ' ORDER BY '.$order_by : '';

		

		$query = $this->db->query('SELECT '.$select.' FROM Reports WHERE status = 1'.$where.$group_by.$order_by, $where_values);

		return $query->result();
	}

	function category_totals($period, $type, $form_id, $school_id, $teacher_id = 0, $student_id = 0, $label_title = 'title', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where = 'type = ? AND objectid = ? AND schoolid = ? AND IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident) > ? AND status = ?';
		$where_values = array($type, $form_id, $school_id, $time, 1);

		if($teacher_id !== 0)
		{
			$where .= ' AND userid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND subjectid = ?';
			array_push($where_values, $student_id);
		}

		$query = $this->db->query('SELECT title as '.$label_title.', COUNT(*) as '.$label_total.' FROM Reports WHERE '.$where.' GROUP BY title', $where_values);
		return $query->result();
	}

	/**
	 * Returns a total by grade for detentions since the time period.
	 * @param $school_id school id
	 */
	function category_totals_by($attribute = 'student.grade', $period, $type, $object_id, $school_id, $teacher_id = 0, $student_id = 0, $label_grade = 'grade', $label_total = 'total')
	{
		$time = get_time_period($period);

		$where_values = array($type, $object_id, $school_id, $time, 1);
		$where = 'Reports.type = ? AND Reports.objectid = ? AND Reports.schoolid = ? AND IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident) > ? AND Reports.status = ?';

		list($user_type, $field) = preg_split('/\./', $attribute);
		
		switch($user_type)
		{
			case 'teacher':
				$where .= ' AND Reports.userid = Users.userid';
			break;
			case 'student':
				$where .= ' AND Reports.subjectid = Users.userid';
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
			$where .= ' AND Reports.userid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND Reports.subjectid = ?';
			array_push($where_values, $student_id);
		}

		$query = $this->db->query('SELECT '.$select.' FROM Reports, Users WHERE '.$where.' GROUP BY '.$group_by, $where_values);

		return $query->result();
	}

	function count_today($period, $type, $object_id, $school_id, $teacher_id = 0, $student_id = 0)
	{
		$time = get_time_period($period);

		$where = '';
		$where_values = array($school_id, $time, $type, $object_id);

		if($teacher_id !== 0)
		{
			$where .= ' AND Reports.userid = ?';
			array_push($where_values, $teacher_id);
		}

		if($student_id !== 0)
		{
			$where .= ' AND subjectid = ?';
			array_push($where_values, $student_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total FROM Reports WHERE schoolid = ? AND IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident) > ? AND type = ? AND objectid = ? AND status = 1'.$where, $where_values);

		return $query->row()->total;
	}	

	/**
	 * Returns the top report counts with student names.
	 * @param $school_id school id
	 * @param $limit number of results to limit by
	 * @param $teacher_id if != 0, will limit to reports assigned by teacher.
	 */
	function top_reports($period, $school_id, $type, $object_id, $teacher_id = 0, $limit = 5)
	{
		$time = get_time_period($period);

		$where = ' AND Reports.schoolid = ? AND IF(Reports.timeincident=0,Reports.timecreated,Reports.timeincident) > ? AND Reports.status = ? AND Reports.type = ? AND Reports.objectid = ?';
		$where_values = array($school_id, $time, REPORT_STATUS_ACTIVE, $type, $object_id);

		if($teacher_id !== 0)
		{
			$where = ' AND userid = ?';
			array_push($where_values, $teacher_id);
		}

		$query = $this->db->query('SELECT COUNT(*) as total, Users.userid, Users.profileimage, CONCAT(Users.lastname,", ",Users.firstname) as studentname FROM Reports, Users WHERE Reports.subjectid = Users.userid '.$where.' GROUP BY Reports.subjectid ORDER BY total DESC LIMIT '.$limit, $where_values);

		return $query->result();
	}		
}

?>