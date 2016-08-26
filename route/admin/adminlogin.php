<?php
require_once 'include/DB_ADMIN.php';

$db = new DB;
$userID = $_REQUEST['id'];
$userPW = $_REQUEST['pw'];
$result = $db->checkAdminInfo($userID, $userPW);

if($result['username']){
	session_start();
	$_SESSION['user'] = 'ghostnote';
	echo "<script>";
	echo "window.location = '/admin/adminMain'";
	echo "</script>";
} else {
	echo "<script>alert('You are not Authorized. Please check.') \n";
	echo "window.location = '/admin'";
	echo "</script>";
}
$db->dbClose();
?>