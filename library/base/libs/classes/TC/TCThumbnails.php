<?
/*


Thumbnails DB strucutre
id
image_id
size_id
foreign_table
filename
url
path
width
height
crop_sx
crop_sy
crop_width
crop_height
*/

class TCThumbnails {
	
	
	private $tables = array(
		"sections"   => "tc_sections",
		"sizes"      => "tc_sizes",
		"images"     => "tc_images",
		"thumbnails" => "tc_thumbnails"
		);
		
	public $image;
	public $section;
		
	function TCThumbnails($image_id){
		$this->image = get_row($this->tables['images'], $image_id);
	
		$section_id = $this->image['section_id'];
		
		$this->section = get_row($this->tables['sections'], $section_id);
		
		
		$query = "SELECT * FROM ".$this->tables['sizes']." WHERE `section_id` = {$section_id}";
		$this->sizes = fetch_assocs(query($query));
	
		}
		
	function getThumbImages(){
		$image_id = $this->image['id'];
		
		foreach($this->sizes as &$size){
			$size_id = $size['id'];
			$sql = "SELECT * FROM `".$this->tables['thumbnails']."` WHERE `image_id`= $image_id AND `size_id` = $size_id";
			$size['image'] = fetch_assoc(query($sql));
			}
		}
	
	function generateDefaults(){
		$root = FILE_ROOT;
		$input = $root.$this->image['path'];
		
		
		$original_dir = $root.$this->section['path'].$this->image['foreign_id']."/";
		$thumbnails_dir = $original_dir.$this->image['id']."/";
		
		@mkdir($original_dir);
		@mkdir($thumbnails_dir);
		
		
		foreach($this->sizes as $size){
			$width  = $size['width'];
			$height = $size['height'];
			$output = $thumbnails_dir.$size['name'].'.'.$this->section['format']; 
			$thumb = PhpThumbFactory::create($input);
			
			
			if($width == 0 && $height == 0){
				// do nothing
				$crop = array();
			}else{			
				$thumb->adaptiveResize($width, $height);
				$crop = $thumb->cropData;
				}
			
			$thumb->save($output);
			
			
			list($width, $height) = getimagesize($output);
			
			
			// save to db
			$filename = $size['name'].'.'.$this->section['format'];
			$url = $this->section['path'].$this->image['foreign_id']."/".$this->image['id']."/".$filename;

			
			$arr = array(
				"crop_sx"    => $crop['sx'],
				"crop_sy"    => $crop['sy'],
				"crop_width" => ($crop['width'] ?$crop['width'] :$width),
				"crop_height"=> ($crop['height']?$crop['height']:$height),
				
				"width" 	=> $width,
				"height"	=> $height,
				
				"size_id"	=> $size['id'],
				"image_id"	=> $this->image['id'],
				"foreign_table" => $this->section['foreign_table'],
				"filename"		=> $filename,
				"url"			=> $url,
				"path"			=> $url
				);
			
			insert_row($this->tables['thumbnails'], quote($arr));
			}
		}
	
	
	function generateThumbnailsFromPost($data){
		// input: $data['images'][<thumbnail_id>]
		//	[<crop>][crop_<sx,sy,width,height>]
		//  [size_id]
		
		$images = $data['images'];
		
		$changed = false;
		
		$root = FILE_ROOT;
		$input = $root.$this->image['path'];
		
		foreach($images as $tid=>$tdata){
			// compare with old data
			// if change
			// resize and crop thumbnail from original
				// 1. crop
				// 2. resize
				// 3. save new data
					// crop data
					// width, height
			
			
			
			$size_id = $tdata['size_id'];
			$crop	 = $tdata['crop'];
			
			$size = get_row($this->tables['sizes'], $size_id);
			$t    = get_row($this->tables['thumbnails'], $tid);
			
			$old = $t;
			$changed = true;
			
			$changed = (
						$crop['crop_sx'] 	!= $old['crop_sx'] 		|| $crop['crop_sy'] 	!= $old['crop_sy'] ||
						$crop['crop_width'] != $old['crop_width'] 	|| $crop['crop_height'] != $old['crop_height']
						);
			
			$output = $root.$t['path'];
			if($changed){
				
				$thumb = PhpThumbFactory::create($input);
				$thumb->crop($crop['crop_sx'], $crop['crop_sy'], $crop['crop_width'], $crop['crop_height']);
				if($size['width'] != 0 && $size['height'] != 0){
					$thumb->adaptiveResize($size['width'], $size['height']);
					}
				$thumb->save($output);
				
				$update = array('width'=>$width, 'height'=>$height);
				$update = array_merge($update, $crop);
				
				update_row($this->tables['thumbnails'], quote($update), $tid);
				}
			
			}
		}
	}