<?php
require_once("template/header.php");
// create an object of class DB.
$tags = $_REQUEST['id'];
$resultSearch = $db->getSearchContentsList($tags);
$contentsList="";

while($result = $resultSearch->fetch_assoc()){
	if($contentsList==""){
		$contentsList = $result['CONTENTS_LIST'];
	} else {
		$contentsList = $contentsList.",".$result['CONTENTS_LIST'];
	}
}

if($contentsList==""){
	echo "NO data";
} else {
	$resultSearch = $db->getSearchContentsInfo(true, 0, $contentsList);

}
?>
<div id="contentsSection" name="contentsSection" class="contents-section">
	<form name="formContent" id="formContent" action="/contents">
	<input type="hidden" name="contentID" id="contentID">
	<?php
		while($result = $resultSearch->fetch_assoc()){
			$lastSN = $result['SN'];
			$contentID =$result['CONTENT_ID']; 
			$title = $result['TITLE'];
			$sectionName = $result['SECTION_NAME'];
			$sectionColor = $result['COLOR'];
			$created = $result['CREATED'];
			$thumbnailURL = $result['THUMBNAIL_URL'];
	?>
<style>
	.frame_hover-area:hover .frame_border_inline {border: 3px solid #c2c2c2;}
</style>
		<div class="contents">
			<div class="frame_hover-area">
				<div class="frame frame--layout-horizontal">
					<div class="frame_main-content" >
						<div class="frame_image-wrapper">
							<div class="image image--placeholder frame_image">
								<?php echo '<img src="' .$thumbnailURL. '" class="image_inner">' ?>
							</div>
						</div>
						<a class="frame_main-content_cover-link" onClick="loadContent('<?=$contentID?>');"></a>
						<div class="frame_border_inline"></div>
						<div class="frame_search_inline">
							<div>
								<span class="inline_title_search">
									<?php echo $title ?>
								</span>
							</div>
							<div>
								<span class="">
									<?php echo $sectionName ?>
								</span>
								<span>
									<?php echo $created ?>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php 
		}
	if(isset($lastSN)){
	?>
	<div name="more<?=$lastSN?>" id="more<?=$lastSN?>" style="width:100%">
		<div name="<?=$lastSN?>" id="<?=$lastSN?>" class="moreDiv">
			<div style="width:100px; margin:0px auto;border:1px solid; text-align:center">
				더보기
			</div>
		</div>
	</div>
<?php 		
	}
?>
	</form>
</div>

<script>
$(function(){
	//More Button
	$(document).on("click",".moreDiv", function() 
	{
		var lastSN = $(this).attr("id");
		if(lastSN)
		{
			$.ajax({
			type: "POST",
			url: "/search/searchMore",
			data: "lastSN="+lastSN+"&contentsList=<?=$contentsList?>", 
			cache: false,
			success: function(html){
				$("#formContent").append(html);
				$("#more"+lastSN).remove();
			}
			});
		}
		else
		{
			//$("#more"+lastSN).remove();
			//alert("끝");
		}
		return false;
	});
});

function loadContent(ID){	
	var contentID = document.getElementById("contentID");
	contentID.value = ID;
	formContent.submit();
}
</script>
<?php require_once("template/bottom.php"); ?>