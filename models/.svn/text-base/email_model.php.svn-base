<?php

class Email_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function email_report($parent, $student, $reporter, $form, $report)
	{
		$parent_hash = md5($parent->email);
		$view_token = md5($report->reportid.'YLPOOY'.$parent_hash.'SECURE'.$report->nonce);
		$to = $parent->email;

		$message = $this->load->view('email/report', array(
			'parent' => $parent, 
			'student' => $student,
			'reporter' => $reporter,
			'report' => $report,
			'form' => $form,
			'publictoken' => $parent_hash.'-'.$view_token
		), true);

		$subject = $form->title.' for '.$student->firstname.' '.$student->lastname;

  	mail($to, $subject, $message, 'From: "Yooply" <support@yoop.ly>');
	}

	/**
	 * Sends an email with a reset password link.
	 *
	 * @param object $user the user object representing user.
	 * @param object $reset the reset object representing the reset variables.	 
	 */
	function forgot_password($user, $reset)
	{
		$subject = "Reset Your Password";
		$to = $user->email;

		$message = $this->load->view('email/forgot_password', array(
			'user' => $user,
			'reset' => $reset,
		), true);

		//$this->load->model('Log_model');
		//$this->Log_model->forgot_password($user->userid, $reset->hashkey, $to, $subject, $message);

		mail($to, $subject, $message, 'From: "Yooply" <support@yoop.ly>');
	}
}

?>