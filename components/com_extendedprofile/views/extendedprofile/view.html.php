<?php
defined('_JEXEC') or die('Restricted access');

class ExtendedProfileViewExtendedProfile extends JViewLegacy
{
    /*
    * Display the extended profile view
    */
    function display($tpl = null)
	{
		// Assign data to the view
		$this->msg = 'Extended Profile...';
 
		// Display the view
		parent::display($tpl);
	}
}