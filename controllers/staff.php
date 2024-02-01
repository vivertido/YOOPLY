<?php

class Staff extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function error()
	{
		$file = file('/var/www/vhosts/yoop.ly/statistics/logs/error_log');

		print_r($file);
	}
}

?>