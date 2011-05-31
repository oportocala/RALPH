<?
$app_file   = FILE_ROOT.RALPH_APP_FILE;
$app_file_exists = is_file($app_file);
$tmp = "";
if($app_file_exists){
    $tmp = file_get_contents($app_file);
    if($tmp){
        $app_dir_exists = is_dir(FILE_ROOT.$tmp);
        if(!$app_dir_exists){
            $env_error = "Directory specified in the env file, \"".FILE_ROOT.$tmp."\" does not exist.";
            die($env_error);
            }
        }
    }

if(!$tmp){
    $tmp = RALPH_DEFAULT_APP_DIR;
    $app_dir_exists = is_dir(FILE_ROOT.$tmp);
    if(!$app_dir_exists){
        $env_error = "Default app directory, \"".FILE_ROOT.$tmp."\" does not exist.";
        if($app_file_exists){
            $env_error .= " Env file exists, but is empty.";
        }else{
            $env_error .= " Env file has NOT been found to overwrite the default.";
            }
        die($env_error);
        }
    }

define(RALPH_CONST_APP_DIR,   $tmp);
define(RALPH_CONST_APP_ROOT,  FILE_ROOT.$tmp.DIRECTORY_SEPARATOR);

$RALPH['app'] = array(
    "dir"   => constant(RALPH_CONST_APP_DIR),
    "path"  => constant(RALPH_CONST_APP_ROOT)
    );


$RALPH['paths']['app'] = $RALPH['app']['path'];