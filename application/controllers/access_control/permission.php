<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * APIv2 AdminUI Pilot Permissions Controller
 *
 * @package	Role Controller
 * @author	johnsonpatrickk (Patrick Johnson Jr.)
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Permission extends CI_controller {

	// number of records per page
	private $limit = 10;
	private $acl_table; 
	private $super_title='';
	private $version = '';
	private $version_title = '';
	private $validation_results = '';
	
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();

		// bootstrap dashboard and access control model
		$this->load->model("adminuiacl_model","", TRUE);
		$this->load->model ( "version_model", "", TRUE );
		
		$this->acl_table = (object)$this->config->item("acl");
		$this->acl_table =& $this->acl_table->table;
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
			echo "You don't have permission to access this page. ". anchor("/login", "Login Now");
			die();
		}
	}

	public function permission_manager($offset = 0)
	{
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_perms"))
		{
			show_error("Permission denied.", 401);
		}
		//offset
		$uri_segment = 4;
		//$offset = $this->uri->segment($uri_segment);

		// generate pagination
		$config = array(
				'base_url' => site_url('access_control/permission/permission_manager/'),
				'total_rows' => $this->adminuiacl_model->count_all_perms(),
				'per_page' => $this->limit,
				'uri_segment' => $uri_segment,
				'suffix' => '/?tab=permissions',
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
				'first_url' => site_url('access_control/permission/permission_manager/?tab=permissions'),
				'last_link' => 'Last',
				'next_link' => 'Next',
				'first_tag_open' => '<li>',
				'first_tag_close' => '</li>',
				'last_tag_open' => '<li>',
				'last_tag_close' => '</li>'
		);
		
		$perms = $this->adminuiacl_model->get_perm_paged_list($this->limit, $offset)->result();	
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;		
		$data["subtitle"] = "List of Permissions";
		$data["panel_title"] = "AdminUI Permissions";
		
		$this->pagination->initialize($config);
		$data["pagination"] = $this->pagination->create_links();		
		$data["add_perm"] = site_url("access_control/permission/add_perm/?tab=add_permission");
		$data ["link_back"] = anchor ('access_control/permission/permission_manager/?tab=permissions', " Back to Permissions");		
		
		// Need to remember the last pagination number in order to properly link back from an action
		$this->session->set_userdata('last_pagination', current_url());
				
		// generate table data
		$this->table->set_empty("&nbsp;");
		$table_setup = array("table_open" => "<table class=\"table table-striped table-bordered table-hover\">",
				"heading_cell_start"  => "<th scope='col'>"
		);
		$this->table->set_heading("System ID", "System Name", "Description", "Actions");
		$view = array("class" => "btn btn-warning btn-sm");
		$update = array("class" => "btn btn-success btn-sm");
		$delete = array("class" => "btn btn-danger btn-sm", "data-toggle" => "confirmation");
		foreach ($perms as $perm)
		{
			if($perm->locked)
			{					
				$this->table->add_row($perm->slug, $perm->name, $perm->description,
					anchor("access_control/permission/perm_view/".$perm->perm_id, "View <span class='scrn_rdr'>".$perm->name."</span>", $view)
				);
			}
			else
			{
				$this->table->add_row($perm->slug, $perm->name, $perm->description,
						anchor("access_control/permission/perm_view/".$perm->perm_id, "View <span class='scrn_rdr'>".$perm->name."</span>", $view)." ".
						anchor("access_control/permission/perm_update/".$perm->perm_id, "Update <span class='scrn_rdr'>".$perm->name."</span>", $update)." ".
						anchor("access_control/permission/perm_delete/".$perm->perm_id, "Delete <span class='scrn_rdr'>".$perm->name."</span>", $delete)
				);				
			}	
		}
		$this->table->set_template($table_setup);
		$data["table"] = $this->table->generate();

		// load account view
		$data["acl_content"] = "access_control_view/permission_manager";
		if(isset($this->validation_results))
		{
			$data['validation_errors'] = $this->validation_results;
		}
		else
		{
			$data['validation_errors'] = '';
		}		
		$this->load->view("access_control/template", $data);
	}
	public function add_perm(){
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "add_perm"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}

		//validation
		$this->_set_perm_rules();

		if($this->form_validation->run() == FALSE)
		{			
			$this->validation_results = validation_errors();
						
			// set common properties
			$this->session->set_flashdata('data', validation_errors());
			$data ["title"] = $this->super_title;
			$data ["version_official_name"] = $this->version_title;		
			$data["subtitle"] = "Add Permission";
			$data["panel_title"] = "AdminUI Permissions";
			$data["link_back"] = anchor("access_control_view/permission/permission_manager/?tab=permissions", "Back to Permissions");
			$data["add_perm"] = site_url("access_control/permission/permission_manager#add_permission");
			$data["acl_content"] = "access_control_view/permission_manager";

			redirect("access_control/permission/permission_manager#add_permission");
			
		}
		else
		{
			$new_perm = array(
					"name"  => $this->input->post("name"),
					"slug"  => $this->input->post("slug"),
					"description" => $this->input->post("description")
				);

			$this->adminuiacl_model->add_perm($new_perm);
			redirect("access_control/permission/permission_manager/1/add_perm?success_message=success#add_permission");
		}
	}
	
	public function perm_view($perm_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "view_perms"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		$page_index = $this->session->userdata('last_pagination');
				
		// set common properties
		$data ["title"] = $this->super_title;		
		$data ["version_official_name"] = $this->version_title;
				
		$data["subtitle"] = "View Permission";
		$data["link_back"] = anchor($page_index.'/?tab=permissions', "Back to Permissions");
		// get permission details
		$data["perm"] = $this->adminuiacl_model->get_perm($perm_id);
		// load view
		$data["acl_content"] = "access_control_view/permission_view";
		$this->load->view("access_control/template", $data);
	}

	public function perm_update($perm_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "edit_perm"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		$page_index = $this->session->userdata('last_pagination');
						
		// prefill form values
		$perm = $this->adminuiacl_model->get_perm($perm_id);
		$this->form_data = new stdClass;		
		$this->form_data->perm_id = $perm_id;
		$this->form_data->name = $perm->name;
		$this->form_data->slug = $perm->slug;
		$this->form_data->description = $perm->description;

		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
				
		$data["subtitle"] = 'Update Permission';
		$data["action"] = site_url("access_control/permission/permission_modify");
		$data["link_back"] = anchor($page_index.'/?tab=permissions', "Back to Permissions");

		// load view
		$data["acl_content"] = "access_control_view/permission_modify";
		$this->load->view("access_control/template", $data);
	}

	public function permission_modify()
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "edit_perm"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		
		$page_index = $this->session->userdata('last_pagination');
						
		$perm_id =$this->input->post("perm_id");

		//set validation properties
		$this->_set_change_perm_rules();

		//Add Validation Rules here..
		if($this->form_validation->run() == FALSE)
		{

			// set common properties
			$this->session->set_flashdata('data', validation_errors());
			$data ["title"] = $this->super_title;
			$data ["version_official_name"] = $this->version_title;
				
			$data["subtitle"] = "Add Permission";
			$data["panel_title"] = "AdminUI Permissions";
			$data["link_back"] = anchor($page_index.'/?tab=permissions', "Back to Permissions");
			$data["add_perm"] = site_url("access_control/permission/permission_modify/?add_permission");
			$data["acl_content"] = "access_control_view/permission_modify";

			redirect("access_control/permission/perm_update/{$perm_id}");

		}
		else
		{
			$perm_pdo = array(
				"perm_id" => $this->input->post("perm_id"),
				"name" => $this->input->post("name"),
				"slug" => $this->input->post("slug"),
				"description" => $this->input->post("description")
			);
			
			$data ["title"] = $this->super_title;
			$data ["version_official_name"] = $this->version_title;
				
			$data["subtitle"] = "Modify Permission";
			$data["panel_title"] = "AdminUI Permission";

			$this->form_data->perm_id = $perm_id;
			$this->form_data->name =$this->input->post("name");
			$this->form_data->slug = $this->input->post("slug");
			$this->form_data->description = $this->input->post("description");

			//Update the permission
			$data["action"] = site_url("access_control/permission/perm_update/{$perm_id}");
			$data["link_back"] = anchor($page_index.'/?tab=permissions', "Back to Permissions");
			$data["acl_content"] = "access_control_view/permission_modify";
			$this->adminuiacl_model->edit_perm($perm_id,$perm_pdo);

			redirect("access_control/permission/perm_update/{$perm_id}/?UpdateSuccess=success");
		}
	}
	public function perm_delete($perm_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "delete_perm"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		// set validation properties
		//$this->_set_rules();
		$page_index = $this->session->userdata('last_pagination');

		// prefill form values
		$perm = $this->adminuiacl_model->get_perm($perm_id);
		$data["perm"] = $perm;
		// set common properties
		$data ["title"] = $this->super_title;
		$data ["version_official_name"] = $this->version_title;
				
		$data["subtitle"] = 'DELETE PERMISSION';
		$data["del_perm"] = site_url("access_control/permission/del_perm_process/{$perm_id}");
		$data["action"] = site_url("access_control/permission/permission_delete");
		$data["link_back"] = anchor($page_index.'/?tab=permissions', "Back to Permissions");

		// load view
		$data["acl_content"] = "access_control_view/permission_delete";
		$this->load->view("access_control/template", $data);
	}

	public function del_perm_process($perm_id)
	{
		// check roles and permissions
		if(!$this->adminuiacl_model->user_has_perm($this->session->userdata("user_id"), "delete_perm"))
		{
			show_error("You do not have access to this section ". anchor($this->agent->referrer(), "Return", 'title="Go back to previous page"'));
		}
		$this->adminuiacl_model->del_perm($perm_id);
		redirect("access_control/permission/permission_manager/?tab=permissions&del_success_message=success");
	}

	// validation rules
	protected function _set_perm_rules()
	{
		// set empty default form field values
		$this->form_data->name = "";
		$this->form_data->slug = "";
		$this->form_data->description = "";

		$this->form_validation->set_rules("name", "System Name", "trim|min_length[3]|max_length[50]|required|is_unique[perm.name]");
		$this->form_validation->set_rules("slug", "System ID", "trim|min_length[3]|max_length[50]|required|is_unique[perm.slug]");
		$this->form_validation->set_rules("description", "Description", "trim|min_length[3]|max_length[500]|required");

		$this->form_validation->set_message ( "is_unique", "%s has already been taken. Please try a different value." );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
	// validation rules
	protected function _set_change_perm_rules()
	{
		// set empty default form field values
		$this->form_data->name = "";
		$this->form_data->slug = "";
		$this->form_data->description = "";

		$perm_id = $this->input->post('perm_id');
		$perm_obj = $this->adminuiacl_model->get_perm($perm_id);

		$new_name = $this->input->post('name');
		$new_slug = $this->input->post('slug');

		//print_r($perm_obj);
		//print_r($new_name."  ".$new_slug); exit;

		if($perm_obj->name != $new_name)
		{
			$this->form_validation->set_rules("name", "System Name", "trim|min_length[3]|max_length[50]|required|is_unique[perm.name]");
		}

		if($perm_obj->slug != $new_slug)
		{
			$this->form_validation->set_rules("slug", "System ID", "trim|min_length[3]|max_length[50]|required|is_unique[perm.slug]");
		}

		$this->form_validation->set_rules("description", "Description", "trim|min_length[3]|max_length[500]|required");

		$this->form_validation->set_message ( "is_unique", "%s has already been taken. Please try a different value." );
		$this->form_validation->set_error_delimiters ( "<div class=\"alert alert-danger alert-dismissable\">
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>", "</div>" );
	}
}



