<?php
require_once 'include/DB_CONTENTS.php';
$db = new DB;
$lastSN=$_POST['lastSN'];
$resultSearch = $db->getArchives(false, $lastSN);
$resultLastSN = 0;
$fetchCount=0;
while($result = $resultSearch->fetch_assoc()){
	$fetchCount++;
	$resultLastSN = $result['SN'];
	$contentID =$result['CONTENT_ID'];
	$title = $result['TITLE'];
	$sectionName = $result['SECTION_NAME'];
	$sectionColor = $result['COLOR'];
	$created = $result['CREATED'];
	$thumbnailURL = $result['THUMBNAIL_URL'];
?>
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
if($fetchCount==6 && $resultLastSN != 0){
?>
<div name="more<?=$resultLastSN?>" id="more<?=$resultLastSN?>" style="position:relative; display:block;">
	<input type="button" value="더보기" class="more" name="<?=$resultLastSN?>" id="<?=$resultLastSN?>">
</div>
<?php 
}	
?>