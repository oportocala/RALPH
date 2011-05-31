<?

$APP['db'] = array(
	'path'	=> "database",
	'files' => array(
		"doctrine2",
		"simple"
		),
	'config' => $DOMAIN_CONFIG['db']
	);

foreach($APP['db']['files'] as $_file){
	$_path  = $APP['db']['path'] .DS. $_file . ".php";
	require($_path);
	}