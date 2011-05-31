<?
/*

$up = new TCUpload($section_id);
$image_id = $up->upload($id, $_FILES['image']);
redirect('thumbanil.edit/id/$image_id/');

*/
include("external/PHPTumb/ThumbLib.inc.php");
include("TCThumbnails.php");

class TCUpload{
	
	private $section;
	private $section_set = false;
	
	private $processFlags = array(
		"scale"   => false,
		"convert" => false
		);
	
	private $tables = array(
		"sections"   => "tc_sections",
		"sizes"      => "tc_sizes",
		"images"     => "tc_images",
		"thumbnails" => "tc_thumbnails"
		);
	
	private $settings = array(
		"accepted_extensions" => array("png", "jpg", "gif"),
		"validate_foreign"    => true,
		
		"time_limit" 	=> 0,
		"debug"	  		=> false
		);
	
	private $originalImage = array();
	
	function TCUpload($section_id){
		$this->setSectionID($section_id);
		}
	
	function setSectionID($section_id){
		
		$this->section = get_row($this->tables['sections'], $section_id);
		
		if($this->section){
			$this->section_set = true;
			return true;
		}else{
			$this->debug("Section ID invalid");
			return false;
			}
		}

	function upload($foreign_id, $post){
		set_time_limit($this->settings['time_limit']);
		
		
		// Foreign validation
		if($this->settings['validate_foreign']){
			$valid_foreign = $this->validForeign($foreign_id);
			if($valid_foreign !== true){
				$this->error("Invalid foreign table/id:<br/>".$valid_foreign);
				}
			}
		
		// FILES validation
		$valid_post = $this->validPost($post);
		if($valid_post !== true){
			$this->error("Invalid POST data:<br/>".$valid_post);
			}
		
		// Save in DB
		$image_id = $this->saveImageRow($post, $foreign_id);
		if(!$image_id){
			$this->error("Could not save image in database.");
			}
			
		// Move uploaded files
		$original_file = $this->moveImage($image_id, $foreign_id, $post);
		if($move === false){
			$this->deleteImageRow($image_id);
			$this->error("Could not move uploaded files.");
			}
		
		// Process image if needed
		$processed_image = $this->processImage($original_file, $image_id, $foreign_id);
		if($processed_image === false){
			$this->error("Error processing image:<br/>".$process);
			}
		
		// Update image row with new data
		$update = $this->updateImageRow($processed_image, $image_id, $foreign_id);
		if(!$update){
			$this->error("Error updating image data in database.");
			}
			
		// Create default thumbnails
		$thumbs = new TCThumbnails($image_id);
		$thumbs->generateDefaults();
		
		return $image_id;
		}

	function processImage($original_file, $image_id, $foreign_id){
		$file = $original_file;
		$scaleFlag = $this->processFlags['scale'];
		if($scaleFlag !== false){
			$thumb = PhpThumbFactory::create($file);
			
			if($scaleFlag == "up"){
				$thumb->resize($this->section['minw'], $this->section['minh']);
				}
				
			if($scaleFlag == "down"){
				$thumb->resize($this->section['maxw'], $this->section['maxh']);
				}
			$thumb->save($file);
			}
		
		if($this->processFlags['convert']){
			$root = FILE_ROOT;
			$original_dir = $root.$this->section['path'].$foreign_id."/";
			$new_filename = $original_dir.$image_id.".".$this->section['format'];
			
			$thumb = PhpThumbFactory::create($file);
			$thumb->save($new_filename, $this->section['format']);
			
			@unlink($original_file);
			
			if(is_file($original_file)){
				$this->debug("Could not delete originial file:<br/>[$original_file]");
				}
			$file = $new_filename;
			}
			
		return $file;
		}

	function moveImage($image_id, $foreign_id, $post){
		$root = FILE_ROOT;
		
		$original_dir = $root.$this->section['path'].$foreign_id."/";
		@mkdir($original_dir);
		$thumbnails_dir = $original_dir.$image_id;
		@mkdir($thumbnails_dir);
		
		$this->debug("original_dir:".$original_dir);
		$this->debug("thumbnails_dir:".$thumbnails_dir);
		
		if(!is_dir($original_dir) || !is_dir($thumbnails_dir)){
			$this->error("Could not create directories!<br>[$original_dir|$thumbnails_dir]");
			}
		
		$original_image = $original_dir.$image_id.".".$this->originalImage['extension'];
		$this->debug("original_image:".$original_image);
		
		
		$ok = move_uploaded_file($post['tmp_name'], $original_image);
		if($ok === false)
			return false;
		
		return $original_image;
		}
	
