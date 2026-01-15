<?php
namespace BasicCaptcha;

class Captcha{

  //fixed custom key, use your own
	private static string $key = 'lisnLJNBUI678624';

  //generate a token to be sent along with the captcha
	public static function generateFormToken() : string
	{ 
		return bin2hex(random_bytes(6)); 
		
	}

  //generate captcha value itself
	public static function generate(string $formToken):string
	{
		$c = hash('crc32',self::$key.$formToken,false); 
		$c = self::fixChars($c);
		return $c;
	}

  //check a captcha value (form input) using form token 
	public static function verify(string $captcha,string $formToken):bool
	{
		if(strlen($captcha) != 8){return false;}
		if(strlen($formToken) != 12){return false;}
		$check = self::generate($formToken);
		return $captcha == $check;
	}

  //force uppercase and replace easily mistaken characters
	private static function fixChars(string $s,  bool $reset = false) : string
	{
		$s = strtoupper($s);
		if($reset){
			$s = str_replace('%','0',$s); 
			$s = str_replace('?','O',$s); 
			$s = str_replace('#','I',$s); 
		}
		else{
			$s = str_replace('0','%',$s); 
			$s = str_replace('O','?',$s); 
			$s = str_replace('I','#',$s); 
		}
		return $s;
	}

  //generate a base64 image of a string captcha (exposed on the form)
	public static function getB64(string $captcha) : string
	{
		
		$image = imagecreatetruecolor(100, 35);
		$white = imagecolorallocate($image, 255, 255, 255);
		$black = imagecolorallocate($image, 0, 0, 0);
		imagefill($image, 0, 0, $white);
		imagestring($image, 4, 10, 10, $captcha, $black);
		imagefilter($image, IMG_FILTER_SMOOTH, 16);
		ob_start(); imagepng($image); $imageData = ob_get_clean();
		$base64 = base64_encode($imageData);
		imagedestroy($image);
		return $base64;
		
	}	
}




