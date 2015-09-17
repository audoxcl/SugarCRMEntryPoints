<?php

/*********************************************************************************
* This code was developed by:
* Audox Ingeniería Ltda.
* You can contact us at:
* Web: www.audox.cl
* Email: info@audox.cl
* Skype: audox.ingenieria
********************************************************************************/

$manifest = array(
	'acceptable_sugar_flavors' => array(
		'CE',
		'PRO',
		'ENT',
		'CORP',
		'ULT',
	),
	'acceptable_sugar_versions' => array(
		'6*',
		'7*',
	),
	'is_uninstallable' => true,
	'name' => 'Custom EntryPoints',
	'author' => 'Audox Ingenieria Ltda',
	'description' => 'Custom EntryPoints',
	'published_date' => '2015/09/12',
	'version' => 'v1.0',
	'type' => 'module',
);

$installdefs = array(
	'copy' => array(
		array(
			'from' => '<basepath>/WebToOpportunity.php',
			'to' => 'custom/WebToOpportunity.php',
		),
		array(
			'from' => '<basepath>/AccountOpportunitiesChart.php',
			'to' => 'custom/AccountOpportunitiesChart.php',
		),
	),
	'entrypoints' => array(
		array(
			'from' => '<basepath>/CustomEntryPoints.php',
			'to_module' => 'application',
			),
		),
);

?>
