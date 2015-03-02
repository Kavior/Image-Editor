<!DOCTYPE HTML>
<html>	
	<head>
	<link rel="stylesheet" type="text/css" href="style.css" >
	<link rel="ICON" href="icon.png" type="image/ico" />
	<title>Simple online image changer</title>
	<script type="text/javascript" src="jquery-2.0.3.min.js"></script>
	<script src="jquery-ui.js"></script>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
	</head>
	<body>
		<div id="main">
			<?php
				session_start();				
			?>	
			<script>
				$(document).ready(function(){
					uploadAppended = false;
					//Allows uploading new image
					$('#new').click(function(){
						$(this).hide();
						if(!uploadAppended){
						$('#uploadArea').append('<div id="newForm"><form action="index.php" method="post" enctype="multipart/form-data">'+
						'Select image to upload: <input type="file" name="fileToUpload" id="fileToUpload">'+
						'<input type="submit" value="Upload Image" name="submit"></form></div>'
						);
						uploadAppended = true;
						}
					});

					// Sliding options
					$('.switcher').click(function(){
						$thisMenuId = $(this).attr("id");
						//Save it in order to load this menu automaticly after refresh
						localStorage.setItem("lastEditedCategory",$thisMenuId);
						if(typeof rolled == 'undefined' || rolled== false){
							$(this).next().next().slideToggle("slow");
							rolled = true;
						}else{
							$(this).next().next().slideToggle("slow");
							rolled = false;
						}

					});
					//retrieve last showed menu
					lastEditedCatgoryId = localStorage.getItem("lastEditedCategory");
					menuToShow = document.getElementById(lastEditedCatgoryId);
					menuToShow.click();			
				});
			</script>
			<div id="mainBody">	
				<div id="leftTools">
					<div id="uploadArea">
						<?php
							require "upload.php";
							if(isset($_SESSION['uploaded']) && !$_SESSION['uploaded']){
								echo '<form action="index.php" method="post" enctype="multipart/form-data">
								    Select image to upload:
								    <input type="file" name="fileToUpload" id="fileToUpload">
								    <input type="submit" value="Upload Image" name="submit">
									</form>';
							}else{
								//Option to save edited image
								echo '<form method="post" action="index.php"><input type="submit" name="save" id="save" value="Save"></form></br><button id="new" >Upload new image</button>';
								if(isset($_POST['save'])){
									$file=@$_SESSION['imagePath'];
									$filename = basename($file);							    
									$extension =	pathinfo( $ImageName, PATHINFO_EXTENSION);
							        header('Content-Description: File Transfer');
							        header('Content-Type: application/octet-stream');
							        header('Content-Disposition: attachment; filename='.$filename);
							        header('Content-Transfer-Encoding: binary');
							        header('Expires: 0');
							        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
							        header('Pragma: public');
							        ob_clean();
							        flush();
							        readfile($file);
							        exit;										
								}
							}
						?>
						
					</div>
					<div class="switcher" id="size">Change size</div><hr noshade="noshade"  size="1"/>
					<div class="more"><div class="options"><form action="index.php" method="post">
						<div width="150">
						Width:<input type="number" min="1" step="1"  class="leftField" name="width" autocomplete="off"/></br>						
						Height:<input type="number" min="1" step="1"  class="leftField" name="height" autocomplete="off"/></br>
						</div>

						<input type="radio" name="unit" value="percent" id="percent" checked="checked" /> <label for="lr0">%</label>
						<input type="radio" name="unit" value="px" id="lr1" /> <label for="lr1">px</label></br>
						<input type="submit" value="Ok" />
					</form></div><hr noshade="noshade"  size="1"/></div>
					<div class="switcher" id="rotate">Rotate</div><hr noshade="noshade"  size="1"/>
					<div class="more"><div class="options"><form action="index.php" method="post">
						<input type= "radio" id="angle" name="angle" value="90"> 90
						<input type= "radio" id="angle" name="angle" value="180">180
						deegrees						
						<input type= "submit" value="Rotate"></br>
					</form></div><hr noshade="noshade"  size="1"/></div>
					<div class="switcher" id="adjustment">Adjustments</div><hr noshade="noshade"  size="1"/><div class="more"><div class="options"><form action="index.php" method="post">
						brightness</br> <input type="text"  name= "brightness" class="inputBox"  id= "brightness" autocomplete="off"></br>
						contrast </br><input type="text" name= "contrast" class="inputBox"  id= "contrast" autocomplete="off"></br>
						<input type="submit" value="Apply">
					</form></div><hr noshade="noshade"  size="1"/></div>
					<div class="switcher" id="specialEffects">Special effects:</div>
					<div class="empty"></div><div class="more"><div class="options"><form action="index.php" method="post">
						
						<input type="checkbox" name="greyscale" value="greyscale" />greyscale</br>
						<input type="checkbox" name="negate" value="negate" />negate</br>
						<input type="checkbox" name="emboss" value="emboss" />emboss</br>
						<input type="submit" value="Apply">
					</form>	</div></div>		
				</div>
				<div id="mainImage" style="vertical-align:top;">
					<?php 
						require 'image.php';
						//Show image if uploaded
						if(isset($_SESSION['imagePath'])){
							$imagePath = $_SESSION['imagePath'];
							echo '<center><div style="vertical-align: middle;"><img src="'.$imagePath.'" ></div></center>';
							$currentImage = new Image($imagePath);
							
							if(isset($_POST['height']))
								$height = $_POST['height'];
							if(isset($_POST['width']))
								$width = $_POST['width'];
							//Set new width and height
							if(isset($height) && $height!=="" && isset($width) && $width!==""){						
								$currentImage->setSize($width, $height);											
							}else if(isset($height ) && $width==""){
								$currentImage->setSize($currentImage->getWidth(), $height);	
							}else if(isset($width) && $height==""){
								$currentImage->setSize($width, $currentImage->getHeight() );				
							}
							
							if(isset($_POST['angle'])){
								$currentImage->rotate($_POST['angle']);
							}
							
							if(isset($_POST['brightness'])){
								$currentImage->setBrightness($_POST['brightness']);
							}
	
							if(isset($_POST['contrast'])){
								$currentImage->setContrast($_POST['contrast']);
							}
							
							if(isset($_POST['greyscale'])){	
								$currentImage->makeGreyscale();
							}
							
							if(isset($_POST['negate'])){
								$currentImage->negate();
							}
	
							if(isset($_POST['emboss'])){
								$currentImage->emboss();
							}
						}	
					?>
				</div>	
			</div>	
		</div>
	</body>
</html>
