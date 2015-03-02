<?php
	@session_start();
	if(!empty($_FILES)){
		if ( ($_FILES["fileToUpload"]["type"] == "image/gif")
		|| ($_FILES["fileToUpload"]["type"] == "image/jpeg")
		|| ($_FILES["fileToUpload"]["type"] == "image/jpg")
		|| ($_FILES["fileToUpload"]["type"] == "image/pjpeg")
		|| ($_FILES["fileToUpload"]["type"] == "image/png")
		&& ($_FILES["fileToUpload"]["size"] < 5000000) )
		{
		  	if ($_FILES["fileToUpload"]["error"] > 0){
		   		 echo "Error: " . $_FILES["fileToUpload"]["error"] . "</br>";
		    }else{
		    	$imageName =  $_FILES["fileToUpload"]["name"];
				//remove polish signs
				$replacements=array('ą'=>'a', 'ć'=>'c', 'ę'=>'e', 'ł'=>'l', 'ń'=>'n', 'ó'=>'o', 'ś'=>'s', 'ź'=>'z', 'ż'=>'z',
									'Ą'=>'A', 'Ć'=>'C', 'Ę'=>'E', 'Ł'=>'L', 'Ń'=>'N', 'Ó'=>'O', 'Ś'=>'S', 'Ź'=>'Z', 'Ż'=>'Z');
				
				$_SESSION['imageName']= $imageName = strtr($imageName,  $replacements);
				$image = $_SESSION['imagePath']= 'uploads/'.$imageName;
				
			    move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $image);
				$_SESSION['uploaded'] = 'true';
		    }
	    }else{
	    	echo "Wrong image size or type (only gif, jpg and png allowed).";
	    }
	}	
?>
