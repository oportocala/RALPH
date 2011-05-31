<?

define("FULL_DOMAIN", $_SERVER['HTTP_HOST']);
define("DOMAIN", str_replace(array("www."),"", FULL_DOMAIN));

define("ENV_TYPE_LIVE", "LIVE");
define("ENV_TYPE_DEV", "DEV");

$evn_type = (DOMAIN == APP_LIVE_DOMAIN)?ENV_TYPE_LIVE:ENV_TYPE_DEV;

define("ENV_TYPE", $evn_type);
if(ENV_TYPE == ENV_TYPE_LIVE){
	include('config.live.php');
}else{	
	include('config.dev.php');
	}