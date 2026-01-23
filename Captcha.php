<?php
namespace BasicCaptcha;

/**
 * Basic Captcha
 *
 * This class handles the generation, validation and rendering of CAPTCHAs.
 *
 * @author Filippo Maria Grilli
 * @github crikfilippo
 * @version 1.0.0
 * @since 2026-01-15
 * @license MIT
 * @link https://github.com/crikfilippo/basic_php_captcha
 *
 */

class Captcha{
	
	private static string $key = 'lisnLJNBUI678624'; //salt key, please use your own
	private static string $ive = 'fecb016b666c47c2'; //initialization vector, 16 characters
	private static string $alg = 'aes-128-cbc'; //encryption algo
	private static int $maxTokenSeconds = 3600; //max token time, in seconds

	//generate a token to be sent along with the captcha
	public static function generateFormToken() : string
	{
		$randomPrefix = bin2hex(random_bytes(2));
		$timeString = $randomPrefix.'_'.time();
		return  openssl_encrypt($timeString,self::$alg,self::$key,0,self::$ive);
	}

	//generate captcha value itself
	public static function generate(string $formToken) : string
	{
		$c = hash('crc32',self::$key.$formToken,false); 
		$c = self::fixChars($c);
		return $c;
	}
	
	//check a captcha value (form input) using form token 
	public static function verify(string $captcha,string $formToken) : bool
	{
		if(strlen($captcha) != 8){return false;}
		if(strlen($formToken) < 20 || strlen($formToken) > 100){return false;}
		if( ! self::isFormTokenInTime($formToken)){ return false; }
		$captcha = strtoupper($captcha);
		$check = self::generate($formToken);
		return $captcha == $check;
	}

	//check if form token was issued before allowed maximum
	private static function isFormTokenInTime(string $formToken) : bool
	{
		try{
			$formTokenTime =  openssl_decrypt($formToken,self::$alg,self::$key,0,self::$ive);
			$formTokenTime = substr($formTokenTime, (strpos($formTokenTime,'_') + 1) );
			if( ! is_numeric($formTokenTime)){return false;}
			return (time() - $formTokenTime) > self::$maxTokenSeconds ? false : true;
		}catch(\Throwable $e){
			return false;
		}
		
	}

	//force uppercase and replace easily mistaken characters
	private static function fixChars(string $s,  bool $reset = false) : string
	{
		$s = strtoupper($s);
		if($reset){
			$s = str_replace('%','0',$s); 
			$s = str_replace('?','0',$s); 
			$s = str_replace('#','0',$s); 
		}
		else{
			$s = str_replace('0',['%','#','?'][rand(0,2)],$s); ; 
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


