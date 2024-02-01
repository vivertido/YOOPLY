<?php
class Message extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function compose($recipient_id)
	{
		$user_id = 4;

		if($this->input->post('submit'))
		{
			$message = $this->input->post('message');

			$this->load->model('Message_model');
			$this->Message_model->create($user_id, $recipient_id, $body);

			redirect('teacher');
		}
		else
		{
			$this->layout->view('message/compose', array(
				'title_for_layout' => 'Compose message'
			));
		}
	}

	function index($message_id)
	{
		$this->load->model('Message_model');
		$messages = $this->Message_model->get_messages($user_id);

		$this->layout->view('message/index', array(
			'title_for_layout' => 'Compose message',
			'messages' => $messages
		));
	}

}
?>