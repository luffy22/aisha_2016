<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class AstroLoginViewAstroLogin extends JViewLegacy
{
    protected $user;
    // Overwriting JView display method
    function display($tpl = null) 
    {
        // Get the view data.
        $this->user		= JFactory::getUser();
        // Assign data to the view
        $this->msg = 'Astro Login';

        // Display the view
        parent::display($tpl);
    }
}