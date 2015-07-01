<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * APIv2 AdminUI DaaS Controller
 *
 * @package	DaaS Controller
 * @author johnsonpatrickk (Patrick Johnson Jr.)
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Daas extends CI_Controller {
	
	private $daas_mgr_table;
	private $limit = 10;
	private $timeout = 10;
	private $prompt = '$';
	private $dataset_limit = 15;
	private $acl_table;
	private $super_title='';
	private $version = '';
	private $version_title = '';
		
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		
		// no page caching
		$this->output->nocache();
		$this->load->helper('date');
		
		// bootstrap app and access control model
		$this->load->model("daas_model","", TRUE);
		$this->load->model("adminuiacl_model","", TRUE);
		//$this->load->database("apiv2", TRUE);
		$this->load->model ( "version_model", "", TRUE );
		
		
		// load virtual db connection config file
		$this->daas_mgr_table = (object)$this->config->item("apiv2");
		$this->acl_conf = (object)$this->config->item("acl");
		$this->rest_conf = (object)$this->config->item("rest");
		$this->acl_table =& $this->acl_conf->table;	

		$this->version = $this->version_model->get_version();
		$this->version_title = $this->version_model->get_name();
		$this->super_title = $this->version_model->get_name().' '.$this->version_model->get_product().' '.$this->version;
		
	}
	
	public function is_logged_in()
	{
		$is_logged_in = $this->session->userdata("is_logged_in");
		//print_r($this->session->all_userdata()); exit;
	
		if (!isset($is_logged_in) || $is_logged_in != TRUE)
		{
			redirect("/login");
			//echo "You don't have permission to access this page. ". anchor("/login", "Login Now");
			//die();
		}
	}
	
	public function daas_manager($offset = 0)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_dataset"))
		{
			show_error("Permission denied.", 401);		
		}		
		//offset
		$uri_segment = 4;
		//$offset = $this->uri->segment($uri_segment);
				
		// generate pagination
		$config = array(
			'base_url' => site_url('daas_control/daas/daas_manager/'),
			'total_rows' => $this->daas_model->count_all(),
			'per_page' => $this->limit,
			'uri_segment' => $uri_segment,
			'suffix' => '/?tab=daas_list',
			'full_tag_open' => '<ul class="pagination pagination-mm">',
			'full_tag_close' => '</ul>',
			'num_tag_open' => '<li>',
			'num_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><span>',
			'cur_tag_close' => '<span class="sr-only">(current)</span></span></li>',
			'prev_tag_open' => '<li>',
			'prev_tag_close' => '</li>',
			'next_tag_open' => '<li>',
			'next_tag_close' => '</li>',
			'first_link' => 'First',
			'prev_link' => 'Previous',
			'first_url' => site_url('daas_control/daas/daas_manager/?tab=daas_list'),
			'last_link' => 'Last',
			'next_link' => 'Next',
			'first_tag_open' => '<li>',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li>',
			'last_tag_close' => '</li>'
		);		

		// Need to remember the last pagination number in order to properly link back from an action
		$this->session->set_userdata('last_daas_pagination', current_url());
				
		// PHP 5.4 and above requires stdClass be declare on an empty object
		$this->form_data = new stdClass;
		
		// load data
		$services = $this->daas_model->get_service_list($this->limit, $offset)->result();						
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data["subtitle"] = "List of Data Services";
		$data["panel_title"] = "Application Source";
		
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();		
		$data["add_daas"] = site_url("daas_control/daas/daas_manager/?tab=add_daas");
		$data["add_daas_process"] = site_url("daas_control/daas/add_daas");
		$data ["link_back"] = anchor ('daas_control/daas/daas_manager/?tab=daas_list', " Back to Service List");
		
		// get relational database types...
		$db_type = $this->daas_model->get_rdbms_list();
		
		$data["dbtype"] = $db_type;

		// generate table data
		$this->table->set_empty("&nbsp;");
		$table_setup = array("table_open" => "<table class=\"table table-striped table-bordered table-hover\">");
		$this->table->set_heading("DB Type", "Host/IP", "API User", "DB Name", "DB Table", "Alias", "DB Port", "Date Created", "Actions");
		$i = 0 + $offset;
		$view = array("class" => "btn btn-success btn-sm", "title" => "View Details", "title" => "View Details");
		$update = array("class" => "btn btn-warning btn-sm",  "title" => "Modify Connection", "title" => "Modify Connection");
		$check_status = array("class" => "btn btn-primary btn-sm", "title" => "Check Connection", "title" => "Check Connection");
		$delete = array("class" => "btn btn-danger btn-sm", "data-toggle" => "confirmation", "title" => "Delete Connection", "title" => "Delete Connection");
		
		foreach ($services as $cstring)
		{
			if (!empty($cstring->daas_instance))
			{
				$hostname = $cstring->daas_host."\\".$cstring->daas_instance;
			}
			else
			{
				$hostname = $cstring->daas_host;
			}
			$this->table->add_row($cstring->db_type, $hostname, $cstring->daas_user, $cstring->daas_dbname, $cstring->daas_table, $cstring->daas_table_alias, $cstring->daas_port, date("m/d/Y", strtotime($cstring->date_created)),
					anchor("daas_control/daas/daas_view/".$cstring->daas_id."#daas-service", "<span>View</span>", $view)." ".
					anchor("daas_control/daas/daas_modify/".$cstring->daas_id, "<span>Update</span>", $update)." ".
					anchor("daas_control/daas/daas_delete/".$cstring->daas_id, "<span>Delete</span>", $delete)." ".
					anchor("daas_control/daas/daas_view/".$cstring->daas_id."#connect-test", "<span>Test</span>", $check_status)
			);
		}
		
		$this->table->set_template($table_setup);
		$data["table"] = $this->table->generate();
		
		// load account view
		$data["daas_content"] = "daas_manager_view/daas_manager";
		
		if(isset($this->validation_results)){
			$data['validation_errors'] = $this->validation_results;
		}else{
			$data['validation_errors'] = '';
		}		
		$this->load->view("daas_manager/template", $data);
	}
	
	public function daas_view($daas_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_dataset"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		$page_index = $this->session->userdata('last_daas_pagination');
				
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data["subtitle"] = "View Data Service";
		$data["link_back"] = anchor($page_index.'/?tab=daas_list', "Back to Service List");		
					
		// get service details
		$service_profile = $this->daas_model->get_by_id($daas_id)->row();
		$data["service"] = $service_profile;

		// get list of test connections based on driver type...
		$string_array = $this->daas_model->get_connection_strings_by_rdbms($service_profile->db_id);		
		$data["string_array"] = $string_array;		
		$data["description"] = json_decode($service_profile->daas_flatfile);
		
		//print_r($service_profile); exit;
		
		// get dataset with json form and decode results for a friendly view
			
		// generate pagination
		$config["base_url"] = site_url("daas_control/daas/daas_view/{$daas_id}/");
		$data["deactivate_user"] = site_url("daas_control/daas/rest_client_request/");
		$data["error"] = "";
	
		// load view
		$data["daas_content"] = "daas_manager_view/daas_service_view";
		$this->load->view("daas_manager/template", $data);
	}
	
	public function daas_delete($daas_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "delete_dataset"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}		
		$data['service'] = $this->daas_model->get_by_id($daas_id)->row();
		$page_index = $this->session->userdata('last_daas_pagination');
		// prefill form values
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data["subtitle"] = 'Delete Data Service';
		$data["del_service"] = site_url("daas_control/daas/deleteDataset/{$daas_id}");
		$data["action"] = site_url("daas_control/daas/deleteDataset");
		$data["link_back"] = anchor($page_index.'/?tab=daas_list', "Back to Service List");		
		// load view
		$data["acl_content"] = "daas_manager_view/daas_service_delete";
		$this->load->view("access_control/template", $data);	
	}
	
	/*
	 * Here comes the public supporting casts:
	 * these functions provide support
	 */
	
	public function deleteDataset($daas_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "delete_dataset"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}

		// call private delete method to process request
		$this->process_delete_dataset($daas_id); 
	}
	
	/*
	 * Here comes the private supporting method for additional security.
	 * this prevents direct access from the browser/user interaction.
	 * first supporting method (deleteDataset) calls this private method
	*/

	private function process_delete_dataset($daas_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "delete_dataset"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
			$this->daas_model->deleteDataset($daas_id);
			redirect("daas_control/daas/daas_manager/?tab=daas_list&del_data_success=true");	
	}	

	public function daas_modify($daas_id)
	{		
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "edit_dataset"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}

		//print_r($_POST);exit;
		// PHP 5.4 and above requires stdClass be declare on an empty object
		$this->form_data = new stdClass;    
		
		$page_index = $this->session->userdata('last_daas_pagination');
		
		// prefill form values
		$daas = $this->daas_model->get_by_id($daas_id)->result();
		$this->form_data->daas_id = $daas_id;
		$daas_string = json_decode($daas[0]->daas_flatfile);
		$daas_dbtype = $this->daas_model->get_rdbms_by_id($daas[0]->daas_rdbms);
		//Needed to ensure that the the follwing fields are uniform
		$daas_string->db_id = $daas[0]->daas_rdbms;
		$daas_string->username = $daas[0]->daas_user;
		$daas_string->daas_passwd = $daas[0]->daas_passwd;
		$daas_string->dbname = $daas[0]->daas_dbname;
		$daas_string->hostname = $daas[0]->daas_host;
		$daas_string->instance = $daas[0]->daas_instance;
		$daas_string->schema = $daas[0]->daas_method;
		$daas_string->dbtable = $daas[0]->daas_table;
		$daas_string->dbtable_clmn = $daas[0]->daas_action_clmn;
		$daas_string->dbport = $daas[0]->daas_port;
		$daas_string->dbsid = $daas[0]->daas_sid;
		$daas_string->dbsname = $daas[0]->daas_sname;
		
		// get relational database types...
		//$db_type = $this->daas_model->get_rdbms_list();
			
		// set common properties
		$data["title"] = $this->super_title;
		$data["version_official_name"] = $this->version_title;		
		$data["subtitle"] = "Update Data Service";
		$data["message"] = "";
		$data["passwd_message"] = "";
		$data["error"] = "";
		$data["passwd_error"] = "";
		$data["success"] = "";
		$data["passwd_success"] = "";
		$data["dbtype"] = $this->daas_model->get_rdbms_list();
		$data["dbtype_name"] = $daas_dbtype->db_type;
		$data["daas_obj"] = $daas_string;
		$data["action"] = site_url("daas_control/daas/modifyDaaS");
		$data["link_back"] = anchor($page_index.'/?tab=daas_list', "Back to Service List");		
			
		// load view
		$data["daas_content"] = "daas_manager_view/daas_modify";
		$this->load->view("daas_manager/template", $data);
	}
	
	public function modifyDaaS()
	{
		$daas_id = $this->input->post('db_id');
		$daas = $this->daas_model->get_by_id($daas_id)->row();
		$daas_string = json_decode($daas->daas_flatfile);
				
		// daas validation check
		$this->_daas_rules();
		
		$this->form_data = new stdClass;

		if ($this->form_validation->run() == FALSE)
		{
			// set common properties
			$this->session->set_flashdata('data', validation_errors());
			$data ["title"] = $this->super_title;
			$data ["version_official_name"] = $this->version_title;			
			$data["subtitle"] = "Update Data Service";			
			$data["daas_content"] = "daas_manager_view/daas_modify";
			$data["daas_obj"] = $daas_string;
			redirect('daas_control/daas/daas_modify/'.$daas_id);
		}
		else
		{
			/*
			 * call private update method.
			 * additional security implementation 
			 */
			$this->update_daas($daas_id);
		}
		
	}
	
	// private update DaaS method
	private function update_daas($daas_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "edit_dataset"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		// validation complete
		$string = strtolower($this->input->post('dbtable'));
		$slug = preg_replace('~[\\\\/:*?"<>| ]~', '_', $string);
		$alias = strtolower(preg_replace('~[\\\\/:*?"<>| ]~', '_', $this->input->post("dbtable_alias")));
		
		$conn_obj = $this->daas_model->get_by_id($this->input->post("db_id"))->row();
		
		$update_string = array("daas_rdbms" => $conn_obj->daas_rdbms,
				"daas_host" => strtolower($this->input->post("hostname")),
				"daas_instance" => strtolower($this->input->post("instance")),
				"daas_user" => $this->input->post("username"),
				"daas_passwd" => $this->input->post("dbpassword"),
				"daas_dbname" => $this->input->post("dbname"),
				"daas_table" => $this->input->post("dbtable"),
				"daas_table_alias" => $alias,
				"daas_port" => $this->input->post("dbport"),
				"daas_method" => $slug,
				"daas_action_clmn" => $this->input->post("dbtable_clmn"),
				"daas_sid" => $this->input->post("dbsid"),
				"daas_sname" => $this->input->post("dbsname"),
				"daas_access_link" => "https://".REST_SERVER."/api/rest_services/daas_read/source/",
				'daas_flatfile' => json_encode($_POST),
				"date_modified" => date("Y-m-d H:i:s"));
		
		
		$response = $this->daas_model->update_string($slugid = $this->input->post('db_id'), $update_string);
		//print_r($response); exit;
		redirect("daas_control/daas/daas_modify/{$daas_id}/?success");
	}
	
	public function add_daas()
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "add_dataset"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}

		$page_index = $this->session->userdata('last_daas_pagination');
		
		// daas validation check
		$this->_daas_rules();

		// check form submission and process 
		if ($this->form_validation->run() == FALSE)
		{
			// set common properties
			$this->session->set_flashdata('validation_errors', validation_errors());
			$data = array(
					'pagination' => $this->pagination->create_links(),
					'actionregKey' => site_url('key_control/key/registerKey'),
					'title' => $this->super_title,
					'version_official_name' => $this->version_title,
					'subtitle' => 'View Token',
					'panel_title' => 'Key Management',
					'link_back' => anchor($page_index.'/?tab=daas_list', "Back to Service List")
			);
				
			// send requestor back to daas manager
			redirect("daas_control/daas/daas_manager/?tab=add_daas");	
		}
		else
		{
			/* call DaaS model and process new connection
			 * string process the request
			 */ 
			
			$this->add_daas_process();
		}
		
	}

	public function connection_test()
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "access_acl"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		//log_message('debug', 'Value of connection string '+$this->input->post("connect_id"));
				
		$connect_id = $this->input->post("connectid");

		if(empty($connect_id))
		{
			echo 'Select a Datasource';				
		}
		else
		{
			$connect = array();
			$connect = $this->daas_model->get_connect_json($connect_id)->result();
			//print_r($connect[0]->daas_method); exit;
			$source = strtolower($connect[0]->daas_table_alias);
			$data = strtolower($connect[0]->daas_table_alias);
			$useragent = $_SERVER['HTTP_USER_AGENT'];
			$rest_server = REST_SERVER;
			$get_url = "https://{$rest_server}/get/{$source}/limit/10";
			
			$headers = array(
					"Content-Type: application/json",
					"Accept: application/json",
					"X-API-KEY: ".REST_DEV_KEY.""
			);
			
			$ch = curl_init();
			curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_URL => $get_url,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_USERAGENT => $useragent,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_SSL_VERIFYPEER => FALSE // set to TRUE on QA and Prod
			));
		
			// Execute - returns response
			$response = curl_exec($ch);
			if(!empty($response))
			{
				echo $response;
			}
			else
			{
				echo 'Failed to retrieve data';
			}
		}
	}
	
	private function add_daas_process()
	{		
				
		// validation complete
		$string = strtolower($this->input->post("dbtable"));
		$slug = preg_replace('~[\\\\/:*?"<>| ]~', '_', $string);
		$tblalias = strtolower($this->input->post("dbtable_alias"));
		$alias = preg_replace('~[\\\\/:*?"<>| ]~', '_', $tblalias);
		$hostname = strtolower($this->input->post("hostname"));
		
		$new_string = array("daas_rdbms" => $this->input->post("db_type"),
				"daas_host" => $hostname,
				"daas_instance" => strtolower($this->input->post("instance")),
				"daas_user" => $this->input->post("username"),
				"daas_passwd" => $this->input->post("dbpassword"),
				"daas_dbname" => $this->input->post("dbname"),
				"daas_table" => $this->input->post("dbtable"),
				"daas_table_alias" => $alias,
				"daas_port" => $this->input->post("dbport"),
				"daas_method" => $slug,
				"daas_action_clmn" => $this->input->post("dbtable_clmn"),
				"daas_sid" => $this->input->post("dbsid"),
				"daas_sname" => $this->input->post("dbsname"),
				"daas_access_link" => "https://".REST_SERVER."/get/",
				'daas_flatfile' => json_encode($_POST),
				"date_created" => date("Y-m-d H:i:s"));
		
		$response = $this->daas_model->add_daas_string($new_string);
		
		if ($response == NO_DUPLICATE) {
			// if no duplicate connection string is found, send an email notification to the new admin
			redirect("daas_control/daas/daas_manager/?service_registered=true&dbhost={$hostname}#add_daas");	
		} else {
			// redirect on duplicate string error
			redirect("daas_control/daas/daas_manager/?service_error=true#add_daas");
		}		
	}
	
	function control_list()
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_dataset"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		// set common properties
		$page_index = $this->session->userdata('last_daas_pagination');
		
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;		
		$data["subtitle"] = "Control List";
		$data["panel_title"] = "Control List";
		$data["link_back"] = anchor($page_index.'/?tab=daas_list', "Back to Service List");		
				
		// load view
		$data["daas_content"] = "daas_manager_view/control_list";
		$this->load->view("daas_manager/template", $data);
	}
	
	public function rest_client_request($daas_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "access_acl"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		//log_message('debug', 'Value of connection string '+$this->input->post("connect_id"));
		$connect = array();
		$connect = $this->daas_model->get_connect_json($connect_id = $daas_id)->result();
		//print_r('database='.$connect[0]->daas_dbname.' table='. $connect[0]->daas_method); exit;
		$source = strtolower($connect[0]->daas_table_alias);
		$useragent = $_SERVER['HTTP_USER_AGENT'];
		$rest_server = REST_SERVER;
		
		if ($this->input->get('format') == 'xml')
		{
			$get_url = "https://{$rest_server}/get/{$source}/format/xml/limit/10";
			$headers = array("X-API-KEY: ".REST_DEV_KEY."");
		}
		elseif($this->input->get('format') == 'json')
		{
			//print_r($this->input->get('format')); exit;
			$get_url = "https://{$rest_server}/get/{$source}/format/json/limit/10";
			$headers = array("X-API-KEY: ".REST_DEV_KEY."");
		}
		
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_URL => $get_url,
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_USERAGENT => $useragent,
			CURLOPT_FOLLOWLOCATION => FALSE,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYHOST => FALSE,
			CURLOPT_SSL_VERIFYPEER => FALSE // set to TRUE on QA and Prod
		));
		
		// Execute - returns response
		$response = curl_exec($ch);
		curl_close($ch);
		
		if ($this->input->get('format') == 'xml')
		{
			$response = htmlspecialchars($response);
		}
		
		// load view
		//$data["daas_content"] = "daas_manager_view/rest_result";
		$data = array('message' => $response);
		$this->load->view("daas_manager_view/rest_result", $data);		
		
	}
	
