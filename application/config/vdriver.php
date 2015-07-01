<?php

/*
|--------------------------------------------------------------------------
| Virtual Configuration
|--------------------------------------------------------------------------
|
| This is the configuration file for the virtual datasource management.
| If you have different names for the tables required for the virtual manager to work
| please enter those below.
|
*/

$config['vdriver']['client']['hostname'] = RESTCLNTHOST;
$config['vdriver']['client']['username'] = RESTCLNTUSER;
$config['vdriver']['client']['password'] = RESTCLNTPASSWORD;
$config['vdriver']['client']['database'] = RESTCLNTDATABASE;
$config['vdriver']['client']['dbdriver'] = RESTCLNTDBDRIVER; // this determines the entire connection
$config['vdriver']['client']['dbprefix'] = RESTCLNTDBPREFIX;
$config['vdriver']['client']['cache_on'] = RESTCLNTCACHEON;
$config['vdriver']['client']['cachedir'] = RESTCLNTCACHEDIR;
$config['vdriver']['client']['pconnect'] = RESTCLNTPCONNECT;
$config['vdriver']['client']['db_debug'] = RESTCLNTDEBUG;
$config['vdriver']['client']['char_set'] = RESTCLNTCHARSET;
$config['vdriver']['client']['dbcollat'] = RESTCLNTDBCOLLAT;
$config['vdriver']['client']['autoinit'] = RESTCLNTAUTOINIT;
$config['vdriver']['client']['stricton'] = RESTCLNTSTRICTON;

/*
|--------------------------------------------------------------------------
| Remote REST DB server, get crendentials from constants
|--------------------------------------------------------------------------
|
*/

$config['vdriver']['server']['hostname'] = RESTHOST;
$config['vdriver']['server']['username'] = RESTUSER;
$config['vdriver']['server']['password'] = RESTPASSWORD;
$config['vdriver']['server']['database'] = RESTDATABASE;
$config['vdriver']['server']['dbdriver'] = RESTDBDRIVER;
$config['vdriver']['server']['dbprefix'] = RESTDBPREFIX;
$config['vdriver']['server']['pconnect'] = RESTPCONNECT;
$config['vdriver']['server']['db_debug'] = RESTDEBUG;
$config['vdriver']['server']['char_set'] = RESTCHARSET;
$config['vdriver']['server']['dbcollat'] = RESTDBCOLLAT;
$config['vdriver']['server']['autoinit'] = RESTAUTOINIT;
$config['vdriver']['server']['stricton'] = RESTSTRICTON;