<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * APIv2 AdminUI DaaS Controller
 *
 * @package	Key Controller
 * @author 	johnsonpatrickk (Patrick Johnson Jr.)
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Key extends CI_Controller
{
	
	private $daas_mgr_table;
	private $limit = 20;
	private $acl_table;
	private $token_tbl = "keys";
	private $super_title='';
	private $version = '';
	private $version_title = '';
		
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		
		// no page caching
		//$this->output->nocache();
		
		// bootstrap app and access control model
		$this->load->model("token_model","", TRUE);
		$this->load->model("adminuiacl_model","", TRUE);
		//$this->load->database("apiv2", TRUE);
		$this->load->model("version_model", "", TRUE );
			
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

	public function token_manager($query_id = 0, $sort_by = 'name', $sort_order = 'asc', $offset = 0)
	{	
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_key"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		// offset
		$uri_segment = 7;
		$offset = $this->uri->segment($uri_segment);

		// Need to remember the last pagination number in order to properly link back from an action use current url
		$this->session->set_userdata('last_key_pagination', current_url());
		
		// load data
		$tokenList = $this->token_model->get_paged_list($this->limit, $offset, $sort_by, $sort_order)->result();
		
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
		$searchResults = $this->token_model->searchList($query_array, $this->limit, $offset, $sort_by, $sort_order)->result();
		$searchCount = $this->token_model->searchCount($query_array, $this->limit, $offset, $sort_by, $sort_order);
		
		// set row count based on results
		if (empty($query_array['searchval']))
		{
			$rowCount = $this->token_model->count_all();
		}
		elseif (!empty($query_array['searchval']))
		{
			$rowCount = $searchCount['num_rows'];
		}
		
		//print_r($results); return;
		
		// generate pagination
		$config = array(
			'base_url' => site_url("key_control/key/token_manager/$query_id/$sort_by/$sort_order"),
			'total_rows' => $rowCount,
			'per_page' => $this->limit,
			'uri_segment' => $uri_segment,
			'suffix' => '/?tab=key_list',
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
			'first_url' => site_url("key_control/key/token_manager/$query_id/$sort_by/$sort_order/?tab=key_list"),
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
			'actionregKey' => site_url('key_control/key/registerKey'),
			'title' => $this->super_title,
			'version_official_name' => $this->version_title,
			'subtitle' => 'List of API Keys',
			'panel_title' => 'Key Management',
			'sort_by' => $sort_by,
			'sort_order' => $sort_order,
			'actionSearch' => site_url('key_control/key/searchList'),
			'reset' => anchor('key_control/key/token_manager/?tab=key_list', "Reset", array("class" => "btn btn-primary")),
			'link_back' => anchor('key_control/key/token_manager/?tab=key_list', "Back to Token List")
		);
								
		// generate table data
		$this->table->set_empty('&nbsp;');
		// sorting icon indication
		$sort_up = "<i class=\"fa fa-sort-up\"></i>";
		$sort_down = "<i class=\"fa fa-sort-down\"></i>";
		
		$table_setup = array('table_open' => '<table class="table table-striped table-bordered table-hover">',
							 "heading_cell_start"  => "<th scope='col'>");
		$this->table->set_heading(
				anchor("key_control/key/token_manager/$query_id/name/".(($sort_order == 'asc' && $sort_by == 'name') ? 'desc/'.$offset.'/?tab=key_list' : 'asc/'.$offset.'/?tab=key_list'),
						$sort_order == "asc" ? "Key Owner $sort_up" : "Key Owner $sort_down"),				
				anchor("key_control/key/token_manager/$query_id/key/".(($sort_order == 'asc' && $sort_by == 'key') ? 'desc/'.$offset.'/?tab=key_list' : 'asc/'.$offset.'/?tab=key_list'),
						$sort_order == "asc" ? "Token $sort_up" : "Token $sort_down"),
				anchor("key_control/key/token_manager/$query_id/ip_addresses/".(($sort_order == 'asc' && $sort_by == 'ip_addresses') ? 'desc/'.$offset.'/?tab=key_list' : 'asc/'.$offset.'/?tab=key_list'),
						$sort_order == "asc" ? "IP Addresses $sort_up" : "IP Addresses $sort_down"),
				anchor("key_control/key/token_manager/$query_id/email_addr/".(($sort_order == 'asc' && $sort_by == 'email_addr') ? 'desc/'.$offset.'/?tab=key_list' : 'asc/'.$offset.'/?tab=key_list'),
						$sort_order == "asc" ? "Email $sort_up" : "Email $sort_down"),
				anchor("key_control/key/token_manager/$query_id/status/".(($sort_order == 'asc' && $sort_by == 'status') ? 'desc/'.$offset.'/?tab=key_list' : 'asc/'.$offset.'/?tab=key_list'),
						$sort_order == "asc" ? "Status $sort_up" : "Status $sort_down"),
				anchor("key_control/key/token_manager/$query_id/date_created/".(($sort_order == 'asc' && $sort_by == 'date_created') ? 'desc/'.$offset.'/?tab=key_list' : 'asc/'.$offset.'/?tab=key_list'),
						$sort_order == "asc" ? "Date $sort_up" : "Date $sort_down"),
				"Actions"
		);
		$i = 0 + $offset;
		
	    $view = array("class" => "btn btn-success btn-sm","title" => "More Details", "title" => "More Details");
	    $update = array("class" => "btn btn-warning btn-sm","title" => "Edit Token", "title" => "Edit Connection");
	    $delete = array("class" => "btn btn-danger btn-sm", "title" => "Delete Token", "title" => "Delete Token");
	    $status = array("class" => "btn btn-primary btn-sm", "title" => "Status Change", "title" => "Status Change");
	    
	    //print_r($query_array); exit;
	    
		if (empty($query_array['searchval']))
		{
			foreach($tokenList as $key)
			{
				$status = array("class" => "btn btn-primary btn-sm","title" => "Status Change", "title" => "Status Change");
				if ($key->status)
				{
					$status = array("class" => "btn btn-default ",  "title" => "Status Change", "title" => "Status Change");
				}
				
				$changeStatus = anchor('key_control/key/unblockToken/'.$key->id, '<span>Unblock</span>', $status);
				if ($key->status)
				{
					$changeStatus = anchor('key_control/key/blockToken/'.$key->id, '<span>Block</span>', $status);
				}
	
				$this->table->add_row($key->name,$key->key, $key->ip_addresses, $key->email_addr, strtoupper($key->status) == "1" ? "<strong class='text-success'>Active</strong>" : "<strong class='text-danger'>Inactive</strong>", date("m/d/Y", $key->date_created),
						anchor('key_control/key/token_control/'.$key->id.'/?tab=token_view', '<span>View</span>', $view) . ' 
						' . anchor('key_control/key/token_control/'.$key->id.'/?tab=token_edit', '<span>Update</span>', $update) . ' 
						' . anchor('key_control/key/token_control/'.$key->id.'/confirm_delete', '<span>Delete</span>', $delete).'
		    			' . $changeStatus);
			}
		}
		elseif (!empty($query_array['searchval']))
		{
			foreach($searchResults as $key)
			{
				$status = array("class" => "btn btn-success btn-sm", "title" => "Status Change", "title" => "Status Change");
				if ($key->status)
				{
					$status = array("class" => "btn btn-danger btn-sm", "title" => "Status Change", "title" => "Status Change");
				}
				
				$changeStatus = anchor('key_control/key/unblockToken/'.$key->id, '<span>Unblock</span>', $status);
				if ($key->status)
				{
					$changeStatus = anchor('key_control/key/blockToken/'.$key->id, '<span>block</span>', $status);
				}
	
				$this->table->add_row($key->name, $key->key, $key->ip_addresses, $key->email_addr, strtoupper($key->status) == "1" ? "<strong class='text-success'>Active</strong>" : "<strong class='text-danger'>Inactive</strong>", date("m/d/Y", $key->date_created),
						anchor('key_control/key/token_control/'.$key->id.'/?tab=token_view', '<span>View</span>', $view) . ' 
						' . anchor('key_control/key/token_control/'.$key->id.'/?tab=token_edit', '<span>Update</span>', $update) . ' 
						' . anchor('key_control/key/token_control/'.$key->id.'/confirm_delete', '<span>Delete</span>', $delete).' 
		    			' . $changeStatus);
			}
		}

		$this->table->set_template($table_setup);
		$data['table'] = $this->table->generate();
	
		// load account view
		$data['key_content'] = 'token_manager_view/token_list';
		$this->load->view('token_manager/template', $data);
	}
	
	// register a key
	public function registerKey()
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "add_key" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$page_index = $this->session->userdata('last_key_pagination');
		$this->form_data = new stdClass;
		
		// set empty default form field values
		$this->_set_keyfields();
		
		// Set validation properties
		$this->_addkey_rules();
		
		if ($this->form_validation->run() == FALSE)
		{
			// Validation error messages
			$this->session->set_flashdata('validation_errors', validation_errors());
				
			$data = array(
				'pagination' => $this->pagination->create_links(),
				'actionregKey' => site_url('key_control/key/registerKey'),
				'title' => $this->super_title,
				'version_official_name' => $this->version_title,
				'subtitle' => 'View API Key',
				'panel_title' => 'Key Management',
				'link_back' => anchor($page_index.'/?tab=key_list', "Back to Token List")
			);
			// send requestor back to key registration
			redirect("key_control/key/token_manager/?tab=register_key");
		
		}
		else
		{
			// Call token model and start generating 
			$this->addKeyProcess();
		}
	}
	
	private function addKeyProcess() {
	
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "add_key" ))
		{
			show_error("You do not have access to this section" . anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}		
		
		// call token generator
		$this->token_model->addKey($keyadd = $this->generateToken($len = 32));
		//Everything checked out...		
		$name = $this->input->post('name');
		$email = $this->input->post('email_address');
		
		$this->email->set_newline ( "\r\n" );	
		$this->email->from(".FROM_EMAIL.",".FROM_NAME.");
		$this->email->to($email);
		$this->email->cc(".APPROVAL_ADMIN.");
		$this->email->subject('New Token Assigned');
		
		$body = "
		<table border=\"1\" style=\"border-width: thin; border-spacing: 2px; border-style: none; border-color: #ccc;\">
		<tr>
		<td>Assignee</td>
		<td>{$name}</td>
		</tr>
		<tr>
		<td>Message</td>
		<td width=\"300\">
		A new api key has been generated for you.<br>
		Assignee: {$name}<br>
		Token: {$keyadd['key']}<br>
		Description: {$this->input->post('description')}<br>
		Follow documentation link to see key usage examples <Link coming soon...>..
		</td>
			</tr>
		</table>";
			
		$this->email->message($body);
		
		if ($this->email->send())
		{
			// echo 'Your message was sent successfully...';
			redirect("key_control/key/token_manager/?tab=register_key&success_message=true");
		}
		else
		{
			// for development only. debugger will be remove for production environment
			show_error($this->email->print_debugger());
		}			
	}
	
	// key generator
	private function generateToken($len = 32)
	{
		/**
		 * Generate an encryption key for RESTful services.
		 * http://codeigniter.com/user_guide/libraries/encryption.html
		 */
		
		$chars = array(
				'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
				'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
				'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
				'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
				'0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
		);
		
		shuffle($chars);
		
		$num_chars = count($chars) - 1;
		$token = '';
		
		// Create random token at the specified length.
		for ($i = 0; $i < $len; $i++)
		{
		$token .= $chars[mt_rand(0, $num_chars)];
		}

		$keyadd = array('key' => strtolower($token),
				'level' => 0,
				'ignore_limits' => 0,
				'is_private_key' => 0,
				'ip_addresses' => $_SERVER['REMOTE_ADDR'],
				'status' => 1,
				'name' => $this->input->post('name'),
				'email_addr' => $this->input->post('email_address'),
				'description' => $this->input->post('description'),
				"date_created" => time()
		);
		
		return $keyadd;
	}
	
	// token control panel. this panel controls the view and edit features of the client key...
	public function token_control($key_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_key"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		$page_index = $this->session->userdata('last_key_pagination');
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;

		// get data from model
		$tokenData = $this->token_model->getTokenById($key_id)->result();
		$data = array
		(
			/*		<a href="<?php echo link_view ?>" data-toggle="tab">View</a>	*/
					
			// set common properties
			'title' => 'DOL APIv2 '.$this->version,
			'subtitle' => 'API Key Description',
			'version_official_name' => $this->version_title,	
			'panel_title' => 'Key Management',
			'editKeyReg' => site_url('key_control/key/editToken/'.$key_id),
			'delKeyReg' => site_url('key_control/key/deleteKeyConfirm/'.$key_id),	
			'link_back' => anchor($page_index.'/?tab=key_list', "Back to Token List"),
			'link_view' => anchor('key_control/key/token_control/'.$key_id.'/?tab=token_view','View'),
			'link_edit' => anchor('key_control/key/token_control/'.$key_id.'/?tab=token_edit','Edit'),
				
			// load data for view the view tab
			'key_id' => $tokenData[0]->id,
			'key_owner' => $tokenData[0]->name,
			'cdate' => date("m/d/Y", $tokenData[0]->date_created),
			'token' => $tokenData[0]->key,
			'status' => $tokenData[0]->status,
			'descr' => $tokenData[0]->description
		);
		
		
		// load view
		$confirm_delete = $this->uri->segment($this->uri->total_segments(),false);
			
		if($confirm_delete == "confirm_delete"){
			$data['key_content'] = 'token_manager_view/token_delete';				
		}else{			
			$data['key_content'] = 'token_manager_view/token_management';
		}
		$this->load->view('token_manager/template', $data);		
	}
	
	public function viewToken($key_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_key"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		//load some edit form
		redirect('key_control/key/token_manager');
	}
	
	public function editToken($key_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "edit_key"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		$page_index = $this->session->userdata('last_key_pagination');
		
		// set empty default form field values
		$this->_set_keyfields();
		
		// Set validation properties
		$this->_editkey_rules();
		
		if ($this->form_validation->run() == FALSE)
		{
			// Validation error messages
			$this->session->set_flashdata('validation_errors', validation_errors());
				
			$data = array(
				'pagination' => $this->pagination->create_links(),
				'actionregKey' => site_url('key_control/key/registerKey'),
				'title' => $this->super_title,
				'version_official_name' => $this->version_title,
				'subtitle' => 'View API Key',
				'panel_title' => 'Key Management',
				'link_back' => anchor($page_index.'/?tab=key_list', "Back to Token List")
			);
			// send requestor back to key registration
			redirect('key_control/key/token_control/'.$key_id.'/?tab=token_edit','Edit');
		
		}
		else
		{	
			$params = array(
				'name'	=> $this->input->post('name'),
				'description' =>  $this->input->post('description')
			);		
				
			// Call token model and update 
			$this->token_model->updateKey($key_id,$params);
			redirect('key_control/key/token_control/'.$key_id.'/?tab=token_edit&UpdateSuccess=success');
				
		}
	
	}
	
	public function deleteKeyConfirm($key_id)
	{ 
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "delete_key"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		// send request to token model to be process
		$this->deleteToken($tokenData = array('id' => $key_id));
		
		redirect("key_control/key/token_manager/?tab=key_list&status=del_success");
	}
	
	public function blockToken($key_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "block_key"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		$this->updateKeyStatus($key_id, KEYSTATUS_BLOCK);
		redirect($this->agent->referrer());
	}
	
	public function unblockToken($key_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "unblock_key"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		$this->updateKeyStatus($key_id, KEYSTATUS_ACTIVE);
		redirect($this->agent->referrer());
	}
	
	/*
	* private method for key status control:
	*/
	
	private function updateKeyStatus($key_id, $keyStatus)
	{
		$this->token_model->updateKeyStatus($key_id, $keyStatus);
	}
	
	/*
	 * private method for key removal:
	*/
	
	private function deleteToken($tokenData)
	{
		$this->token_model->deleteKey($tokenData);
	}
	
	public function is_unique_apiv2_user($key)
	{ 
		return $this->token_model->dupUserCheck($key);
	}
	
	// here goes the token search method
	public function searchList()
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "search_key" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		
		// Set validation properties
		$this->_search_rules();
		
		if ($this->form_validation->run() == FALSE)
		{
			// Validation error messages
			$this->session->set_flashdata('validation_errors', validation_errors());
			
			// load key list view
			//$this->token_manager($query_id = 0, $sort_by = 'name', $sort_order = 'asc', $offset = 0);
			redirect("key_control/key/token_manager/?tab=key_list");	
		}
		else
		{	
			// received search input and prepare for processing
			$query_array = array(
				'searchval' => $this->input->post('searchval'),
				'searchcat' => $this->input->post('searchcat'),
				'searchcond' => $this->input->post('searchcond'),
				'tab' => 'key_list'
			);
			
			$query_id = $this->input->save_query($query_array);
			
			redirect("key_control/key/token_manager/$query_id/?tab=key_list");
		}
	}  
	
	// Validation rules for registering a key
	protected function _addkey_rules()
	{
		//Removed because users can register multiple keys
		//$this->form_validation->set_rules ( "email_address", "Email Address", "trim|required|valid_email|callback_is_unique_apiv2_user[keys.email_address]" );
		
		$this->form_validation->set_rules ( "name", "API Key Name", "trim|required|min_length[5]|max_length[50]");
		$this->form_validation->set_rules ( "email_address", "Assign E-mail Address", "trim|required|valid_email");
		$this->form_validation->set_rules ( "description", "Description", "trim|required|max_length[1000]");	
			
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value.");
		$this->form_validation->set_message ( "is_unique_apiv2_user", "This %s has already been taken. Please try a different value.");
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
	        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>");
	}

	// Validation rules for editing a key
	protected function _editkey_rules()
	{
		$this->form_validation->set_rules ( "name", "API Key Name", "trim|required|min_length[5]|max_length[50]");
		$this->form_validation->set_rules ( "description", "Description", "trim|required|max_length[1000]");	
					
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value.");
		$this->form_validation->set_message ( "is_unique_apiv2_user", "This %s has already been taken. Please try a different value.");
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
	        	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
		
	// validation rules for search form
	protected function _search_rules()
	{
		$this->form_validation->set_rules("searchval", "Search Value", "trim|required");
		$this->form_validation->set_rules("searchcat", "Search Category", "trim|required");
		$this->form_validation->set_rules("searchcond", "Search Condition", "trim|required");
		
		$this->form_validation->set_message("required", "* required");
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
	        	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
	
	// set empty default form field values
	protected function _set_keyfields()
	{
		$this->form_data->id = "";
		$this->form_data->name = "";
		$this->form_data->email_address = "";
		$this->form_data->description = "";
	}
}
