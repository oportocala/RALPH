<?

$APP['libs'] = array(
	'path'	=> LIB_PATH,
	'files' => array(
		"internal".DS."app".DS."array",
		"internal".DS."app".DS."db",
		"internal".DS."app".DS."debug",
		"internal".DS."app".DS."files",
		"internal".DS."app".DS."url",
		"internal".DS."app".DS."layout",
		"internal".DS."app".DS."unsorted",
		)
	);
	


foreach($APP['libs']['files'] as $_file){
	$_path  = $APP['libs']['path'] . $_file . ".php";
	require($_path);
	}