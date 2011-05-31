<?
class Routable_Inflector{
	
	static $classes = array(
			'slugs' => array(
				'index'		=> 'RoutesIndex',
				'sections'	=> 'RoutesSections'
				)
			);
	
	static $ext = "html";
	
	function urlize($value, $record){
		
		$table  	= $record->getTable();
		$table_name = $table->getTableName();
		$section 	= Doctrine_Core::getTable(self::$classes['slugs']['sections'])->findOneByForeignTable($table_name);
		if($section){
			
			$value = $value . '.' . self::$ext;
			if($section->path){
				$value = $section->path . RS . $value;
				}
			}

		return $value;
		}
	}