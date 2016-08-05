<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class ExtendedProfileModelExtendedProfile extends JModelItem
{
    public function getData()
    {
        $user = JFactory::getUser();
        $id   = $user->id;
        // get the data
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        
        $query          ->select($db->quoteName(array('astrologer','COUNT(*)')));
        $query          ->from($db->quoteName('#__user_extended'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query1);
        
        $result         = $db->loadAssoc();
        return $result;
    }
}
?>
