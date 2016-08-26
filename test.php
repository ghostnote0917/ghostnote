<?php 

$fileExtArr = explode("?", "abce?def");

if(isset($fileExtArr[1])){
	echo end($fileExtArr);
}



?>