<?php
class DB {
	function __construct(){
		// Database configuration
		$dbHost = '172.31.15.17';
		$dbUsername = 'ghostnote';
		$dbPassword = 'ghostnote';
		$dbName = 'ghostnote';
		
		// Connect database
		$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
		if(mysqli_connect_errno($conn)){
			die("Failed to connect with MySQL: " . $conn->connect_error);
		}else{
			$this->db = $conn;
		}
	}

    function dbClose(){
            mysqli_close($this->db);
            return true;
    }
    function dbCommit(){
    	mysqli_commit($this->db);
    	return true;
    }
    function dbRollback(){
    	mysqli_rollback($this->db);
    	return true;
    }

    function getSectionInfo(){
    	$tableName = 'sectioninfo';
    	$sql  = "";
    	$sql .= "SELECT SECTION_ID \n";
    	$sql .= "      ,SECTION_NAME \n";
    	$sql .= "  FROM " .$tableName. " \n";
    	if(!isset($_SESSION['user'])){
	    	$sql .= " WHERE PUBLIC_YN = 'Y' \n";
    	} else if($_SESSION['user'] != 'ghostnote'){
    		$sql .= " WHERE PUBLIC_YN = 'Y' \n";
    	}
   		$query = $this->db->query($sql);
    	if($query){
    		return $query;
    	}else{
    		return false;
    	}
    }
	
    function getLastRecentContent(){
    	$tableName1 = 'contents';
    	$tableName2 = 'sectioninfo';

    	$sql  = "";
    	$sql .= "SELECT A.SECTION_ID \n";
    	$sql .= "      ,B.SECTION_NAME \n";
    	$sql .= "      ,B.COLOR \n";
    	$sql .= "      ,A.CONTENT_TYPE \n";
    	$sql .= "      ,A.CONTENT_ID \n";
    	$sql .= "      ,A.TITLE \n";
    	$sql .= "      ,A.THUMBNAIL_URL \n";
    	$sql .= "  FROM " .$tableName1. " A \n";
    	$sql .= "      ," .$tableName2. " B \n";
    	$sql .= " WHERE A.SECTION_ID = B.SECTION_ID \n";
    	if(!isset($_SESSION['user']) || $_SESSION['user']!='ghostnote'){
    		$sql .= "   AND A.PRIVATE_YN = 'N' \n";
    		$sql .= "   AND B.PUBLIC_YN = 'Y' \n";
    	}
    	$sql .= "   AND A.STATUS = '1' \n";
    	$sql .= " ORDER BY SN DESC LIMIT 1";
    	$query = $this->db->query($sql);
    	if($query){
    		$result = $query->fetch_assoc();
    		return $result;
    	}else{
    		return false;
    	}
    }
    
    function getLastRecentforUpload(){
    	$tableName1 = 'contents';
    	$tableName2 = 'sectioninfo';
    
    	$sql  = "";
    	$sql .= "SELECT A.CONTENT_TYPE \n";
    	$sql .= "      ,A.CONTENT_ID \n";
    	$sql .= "      ,A.TITLE \n";
    	$sql .= "      ,A.THUMBNAIL_URL \n";
    	$sql .= "      ,A.VIDEO_PATH \n";
    	$sql .= "      ,A.PRIVATE_YN \n";
    	$sql .= "      ,A.TAGS \n";
    	$sql .= "  FROM " .$tableName1. " A \n";
    	$sql .= "      ," .$tableName2. " B \n";
    	$sql .= " WHERE A.SECTION_ID = B.SECTION_ID \n";
    	$sql .= " ORDER BY SN DESC LIMIT 1";
    	$query = $this->db->query($sql);
    	if($query){
    		$result = $query->fetch_assoc();
    		return $result;
    	}else{
    		return false;
    	}
    }
    
    function getLastRecentUpdated(){
    	$tableName1 = 'contents';
    	$tableName2 = 'sectioninfo';

    	$sql  = "";
    	$sql .= "SELECT A.CONTENT_TYPE \n";
    	$sql .= "      ,A.CONTENT_ID \n";
    	$sql .= "      ,A.TITLE \n";
    	$sql .= "      ,A.THUMBNAIL_URL \n";
    	$sql .= "      ,A.VIDEO_PATH \n";
    	$sql .= "      ,A.PRIVATE_YN \n";
    	$sql .= "      ,A.TAGS \n";
    	$sql .= "  FROM " .$tableName1. " A \n";
    	$sql .= "      ," .$tableName2. " B \n";
    	$sql .= " WHERE A.SECTION_ID = B.SECTION_ID \n";
    	$sql .= " ORDER BY UPDATED DESC LIMIT 1";
    	$query = $this->db->query($sql);
    	if($query){
    		$result = $query->fetch_assoc();
    		return $result;
    	}else{
    		return false;
    	}
    }
    
