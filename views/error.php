<?php
header("HTTP/1.0 404 Not Found");

if(isset($exception))
	echo $exception ;
