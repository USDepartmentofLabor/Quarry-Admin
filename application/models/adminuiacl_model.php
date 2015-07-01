<?php  if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * Quarry AdminUI Access Model
 *
 * @package	AdminAccess Model
 * @author	johnsonpatrickk (Patrick Johnson Jr.)
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Adminuiacl_model extends CI_model {

	/**
	 * acl config shortcut
	 *
	 * @var object
	 */
	private $_config;
	private $admin_user = "admin_user";
	private $admin_request = "admin_request";
	private $request_assistance = "request_assistance";
	private $role_manager = "role";
	private $permission_manager = "perm";

	/**
	 * constructor
	 *
	 */
	public function __construct()
	{
		parent::__construct();

		$this->_config = (object)$this->config->item("acl");
		$this->db = $this->load->database("adminDB", TRUE);
	}

	/*
	| -------------------------------------------------------------------
	|  user specific methods
	| -------------------------------------------------------------------
	*/

	/**
	 * get all users details
	 *
	 * @return	array	an array of CodeIgniter row objects for user
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function list_all()
	{
		$this->db->order_by("user_id","asc");
		return $this->db->get($this->admin_user);
	}

	public function get_paged_list($limit = 10, $offset = 0)
	{
		if ($offset < 0){ $offset = 0;}
		$this->db->order_by("last_name","asc");
		return $this->db->get($this->admin_user, $limit, $offset);
	}

	public function count_all()
	{
		return $this->db->count_all($this->admin_user);
	}

	public function get_by_id($user_id)
	{
		$this->db->where("user_id", $user_id);
		return $this->db->get($this->admin_user);
	}

	function get_group()
	{
		$admin_role[""] = "Select admin role";
		$this->db->order_by("name", "asc");
		$query = $this->db->get($this->role_manager);

		foreach ($query->result_array() as $row)
		{
			$admin_role[$row["role_id"]] = $row["name"];
		}
		return $admin_role;
	}

	function user_current_group()
	{
		//$admin_role[""] = "Select admin role";
		$this->db->order_by("name", "asc");
		return $this->db->get($this->role_manager);
	}

	/**
	 * get specific user details by constraint
	 *
	 * @param	string	$field	the field to constrain by
	 * @param	mixed	$value	the required value of field
	 * @return	array	an array of CodeIgniter row objects for user
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_user_by($field, $value)
	{
		$this->db->select($this->admin_user. ".*");
		$this->db->where($field, $value);
		return $this->db->get($this->admin_user)->row();
	}

	/**
	 * get user details
	 *
	 * @param	int		$user_id	the unique identifier for the user to get
	 * @return	object	a CodeIgniter row object for user
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter row object
	 * @author
	 */
	public function get_user($user_id)
	{
		$user = $this->get_user_by("user_id", $user_id);
		return ($user !== FALSE) ? $user[0] : FALSE;
	}

	function get_by_user($user)
	{
		$this->db->where("username", $user);
		return $this->db->get($this->admin_user);
	}

	/**
	 * add user to database
	 *
	 * @param	assoc_array	$data	associative array of data to add into `user` table
	 * @return	boolean		TRUE/FALSE - whether or not addition was successful
	 * @author
	 */
	function admin_add_user($new_admin)
	{
		// check valid admin user table private method
		$response = $this->check_admin_user();

		if ($response == DUPLICATE_ADMIN)
		{
			return DUPLICATE_ADMIN;
		}
		else
		{
			// check for duplicate user before inserting new request...
			$this->db->where("username", $this->input->post("username"));
			$this->db->where("email_address", $this->input->post("email_address"));
			$query = $this->db->get($this->admin_request);

			if ($query->num_rows != 1)
			{
				// no duplicate record(s) found...
				$insert = $this->db->insert($this->admin_user, $new_admin);

				return $insert;
			}
			else
			{
				return DUPLICATE_REG;
			}
		}
	}

	// check registration table for duplicate username or/and email
	private function check_admin_user()
	{
		$this->db->where("username", $this->input->post("username"));
		$this->db->where("email_address", $this->input->post("email_address"));
		$query = $this->db->get($this->admin_user);

		if ($query->num_rows == 1)
		{
			return DUPLICATE_ADMIN;
		}
	}

	/**
	 * delete user from database
	 *
	 * @param	int		$user_id	the unique identifier for the user to get
	 * @return	boolean	TRUE/FALSE - whether or not the deletion was successful
	 * @author
	 */
	public function del_user($user_id)
	{
		$this->db->delete($this->_config->table["admin_user"], array("user_id" => $user_id));
		return ($this->db->affected_rows() == 1);
	}

	/**
	 * update user details
	 *
	 * @param	int			$user_id	the unique identifier for the user to get
	 * @param	assoc_array	$data		the new data for the user
	 * @return	boolean		TRUE/FALSE - whether or not the changes were successful
	 * @author
	 */
	function account_update($user_id, $acct)
	{
		$this->db->where("user_id", $user_id);
		$this->db->update($this->admin_user, $acct);
	}

	/*
	| -------------------------------------------------------------------
	|  user role relations
	| -------------------------------------------------------------------
	*/

	/**
	 * get users roles
	 *
	 * @param	string	$user_id	the unique identifier for the user
	 * @return	array	array of CodeIgniter row objects containing the user roles
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_user_role($user_id)
	{
		$this->db->select($this->_config->table["role"] . ".*")
			->from($this->_config->table["user_role"])
			->where("user_id", $user_id)
			->join($this->_config->table["role"], $this->_config->table["role"] . ".role_id = " . $this->_config->table["user_role"] . ".role_id", "inner");

		$role = $this->db->get();
		return ($role->num_rows() > 0) ? $role->result() : FALSE;
	}

	/**
	 * assign user to role
	 *
	 * @param	int		$user_id	the unique identifier for the user to assign
	 * @param	int		$role_id	the unique identifier for the role to assign
	 * @return	boolean	TRUE/FALSE - whether or not the assignment was successful
	 * @author
	 */
	public function add_user_role($user_id, $role_id)
	{
		$this->db->insert($this->_config->table["user_role"], array(
			"user_id" => $user_id,
			"role_id" => $role_id
		));
		return ($this->db->affected_rows() == 1);
	}

	/**
	 * remove user from role
	 *
	 * @param	int		$user_id	the unique identifier for the user
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	boolean	TRUE/FALSE - whether or not the removal was successful
	 * @author
	 */
	public function del_user_role($user_id, $role_id)
	{
		$this->db->delete($this->_config->table["user_role"], array(
			"user_id" => $user_id,
			"role_id" => $role_id
		));
		return ($this->db->affected_rows() == 1);
	}

	public function edit_user_roles($user_id, $role_array)
	{
		// Update user role
		$this->db->delete($this->_config->table["user_role"], array("user_id" => $user_id));
		//LOCKED... ONLY ONE ROLE PER USER
		$role = $role_array;
		$this->db->insert($this->_config->table["user_role"], array("user_id" => $user_id,"role_id" => $role));
		return($this->db->affected_rows());
	}

	/**
	 * password changed by admin
	 *
	 * @param	int		$user_id	the unique identifier for the user
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	boolean	TRUE/FALSE - whether or not the removal was successful
	 * @author
	 */
	function update_password_prompt($user_id, $acct)
	{
		$this->db->where("user_id", $user_id);
		$this->db->update($this->admin_user, $acct);
	}

	/*
	| -------------------------------------------------------------------
	|  role specific methods
	| -------------------------------------------------------------------
	*/

	/**
	 * get all roles details
	 *
	 * @return	array	an array of CodeIgniter row objects for role alphabetically by name
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_all_roles() {
		//$roles = $this->db->get($this->_config->table["role"]);
		$roles = $this->db->query("select * from role order by name");
		
		return ($roles->num_rows() > 0) ? $roles->result() : FALSE;
	}

	public function count_all_roles()
	{
		return $this->db->count_all($this->role_manager);
	}

	public function get_role_paged_list($limit = 10, $offset = 0)
	{
		if ($offset < 0){ $offset = 0;}
		$this->db->order_by("slug","asc");
		return $this->db->get($this->role_manager, $limit, $offset);
	}

	/**
	 * get roles by constraint
	 *
	 * @param	string	$field	the field to constrain
	 * @param	mixed	$value	the required value of field
	 * @return	object	a CodeIgniter row object for role
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter row object
	 * @author
	 */
	public function get_role_by($field, $value)
	{
		$this->db->select("*");
		$this->db->where($field, $value);
		return $this->db->get($this->role_manager)->result();
	}

	/**
	 * get details of a role
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	object	a CodeIgniter row object for role
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter row object
	 * @author
	 *
	 * @todo	return permissions associated w/ role as well
	 */
	public function get_role($role_id)
	{
		$role = $this->get_role_by("role_id", $role_id);
		return ($role !== FALSE) ? $role[0] : FALSE;
	}

	/**
	 * add new role to database
	 *
	 * @param	string	$data		the new roles data
	 * @return	boolean	TRUE/FALSE - whether addition was successful
	 * @author
	 */
	public function add_role($data)
	{
		$this->db->insert($this->_config->table["role"], $data);
		return ($this->db->affected_rows() == 1);
	}

	/**
	 * remove role from database
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	boolean	TRUE/FALSE - whether addition was successful
	 * @author
	 */
	public function del_role($role_id)
	{
		$this->db->delete($this->_config->table["role"], array("role_id" => $role_id));
		return ($this->db->affected_rows() == 1);
	}

	/**
	 * update a roles data
	 *
	 * @param	int			$role_id	the unique identifier for the role
	 * @param	assoc_array	$data		the new roles data
	 * @return	boolean	TRUE/FALSE - whether update was successful
	 * @author
	 */
	public function edit_role($role_id, $data)
	{
		return $this->db->update($this->_config->table["role"], $data, array("role_id" => $role_id));
		// return ($this->db->affected_rows() == 1);
	}

	/*
	| -------------------------------------------------------------------
	|  role permission relations
	| -------------------------------------------------------------------
	*/

	/**
	 * get permission a role has
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	array	array of CodeIgniter row objects for role permissions
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_role_perms($role_id)
	{
		$this->db->select($this->_config->table["perm"] . ".*")
		->from($this->_config->table["role_perm"])
		->where("role_id", $role_id)
		->join($this->_config->table["perm"], $this->_config->table["perm"] . ".perm_id = " . $this->_config->table["role_perm"] . ".perm_id");

		$perms = $this->db->get();
		return ($perms->num_rows() > 0) ? $perms->result() : FALSE;
	}

	/**
	 * get permission ids assigned to a role
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @return	array	array of CodeIgniter row objects for role_perm
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_role_perms_keys($role_id)
	{
		$this->db->query("select * from role_perm");
		$this->db->where('role_id',$role_id);
		$perm_keys = $this->db->get($this->_config->table["role_perm"]);
		return ($perm_keys->num_rows() > 0) ? $perm_keys->result() : FALSE;
	}


	/**
	 * add permission to role
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @param	int		$perm_id	the unique identifier for the permission
	 * @return	boolean	TRUE/FALSE - whether or not addition was successful
	 * @author
	 */
	public function add_role_perm($role_id, $perm_id)
	{
		$this->db->insert($this->_config->table["role_perm"], array(
			"role_id" => $role_id,
			"perm_id" => $perm_id
		));
		return ($this->db->affected_rows() == 1);
	}

	/**
	 * remove permission from role
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @param	int		$perm_id	the unique identifier for the permission
	 * @return	boolean	TRUE/FALSE - whether or not removal was successful
	 * @author
	 */
	public function del_role_perm($role_id, $perm_id)
	{
		$this->db->delete($this->_config->table["role_perm"], array(
			"role_id" => $role_id,
			"perm_id" => $perm_id
		));
		return ($this->db->affected_rows() == 1);
	}

	/**
	 * Edit role permissions
	 *
	 * Essensially this method assigns permissions to a role. This method will return FALSE
	 * if **ANY** of the assignments fail.
	 *
	 * @param	int		$role_id	the unique identifier for the role
	 * @param	array	$perm_array	an array of identifiers for the permissions to assign
	 * @return	boolean	TRUE/FALSE - whether or not **ALL** assignments were successful
	 * @author
	 *
	 * @todo	rework to check for changes rather than bulk remove then add permissions each time
	 * @todo	add in some better error reporting to detail which assignemnts fail and why
	 */
	public function edit_role_perms($role_id, $perm_array)
	{
		
		// bulk delete permissions for the role
		$this->db->delete($this->_config->table["role_perm"], array("role_id" => $role_id));

		// assume permissions all fail to set
		$rtn = TRUE;
		// add permissions provided in array
		foreach($perm_array as $item => $perm_id)
		{
			if(!$this->add_role_perm($role_id, $perm_id))
			{
				$rtn = FALSE;
			}
		}
		// return TRUE if all permissions set
		return $rtn;
	}


	/*
	| -------------------------------------------------------------------
	|  permission specific methods
	| -------------------------------------------------------------------
	*/

	/**
	 * get all permissions
	 *
	 * @return	array	an array of CodeIgniter row objects for permission
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_all_perms()
	{
		//$perms = $this->db->get($this->_config->table["perm"]);
		$perms = $this->db->query("select * from perm order by name");
		return ($perms->num_rows() > 0) ? $perms->result() : FALSE;
	}

	public function count_all_perms()
	{
		return $this->db->count_all($this->permission_manager);
	}
	/**
	 * get permissions with pagination
	 *
	 * @return	array	an array of CodeIgniter row objects for permission
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_perm_paged_list($limit = 10, $offset = 0)
	{
		if ($offset < 0){ $offset = 0;}
		$this->db->order_by("slug","asc");
		return  $this->db->get($this->permission_manager, $limit, $offset);
	}

	/**
	 * get permission by constraint
	 *
	 * @param	string	$field	the field to constrain
	 * @param	mixed	$value	the value field should be
	 * @return	array	an array of CodeIgniter row objects for permissions
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_perm_by($field, $value)
	{
		//TODO THIS NEEDS MINOR FIXING
		$this->db->where($field, $value);
		return $this->get_all_perms();
	}
	/**
	 * get permission by constraint
	 *
	 * @param	string	$user_id of the account
	 * @return	array	an array of CodeIgniter row strings for permissions
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 */
	public function get_perm_by_userid($id)
	{
		//NOT IMPLEMENTED, REQUIRES new db table
		//return $this->db->where("user_id", $id);
	}
	/**
	 * get a specific permissions
	 *
	 * @param	int	$perm_id	the unique identifier for the permission (id not value)
	 * @return	object	a CodeIgniter row object for permission
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter row object
	 * @author
	 */
	public function get_perm($perm_id)
	{
		$this->db->select();
		$this->db->where('perm_id',$perm_id);
		$perm = $this->db->get('perm')->result();
		return ($perm !== FALSE) ? $perm[0] : FALSE;
	}

	/**
	 * add a permission
	 *
	 * @param	assoc_array	$data	the new permissions data
	 * @return	boolean		TRUE/FALSE - whether addition was successful
	 * @author
	 */
	public function add_perm($data)
	{
		$this->db->insert($this->_config->table["perm"], $data);
		return ($this->db->affected_rows() == 1);
	}

	/**
	 * delete a permission
	 *
	 * @param	int		$perm_id	the unique identifier for the permission (id not value)
	 * @return	boolean	TRUE/FALSE - whether addition was successful
	 * @author
	 */
	public function del_perm($perm_id)
	{
		$this->db->delete($this->_config->table["perm"], array("perm_id" => $perm_id));
		return ($this->db->affected_rows() == 1);
	}

	/**
	 * update a permission
	 *
	 * @param	int			$perm_id	the unique identifier for the permission (id not value)
	 * @param	assoc_array	$data		the new data for permission
	 * @return	boolean		TRUE/FALSE - whether or not update successful
	 * @author
	 */
	public function edit_perm($perm_id, $data)
	{
		return $this->db->update($this->_config->table["perm"], $data, array("perm_id" => $perm_id));
		// return ($this->db->affected_rows() == 1);
	}

	/*
	| -------------------------------------------------------------------
	|  user permission relation
	| -------------------------------------------------------------------
	*/

	/**
	 * get a users permissions based off those in users roles
	 *
	 * @param	int		$user_id	the unique identifier for the user
	 * @return	array	an array of CodeIgniter row objects for permissions
	 * @see		http://ellislab.com/codeigniter/user-guide/database/results.html Documentation for CodeIgniter result object
	 * @author
	 *
	 * @todo	refactor code to use complex sql **instead** of rest of model, and multiple sql calls.
	 */
	public function get_user_perms($user_id)
	{
		// hold on tight... this is a complicated one... and will be
		// rolled into a single sql query if possible at a later date w/ diff logic. (might be possible)
		$rtn = array();

		// get users roles
		$role_list = $this->get_user_role($user_id);

		// check role(s) set
		// for each role get its perms and add them to return array
		if(is_array($role_list)) foreach($role_list as $role)
		{
			// get role perms
			$perm_list = $this->get_role_perms($role->role_id);

			// check perms assigned to role
			if(is_array($perm_list)) foreach($perm_list as $perm)
			{
				$rtn[] = $perm;
			}
		}

		// return permission value total and return
		return $rtn;
	}

	/*
	| -------------------------------------------------------------------
	|  helper/utility methods for acl usage
	| -------------------------------------------------------------------
	*/

	/**
	 * user permission check
	 *
	 * Checks a user has the required permission.
	 *
	 * @param	string	$user_id	the user to check permission on
	 * @param	string	$slug		the permission required
	 * @return	boolean	TRUE/FALSE - whether or not user has permission
	 * @author
	 *
	 * @todo	add ability to accept arrays of permission slugs
	 */
	public function user_has_perm($user_id, $slug)
	{
		$user_perms = $this->get_user_perms($user_id);

		// check the user has some permissions
		// loop through users permissions and check for the slug
		if(is_array($user_perms)) foreach($user_perms as $perm)
		{
			// if slug is found then return TRUE
			if($perm->slug == $slug)
			{
				return TRUE;
			}
		}

		// if we get here the user has no permissions
		return FALSE;
	}

	/**
	 * user role check
	 *
	 * @param	int		$user_id	the unique identifier for the uer
	 * @param	string	$slug		the role required
	 * @return	boolean	TRUE/FALSE - whether or not the user has role
	 * @author
	 *
	 * @todo	add ability to accept arrays of role slugs
	 */
	public function user_has_role($user_id, $slug)
	{
		$user_roles = $this->get_user_roles($user_id);

		if(is_array($user_roles)) foreach($role_list as $role)
		{
			if($role->slug == $slug)
			{
				return TRUE;
			}
		}
		else
		{
			if($user_roles->slug == $slug)
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * pending admin request model methods
	 *
	 * @param	int		$user_id	the unique identifier for the uer
	 * @param	string	$slug		the role required
	 * @return	boolean	TRUE/FALSE - whether or not the user has role
	 * @author
	 *
	 * @todo	add ability to accept arrays of role slugs
	 */
	public function get_paged_list_pendrequest($limit = 10, $offset = 0)
	{
		if ($offset < 0){ $offset = 0;};
		$this->db->order_by("last_name","asc");
		return $this->db->get($this->admin_request, $limit, $offset);
	}

	public function get_by_id_pendrequest($user_id)
	{
		$this->db->where("user_id", $user_id);
		return $this->db->get($this->admin_request);
	}

	public function delete_approved_request($user_id)
	{
		// after request has been approved, delete from pending table
		$this->db->where("user_id", $user_id);
		return $this->db->delete($this->admin_request);
	}

	public function approve_request($user_id, $new_acct, $role_id = NULL)
	{
		// check if this request has been previously approved before inserting
		$this->db->where("username", $this->input->post("username"));
		$this->db->where("email_address", $this->input->post("email_address"));
		$query = $this->db->get($this->admin_user);

		if ($query->num_rows != 1)
		{
			//Add new admin and assign role
			$insert = $this->db->insert($this->admin_user, $new_acct);
			$user_obj = $this->get_by_user($new_acct['username']);
			$user = $user_obj->row();
			if(!empty($role_id))
			{
				$this->add_user_role($user->user_id,$role_id);
			}
			return $insert;
		}
		else
		{
			return DUPLICATE_ADMIN;
		}
	}

	public 	function count_all_pendrequest()
	{
		return $this->db->count_all($this->admin_request);
	}

	public function get_logs()
	{
		$this->db->select("uri, method, ip_address");
		$this->db->from("logs");
		//$this->db->limit(10);

		$query = $this->db->get();

		if ($query->num_rows() > 0)
		{
			foreach ($query->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
	}	
}

/* End of file acl_model.php */
/* Location: ./application/models/acl_model.php */