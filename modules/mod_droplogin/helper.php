<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Helper for mod_login
 *
 * @package     Joomla.Site
 * @subpackage  mod_login
 * @since       1.5
 */

class modDropLoginHelper
{
    public function LoginUserAjax()
    {
        if(isset($_GET['uname'])&&isset($_GET['pwd']))
        {
            $login  = $_GET['uname'];
            $pwd    = sha1($_GET['pwd']);
            
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);
            $query          ->select($db->quoteName(array('username', 'email', 'password', 'email','verification')));
            $query          ->from($db->quoteName('#__webusers'));
            $query          ->where((($db->quoteName('username').'='.$db->quote($login)).'OR'.($db->quoteName('username').'='.$db->quote($login))).'AND'.($db->quoteName('password').'='.$db->quote($pwd)));
            $db             ->setQuery($query);
            $count          = count($db->loadResult());
            $row            =$db->loadAssoc();

            if($count>0&&$row['verification']!= '0')
            {
                $lastlogin  = date("Y-m-d G:i:s");
                $query      ->clear();

                $query      ->getQuery(true);
                $query      ->update($db->quoteName('#__webusers'))
                            ->set($db->quoteName('lastlogin').'='.$db->quote($lastlogin))
                            ->where($db->quoteName('username').'='.$db->quote($login));
                $db         ->setQuery($query);
                $insertlogin   =$db->query();

                $session    =& JFactory::getSession();
                $session    ->set( 'username', $row['username'] );
                $session    ->set('email',$row['email']);
                
                $sessuser       = $session->get('username');
                $sessemail      = $session->get('email');
                echo trim($sessuser);?><a href="index.php?option=com_astrologin&task=process.userlogout&user=<?php echo $sessuser; ?>" class="btn btn-danger">Log Out</a>
            <?php
            }
            else if($count>0&&$row['verification']=='0')
            {
                $email      = $row['email'];
                $app        =&JFactory::getApplication();
                echo "no";
            }
            else
            {
                echo "invalid";
            }    
        }
    }
}
