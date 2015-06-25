<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * APIv2 AdminUI Pilot Login Controller
 *
 * @package	Login Controller
 * @author
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Login extends CI_Controller {

	function __construct() {
		parent::__construct();
		// no page caching
		$this->output->nocache();

		// bootstrap dashboard model
		$this->load->model("Dashboard_model", "", TRUE);
	}

	function index() {
		//set default attributes
		$data = array(
			'title' => "Login - Department of Labor - APIv2",
			'subtitle' => "DOL APIv2 - AdminUI",
			'action' => "login/validate_credentials",
			'login_content' => "login_view/login_form"		
		);
		$this->load->view("login/template", $data);
	}

	function validate_credentials() {
		
		// load database model
		$this->load->model("Dashboard_model");
		
		$query = $this->Dashboard_model->validate();
		
		//If the user's credentials validate...
		if ($query == AUTH_PASS) {
			// get user details
			$user = $this->Dashboard_model->get_by_user($user = $this->input->post("username"))->row();
			$is_admin = $this->Dashboard_model->is_user_admin($user->user_id);
							
			$data = array(
				"username" => $this->input->post("username"),
				"is_logged_in" => TRUE,
				"user_id" => $user->user_id,
				"is_admin" =>	$is_admin
			);

			$this->session->set_userdata($data);
			
			if($is_admin){
				redirect("access_control/admin/account_manager/?tab=users");
			}else{
				redirect("daas_control/daas/daas_manager");
			}
		} elseif ($query == PASS_RESET_REQUIRED) {
			$this->password_change_req();
		} else {			
			$data = array(
				'title' => "Validate User Account - Department of Labor - APIv2",					
				'subtitle' => "DOL APIv2 - AdminUI",
				'action' => "login/validate_credentials",
				'login_content' => "login_view/login_form",
				'error' => "<div class=\"alert alert-danger alert-dismissable\">
              		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;
					</button> Invalid Login Credentials</div>"
			);			
			$this->load->view("login/template", $data);		
		}	
	}

	// password change required method
	function password_change_req() {

		// set default parameters
		$data = array(
			'title' => "Password Change Required - Department of Labor - APIv2",
			'action' => site_url("login/password_change_process"),
			'user' => $this->input->post("username"),
			'login_content' => "login_view/password_change_req"	
		);		
		
		$this->load->view("login/template", $data);
	}

	function password_change_process() {
		// load database model
		$this->load->model("Dashboard_model");

		// set default parameters
		$data["title"] = "Password Change Required - Department of Labor - APIv2";
		$data["action"] = site_url("login/password_change_process");

		// set empty default form field values
		$this->_set_fields_login();
		// set validation properties
		$this->_set_rules_login();

		// run validation
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('data', validation_errors());				
			$data["message"] = "";
		} else {

			// get authenticated user id
			$acct = $this->Dashboard_model->get_by_user($user = $this->input->post("username"))->row();

			$this->form_data->user_id = $acct->user_id;
			$this->form_data->current_password = $acct->password;
			$this->form_data->password = $this->input->post("password");
			$this->form_data->password2 = $this->input->post("password2");

			// get user id post-back update
			$data["user_id"] = $acct->user_id;

			// validate current password against the user table
			$current_pass = $this->form_data->current_password;
			$new_password = md5($this->form_data->password);
			$conf_password = md5($this->form_data->password2);

			// get confirmed password and modify table
			$info = array(
				"confirmed_pass" => $conf_password,
				"user" => $acct->username);

			if (!empty($current_pass) && $new_password === $conf_password) {
				// call the password change method
				$this->password_change_appld($this->form_data->user_id, $info);

				// redirect to person list page
				redirect("login/?PasswordChange=success");
			} else {
				// set user message
				$data["error"] = "<div class=\"alert alert-danger alert-dismissable\">
	                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
								 		Incorrect current password
									</div>";
				// load view
				$data["login_content"] = "login_view/password_change_req";
				$this->load->view("login/template", $data);
			}
		}
	}

	// process require password change request
	protected function password_change_appld($user_id, $info) {

		$acct = array("password" => $info["confirmed_pass"],
				"password_reset" => "".PASS_RESET_NOT_REQUIRED."",
				"modified_date" => date("Y-m-d H:i:s"),
				"modified_by" => $info["user"]);

		// parse data array to update user table
		$this->Dashboard_model->update_password_prompt($user_id, $acct);

		// prepare email notification
		$this->email->set_newline("\r\n");
		$this->email->from("".FROM_EMAIL."", "".FROM_NAME."");
		$this->email->to($email);
		$this->email->cc("".CC_EMAIL."", "".CC_NAME."");
		$this->email->subject("Password reset");
		$this->email->message("Your password has been changed. Please return to the " .anchor("".base_url()."login", "Login"). " page to verify");
		$this->email->send();
	}

	// set empty default form field values
	function _set_fields_login() {
		$this->form_data->user_id = "";
		$this->form_data->current_password = "";
		$this->form_data->password = "";
		$this->form_data->password2 = "";
	}

	// validation rules
	function _set_rules_login() {
		$this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|min_length[6]|max_length[16]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[16]');
		$this->form_validation->set_rules('password2', 'Confirm Password', 'trim|required|min_length[6]|max_length[16]|matches[password]');

		$this->form_validation->set_message("required", "* required");
		$this->form_validation->set_message("isset", "* required");
		$this->form_validation->set_message("valid_date", "date format is not valid. dd-mm-yyyy");
		$this->form_validation->set_error_delimiters("<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>");
	}
}