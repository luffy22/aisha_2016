<?php
defined('_JEXEC') or die('Restricted access');

class ExtendedProfileViewDashboard extends JViewLegacy
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
            
            parent::display($tpl);
	}
}