// View Rest Logs	
	public function log_table($query_id = 0, $sort_by = 'name', $sort_order = 'asc', $offset = 0)
	{	
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_rest_logs"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}		
		// offset
		$uri_segment = 7;
		$offset = $this->uri->segment($uri_segment);

		// Need to remember the last pagination number in order to properly link back from an action use current url
		$this->session->set_userdata('last_log_pagination', current_url());
		
		// load data
		$logList = $this->daas_model->get_log_paged_list($this->limit, $offset, $sort_by, $sort_order)->result();
		
		// load last query results
		//print_r($this->input->load_query($query_id)); exit;
		$this->input->load_query($query_id);
		
		// get search input and prepare for processing
		$query_array = array(
				'searchval' => $this->input->get('searchval'),
				'searchcat' => $this->input->get('searchcat'),
				'searchcond' => $this->input->get('searchcond')
		);
		
		// initiate query search model 
		$searchResults = $this->daas_model->searchList($query_array, $this->limit, $offset, $sort_by, $sort_order)->result();
		
		$searchCount = $this->daas_model->searchCount($query_array, $this->limit, $offset, $sort_by, $sort_order);
		
		// set row count based on results
		if (empty($query_array['searchval']))
		{
			$rowCount = $this->daas_model->count_all_logs();
		}
		elseif (!empty($query_array['searchval']))
		{
			$rowCount = $searchCount['num_rows'];
		}
		
		//print_r($results); return;
		
		// generate pagination
		$config = array(
			'base_url' => site_url("daas_control/daas/log_table/$query_id/$sort_by/$sort_order"),
			'total_rows' => $rowCount,
			'per_page' => $this->limit,
			'uri_segment' => $uri_segment,
			'suffix' => '/?tab=log_list',
			'full_tag_open' => '<ul class="pagination pagination-mm">',
			'full_tag_close' => '</ul>',
			'num_tag_open' => '<li>',
			'num_tag_close' => '</li>',
			'cur_tag_open' => '<li class="active"><span>',
			'cur_tag_close' => '<span class="sr-only">(current)</span></span></li>',
			'prev_tag_open' => '<li>',
			'prev_tag_close' => '</li>',
			'next_tag_open' => '<li>',
			'next_tag_close' => '</li>',
			'first_link' => 'First',
			'prev_link' => 'Previous',
			'first_url' => site_url("daas_control/daas/log_table/$query_id/$sort_by/$sort_order/?tab=log_list"),
			'last_link' => 'Last',
			'next_link' => 'Next',
			'first_tag_open' => '<li>',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li>',
			'last_tag_close' => '</li>',
			'query_id' => $query_id
		);
		
		// build pagination data array 
		$this->pagination->initialize($config);

		$data = array(
			'pagination' => $this->pagination->create_links(),
			'actionregKey' => site_url('daas_control/daas/registerKey'),
			'title' => $this->super_title,
			'version_official_name' => $this->version_title,
			'subtitle' => 'REST Transaction Logs',
			'panel_title' => 'APIv2 Transaction History',
			'sort_by' => $sort_by,
			'sort_order' => $sort_order,
			'actionSearch' => site_url('daas_control/daas/searchList'),
			'reset' => anchor('daas_control/daas/log_table/', "Reset", array("class" => "btn btn-primary")),
			'link_back' => anchor('daas_control/daas/log_table/', "Back to APIv2 REST Logs")
		);
								
		// generate table data
		$this->table->set_empty('&nbsp;');
		// sorting icon indication
		$sort_up = "<i class=\"fa fa-sort-up\"></i>";
		$sort_down = "<i class=\"fa fa-sort-down\"></i>";
		
		$table_setup = array('table_open' => '<table class="table table-striped table-bordered table-hover">',
							 "heading_cell_start"  => "<th scope='col'>");
		$this->table->set_heading(				
				anchor("daas_control/daas/log_table/$query_id/time/".(($sort_order == 'asc' && $sort_by == 'time') ? 'desc/'.$offset.'/?tab=log_list' : 'asc/'.$offset.'/?tab=log_list'),
				$sort_order == "asc" ? "Date $sort_up" : "Date $sort_down"),
				anchor("daas_control/daas/log_table/$query_id/api_key/".(($sort_order == 'asc' && $sort_by == 'api_key') ? 'desc/'.$offset.'/?tab=log_list' : 'asc/'.$offset.'/?tab=log_list'),
						$sort_order == "asc" ? "API Key $sort_up" : "API Key $sort_down"),
				anchor("daas_control/daas/log_table/$query_id/uri/".(($sort_order == 'asc' && $sort_by == 'uri') ? 'desc/'.$offset.'/?tab=log_list' : 'asc/'.$offset.'/?tab=log_list'),
						$sort_order == "asc" ? "URI $sort_up" : "URI $sort_down"),
				anchor("daas_control/daas/log_table/$query_id/method/".(($sort_order == 'asc' && $sort_by == 'method') ? 'desc/'.$offset.'/?tab=log_list' : 'asc/'.$offset.'/?tab=log_list'),
						$sort_order == "asc" ? "Method $sort_up" : "Method $sort_down"),
				anchor("daas_control/daas/log_table/$query_id/params/".(($sort_order == 'asc' && $sort_by == 'params') ? 'desc/'.$offset.'/?tab=log_list' : 'asc/'.$offset.'/?tab=log_list'),
						$sort_order == "asc" ? "Parameters $sort_up" : "Parameters $sort_down"),

				anchor("daas_control/daas/log_table/$query_id/ip_address/".(($sort_order == 'asc' && $sort_by == 'ip_address') ? 'desc/'.$offset.'/?tab=log_list' : 'asc/'.$offset.'/?tab=log_list'),
						$sort_order == "asc" ? "IP Address $sort_up" : "IP Address $sort_down"),
				anchor("daas_control/daas/log_table/$query_id/rtime/".(($sort_order == 'asc' && $sort_by == 'rtime') ? 'desc/'.$offset.'/?tab=log_list' : 'asc/'.$offset.'/?tab=log_list'),
						$sort_order == "asc" ? "Response Time (ms) $sort_up" : "Response Time (ms) $sort_down"),				
				"Actions"
		);
		$i = 0 + $offset;
		
	    $view = array("class" => "btn btn-success btn-sm", "title" => "More Details", "title" => "More Details");
	    
		if (empty($query_array['searchval']))
		{
			foreach($logList as $log)
			{
				$this->table->add_row(unix_to_human($log->time), $log->api_key, $log->uri, $log->method, $log->params, $log->ip_address, $log->rtime,
					anchor('daas_control/daas/log_view/'.$log->id.'/?tab=log_view', '<span>View Log ID '.$log->id.'</span>', $view));
			}
		}
		elseif (!empty($query_array['searchval']))
		{
			foreach($searchResults as $log)
			{
				$this->table->add_row(unix_to_human($log->time),$log->api_key,$log->uri, $log->method, $log->params,$log->ip_address, $log->rtime,
					anchor('daas_control/key/token_control/'.$log->id.'/?tab=token_view', '<span>View</span>', $view));
			}
		}

		$this->table->set_template($table_setup);
		$data['table'] = $this->table->generate();
	
		// load account view
		$data['daas_content'] = 'daas_manager_view/log_table';
		$this->load->view('daas_manager/template', $data);
	}
	// transaction log view table. the log is generated from api dataset activities 
	public function log_view($log_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_rest_logs"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
	
		$page_index = $this->session->userdata('last_log_pagination');
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data["subtitle"] = "REST Log Viewer";
		$data["link_back"] = anchor($page_index.'/?tab=log_list', "Back to REST Log List");
		// get REST transaction details
		$rest_log = $this->daas_model->get_rest_log($log_id);

		//Populate empty object values with NA
		foreach($rest_log as $key=>$val) {			
			if(empty($val)) { 
				$rest_log->$key = 'NA'; 
			}
		}
		$data["log"] = $rest_log;
		
		//print_r($service_profile); exit;
		// get dataset with json form and decode results for a friendly view
			
		// generate pagination
		$config["base_url"] = site_url("daas_control/daas/log_view/{$log_id}/");
		$data["error"] = "";
	
		// load view
		$data["daas_content"] = "daas_manager_view/log_view";
		$this->load->view("daas_manager/template", $data);
	}

	private function compare_strings($dbtable, $dbtable_alias)
	{	
		// specify the field names for validation.
		$dbtable = strtolower($this->input->post("dbtable"));
		$dbtable_alias = strtolower($this->input->post("dbtable_alias"));
		
		//one is empty, so no result
		if (strlen($dbtable)==0 || strlen($dbtable_alias)==0)
		{
			return 0;
		}

		//replace none alphanumeric charactors
		//i left - in case its used to combine words
		$s1clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $dbtable);
		$s2clean = preg_replace("/[^A-Za-z0-9-]/", ' ', $dbtable_alias);
	
		//remove double spaces
		while (strpos($s1clean, "  ")!==false)
		{
			$s1clean = str_replace("  ", " ", $s1clean);
		}
		while (strpos($s2clean, "  ")!==false)
		{
			$s2clean = str_replace("  ", " ", $s2clean);
		}
	
		//create arrays
		$ar1 = explode(" ",$s1clean);
		$ar2 = explode(" ",$s2clean);
		$l1 = count($ar1);
		$l2 = count($ar2);
	
		//flip the arrays if needed so ar1 is always largest.
		if ($l2>$l1)
		{
			$t = $ar2;
			$ar2 = $ar1;
			$ar1 = $t;
		}
	
		//flip array 2, to make the words the keys
		$ar2 = array_flip($ar2);
		
		$maxwords = max($l1, $l2);
		$matches = 0;
	
		//find matching words
		foreach($ar1 as $word)
		{
			if (array_key_exists($word, $ar2)) $matches++;
		}
		
		// find the percentage
		return ($matches/$maxwords)*100;
	}

	public function _match($dbtable, $dbtable_alias)
	{
		$percentile = $this->compare_strings($dbtable, $dbtable_alias);
		
		if ($dbtable == $dbtable_alias)
		{
			$this->form_validation->set_message('_match', 'DB Table Alias cannot be the same as DB Table');
			return FALSE;
		}
		elseif ($percentile >= OBFUSCATE_PERCENTAGE)
		{
			$this->form_validation->set_message('_match', round($percentile, 2)."&#37; similarity found. Sorry, please enter an alias not similar to the table name");
			return FALSE;
		}		
		return TRUE;
	}
	
	/*
	 * this method validates connection to database host
	 * if the db hostname/ip is unreachable, no data will
	 * be added or modified
	 */
	public function _validate_host($hostcheck)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "validate_host"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		$hostcheck = strtolower($this->input->post('hostname'));
		$port = $this->input->post("dbport");
		$db_rdbms = $this->input->post("rdbms");
		
		// check if we need to convert host to IP
		if (!preg_match('/([0-9]{1,3}\\.){3,3}[0-9]{1,3}/', $hostcheck))
		{
			$ip = gethostbyname($hostcheck);
			if ($hostcheck == $ip)
			{
				$this->form_validation->set_message('_validate_host', "Cannot resolve host: {$hostcheck}");
				return FALSE;
			}
			else
			{
				$hostcheck = $ip;
			}
		}
		
		// attempt connection - suppress warnings
		if (!empty($port))
		{
			$this->socket = @fsockopen($hostcheck, $port, $this->errno, $this->errstr, $this->timeout);
		}
		else // if port not specified, the system will use the default standard port
		{
			if ($db_rdbms == 1)
			{
				$this->socket = @fsockopen($hostcheck, MYSQL_DEFAULT_PORT, $this->errno, $this->errstr, $this->timeout);
			}
			elseif ($db_rdbms == 2)
			{
				$this->socket = @fsockopen($hostcheck, MSSQL_DEFAULT_PORT, $this->errno, $this->errstr, $this->timeout);
			}
			elseif ($db_rdbms == 3)
			{
				$this->socket = @fsockopen($hostcheck, ORACLE_DEFAULT_PORT, $this->errno, $this->errstr, $this->timeout);
			}
			elseif ($db_rdbms == 4)
			{
				$this->socket = @fsockopen($hostcheck, POSTGRESSQL_DEFAULT_PORT, $this->errno, $this->errstr, $this->timeout);
			}
		}
		if (!$this->socket)
		{
			$this->form_validation->set_message('_validate_host', 'Connection test failed. Check FQDN/IP and DB Port.');
			fclose($this->socket); // close connection, this will free up source process
			return FALSE;
		}
		
		if (!empty($this->prompt))
			fclose($this->socket); // close connection, this will free up source process
			return TRUE;
	}
	
	// set validation rules for adding/deleting/modification
	public function _daas_rules()
	{
		// set empty default form field values
		//$this->form_validation->set_rules("db_type", "DB type", "trim|required");
		$this->form_validation->set_rules("hostname", "Hostname/IP", "trim|required|min_length[2]|max_length[50]|callback__validate_host");
		$this->form_validation->set_rules("instance", "DB Instance", "trim|alpha_numeric|min_length[2]|max_length[50]");
		$this->form_validation->set_rules("username", "Username", "trim|required|min_length[2]|max_length[50]");
		$this->form_validation->set_rules("dbpassword", "Password", "trim|required|min_length[6]");
		$this->form_validation->set_rules("schema", "schema", "trim|alpha_dash");
		$this->form_validation->set_rules("dbname","DB Name","required|alpha_dash");
		$this->form_validation->set_rules("dbtable","DB Table","required|alpha_dash");
		$this->form_validation->set_rules("dbtable_alias","DB Table Alias","required|alpha_dash|callback__match[dbtable]|min_length[5]|max_length[50]");
		//NOTE jquery validator equivalent of 'is_natural' is 'digits:true' in footer.php
		$this->form_validation->set_rules("dbport","DB Port","trim|is_natural");
		$this->form_validation->set_rules("dbtable_clmn","DB Table Column","trim|alpha_dash");
		$this->form_validation->set_rules("dbsid","DB SID","trim");
		//$this->form_validation->set_rules("dbsname","DB Service Name","trim");
		$this->form_validation->set_rules("dbdescription","Dataset Description","trim|required|min_length[6]|max_length[500]");
		
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value." );
		$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );		
	}

}

