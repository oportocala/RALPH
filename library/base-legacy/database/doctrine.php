<?
$DB = $DOMAIN_CONFIG['db'];// shorthand

# Constants

define('DOCTRINE_MODELS_PATH', 		MODELS_PATH);

define('DOCTRINE_LIB_PATH', 		LIB_PATH.'external/doctrine/');
define('DOCTRINE_TEMPLATES_PATH',	LIB_PATH.'internal/doctrine/Templates/');

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
$dsn = "mysql:dbname=".$DB['name'].";host=".$DB['host'].";charset=UTF-8";
$dbh = new PDO($dsn, $DB['user'], $DB['pass']);
$dbh->exec('SET CHARACTER SET utf8');

$conn = Doctrine_Manager::connection($dbh);
$conn->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);

# Load Custom Templates
if(!DOCTRINE_COMPILED){
	Doctrine_Core::loadModels(DOCTRINE_TEMPLATES_PATH);
	}

# Manager Atributes
$manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
$manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES, true);
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_CONSERVATIVE);
$manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL ^ Doctrine_Core::VALIDATE_LENGTHS);

# Load Models
Doctrine_Core::loadModels(DOCTRINE_MODELS_PATH);


# ACP Cache
/*
$cacheDriver = new Doctrine_Cache_Apc();
$manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, $cacheDriver);
*/

# Profiler
$profiler = new Doctrine_Connection_Profiler();
$conn = Doctrine_Manager::connection();
$conn->setListener($profiler);