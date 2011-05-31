<?

class FLASH{

	private static $session_key = "ERIC_FLASH_DATA";

	function get($key){
		$data = self::getData();
		
		$value = $data[$key];
		self::remove($key);

		return $value;
		}
		
	function has($key){
		$data = self::getData();
		return isset($data[$key]);
		}
		
	function remove($key){
		$data = self::getData();
		
		unset($data[$key]);
		self::putData($data);
		}

	function put($key, $put_data){
		$data = self::getData();
		
		$data[$key] = $put_data;
		self::putData($data);
		}
		
	function set($key, $data){
		self::put($key, $data);
		}
		
	private function getData(){
		return $_SESSION[self::$session_key];
		
		}
		
	private function putData($data){
		$_SESSION[self::$session_key] = $data;
		}
	function debug(){
		pr($_SESSION[self::$session_key]);
		}
		
	function clear(){
		unset($_SESSION[self::$session_key]);
		}
	}