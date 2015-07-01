<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Quarry AdminUI Token Model
 *
 * @package	Token Model
 * @author johnsonpatrickk (Patrick Johnson Jr.)
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Token_model extends CI_Model
{
	
	private $daas_manager_tbl = "daas_manager";
	private $rdbms_table = "rdbms";
	private $drv_mssql = "mssql";
	private $drv_mysql = "mysql";
	private $drv_oci8 = "oci8";
	private $drv_postgre = "postgre";
	private $connect_strng_tbl = "connection_strings";
	private $token_tbl = "keys";
	private $_vconn;
	private $product = "APIv2";
	private $version = '';
	private $version_title = '';	

	function __construct()
	{
		parent::__construct();
		$this->db = $this->load->database("adminDB", TRUE);
		$this->_config = (object)$this->config->item("api");
		$this->_vconn = (object)$this->config->item("vdriver");
		
		// bootstrap the adminui virtual client connection
		$this->_vconn->client["dbprefix"];
		$this->_vconn->client["pconnect"];
		$this->_vconn->client["db_debug"];
		$this->_vconn->client["cache_on"];
		$this->_vconn->client["cachedir"];
		$this->_vconn->client["char_set"];
		$this->_vconn->client["dbcollat"];
		$this->_vconn->client["autoinit"];
		$this->_vconn->client["stricton"];
		
		// bootstrap the rest server connection
		$this->_vconn->server['hostname'];
		$this->_vconn->server["username"];
		$this->_vconn->server["password"];
		$this->_vconn->server["database"];
		$this->_vconn->server["dbdriver"];;
		$this->_vconn->server["dbprefix"];
		$this->_vconn->server["pconnect"];
		$this->_vconn->server["db_debug"];
		$this->_vconn->server["char_set"];
		$this->_vconn->server["dbcollat"];
		$this->_vconn->server["autoinit"];
		$this->_vconn->server["stricton"];
		
		// bootstrap the rest server connection
		$this->restdb = $this->load->database($this->_vconn->server, TRUE);
	}
	
	public function list_all()
	{
		//print "here"; exit;
		$this->restdb->order_by("id","asc");
		return $this->restdb->get($this->token_tbl);
	}
	
	public function get_paged_list($limit = 20, $offset = 0, $sort_by, $sort_order)
	{
		// safe search criteria - protect (asc/desc)
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('id', 'key', 'ip_addresses', 'status', 'email_addr', 'name');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'name';
		
		$this->restdb->limit($limit, $offset);
		$this->restdb->order_by($sort_by, $sort_order);
		return $this->restdb->get($this->token_tbl);
	}
	
	public function count_all()
	{
		return $this->restdb->count_all($this->token_tbl);
	}
		
	// update token status : block | unblock
	public function updateKeyStatus($key_id, $keyStatus)
	{
		// update the record
		//print $this->restdb->last_query(); exit;
		$keyStatusUpdate = array('status' => $keyStatus);
		$this->restdb->where('id', $key_id);
		$this->restdb->update($this->token_tbl, $keyStatusUpdate);
	}
	
	// get token by id
	public function getTokenById($key_id)
	{
		$this->restdb->where("id", $key_id);
		return $this->restdb->get($this->token_tbl);
	}
	
	// register new token
	public function addKey($keyadd)
	{
		// no duplicate record(s) found...
		$insert = $this->restdb->insert($this->token_tbl, $keyadd);
	}

	// update token 
	public function updateKey($key_id, $params)
	{
		// update the record
		//print $this->restdb->last_query(); exit;
		$this->restdb->where('id', $key_id);
		$this->restdb->update($this->token_tbl, $params);
	}
	
	public function deleteKey($tokenData)
	{
		// prepare request and delete
		$this->restdb->delete($this->token_tbl, $tokenData);		
	}
	
	public function dupUserCheck($email)
	{
		$this->restdb->where("email_addr", $email);
		$query = $this->restdb->get($this->token_tbl);

		if ($query->num_rows > 0)
		{
			return false;
		}else{
			return true;
		}
	}
	
	// oh, oh here comes the search method
	public function searchList($query_array, $limit, $offset, $sort_by, $sort_order)
	{
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('id', 'key', 'ip_addresses', 'status', 'name', 'email_addr');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'key';
		
		// results query
		$query = $this->restdb->select("id, key, ip_addresses, status, name, email_addr, date_created");
		$this->restdb->limit($limit, $offset);
		$this->restdb->order_by($sort_by, $sort_order);
		
		if (strlen($query_array['searchval']))
		{
			if ($query_array['searchcat'] == 'key')
			{
				$query->like('key', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'ip_addresses')
			{
				$query->like('ip_addresses', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'status')
			{
				// translate active and inactive string for the status feild search
				if (strtolower($query_array['searchval']) == "active")
				{
					$status = 1;
				}
				elseif(strtolower($query_array['searchval']) == "inactive")
				{
					$status = 0;
				}
				$query->like('status', $status);
			}
			elseif ($query_array['searchcat'] == 'name')
			{
				$query->like('name', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'email_addr')
			{
				$query->like('email_addr', $query_array['searchval']);
			}
		}
		if (strlen($query_array['searchcond']))
		{
			$operators = array('eq' => '=', 'gte' => '>=', 'lte' => '<=', 'lt'=>'<');
			$operator = $operators[$query_array['searchcond']];
			
			$query->where("{$query_array['searchcat']} {$operator}", $query_array['searchval']);
		}

		return $this->restdb->get($this->token_tbl);
	}
	
	public function searchCount($query_array, $limit, $offset, $sort_by, $sort_order)
	{
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('id', 'key', 'ip_addresses', 'status', 'name', 'email_addr');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'key';
		
		// count query
		$query = $this->restdb->select('COUNT(*) as count', FALSE);
		$this->restdb->from($this->token_tbl);
		
		if (strlen($query_array['searchval']))
		{
			if ($query_array['searchcat'] == 'key')
			{
				$query->like('key', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'ip_addresses')
			{
				$query->like('ip_addresses', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'status')
			{
				// translate active and inactive string for the status feild search
				if (strtolower($query_array['searchval']) == "active")
				{
					$status = 1;
				}
				elseif(strtolower($query_array['searchval']) == "inactive")
				{
					$status = 0;
				}
				$query->like('status', $status);
			}
			elseif ($query_array['searchcat'] == 'name')
			{
				$query->like('name', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'email_addr')
			{
				$query->like('email_addr', $query_array['searchval']);
			}
		}
		if (strlen($query_array['searchcond']))
		{
			$operators = array('eq' => '=', 'gte' => '>=', 'lte' => '<=', 'lt'=>'<');
			$operator = $operators[$query_array['searchcond']];
		
			$query->where("{$query_array['searchcat']} {$operator}", $query_array['searchval']);
		}
		
		$tmp = $query->get()->result();
		
		$ret['num_rows'] = $tmp[0]->count;
		
		return $ret;
	}
}