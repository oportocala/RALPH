<?
/*
 * Database config class
 */

class CONFIG{

     private static $table = 'app_config';
    
    public function get($key){
        global $dbh;

        $sql = "SELECT `value` FROM ".self::$table." WHERE `key` = ?";
        
        $sth = $dbh->prepare($sql);
	$sth->execute(array($key));

        $result = $sth->fetch(PDO::FETCH_ASSOC);
        
        return $result['value'];
        }
        
    /* @TODO: set a config value, add if not exists */
    public function set($key, $value){
        global $dbh;

        }
}