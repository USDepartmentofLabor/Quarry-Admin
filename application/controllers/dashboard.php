<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Quarry AdminUI Pilot Dashboard Controller
 *
 * @package	Dashboard Controller
 * @author 	johnsonpatrickk
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Dashboard extends CI_Controller {

	// number of records per page
	private $limit = 10;
	// set acl varaible
	private $acl_conf;
	private $super_title='Quarry AdminUI Dashboard';
	private $version = '';
	private $version_title = '';	

	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		// no page caching
		//$this->output->nocache();

		// bootstrap dashboard and access control model
		$this->load->model("adminuiacl_model","", TRUE);
		$this->load->model ( "version_model", "", TRUE );
		
		$this->version = $this->version_model->get_version();
		$this->version_title = $this->version_model->get_name();
		$this->super_title = $this->version_model->get_name().' '.$this->version_model->get_product().' '.$this->version;
				
		$this->acl_conf = (object)$this->config->item("acl");
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata("is_logged_in");
		//print_r($this->session->all_userdata()); exit;

		if (!isset($is_logged_in) || $is_logged_in != TRUE)
		{
			echo "You don't have permission to access this page.". anchor("/login", "Login Now");
			die();
		}
	}
	// load admin menu
	function dashboard_menu()
	{
		$data ["title"] = $this->super_title;
		$data["main_content"] = "dashboard_view/dashboard_menu";
		$data ["version_official_name"] = $this->version_title;
		$this->load->view("dashboard/template",  $data);
	}

	// load full dashboard control panel
	function dashboard_admin()
	{		
		$data ["title"] = $this->super_title;
		$data["main_content"] = "dashboard_view/dashboard_admin";
		$data ["version_official_name"] = $this->version_title;
		$this->load->view("dashboard/template", $data);
	}

	function delete($user_id) {
		// delete person
		$this->Person_model->delete($user_id);

		// redirect to person list page
		redirect("person/index/","refresh");
	}

	// set empty default form field values
	protected function _set_fields()
	{
		$this->form_data->user_id = "";
		$this->form_data->first_name = "";
		$this->form_data->last_name = "";
		$this->form_data->email_address = "";
		$this->form_data->status = "";
		$this->form_data->username = "";

		if ($this->input->post("password"))
		{
			$this->form_data->current_password = "";
			$this->form_data->password = "";
			$this->form_data->password2 = "";
		}
	}

	// validation rules
	protected function _set_rules()
	{
		$this->form_validation->set_rules("first_name", "First Name", "trim|required");
		$this->form_validation->set_rules("last_name", "Last name", "trim|required");
		$this->form_validation->set_rules("status", "Account Status", "trim|required");
		$this->form_validation->set_rules("username", "Username", "trim|required|valid_email");
		$this->form_validation->set_rules("email_address", "Email Address", "trim|required|valid_email");

		if ($this->input->post("password"))
		{
			$this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[32]');
			$this->form_validation->set_rules('password2', 'Confirm Password', 'trim|required|min_length[6]|max_length[32]|matches[password]');
		}

		$this->form_validation->set_message("required", "* required");
		$this->form_validation->set_message("isset", "* required");
		$this->form_validation->set_message("valid_date", "date format is not valid. dd-mm-yyyy");
		$this->form_validation->set_error_delimiters("<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>");
	}

	// date_validation callback
	function valid_date($str)
	{
		//match the format of the date
		if (preg_match ("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $str, $parts))
		{
			//check weather the date is valid of not
			if(checkdate($parts[2],$parts[1],$parts[3]))
				return true;
			else
				return false;
		}
		else
			return false;
	}

	function logout()
	{
		$this->session->unset_userdata("is_logged_in");
   		session_destroy();
  		redirect("login");
	}
}