	function saveImageRow($post, $foreign_id){
		$path = $this->section['path'].$foreign_id."/";
		$insert = array(
			"section_id" => $this->section['id'],
			
			"foreign_id" 	=> $foreign_id,
			"foreign_table"	=> $this->section['foreign_table'],
			
			"filename" => $this->originalImage['name'],
			"size"     => $this->originalImage['size'],
			"width"    => $this->originalImage['width'],
			"height"   => $this->originalImage['height'],
			"path"     => $path,
			"url"      => $path,
			"extension"=> $this->originalImage['extension']
			);
			
		return insert_row($this->tables['images'], quote($insert));
		}

	function deleteImageRow($image_id){
		delete_row($this->tables['images'], $image_id);
		}
	
	function updateImageRow($processed_image, $image_id, $foreign_id){
		
		$pathinfo = pathinfo($processed_image);
		list($width, $height) = getimagesize($processed_image);
		$extension = strtolower($pathinfo['extension']);
		$size = filesize($processed_image);
		
		
		$path = $this->section['path'].$foreign_id."/".$image_id.".".$extension;
		
		$update = array(
			"size"     => $size,
			"width"    => $width,
			"height"   => $height,
			"path"     => $path,
			"url"      => $path,
			"extension"=> $extension
			);
		
		
		return update_row($this->tables['images'], quote($update), $image_id);
		}
	
	function validPost($post){
		$s = $this->section;
		
		// Hardware transfer problems
		$error = $post['error'];
		if ($error !== UPLOAD_ERR_OK)
	  		return $this->file_upload_error_message($error); 
		
		if($post['size'] == 0)
			return "Post filesize == 0.";

		// reset Flags
		$this->resetProcessFlags();
		$this->originalImage = $post;
		
		// Corect filetype
		$pathinfo = pathinfo($post['name']);
		$extension = strtolower($pathinfo['extension']);
		if(!in_array($extension, $this->settings['accepted_extensions'])){
			return "Unacceptable extension: [$extension].";
			}
			
		if($extension !== $s['format']){
			$this->debug("flag[convert] set to true");
			$this->processFlags['convert'] = true;
			}
		
		$this->originalImage['extension'] = $extension;
		
		// Coresponds to min / max 
		
		$min = array(
			"w" => $s['minw']+0,
			"h" => $s['minh']+0,
			"handle" => $s['min-handle']
			);
			
		$max = array(
			"w" => $s['maxw']+0,
			"h" => $s['maxh']+0
			);
		
		list($width, $height) = getimagesize($post['tmp_name']);
		$this->debug("width:$width<br/>height:$height");
		

		if($width < $min['w'] || $height < $min['h']){
			if($min['handle'] == 'reject'){
				return "Size too small. Min size:[".$min['w']."x".$min['h']."]";
			}else{
				$this->debug('flag[scale] set to up');
				$this->processFlags['scale'] = "up";
				}
			}
		
		if($width > $max['w'] || $height > $max['h']){
			$this->debug('flag[scale] set to down');
			$this->processFlags['scale'] = "down";
			}
		
		$this->originalImage['width']  = $width;
		$this->originalImage['height'] = $height;
		
		return true;
		}
	
	function validForeign($foreign_id){
		
		$foreign_table = $this->section['foreign_table'];
		$row = get_row($foreign_table, $foreign_id);
		if(is_array($row))
			return true;
		else{
			$error = "table:[$foreign_table]<br/>id:[$foreign_id]";
			return $error;
			}
		}
		
	function resetProcessFlags(){
		$this->processFlags = array(
			"scale"   => false,
			"convert" => false
			);
		$this->originalImage = array();
		}
	function file_upload_error_message($error_code) {
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			case UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded';
			case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary folder';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk';
			case UPLOAD_ERR_EXTENSION:
				return 'File upload stopped by extension';
			default:
				return 'Unknown upload error';
				}
		} 	
	function debug($message, $die = false){
		if($this->settings['debug']){
			echo "<pre>$message</pre>";
			}
		}
	function error($message){
		echo "<pre>$message</pre>";
		exit;
		}
	}