<?
class Doctrine_Form_Element{
	
	function selectFromRecord($row, $relation, $opt = array()){
		if(!$opt['fields']){
			
			$opt['fields'] = array(
				'display' => 'name'
				);
			}
		
		$table = $row->getTable();
		$relation = $table->getRelation($relation);
		$local_field  = $relation->getLocal();
		$foreign_table = $relation->getTable();
		
		$columns = $table->getColumns();
		
		$rows = $foreign_table->findAll(Doctrine_Core::HYDRATE_ARRAY);
		$html = "<select name='{$relation['local']}'>";
		if(!$columns[$local_field]['notnull']){
			$html .='<option value>&ndash; Empty &ndash;</option>';
			//$html .='<option></option>';
			}
		
		foreach($rows as $_row){
			$name  = $_row[$opt['fields']['display']];
			$value = $_row['id'];
			$s = ($value == $row[$local_field])?'selected':'';
			$html .= "<option value='$value' $s>$name</option>";
			}
		$html.= '</select>';
		return $html;
		}
		
		
	function inputFromRecord($row, $name, $type="text", $classes = array('medium-input')){
		$classes = array_merge($classes, array('text-input'));
		
		$class 	= implode(" ", $classes);
		$id 	= 'field_'.$name;
		$value 	= $row[$name];
		
		$html = "<input  id='$id' type='$type' class='$class' name='$name'  value='$value' />";
		return $html;
		}

        function i18nTextareaFromRecord($item, $name, $classes = array('text-input', 'textarea', 'wysiwyg') ){
            $classes = implode(" ", $classes);
            $jsv0 = "javascript:void(0)";
            $rows = Doctrine_Core::getTable("Language")->findAll(Doctrine_Core::HYDRATE_ARRAY);

            $default_language_code = CONFIG::get('default_language_code');

            $ul = "<ul class='i18n-languages'>";
            $div = "<div class='i18n-textareas'>";

            foreach($rows as $row){
                $code = $row['code'];
                $default = ($default_language_code == $code)?'current':'';
                $ul .= "<li class='i18n-btn-wrap $default'><a href='".$jsv0."' class='i18n-btn $default' rel='$code'>";
               
                $src  = WWW_ROOT."icn/flags/".strtolower($code).".png";
                $ul .= "<img src='$src' title='$code'/>";
                $ul .= "</a></li>";
                $value     = $item->Translation[strtolower($code)][$name];
                
                $textarea_name  = "Translation[".strtolower($code)."][".$name."]";
                
                $div .= "<div class='i18n-textarea-wrap $default' rel='$code'><textarea name='$textarea_name' class='i18n-textarea $classes' rel='$code'>$value</textarea></div>";
                }
                
            $ul .= "</ul>";
            $div.= "</div>";
            
            $html = "<div class='i18n-field i18n-textarea-field'>".$ul.$div."</div>";
            return $html;
            }
	
        function i18nInputFromRecord($item, $name, $type, $classes = array('medium-input')){
            $classes = array_merge($classes, array('text-input'));
            $classes = implode(" ", $classes);
            
            $jsv0 = "javascript:void(0)";

            $rows = Doctrine_Core::getTable("Language")->findAll(Doctrine_Core::HYDRATE_ARRAY);

            $default_language_code = CONFIG::get('default_language_code');

            
            $ul     = "<ul class='i18n-languages'>";
            $div    = "<div class='i18n-textareas'>";

            foreach($rows as $row){
                $code = $row['code'];
                $default = ($default_language_code == $code)?'current':'';

                $ul .= "<li class='i18n-btn-wrap $default'><a href='".$jsv0."' class='i18n-btn $default' rel='$code'>";

                $src  = WWW_ROOT."icn/flags/".strtolower($code).".png";
                $ul .= "<img src='$src' title='$code'/>";
                $ul .= "</a></li>";

                $div.= "<div class='i18n-input-wrap $default' rel='$code'><input id='$id' type='$type' class='$class' name='$name'  value='$value' /></div>";
                }

            

            $div.= "</div>";
            $html = "<div class='i18n-field i18n-input-field'>".$ul.$div."</div>";
            return $html;
            }
        }