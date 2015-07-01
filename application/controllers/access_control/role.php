<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 * APIv2 AdminUI Pilot Role Controller
 *
 * @package Role Controller
 * 
 * @author  johnsonpatrickk (Patrick Johnson Jr.)
 * @license	http://developer.dol.gov
 * @version 1.0.0
 */

class Role extends CI_controller {
	
	// number of records per page
	private $limit = 10;
	private $acl_table;
	private $super_title = '';
	private $version = '';
	private $version_title = '';
	private $validation_results = '';
	
	public function __construct()
	{
		parent::__construct ();
		$this->is_logged_in ();
		
		// bootstrap dashboard and access control model
		$this->load->model ( "adminuiacl_model", "", TRUE );
		$this->load->model ( "version_model", "", TRUE );
		
		$this->acl_table = ( object ) $this->config->item ( "acl" );
		$this->acl_table = & $this->acl_table->table;
		$this->version = $this->version_model->get_version ();
		$this->version_title = $this->version_model->get_name ();
		$this->super_title = $this->version_model->get_name () . ' ' . $this->version_model->get_product () . ' ' . $this->version;
	}

	public function is_logged_in()
	{
		$is_logged_in = $this->session->userdata ( "is_logged_in" );
		// print_r($this->session->all_userdata()); exit;
		
		if (! isset ( $is_logged_in ) || $is_logged_in != TRUE)
		{
			echo "You don't have permission to access this page. " . anchor ( "/login", "Login Now" );
			die ();
		}
	}

