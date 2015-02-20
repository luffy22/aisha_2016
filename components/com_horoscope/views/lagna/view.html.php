<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport('joomla.application.component.controller');
/**
 * HTML View class for the HelloWorld Component
 */
class HoroscopeViewLagna extends JViewLegacy
{
    protected $data;
    function display($tpl = null) 
    {
       
        // Get data from the model
        // Check for errors.
        $model          = &$this->getModel('lagna');  // Add the array to model
        $lagna          = $model->getData();

        if (count($errors = $this->get('Errors'))) 
        {
              
                JError::getError();
                // Add a message to the message queue
               
        }
        $this->data         = &$lagna;
        if(empty($data))
        {
            parent::display($tpl);
        }
        else
        {
            $tpl            = "lagna";
            parent::display($tpl);
        }
    }
    
}
