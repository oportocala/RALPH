<?

$RALPH['config'] = array(
	'path'	=> "configs/",
	'files' => array(
		"structure",
        "constants"
		)
	);

foreach($RALPH['config']['files'] as $_file){
	$_path = $RALPH['config']['path'] . $_file . ".php";

    require($_path);
	}