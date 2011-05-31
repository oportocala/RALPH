<?
/**
 * jquery style extend, merges arrays (without errors if the passed values are not arrays)
 *
 * @return array $extended
 **/
function array_extend($a, $b) {
    foreach($b as $k=>$v) {
        if( is_array($v) ) {
            if( !isset($a[$k]) ) {
                $a[$k] = $v;
            } else {
                $a[$k] = array_extend($a[$k], $v);
            }
        } else {
            $a[$k] = $v;
        }
    }
    return $a;
	}


function invert_keys($arr){
	$a = array();
	foreach($arr as $key=>$value){
		$a[$value] = $key;
		}
	return $a;
	}
	
	
function array_remove_empty($arr){
	$tmp = array();
	foreach($arr as $k){
		if($k) $tmp []= $k;
		}
	return $tmp;
	}