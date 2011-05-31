<?
/*
 * If APP_DIR/env file is found:
 *  1. read it
 *  2. see if configs/environment/<env>.php exits
 *  3. if it does:
 *      1. load the file
 *      2. save the data within into the RALPH consts
 *  4. if it does not
 *      1. see if default env name exists
 *      2. goto 3
 *
 * If the APP_DIR/env file is not found:
 *  1. use default value (defined in TODO, "development")
 *  2. see if it exists
 *  3. if it does:
 *      ...
 *  4. if
 *  --- This does not make sense ---
 *  TODO
 */

$env_file    = APP_ROOT.RALPH_ENV_FILE;
$env_exists  = is_file($env_file);
$env         = file_get_contents($env_file);

if($env_exists && $env){
    $tmp = APP_CONFIGS_PATH.ENVS_DIR.DS.$env.".php";
    require($tmp);
    $RALPH['app']['env'] = $__env;
    }

define(RALPH_CONST_APP_ENV, $env);