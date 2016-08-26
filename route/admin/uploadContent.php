<?php
require_once 'include/DB_CONTENTS.php';
session_start();

if(!isset($_SESSION['user'])){
	echo "<script>alert('Your not Authorized. Please check.') \n";
	echo "window.location = '/'";
	echo "</script>";
}

$db = new DB;
$resultSectionInfo = $db->getSectionInfo();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes, target-densitydpi=medium-dpi">
	<title>Ghostnote Company</title>

	<!-- include libraries(jQuery, bootstrap) -->
  	<link href="/javascript/bootstrap.css" rel="stylesheet">
  	<script src="/javascript/jquery.js"></script> 
  	<script src="/javascript/bootstrap.js"></script>

	<!-- include summernote css/js-->
  	<link href="/summernote/dist/summernote.css" rel="stylesheet">
  	<script src="/summernote/dist/summernote.js"></script>
  	<script src="/summernote/dist/lang/summernote-ko-KR.js"></script>
  	<link href="/javascript/common.css" rel="stylesheet">
</head>
<body>

<div class="body body_center">
	<div class="wrap">
		<div>
			<header class="primary">
				<hgroup><h1>Upload</h1></hgroup>
			</header>
		</div>
		<form method="post" name="multiple_upload_form" id="multiple_upload_form" enctype="multipart/form-data" action="/admin/uploadContentProc">
			<div class="upload-ncotents">
				<div class="upload_div">
					<span class="upload-section_select">
			  			<select name="sectionID" id="sectionID">
			  				<option value="0000">=Section=</option>
<?php 
							while($result = $resultSectionInfo->fetch_assoc()){
								echo '<option value="' .$result['SECTION_ID'].'">'.$result['SECTION_NAME'].'</option>'; 
							}
?>
			  			</select>
			  		</span>
					<span class="upload-content_type">
			  			<select name="contentType" id="contentType">
			  				<option value="NO">=Contents Type=</option>
			  				<option value="VIDEO">VIDEO</option>
			  				<option value="WRITING">WRITING</option>
			  			</select>
			  		</span>
		  		</div>
		  		<div class="upload-title">
		  			<input type="text" id="title" name="title" placeholder="제목을 입력하세요." class="text_title">
		  		</div>
		  		<div class="upload_div">
		  			<span class="upload_private">
			  			<select name="privateYn" id="privateYn" class="option_private">
			  				<option value="N">공개</option>
			  				<option value="Y">비공개</option>
			  			</select>
			  		</span>
			  		<span class="upload-tags">
			  			<input type="text" id="tags" name="tags" placeholder="#태그를 입력하세요. 쉼표로 구분가능합니다." class="text_tag">
			  		</span>
		  		</div>
		  		<div class="upload-thumb">
		  			<label for="thumbfile">썸네일&nbsp;</label><input type="file"  name="thumbfile" id="thumbfile" style="display:inline;"accept="image/*">
		  		</div>
		  		<div class="upload-video" id="divVideo" name="divVideo">
		  			<label for="videofile">동영상&nbsp;</label><input type="file"  name="videofile" id="videofile" style="display:inline;"accept="video/*">
		  		</div>
	  			<div class="upload-desc">
		  			<div>
						<textarea id="description" name="description" align="left"></textarea>
		  			</div>
		  		</div>
		  		<div class="button_div">
				  	<div class="bottom_button">
						<a class="button_cancel" onClick="cancel();">
				  			<span>취소</span>
				  		</a>
				  	</div>
				  	<div class="bottom_button">
				  		<a class="button_ok" onClick="upLoad();">
				  			<span>저장</span>
				  		</a>
				  	</div>
				</div>
		  	</div>
		</form>
	</div>
</div>
<script>
	function checkParam(){
		var sectionID = document.getElementById("sectionID");
		var contentType = document.getElementById("contentType");
		var title = document.getElementById("title");
		var thumbfile = document.getElementById("thumbfile");
		var videofile = document.getElementById("videofile");
		if(sectionID.value =='0000'){
			alert('Section을 선택하세요.');
			window.moveTo(0, 0);
			//sectionID.focus();
			return false;
		}
		if(contentType.value =='NO'){
			alert('Contents Type을 선택하세요.');
			contentType.focus();
			return false;
		}
		if(title.value ==''){
			alert('제목을 입력하세요.');
			title.focus();
			return false;
		}
		if(thumbfile.value ==''){
			alert('썸네일 이미지를 선택하세요.');
			thumbfile.focus();
			return false;
		}
		if(contentType.value == 'VIDEO' && title.value ==''){
			alert('동영상 파일을 선택하세요.');
			videofile.focus();
			return false;
		}
		return true;
	}
  
	function upLoad(){
		checkResult = checkParam();
		if(checkResult == true){
			multiple_upload_form.submit();
		} else {
			return;
		}
	}
	function cancel(){
		var confirmYn = confirm('현재 작성중인 문서를 취소하시겠습니까?');
		if(confirmYn){
			location.href='/admin/'
			return;
		} else {
			return;
		}
	}
  
    $(document).ready(function() {
    	$('#divVideo').css("display","none");
    	
    	
    	$('#description').summernote({
            callbacks: {
                onImageUpload : function(files, editor, welEditable) {
                    console.log('image upload:', files);
                    sendFile(files[0], editor, welEditable);
                }	
            },
        	height: 550,
        	lang : 'ko-KR',
        	shortcuts : false,
        	placeholder: '내용을 입력하세오.'
        });
        
    	   function sendFile(file,editor,welEditable) {
    		   data = new FormData();
    		   data.append("file", file);
    		    $.ajax({
    		      url: "/admin/imageUploader", // image 저장 소스
    		      data: data,
    		      cache: false,
    		      contentType: false,
    		      processData: false,
    		      type: 'POST',
    		      success: function(data){
    		    	  
    		       	var image = $('<img>').attr('src', '' + data); // 에디터에 img 태그로 저장을 하기 위함
    		       	image.attr('class', 'image_inner');
    		       	$('#description').summernote("insertNode", image[0]); // summernote 에디터에 img 태그를 보여줌
    		      },
    		      error: function(jqXHR, textStatus, errorThrown) {
    		        console.log(textStatus+" "+errorThrown);
    		      }
    		    });
    		   }

        $('#contentType').change(function(){
        	$("#contentType option:selected").each(function(){
            	if($(this).val()=="VIDEO"){
            		$('#divVideo').css("display","block");
            	} else {
            		$('#divVideo').css("display","none");
            	}
        	});
        });        
    });
</script>
</body>
</html>