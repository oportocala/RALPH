<?
if(!defined('ROUTER_LAYOUT')){
	$script = $_SERVER['SCRIPT_FILENAME'];
	
	$layout_name = array_diff_assoc(explode(RS, $script), explode(DS, PAGES_PATH));

	list($layout_name) = array_values($layout_name);
	
	define_layout_constants($layout_name);
	}