<?php
session_start();
if(session_id() != ''){
	if(isset($_SESSION['user'])){
		echo session_id();
		if ($_SESSION['user'] == 'ghostnote'){
			header('Location: /admin/adminMain');
		}
	} else {
		session_destroy();
	}
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
	<title>Ghostnote Company.</title>
	<link rel="stylesheet" type="text/css" href="/javascript/common.css">
	<script src="/javascript/common.js"></script>
</head>
<body>

<div>
		<header class="primary">
			<hgroup><h1>Ghostnote</h1></hgroup>
		</header>
</div>
<div>
	<div id="wrap">
  		<form method="post" name="form_admin" id="form_admin" enctype="multipart/form-data" action="/admin/adminlogin" onsubmit="return confirmSubmit();">
  		<filedset class="login_form">
	  		<legend class="blind">Admin</legend>
	  		<div id="id_area" class="input_row">
	    		<span class="input_box">
	      			<label for="id" id="label_id_area" class="lbl">관리자 계정</label>
	      			<input type="text" id="id" name="id" tabindex="1" accesskey="L" placeholder="관리자 계정" class="int" maxlength="41" value="">
	    		</span>
	    		<button type="button" disabled="" title="delete" id="id_clear" class="wrg"><span class="blind">삭제</span></button>
	  		</div>
	  		<div id="err_empty_id" class="error" style="display:none;">관리자 계정을 입력해주세요.</div>
	  		<div id="pw_area" class="input_row">
		    	<span class="input_box">
		      		<label for="pw" id="label_pw_area" class="lbl">비밀번호</label>
		      		<input type="password" id="pw" name="pw" tabindex="2" accesskey="L" placeholder="비밀번호" class="int" maxlength="16" value="">
		    	</span>
		    	<button type="button" disabled="" title="delete" id="pw_clear" class="wrg"><span class="blind">삭제</span></button>
		    	<div id="err_capslock" class="ly_v2" style="display:none;"><div class="ly_box"><p><strong>Caps Lock</strong>이 켜져 있습니다.</p></div><span class="sp ly_point"></span></div>
	  		</div>
	  		<div id="err_empty_pw" class="error" style="display:none;">비밀번호를 입력해주세요.</div>
	  		<span class="btn_login">
	    		<input type="submit" title="로그인" alt="로그인" tabindex="12" value="로그인" class="int_jogin">
	    	</span>
  		</filedset>
  		</form>
	</div>
</div>

<script>
	addInputEvent('id', 'id_area');
	addInputEvent('pw', 'pw_area');
	addDeleteButtonEvent('id', 'id_clear');
	addDeleteButtonEvent('pw', 'pw_clear');
	try{
		if (navigator.appVersion.toLowerCase().indexOf("win") != -1) {
			$('id').style.imeMode = "disabled";
			document.msCapsLockWarningOff = true;
		}
	}catch(e) {}
</script>
</body>
</html>