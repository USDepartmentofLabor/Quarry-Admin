<?php if (! defined ( "BASEPATH" )) exit ( "No direct script access allowed" );

/**
 * @name 	APIv2 AdminUI Admin Controller
 *
 * @author  johnsonpatrickk (Patrick Johnson Jr.)
 * @license	http://developer.dol.gov
 * @version 1.0.0
 */

class Admin extends CI_Controller {
	// number of records per/page
	private $limit = 10;
	private $acl_table;
	private $super_title='';
	private $version = '';
	private $version_title = '';
	private $validation_results = '';

	public function __construct()
	{
		parent::__construct ();
		$this->is_logged_in ();
		// no page caching
		$this->output->nocache ();
		// bootstrap dashboard and access control model
		$this->load->model ( "adminuiacl_model", "", TRUE );
		$this->load->model ( "version_model", "", TRUE );
		$this->acl_conf = ( object ) $this->config->item ( "acl" );
		$this->acl_table = & $this->acl_conf->table;
		$this->version = $this->version_model->get_version();
		$this->version_title = $this->version_model->get_name();
		$this->super_title = $this->version_model->get_name().' '.$this->version_model->get_product().' '.$this->version;
	}
	
	public function is_logged_in()
	{

		$is_logged_in = $this->session->userdata ( "is_logged_in" );

		if (! isset ( $is_logged_in ) || $is_logged_in != TRUE)
		{
			echo "You don't have permission to access this page. " . anchor ( "/login", "Login Now" );
			die ();
		}
	}

