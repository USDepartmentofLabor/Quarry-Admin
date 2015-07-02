# Quarry AdminUI patch update for v1.0.1 - 2015.07.02

This is a security patch update for Quarry AdminUI. If you wish to use the Quarry API, you will need to download the latest version of [Quarry REST](https://github.com/USDepartmentofLabor/Quarry/) 

## Change log v1.0.1 - 2015.07.02

**Changes**
* Added better controls for removing users and permissions
* Profile links have been updated to reflect current operations
  
**Removed**
* Unused UI components have been removed from the administrative interface
  
**Security**
* Added additional checks for securing user requests

Quarry AdminUI is a convenient way to manage datasets served by the [Quarry REST Service] (https://github.com/USDepartmentofLabor/Quarry/blob/master/README.md). It’s a human-friendly web interface for managing what data you share, who can use it, and much more.

* With Quarry AdminUI you can:
 * Use any open web language to access data
 * Obtain public datasets as JSON or XML
 * Filter RESTful queries by key/pair parameters.
 * Run API CRUD operations which will be available for internal usage
 * Conduct transactions via mobile device.
 * Add, edit and delete users
 * Add, edit and delete Quarry API keys
 * Add, edit, delete  and test data sources for dataset retrieval

# Release Information
* **Requirements**
* PHP version 5.4 or newer is recommended (LAMP/LAPP Stack).
* MySQL 5.5 or PostgreSQL 9.3 is recommended
* CodeIgniter 2.2.0 or newer is recommended. This repo contains in-development code for future releases. To download the
latest stable release please visit the [CodeIgniter Downloads](http://www.codeigniter.com/download) page.

# Installation
* (CodeIgniter 2.2.0)
* Database Import (InnoDB Engine is recommended)
  * Rename **quarry_adminuidb_v1.0.0.txt** to **quarry_adminuidb_v1.0.0.sql**
  * Create a database: **quarry_adminuidb**
  * Import the SQL file to your database

* Edit the following configuration files:
*  **application/config/config.php**
    * Base Site URL (Example: https://quarry.domain.tld/ or /https://www.domain.tld/quarry/)
    * Encryption Key: Generate key at: [Codeigniter Encryption Key Generator] (http://jeffreybarke.net/tools/codeigniter-encryption-key-generator/)
  
  **application/config/constants.php**
    * **Quarry AdminUI email constants (modify)**
	* `define('FROM_EMAIL', 'alias@example.com');`
	* `define('FROM_NAME', 'Quarry Admin');`
	* `define('APPROVAL_ADMIN', 'super_alias@example.com');`
	* `define('CC_EMAIL', 'cc_alias@example.com');`
	
	**Remote REST DB server constants**
  **Configure Quarry REST Service remote host information**
	* `define('RESTHOST', ''); // Quarry REST Service DB Host`
	* `define('RESTUSER', ''); // DB Username `
	* `define('RESTPASSWORD', ''); // DB Password`
	* `define('RESTDATABASE', ''); // Quarry REST Database`
	* `define('RESTDBDRIVER', 'mysql');`
	* `define('RESTDBPREFIX', '');`
	* `define('RESTCACHEON', TRUE);`
	* `define('RESTCACHEDIR', 'application/cache');`
	* `define('RESTPCONNECT', FALSE);`
	* `define('RESTDEBUG', TRUE);`
	* `define('RESTCHARSET', 'utf8');`
	* `define('RESTDBCOLLAT', 'utf8_general_ci');`
	* `define('RESTAUTOINIT', TRUE);`
	* `define('RESTSTRICTON', FALSE);`
	 
  **application/config/database.php**
    * Enter database information
    * `$db['adminUI']['hostname'] = '';`
    * `$db['adminUI']['username'] = '';`
    * `$db['adminUI']['password'] = '';`
    * `$db['adminUI']['database'] = 'quarry_adminuidb**';`
	
  **application/config/email.php**
	* `$config['protocol'] = 'smtp';`
	* `$config['smtp_host'] = ''; // mail server host (E.g.: smtp.gmail.com)`
	* `$config['smtp_port'] = 465; // server port`
	* `$config['smtp_user'] = ''; // smtp username`
	* `$config['charset'] = 'iso-8859-1';`
	* `$config['wordwrap'] = TRUE;`
	* `$config['mailtype'] = 'html';`

# Acknowledgement
* DOL Office of Public Affairs would like to thank EllisLab, the CodeIgniter Team at [British Columbia Institute of Technology](http://www.bcit.ca/) and all the
contributors to the CodeIgniter project.
