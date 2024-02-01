<?php

class Library_model extends CI_Model
{
	function __construct() 
	{
		parent::__construct();
	}

	function get_resources($packages)
	{
		$query = $this->db->query('SELECT Library.*, LibraryInstall.timeinstalled FROM Library LEFT JOIN LibraryInstall ON Library.libraryid = LibraryInstall.libraryid AND LibraryInstall.timeuninstalled = 0 WHERE package IN (?)', array(implode(',', $packages)));

		return $query->result();
	}

	function get_resource($resource_id)
	{
		$query = $this->db->query('SELECT * FROM Library WHERE libraryid = ?', array($resource_id));

		return $query->row();
	}

	function create($type, $package, $title, $description, $meta_data)
	{
		$this->db->insert('Library', array(
			'resourcetype' => $type,
			'package' => $package,
			'title' => $title,
			'description' => $description,
			'metadata' => json_encode($meta_data)
		));
	}

	function install($library_id, $school_id, $user_id, $meta_data)
	{
		$this->db->insert('LibraryInstall', array(
			'libraryid' => $library_id,
			'schoolid' => $school_id,
			'userid' => $user_id,
			'timeinstalled' => time(),
			'timeuninstalled' => 0,
			'metadata' => json_encode($meta_data)
		));
	}

	function uninstall($library_id, $school_id, $user_id)
	{
		$this->db->update('LibraryInstall', 
			array(
				'timeuninstalled' => time()
			), 
			array(
				'libraryid' => $library_id, 
				'schoolid' => $school_id, 
				'userid' => $user_id
			)
		);
	}

	function is_installed($resource_id, $school_id, $user_id)
	{
		$query = $this->db->query('SELECT * FROM LibraryInstall WHERE libraryid = ? AND schoolid = ? AND userid = ? AND timeuninstalled = 0', array($resource_id, $school_id, $user_id));

		$result = $query->row();

		return !empty($result);
	}

	function get_install($resource_id, $school_id, $user_id)
	{
		$query = $this->db->query('SELECT * FROM LibraryInstall WHERE libraryid = ? AND schoolid = ? AND userid = ? ORDER BY timeuninstalled', array($resource_id, $school_id, $user_id));

		return $query->row();

	}
}