<?
define('CURRENT_URL', $_SERVER['REDIRECT_URL']);// no paramters
define('CURRENT_URI', $_SERVER['REQUEST_URI']); // with parameters
define('CURRENT_PREP_URI', CURRENT_URL.'?'.http_build_query($_GET, '','&'));

