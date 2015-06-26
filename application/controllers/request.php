<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * APIv2 AdminUI Request Controller
 *
 * @package	Request Controller
 * @author	johnsonpatrickk (Patrick Johnson Jr.)
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Request extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->get_anonymous();

		// load data model object
		$this->load->database("adminDB", TRUE);
	}

	public function index() {
		// set default attributes
		$data["title"] = "User Request - Quarry - APIv2";
		$data["subtitle"] = "User Request";
		$data["action"] = "request/access_process";
		$data["request_content"] = "request_view/signup_form";
		$data["error"] = "";

		// load view
		$this->load->view("request/template", $data);
	}

	public function access_process() {
		// field name, error message, validation rules
		$this->form_validation->set_rules("first_name", "First Name", "trim|required|min_length[2]|max_length[50]");
		$this->form_validation->set_rules("last_name", "Last Name", "trim|required|min_length[2]|max_length[50]");
		$this->form_validation->set_rules("email_address", "Email Address", "trim|required|is_unique[admin_user.email_address]|is_unique[admin_request.email_address]|valid_email");

		$this->form_validation->set_rules("password", "Password", "trim|required|min_length[6]|max_length[16]");
		$this->form_validation->set_rules("password2", "Confirm Password", "trim|required|min_length[6]|max_length[16]|matches[password]");
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value." );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );

		if ($this->form_validation->run() == FALSE) {

			$this->session->set_flashdata('data', validation_errors());
			$data["title"] = "User Request - Quarry - APIv2" ;
			$data["subtitle"] = "APIv2 - User Request";
			$data["error"] = "";

			$data["request_content"] = "request_view/signup_form";
			redirect("request/");

		} else {
			// after server validation, prepare data for database post
			$this->data_process();
		}
	}

	private function data_process() {
		// load database model
		$this->load->model("Dashboard_model");

		// validation complete
		$response = $this->Dashboard_model->admin_request();

		if ($response == NO_DUPLICATE_ADMIN) {

			// If registration is successful, send an email notification to the administrator
			$name = $this->input->post("first_name")." ".$this->input->post("last_name");
			$email = $this->input->post("email_address");

			$this->email->set_newline("\r\n");

			$this->email->from("".FROM_EMAIL."", "".FROM_NAME."");
			$this->email->to("".APPROVAL_ADMIN."");
			//$this->email->cc($email);
			$this->email->subject("APIv2 AdminUI User Request");
			$body = "
				<table border=\"1\" style=\"border-width: thin; border-spacing: 2px; border-style: none; border-color: #ccc;\">
				<tr>
				<td>Requestor</td>
				<td>{$name}</td>
				</tr>
				<tr>
				<td>User Request</td>
				<td>{$name} has requested access to APIv2 AdminUI. <br/>Please " .anchor("".base_url()."login", "Login"). "  to approve this request.</td>
				</tr>
				</table>
			";
			$this->email->message($body);

			if ($this->email->send()) {
			//echo 'Your message was sent successfully...';
				$data["request_content"] = "request_view/signup_successful";
				$this->load->view("request/template", $data);
			} else {
				show_error($this->email->print_debugger());
			}
		} elseif ($response == DUPLICATE_ADMIN) {
			// set default attributes
			$data["title"] = "User Request - Quarry - APIv2";
			$data["subtitle"] = "APIv2 AdminUI - User Request";
			$data["action"] = "request/access_process";
			$data["request_content"] = "request_view/signup_form";
			// set user message
			$data["error"] = "<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
							 		User already in use...
								</div>";

			$this->load->view("request/template", $data);
		} elseif ($response == DUPLICATE_REG)  {
			// set default attributes
			$data["subtitle"] = "APIv2 AdminUI - User Request";
			$data["action"] = "request/access_process";
			$data["request_content"] = "request_view/signup_form";
			// set user message
			$data["error"] = "<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
							 		User already registered. Contact the " .anchor("".base_url()."request/registration_error?requestor=".$this->input->post("first_name")."
							 				".$this->input->post("last_name")."&email=".$this->input->post("email_address"), "Admin"). "
								</div>";

			$this->load->view("request/template", $data);
		}

	}

	// registration error form request
	public function registration_error() {
		// set default attributes
		$data["subtitle"] = "Quarry AdminUI - Registration Error";
		$data["requestor"] = $this->input->post("first_name")." ".$this->input->post("last_name");
		$data["action"] = "request/process_reg_error";
		$data["request_content"] = "request_view/registration_error";
		$data["error"] = "";
		$data["success"] = "";

		// load view
		$this->load->view("request/template", $data);
	}

	// process user request assistance
	function process_reg_error() {
		// load database model
		$this->load->model("Dashboard_model");

		// set default attributes
		$contact_name = $this->input->post("contact_name");;
		$from_email = $this->input->post("email_address");
		$message = $this->input->post("message_area");

		//print_r($_POST); exit;

		// set empty default form field values
		$this->_set_error_fields();
		// set validation properties
		$this->_set_error_rules();

		// run validation
		if ($this->form_validation->run() == FALSE) {
			$data["message"] = "";
		} else {
			// parse data array to update user table
			$data = array(
				"requestor" => $contact_name,
				"requestor_email" => $from_email,
				"requestor_ip" => $this->input->ip_address(),
				"message" => $message,
				"message_date" => date("Y-m-d H:i:s")
			);
			// keep track of messages
			$this->Dashboard_model->reg_error_message($data);

			// prepare email notification
			$this->email->set_newline("\r\n");
			$this->email->from("".FROM_EMAIL."", "".FROM_NAME."");
			$this->email->to("".CC_EMAIL."", "".CC_NAME."");
			$this->email->cc($from_email);
			$this->email->subject("Quarry AdminUI - Registration Error");
			$body = "
				<table border=\"1\" style=\"border-width: thin; border-spacing: 2px; border-style: none; border-color: #ccc;\">
				<tr>
					<td>Requestor</td>
					<td>{$contact_name}</td>
				</tr>
				<tr>
					<td>Message</td>
					<td width=\"250\">{$message}</td>
				</tr>
				</table>
			";
			$this->email->message($body);
			$this->email->send();

			// load view
			$data["subtitle"] = "Quarry AdminUI - Registration Error";
			$data["requestor"] = $contact_name;
			$data["email_add"] = $from_email;
			$data["action"] = "request/process_reg_error";
			$data["request_content"] = "request_view/registration_error";
			$data["error"] = "";

			// set user message
			$data["success"] = "<div class=\"alert alert-success alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                                	Message sent, please allow 24Hrs for response.
                            	</div>";

			// load view
			$this->load->view("request/template", $data);
		}
	}

	private function get_anonymous() {
		// get anonymous user IP and environment variables
		$anonymous = array("ipaddress" => $this->input->ip_address(),
					"user_agent" => $this->input->user_agent()
		);

		return $anonymous;
	}

	// password reset form
	function password_reset() {
		// load default parameters
		$data["title"] = "Password Reset - Quarry - APIv2";
		$data["subtitle"] = "Password Reset";
		$data["action"] = site_url("request/password_reset_process");
		$data["success"] = "";
		$data["error"] = "";

		// load view
		$data["request_content"] = "request_view/password_reset";
		$this->load->view("request/template", $data);
	}

	// password reset request view form processor
	function password_reset_process() {
		// load database model
		$this->load->model("Dashboard_model");

		// set empty default form field values
		$this->_set_fields_passreset();
		// set validation properties
		$this->_set_rules_passreset();

		// email address entered by requestor
		$email = $this->input->post("email_address");
		$data["title"] = "Password Reset - Quarry - APIv2";
		$data["subtitle"] = "Password Reset";		
		$data["action"] = site_url("request/password_reset_process");
		$data["success"] = "";
		$data["error"] = "";

		// get email address from dashboard model
		$acct = $this->Dashboard_model->passreset($email)->row();

		if (!empty($acct)) {
			// obtain some basic account info for verification...
			$this->form_data->user_id = $acct->user_id;
			$this->form_data->username = $acct->username;
			$this->form_data->email_address = $acct->email_address;

			// call password reset method
			$this->reset_password_allowed($user_id = $this->form_data->user_id, $email = $this->form_data->email_address);
			// set user message
			$data["success"] = "<div class=\"alert alert-success alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
                                	Password changed. Check your email...
                            	</div>";

			// load password reset view
			$data["request_content"] = "request_view/password_reset";
			$this->load->view("request/template", $data);
		} else {
			// set user message
			$data["error"] = "<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>
							 		Invalid email address
								</div>";

			// load password reset view
			$data["request_content"] = "request_view/password_reset";
			$this->load->view("request/template", $data);
		}
	}

	// reset user password after validation.
	protected function reset_password_allowed($user_id, $email) {
		// load database model
		$this->load->model("Dashboard_model");

		//date_default_timezone_set("GMT");
		// generate random password
		$password = random_string("alnum", 16);
		$acct = array("password" => md5($password),
				"password_reset" => "1",
				"modified_date" => date("Y-m-d H:i:s"));

		// parse data array to update user table
		$this->Dashboard_model->update_password($user_id, $acct);

		// prepare email notification
		$this->email->set_newline("\r\n");
		$this->email->from("".FROM_EMAIL."", "".FROM_NAME."");
		$this->email->to($email);
		$this->email->cc("".CC_EMAIL."", "".CC_NAME."");
		$this->email->subject("Password reset");
		$this->email->message("You have requested a password change, here's your new password: ". $password);
		$this->email->send();
	}

	// set empty default form field values
	function _set_fields_passreset() {
		$this->form_data->user_id = "";
		$this->form_data->email_address = "";
	}

	// validation rules
	function _set_rules_passreset() {
		$this->form_validation->set_rules("email_address", "email address", "trim|required|valid_email");
		$this->form_validation->set_message("required", "* required");
		$this->form_validation->set_message("isset", "* required");
		$this->form_validation->set_message("valid_date", "date format is not valid. dd-mm-yyyy");
		$this->form_validation->set_error_delimiters("<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>");
	}

	// set empty default form field values
	function _set_error_fields() {
		$this->form_data->contact_name = "";
		$this->form_data->message_area = "";
	}

	// validation rules
	function _set_error_rules() {
		$this->form_validation->set_rules("contact_name", "contact name", "trim|required");
		$this->form_validation->set_rules("message_area", "message", "trim|required");

		$this->form_validation->set_message("required", "* required");
		$this->form_validation->set_message("isset", "* required");
		$this->form_validation->set_error_delimiters("<div class=\"alert alert-danger alert-dismissable\">
                                	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>");
	}
}
