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
    public $data;
    function display($tpl = null) 
    {
        $data       = $this->data;
        if (count($errors   = $this->get('Errors')))
        {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
        }
        if(!empty($data))
        {
            $tpl    = 'lagna';
        }
        else
        {
            $tpl        = null;
        }
     
        //echo gettype($this->data);
        parent::display($tpl);
        
    }
    public function ascendant($tpl=null)
    {
        $data       = $this->data;
        if (count($errors   = $this->get('Errors')))
        {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
        }
        if(!empty($data))
        {
            $tpl    = 'asc';
        }
        else
        {
            $tpl        = null;
        }
     
        //echo gettype($this->data);
        parent::display($tpl);
    }
    public function moon($tpl=null)
    {
        $data       = $this->data;
        if (count($errors   = $this->get('Errors')))
        {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
        }
        if(!empty($data))
        {
            $tpl    = 'moon';
        }
        else
        {
            $tpl        = null;
        }
     
        //echo gettype($this->data);
        parent::display($tpl);
    }
}
