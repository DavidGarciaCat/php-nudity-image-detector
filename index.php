<?php
include('db.php');
session_start();
$session_id='1'; //$session id
?>
<html>
<head>
<title>Ajax Image Upload Block Adult Images 9lessons blog</title>
</head>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="scripts/jquery.wallform.js"></script>

<script type="text/javascript" >
 $(document).ready(function() { 
		
            $('#photoimg').on('change', function()			{ 
			           //$("#preview").html('');
			    
				$("#imageform").ajaxForm({target: '#preview', 
				     beforeSubmit:function(){ 
					
					
					$("#imageloadstatus").show();
					 $("#imageloadbutton").hide();
					 }, 
					success:function(){ 
				
					 $("#imageloadstatus").hide();
					 $("#imageloadbutton").show();
					}, 
					error:function(){ 
					
					 $("#imageloadstatus").hide();
					$("#imageloadbutton").show();
					} }).submit();
					
		
			});
        }); 
</script>

<style>

body
{
font-family:arial;
}
.preview
{
width:200px;
border:solid 1px #dedede;
padding:10px;
}
#preview
{
color:#cc0000;
font-size:12px
}

</style>
<body>
<a href='http://9lessons.info'>9lessons.info</a>


<div style="width:600px">

	<div id='preview'>
	</div>
	


<form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>
Upload your image 
<div id='imageloadstatus' style='display:none'><img src="loader.gif" alt="Uploading...."/></div>
<div id='imageloadbutton'>
<input type="file" name="photoimg" id="photoimg" />
</div>
</form>



</div>
</body>
</html>