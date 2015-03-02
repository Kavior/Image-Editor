<?php
	class Image{
		public $name =null;

		public function __construct($name){
			$this->name= $name;
			define('IMAGE_NAME', $name);	
		}
		
		public function createImg(){
			$extension = pathinfo(IMAGE_NAME, PATHINFO_EXTENSION);
			switch($extension){
				case 'jpg':
				case 'jpeg':
					return imagecreatefromjpeg(IMAGE_NAME);							
				break;
			
				case 'png':		
					return imagecreatefrompng(IMAGE_NAME);			
				break;			
			}
		}

		public function outputImg($ImageName, $input){
			$extension = pathinfo($ImageName, PATHINFO_EXTENSION);
			switch($extension){
				case 'jpg':
				case 'jpeg':
					 imagejpeg($input, $ImageName);
				break;
				
				case 'png':
					 imagepng($input, $ImageName);
				break;
			}
		}
					
		public function getWidth(){
			return getimagesize($this->name)[0];
		}
		
		public function getHeight(){
			return getimagesize($this->name)[1];
		}
		
		public function setSize($width, $height){
			if($_POST['unit']=="percent"){
				$width*=$_POST['width']!==""?$this->getWidth()/100:1;
				$height*=$_POST['height']!==""?$this->getWidth()/100:1;
			}
			$oldHeight = $this->getHeight();
			$oldWidth = $this->getWidth();
			$newRes = imagecreatetruecolor($width,$height );
			
			imagealphablending($newRes, false);
			imagesavealpha($newRes, true); 
			imagealphablending($this->createImg(), true);
			imagecopyresampled($newRes, $this->createImg(), 0, 0, 0, 0, $width, $height, $oldWidth , $oldHeight);
			
			$this->outputImg(IMAGE_NAME, $newRes);
		}

		public function makeGreyscale(){
				$createdImage = $this->createImg();			
				imagealphablending($this->createImg(), true);
				imagesavealpha($this->createImg(), true); 
				imagefilter( $this->createImg(), IMG_FILTER_GRAYSCALE);
				$this->outputImg(IMAGE_NAME, $this->createImg());				
		} 
		
		public function setBrightness($brightness){
			$createdImage = $this->createImg();
			imagealphablending($createdImage, true);
			imagesavealpha($createdImage, true); 
			$brightness= intval($brightness);
			imagefilter( $createdImage, IMG_FILTER_BRIGHTNESS, $brightness);			
			$this->outputImg(IMAGE_NAME, $createdImage);
		}
		
		public function setContrast($contrast){
			$createdImage = $this->createImg();
			imagealphablending($createdImage, true);
			imagesavealpha($createdImage, true); 
			$contrast = intval($contrast);
			imagefilter( $createdImage, IMG_FILTER_CONTRAST, $contrast);			
			$this->outputImg(IMAGE_NAME, $createdImage);
		}
		
		public function emboss(){
			$createdImage = $this->createImg();
			imagealphablending($createdImage, true);
			imagesavealpha($createdImage, true); 
			imagefilter( $createdImage, IMG_FILTER_EMBOSS);			
			$this->outputImg(IMAGE_NAME, $createdImage);		
		}

		public function negate(){
			$createdImage = $this->createImg();
			imagealphablending($createdImage, true);
			imagesavealpha($createdImage, true); 
			imagefilter( $createdImage, 	IMG_FILTER_NEGATE);			
			$this->outputImg(IMAGE_NAME, $createdImage);
		}

		public function rotate($angle){
			imagealphablending($this->createImg(), true);
			$rotated = imagerotate($this->createImg(), $angle, 0);
			imagealphablending($rotated, false);
			imagesavealpha($rotated, true); 
			$this->outputImg(IMAGE_NAME, $rotated);
		}			
	} //Image end	
?>