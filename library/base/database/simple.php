<?

function save_row($table, $data, $id){
	
	if($id)
		$id = update_row($table, $data, $id);
	else
		$id = insert_row($table, $data);
		
	return $id;
	}

 
function insert_row($table, $data){
	global $dbh;
	
	$fields = array();
	$values = array();
	
	
	foreach($data as $key=>$value){
		$fields []= '`'.$key.'`';
		$values []= ':'.$key;
		
		$prepare [':'.$key] = $value;
		}
	
	$fields = implode(", ", $fields);
	$values = implode(", ", $values);
	
	$sql = "INSERT INTO `{$table}` ($fields) VALUES($values)";
	
	
	$sth = $dbh->prepare($sql);
	$sth->execute($prepare);
	
	return $dbh->lastInsertId; 
	}
	
	
function update_row($table, $data, $id){
	global $dbh;
	$sets = array();
	
	foreach($data as $key=>$value){
		$sets  []= "`$key` = :$key";
		
		$prepare[":".$key] = $value; 
		}
	
	$sets = implode(", ", $sets);

	$sql = "UPDATE `{$table}` SET $sets WHERE `id` = $id";
	
	$sth = $dbh->prepare($sql);
	$sth->execute($prepare);
	
	return $id;
	}


function delete_row($table, $id){
	global $dbh;
	
	if(!$table) return false;
	if(!$id) return false;
	$sql = "DELETE FROM `{$table}` WHERE `id` = $id";
	
	$sth= $dbh->prepare($sql);
	$sth->execute();
	
	return true;
	}
	
function delete_rows($table, $where){
	global $dbh;
	
	if(!$table) return false;
	if(!$where) return false;
	if(is_array($where))
		$where = implode(" AND ", $where);
	
	$sql = "DELETE FROM `{$table}` WHERE $where";
	
	$sth= $dbh->prepare($sql);
	$sth->execute();
	
	return true;
	}
	
	
function get_row($table, $id){
	global $dbh;
	
	$query = "SELECT * FROM `$table` WHERE `id` = ?";
	
	$sth= $dbh->prepare($sql);
	$sth->execute($id);
	
	$results = $sth->fetch(PDO::FETCH_ASSOC);
	
	return $results[0];
	}
	
function get_rows($table){
	global $dbh;
	
	$sql = "SELECT * FROM `$table`";
	
	$sth= $dbh->prepare($sql);
	$sth->execute();
	
	$results = $sth->fetch(PDO::FETCH_ASSOC);
	
	return $results;
	}
	