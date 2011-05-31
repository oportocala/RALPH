<?
if(!defined('BOOTSTRAP_LOADED')){
	$_bootstrap_load_start_time = microtime(true);
	$APP  = array();
	session_start();
	
	$_bootstrap_load_files = array('configs', 'database', 'libs', 'classes', "url", "layout");
	foreach($_bootstrap_load_files as $_bootstrap_file){
		include($_bootstrap_file.".php");
		}
	
	$_bootstrap_load_end_time = microtime(true);
	$_bootstrap_time = $_bootstrap_load_end_time - $_bootstrap_load_start_time;

	$APP['bootstrap'] = array(
		'files' => $_bootstrap_load_files,
		'timestap'	=> array(
			'start' => $_bootstrap_load_start_time,
			'end'	=> $_bootstrap_load_end_time,
			'diff'	=> $_bootstrap_time
			)
		);
	
	unset(
		$_bootstrap_file, 
		$_bootstrap_time, 
		$_bootstrap_load_start_time,
		$_bootstrap_end_time
		);

        require_once EXTENRAL_LIB_PATH.'Zend/Loader/Autoloader.php';
        Zend_Loader_Autoloader::getInstance();

        define('BOOTSTRAP_LOADED', true);
	}