<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';

/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class AstrologinControllerProcess extends AstroLoginController
{
public function userregister()
{
    if(isset($_POST['register']))
    {
        /*$code= JRequest::get('recaptcha_response_field');     
        JPluginHelper::importPlugin('captcha');
        $dispatcher = JDispatcher::getInstance();
        $res = $dispatcher->trigger('onCheckAnswer',$code);
        if(!$res[0])
        {
            die('Invalid Captcha Code.');
        }
        else
        {*/
            $model          = $this->getModel('Process', 'AstroLoginModel');
            $username       = $_POST['username'];
            $password       = $_POST['password'];
            $email          = $_POST['email'];
            $logintype      = '1';
            $joindate       = date("Y/m/d-G:i:s");
            $webauthcode    = 'webauth_'.rand(1000000,9999999);

            $user_details   = array('username'=>$username,'password'=> $password, 'email'=> $email,'logintype'=> $logintype,'joindate'=>$joindate,'webauthcode'=>$webauthcode);
            $model          = &$this->getModel('process');  // Add the array to model
            $model->registerUser($user_details);
        //}
    }
    else if(isset($_POST['fblogin']))
    {
        echo "fb login successful";
    }
    else
    {
        echo "fail";
    }
}
public function userlogin()
{
    if(isset($_POST['login']))
    {
        $model          = $this->getModel('Process', 'AstroLoginModel');
        $uname          = $_POST['username'];
        $pwd            = $_POST['password'];

        if(isset($_POST['remember']))
        {
            $remember   = $_POST['remember'];
        }
        else
        {
            $remember   = "no";
        }

        $login_details  = array('username'=>$uname, 'password'=>$pwd, 'rememberme'=>$remember);
        $model          = &$this->getModel('process');  // Add the array to model
        $model->getDetails($login_details);
    }
}
public function userlogout()
{
    $user                   = $_GET['user'];
    $model                  = &$this->getModel('process');
    $model->logoutuser($user);
}
public function ConfirmUser()
{
    if((isset($_GET['ref']))&&(isset($_GET['email'])))
    {
        $details            = array('email'=>$_GET['email'], 'auth'=>$_GET['ref']);
        $model              = &$this->getModel('process');   // Add the array to model
        $model              ->ConfirmUser($details);
    }
}
public function forgotpwd()
{
    if(isset($_POST['forgotpwd']))
    {
        $forgotemail        = $_POST['forgotemail'];
        $model              = &$this->getModel('process');
        $model              ->ForgotPwd($forgotemail);
    }
}
public function resetpwd()
{
    if(isset($_POST['resetpwd'])&&($_POST['forgotpwd']==$_POST['repass']))
    {
        $resetemail         = $_POST['resetemail'];
        $resetpwd           = $_POST['forgotpwd'];
        $resetdetails       = array('resemail'=>$resetemail, 'respwd'=> $resetpwd);
        $model              = &$this->getModel('process');
        $model              ->ResetPwd($resetdetails);
    }
    else
    {
        echo "Passwords do not match";
    }
}

}
?>
