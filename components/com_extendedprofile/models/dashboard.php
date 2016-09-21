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
        $user = JFactory::getUser();
        if($user->guest)
         {
            $location   = JURi::base()."login";
            $mainframes = JFactory::getApplication();
            $link=  JURI::base().'login';
            $msg = "Please Login";

            $mainframes->redirect($link, $msg);
         }
        $id   = $user->id;$name = $user->name;  
        // get the data
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array('a.id','a.name','a.username','a.email', 
                                    'b.membership','b.img_1','b.img_1_id','b.addr_1','b.addr_2','b.city',
                                    'b.state','b.country','b.postcode','b.phone','b.mobile','b.whatsapp','b.website',
                                    'b.info','b.profile_status')));
        $query          ->from($db->quoteName('#__users', 'a'));
        $query          ->join('INNER', $db->quoteName('#__user_astrologer','b'). ' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserId') . ')');
        $query          ->where($db->quoteName('a.id').' = '.$db->quote($id));
        $db             ->setQuery($query);
        $db->execute();
        $row            = $db->getNumRows();
        if($row > 0)
        {
            $results =      $db->loadAssoc();
        }
        else
        {
            $results = "NoRow";
        }
        return $results;
    }
}