    function getLastSectionContent($sectionID){
    	$tableName1 = 'contents';
    	$tableName2 = 'sectioninfo';

    	$sql  = "";
    	$sql .= "SELECT A.SECTION_ID \n";
    	$sql .= "      ,A.CONTENT_TYPE \n";
    	$sql .= "      ,A.CONTENT_ID \n";
    	$sql .= "      ,A.TITLE \n";
    	$sql .= "      ,A.THUMBNAIL_URL \n";
    	$sql .= "      ,B.COLOR \n";
    	$sql .= "  FROM " .$tableName1. " A \n";
    	$sql .= "      ," .$tableName2. " B \n";
    	$sql .= " WHERE A.SECTION_ID = B.SECTION_ID \n";
    	$sql .= "   AND A.SECTION_ID = '" .$sectionID. "' \n";
    	if(!isset($_SESSION['user']) || $_SESSION['user']!='ghostnote'){
    		$sql .= "   AND A.PRIVATE_YN = 'N' \n";
    		$sql .= "   AND B.PUBLIC_YN = 'Y' \n";
    	}
    	$sql .= "   AND STATUS = '1' \n";
    	$sql .= " ORDER BY SN DESC LIMIT 1";
    	$query = $this->db->query($sql);
        if($query){
    		$result = $query->fetch_assoc();
    		return $result;
    	}else{
    		return false;
    	}
    }

    function getSectionColor($tpCd, $ID){
    	$tableName1 = 'contents';
    	$tableName2 = 'sectioninfo';
    	$sql  = "";
    	
    	if($tpCd == 'SECTION'){
    		$sql .= "SELECT COLOR \n";
    		$sql .= "  FROM " .$tableName2. " \n";
    		$sql .= " WHERE SECTION_ID = '" .$ID. "'";
    	} else if($tpCd == 'CONTENT'){
    		$sql .= "SELECT B.COLOR \n";
    		$sql .= "  FROM " .$tableName1. " A \n";
    		$sql .= "      ," .$tableName2. " B \n";
    		$sql .= " WHERE A.CONTENT_ID = '" .$ID. "' \n";
    		$sql .= "   AND A.SECTION_ID = B.SECTION_ID";
    	} else{
    		return false;
    	}
    	
    	$query = $this->db->query($sql);
    	$result = $query->fetch_assoc();
    	if($query){
    		return $result;
    	}else{
    		return false;
    	}
    }
    
    function getContentsList($sectionID){
    	$tableName = 'contents';
    	$sql  = "";
    	$sql .= "SELECT CONTENT_TYPE \n";
    	$sql .= "      ,CONTENT_ID \n";
    	$sql .= "      ,TITLE \n";
    	$sql .= "      ,THUMBNAIL_URL \n";
    	$sql .= "      ,CREATED \n";
    	$sql .= "  FROM " .$tableName. " \n";
    	$sql .= " WHERE SECTION_ID = '" .$sectionID. "' \n";
    	if(!isset($_SESSION['user']) || $_SESSION['user']!='ghostnote'){
    		$sql .= "   AND PRIVATE_YN = 'N' \n";
    	}
    	$sql .= "   AND STATUS = '1' \n";
    	$sql .= " ORDER BY SN DESC";
    	$query = $this->db->query($sql);
    	if($query){
    		return $query;
    	}else{
    		return false;
    	}
    }

    function getContentDetail($contentID){
    	$tableName = 'contents';
    	$sql  = "";
    	$sql .= "SELECT SECTION_ID \n";
    	$sql .= "      ,CONTENT_ID \n";
    	$sql .= "      ,CONTENT_TYPE \n";
    	$sql .= "      ,TITLE \n";
    	$sql .= "      ,PRIVATE_YN \n";
    	$sql .= "      ,DESCRIPTION \n";
    	$sql .= "      ,TAGS \n";
    	$sql .= "      ,THUMBNAIL_URL \n";
    	$sql .= "      ,VIDEO_PATH \n";
    	$sql .= "      ,CREATED \n";
    	$sql .= "  FROM " .$tableName. " \n";
    	$sql .= " WHERE CONTENT_ID = '" .$contentID. "'";
    	$query = $this->db->query($sql);
    	$result = $query->fetch_assoc();
    	if($query){
    		return $result;
    	}else{
    		return false;
    	}
    }
    
