<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
//Import filesystem libraries. Perhaps not necessary, but does not hurt
jimport('joomla.filesystem.file');
class ExtendedProfileModelDashboard extends JModelItem
{
    public function getData()
    {
        $this->msg      =   "Hello World...";
        return $this->msg;
    }
}