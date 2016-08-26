<?php
require_once 'include/DB_CONTENTS.php';
// OAUTH Configuration
$oauthClientID = '600279212436-aeqmsrgb16vhg3c7hpqm5780fs5etcis.apps.googleusercontent.com';
$oauthClientSecret = 'eJmXzyiKxLE2qZIeNqb7jnC1';
$baseUri = 'http://www.ghostnote.co.kr/admin/uploadContent';
$redirectUri = 'http://www.ghostnote.co.kr/admin/uploadContentProc';

define('OAUTH_CLIENT_ID',$oauthClientID);
define('OAUTH_CLIENT_SECRET',$oauthClientSecret);
define('REDIRECT_URI',$redirectUri);
define('BASE_URI',$baseUri);

// Include google client libraries
require_once 'Google/src/Google/autoload.php';
session_start();
if(!isset($_SESSION['user'])){
	echo "<script>alert('You're not Authorized. Please check.') \n";
	echo "window.location = '/admin'";
	echo "</script>";
}
$client = new Google_Client();
$client->setClientId(OAUTH_CLIENT_ID);
$client->setClientSecret(OAUTH_CLIENT_SECRET);
$client->setScopes('https://www.googleapis.com/auth/youtube');
$client->setRedirectUri(REDIRECT_URI);
// Define an object that will be used to make all API requests.
$youtube = new Google_Service_YouTube($client);


$shuffleString = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$htmlBody = "";
$db = new DB;

