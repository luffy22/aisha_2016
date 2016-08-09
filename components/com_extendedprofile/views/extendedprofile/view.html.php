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
           
            if((array_key_exists("UserId",$this->msg))&&($this->msg['astrologer']=="no"))
            {
                $tpl    = "user";
            }
            else
            {
               $tpl      = null;
            }
            parent::display($tpl);
	}
}