<?php
	var $output=null;
	var $retval=null;
	exec("whoami", $output, $retval);
	echo $output;
?>
