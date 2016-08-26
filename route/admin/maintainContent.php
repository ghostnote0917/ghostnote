<?php
	require_once("template/header.php");
	
	// create an object of class DB.
	$db = new DB;
	
	$resultSectionInfo = $db->getSectionInfo();
	$resultLastRecentContent = $db->getAllContents();
	if($resultLastRecentContent){
?>

<div class="contents-section">
	<form name="formContentSection" id="formContentSection" action="/section">
	<input type="hidden" name="sectionID" id="sectionID">
	<div class="contents contents_top" >
		<div class="frame_hover-area">
			<div class="frame frame--layout-vertical">
				<div class="frame_main-content" >
					<div class="frame_image-wrapper">
						<div class="image image--placeholder frame_image">
							<?php echo '<img src="' .$resultLastRecentContent['THUMBNAIL_URL']. '" class="image_inner">' ?>
						</div>
					</div>
					<a class="frame_main-content_cover-link" onClick="loadSection('<?=$resultLastRecentContent['SECTION_ID']?>');"></a>
					<div class="frame_border_inline_top"></div>
					<div class="frame_content_inline">
						<div>
							<span style="color: white; background-color:<?=$resultLastRecentContent['COLOR']?>; padding:3px 3px;">
								<?php echo $resultLastRecentContent['SECTION_NAME'] ?>
							</span>
						</div>
						<span class="inline_title_top">
							<?php echo $resultLastRecentContent['TITLE'] ?>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	}
	while($result = $resultSectionInfo->fetch_assoc()){
		$resultLastSectionContent = $db->getLastSectionContent($result['SECTION_ID']);
		if(!$resultLastSectionContent){
			continue;
		}
	?>
	<style>
		.frame_hover-area .a<?=$resultLastSectionContent['SECTION_ID']?>{position: absolute;left: 3%;top: 2%;bottom: 2%;right: 3%;border: 1px solid #c2c2c2;
        		         z-index: 1000;-webkit-transition: border-color 0.3s ease;transition: border-color 0.3s ease;margin: 0 0;
                 		margin: 5px 5px;;pointer-events: none;-webkit-transition: opacity 300ms;transition: opacity 300ms;opacity: 1;}
		.frame_hover-area:hover .a<?=$resultLastSectionContent['SECTION_ID']?> {border: 3px solid <?=$resultLastSectionContent['COLOR']?>;}
	</style>
	
	<div class="contents contents_medium" >
		<div class="frame_hover-area">
			<div class="frame frame--layout-vertical">
				<div class="frame_main-content" >
					<div class="frame_image-wrapper">
						<div class="image image--placeholder frame_image">
							<?php echo '<img src="' .$resultLastSectionContent['THUMBNAIL_URL']. '" class="image_inner">' ?>
						</div>
					</div>
					<a class="frame_main-content_cover-link" onClick="loadSection('<?=$resultLastSectionContent['SECTION_ID']?>');"></a>
					<div class="a<?=$resultLastSectionContent['SECTION_ID']?>"></div>
					<div class="frame_content_inline">
						<span class="inline_section_bottom" style="color:<?=$resultLastSectionContent['COLOR']?>;">
							<?php echo $result['SECTION_NAME'] ?>
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
	function loadSection(section){
		var sectionID = document.getElementById("sectionID");
		sectionID.value = section;
		formContentSection.submit();
	}
</script>
<?php require_once("template/bottom.php"); ?>