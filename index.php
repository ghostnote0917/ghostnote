<?php
 if (isset($_REQUEST['handler'])){
 	 
     if (file_exists("route/" . $_REQUEST['handler'] . ".php")) {
     	//URL의 마지막 파라미터.php 파일이 존재 하는 경우
         require_once("route/" . $_REQUEST['handler'] . ".php");
     } else  if (file_exists("route/" . $_REQUEST['handler'] . "/index.php")) {
     	//URL의 마지막 파라미터의 디렉토리에 index.php가 존재 하는경우
 			require_once("route/" . $_REQUEST['handler'] . "/index.php");
 	 } else  if (file_exists("route/" . $_REQUEST['handler'] .'/'. $_REQUEST['handler'] . ".php")) {
		//URL의 마지막 파라미터의 디렉토리에 index.php가 존재 하는경우
			require_once("route/" . $_REQUEST['handler'] .'/'. $_REQUEST['handler'] . ".php");
     } else if (file_exists("route/" . $_REQUEST['handler'])) {
     	//URL과 동일한 파일명이 존재 하는 경우
         require_once("route/" . $_REQUEST['handler']);
     } else {
		$fileExtArr = explode("/", $_REQUEST['handler']);
		if(isset($fileExtArr[1])){
			$_REQUEST['handler'] = end($fileExtArr);
			require_once("route/contents/contents.php");
		} else {
			require_once("route/contents/contents.php");
		}
     }
 } else {
 	require_once("route/ghostnote.php");
     //header("Location: /ghostnote");
     exit();
 }
 
 
