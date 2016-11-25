<?php
defined('_JEXEC') or die('Restricted access');

class AstroLoginViewAstroSearch extends JViewLegacy
{
    /*
    * Display the extended profile view
    */
    public $astro;
    function display($tpl = null)
    {
        $this->astro = $this->get('Astrologer');
        //print_r($this->astro);exit;
        parent::display($tpl);
    }
}
