<?

$APP['config'] = array(
	'path'	=> "configs/",
	// ORDER IS IMPORTANT
	'files' => array(
		"filesystem",
		"constants",
		"domain"
		)
	);
	


foreach($APP['config']['files'] as $_file){
	$_path  = $APP['config']['path'] . $_file . ".php";
	require($_path);
	}