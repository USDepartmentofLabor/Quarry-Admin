<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

// Quarry AdminUI adminDB
$active_group = 'adminDB';
$active_record = TRUE;

$db['adminDB']['hostname'] = ''; // Quarry AdminUI hostname
$db['adminDB']['username'] = ''; // Quarry AdminUI username
$db['adminDB']['password'] = ''; // Quarry AdminUI password
$db['adminDB']['database'] = 'quarry_adminuidb'; // Quarry AdminUI database
$db['adminDB']['dbdriver'] = 'mysql';
$db['adminDB']['dbprefix'] = '';
$db['adminDB']['pconnect'] = TRUE;
$db['adminDB']['db_debug'] = FALSE;
$db['adminDB']['cache_on'] = FALSE;
$db['adminDB']['cachedir'] = 'application/cache';
$db['adminDB']['char_set'] = 'utf8';
$db['adminDB']['dbcollat'] = 'utf8_general_ci';
$db['adminDB']['swap_pre'] = '';
$db['adminDB']['autoinit'] = TRUE;
$db['adminDB']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */
