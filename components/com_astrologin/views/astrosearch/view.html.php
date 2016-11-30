<?php
defined('_JEXEC') or die('Restricted access');

class AstroLoginViewAstroSearch extends JViewLegacy
{
    /*
    * Display the extended profile view
    */
    var $astro;
    var $pagination;
    function display($tpl = null)
    {
        $this->astro = $this->get('Data');
        $this->pagination = $this->get('Pagination'); 
 
       // print_r($this->pagination);exit;
        parent::display($tpl);
    }
}
