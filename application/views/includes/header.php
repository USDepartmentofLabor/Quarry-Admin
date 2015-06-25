<?php echo doctype('html'); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $title; ?></title>
<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
//<!-- Core CSS - Include with every page -->
$style = array(
	'href' => base_url().'assets/css/bootstrap.min.css',
    'rel' => 'stylesheet',
    'type' => 'text/css'
);

$font = array(
		'href' => base_url().'assets/font-awesome/css/font-awesome.css',
		'rel' => 'stylesheet',
		'type' => 'text/css'
);

//<!-- Page-Level Plugin CSS - Dashboard -->
$morrischart = array(
		'href' => base_url().'assets/css/plugins/morris/morris-0.4.3.min.css',
		'rel' => 'stylesheet',
		'type' => 'text/css'
);

$timeline = array(
		'href' => base_url().'assets/css/plugins/timeline/timeline.css',
		'rel' => 'stylesheet',
		'type' => 'text/css'
);

// <!-- Page-Level Plugin CSS - Tables -->
$datatable = array(
		'href' => base_url().'assets/css/plugins/dataTables/dataTables.bootstrap.css',
		'rel' => 'stylesheet',
		'type' => 'text/css'
);

// <!-- SB Admin CSS - Include with every page -->
$sbadmin = array(
		'href' => base_url().'assets/css/sb-admin.css',
		'rel' => 'stylesheet',
		'type' => 'text/css'
);

echo link_tag($style);
echo link_tag($font);
echo link_tag($timeline);
echo link_tag($datatable);
echo link_tag($sbadmin);

?>
</head>
<body>

