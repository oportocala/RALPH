<?
$include_path = "app";
$key = "app";
$RALPH[$key]['bootstrap_files'] = array(

        "configs",
        "environment",
        "layout"
		);

foreach($RALPH[$key]['bootstrap_files'] as $_file){
	$_path = $include_path. DIRECTORY_SEPARATOR . $_file . ".php";

    require($_path);
	}

unset($key, $include_path);