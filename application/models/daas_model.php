<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * APIv2 AdminUI Application Model
 *
 * @package	Application Model
 * @author johnsonpatrickk
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class Daas_model extends CI_Model {
	
	private $daas_manager_tbl = "daas_manager";
	private $rdbms_table = "rdbms";
	private $drv_mssql = "mssql";
	private $drv_mysql = "mysql";
	private $drv_oci8 = "oci8";
	private $drv_postgre = "postgre";
	private $connect_strng_tbl = "connection_strings";
	private $rest_logs = "logs";
	private $_vconn;

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
		
		// bootstrap the RESTful SQL server connection
		$this->restdb = $this->load->database($this->_vconn->server, TRUE);
	}
	
	/*
	|--------------------------------------------------------------------------
	| Dataset Management
	|--------------------------------------------------------------------------
	|
	*/

	public function list_all()
	{
		$this->db->order_by("daas_id","asc");
		return $this->db->get($this->_config->table["daas_manager"]);
	}

	// get the dataset service listing
	public function get_service_list($limit = 10, $offset = 0)
	{
		$this->restdb->join("api_rdbms AS tbl2", "connection_strings.daas_rdbms = tbl2.db_id");
		$this->restdb->order_by("connection_strings.daas_id","asc");
		
		return $this->restdb->get($this->_config->table["connection_strings"], $limit, $offset);
	}
	
	// count the total amount of datasets available 
	public function count_all()
	{
		return $this->restdb->count_all($this->_config->table["connection_strings"]);
	}
	
	// get dataset by id. the id provides more information to the viewer
	public function get_by_id($daas_id)
	{

		$this->restdb->join("api_rdbms AS tbl2", "connection_strings.daas_rdbms = tbl2.db_id");
		$this->restdb->where("connection_strings.daas_id", $daas_id);

		return $this->restdb->get($this->_config->table["connection_strings"]);
	}
	
