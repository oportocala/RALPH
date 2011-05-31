<?
error_reporting(E_ERROR);
include("../../../bootstrap.php");

// Configure Doctrine Cli
// Normally these are arguments to the cli tasks but if they are set here the arguments will be auto-filled
$config = array('data_fixtures_path'  =>  RALPH_FIXTURES_PATH,
                'models_path'         =>  RALPH_MODELS_PATH,
                'migrations_path'     =>  RALPH_MIGRATIONS_PATH,
                'sql_path'            =>  RALPH_SQL_PATH,
                'yaml_schema_path'    =>  RALPH_SCHEMAS_PATH);


$cli = new Doctrine_Cli($config);
$cli->run($_SERVER['argv']);