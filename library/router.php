<?

define('ROUTER_LAYOUT', true);


include('bootstrap.php');

//print_r($RALPH);

define('ROUTER_DEBUG', true);

$query = explode(RS, $_GET['_router_query']);
$tmp   = explode(RS, WWW_ROOT);
$root  = array_splice($tmp, 1);

$root  = array_remove_empty($root);
$query = array_remove_empty($query);
$path  = array_diff_assoc($query, $root);
$router = implode( RS, $path);
define("ROUTER_QUERY", $router);

unset($_GET['_router_query']);
@list($dirname, $basename, $extension) = array_values(pathinfo(ROUTER_QUERY));
if($basename == $extension){
	$extension = '';
	$router_query_no_ext = $basename;
}else{
	$router_query_no_ext = strtolower(substr(ROUTER_QUERY, 0, -(strlen($extension)+1)));
	}
define("ROUTER_QUERY_NO_EXTENSION", $router_query_no_ext);

$searchable_extensions = array('', 'html', 'php', 'htm');

/* Check extension for 404 responses */
if(!in_array($extension, $searchable_extensions)){
	header("HTTP/1.0 404 Not Found");
	exit;
	}
	

/* Check redirects */
$check = Doctrine_Core::getTable('RoutesRedirect')
			->findOneByOld(ROUTER_QUERY); 

if($check){
	$type = $check['type'];
	if($type == "301"){
		header("HTTP/1.1 301 Moved Permanently");
		}
	headerRedirect($check['new']);
	exit;
	}


/* Check static routes */

$check = Doctrine_Core::getTable('RoutesStatic')
			->findOneBySlug(ROUTER_QUERY_NO_EXTENSION);

if($check){
	echo $file;
	$file = PAGES_PATH.$check['layout'].DS.$check['page'];
	if(is_file($file)){
		define_layout_constants($check['layout']);
		include($file);
		exit;
	}else{
		echo "[Router Fatal Error] Static route handle not found: [".$file."]";
		exit;
		}
	}

/* Check dynamic slug routes */
$check = Doctrine_Core::getTable('RoutesIndex')
			->findOneBySlug(ROUTER_QUERY);
			
if($check){
	$section = $check->Section;
	
	$_GET[$section['id_param_name']] = $check['foreign_id'];
	$file = PAGES_PATH.$section['layout'].DS.$section['page'];
	
	if(is_file($file)){
		define_layout_constants($section['layout']);
		include($file);
		exit;
	}else{
		echo "[Router Fatal Error] dynamic route file not found: [".$file."]";
		exit;
		}
	}
	
	
/* TODO: If extension accepted and still no match redirect to search / 404 */
header("HTTP/1.0 404 Not Found");

if(ROUTER_DEBUG){
	echo ROUTER_QUERY;
	}