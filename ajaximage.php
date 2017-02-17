<?php
include('db.php');
session_start();
$session_id='1'; //$session id
$path = "uploads/";

function getExtension($str) 
{

         $i = strrpos($str,".");
         if (!$i) { return ""; } 

         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
 }

	$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
		{
			$name = $_FILES['photoimg']['name'];
			$size = $_FILES['photoimg']['size'];
			
			if(strlen($name))
				{
					 $ext = getExtension($name);
					if(in_array($ext,$valid_formats))
					{
					if($size<(1024*1024))
						{
							
							require_once('class.ImageFilter.php');
						    $filter = new ImageFilter;
						    $score = $filter->GetScore($_FILES['photoimg']['tmp_name']);
							
							if(isset($score))
							{
								if($score >= 40)
								{
									 echo "Image scored ".$score."%, It seems that you have uploaded a nude picture :-(";
								}
								
							    else
							   {
							
							//---------
							$actual_image_name = time().".".$ext;
							$tmp = $_FILES['photoimg']['tmp_name'];
							if(move_uploaded_file($tmp, $path.$actual_image_name))
								{
								mysqli_query($connection,"UPDATE users SET profile_image='$actual_image_name' WHERE uid='$session_id'");
									
									echo "<img src='uploads/".$actual_image_name."'  class='preview'>";
								}
							else
								echo "Fail upload folder with read access.";
							//--------	
						}
					}
						
						}
						else
						echo "Image file size max 1 MB";					
						}
						else
						echo "Invalid file format..";	
				}
				
			else
				echo "Please select image..!";
				
			exit;
		}
?>