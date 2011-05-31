<?
$DB = $RALPH['app']['env']['db'];// shorthand

# Constants
define('DOCTRINE_LIB_PATH', 	VENDOR_PATH.'orm/doctrine1/lib/');

# Current working directory
$CWD = dirname(realpath ( __FILE__ )).DS."doctrine".DS;

# Ralph Doctrine constants
define('RALPH_TEMPLATES_PATH',	$CWD.'templates'.DS);
define('RALPH_MODELS_PATH',	    $CWD.'models'.DS);
define('RALPH_SCHEMAS_PATH',	$CWD.'schemas'.DS);
define('RALPH_FIXTURES_PATH',	$CWD.'fixtures'.DS);
define('RALPH_MIGRATIONS_PATH',	$CWD.'migrations'.DS);
define('RALPH_SQL_PATH',	    $CWD.'migrations'.DS);

define('DOCTRINE_COMPILED', 		false);

# Library load
if(DOCTRINE_COMPILED){
	require_once(LIB_PATH.'internal/doctrine/compiled.php');
}else{
	require_once(DOCTRINE_LIB_PATH.'Doctrine.php');
	}

# Autoloaders
spl_autoload_register(array('Doctrine', 'autoload'));
spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));
$manager = Doctrine_Manager::getInstance();

# Connection
$db_conn_string = 'mysql://'.$DB['user'].':'.$DB['pass'].'@'.$DB['host'].'/'.$DB['name'];
$conn = Doctrine_Manager::connection($db_conn_string);
$conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

# Load Custom Templates
if(!DOCTRINE_COMPILED){
	Doctrine_Core::loadModels(RALPH_TEMPLATES_PATH);
	}

# Manager Atributes
$manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
$manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
$manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL ^ Doctrine_Core::VALIDATE_LENGTHS);

# Load Models
Doctrine_Core::loadModels(RALPH_MODELS_PATH);
Doctrine_Core::loadModels(APP_MODELS_PATH);


# ACP Cache
/*
$cacheDriver = new Doctrine_Cache_Apc();
$manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $cacheDriver);
*/

# Profiler
$profiler = new Doctrine_Connection_Profiler();
$conn = Doctrine_Manager::connection();
$conn->setListener($profiler);