<?

$APP['classes'] = array(
	'path'	=> CLASS_PATH,
	'files' => array(
                'util/CONFIG'
		)
	);


foreach($APP['classes']['files'] as $_file){
	$_path  = $APP['classes']['path'] . $_file . ".php";
	require($_path);
	}