	// load users account view
	public function account_manager($offset = 0)
	{

		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "view_users" ))
		{
			show_error ( "You do not have access to this section" . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// offset
		$uri_segment = 4;
		$offset = $this->uri->segment($uri_segment);

		// PHP 5.4 and above requires stdClass be declare on an empty object
		$this->form_data = new stdClass;
		// load data
		$users = $this->adminuiacl_model->get_paged_list ( $this->limit, $offset )->result();
		$this->form_data->first_name = $this->input->post('first_name');

		// generate pagination
		$config = array(
			'base_url' => site_url ("access_control/admin/account_manager/"),
		 	'total_rows' => $this->adminuiacl_model->count_all(),
			'per_page' => $this->limit,
		 	'uri_segment' => $uri_segment,
			'suffix' => '/?tab=users',
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
			'first_url' => site_url('access_control/admin/account_manager/?tab=users'),
			'last_link' => 'Last',
			'next_link' => 'Next',
			'first_tag_open' => '<li>',
			'first_tag_close' => '</li>',
			'last_tag_open' => '<li>',
			'last_tag_close' => '</li>'
		);

		$this->pagination->initialize($config);

		// Need to remember the last pagination number in order to properly link back from an action
		$this->session->set_userdata('last_pagination', current_url());

		// generate table data
		$this->table->set_empty ( "&nbsp;" );
		$table_setup = array (
				"table_open" => "<table class=\"table table-striped table-bordered table-hover\">",
				"heading_cell_start"  => "<th scope='col'>"
		);
		$this->table->set_heading ( "Last Name", "First Name", "User Name", "Status", "Date", "Actions" );
		
		$i = 0 + $offset;
		
		$view = array (
				"class" => "btn btn-warning btn-sm"
		);
		$update = array (
				"class" => "btn btn-success btn-sm"
		);
		$delete = array (
				"class" => "btn btn-danger btn-sm",
				"data-toggle" => "confirmation"
		);
		
		foreach ( $users as $acct )
		{
			
			if($this->session->userdata("user_id") === $acct->user_id)
			{
				$this->table->add_row ( $acct->last_name, $acct->first_name, $acct->username, strtoupper ( $acct->status ) == "1" ? "Active" : "Disabled",
						date ( "m/d/Y", strtotime ( $acct->date_created )),
						anchor ( "access_control/admin/account_view/" . $acct->user_id, "View <span class='scrn_rdr'>".$acct->first_name." ".$acct->last_name."</span>", $view ) . " " .
						anchor ( "access_control/admin/account_update/" . $acct->user_id, "Update <span class='scrn_rdr'>".$acct->first_name." ".$acct->last_name."</span>", $update ) 
				);
			}
			else
			{
			$this->table->add_row ( $acct->last_name, $acct->first_name, $acct->username, strtoupper ( $acct->status ) == "1" ? "Active" : "Disabled", 
				date ( "m/d/Y", strtotime ( $acct->date_created )), 
				anchor ( "access_control/admin/account_view/" . $acct->user_id, "View <span class='scrn_rdr'>".$acct->first_name." ".$acct->last_name."</span>", $view ) . " " .
			    anchor ( "access_control/admin/account_update/" . $acct->user_id, "Update <span class='scrn_rdr'>".$acct->first_name." ".$acct->last_name."</span>", $update ) . " " . 
				anchor ( "access_control/admin/account_delete/" . $acct->user_id, "Delete <span class='scrn_rdr'>".$acct->first_name." ".$acct->last_name."</span>", $delete ) 
				);
			}
		}
			$this->table->set_template ($table_setup);
	
			// Create form properties
			$data = array(
				'pagination' => $this->pagination->create_links(),
				'title' => $this->super_title,
				'version_official_name' => $this->version_title,
				'subtitle' => 'List of User Accounts',
				'panel_title' => 'User Accounts',
				'action' => site_url ( "access_control/admin/account_manager/" ),
				'action_passwd_chg' => site_url ('access_control/admin/password_change_process'),
				'add_admin_process' => site_url ('access_control/admin/add_admin/?tab=add_admin'),
				'roles' => $this->adminuiacl_model->get_group(),
				'perm_list' => $this->adminuiacl_model->get_all_perms(),
				'role_list' => $this->adminuiacl_model->get_all_roles(),
				'role_back' => anchor ('access_control/role/role_manager/?tab=roles', 'Role Manager'),
				'perm_back' => anchor ('access_control/permission/permission_manager/?tab=permissions', 'Permission Manager'),
				'table' => $this->table->generate(),
				'acl_content' => 'access_control_view/account_manager',
				'link_back' => anchor ('access_control/admin/account_manager/?tab=users', 'Back to User Accounts')
			);
	
			if(isset($this->validation_results))
			{
				$data['validation_errors'] = $this->validation_results;
			}
			else
			{
				$data['validation_errors'] = '';
			}
			$this->load->view ('access_control/template', $data);
		}


	public function account_view($user_id)
	{
		// check roles and permissions

		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "view_users" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		$page_index = $this->session->userdata('last_pagination');
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data ["subtitle"] = "View User Account";
		$data ["link_back"] = anchor ($page_index.'/?tab=users', " Back to User Accounts" );
		$data["user"] =  new stdClass; 
		$data ["user"]->roles = $this->adminuiacl_model->get_user_role( $user_id );
		$data['perm_list'] = array();
		// get  details
		$data ["acct"] = $this->adminuiacl_model->get_by_id ( $user_id )->row ();
		$data ["role_list"] = $this->adminuiacl_model->get_all_roles();
		// Extract allowable permissions by roles based on role id.
		// TODO ADD TO HELPER CLASS all functions involving roles should be in the adminuiacl class
		$perm_list = $this->adminuiacl_model->get_all_perms();
		if (is_array($data ["user"]->roles ))
		{
			foreach ($data ["user"]->roles as &$role )
			{
				$perm_checklist = $this->adminuiacl_model->get_role_perms_keys ( $role->role_id );
				$role->set = in_array ($role, $data ["user"]->roles );
				if ($role->set && !empty($perm_checklist))
				{
					foreach ($perm_list as &$perm )
					{
						foreach ($perm_checklist as &$check )
						{
							if ($perm->perm_id === $check->perm_id)
							{
								$perm->set = true;
								array_push($data['perm_list'],$perm);
							}
						}
					}
				}
				else
				{
					$no_perm = (object) array('perm_id' => 0,'set'=>true,'name'=>'NO PERMISSIONS HAVE BEEN ASSIGNED TO THIS ROLE.');
					$data ["perm_list"] = array($no_perm);				
				}
			}
		}
		else
		{
			foreach ( $data ["role_list"] as &$role )
			{
				$role->set = FALSE;
			}
			$no_role = (object) array('role_id' => 0,'set'=>true,'name'=>'NO ROLE HAS BEEN ASSIGNED TO THIS USER.');
			$no_perm = (object) array('perm_id' => 0,'set'=>true,'name'=>'NO PERMISSIONS.');
			$data ["user"]->roles = array($no_role);
			$data ["perm_list"] = array($no_perm);
		}
		// load view
		$data ["acl_content"] = "access_control_view/account_view";
		$this->load->view ( "access_control/template", $data );
	}

	public function account_update($user_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// set validation properties
		$page_index = $this->session->userdata('last_pagination');
		$acct = $this->adminuiacl_model->get_by_id ( $user_id )->row ();
		$this->form_data = new stdClass;		
		$this->form_data->user_id = "$user_id";
		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->username = $acct->username;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );
		$this->form_data->date_created = date ( 'm/d/Y', strtotime ($acct->date_created));

		// set common properties
		$data = array(
			"title" => $this->super_title,
			"version_official_name" => $this->version_title,
			"subtitle" => 'Update User Account',
			"roles" => $this->adminuiacl_model->get_group (),
			"user" => (object)array('roles' => $this->adminuiacl_model->get_user_role ( $user_id )),
			"role_list" => $this->adminuiacl_model->get_all_roles (),
			"action" => site_url ( "access_control/admin/account_modify" ),
			"action_passwd_chg" => site_url ( "access_control/admin/password_change_process" ),
			"role_back" => anchor ( "access_control/role/role_manager/?tab=roles", "Role Manager"),
			"perm_back" => anchor ( "access_control/permission/permission_manager/?tab=permissions", "Permission Manager"),
			"link_back" => anchor ( $page_index.'/?tab=users', " Back to User Accounts")
		);

		$data ["perm_list"] = array();
		$perm_list = $this->adminuiacl_model->get_all_perms ();

		// Extract allowable permissions by roles based on role id.
		// TODO ADD TO HELPER CLASS user->roles is an array for now..
		if (!empty($data ["user"]->roles) && is_array ($data ["user"]->roles ))
		{
			foreach ( $data ["role_list"] as &$role ) {
				$perm_checklist = $this->adminuiacl_model->get_role_perms_keys ( $role->role_id );
				$role->set = in_array ( $role, $data ["user"]->roles );
				if ($role->set)
				{
					if(!empty($perm_list)){
						foreach ($perm_list as &$perm )
						{
							if(!empty($perm_checklist))
							{	
								foreach ( $perm_checklist as &$check )
								{									
									if ($perm->perm_id === $check->perm_id)
									{											
										$perm->set = true;
										array_push($data['perm_list'],$perm);
									}
								}
							}
							else
							{
								//NO PERMS HAVE BEEN ASSIGNED TO THIS ROLE SO JUST BREAK								
								$no_perm = (object) array('perm_id' => 0,'set'=>true,'name'=>'NO PERMISSIONS HAVE BEEN ASSIGNED TO THIS ROLE.');
								array_push($data['perm_list'],$no_perm);
								break;
							}
						}
					}				
				}
			}
		}
		else
		{
			foreach ( $data ["role_list"] as &$role )
			{
				$role->set = FALSE;
			}
			$no_perm = (object) array('perm_id' => 0,'set'=>true,'name'=>'NO ROLE HAS BEEN ASSIGNED TO THIS USER.');
			$data ["perm_list"] = array($no_perm);
		}
		// load view
		$data ["acl_content"] = "access_control_view/account_modify";
		$this->load->view ( "access_control/template", $data );
	}

	public function account_delete($user_id)
	{
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// set validation properties
		// prefill form values

		// Get last known pagination from the parent table
		$page_index = $this->session->userdata('last_pagination');

		$acct = $this->adminuiacl_model->get_by_id ($user_id )->row ();
		$this->form_data = new stdClass;		
		$this->form_data->user_id = $user_id;
		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->username = $acct->username;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );
		$this->form_data->date_created = date ( 'm/d/Y', strtotime ( $acct->date_created ) );

		// set common properties
		$roles = $this->adminuiacl_model->get_user_role ($user_id);
		if(empty($roles))
		{
			$roles[0] = (object)array('role_id' => 0,'set'=>true,'name'=>'NO ROLE HAS BEEN ASSIGNED TO THIS USER.');
		}
		$data ["perm_list"] = array();
		$perm_list = $this->adminuiacl_model->get_all_perms ();

		// Extract allowable permissions by roles based on role id.
		// TODO ADD TO HELPER CLASS user->roles is an array for now..
		if (!empty($data ["user"]->roles) && is_array ($data ["user"]->roles ))
		{
			foreach ( $data ["role_list"] as &$role ) {
				$perm_checklist = $this->adminuiacl_model->get_role_perms_keys ( $role->role_id );
				$role->set = in_array ( $role, $data ["user"]->roles );
				if ($role->set)
				{
					foreach ($perm_list as &$perm )
					{
						foreach ( $perm_checklist as &$check )
						{
							if ($perm->perm_id === $check->perm_id)
							{
								$perm->set = true;
								array_push($data['perm_list'],$perm);
							}
						}
					}
				}
			}
		}

		$data = array(
			'title' => $this->super_title,
			'version_official_name' => $this->version_title,
			'subtitle' => 'Delete User Account',
			'del_account' => site_url("access_control/admin/del_account_process/{$user_id}"),
			'action' => site_url ('access_control/admin/account_delete'),
			'role_list' => $this->adminuiacl_model->get_all_roles (),
			'perm_list' => $this->adminuiacl_model->get_all_perms (),
			'role' => $roles[0],
			'acl_content' => 'access_control_view/account_delete',
			'link_back' => anchor ( $page_index.'/?tab=users', 'Back to Administrator')
		);
		// load view
		$this->load->view ( "access_control/template", $data );
	}

	public function del_account_process($user_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$this->adminuiacl_model->del_user ( $user_id );
		redirect ( "access_control/admin/account_manager/?tab=users&del_success_message=success" );
	}

	public function account_modify()
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// Need to remember the last pagination number in order to properly link back from an action
		$page_index = $this->session->userdata('last_pagination');

		$this->form_data = new stdClass;
		
		// set empty default form field values
		$this->_set_fields ();
		// set validation properties
		$this->_set_user_rules ();
		
		$user_id = $this->input->post ( "user_id" );

		// set common properties

		$data = array (
			'title' => $this->super_title,
			'version_official_name' => $this->version_title,
			'subtitle' => 'Modify Account',
			'action' => site_url('access_control/admin/account_modify'),
			'action_passwd_chg' => site_url('access_control/admin/password_change_process'),
			'roles' => $this->adminuiacl_model->get_group(),
			'role_array' => $this->adminuiacl_model->user_current_group(),
			'role_back' => anchor ( "access_control/role/role_manager/?tab=roles", "Role Manager"),
			'perm_back' => anchor ( "access_control/permission/permission_manager/?tab=permissions", "Permission Manager"),
			'link_back' => anchor ($page_index.'/?tab=users' , "Back to User Accounts")
		);

		// run validation
		if ($this->form_validation->run () == FALSE)
		{
			// set error properties
			$this->session->set_flashdata('data', validation_errors());
			$data["acl_content"] = "access_control/admin/account_manager/";
			redirect("access_control/admin/account_update/{$user_id}");

		}
		else
		{
				
			// save modification;
			if ($this->adminuiacl_model->edit_user_roles ( $user_id = $this->input->post ( "user_id" ), $this->input->post ( "roles" ) )) {
				$acct = array (
					"first_name" => $this->input->post ( "first_name" ),
					"last_name" => $this->input->post ( "last_name" ),
					"username" => $this->input->post ( "username" ),
					"email_address" => $this->input->post ( "email_address" ),
					"status" => $this->input->post ( "status" ),
					"modified_date" => date ( "Y-m-d H:i:s" ),
					"modified_by" => $this->session->userdata ( "username" )
				);
				$this->adminuiacl_model->account_update ( $user_id, $acct );

				redirect("access_control/admin/account_update/{$user_id}/?UpdateSuccess=success");

			}
			else
			{
				show_error ( "Failed assigning user." );
			}
		}
	}

	// create admin account form
	public function add_admin()
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "add_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$this->form_data = new stdClass;
		
		// set empty default form field values
		$this->_set_fields();
		// Set validation properties for adding an admin
		$this->_set_new_user_rules();

		if ($this->form_validation->run() == FALSE)
		{
					// Validation error messages
			$this->session->set_flashdata('validation_results', validation_errors());
			$this->validation_results = validation_errors();
			$data = array (
				'title' => $this->super_title,
				'version_official_name' => $this->version_title,
				'subtitle' => 'Account Manager',
				'panel_title' => 'User Accounts',
				'acl_content' => 'access_control_view/admin/account_manager',
			);
			redirect("access_control/admin/account_manager#add_admin");
			// CI only load form "set_value" data if the method below is defined. the previous code is commented above
			//$this->account_manager($offset = 0);

		}
		else
		{
			// Call admin model and process new admin
			$this->add_admin_process();
		}
	}

	private function add_admin_process()
	{

		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "add_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$role_array = $this->input->post("roles");
		// For now, only 1 role per user until requirements are set.
		$role_id = $role_array;
		
		// validation complete
		$new_admin = array(
			"first_name" => $this->input->post("first_name"),
			"last_name" => $this->input->post("last_name"),
			"username" => $this->input->post("username"),
			"password" => md5($this->input->post("password")),
			"password_reset" => 1,
			"email_address" => $this->input->post("email_address"),
			"status" => 1,
			"date_created" => date("Y-m-d H:i:s")
		);

		$response = $this->adminuiacl_model->admin_add_user($new_admin);

		if ($response == NO_DUPLICATE_ADMIN)
		{
			//Everything checks out add role
			$new_admin = $this->adminuiacl_model->get_user_by('username',$this->input->post("username"));
			$this->adminuiacl_model->add_user_role($new_admin->user_id,$role_id);

			// if no duplicate admin account is found, send an email notification to the new admin
			$name = $this->input->post ( "first_name" ) . " " . $this->input->post ( "last_name" );
			$email = $this->input->post ( "email_address" );

			$this->email->set_newline ( "\r\n" );
			$this->email->from (".FROM_EMAIL.", ".FROM_NAME.");
			$this->email->to (". APPROVAL_ADMIN.");
			$this->email->cc ( $email );
			$this->email->subject ( "User account created" );
			$body = "
			<table border=\"1\" style=\"border-width: thin; border-spacing: 2px; border-style: none; border-color: #ccc;\">
				<tr>
					<td>User</td>
					<td>{$name}</td>
				</tr>
				<tr>
					<td>Message</td>
					<td width=\"300\">
						{$name} has access to DOL APIv2 AdminUI.<br>
						Username: {$email}<br>
						Password: {$this->input->post("password2")}<br>
						Please " . anchor ( "" . base_url () . "login", "Login" ) . " at your earliest convenience and change your password.
					</td>
				</tr>
			</table>";

			$this->email->message ( $body );

			if ($this->email->send ())
			{
				// get user details
				$user = $this->adminuiacl_model->get_by_user ( $user = $this->input->post ( "username" ) )->row ();

				// echo 'Your message was sent successfully...';
				redirect ( "access_control/admin/account_manager/?success_message=true&user={$user->user_id}#add_admin" );
			}
			else
			{
				show_error ( $this->email->print_debugger () );
			}
		}
		elseif ($response == DUPLICATE_ADMIN)
		{
			// redirect to password tab on duplicate admin error
			redirect ( "access_control/admin/account_manager/?admin_error_message=true#add_admin" );
		}
		elseif ($response == DUPLICATE_REG)
		{
			// redirect to password tab on registered user error
			redirect ( "access_control/admin/account_manager/?reg_error_message=true#add_admin" );
		}
		else
		{
			// TODO throw internal server error page
			show_error ( "The system is currently down for maintenance please try again later." . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
	}

	// view pending account details
	public function pend_account_view($user_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "view_users" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// set common properties
		$page_index = $this->session->userdata('last_pending_pagination');

		$data = array(
			'title' => $this->super_title,
			'version_official_name' => $this->version_title,
			'subtitle' => 'View Pending User Account',
			'acct' => $this->adminuiacl_model->get_by_id_pendrequest ( $user_id )->row (),
			'acl_content' => 'access_control_view/pend_account_view',
			'link_back' => anchor ( $page_index.'/?tab=users', 'Back to Pending Request')
		);
		$this->load->view ( "access_control/template", $data );
	}

	// load pending request view
	public function pending_request($offset = 0)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "view_users" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// offset
		$uri_segment = 4;
		// load data
		$users = $this->adminuiacl_model->get_paged_list_pendrequest ( $this->limit, $offset )->result ();

		// generate pagination
		$config ["base_url"] = site_url ( "access_control/admin/pending_request/" );
		$config ["total_rows"] = $this->adminuiacl_model->count_all_pendrequest ();
		$config ["per_page"] = $this->limit;
		$config ["uri_segment"] = $uri_segment;
		$config ["anchor_class"] = "class=\"btn btn-primary btn-sm\"";
		$config['full_tag_open'] = '<ul class="pagination pagination-mm">';
		$config['full_tag_close'] = '</ul>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><span>';
		$config['cur_tag_close'] = '<span class="sr-only">(current)</span></span></li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['first_link'] = 'First';
		$config['prev_link'] = 'Previous';
		$config['last_link'] = 'Last';
		$config['next_link'] = 'Next';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$this->pagination->initialize ( $config );

		// Need to remember the last pagination number in order to properly link back from an action
		$this->session->set_userdata('last_pending_pagination', current_url());

		// generate table data
		$this->table->set_empty ( "&nbsp;" );
		$table_setup = array (
				"table_open" => "<table class=\"table table-striped table-bordered table-hover\">",
				"heading_cell_start"  => "<th scope='col'>"
		);
		$this->table->set_heading ("First Name", "Last Name", "User Name", "Status", "Request Date", "Actions" );
		$i = 0 + $offset;
		$view = array (
				"class" => "btn btn-warning btn-sm"		
		);
		$update = array (
				"class" => "btn btn-success btn-sm"
		);
		$delete = array (
				"class" => "btn btn-danger btn-sm",
				"data-toggle" => "confirmation"
		);
		foreach ( $users as $acct )
		{
			$this->table->add_row ($acct->first_name, $acct->last_name, $acct->username, strtoupper ( $acct->status ) == "1" ? "Disabled" : "Pending", date ( "m/d/Y", strtotime ( $acct->date_requested ) ), anchor ( "access_control/admin/pend_account_view/" . $acct->user_id, "View <span class='scrn_rdr'>".$acct->first_name." ".$acct->last_name."</span>", $view ) . " " . anchor ( "access_control/admin/pend_account_update/" . $acct->user_id, "Approve <span class='scrn_rdr'>".$acct->first_name." ".$acct->last_name."</span>", $update ) . " " . anchor ( "access_control/admin/pend_account_delete/" . $acct->user_id, "Deny <span class='scrn_rdr'>".$acct->first_name." ".$acct->last_name."</span>", $delete ) );
		}
		$this->table->set_template ($table_setup);

		$data = array(
			'table' => $this->table->generate (),
			'pagination' => $this->pagination->create_links(),
			'title' => $this->super_title,
			'version_official_name' => $this->version_title,
			'subtitle' => "Pending User Account Requests",
			'panel_title' => "Pending Requests",
			'acl_content' => "access_control_view/pending_request"
		);

		$this->load->view ( "access_control/template", $data );
	}

	public function pend_account_update($user_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		// set validation properties
		//$this->_set_pending_rules();

		$page_index = $this->session->userdata('last_pending_pagination');

		// prefill form values
		$acct = $this->adminuiacl_model->get_by_id_pendrequest ( $user_id )->row ();
		
		$this->form_data = new stdClass;
		$this->form_data->user_id = "$user_id";
		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->username = $acct->username;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );
		$this->form_data->date_requested = date ( 'm/d/Y', strtotime ( $acct->date_requested ) );

		$data = array(
			// set common properties
			'title' => $this->super_title,
			'version_official_name' => $this->version_title,
			'subtitle' => 'Approve Pending User Account',
			'role_list' => $this->adminuiacl_model->get_all_roles (),
			'perm_list' => $this->adminuiacl_model->get_all_perms (),
			'action' => site_url ( 'access_control/admin/pend_account_modify'),
			'role_back' => anchor ( 'access_control/role/role_manager/?tab=roles', 'Role Manager'),
			'perm_back' => anchor ( 'access_control/permission/permission_manager/?tab=permissions', 'Permission Manager'),
			'acl_content' => 'access_control_view/pend_account_modify',
			'link_back' => anchor ( $page_index.'/?tab=users', ' Back to Pending Request')
		);

		$this->load->view ( "access_control/template", $data );
	}

	// pending requet modifier
	public function pend_account_modify()
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$page_index = $this->session->userdata('last_pending_pagination');

		// load database model
		$this->load->model ( "adminuiacl_model" );

		// set common properties
		$data = array(
			'title' => $this->super_title,
			'version_official_name' => $this->version_title,
			'subtitle' => 'Update Pending User Account',
			'action' => site_url ( 'access_control/admin/pend_account_modify' ),
			'link_back' => anchor ( $page_index.'/?tab=users', 'Back to Pending Request')
		);

		$user_id = $this->input->post ( "user_id" );
		$this->form_data = new stdClass;
		
		// Get requestor's information
		$user_info = $this->adminuiacl_model->get_by_id_pendrequest ( $user_id )->row ();

		// set empty default form field values
		$this->_set_fields_pendreqst ();
		// set validation properties
		$this->_set_rules_pendreqst ();

		// run validation
		if ($this->form_validation->run () == FALSE)
		{
			// Set error properties
			$this->session->set_flashdata('data', validation_errors());
			$data ["acl_content"] = "access_control_view/pend_account_modify";
			redirect("access_control/admin/pend_account_update/{$user_id}");

		}
		else
		{

			// Transfer new account
			$new_acct = array (
				"first_name" => $user_info->first_name,
				"last_name" => $user_info->last_name,
				"username" => $this->input->post ( "username" ),
				"password" => $user_info->password,
				"password_reset" => 0,
				"email_address" => $this->input->post ( "email_address" ),
				"status" => 1,
				"date_created" => date ( "Y-m-d H:i:s" ),
				"modified_by" => $this->session->userdata ( "username" )
			);

			$role_array = $this->input->post('roles');
			// Send to data model for processing
			$this->adminuiacl_model->approve_request ( $user_id, $new_acct,$role_array);

			// After request has been approved, delete record from pending table
			$this->adminuiacl_model->delete_approved_request ( $user_id );

			// prepare email notification
			$this->email->set_newline ( "\r\n" );
			$this->email->from (".FROM_EMAIL.", ".FROM_NAME.");
			$this->email->to ( $this->input->post ( "email_address" ) );
			$this->email->cc (".CC_EMAIL.",".CC_NAME.");
			$this->email->subject ( "User Account Activated" );
			$this->email->message ( "Your APIv2 user account has been approved. Please login to verify " . anchor ( "" . base_url () . "login", "Login" ) . " " );
			$this->email->send ();

			// get user details
			$this->request_approved_view ( $user = $this->input->post ( "username" ), $name = $user_info->first_name );
		}
	}
	public function pend_account_delete($user_id)
	{
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// set validation properties
		// $this->_set_rules();
		// prefill form values

		$page_index = $this->session->userdata('last_pending_pagination');
		$acct = $this->adminuiacl_model->get_by_id_pendrequest( $user_id )->row ();

		$this->form_data = new stdClass;
		$this->form_data->user_id = "$user_id";
		$this->form_data->first_name = $acct->first_name;
		$this->form_data->last_name = $acct->last_name;
		$this->form_data->username = $acct->username;
		$this->form_data->email_address = $acct->email_address;
		$this->form_data->status = strtoupper ( $acct->status );
		$this->form_data->date_requested = date ( 'm/d/Y', strtotime ( $acct->date_requested ) );

		// set common properties
		$data = array(
			'title' => 'DOL APIv2 AdminUI '.$this->version,
			'version_official_name' => $this->version_title,
			'subtitle' => 'Delete Pending User Account',
			'del_pending_account' => site_url ( "access_control/admin/del_pend_account_process/{$user_id}"),
			'action' => site_url ( 'access_control/admin/pend_account_delete' ),
			'acl_content' => 'access_control_view/pend_account_delete',
			'link_back' => anchor ($page_index.'/?tab=users', ' Back to Pending Request')
		);

		$this->load->view ( "access_control/template", $data );
	}
	public function del_pend_account_process($user_id)
	{
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

			$this->adminuiacl_model->delete_approved_request ( $user_id );

			/* TODO Prepare proper deny message
			$this->email->set_newline ( "\r\n" );
			$this->email->from ( "" . FROM_EMAIL . "", "" . FROM_NAME . "" );
			$this->email->to ( $this->input->post ( "email_address" ) );
			$this->email->cc ( "" . CC_EMAIL . "", "" . CC_NAME . "" );
			$this->email->subject ( "User Account Activated" );
			$this->email->message ( "Your request for an APIv2 account has been denied." );
			$this->email->send ();
			*/
			redirect ( "access_control/admin/pending_request/?del_success_message=success" );
	}
	public function request_approved_view($user, $name)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "access_acl" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// set common properties
		$data = array(

			"title" => $this->super_title,
			"version_official_name" => $this->version_title,
			"subtitle" => "User Account has been Approved",
			"acct" => $this->adminuiacl_model->get_by_user ( $user = $this->input->post ( "username" ) )->row (),
			"acl_content" => "access_control_view/request_approved_view",
			"message" =>
				"<div class = 'alert alert-success alert-dismissable'>
				<button type = 'button' class ='close' data-dismiss = 'alert' aria-hidden = 'true'>&times;</button>
			{$name}'s account has been activated. </div>" ,
			"link_back" => anchor('access_control/admin/account_manager/?tab=users', ' Back to User Accounts')
		);

		$this->load->view ( "access_control/template", $data );
	}
	public function roles_select_ui()
	{

		$roles_json = '';
		$all_perms = $this->adminuiacl_model->get_all_perms ();
		$role_id = $this->input->get ( "rid" );
		$perm_keys = $this->adminuiacl_model->get_role_perms_keys ( $role_id );
		$flag = 0;
		
		if(!empty($perm_keys))
		{
			// Populate the dropdown options here
			foreach ( $all_perms as $perm )
			{
				foreach ( $perm_keys as $keys )
				{
					if ($perm->perm_id == $keys->perm_id)
					{
						$flag = 1;
					}
				}
				if ($flag)
				{
					$roles_json .= " <option selected value = " . $perm->perm_id . "> " . $perm->name;
				}
				else
				{
					// UNCOMMENT if you want the list unfiltered
					//$roles_json .= "<option  value=" . $perm->perm_id . ">" . $perm->name;
				}
				$flag = 0;
			}
		}
		else
		{
			$roles_json .= "<option selected> NO PERMISSIONS HAVE BEEN ASSIGNED TO THIS ROLE.";
		}
		print_r ( $roles_json );

		// echo json_encode($roles_json);
	}
	// admin password change...
	public function password_change_process()
	{
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_user" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}

		$current_page = $this->session->flashdata('last_pagination');
		$page_index = ($current_page * $this->limit) - 10;

		// Need to remember the last pagination number in order to properly link back from an action
		$this->session->set_flashdata('last_pagination', $current_page);

		// set default parameters
		$data ["passwd_success"] = "";
		$data ["passwd_error"] = "";
		$data ["user"] = "";
		
		$acct = $this->adminuiacl_model->get_by_user ( $user = $this->input->post ( "username" ) )->row ();
		$user_id = $acct->user_id;
		$this->form_data = new stdClass;
		$this->form_data->user_id = $acct->user_id;		

		// set empty default form field values
		$this->_set_passwd_fields ();
		// set validation properties
		$this->_set_passwd_rules ();
		
		// run validation
		if ($this->form_validation->run () == FALSE)
		{
			$this->session->set_flashdata('data', validation_errors());
			$data ["title"] = $this->super_title;
			$data ["version_official_name"] = $this->version_title;
			$data["subtitle"] = "Modify Account";
			$data["panel_title"] = "Account Modify";
			$data["link_back"] = anchor($page_index.'/?tab=users', " Back to User Accounts");
			$data["acl_content"] = "access_control/admin/account_manager/";
			redirect("access_control/admin/account_update/{$user_id}#password-pills");

		}
		else
		{
			// get authenticated user user_id
			$acct = $this->adminuiacl_model->get_by_user ( $user = $this->input->post ( "username" ) )->row ();
			$this->form_data->user_id = $acct->user_id;
			$this->form_data->password = $acct->password;
			// get user user_id post-back update
			$data ["user_id"] = $acct->user_id;
			$data ["email_address"] = $acct->email_address;

			// process password change
			$new_password = md5 ( $this->input->post ( "password" ) );
			$conf_password = md5 ( $this->input->post ( "password2" ) );

			// echo $post_password; exit;
			if ($new_password === $conf_password)
			{
				// call the password change method
				$this->password_change_admin ( $this->form_data->user_id, $conf_password, $acct->email_address );

				// redirect password change profile tab with success message
				redirect ( "access_control/admin/account_update/{$acct->user_id}/?PasswordChangeSuccess=success#password-pills" );
			}
			else
			{
				// redirect password change profile tab with error message
				redirect ( "access_control/admin/account_update/{$acct->user_id}/?PasswordChangeError=error#password-pills" );
			}
		}
	}

	// process password by super admin
	protected function password_change_admin($user_id, $conf_password, $email)
	{
		$acct = array (
				"password" => $conf_password,
				"password_reset" => "" . PASS_RESET_REQUIRED . "",
				"modified_date" => date ( "Y-m-d H:i:s" ),
				"modified_by" => $this->session->userdata ( "username" )
		);

		// parse data array to update user table
		$this->adminuiacl_model->update_password_prompt ( $user_id, $acct );

		// prepare email notification
		$this->email->set_newline ( "\r\n" );
		$this->email->from ("".FROM_EMAIL."", "".FROM_NAME."");
		$this->email->to ( $email );
		$this->email->cc (".CC_EMAIL.", ".CC_NAME.");
		$this->email->subject ( "Password reset by admin" );
		$this->email->message ( "You password has been changed to: " . $this->input->post ( "password2" ) . " Please return to the " . anchor ( "" . base_url () . "login", "Login" ) . " page to verify" );
		$this->email->send ();
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
	}
	
	// protect pending rules
	protected function _set_pending_rules()
	{
		$id_check = $this->input->post('user_id');
		$current_acct = $this->adminuiacl_model->get_user_by('user_id',$id_check);

		$current_acct_name = $current_acct->username;
		$current_acct_email = $current_acct->email_address;

		$post_username = $this->input->post('username');
		$post_email_address = $this->input->post('email_address');

		//Do not apply rule is the current user already owns the username
		if($current_acct_name != $post_username){
			$this->form_validation->set_rules ( "username", "username", "trim|required|is_unique[admin_user.username]");
		}
		//Do not apply rule is the current user already owns the email address
		if($current_acct_email != $post_email_address) {
			$this->form_validation->set_rules ( "email_address", "email address", "trim|required|valid_email|is_unique[admin_user.email_address]");
		}
		$this->form_validation->set_rules ( "first_name", "First Name", "trim|required" );
		$this->form_validation->set_rules ( "last_name", "Last Name", "trim|required" );
		$this->form_validation->set_rules ( "status", "Account Status", "trim|required" );
		$this->form_validation->set_rules ( "roles[]", "Roles", "required" );

		$this->form_validation->set_message ( "required", "* required" );
		$this->form_validation->set_message ( "isset", "* required" );
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value." );
		$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
	
	// Validation rules for modifying an existing user
	protected function _set_user_rules() {

		$id_check = $this->input->post('user_id');
		$current_acct = $this->adminuiacl_model->get_user_by('user_id',$id_check);

		$current_acct_name = $current_acct->username;
		$current_acct_email = $current_acct->email_address;

		$post_username = $this->input->post('username');
		$post_email_address = $this->input->post('email_address');

		//Do not apply rule if the current user already owns the username
		if($current_acct_name != $post_username)
		{
			$this->form_validation->set_rules ( "username", "User Name", "trim|required|is_unique[admin_user.username]|min_length[2]|max_length[50]" );
		}
		//Do not apply rule if the current user already owns the email address
		if($current_acct_email != $post_email_address)
		{
			$this->form_validation->set_rules ( "email_address", "Email Address", "trim|required|valid_email|is_unique[admin_user.email_address]" );
		}
		$this->form_validation->set_rules ( "first_name", "First Name", "trim|required|min_length[2]|max_length[50]" );
		$this->form_validation->set_rules ( "last_name", "Last Name", "trim|required|min_length[2]|max_length[50]" );
		$this->form_validation->set_rules ( "roles", "Assign Role", "required" );
		
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value." );
		//$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}

	// Validation rules for adding a new user
	protected function _set_new_user_rules()
	{
		$this->form_validation->set_rules ( "first_name", "First Name", "trim|required|min_length[2]|max_length[50]" );
		$this->form_validation->set_rules ( "last_name", "Last Name", "trim|required|min_length[2]|max_length[50]" );
		$this->form_validation->set_rules ( "email_address", "Email Address", "trim|required|valid_email|is_unique[admin_user.email_address]" );
		$this->form_validation->set_rules ( "username", "User Name", "trim|required|is_unique[admin_user.username]|min_length[2]|max_length[50]" );
		$this->form_validation->set_rules ( "password", "Password", "required|min_length[6]|max_length[16]" );
		$this->form_validation->set_rules ( "password2", "Confirm Password", "required|matches[password]" );
		$this->form_validation->set_rules ( "roles", "Assign Role", "required" );
		
		$this->form_validation->set_message ( "isset", "* required" );
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value." );
		$this->form_validation->set_message ( "matches[password]", "Passwords do not match." );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
	  		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}

	// set empty default form field values for pending request
	protected function _set_fields_pendreqst()
	{		
		$this->form_data->first_name = "";
		$this->form_data->last_name = "";
		$this->form_data->email_address = "";
	}

	// validation rules
	protected function _set_rules_pendreqst()
	{
		$this->form_validation->set_rules ( "first_name", "First name", "trim|required|min_length[2]|max_length[50]" );
		$this->form_validation->set_rules ( "last_name", "Last name", "trim|required|min_length[2]|max_length[50]" );
		$this->form_validation->set_rules ( "roles", "Role", "trim|required" );
		// is_unique validator enforces strict username policy
		$this->form_validation->set_rules ( "username", "User Name", "trim|required|is_unique[admin_user.username]|min_length[2]|max_length[50]" );		
		// is_unique validator enforces strict email policy
		$this->form_validation->set_rules ( "email_address", "Email Address", "trim|required|valid_email|is_unique[admin_user.email_address]" );
		
		$this->form_validation->set_message ( "is_unique", "This %s has already been taken. Please try a different value.");
		$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<h3 class=\"alert alert-danger alert-dismissable\">
      		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</h3>" );
	}

	// set empty default form field password values
	protected function _set_passwd_fields()
	{
		$this->form_data->password = "";
		$this->form_data->password2 = "";
	}

	// validation rules
	protected function _set_passwd_rules()
	{
		$this->form_validation->set_rules ( 'password', 'New Password', 'trim|required|min_length[6]|max_length[32]' );
		$this->form_validation->set_rules ( 'password2', 'Confirm password', 'trim|required|min_length[6]|max_length[32]|matches[password]' );

		$this->form_validation->set_message ( "matches", "Passwords do not match." );
		$this->form_validation->set_message ( "valid_date", "date format is not valid. dd-mm-yyyy" );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
     		<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
}