    function getAllContents(){
    	$tableName = 'contents';
    	$sql  = "";
    	$sql .= "SELECT CONTENT_TYPE \n";
    	$sql .= "      ,CONTENT_ID \n";
    	$sql .= "      ,TITLE \n";
    	$sql .= "      ,THUMBNAIL_URL \n";
    	$sql .= "      ,CREATED \n";
    	$sql .= "  FROM " .$tableName. " \n";
    	$sql .= " WHERE SECTION_ID = '" .$sectionID. "' \n";
    	$sql .= "   AND STATUS = '1' \n";
    	$sql .= " ORDER BY SN DESC";
    	$query = $this->db->query($sql);
    	if($query){
    		return $query;
    	}else{
    		return false;
    	}
    }
    
    function setNewContent($sectionID, $contentType,$contentID,$title,$description,$tags,$privateYn,$thumbFileURL,$videoPath,$status){
    	$tableName = 'contents';
    	
    	$sql  = "";
    	$sql .= "INSERT INTO \n";
    	$sql .= " " .$tableName. " \n";
    	$sql .= " (SECTION_ID, CONTENT_TYPE, CONTENT_ID, TITLE, DESCRIPTION, TAGS, PRIVATE_YN, THUMBNAIL_URL, VIDEO_PATH, STATUS, CREATED) \n";
    	$sql .= " VALUES(";
    	$sql .= "'" .$sectionID. "','" .$contentType. "','" .$contentID. "','" .$title. "','" .$description. "','" .$tags. "','" .$privateYn. "','" .$thumbFileURL. "','" .$videoPath. "','" .$status. "', SYSDATE() \n";
    	$sql .= ")";
    	
    	if($this->db->query($sql)){
    		return true;
    	}else{
    		return false;
    	}
	}
	
	function modifyContent($contentID, $sectionID,$title,$description,$tags,$privateYn,$thumbFileURL){
		$tableName = 'contents';
		 
		$sql  = "";
		$sql .= "UPDATE ".$tableName. " \n";
		$sql .= "   SET SECTION_ID = '" .$sectionID. "' \n";
		$sql .= "      ,TITLE = '" .$title. "' \n";
		$sql .= "      ,DESCRIPTION = '" .$description. "' \n";
		$sql .= "      ,TAGS = '" .$tags. "' \n";
		$sql .= "      ,PRIVATE_YN = '" .$privateYn. "' \n";
		$sql .= "      ,THUMBNAIL_URL = '" .$thumbFileURL. "' \n";
		$sql .= "      ,UPDATED = SYSDATE() \n";
		$sql .= " WHERE CONTENT_ID = '" .$contentID. "'";

		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}
	
	function updateVideoContent($contentID, $youtubeID){
		$tableName = 'contents';
		
		$sql  = "";
		$sql .= "UPDATE ".$tableName. " \n";
		$sql .= "   SET VIDEO_PATH = '" .$youtubeID. "' \n";
		$sql .= "      ,STATUS = '1' \n";
		$sql .= "      ,UPDATED = SYSDATE() \n";
		$sql .= " WHERE CONTENT_ID = '" .$contentID. "'";
		
		$this->db->query($sql);
		return true;
	}
	
	function deleteContent($contentID){
		$tableNameSrc = 'contents';
		$tableNametgt = 'contents_backup';
		$sql  = "";
		$sql .= "INSERT INTO  ".$tableNametgt. " \n";
		$sql .= "SELECT * FROM " .$tableNameSrc. " \n";
		$sql .= " WHERE CONTENT_ID = '" .$contentID. "'";
		if(!$this->db->query($sql)){
			return false;
		}
		$sql  = "";
		$sql .= "DELETE FROM " .$tableNameSrc. " \n";
		$sql .= " WHERE CONTENT_ID = '" .$contentID. "'";
		if(!$this->db->query($sql)){
			return false;
		}
		return true;
	}
	
	function getTagList($tagID){
		$tableName = 'tags';
		
		$sql  = "";
		$sql .= "SELECT CONTENTS_LIST \n";
		$sql .= "  FROM " .$tableName. " \n";
		$sql .= " WHERE TAG_ID = '".$tagID."'";
		
		$query = $this->db->query($sql);
		if($query){
			return $query;
		}else{
			return false;
		}
	}
	
	function updateTagsContentsList($tagID, $contentsList){
		$tableName = 'tags';

		$sql  = "";
		$sql .= "UPDATE ".$tableName. " \n";
		$sql .= "   SET CONTENTS_LIST = '" .$contentsList. "' \n";
		$sql .= " WHERE TAG_ID = '" .$tagID. "'";

		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}
	
	function setNewTags($tagID, $contentID){
		$tableName = 'tags';
		 
		$sql  = "";
		$sql .= "INSERT INTO \n";
		$sql .= " " .$tableName. " \n";
		$sql .= " (TAG_ID, CONTENTS_LIST) \n";
		$sql .= " VALUES(";
		$sql .= "'" .$tagID. "','" .$contentID."' \n";
		$sql .= ")";
		 
		if($this->db->query($sql)){
			return true;
		}else{
			return false;
		}
	}
	
