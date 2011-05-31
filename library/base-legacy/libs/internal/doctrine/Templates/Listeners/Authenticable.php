<?
class Doctrine_Template_Listener_Authenticable extends Doctrine_Record_Listener{

		public function preSave(Doctrine_Event $event){
			$this['password'] = md5($this['password']);	
			}
}