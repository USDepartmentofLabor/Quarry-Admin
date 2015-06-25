<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
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

// <!-- SB Admin CSS - Include with every page -->
$sbadmin = array(
		'href' => base_url().'assets/css/sb-admin.css',
		'rel' => 'stylesheet',
		'type' => 'text/css'
);

echo link_tag($style);
echo link_tag($font);
echo link_tag($sbadmin);

?>
    <title><?= $title; ?></title>

</head>

<body>