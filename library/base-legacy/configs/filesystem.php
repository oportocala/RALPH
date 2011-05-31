<?

/* FILE ROOT */
define("DS", DIRECTORY_SEPARATOR);
$tmp = explode(DS, dirname(__FILE__));
$tmp = array_slice($tmp, 0, count($tmp)-3);//3 represents the relative number of levels to root
$tmp = implode(DS, $tmp);

if(substr($tmp,-1,1) != DS){
	define("FILE_ROOT_NO_TRAILING_DS", $tmp);
	$tmp .= DS;
}else{
	define("FILE_ROOT_NO_TRAILING_DS", substr($tmp, 0, strlen($tmp)-2));
	}
define("FILE_ROOT", $tmp);
define("FILE_PATH", $tmp);


/* WWW ROOT */
define("RS", "/"); // ROUTE SEPARATOR
$www_path  = explode(RS, $_SERVER['REDIRECT_URL']);
$file_path = explode(DS, FILE_ROOT_NO_TRAILING_DS);
$tmp = array_intersect($www_path, $file_path);
$t = array();
foreach($tmp as $i=>$tmp_value){if($tmp_value){ $t [] = $tmp_value;}}
$tmp = $t;
$tmp = implode(RS, $tmp);
$tmp = $tmp?$tmp.RS:$tmp;

define("WWW_ROOT", RS.$tmp );

/* WWW ROUTES */
unset($tmp, $www_path, $file_path);