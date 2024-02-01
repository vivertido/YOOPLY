<?php
define('CLEVER_ENDPOINT', 'https://api.clever.com');
//define('CLEVER_KEY', 'DEMO_KEY');
//define('CLEVER_KEY', '0dfcd345430'); // no longer valid
//define('CLEVER_KEY', 'IqitEQy9bxb'); // no longer valid
//define('CLEVER_KEY', 'gWhUw8GdGr1'); // Old ascend, learning without limits
define('CLEVER_KEY', '097f2427fbd1a05a456dcffed6826edd906da6c5'); // Old ascend, learning without limits
class Cleverapi
{
	var $oauth_token;

	function __construct()
	{

	}

	function set_token($oauth_token)
	{
		$this->oauth_token = $oauth_token;
	}

	function get_districts()
	{
		$json = $this->_makerequest('/v1.1/districts', array());

		return $json;
	}

	function get_schools($district_id)
	{
		$json = $this->_makerequest('/v1.1/districts/'.$district_id.'/schools', array());

		return $json;
	}

	function get_school($school_id)
	{
		$json = $this->_makerequest('/v1.1/schools/'.$school_id, array());

		return $json;		
	}

	function get_teachers($school_id)
	{
		$json = $this->_makerequest('/v1.1/schools/'.$school_id.'/teachers', array());

		return $json;
	}

	function get_admins($school_id)
	{
		$json = $this->_makerequest('/v1.1/school_admins', array());

		$admins = array();

		foreach($json->data as $admin)
		{
			if(in_array($school_id, $admin->data->schools))
			{
				array_push($admins, $admin);
			}
		}
	
		return $admins;
	}	

	function get_teacher_count($school_id)
	{
		$json = $this->_makerequest('/v1.1/schools/'.$school_id.'/teachers', array('count' => 'true'));

		return $json->count;
	}

	function get_admin_count($school_id)
	{
		$json = $this->_makerequest('/v1.1/school_admins', array());

		$admins = array();

		foreach($json->data as $admin)
		{
			if(in_array($school_id, $admin->data->schools))
			{
				array_push($admins, $admin);
			}
		}

		return count($admins);
	}

	function get_students($school_id, $starting_after = 0)
	{
		$data = $starting_after == 0 ? array() : array('starting_after' => $starting_after);

		$json = $this->_makerequest('/v1.1/schools/'.$school_id.'/students', $data);

		return $json;
	}

	function get_student_count($school_id)
	{
		$json = $this->_makerequest('/v1.1/schools/'.$school_id.'/students', array('count' => 'true'));

		return $json->count;
	}

	function get_sections($school_id)
	{
		$json = $this->_makerequest('/v1.1/schools/'.$school_id.'/sections', array('limit' => '1000'));

		return $json;
	}

	function get_section_count($school_id)
	{
		$json = $this->_makerequest('/v1.1/schools/'.$school_id.'/sections', array('count' => 'true'));

		return $json->count;
	}

	function get_next_page($next_page)
	{
		$json = $this->_makerequest($next_page, array());

		return $json;
	}

	function _makerequest($method, $params)
	{
		$url = CLEVER_ENDPOINT.$method;
		$url .= empty($params) ? '' : '?'.http_build_query($params);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->oauth_token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);

		return json_decode($response);
	}

	function _postrequest($method, $params)
	{
		$url = CLEVER_ENDPOINT.$method;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$response = curl_exec($ch);

		$r = preg_split("/\r\n\r\n/", $response);

		$content = array_pop($r);
		$header = implode("\r\n\r\n", $r);

		//$ci =& get_instance();
		//$ci->db->insert('Fetches', array('timecreated' => time(), 'header' => $header, 'post' => json_encode($params), 'url' => $url, 'response' => $content));

		return json_decode($content);
	}

}
?>