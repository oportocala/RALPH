<?
function is_table($name){
	$name = res($name);
	$sql = "SHOW TABLES LIKE '$name'";
	return (num_rows(query($sql)) == 1);
	}