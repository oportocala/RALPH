<?
if(!defined('BOOTSTRAP_LOADED')){
	$ds  = DIRECTORY_SEPARATOR;
	$tmp = explode($ds, dirname(__FILE__));
	$tmp = array_slice($tmp, 0, count($tmp)-3);
	$tmp = implode($ds, $tmp);
    $tmp = $tmp.$ds."library".$ds."bootstrap.php";
    include($tmp);
	}