<?php
    require_once(realpath(dirname(__FILE__)).'/config.php');
    require_once(PATH_PHP.'security.php');
    function generate_captcha(){
		$captcha = substr(md5(rand()),0,15);
		$_SESSION['captcha'] = $captcha;
		//Set the image width and height
		$width = 165;
		$height = 20; 
		//Create the image resource 
		$image = ImageCreate($width, $height);  
		//We are making three colors, white, black and gray
		$white = ImageColorAllocate($image, 255, 255, 255);
		$black = ImageColorAllocate($image, 0, 0, 0);
		$grey = ImageColorAllocate($image, 204, 204, 204);
		//Make the background black 
		ImageFill($image, 0, 0, $black); 
		//Add randomly generated string in white to the image
		ImageString($image, 3, 30, 3, $captcha, $white); 
		//Throw in some lines to make it a little bit harder for any bots to break 
		ImageRectangle($image,0,0,$width-1,$height-1,$grey); 
		imageline($image, 0, $height/2, $width, $height/2, $grey); 
		imageline($image, $width/2, 0, $width/2, $height, $grey); 
		//Tell the browser what kind of file is come in 
		header("Content-Type: image/jpeg"); 
		//Output the newly created image in jpeg format 
		ImageJpeg($image);
		//Free up resources
		ImageDestroy($image);
		exit();
	}
	function compare_captcha($captcha){
		return isset($_SESSION['captcha'])&&$captcha == $_SESSION['captcha'];
	}
?>