// Thumbnail Image Insert
if(isset($_REQUEST['contentType'])){

	$sectionID = $_REQUEST['sectionID'];
	$videoFilePath = null;
	$contentType = $_REQUEST['contentType'];
	$contentID = substr(str_shuffle($shuffleString),0,12);
	$title = $_REQUEST['title'];
	$description = $_REQUEST['description'];
	$tags = $_REQUEST['tags'];
	$privateYn = $_REQUEST['privateYn'];
	$status = "1";
	
	$thumbFileName = $_FILES["thumbfile"]["name"];
	$fileExtArr = explode(".", $thumbFileName);

	$fileSize = $_FILES['thumbfile']['size'];
	$fileType = $_FILES['thumbfile']['type'];
	
	$fileName = $contentID .'.' .end($fileExtArr);
	$uploadDir = "/Mgmt/upload/thumb/";
	$targetDir = "/upload/thumb/";
	$targetFile = $uploadDir . $fileName;
	$allowedTypeArr = array("image/jpeg", "image/jpg", "image/png", "image/gif");
	if(in_array($fileType, $allowedTypeArr)) {
		if(move_uploaded_file($_FILES['thumbfile']['tmp_name'], $targetFile)) {
			$thumbFileURL = $targetDir.$fileName;
		}else{
			// header('Location:'.BASE_URI.'index.php?err=ue');
			exit;
		}
	}

	$tagsArr = explode(",", $tags);
	$newTags = "";

	// Video contents uploading
	if($contentType == 'VIDEO'){
		$status = "0";
		$videoFileName = $_FILES["videofile"]["name"];
		$fileName = $contentID.'-'.basename($_FILES["videofile"]["name"]);
		$fileType = $_FILES['videofile']['type'];
		$targetDir = "/Mgmt/upload/videos/";
		$targetFile = $targetDir . $fileName;
	}

	for($i=0;$i<count($tagsArr);$i++){
		$isSame = false;
		$tagI = trim($tagsArr[$i]);
		if($tagI==""){
			continue;
		}
		for($j=$i+1;$j<count($tagsArr);$j++){
			$tagJ = trim($tagsArr[$j]);
			if($tagJ==""){
				continue;
			}
			if($tagI == $tagJ){
				$isSame = true;
			}
		}
		if(!$isSame){
			if($newTags==""){
				$newTags =  $tagI;
			} else {
				$newTags = $newTags .",". $tagI;
			}

			$result = $db->getTagList($tagI);
			if(!$result){
				$db->dbRollback;
				echo "<SCRIPT>alert('등록 중 오류가 발생 했습니다.(getTagList)');</SCRIPT>";
			}
			$contentsList = $result->fetch_assoc();
			if($contentsList)
			{
				$newContentsList = $contentsList['CONTENTS_LIST'].",".$contentID;
				$tagUpdateResult = $db->updateTagsContentsList($tagI, $newContentsList);
				if(!$tagUpdateResult){
					$db->dbRollback;
					echo "<SCRIPT>alert('등록 중 오류가 발생 했습니다.(updateTagsContentsList)');</SCRIPT>";
				}
			} else {
				//tag 정보가 존재하지 않으니까 새로운 tag 등록
				$tagUpdateResult = $db->setNewTags($tagI, $contentID);
				if(!$tagUpdateResult){
					$db->dbRollback;
					echo "<SCRIPT>alert('등록 중 오류가 발생 했습니다.(setNewTags)');</SCRIPT>";
				}
			}
		}
	}

	// create an object of class DB.
	$result = $db->setNewContent($sectionID, $contentType,$contentID,$title,$description,$newTags,$privateYn,$thumbFileURL,$videoFilePath,$status);
	if(!$result){
		$db->dbRollback;
		echo "<SCRIPT>alert('등록 중 오류가 발생 했습니다.(setNewContent)');</SCRIPT>";
	}
}
	// get last video data
	$result = $db->getLastRecentforUpload();
	if(!$result){
		$db->dbRollback;
		echo "<SCRIPT>alert('등록 중 오류가 발생 했습니다.(getLastRecentforUpload)');</SCRIPT>";
	}
	
	if($result['CONTENT_TYPE']=="VIDEO"){
		if (isset($_GET['code'])) {
			if (strval($_SESSION['state']) != strval($_GET['state'])) {
				@unlink($result['VIDEO_PATH']);
				die('The session state did not match.');
			}
			$client->authenticate($_GET['code']);
			$_SESSION['token'] = $client->getAccessToken();
		}
		if (isset($_SESSION['token'])) {
			$client->setAccessToken($_SESSION['token']);
		}
	
		if ($client->getAccessToken()) {
			try{
				// REPLACE this value with the path to the file you are uploading.
				$videoPath = $result['VIDEO_PATH'];
		
				// Create a snippet with title, description, tags and category ID
				// Create an asset resource and set its snippet metadata and type.
				// This example sets the video's title, description, keyword tags, and
				// video category.
				$snippet = new Google_Service_YouTube_VideoSnippet();
				$snippet->setTitle($result['TITLE']);
				$snippet->setDescription('http://www.ghostnote.co.kr');
				$snippet->setTags(explode(",",$result['TAGS']));
		
				// Numeric video category. See
				// https://developers.google.com/youtube/v3/docs/videoCategories/list
				$snippet->setCategoryId("22");
	
				// Set the video's status to "public". Valid statuses are "public",
				// "private" and "unlisted".
				$status = new Google_Service_YouTube_VideoStatus();
				if($result['PRIVATE_YN']=='Y'){
					$status->privacyStatus = "private";
				} else {
					$status->privacyStatus = "public";
				}
		
				// Associate the snippet and status objects with a new video resource.
				$video = new Google_Service_YouTube_Video();
				$video->setSnippet($snippet);
				$video->setStatus($status);
		
				// Specify the size of each chunk of data, in bytes. Set a higher value for
				// reliable connection as fewer chunks lead to faster uploads. Set a lower
				// value for better recovery on less reliable connections.
				$chunkSizeBytes = 1 * 1024 * 1024;
		
				// Setting the defer flag to true tells the client to return a request which can be called
				// with ->execute(); instead of making the API call immediately.
				$client->setDefer(true);
		
				// Create a request for the API's videos.insert method to create and upload the video.
				$insertRequest = $youtube->videos->insert("status,snippet", $video);
		
				// Create a MediaFileUpload object for resumable uploads.
				$media = new Google_Http_MediaFileUpload(
						$client,
						$insertRequest,
						'video/*',
						null,
						true,
						$chunkSizeBytes
						);
				$media->setFileSize(filesize($videoPath));
		
				// Read the media file and upload it.
				$status = false;
				$handle = fopen($videoPath, "rb");
				while (!$status && !feof($handle)) {
					$chunk = fread($handle, $chunkSizeBytes);
					$status = $media->nextChunk($chunk);
				}
				fclose($handle);
		
				// If you want to make other calls after the file upload, set setDefer back to false
				$client->setDefer(false);
		
				// Update youtube video ID to database
				$db->updateVideoContent($result['CONTENT_ID'],$status['id']);
				// delete video file from local folder
				@unlink($result['VIDEO_PATH']);
				$db->dbCommit();
				echo "<SCRIPT>alert('작성하신 게시물이 등록되었습니다.');";
				echo "location.href='/contents?contentID=".$result['CONTENT_ID']."'";
				echo "</SCRIPT>";
	
			} catch (Google_ServiceException $e) {
				$htmlBody .= sprintf('<p>A service error occurred: <code>%s</code></p>',
						htmlspecialchars($e->getMessage()));
			} catch (Google_Exception $e) {
				$htmlBody .= sprintf('<p>An client error occurred: <code>%s</code></p>', htmlspecialchars($e->getMessage()));
				$htmlBody .= 'Please reset session <a href="/admin/logout">Logout</a>';
			}
		
			$_SESSION['token'] = $client->getAccessToken();
		} else {
			// If the user hasn't authorized the app, initiate the OAuth flow
			$state = mt_rand();
			$client->setState($state);
			$_SESSION['state'] = $state;
			$authUrl = $client->createAuthUrl();
			$htmlBody = <<<END
			<h1><a href="$authUrl">인증하고 동영상 올리기</a></h1>
END;
		}
	} else {
		$db->dbCommit();
		echo "<SCRIPT>alert('작성하신 게시물이 등록되었습니다.');";
		echo "location.href='/contents?contentID=".$contentID."'";
		echo "</SCRIPT>";
	}
	$db->dbClose();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
<link rel="stylesheet" type="text/css" href="/javascript/common.css">
</head>
<body>
<div class="youtube-box">
	<div class="content">
		<?php echo $htmlBody; ?>
	</div>
</div>
</div>
</body>
</html>