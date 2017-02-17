# PHP nudity and porn image detector

PHP code by Bakr Alsharif from Egypt:
http://www.9lessons.info/2014/01/block-uploads-of-adult-or-nude-images.html

Live demo:
http://demos.9lessons.info/ajaximageupload/index_m_block.php

## Block Uploads of Adult or Nude Images using PHP.

I found an interesting and useful class file in phpclasses.org, that helps to detect image nudity based on skin pixel score developed by Bakr Alsharif from Egypt. I had integrated this with my previous tutorial Ajax image upload with Jquery and PHP, sure this code helps you to block adult or nudity images.

### Sample database design for Users.

**Users**
Contains user details username, password and email etc.
```sql
CREATE TABLE `users` (
`uid` int(11) AUTO_INCREMENT PRIMARY KEY,
`username` varchar(255) UNIQUE KEY,
`password` varchar(100),
`email` varchar(255) UNIQUE KEY
)
```
Sample values:
```sql
INSERT INTO `users` 
(`uid`, `username`, `password`, `email`) 
VALUES 
('1', '9lessons', MD5('password'), 'srinivas@9lessons.info');
```

### Javascript Code

`$("#photoimg").on('change',function(){})`
- photoimg is the ID name of INPUT FILE tag and

`$('#imageform').ajaxForm()`
- imageform is the ID name of FORM.

While changing INPUT it calls FORM submit without refreshing page using `ajaxForm()` method. Uploaded images will `prepend` inside `#preview` tag.

```html
<script type="text/javascript" src="http://ajax.googleapis.com/
ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="jquery.wallform.js"></script>
<script type="text/javascript">
$(document).ready(function() 
{ 

$('#photoimg').on('change', function() 
 {
var A=$("#imageloadstatus");
var B=$("#imageloadbutton");

$("#imageform").ajaxForm({target: '#preview', 
beforeSubmit:function(){
A.show();
B.hide();
}, 
success:function(){
A.hide();
B.show();
}, 
error:function(){
A.hide();
B.show();
} }).submit();
});

}); 
</script>
```

Here hiding and showing `#imageloadstatus` and `#imageloadbutton` based on form upload submit status. 

### PHP Code

**index.php**
Contains simple PHP and HTML code. Here `$session_id=1` means user id session value. 
```php
<?php
include('db.php');
session_start();
$session_id='1'; // User login session value
?>
<div id='preview'>
</div>
<form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>
Upload image: 
<div id='imageloadstatus' style='display:none'><img src="loader.gif" alt="Uploading...."/></div>
<div id='imageloadbutton'>
<input type="file" name="photoimg" id="photoimg" />

</div>
</form>
```

**ajaximage.php**
Contains PHP code. This script helps you to upload images into `uploads` folder. Image file name rename into `timestamp+session_id.extention`
```php
<?php
include('db.php');
session_start();
$session_id='1'; // User session id
$path = "uploads/";

function getExtension($str)
{
$i = strrpos($str,".");
if (!$i)
{
return "";
}
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
if($size<(1024*1024)) // Image size max 1 MB
{
//---Image Filter Code
require_once('class.ImageFilter.php');
$filter = new ImageFilter;
$score = $filter->GetScore($_FILES['photoimg']['tmp_name']);
if(isset($score))
{
if($score >= 60) // Score value If more than 60%, it consider as adult image. 
{
echo "Image scored ".$score."%, It seems that you have uploaded a nude picture :-(";
}
else
{
//---Image Filter Code 
$actual_image_name = time().$session_id.".".$ext;
$tmp = $_FILES['photoimg']['tmp_name'];
if(move_uploaded_file($tmp, $path.$actual_image_name))
{
mysqli_query($connection,"UPDATE users SET profile_image='$actual_image_name' WHERE uid='$session_id'");
echo "<img src='uploads/".$actual_image_name."' class='preview'>";
}
else
echo "failed";
//---Image Filter Code 
}
}
//---Image Filter Code 
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
```

**db.php**
Database configuration file, just modify database credentials.
```php
<?php
error_reporting(0);
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_DATABASE', 'database');
$connection = @mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
?>
```

