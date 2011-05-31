<?
class Doctrine_Template_Authenticatable extends Doctrine_Template{
	
	protected $_options = array(
        'fields' => array(
			'username' => 'username',
			'password' => 'password'
			)
   	 	);
		
	protected $_data = array(
		'session' => array(
				'name'	=> 'user',
				'quick' => 'u5'
				)
		);
	
	protected $_listener;
	
	public function setTableDefinition(){
		$this->_listener = new Doctrine_Template_Listener_Authenticable();
        $this->addListener($this->_listener);
		}
	
	public function setUp(){
		$options = $this->getTable()->getOptions();
		$tableName = $options['tableName'];
		
		$this->_data['session']['name'] = $tableName;
		$this->_data['session']['quick'] = $tableName."_quick";
		
		$session_data = $_SESSION[$this->_data['session']['name']];
		if(isset($session_data['id'])){  
			//$this->getInvoker()->hydrate($session_data, true);
			}  
		}
	
	public function login($data){
		if(!is_array($data) || !count($data)) return false;
		
	
		$table = $this->getTable();
		$q = $table->createQuery();
		
		$q	->where(	$this->_options['fields']['username'].' = ?' , $data['username'])
			->andWhere(	$this->_options['fields']['password'].' = ?' , md5($data['password']));
		
		$q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
		
		$check = ($q->count() == 1);
		
		if($check){
			$_SESSION[$this->_data['session']['name'] ]	= $q->fetchOne();
			$_SESSION[$this->_data['session']['quick']] = true;
			$ret = true;
		}else 
			$ret = false;
		
		return $ret;
		}
	
	public function login_check(){
		$ar = $_SESSION[$this->_data['session']['name']];
		$quick = $_SESSION[$this->_data['session']['quick']];
		return ($quick === true)?$ar['id']:false;
		}
		
	public function logout(){
		$_SESSION[$this->_data['session']['quick']] = false;
		unset($_SESSION[$this->_data['session']['name']], $_SESSION[$this->_data['session']['quick']]);
		
		}
	}