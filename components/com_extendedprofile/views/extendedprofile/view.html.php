<?php
defined('_JEXEC') or die('Restricted access');

class ExtendedProfileViewExtendedProfile extends JViewLegacy
{
    /*
    * Display the extended profile view
    */
    public $msg;
    function display($tpl = null)
	{
            // Assign data to the view
            $this->msg = $this->get('Data');
            //print_r($this->msg);exit;
            if(array_key_exists("UserId",$this->msg))
            {
               $tpl    = "astro";
            }
            
            else
            {
               $tpl      = null;
            }
            parent::display($tpl);
	}
}