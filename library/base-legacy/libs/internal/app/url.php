<?

/* Redirect functions */
function redirect($url){
	if(!output_sent())
		headerRedirect($url);
	else
		jsRedirect($url);
	}

function output_sent(){
    if (!headers_sent() && error_get_last()==NULL ) {
        return false;
    	}
		
    return true;
	}

function headerRedirect($url){
	header("Location: $url");
	exit;
	}

function jsRedirect($url, $timer = ""){
	if($timer){
		$timer *= 1000; //  seconds
		echo "<script language='javascript'>
		function redir(){document.location='$url'}
		setTimeout(redir, $timer);
		</script>";
		exit;
		}
	echo "<script language='javascript'>document.location='$url'</script>";
	exit();
	}
	
/* End redirect functions */