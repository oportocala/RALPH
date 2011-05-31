<?
/* APP SPECIFIC */
define("APP_NAME", "CLUJNIGHTS");
define("APP_LIVE_DOMAIN", "new.clujnights.ro");

/* PLATFORM SPECIFIC */

/* FOLDERS */
define('APP_DIR', "app");

define("INC_DIR", 	"includes");

define("CLASSES_DIR", 	"classes");
define("LIB_DIR", 	"libs");

define("PAGES_DIR", 	"pages");
define("LAYOUTS_DIR", 	"layouts");
define("MODELS_DIR", 	"models");

define("DOCTRINE2_MODELS_DIR", "models2");


/* PATHS */
define("APP_PATH", 	FILE_ROOT.APP_DIR.DS);

define("INC_PATH", 	APP_PATH.INC_DIR.DS);

define("CLASS_PATH", 	INC_PATH.CLASSES_DIR.DS);
define("LIB_PATH", 	INC_PATH.LIB_DIR.DS);

define("DOCTRINE2_MODELS_PATH",   APP_PATH.DOCTRINE2_MODELS_DIR.DS);
define("DOCTRINE2_ENTITIES_PATH", DOCTRINE2_MODELS_PATH."entities".DS);
define("DOCTRINE2_PROXIES_PATH",  DOCTRINE2_MODELS_PATH."proxies" .DS);

/* LIBS */
define("INTERNAL_LIB_PATH", LIB_PATH."internal".DS);
define("EXTENRAL_LIB_PATH", LIB_PATH."external".DS);

define("LAYOUTS_PATH",	APP_PATH.LAYOUTS_DIR.DS);
define("PAGES_PATH",	APP_PATH.PAGES_DIR.DS);
define('MODELS_PATH', 	APP_PATH.MODELS_DIR.DS);

/* WWW PATHS */
define('WWW_LAYOUTS', WWW_ROOT.LAYOUTS_DIR);