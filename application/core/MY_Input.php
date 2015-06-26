<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

/**
 * CI Input extended library
 *
 * @package	Request Controller
 * @author	johnsonpatrickk (Patrick Johnson Jr.)
 * @link	http://developer.dol.gov
 * @version 1.0.0
 */

class MY_Input extends CI_Input 
{
	private $searchtbl = "search_query";
	
	// convert post array into search query string
	function save_query($query_array)
	{
		$CI =& get_instance();
		
		$CI->db->insert($this->searchtbl, array('query_string' => http_build_query($query_array)));
		
		return $CI->db->insert_id();
	}
	
	// load saved query
	function load_query($query_id)
	{
		$CI =& get_instance();
		
		$rows = $CI->db->get_where($this->searchtbl, array('id' => $query_id))->result();
		if (isset($rows[0]))
		{
			parse_str($rows[0]->query_string, $_GET);
		}
	}
}
