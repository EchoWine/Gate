<?php
		
function check_error($errno, $str, $file, $line, $context = null){
	error($errno, $str, $file, $line);
}

function check_exception($e){

	error(get_class($e),$e->getMessage(),$e->getFile(),$e->getLine());
    error_backtrace();
    die();
}

function check_fatal(){
    $error = error_get_last();
    if($error["type"] == E_ERROR)
        check_error($error["type"],$error["message"],$error["file"],$error["line"]);
}


function error($type,$message,$file,$line){
	echo "
    	<h1>Error</h1>
   		<b>Type</b>: $type <br>
    	<b>Message</b>: $message <br>
    	<b>File</b>: $file <br>
    	<b>Line</b>: $line<br>
    ";

}

function error_backtrace(){

    echo "
        <h2>Debug Backtrace</h2>
    ";

    $trace = debug_backtrace();

    $st = '';
    $f = false;
    foreach($trace as $k){

        if(isset($k['file']) && $k['file'] != __FILE__){
            $class = isset($k['class']) ? $k['class'] : '';
            $function = isset($k['function']) ? $k['function'] : '';
            echo "
                <b>File</b>: {$k['file']}<br>
                <b>Line</b>: {$k['line']}<br>
                <b>Class</b>: {$class}<br>
                <b>Function</b>: {$function}<br>
                <br>
            ";
            $f = true;
        }
    }
    if(!$f) echo "The debug backtrace is empty!";
    echo "</div>";
}

error_reporting(-1); 

set_error_handler("check_error");
set_exception_handler("check_exception");
//register_shutdown_function("check_fatal");
?>