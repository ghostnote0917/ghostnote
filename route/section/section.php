<?php
require_once("template/header.php");
// create an object of class DB.

$sectionID = $_REQUEST['sectionID'];
$resultColor = $db->getSectionColor('SECTION',$sectionID);
$resultSection = $db->getContentsList($sectionID);
$cnt=0;
?>
<style>
	.frame_hover-area:hover .frame_border_inline {border: 3px solid <?=$resultColor['COLOR']?>;}
</style>
<div class="contents-section">
	<form name="formContent" id="formContent" action="/contents">
	<input type="hidden" name="contentID" id="contentID">
	
	<?php
		while($result = $resultSection->fetch_assoc()){
			if($cnt==0){
				echo '<div class="contents contents_top">';
				$cnt++;
			} else {
				echo '<div class="contents contents_medium">';
			}
	?>
			<div class="frame_hover-area">
				<div class="frame frame--layout-vertical">
					<div class="frame_main-content" >
						<div class="frame_image-wrapper">
							<div class="image image--placeholder frame_image">
								<?php echo '<img src="' .$result['THUMBNAIL_URL']. '" class="image_inner">' ?>
							</div>
						</div>
						<a class="frame_main-content_cover-link" onClick="loadContent('<?=$result['CONTENT_ID']?>');"></a>
						<div class="frame_border_inline"></div>
						<div class="frame_content_inline">
							<span class="inline_title_top">
								<?php echo $result['TITLE'] ?>
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php 
		}
	?>
	</form>
</div>
<script>
$(document).ready(function(){
	$('#primaryHeader').css("border-bottom","3px solid <?=$resultColor['COLOR']?>");
});

	function loadContent(ID){	
		var contentID = document.getElementById("contentID");
		contentID.value = ID;
		formContent.submit();
	}
</script>

<?php require_once("template/bottom.php"); ?>