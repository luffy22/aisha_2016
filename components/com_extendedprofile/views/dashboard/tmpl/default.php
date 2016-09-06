<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
$user = JFactory::getUser();
if($user->guest)
 {
    $location   = JURi::base()."login";
    echo header('Location: '.$location);
 }
else
{
    echo $this->msg;
}