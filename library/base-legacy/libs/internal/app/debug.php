<?
function print_var($ar, $output = false){
	if($ar === false){
		$ar = '<span style="color:red">false</span>';
		}
	if($ar === true){
		$ar = '<span style="color:green">true</span>';
		}
	$string  = "<pre>".print_r($ar, true)."</pre>";
	
	$string = str_replace('Array','<span style="color:blue">Array</span>',$string);
	$string = str_replace('=>','<span style="color:#556F55">=></span>',$string);
	$string = preg_replace("/\[(\w*)\]/i", '[<span style="color:red;">$1</span>]', $string);
	
	if(!$output)
		echo $string;
	else
		return $string;
	}

/* alias */
function pr($ar,$o= false){
	print_var($ar, $o);
	}