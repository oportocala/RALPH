<?

$RALPH['init'] = array(
	'path'	=> "init",
	'files' => array(
		"roots",
        "paths",
        "environment",
        "url"
		)
	);

foreach($RALPH['init']['files'] as $_file){
	$_path = $RALPH['init']['path']. DIRECTORY_SEPARATOR . $_file . ".php";

    require($_path);
	}