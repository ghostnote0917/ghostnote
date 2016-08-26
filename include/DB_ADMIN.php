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
        }

        function checkAdminInfo($userID, $userPW){
                $sql = "SELECT username FROM admininfo WHERE userid = '".$userID."' AND password = password('".$userPW."')";
                $query = $this->db->query($sql);
                $result = $query->fetch_assoc();
               if($result){
                        return $result;
                }else{
                        return false;
                }
        }
}
?>