	function getSearchContentsList($tags){
		$tableName = 'tags';
		$tagsArr = explode(" ",$tags);
		$condition="";
		for($i=0;$i<count($tagsArr);$i++){
			if($i==0){
				$condition = $condition . " A.TAG_ID LIKE '" . $tagsArr[$i] ."%' \n";
			} else {
				$condition = $condition . " OR A.TAG_ID LIKE '" . $tagsArr[$i] ."%' \n";
			}
		}
		$sql  = "";
		$sql .= "SELECT A.CONTENTS_LIST \n";
		$sql .= "  FROM " .$tableName. " A \n";
		$sql .= " WHERE 1 = 1 \n";
		$sql .= "   AND (";
		$sql .= $condition;
		$sql .= "        )";
	    $query = $this->db->query($sql);
    	if($query){
    		return $query;
    	}else{
    		return false;
    	}
	}
	
	function getSearchContentsInfo($firstYN, $lastSN, $contentsList){
		$tableName1 = 'contents';
		$tableName2 = 'sectioninfo';
		$contentsListArr = explode(",", $contentsList);
		$newContentsList="";
		for($i=0;$i<count($contentsListArr);$i++){
			if($newContentsList==""){
				$newContentsList = $newContentsList . "'".$contentsListArr[$i]."'";
			} else {
				$newContentsList = $newContentsList . ",'".$contentsListArr[$i]."'";
			}
		}

		$sql  = "";
		$sql .= "SELECT A.SN \n";
		$sql .= "      ,A.SECTION_ID \n";
		$sql .= "      ,A.CONTENT_TYPE \n";
		$sql .= "      ,A.CONTENT_ID \n";
		$sql .= "      ,A.TITLE \n";
		$sql .= "      ,A.THUMBNAIL_URL \n";
		$sql .= "      ,B.COLOR \n";
		$sql .= "      ,B.SECTION_NAME \n";
		$sql .= "      ,DATE_FORMAT(A.CREATED,'%Y.%m.%d') AS CREATED \n";
		$sql .= "  FROM " .$tableName1. " A \n";
		$sql .= "      ," .$tableName2. " B \n";
		$sql .= " WHERE A.SECTION_ID = B.SECTION_ID \n";
		$sql .= "   AND A.CONTENT_ID IN ( \n";
		$sql .= $newContentsList;
		$sql .= "   ) \n";
		
		if(!isset($_SESSION['user']) || $_SESSION['user']!='ghostnote'){
			$sql .= "   AND A.PRIVATE_YN = 'N' \n";
			$sql .= "   AND B.PUBLIC_YN = 'Y' \n";
		}
		if(!$firstYN){
			$sql .= "   AND A.SN < ".$lastSN. " \n";
		}
		$sql .= "   AND A.STATUS = '1' ";
		$sql .= " ORDER BY A.SN DESC LIMIT 6";
		$query = $this->db->query($sql);
		if($query){
			return $query;
		}else{
			return false;
		}
	}
	
	function getArchives($firstYN, $lastSN){
		$tableName1 = 'contents';
		$tableName2 = 'sectioninfo';
	
		$sql  = "";
		$sql .= "SELECT A.SN \n";
		$sql .= "      ,A.SECTION_ID \n";
		$sql .= "      ,A.CONTENT_TYPE \n";
		$sql .= "      ,A.CONTENT_ID \n";
		$sql .= "      ,A.TITLE \n";
		$sql .= "      ,A.THUMBNAIL_URL \n";
		$sql .= "      ,B.COLOR \n";
		$sql .= "      ,B.SECTION_NAME \n";
		$sql .= "      ,A.CREATED \n";
		$sql .= "  FROM " .$tableName1. " A \n";
		$sql .= "      ," .$tableName2. " B \n";
		$sql .= " WHERE A.SECTION_ID = B.SECTION_ID \n";
		if(!isset($_SESSION['user']) || $_SESSION['user']!='ghostnote'){
			$sql .= "   AND A.PRIVATE_YN = 'N' \n";
			$sql .= "   AND B.PUBLIC_YN = 'Y' \n";
		}
		if(!$firstYN){
			$sql .= "   AND A.SN < ".$lastSN. " \n";
		}
		$sql .= "   AND A.STATUS = '1' ";
		$sql .= " ORDER BY A.SN DESC LIMIT 6";
		$query = $this->db->query($sql);
		if($query){
			return $query;
		}else{
			return false;
		}
	}
}
?>