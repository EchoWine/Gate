<?php
	include 'inc.php';
	include TemplateEngine::html('index');
	
	echo "<script>console.log('Tempo di esecuzione: ".(microtime(true) - $s)."');</script>";
?>