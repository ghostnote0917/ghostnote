<?php 
session_start();
require_once 'include/DB_CONTENTS.php';
$db = new DB;
$resultSectionInfo = $db->getSectionInfo();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
  	<title>Ghostnote</title>
	<link rel="stylesheet" type="text/css" href="/javascript/common.css">
	<link rel="stylesheet" href="/javascript/normalize.css">
	<link rel="stylesheet" href="/javascript/bamboo.css">
<SCRIPT>
	function move(dest){
		switch(dest){
		case 'aboutUS':
			location.href="/aboutus";
			break;
		case 'archive':
			location.href="/archive";
			break;
		case 'admin':
			location.href="/admin/adminMain";
			break;
		case 'logout':
			location.href="/admin/logout";
			break;
		default:
			location.href="/section?sectionID="+dest;
		}
	}
</SCRIPT>
</head>
<body>
<section class="section_menu" id="layerMenu" name="layerMenu">
	<nav id="main-nav" class="navigation">
		<ul>
	<?php 
			while($result = $resultSectionInfo->fetch_assoc()){
				echo '<li><a onClick="move('.$result['SECTION_ID'].');">'.$result['SECTION_NAME'].'</a></li>';
			}
	?>
			<li><a onClick="move('archive')">Archive</a></li>
			<li><a onClick="move('aboutUS')">About US</a></li>
	<?php 
			if(isset($_SESSION['user']) && $_SESSION['user']='ghostnote') {
				echo '<li><a onClick="move(\'admin\')">Admin</a></li>';
				echo '<li><a onClick="move(\'logout\')">Logout</a></li>';
			}
	?>
		</ul>
	</nav>
</section>

<div class="body">
	<div id="container" class="container">
		<div id="mainHeader" name="mainHeader" class="main_header">
			<header class="primary" name="primaryHeader" id="primaryHeader">
				<div id="primaryMain" name="primaryMain" class="primaryMain">
					<div class="open icon" id="open" name="open"></div>	
					<div class="header_logo">
						<div style="margin:0px auto;">
							<a href='/'><img src="/images/ghostnoe.png" style="height:20px"></a>
						</div>
					</div>
					<div class="search" id="search" name="search"></div>
				</div>
				<form method="get" name="form_search" id="form_search" enctype="multipart/form-data" action="/search" class="formSearch">
					<div id="primarySearch" name="primarySearch" class="primarySearch">
						<button type="button" name="back" id="back" style="display:inline-block; position:absolute;left:0;right:0;background:0;background-image:url(/images/back.png);background-size:25px;border:0px; width:49px; height:49px;background-repeat:no-repeat;outline:0;background-position: 12px;left:5px;"></button>
						<div id="id_area" style="top:8px;left:10px;position:relative;height:35px; display:block; border-bottom:2px solid; border-color:gray;">
		      				<input type="search" id="id" name="id" tabindex="1" accesskey="L" placeholder="검색어를 입력하세요." class="int2">
		      				<button type="button" disabled="" title="delete" id="id_clear" class="wrg2"><span class="blind">삭제</span></button>
		      			</div>
					</div>
				</form>
			</header>
		</div>
		<div id="content" class="content">
<script src="/javascript/common.js"></script>
<script>
addDeleteButtonEvent('id', 'id_clear');

</script>
<script src="/javascript/jquery.js"></script>
<script>
$(document).ready(function(){
	    $("#search").click(function(){
	    	$("#form_search").css("transition-duration","300ms");
	    	$("#form_search").css("transform","translate(0, 50px)");
	    	$("#layerBlank3").css("display","block");
	    	$("#container").css("position","fixed");
	    	$('#id').focus();
	    });
	    $("#back").click(function(){
	    	$("#form_search").css("transition-duration","900ms");
	    	$("#form_search").css("transform","translate(0, -50px)");
	    	$("#layerBlank3").css("display","none");
	    	$("#container").css("position","relative");
	    	$('#id').val("");
	    });
	    $("#layerBlank3").click(function(){
	    	$("#form_search").css("transition-duration","900ms");
	    	$("#form_search").css("transform","translate(0, -50px)");
	    	$("#layerBlank3").css("display","none");
	    	$("#container").css("position","relative");
	    	$('#id').val("");
	    });
});
</script>