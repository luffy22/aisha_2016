<?php

defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class AstrologinModelAstroSearch extends JModelItem
{
    public function getAstrologer()
    {
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query              ->select($db->quoteName(array('b.number','b.membership','a.email',
                                    'a.name', 'a.registerDate', 'a.lastVisitDate','b.img_1','b.img_1_id',
                                    'b.addr_1','b.addr_2','b.city','b.state','b.country','b.postcode', 'b.phone','b.mobile','b.whatsapp','b.info')))
                            ->from($db->quoteName('#__users','a'))
                              ->join('INNER', $db->quoteName('#__user_astrologer', 'b') . ' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserId') . ')')
                            ->where($db->quoteName('b.approval_status').'='.$db->quote('approved').' AND '.
                                    $db->quoteName('b.profile_status').' = '.$db->quote('visible'));
       $db                  ->setQuery($query);
       
       $details         = $db->loadObjectList();
       return $details;
       
    }
}
