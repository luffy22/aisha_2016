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
            if(empty($this->msg))
            {
                $app = JFactory::getApplication(); 
                $link = JURI::base().'dashboard';
                $app->redirect($link);
            }
            
            else
            {
                $tpl    = "astro";
            }
            parent::display($tpl);
	}
}