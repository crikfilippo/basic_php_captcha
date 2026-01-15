<?php

//----------------
//SIMPLE SHOWCASE
//---------------

require_once('Captcha.php');
use BasicCaptcha/Captcha;

$getCaptcha = $_GET['captcha'] ?? '';
$getToken	= $_GET['token'] ?? '';
$isFormSubmitted = strlen($getCaptcha) > 0;

?>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Basic Captha test</title>
	</head>
	
	<body>

		<?php

		if( ! $isFormSubmitted){ 

      //generate required data
			$token = Captcha::generateFormToken();
			$captcha = Captcha::generate($token);
			$base64 = Captcha::getB64($captcha);
			
			
			?>
			<form method="GET" action="/">
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<p><img src="data:image/png;base64,<?php echo $base64;?>" style="width:200px;"></p>
				<p><input required type="text" name="captcha" maxlength="<?php echo strlen($captcha); ?>" placeholder="Enter captcha code" style="width:200px; box-sizing: border-box;" /></p>
				<button type="submit" style="width:200px;">send</button>
			</form>
			<?php
			
		}
		
		else{

      //check if captcha is valid
			$isCaptchaValid = $isFormSubmitted && Captcha::verify($getCaptcha,$getToken);
			
			?>
			
			<p><?php echo $isCaptchaValid ? 'CAPTCHA VERIFICATION PASSED.' : 'WRONG CAPTCHA.' ; ?></p>
			
			<?php
      
		}
	
		?>

	</body>
</html>

