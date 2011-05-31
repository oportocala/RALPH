<?
class Doctrine_Template_Listener_Publishable extends Doctrine_Record_Listener{
	protected $_options = array();
	
    public function __construct(array $options){
        $this->_options = $options;
    }
		
	
	/*public function postSave(Doctrine_Event $event){
		$record = $event->getInvoker();
		if($record->status == 'draft'){
			$this->postInsert($event);
		}else{
			//$this->setSlugFromSlugTable($record);

                      }
        }*/
		
	
		
        public function postInsert (Doctrine_Event $event){
               
               $record = $event->getInvoker();
               $table = $record->getTable();
               $table = $table->getTableName();
               
               $id = $record->id;

                $data = array (
                        'foreign_id'    => $id,
                        'foreign_table' => $table,
                        'number_votes'  => 0,
                        'total_points'  => 0,
                        'dec_avg'       => 0,
                        'whole_avg'     => 0
                );

                insert_row('publishable_ratings', $data);

                $data = array (
                        'foreign_id'    => $id,
                        'foreign_table' => $table,
                        'views'         => 0,
                        'unique_views'   => 0
                );

                insert_row('publishable_views', $data);
       	
              	}
		
		/* This does not work, probably because of SoftDeletable, as it normaly should work */
		/*
        public function postDelete(Doctrine_Event $event){
            $record = $event->getInvoker();
            $table = $record->getTable();

            $id = $record->id;
			
			
			
            global $dbh;
            $sth = $dbh->prepare("DELETE FROM `publishable_ratings` WHERE `foreign_id` = ? and `foreign_table` = ?");
            $sth->execute(array($id, $table));
			
            $sth = $dbh->prepare("DELETE FROM `publishable_views` WHERE `foreign_id` = ? and `foreign_table` = ?");
            $sth->execute(array($id, $table));

        	}
       */
}