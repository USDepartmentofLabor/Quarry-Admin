<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * APIv2 AdminUI Pilot Database Controller
 *
 * @package	CI Controller
 * @category Database Controller
 * @author
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Login_model extends CI_Model {

	private $admin_user = "admin_user";
	private $admin_request = "admin_request";
	private $request_assistance = "request_assistance";
	private $group_manager = "role";
	private $apiv2db = "apiv2db";
	private $apiv2_logs = "logs";
	
	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database("adminDB", TRUE);
	}

	public function validate()
	{
		$this->db->where("username", $this->input->post("username"));
		$this->db->where("password", md5($this->input->post("password")));
		$this->db->where("status", "1");
		$query = $this->db->get($this->admin_user);

		if ($query->num_rows == 1 && $query->row("password_reset") != 1)
		{
			return AUTH_PASS;
		} 
		elseif ($query->row("password_reset") == 1)
		{
			return PASS_RESET_REQUIRED;
		}
	}

	// check registration table for duplicate username or/and email
	private function check_admin_user()
	{
		$this->db->where("email_address", $this->input->post("email_address"));
		$query = $this->db->get($this->admin_user);

		if ($query->num_rows == 1)
		{
			return DUPLICATE_ADMIN;
		}
	}

	public function admin_request()
	{
		// check valid admin user table private method
		$response = $this->check_admin_user();

		if ($response == DUPLICATE_ADMIN)
		{
			return DUPLICATE_ADMIN;
		}
		else
		{
			// Check pending table for duplicate users by email before inserting new request...
			$this->form_validation->is_unique($this->input->post("email_address"),'admin_user.email_address');
			
			$this->db->where("email_address", $this->input->post("email_address"));
			$query = $this->db->get($this->admin_request);
			
			//Create system username
			$username_format = $this->input->post("last_name").'-'.$this->input->post("first_name");
				
			if ($query->num_rows != 1)
			{
				// no duplicate record(s) found...				
				// Next check if system generated username exists. if so, append a unique identifier.
				
				$this->db->like("username", $username_format);
				$this->db->order_by('date_created','asc');
				$check = $this->db->get($this->admin_user)->row();
				
				//print_r($check);exit;
				
				if(isset($check->username))
				{	
					//echo " dup user ";
					//A duplicate username exists append the identifier
					
					//print_r(substr($check_username->username, -1));
					
					if(is_numeric(substr($check->username, -1)))
					{
						//echo $check_username->username;
						//Check if the last character is number.. if so increment
						//echo " HERE IN QUESTION "; exit;
														
						$username_format = $username_format.= '-'.$i++;
					}
					else
					{
						$username_format = $username_format.= '-1';
					}
				}
												
				$new_user = array(
						"first_name" => $this->input->post("first_name"),
						"last_name" => $this->input->post("last_name"),
						"email_address" => strtolower($this->input->post("email_address")),
						"username" => strtolower($username_format),
						"password" => md5($this->input->post("password")),
						"password_reset" => "0",
						"status" => "0",
						"date_requested" => date("Y-m-d H:i:s")
				);

				$insert = $this->db->insert($this->admin_request, $new_user);
				return $insert;
			}
			else
			{
				return DUPLICATE_REG;
			}
		}
	}

	public function create_admin()
	{
		// check for duplicate user before inserting new request...
		$this->db->where("username", $this->input->post("username"));
		$this->db->where("email_address", $this->input->post("email_address"));
		$query = $this->db->get($this->admin_user);

		if ($query->num_rows != 1)
		{
			// no duplicate record(s) found...
			$new_user = array(
				"first_name" => $this->input->post("first_name"),
				"last_name" => $this->input->post("last_name"),
				"email_address" => $this->input->post("email_address"),
				"username" => $this->input->post("username"),
				"password" => md5($this->input->post("password")),
				"password_reset" => "0",
				"status" => "0",
				"date_created" => date("Y-m-d H:i:s")
			);

			$insert = $this->db->insert($this->admin_user, $new_user);
			return $insert;
		}
		else
		{
			return DUPLICATE_ADMIN;
		}
	}
	
	// validate if user is admin
	public function is_user_admin($user_id)
	{
		$this->db->select('slug');
		$this->db->from('role');
		$this->db->join('user_role','role.role_id = user_role.role_id');
		$this->db->where('user_id',$user_id);
	    $role_param =  $this->db->get()->row();
	    
	    if($role_param->slug == 'super_admin')
	    {
	    	return true;
	    } 
	    return false;	    
	}
	
	public function list_all()
	{
		$this->db->order_by("user_id","asc");
		return $this->db->get($this->admin_user);
	}

	public function list_all_pendrequest()
	{
		$this->db->order_by("user_id","asc");
		return $this->db->get($this->admin_request);
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

	public function get_by_user($user)
	{
		$this->db->where("username", $user);
		return $this->db->get($this->admin_user);
	}

	public function save($acct)
	{
		$this->db->insert($this->admin_user, $acct);
		return $this->db->insert_id();
	}

	public function delete($user_id)
	{
		$this->db->where("user_id", $user_id);
		$this->db->delete($this->admin_user);
	}

	public function passreset($email)
	{
		$this->db->where("email_address", $email);
		return $this->db->get($this->admin_user);
	}

	public function update_password($user_id, $acct)
	{
		$this->db->where("user_id", $user_id);
		$this->db->update($this->admin_user, $acct);
	}

	public function update_password_prompt($user_id, $acct)
	{
		$this->db->where("user_id", $user_id);
		$this->db->update($this->admin_user, $acct);
	}

	function reg_error_message($data)
	{
		$insert = $this->db->insert($this->request_assistance, $data);
		return $insert;
	}
}