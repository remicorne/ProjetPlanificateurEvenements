<?php
header("HTTP/1.0 404 Not Found");
echo "POST"; var_dump($_POST);

echo "FILES"; var_dump($_FILES);
if(isset($exception))
	echo $exception->getMessage();
