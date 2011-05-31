<?

class TCImages{

	private $tables = array(
		"sections"   => "tc_sections",
		"sizes"      => "tc_sizes",
		"images"     => "tc_images",
		"thumbnails" => "tc_thumbnails"
		);
	
	private $table;
	private $id;
	
	function TCImages($table, $id){
		
		$this->table = $table;
		
		$this->id    = $id;
		
		$table = res($table);
		$id    = res($id) + 0;
		
		$sql = "SELECT `id` FROM ".$this->tables['images']." WHERE `foreign_table` = '{$table}' AND `foreign_id` = '{$id}'";
		
		$ids = fetch_assocs(query($sql));
		
		foreach($ids as $i=>$row){
			$this->images[]= new TCImage($row['id']);
			}
		
		}
		
	function getSectionID(){
		$table = res($this->table);
		$sql = "SELECT `id` FROM ".$this->tables['sections']." WHERE `foreign_table` = '{$table}'";

		$id  = fetch_assoc(query($sql));
		return array_shift($id); 
		}
	}
	
class TCImage{
	
	private $tables = array(
		"sections"   => "tc_sections",
		"sizes"      => "tc_sizes",
		"images"     => "tc_images",
		"thumbnails" => "tc_thumbnails"
		);
	public $id;
	
	function TCImage($id){
		$this->id = $id;
		$this->image = get_row($this->tables['images'], $id);
		}
		
	function getThumbnail($name){
		$name = res($name);
		
		$sql = "SELECT * FROM ".$this->tables["sizes"]." WHERE `section_id`= ".$this->image['section_id']." AND `name` = '$name'";
		
		$size = fetch_assoc(query($sql));
		
		$sql = "SELECT `url` FROM ".$this->tables["thumbnails"]." WHERE `image_id` = '".$this->image['id']."' AND `size_id` = '".$size['id']."'";
		$row = fetch_assoc(query($sql));
		return str_replace("//", "/", WWW_ROOT.$row['url']);
		}
		
		
	function delete(){
		if(!$this->id) return false;
		$sql = "SELECT `id`,`path` FROM ".$this->tables["thumbnails"]." WHERE `image_id` = '".$this->image['id']."'";
		$rows = fetch_assocs(query($sql));
		foreach($rows as $row){
			$path = $row['path'];
			$path = FILE_ROOT.$path;
			
			unlink($path);
			if(!is_file($path)){
				delete_row($this->tables["thumbnails"], $row['id']);
				}
			}
		
		$section = get_row($this->tables['sections'], $this->image['section_id']);
		rmdir(FILE_ROOT.$section['path'].$this->image['foreign_id'].'/'.$this->id);
		
		$path = FILE_ROOT.$this->image['path'];
		unlink($path);
		if(!is_file($path)){
			delete_row($this->tables["images"], $this->id);
			}
			
		return true;
		}
	}