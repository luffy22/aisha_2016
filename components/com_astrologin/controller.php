<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
 
/**
 * Astro Login Component Controller
 */
class AstroLoginController extends JControllerLegacy
{
    public function validateLogin()
    {
        if(isset($_POST['data']))
        {
            $uname  = $_POST['uname'];
            $passwd = $_POST['passwd'];
            
            echo $uname;
        }
        else
        {
            return false;
        }
    }
}
