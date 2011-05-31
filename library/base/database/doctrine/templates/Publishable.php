<?

class Doctrine_Template_Publishable extends Doctrine_Template{
	
	protected $_options = array(
			'name'	=> 'slug',
			'ext'	=> 'html'
			);
			
	protected $_listener;
	
	public function setTableDefinition(){
		
		$this->hasColumn('status', 'enum', null, array('default'=> 'draft', 'values'=> array('draft', 'published')));
		$this->hasColumn('published_at', 'timestamp');
		
		
		$this->_listener = new Doctrine_Template_Listener_Publishable($this->_options);
                $this->addListener($this->_listener);
		}
		
	public function incViews($dev = false){
                $record = $this->getInvoker();
                $table = $this->getTable();
                $table = $table->getTableName();
                
                $id = $record->id;
                $ip = $_SERVER['REMOTE_ADDR'];
           
                global $dbh;
                //Update View
                if ($dev || !$this->isUniqueAction(0)){
                    $sql = "UPDATE `publishable_views` SET `views` = `views` + 1 , `unique_views` = `unique_views` + 1
                                      WHERE `foreign_id` = {$id} AND `foreign_table` = '{$table}'";

                                      
                    $sth = $dbh->prepare($sql);
                    $sth->execute();
                }
                else {
                    $sth = $dbh->prepare("UPDATE `publishable_views` SET `views` = `views` + 1
                                      WHERE `foreign_id` = {$id} AND `foreign_table` = '{$table}'");
                     $sth->execute();
                }
                ;
                //Log View
                $sth = $dbh->prepare("INSERT INTO `publishable_logs` (`foreign_id`,`foreign_table`,`ip`,`time_stamp`, `action`)
                            VALUES ({$id}, '{$table}', INET_ATON('{$ip}'), NOW(), 0)");
                $sth->execute();


                }
         private function isUniqueAction($action = 0){
             $record = $this->getInvoker();
             $table = $this->getTable();
             $table = $table->getTableName();
             $id = $record->id;
             $ip = $_SERVER['REMOTE_ADDR'];
             
             global $dbh;

             $sql = "SELECT * FROM `publishable_logs` 
                     WHERE `ip` = INET_ATON(?) AND `foreign_id` = ? AND `foreign_table`= ? AND time_stamp = CURDATE() and `action` = ?";
	
             $sth = $dbh->prepare($sql);
             $sth->execute(array($ip, $id, $table, $action));
             
             if($sth->rowCount() > 0){
                return false;
             }

             return true;
         }
         
    public function publish(){
        $record = $this->getInvoker();
        $record->status = 'published';
        $record->published_at = date('Y-m-d H:i:s', time());
        $record->save();
        }
}
