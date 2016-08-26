<?php
require_once("template/header.php");
if(!isset($_SESSION['user'])){
	echo "<script>alert('Your not Authorized. Please check.') \n";
	echo "window.location = '/admin'";
	echo "</script>";
}

?>
  <div>
    <div>
      <nav>
        <ul>
          <li><a href="/admin/uploadContent">게시물 등록</a></li>
          <li><a href="/admin/maintainContent">게시물 관리</a></li>
        </ul>
      </nav>
    </div>
  </div>
  
<?php require_once("template/bottom.php");?>