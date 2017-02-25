<?php
abstract class uploadFile {
	
	public $allowed_filetypes;
	public $regex;
	public $return;
}
	function echoout() {
		echo "hello file";
	}
	
	function handle() {
	// = array('.jpeg','.jpg','.gif','.bmp','.png','.mov','.mp3','.mp4','.avi','.mpg','.mpeg','.flv','.ukn');
	
	//array('/image/','/audio/','/video/');
	
	// = (isset($return) ? $return : "");
	if (!$_FILES) {
		//echo "do nothing";
	} else {
		$name = $_FILES['uploadedfile']['name'];
		$type = $_FILES['uploadedfile']['type'];
		$tmpfile = $_FILES['uploadedfile']['tmp_name'];
		$error = $_FILES['uploadedfile']['error'];
		$size = $_FILES['uploadedfile']['size'];
		if ($error !== UPLOAD_ERR_OK) {
			switch ($error) {
				case 1:
					print "<p>1 The uploaded file exceeds the max filesize.</p>";
					break;
				case 2:
					print "<p>2 The uploaded file exceeds the max filesize.</p>";
					break;
				case 3:
					print "<p>3 The uploaded file was only partially uploaded.</p>";
					break;
				case 4:
					print "<p>4 No file was uploaded. </p>";
					break;
				case 6:
					print "<p>6 Cannot store uploaded file.</p>";
					break;
				case 7:
					print "<p>7 Failed to write file to disk.</p>";
					break;
				case 7:
					print "<p>8 PHP stopped the file upload.</p>";
					break;
				default:
					print "<p>Fatal upload error.</p>";
					break;
			}
			die($html->htmlfoot());
		} elseif ($size > 52428800) {
			print "<p>file size exceeds 50MB</p>";
			die("");
		} elseif (!$_FILES['uploadedfile'] OR !is_uploaded_file($tmpfile)) {
			$html->htmlfoot();
			print "<p>couldn't read file</p>";
			die($html->htmlfoot());
		} else {
			//echo "<h1>uploading</h1>";
			$upfile = basename( $_FILES['uploadedfile']['name']);
			$upfile = preg_replace ( '/[^a-z0-9._-]/i', '', $upfile );
			$lastdot = strripos($upfile,'.');
			if($lastdot > 0) $ext = substr($upfile, strripos($upfile,'.'), strlen($upfile)-1);
			else $ext = ".ukn";
			$basename = substr($upfile, 0, strripos($upfile,'.'));
			$filename = $basename.$ext;
		}
	}
	}
?>