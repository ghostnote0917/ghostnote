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


</head>
<body>

<div class="body">
	<div id="container" class="container">

		<div id="content" class="content">
<script src="/javascript/common.js"></script>
<script src="/javascript/jquery.js"></script>

<?php 
$resultSearch = $db->getArchives(true, 0);
?>
<div id="contentsSection" name="contentsSection" class="contents-section">
	
		<input type="hidden" name="contentID" id="contentID">
	</form>
	<?php
		while($result = $resultSearch->fetch_assoc()){
			$lastSN = $result['SN'];
	?>
		<div class="contents contents_medium">
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
	<div name="more<?=$lastSN?>" id="more<?=$lastSN?>" style="width:100%;height:30px;">
		<div name="<?=$lastSN?>" id="<?=$lastSN?>" class="moreDiv">
				<span classs="inline_title_top">더보기</span>
		</div>
	</div>
</div>

		</div>
	</div>
	<div class="layer_Blank" id="layerBlank" name="layerBlank"></div>
	<div class="layer_Blank2" id="layerBlank2" name="layerBlank2"></div>
	<div class="layer_Blank3" id="layerBlank3" name="layerBlank3"></div>
</div>
<script src="/javascript/bamboo.0.1.js"></script>

<script>
	var site = new Bamboo();
</script>
</body>
</html>