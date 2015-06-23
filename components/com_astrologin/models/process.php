<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');

class AstroLoginModelProcess extends JModelItem
{
    // register user and send notification email
    public function registerUser($user_details)
    {
        $username     = $user_details['username'];
        $password     = sha1($user_details['password']);
        $email        = $user_details['email'];
        $logintype          = $user_details['logintype'];
        $joindate           = $user_details['joindate'];
        $webauthcode        = $user_details['webauthcode'];
        $app                =&JFactory::getApplication();
        //$session            = JFactory::getSession();
        //$session            ->set('cusername', $username);
        //$session            ->set('cemail', $email);
        if($this->checkUsername($username)==false)
        {
            $app        ->redirect('index.php?option=com_astrologin&view=astroregister&dupuname=duplicate'); 

        }
        else if($this->checkEmail($email)==false)
        {
            $app        ->redirect('index.php?option=com_astrologin&view=astroregister&dupemail=duplicate2'); 
        }
        else
        {
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);

            $columns        = array('username','password','email', 'logintype', 'joindate', 'webauthcode');
            $values         = array($db->quote($username), $db->quote($password), $db->quote($email),
                                    $db->quote($logintype), $db->quote($joindate), $db->quote($webauthcode));
            // Prepare the insert query
            $query    ->insert($db->quoteName('#__webusers'))
                            ->columns($db->quoteName($columns))
                            ->values(implode(',', $values));
            // Set the query using our newly populated query object and execute it
            $db             ->setQuery($query);
            $result          = $db->query();
            
            // sending notification email via function
            if($result)
            {
               $credentials     = array('email'=>$email,'username'=>$username);
               $this->sendAuthMail($credentials);
            }
            else
            {
                $app        ->redirect('index.php?option=com_astrologin&view=astroregister&failure=fail'); 
            }
        }
    }
    // Check if Username present in DB
    public function checkUsername($username)
    {
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          -> select($db->quoteName('username'));
        $query          -> from($db->quoteName('#__webusers'));
        $query          -> where($db->quoteName('username').'='.$db->quote($username));
        $db             ->setQuery($query);
        $count          = count($db->loadAssoc());
		$app        	=&JFactory::getApplication();
        if($count>0)
        {
           return false;
        }
        else
        {
            return true;
        }
    }
    // Check if Email present in DB
     public function checkEmail($email)
    {
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          -> select($db->quoteName('email'));
        $query          -> from($db->quoteName('#__webusers'));
        $query          -> where($db->quoteName('email').'='.$db->quote($email));
        $db             ->setQuery($query);
        $count          = count($db->loadAssoc());

        if($count>0)
        {
           return false;
        }
        else
        {
            return true;
        }
    }
    // Login 
    function getDetails($login_details)
    {
        $loginuname     = $login_details['username'];
        $loginpwd       = sha1($login_details['password']);
        $remember       = $login_details['rememberme'];
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array('username', 'email', 'password', 'email','verification')));
        $query          ->from($db->quoteName('#__webusers'));
        $query          ->where((($db->quoteName('username').'='.$db->quote($loginuname)).'OR'.($db->quoteName('username').'='.$db->quote($loginuname))).'AND'.($db->quoteName('password').'='.$db->quote($loginpwd)));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
        $row            =$db->loadAssoc();
        $app        	=&JFactory::getApplication();
        if($count>0&&$row['verification']!= '0')
        {
            $lastlogin  = date("Y/m/d-G:i:s");
            $query      ->clear();
            $app        =&JFactory::getApplication();
            $query      ->getQuery(true);
            $query      ->update($db->quoteName('#__webusers'))
                        ->set($db->quoteName('lastlogin').'='.$db->quote($lastlogin))
                        ->where($db->quoteName('username').'='.$db->quote($loginuname));
            $db         ->setQuery($query);
            $insertlogin   =$db->query();

            $session    =& JFactory::getSession();
            $session    ->set( 'username', $row['username'] );
            $session    ->set('email',$row['email']);
            
            $app        ->redirect('index.php');        
           
        }
        else if($count>0&&$row['verification']=='0')
        {
            $email      = $row['email'];
            $app        ->redirect("index.php?option=com_astrologin&view=validateuser&email='$email'"); 
        }
        else
        {
            $app        ->redirect('index.php?option=com_astrologin&view=astrologin&failure=fail'); 
        }
    }
    // Logout the User
   public function logoutuser($user)
   {
       $session         =& JFactory::getSession();
       $sessuser        = $session->get('username');

       if((!empty($sessuser))&&($sessuser==$user))
       {
           $session->clear('username');
           $session->clear('email');
           $app        =&JFactory::getApplication();
           $app        ->redirect('index.php'); 
       }
   }
   // Sending Authentication Mail on Registration
   public function sendAuthMail($credentials)
   {
        $email          = $credentials['email'];
        $username       = $credentials['username'];
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName('webauthcode'), COUNT(1));
        $query          ->from($db->quoteName('#__webusers'));
        $query          ->where($db->quoteName('email').'='.$db->quote($email));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
        $row            =$db->loadAssoc();
        $webauth        = $row['webauthcode'];
        $weburl         = "index.php?option=com_astrologin&task=process.ConfirmUser&email=$email&ref=$webauth";
        
        $mailer         = JFactory::getMailer();
        $recepient      = $email;
        $part1          = "Confirmation Email. Click on the link below\n";
        $part2          = 'http://'.$_SERVER['SERVER_NAME'].'/'.$weburl;
        $subject        = "Email Verification";
        $body           = $part1.$part2;

        $send           = $mailer->sendMail('admin@astroisha.com', 'Luffy Mugiwara<Administrator>', $recepient, $subject, $body);
        if( $send !== true ) 
        {
            echo 'Error sending email: ' . $send->__toString();
            echo "Please Go Back";
        }
        else
        {
            $app        =&JFactory::getApplication();
            $app        ->redirect('index.php?option=com_astrologin&view=astrologin&sendmail=success');
        }
   }
   public function ConfirmUser($details)
   {
        $authref            = $details['auth'];
        $emailref          = $details['email'];
       
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName('webauthcode'), $db->quoteName('email'));
        $query          ->from($db->quoteName('#__webusers'));
        $query          ->where($db->quoteName('webauthcode').'='.$db->quote($authref).'AND'.$db->quoteName('email').'='.$db->quote($emailref));
        $db             ->setQuery($query);
        $count          = count($db->loadResult());
        
        if($count>0)
        {
            $query->clear();
            $query      ->getQuery(true);
            $query      ->update($db->quoteName('#__webusers'))
                        ->set($db->quoteName('verification').'='.'1')
                        ->where($db->quoteName('email').'='.$db->quote($emailref));
            $db         ->setQuery($query);
            $confirm    = $db->query();
            
            $app        =&JFactory::getApplication();
            $app        ->redirect("index.php?option=com_astrologin&view=astrologin&confirm=yes");
        }
   }
   // Sends reset password link to email
   public function ForgotPwd($forgotemail)
   {
        $recepient       = $forgotemail;
        $weburl         = "index.php?option=com_astrologin&view=resetpwd&email=$forgotemail";
        $mailer         = JFactory::getMailer();
        $part1          = "Astro Isha \nClick on the link below to reset your Password\n";
        $part2          = 'http://'.$_SERVER['SERVER_NAME'].'/'.$weburl;
        $subject        = "Reset Password";
        $body           = $part1.$part2;

        $send           = $mailer->sendMail('admin@astroisha.com', 'Luffy Mugiwara<Administrator>', $recepient, $subject, $body);
        if( $send !== true ) 
        {
            echo 'Error sending email: ' . $send->__toString();
        }
        else
        {
            echo "Please check your email to reset password";
        }
   }
   // Reset Pwd and redirect to login
   public function ResetPwd($resetdetails)
   {
       $email           = $resetdetails['resemail'];
       $pwd             = $resetdetails['respwd'];
       
       $db              = JFactory::getDbo();  // Get db connection
       $query           = $db->getQuery(true);
       $query           ->update($db->quoteName('#__webusers'))
                        ->set($db->quoteName('password').'='.$db->quote($pwd))
                        ->where($db->quoteName('email').'='.$db->quote($email));
       $confirm         = $db->query();
            
        $app        =&JFactory::getApplication();
        $app        ->redirect("index.php?option=com_astrologin&view=astrologin&reset=pwd");
   }
}
?>
