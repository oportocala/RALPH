<?

$RALPH['libs'] = array(
	'path'	=> "libs",
	'files' => array(
        "constants",
		"scripts/array",
        "scripts/layout"
		)
	);

foreach($RALPH['libs']['files'] as $_file){
	$_path = $RALPH['libs']['path'] . DIRECTORY_SEPARATOR . $_file . ".php";

    require($_path);
	}