/* 	public function get_rdbms_list()
	{
	    $this->db->order_by("db_type", "asc");
	    return $this->db->get($this->rdbms_table);
	} */
	
	public function get_rdbms_list() {
		//$perms = $this->db->get($this->_config->table["perm"]);
		//$this->db->query("select * from {$this->rdbms_table} order by db_type");
		$dbtype = $this->db->get($this->_config->table["rdbms"]);
		//print $this->db->last_query(); exit;
		return ($dbtype->num_rows() > 0) ? $dbtype->result() : FALSE;
	}
	
	/**
	 * add connection string to REST database
	 */
	public function add_daas_string($new_string)
	{
		// check for duplicate user before inserting new request...
		//var_dump($new_string); exit;
		
		$string = strtolower($this->input->post("dbname"));			
    	$slug = preg_replace('~[\\\\/:*?"<>| ]~', '_', $string);

		$this->restdb->where("daas_method", $new_string['daas_method']);
		$this->restdb->where("daas_table_alias", $new_string['daas_table_alias']);
		$query = $this->restdb->get($this->connect_strng_tbl);
		
		//print_r($query->num_rows); exit;
		
		if ($query->num_rows > 0)
		{
			return DUPLICATE;
		}
		else
		{			
			return $this->restdb->insert($this->connect_strng_tbl, $new_string);
		}
	}
	
	public function get_connect_json($connect_id)
	{
		$this->restdb->select("connection_strings.daas_id, connection_strings.daas_host, connection_strings.daas_user, connection_strings.daas_passwd, 
				`connection_strings.daas_dbname`, `connection_strings.daas_method`, `connection_strings.daas_table`, `connection_strings.daas_table_alias`, connection_strings.daas_port,
				connection_strings.daas_action_clmn, connection_strings.daas_sid, connection_strings.daas_sname, tbl2.db_type, tbl2.db_driver");
		$this->restdb->join("api_rdbms AS tbl2", "connection_strings.daas_rdbms = tbl2.db_id");
		$this->restdb->where("connection_strings.daas_id", $connect_id);

		return $this->restdb->get($this->_config->table["connection_strings"]);
	}
	
 	// get the list of all connection strings
	public function get_connection_strings()
	{
		$this->restdb->order_by("tbl2.db_type", "asc");
		$this->restdb->join("api_rdbms AS tbl2", "connection_strings.daas_rdbms = tbl2.db_id");
		//print $this->db->last_query(); exit;
	
		return $this->restdb->get($this->_config->table["connection_strings"]);
	}
	
	
	// Get the list of connection strings by driver
	public function get_connection_strings_by_rdbms($driver_id)
	{
		//SELECT * FROM `connection_strings` JOIN `api_rdbms` AS `tbl2` ON `connection_strings`.`daas_rdbms` = `tbl2`.`db_id` WHERE `connection_strings`.`daas_id` = '1'
		
		$this->restdb->select('*');
		$this->restdb->from('api_rdbms');		
		$this->restdb->join('connection_strings', "api_rdbms.db_id = connection_strings.daas_rdbms");
		$this->restdb->where('api_rdbms.db_id',$driver_id);

		return $this->restdb->get()->result();
	}		
	public function deleteDataset($daas_id)
	{		
		// process delete request sent from the user/admin  
		$delDatasetRest = $this->restdb->delete($this->_config->table['connection_strings'], array('daas_id' => $daas_id));
		
		return (array($delDatasetRest, $this->restdb->affected_rows() == 1));
	}
	
	public function update_string($slugid, $update_string)
	{
		// check for duplicate user before inserting new request...
		
		$string = strtolower($this->input->post("dbname"));
		$slug = preg_replace('~[\\\\/:*?"<>| ]~', '_', $string);
		
		// REST DB update
		$this->restdb->where("daas_id", $slugid);
		$updatedb = $this->restdb->update($this->_config->table['connection_strings'], $update_string);
		
		/*$this->db->where("daas_id", $slugid);*/
		/*$upDateadminUI = $this->db->update($this->_config->table['daas_manager'], $upDateString);*/
		/*return (array($upDateadminUI, $this->db->affected_rows() == 1, $upDaterestDB, $this->restdb->affected_rows() == 1));*/
				
		return (array($updatedb, $this->restdb->affected_rows() == 1));
	}
		
	// Get rdbms info by pk.. Had to add api_rdbms to the api config
	public function get_rdbms_by_id($id)
	{
		$this->restdb->where('db_id', $id);
		return $this->restdb->get($this->_config->table['api_rdbms'])->row();
	}

	//Get paged list for logs
	public function get_log_paged_list($limit = 20, $offset = 0, $sort_by, $sort_order)
	{
		// safe search criteria - protect (asc/desc)
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('id', 'uri', 'method', 'params', 'api_key', 'ip_address','time','rtime');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'id';
		
		$this->restdb->limit($limit, $offset);
		$this->restdb->order_by($sort_by, $sort_order);
		return $this->restdb->get($this->rest_logs);
	}	

	//Get all REST log records
	public function count_all_logs()
	{
		return $this->restdb->count_all($this->rest_logs);
	}	
	
	public function get_rest_log($log_id)
	{
		$this->restdb->where('id', $log_id);
		return $this->restdb->get($this->rest_logs)->row();			
	}	
	// oh, oh here comes the search method for REST transaction logs
	public function searchList($query_array, $limit, $offset, $sort_by, $sort_order)
	{
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('time','id', 'uri', 'method', 'params', 'api_key', 'ip_address','rtime');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'time';
	
		// results query
		$query = $this->restdb->select("time, id, uri, method, params, api_key, ip_address, rtime");
		$this->restdb->limit($limit, $offset);
		$this->restdb->order_by($sort_by, $sort_order);
	
		if (strlen($query_array['searchval']))
		{
			if ($query_array['searchcat'] == 'id')
			{
				$query->like('id', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'uri')
			{
				$query->like('uri', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'method')
			{
				$query->like('method', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'params')
			{
				$query->like('params', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'api_key')
			{
				$query->like('api_key', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'ip_address')
			{
				$query->like('ip_address', $query_array['searchval']);
			}						
		}
		if (strlen($query_array['searchcond']))
		{
			$operators = array('eq' => '=', 'gte' => '>=', 'lte' => '<=', 'lt'=>'<');
			$operator = $operators[$query_array['searchcond']];
				
			$query->where("{$query_array['searchcat']} {$operator}", $query_array['searchval']);
		}
	
		return $this->restdb->get($this->rest_logs);
	}
	
	// Search count for REST Transaction logs
	public function searchCount($query_array, $limit, $offset, $sort_by, $sort_order)
	{
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('id', 'uri', 'method', 'params', 'api_key', 'ip_address','time','rtime');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'time';
	
		// count query
		$query = $this->restdb->select('COUNT(*) as count', FALSE);
		$this->restdb->from($this->rest_logs);
	
		if (strlen($query_array['searchval']))
		{
			if ($query_array['searchcat'] == 'id')
			{
				$query->like('id', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'uri')
			{
				$query->like('uri', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'method')
			{
				$query->like('method', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'params')
			{
				$query->like('params', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'api_key')
			{
				$query->like('api_key', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'ip_address')
			{
				$query->like('ip_address', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'time')
			{
				$query->like('time', $query_array['searchval']);
			}
			elseif ($query_array['searchcat'] == 'rtime')
			{
				$query->like('rtime', $query_array['searchval']);
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