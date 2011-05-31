<?
if(!defined('BOOTSTRAP_LOADED')){
	/* START TIMER */
    $_bootstrap_load_start_time = microtime(true);

	$RALPH  = array();
	session_start();

    $_bootstrap_dir = "base";
	$_bootstrap_load_files  = array('configs', 'init', 'libs', 'app', 'database');
    
	foreach($_bootstrap_load_files as $_bootstrap_file){
		include($_bootstrap_dir.DIRECTORY_SEPARATOR.$_bootstrap_file.".php");
		}
	
	$_bootstrap_load_end_time = microtime(true);
	$_bootstrap_time = $_bootstrap_load_end_time - $_bootstrap_load_start_time;

	$RALPH['bootstrap'] = array(
        'dir'   => $_bootstrap_dir,
		'files' => $_bootstrap_load_files,
		'timestamp'	=> array(
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

    define('BOOTSTRAP_LOADED', true);
	}