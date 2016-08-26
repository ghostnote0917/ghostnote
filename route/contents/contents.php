<?php 
require_once("template/header.php");

if(isset($_REQUEST['contentID'])){
	$contentID = $_REQUEST['contentID'];
} else {
	$contentID = $_REQUEST['handler'];
}

$fbLikePath = 'http://www.ghostnote.co.kr/'.$contentID;
$resultColor = $db->getSectionColor('CONTENT', $contentID);
$result = $db->getContentDetail($contentID);

if(isset($result)){
	$contentType = $result['CONTENT_TYPE'];
	$title = $result['TITLE'];
	$description = $result['DESCRIPTION'];
	$tags = $result['TAGS'];
	$thumbURL = $result['THUMBNAIL_URL'];
	$videoPath = $result['VIDEO_PATH'];
	$created = $result['CREATED'];
} else {
	echo 'No contents.';
}
?>
<!-- facebook Like API -->
<div id="fb-root"></div>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ko_KR/sdk.js#xfbml=1&version=v2.7";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<form name="formContent" id="formContent" method="post" action="/admin/modifyContent">
<input hidden id="contentID" name="contentID" value="<?=$contentID?>">
<input hidden id="admin" name="admin">
<div class="contents-section">
	<div class="contents contents_top">
		<div class="thumbImage" id="divThumb" name="divThumb">
			<div class="frame_image-wrapper">
				<div class="image image--placeholder frame_image">
					<img src="<?=$thumbURL?>" class="image_inner">
				</div>
<?php 
				if($contentType=="VIDEO"){
					echo '<div class="frame_play" id="divPlay" name="divPlay">';
					echo '<a id="videoPlay" name="videoPlay" class="roll"><img src="/images/play.png" class="image_play"><img src="/images/play_roll.png" class="image_play over"></a>';
					echo '</div>';
				}
?>
				<div class="frame_content_inline">
					<span class="inline_title_top">
						<?=$title?>
					</span>
				</div>
			</div>
		</div>
		<div>
			<div id="divYoutubePlayer" name="divYoutubePlayer">
		    	<div id="player"></div>
			</div>
		</div>
		<div class="frame frame--layout-vertical" style="top:4px;">
			<div>
				<div style="display: inline-block;">
<?php
	if(isset($_SESSION['user']) && $_SESSION['user']='ghostnote') {
		echo '	<div class="admin_button_div">';
		echo '			<a class="button_delete" onClick="adminCall(\'delete\');">';
		echo '				<span>삭 제</span>';
		echo '			</a>';
		echo '	</div>';
		echo '	<div class="admin_button_div">';
		echo '			<a class="button_modify" onClick="adminCall(\'modify\');">';
		echo '				<span>수 정</span>';
		echo '			</a>';
		echo '	</div>';
	}
?>
				</div>
				<div  class="fb-like_wrap">
					<span class="fb-like" data-href="<?=$fbLikePath?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></span>
				</div>
			</div>
			<div class="description">
				<div>
					<?=$description?>
				</div>
			</div>
		</div>
		<div class="fb-comments_wrap">
			<div class="fb-comments" data-href="<?=$fbLikePath?>" data-width="100%" data-numposts="3"></div>
		</div>
	</div>
</div>
</form>
<script>

function adminCall(input){
	if(input=='delete'){
		var confirmYn = confirm('현재 보고계신 게시물을 삭제하시겠습니까?');
		if(!confirmYn){
			return;
		}
	} else if(input=='modify'){
		var confirmYn = confirm('현재 보고계신 게시물을 수정하시겠습니까?');
		if(!confirmYn){
			return;
		}
	}
	var admin = document.getElementById("admin");
	admin.value = input;
	formContent.submit();
}

$(document).ready(function(){
	
	$('#primaryHeader').css("border-bottom","3px solid <?=$resultColor['COLOR']?>");
	$('#divYoutubePlayer').css("display","none");
<?php
	if($contentType == "VIDEO"){
	    echo '$("#videoPlay").click(function(){ ';
		echo '   $("#divYoutubePlayer").css("display","block");';
		echo '   $("#divThumb").css("display","none");';
	    echo  '});';
	}
?>
});

// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');

tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
function onYouTubeIframeAPIReady() {
  player = new YT.Player('player', {
    width: '100%',
    videoId: '<?=$videoPath?>',
    playerVars: {'showinfo': 0, 'controls':1},
    events: {
      'onReady': onPlayerReady,
      'onStateChange': onPlayerStateChange
    }
  });
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
  //event.target.playVideo();
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.
var done = false;
function onPlayerStateChange(event) {
}
function stopVideo() {
  player.stopVideo();
}
function play(){
	player.playVideo();
}
</script>
<?php
require_once("template/bottom.php");
?>