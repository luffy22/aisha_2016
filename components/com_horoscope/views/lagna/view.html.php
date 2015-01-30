<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.application.component.modelitem');
/**
 * HTML View class for the HelloWorld Component
 */
class HoroscopeViewLagna extends JViewLegacy
{
    function display($tpl = null) 
    {
        // Get data from the model
        // Check for errors.
        $data  = $this->get('Data');
        if (count($errors = $this->get('Errors'))) 
        {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
        }
        if(empty($this->data))
        {
            parent::display($tpl);
        }
        else
        {
            echo data;
            //$this->loadTemplate('lagna');
        }
    }
}
