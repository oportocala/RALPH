<?

function define_layout_constants($layout_name){
	global $APP;
	
	define('LAYOUT', $layout_name);
	
	define('WWW_LAYOUT', 	WWW_LAYOUTS.RS.LAYOUT.RS);
	define('LAYOUT_PATH', 	LAYOUTS_PATH.LAYOUT.DS);
	
	define('PAGE_PATH', PAGES_PATH.$layout_name.DS);
	if(LAYOUT != 'frontend'){
		define('WWW_PAGE',  WWW_ROOT.LAYOUT.RS);
	}else{
		define('WWW_PAGE',  WWW_ROOT);
		}

	$adm_break  = explode(RS, WWW_PAGE);
	$page_break = explode(RS, CURRENT_URL);
	$diff_break = array_diff_assoc( $page_break, $adm_break);
	
	define('CURRENT_PAGE_URL', implode(RS, $diff_break));
	$params = http_build_query($_GET, '','&');
	$params = $params?'?'.$params:'';
	define('CURRENT_PAGE_URI', 	CURRENT_PAGE_URL.$params);
	define('CURRENT_PAGE', 		CURRENT_PAGE_URL);
	define('CURRENT_PAGE_PATH',     dirname(PAGE_PATH.CURRENT_PAGE).DS);
	
	$APP['layout'] = array(
		'name'	=> LAYOUT,
		'www' 	=> WWW_LAYOUT,
		'path' 	=> LAYOUT_PATH
		);
		
	$APP['page'] = array(
		'path'	=> PAGE_PATH,
		'www'	=> WWW_PAGE,
		'url'	=> array(
			'url'	=> CURRENT_PAGE_URL,
			'uri'	=> CURRENT_PAGE_URI
			)
		);
		
	}