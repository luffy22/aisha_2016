<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HTML View class for the HelloWorld Component
 */
class HoroscopeViewLagna extends JViewLegacy
{
    function display($tpl = null) 
    {
        // Get data from the model
        // Check for errors.
        //$siderealTime  = $this->get('Lagna');
        if (count($errors = $this->get('Errors'))) 
        {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
        }
        


        // Display the template
        parent::display($tpl);
    }
}
