<?

$required_structure_constants = array(
    "CONFIGS_DIR", "ENVS_DIR","DATABASE_DIR","MODELS_DIR", "SCHEMAS_DIR", "MIGRATIONS_DIR", "FIXTURES_DIR", "SQL_DIR", "LAYOUTS_DIR","PAGES_DIR","UPLOADS_DIR");

$app_structure_file = APP_ROOT.RALPH_STRUCTURE_FILE;

if(is_file($app_structure_file)){
    include($app_structure_file);

    foreach($required_structure_constants as $test){
        if(!defined($test)){
            $error = "Required constant '$test' not found in app structure file: '".APP_ROOT.RALPH_STRUCTURE_FILE."'.";
            die($error);
            }
        }
    $RALPH['app']['structure_status'] = 'Using app defined structure file.';
}else{
    $RALPH['app']['structure_status'] = 'Using default structure file.';
    $app_structure_file = RALPH_DEFAULTS_PATH.RALPH_DEFAULT_APP_DIR.DS.RALPH_STRUCTURE_FILE;
    include($app_structure_file);
    }

$tmp = array();
foreach($required_structure_constants as $test){
     $RALPH['app']['structure'][$test] = constant($test);
    }


define("APP_CONFIGS_PATH",  APP_ROOT.CONFIGS_DIR.DS);

define("APP_MODELS_PATH",       APP_ROOT.DATABASE_DIR.DS.MODELS_DIR.DS);
define("APP_SCHEMAS_PATH",      APP_ROOT.DATABASE_DIR.DS.SCHEMAS_DIR.DS);
define("APP_MIGRATIONS_PATH",   APP_ROOT.DATABASE_DIR.DS.MIGRATIONS_DIR.DS);
define("APP_FIXTURES_PATH",     APP_ROOT.DATABASE_DIR.DS.FIXTURES_DIR.DS);
define("APP_SQL_PATH",          APP_ROOT.DATABASE_DIR.DS.SQL_DIR.DS);

define("PAGES_PATH",        APP_ROOT.PAGES_DIR.DS);
define("LAYOUTS_PATH",      APP_ROOT.LAYOUTS_DIR.DS);


define("WWW_LAYOUTS",       WWW_ROOT.APP_DIR.RS.LAYOUTS_DIR.RS);
