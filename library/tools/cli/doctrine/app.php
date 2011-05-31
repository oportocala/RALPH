<?
error_reporting(E_ERROR);
include("../../../bootstrap.php");

// Configure Doctrine Cli
// Normally these are arguments to the cli tasks but if they are set here the arguments will be auto-filled
$config = array('data_fixtures_path'  =>  APP_FIXTURES_PATH,
                'models_path'         =>  APP_MODELS_PATH,
                'migrations_path'     =>  APP_MIGRATIONS_PATH,
                'sql_path'            =>  APP_SQL_PATH,
                'yaml_schema_path'    =>  APP_SCHEMAS_PATH);


$cli = new Doctrine_Cli($config);
$cli->run($_SERVER['argv']);