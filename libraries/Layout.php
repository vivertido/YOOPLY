<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{

    var $obj;
    var $layout;

    function Layout($layout = "layout_main")
    {
        $this->obj =& get_instance();
        $this->layout = $layout;
    }

    function setLayout($layout)
    {
      $this->layout = $layout;
    }

    function view($view, $data=null, $return=false)
    {
        $loadedData = $data;
        

        $school_id = $this->obj->session->userdata('schoolid');
        
        if($this->obj->session->userdata('schoolid'))
        {
            $this->obj->load->model('Settings_model');
            $loadedData['school_features'] = json_decode($this->obj->Settings_model->get_settings($school_id, SETTINGS_FEATURES));            
            $loadedData['labels'] = json_decode($this->obj->Settings_model->get_settings($school_id, 'labels'));            
        }

        if($this->obj->session->userdata('userid'))
        {
            $this->obj->load->model('Notification_model');
            $loadedData['notificationcount'] = $this->obj->Notification_model->get_unread_count($this->obj->session->userdata('userid'));
        }
        
        $loadedData['content_for_layout'] = $this->obj->load->view($view,$loadedData,true);

        if(true) //FEATURE_PAGELOAD)
		{
			$this->obj->load->model('Log_model');
			$this->obj->Log_model->page_load(array(
				'url' => $_SERVER['REQUEST_URI'],
				'queries' => $this->obj->db->queries,
				'post' => $this->obj->input->post(),
			));
		}

        if($return)
        {
            $output = $this->obj->load->view($this->layout, $loadedData, true);
            return $output;
        }
        else
        {
            $this->obj->load->view($this->layout, $loadedData, false);
        }
    }
}
?>
