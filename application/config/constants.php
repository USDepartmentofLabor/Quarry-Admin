<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Admin UI constants...
|--------------------------------------------------------------------------
|
| Default configurations for mail processing...
|
*/

define('FROM_EMAIL', 'alias@example.com');
define('FROM_NAME', 'Quarry Admin');
define('APPROVAL_ADMIN', 'super_alias@example.com');
define('CC_EMAIL', 'cc_alias@example.com');

/*
 |--------------------------------------------------------------------------
| Admin UI constants...
|--------------------------------------------------------------------------
|
| Default configurations for account management...
|
*/
define('PASS_RESET_REQUIRED', 1);
define('PASS_RESET_NOT_REQUIRED', 0);
define('AUTH_PASS', 2);
define('DUPLICATE_ADMIN', 'admin_in_use');
define('DUPLICATE_REG', 'reg_user_exist');
define('NO_DUPLICATE_ADMIN', 1);
define('NO_DUPLICATE', 1);
define('DUPLICATE', 1);
define('REST_SERVER', ''); // enter quarry rest server base_url with no trailing slashes at the end
define('REST_DEV_KEY', ''); // enter quarry api-key. to generate a new key, go to https://base_url/key_control/key/token_manager/?tab=register_key 

// token status
define('KEYSTATUS_BLOCK', 0);
define('KEYSTATUS_ACTIVE', 1);

/*
|--------------------------------------------------------------------------
| Remote REST DB server constants
|--------------------------------------------------------------------------
|
*/

define('RESTHOST', ''); // Quarry REST Service DB Host
define('RESTUSER', ''); // DB Username 
define('RESTPASSWORD', ''); // DB Password
define('RESTDATABASE', ''); // Quarry REST Database 
define('RESTDBDRIVER', 'mysql');
define('RESTDBPREFIX', '');
define('RESTCACHEON', TRUE);
define('RESTCACHEDIR', 'application/cache');
define('RESTPCONNECT', FALSE);
define('RESTDEBUG', TRUE);
define('RESTCHARSET', 'utf8');
define('RESTDBCOLLAT', 'utf8_general_ci');
define('RESTAUTOINIT', TRUE);
define('RESTSTRICTON', FALSE);

/*
|--------------------------------------------------------------------------
| Remote REST DB client constants
|--------------------------------------------------------------------------
|
*/

define('RESTCLNTHOST', '');
define('RESTCLNTUSER', '');
define('RESTCLNTPASSWORD', '');
define('RESTCLNTDATABASE', '');
define('RESTCLNTDBDRIVER', '');
define('RESTCLNTDBPREFIX', '');
define('RESTCLNTCACHEON', TRUE);
define('RESTCLNTCACHEDIR', 'application/cache');
define('RESTCLNTPCONNECT', TRUE);
define('RESTCLNTDEBUG', TRUE);
define('RESTCLNTCHARSET', 'utf8');
define('RESTCLNTDBCOLLAT', 'utf8_general_ci');
define('RESTCLNTAUTOINIT', TRUE);
define('RESTCLNTSTRICTON', FALSE);

/*
|--------------------------------------------------------------------------
| Dataset obfuscation percentage
|--------------------------------------------------------------------------
|
*/

define('OBFUSCATE_PERCENTAGE', '25');

/*
|--------------------------------------------------------------------------
| DB providers default ports 
|--------------------------------------------------------------------------
|
*/

define('MYSQL_DEFAULT_PORT', '3306');
define('MSSQL_DEFAULT_PORT', '1433');
define('ORACLE_DEFAULT_PORT', '1521');
define('POSTGRESSQL_DEFAULT_PORT', '5432');
define('DB2_DEFAULT_PORT', '50000');

/* End of file constants.php */
/* Location: ./application/config/constants.php */
