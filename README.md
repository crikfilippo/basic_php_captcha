# basic_php_captcha
A simple php class to generate and check captcha values.
<br><br>Notes:
<br>- Single file class, no database needed.
<br>- The check is not case sensitive.
<br>- A token can be used within 1 hour.

# usage
1. include the class in your project and assign a new own value to the static class property $key.
2. use ``` $formToken = Captcha::generateFormToken(); ```  to obtain a new token to be sent alongside the captcha value inserted by the form user.
3. use ``` $captchaValue = Captcha::generate($formToken); ```, passing the previously obtained form token, to obtain a new captcha value.
4. use ``` $captchaBase64 = Captcha::getB64($captchaValue); ```, passing the previously obtained captcha value, to obtain a base64 encoded image of the captcha value.
5. in a form, add an ``` <img src="data:image/png;base64,<?php echo $captchaBase64;?>"> ``` node, needed to show the user the captcha image.
6. in the same form, add a ``` <input type="text" maxlength="8" name="captcha" placeholder="Input the captcha code" required /> ``` input, needed to let the user input the captcha code.
7. in the form processing page, use ``` $isCaptchaValid = Captcha::verify($captcha,$formToken); ``` making sure both the arguments are obtained from the post submission, to check if the captcha input is valid.

# demo
You can try a simple form implementation including demo.php in your project.
Please remove the file when no more needed.

# screenshots
<img width="276" height="212" alt="image" src="https://github.com/user-attachments/assets/1058044a-9544-4a69-9c04-f2b1824327c5" />

