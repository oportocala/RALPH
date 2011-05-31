<?
define('DOCTRINE2_LIB_PATH', EXTENRAL_LIB_PATH.'doctrine2');

//  Class Loader
require DOCTRINE2_LIB_PATH.'/Doctrine/Common/ClassLoader.php';
use \Doctrine\Common\ClassLoader;

$classLoader = new ClassLoader('Doctrine', DOCTRINE2_LIB_PATH);
$classLoader->register();

// Configuration
$config = new Doctrine\ORM\Configuration();

// Proxy Configuration
$config->setProxyDir(DOCTRINE2_PROXIES_PATH);
$config->setProxyNamespace('Models\Proxies');
$config->setAutoGenerateProxyClasses((ENV_TYPE == ENV_TYPE_DEV));


/* Autoloaders */
$classLoader = new ClassLoader('Entities', DOCTRINE2_MODELS_PATH);
$classLoader->register();


$classLoader = new ClassLoader('Proxies', DOCTRINE2_MODELS_PATH);
$classLoader->register();


// Mapping Configuration
$driverImpl = $config->newDefaultAnnotationDriver(DOCTRINE2_ENTITIES_PATH);
$config->setMetadataDriverImpl($driverImpl);


// Caching Configuration
if (ENV_TYPE == ENV_TYPE_DEV) {
    $cache = new \Doctrine\Common\Cache\ArrayCache();
} else {
    $cache = new \Doctrine\Common\Cache\ApcCache();
}
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

// Database Connection Configuration
$conn = array(
    'dbname'    => $APP['db']['config']['name'],
    'user'      => $APP['db']['config']['user'],
    'password'  => $APP['db']['config']['pass'],
    'host'      => $APP['db']['config']['host'],
    
    'driver'    => 'pdo_mysql'
);

// Obtaining the Entity Manager
$evm = new Doctrine\Common\EventManager();
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config, $evm);
