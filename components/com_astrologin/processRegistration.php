<?php
// No direct access to this file
if(isset($_POST['register']))
{
    include_once $_SERVER['DOCUMENT_ROOT'] . '/jvidya/securimage/securimage.php';
    $securimage = new Securimage();
    if ($securimage->check($_POST['captcha_code']) == false)
    {
        // the code was incorrect
        // you should handle the error so that the form processor doesn't continue

        // or you can use the following code if there is no validation or you do not know how
        echo "The security code entered was incorrect.<br /><br />";
        echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
        exit;
    }
 else 
    {
        $username       = $_POST['username'];
        $password       = sha1($_POST['password']);
        $email          = $_POST['email'];
        $logintype      = '1';
        $joindate       = date("Y/m/d-G:i:s");
        $webauthcode    = 'webauth_'+rand(1000,9999);
        
        //$db             = JFactory::getDbo();  // Get db connection
        //$query          = $db->getQuery(true);
        //$columns        = array('username','password','email', 'logintype', 'joindate', 'webauthcode');
        //$values         = array('$username', '$password', '$email', '$logintype', '$joindate', '$webauthcode');
    }
}
?>
