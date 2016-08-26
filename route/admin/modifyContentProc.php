<?php
require_once 'include/DB_CONTENTS.php';

// OAUTH Configuration
$oauthClientID = '600279212436-aeqmsrgb16vhg3c7hpqm5780fs5etcis.apps.googleusercontent.com';
$oauthClientSecret = 'eJmXzyiKxLE2qZIeNqb7jnC1';
$baseUri = 'http://www.ghostnote.co.kr/admin/modifyContent';
$redirectUri = 'http://www.ghostnote.co.kr/admin/modifyContentProc';

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
$htmlBody = "";
$db = new DB;

// Thumbnail Image Insert
if(isset($_REQUEST['contentID'])){
	$contentID = $_REQUEST['contentID'];
	$sectionID = $_REQUEST['sectionID'];
	$title = $_REQUEST['title'];
	$description = $_REQUEST['description'];
	$tags = $_REQUEST['tags'];
	$privateYn = $_REQUEST['privateYn'];
	$thumbFileURL = $_REQUEST['thumbURL'];

	$thumbFileName = $_FILES["thumbfile"]["name"];
	if($thumbFileName){
		$fileExtArr = explode(".", $thumbFileName);
		
		$fileSize = $_FILES['thumbfile']['size'];
		$fileType = $_FILES['thumbfile']['type'];
		
		$fileName = $contentID .'.' .end($fileExtArr);
		$uploadDir = "/Mgmt/upload/thumb/";
		$targetDir = "/upload/thumb/";
		$targetFile = $uploadDir . $fileName;
		$allowedTypeArr = array("image/jpeg", "image/jpg", "image/png", "image/gif");
		@unlink('/Mgmt'.$thumbFileURL);
		
		if(in_array($fileType, $allowedTypeArr)) {
			if(move_uploaded_file($_FILES['thumbfile']['tmp_name'], $targetFile)) {
				$thumbFileURL = $targetDir.$fileName;
			}else{
				// header('Location:'.BASE_URI.'index.php?err=ue');
				exit;
			}
		}
	}
	
	$contentOriginDetailResult = $db->getContentDetail($contentID);
	if(!$contentOriginDetailResult){
		$db->dbRollback;
		echo "<SCRIPT>alert('수정 중 오류가 발생 했습니다.(getContentDetail)');</SCRIPT>";
	}
	$tagsArr = explode(",", $tags);
	$newTags = "";
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
		}
	}
	
	$result = $db->modifyContent($contentID, $sectionID,$title,$description,$newTags,$privateYn,$thumbFileURL);
	if(!$result){
		$db->dbRollback;
		echo "<SCRIPT>alert('수정 중 오류가 발생 했습니다.(modifyContent)');</SCRIPT>";
	}
	
	// tag 정보 수정
	$originTagsArr = explode(",", $contentOriginDetailResult['TAGS']);
	$newTagsArr = explode(",", $tags);

	//Orgin -> New 비교
	for ($i = 0; $i < count($originTagsArr); $i++ ) {
		$originTag = trim($originTagsArr[$i]);
		$isSame = false;
		for ($j = 0; $j < count($newTagsArr); $j++ ) {
			$newTag = trim($newTagsArr[$j]);
			if($originTag==$newTag){
				$isSame = true;
			}
		}

		if(!$isSame){
			$result = $db->getTagList($originTag);
			$contentsList = $result->fetch_assoc();
			$contentsListArr = explode("," , $contentsList['CONTENTS_LIST']);
			$newContentsList = "";
			if(count($contentsListArr)>1){
				for($j=0;$j<count($contentsListArr);$j++){
					if($contentID == $contentsListArr[$j]){
						continue;
					}
					if($newContentsList==""){
						$newContentsList = $contentsListArr[$j];
					} else {
						$newContentsList = $newContentsList . "," .$contentsListArr[$j];
					}
				}
			}
			$tagUpdateResult = $db->updateTagsContentsList($originTag, $newContentsList);
		}
	}
	
	for ($i = 0; $i < count($newTagsArr); $i++ ) {
		$newTag = trim($newTagsArr[$i]);
		$isSame = false;
		if($newTag=="" ){
			continue;
		}
		for ($j = 0; $j < count($originTagsArr); $j++ ) {
			$originTag = trim($originTagsArr[$j]);
			if($originTag=="" ){
				continue;
			}
			if($newTag==$originTag){
				$isSame = true;
			}
		}
		if(!$isSame){
			$result = $db->getTagList($newTag);
			$contentsList = $result->fetch_assoc();
			if($contentsList){
				if($contentsList['CONTENTS_LIST']==""){
					$newContentsList = $contentID;
				} else {
					$newContentsList = $contentsList['CONTENTS_LIST'].",".$contentID;
				}
				$tagUpdateResult = $db->updateTagsContentsList($newTag, $newContentsList);
				if(!$tagUpdateResult){
					$db->dbRollback;
					echo "<SCRIPT>alert('등록 중 오류가 발생 했습니다.(updateTagsContentsList)');</SCRIPT>";
				}
			} else {
				$tagUpdateResult = $db->setNewTags($newTag, $contentID);
				if(!$tagUpdateResult){
					$db->dbRollback;
					echo "<SCRIPT>alert('등록 중 오류가 발생 했습니다.(setNewTags)');</SCRIPT>";
				}
			}
		}
	}
}
	// get last data
	$result = $db->getLastRecentUpdated();
	if(!$result){
		$db->dbRollback;
		echo "<SCRIPT>alert('수정 중 오류가 발생 했습니다.(getLastRecentUpdated)');</SCRIPT>";
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
				$videoId = $result['VIDEO_PATH'];
		
				// Call the API's videos.list method to retrieve the video resource.
				$listResponse = $youtube->videos->listVideos("status, snippet",
						array('id' => $videoId));
				
				// If $listResponse is empty, the specified video was not found.
				if (empty($listResponse)) {
					$htmlBody .= sprintf('<h3>Can\'t find a video with video id: %s</h3>', $videoId);
				} else {
					// Since the request specified a video ID, the response only
					// contains one video resource.
					$video = $listResponse[0];
					$videoSnippet = $video['snippet'];
					$videoStatus = $video['status'];
					$tags = explode(",",$result['TAGS']);
					
					// Set the tags array for the video snippet
					$videoSnippet['title'] = $result['TITLE'];
					$videoSnippet['tags'] = $tags;
 					if($result['PRIVATE_YN']=='Y'){
						$videoStatus['privacyStatus'] = "private";
					} else {
						$videoStatus['privacyStatus'] = "public";
					}
					
					// Update the video resource by calling the videos.update() method.
					$updateResponse = $youtube->videos->update("status, snippet", $video);
					
					$responseTags = $updateResponse['status']['privacyStatus'];
					$db->dbCommit();
					echo "<SCRIPT>alert('수정하신 게시물이 저장되었습니다.');";
					echo "location.replace('/contents?contentID=".$result['CONTENT_ID']."')";
					echo "</SCRIPT>";
				}
	
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
			<h3>Authorization Required</h3>
			<p>You need to <a href="$authUrl">authorize access</a> before proceeding.<p>
END;
		}
	} else {
		$db->dbCommit();
		echo "<SCRIPT>alert('수정하신 게시물이 저장되었습니다.');";
		echo "location.replace('/contents?contentID=".$contentID."')";
		echo "</SCRIPT>";
	}
	$db->dbClose();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
<title>Upload video to YouTube using PHP</title>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>
<body>
<div class="youtube-box">
	<h1>Upload video to YouTube using PHP</h1>
	<div class="content">
		<?php echo $htmlBody; ?>
	</div>
</div>
</div>
</body>
</html>