	public function role_manager($offset = 0)
	{
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "view_roles" ))
		{
			show_error ( "Permission denied.", 401 );
		}
		
		// offset
		$uri_segment = 4;
		// $offset = $this->uri->segment($uri_segment);
		// Need to remember the last pagination number in order to properly link back from an action use current url
		$this->session->set_userdata ( 'last_key_pagination', current_url () );
		// load data
		$role = $this->adminuiacl_model->get_role_paged_list ( $this->limit, $offset )->result ();
		// print_r($role);exit;
		// generate pagination
		$config = array (
				'base_url' => site_url ( 'access_control/role/role_manager/' ),
				'total_rows' => $this->adminuiacl_model->count_all_roles (),
				'per_page' => $this->limit,
				'uri_segment' => $uri_segment,
				'suffix' => '/?tab=roles',
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
				'first_url' => site_url ( 'access_control/role/role_manager/?tab=roles' ),
				'last_link' => 'Last',
				'next_link' => 'Next',
				'first_tag_open' => '<li>',
				'first_tag_close' => '</li>',
				'last_tag_open' => '<li>',
				'last_tag_close' => '</li>' 
		);
		$this->pagination->initialize ( $config );
		
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data ["subtitle"] = "List of User Roles";
		$data ["panel_title"] = "AdminUI Roles";
		$data ["add_role"] = site_url ( "access_control/role/add_role/?tab=add_roles" );
		$data ["perm_list"] = $this->adminuiacl_model->get_all_perms ();
		$data ["pagination"] = $this->pagination->create_links ();
		$data ["link_back"] = anchor ( 'access_control/role/role_manager/?tab=roles', " Back to Roles" );
		
		// Need to remember the last pagination number in order to properly link back from an action
		$this->session->set_userdata ( 'last_pagination', current_url () );
		// generate table data
		$this->table->set_empty ( "&nbsp;" );
		$table_setup = array (
				"table_open" => "<table class=\"table table-striped table-bordered table-hover\">",
				"heading_cell_start" => "<th scope='col'>" 
		);
		$this->table->set_heading ( "System ID", "System Name", "Description", "Actions" );
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
		foreach ( $role as $group )
		{
			// print_r($group);exit;
			if ($group->locked)
			{
				$this->table->add_row ( $group->slug, $group->name, $group->description, anchor ( "access_control/role/role_view/" . $group->role_id, "View <span class='scrn_rdr'>" . $group->name . "</span>", $view ) );
			}
			else
			{
				$this->table->add_row ( $group->slug, $group->name, $group->description, anchor ( "access_control/role/role_view/" . $group->role_id, "View <span class='scrn_rdr'>" . $group->name . "</span>", $view ) . " " . anchor ( "access_control/role/role_update/" . $group->role_id, "Update <span class='scrn_rdr'>" . $group->name . "</span>", $update ) . " " . anchor ( "access_control/role/role_delete/" . $group->role_id, "Delete <span class='scrn_rdr'>" . $group->name . "</span>", $delete ) );
			}
		}
		$this->table->set_template ( $table_setup );
		// print_r($this->table);exit;
		$data ["table"] = $this->table->generate ();
		// load account view
		$data ["acl_content"] = "access_control_view/role_manager";
		
		if (isset ( $this->validation_results ))
		{
			$data ['validation_errors'] = $this->validation_results;
		}
		else
		{
			$data ['validation_errors'] = '';
		}
		$this->load->view ( "access_control/template", $data );
	}
	// add system role
	public function add_role()
	{
		
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "add_role" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$this->_set_role_rules ();
		
		if ($this->form_validation->run () == FALSE)
		{
			// set common properties
			$this->session->set_flashdata ( 'data', validation_errors () );
			$data ["title"] = $this->super_title;
			$data ["version_official_name"] = $this->version_title;
			$data ["subtitle"] = "Add Role";
			$data ["panel_title"] = "AdminUI Roles";
			$data ["link_back"] = anchor ( "access_control_view/role/role_manager/", "Back to roles" );
			$data ["add_role"] = site_url ( "access_control/role/role_manager#add_role" );
			$data ["acl_content"] = "access_control_view/role_manager";
			redirect ( "access_control/role/role_manager#add_role" );
		}
		else
		{		
			$new_role = array (
					"name" => $this->input->post ( "name" ),
					"slug" => $this->input->post ( "slug" ),
					"description" => $this->input->post ( "description" ) 
			);
			
			$this->adminuiacl_model->add_role ( $new_role );
			$new_perm_array = $this->input->post ( 'perms' );
			$new_role = $this->adminuiacl_model->get_role_by ( "slug", $this->input->post ( "slug" ) );
			
			foreach ( $new_perm_array as $perm ) {
				$this->adminuiacl_model->add_role_perm ( $new_role [0]->role_id, $perm );
			}
			
			$this->form_data->role_id = $role_id;
			$this->form_data->name = $this->input->post ( "name" );
			$this->form_data->slug = $this->input->post ( "slug" );
			$this->form_data->description = $this->input->post ( "description" );
			redirect ( "access_control/role/role_manager?success_message=success#add_role" );
		}
	}
	// view system roles
	public function role_view($role_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "view_roles" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		
		$page_index = $this->session->userdata ( 'last_pagination' );
		
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data ["subtitle"] = "View Role";
		$data ["link_back"] = anchor ( $page_index . '/?tab=roles', "Back to Roles" );
		// get role details
		$data ["role"] = $this->adminuiacl_model->get_role ( $role_id );
		$data ["role_list"] = $this->adminuiacl_model->get_all_roles ();
		$data ["perm_list"] = $this->adminuiacl_model->get_all_perms ();
		
		// load view
		// $this->load->view('personView', $data);
		$data ["acl_content"] = "access_control_view/role_view";
		$this->load->view ( "access_control/template", $data );
	}
	// update system rolee
	public function role_update($role_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_role" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		
		$page_index = $this->session->userdata ( 'last_pagination' );
		
		// prefill form values
		$role = $this->adminuiacl_model->get_role ( $role_id );
		$this->form_data = new stdClass;		
		$this->form_data->role_id = $role_id;
		$this->form_data->name = $role->name;
		$this->form_data->slug = $role->slug;
		$this->form_data->description = $role->description;
		$data ["perm_list"] = $this->adminuiacl_model->get_all_perms ();
		
		$perm_keys = $this->adminuiacl_model->get_role_perms_keys ( $role_id );
		if (! empty ( $perm_keys ))
		{
			foreach ( $data ["perm_list"] as $perm ) {
				foreach ( $perm_keys as $keys )
				{
					if ($perm->perm_id == $keys->perm_id)
					{
						$perm->set = true;
					}
				}
			}
			;
		}
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data ["subtitle"] = 'Update Role';
		// $data["perms"] = $this->adminuiacl_model->get_all_perms();
		
		// $data["role_array"] = $this->adminuiacl_model->user_current_group();
		// $data["user"]->roles = $this->adminuiacl_model->get_user_roles($user_id);
		// $data["role_list"] = $this->adminuiacl_model->get_all_roles();
		$data ["action"] = site_url ( "access_control/role/role_modify" );
		$data ["link_back"] = anchor ( $page_index . '/?tab=roles', "Back to Roles" );
		
		// load view
		$data ["acl_content"] = "access_control_view/role_modify";
		$this->load->view ( "access_control/template", $data );
	}
	// modify stystem role
	public function role_modify()
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "edit_role" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		$page_index = $this->session->userdata ( 'last_pagination' );
		
		$role_id = $this->input->post ( "role_id" );
		// set validation properties
		$this->_set_change_role_rules ();
		
		if ($this->form_validation->run () == FALSE)
		{
			// set common properties
			$this->session->set_flashdata ( 'data', validation_errors () );
			$data ["title"] = $this->super_title;
			$data ["version_official_name"] = $this->version_title;
			$data ["subtitle"] = "Add Role";
			$data ["panel_title"] = "AdminUI Role";
			$data ["link_back"] = anchor ( $page_index . '/?tab=roles', "Back to Roles" );
			$data ["add_perm"] = site_url ( "access_control/role/role_modify" );
			$data ["acl_content"] = "access_control_view/role_modify";
			
			redirect ( "access_control/role/role_update/{$role_id}" );
		}
		else
		{
			$role_pdo = array (
					"role_id" => $this->input->post ( "role_id" ),
					"name" => $this->input->post ( "name" ),
					"slug" => $this->input->post ( "slug" ),
					"description" => $this->input->post ( "description" ) 
			);
			$this->form_data->role_id = $role_id;
			$this->form_data->name = $this->input->post ( "name" );
			$this->form_data->slug = $this->input->post ( "slug" );
			$this->form_data->description = $this->input->post ( "description" );
			$this->adminuiacl_model->edit_role ( $role_id, $role_pdo );
			$this->adminuiacl_model->edit_role_perms ( $role_id, $this->input->post("perms"));
			
			$data ["title"] = $this->super_title;
			$data ["subtitle"] = "Modify Role";
			$data ["panel_title"] = "AdminUI Role";
			
			// Extract allowable permissions by roles based on role id.
			// TODO ADD TO HELPER CLASS
			unset ( $data ["perm_list"] );
			$data ["perm_list"] = $this->adminuiacl_model->get_role_perms ( $role_id );
			// print_r($data["perm_list"]);exit;
			
			// Update the role
			$data ["action"] = site_url ( "access_control/role/role_update/{$role_id}" );
			$data ["link_back"] = anchor ( $page_index . '/?tab=roles', "Back to Roles" );
			$data ["acl_content"] = "access_control_view/role_modify";
			
			redirect ( "access_control/role/role_update/{$role_id}/?UpdateSuccess=success" );
		}
	}
	// delete system roles
	public function role_delete($role_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_role" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// set validation properties
		// $this->_set_rules();
		$page_index = $this->session->userdata ( 'last_pagination' );
		
		// prefill form values
		$role = $this->adminuiacl_model->get_role ( $role_id );
		$data ["role"] = $role;
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
		$data ["subtitle"] = 'Delete Role';
		$data ["del_role"] = site_url ( "access_control/role/del_role_request/{$role_id}" );
		$data ["action"] = site_url ( "access_control/role/role_delete" );
		$data ["link_back"] = anchor ( $page_index . '/?tab=roles', "Back to Roles" );
		
		// load view
		$data ["acl_content"] = "access_control_view/role_delete";
		$this->load->view ( "access_control/template", $data );
	}
	// process delete request, when super admin deletes a role
	public function del_role_request($role_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_role" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// for additional security, avoid direct access to models
		$this->del_role_process($role_id);
		redirect ( "access_control/role/role_manager/?tab=roles&del_success_message=success" );
	}
	
	// process delete request, when super admin deletes a role
	private function del_role_process($role_id)
	{
		// check roles and permissions
		if (! $this->adminuiacl_model->user_has_perm ( $this->session->userdata ( "user_id" ), "delete_role" ))
		{
			show_error ( "You do not have access to this section " . anchor ( $this->agent->referrer (), "Return", 'title="Go back to previous page"' ) );
		}
		// process admin request and return 
		$this->adminuiacl_model->del_role ( $role_id );
	}
	
	// validation rules
	protected function _set_role_rules()
	{
		$this->form_data->name = "";
		$this->form_data->slug = "";
		$this->form_data->description = "";
		
		$this->form_validation->set_rules ( "name", "System Name", "trim|min_length[3]|required|is_unique[role.name]" );
		$this->form_validation->set_rules ( "slug", "System ID", "trim|min_length[3]|required|is_unique[role.slug]" );
		$this->form_validation->set_rules ( "description", "Description", "trim|required" );
		$this->form_validation->set_rules ( "perms[]", "Permissions", "trim|required" );
		
		$this->form_validation->set_message ( "is_unique", "%s has already been taken. Please try a different value." );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
        	<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );

	}
	protected function _set_change_role_rules()
	{		
		// set empty default form field values
		$this->form_data->name = "";
		$this->form_data->slug = "";
		$this->form_data->description = "";
		
		$role_id = $this->input->post ( 'role_id' );
		$role_obj = $this->adminuiacl_model->get_role ( $role_id );
		
		$new_name = $this->input->post ( 'name' );
		$new_slug = $this->input->post ( 'slug' );
		
		// print_r($role_obj);
		// print_r($new_name." ".$new_slug);
		
		if ($role_obj->name != $new_name)
		{
			$this->form_validation->set_rules ( "name", "System Name", "trim|min_length[3]|required|is_unique[role.name]" );
		}
		
		if ($role_obj->slug != $new_slug)
		{
			$this->form_validation->set_rules ( "slug", "System ID", "trim|min_length[3]|required|is_unique[role.slug]" );
		}
		$this->form_validation->set_rules ( "description", "Description", "trim|required" );
		
		$this->form_validation->set_message ( "is_unique", "%s has already been taken. Please try a different value." );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
}