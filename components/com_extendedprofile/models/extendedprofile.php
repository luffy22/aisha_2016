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
        
        $query          ->select($db->quoteName('UserId'));
        $query          ->from($db->quoteName('#__user_extended'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);
        $db->execute();
        $row            = $db->getNumRows();
        if($row== "0")
        {
            $query      ->clear();
            $query      ->select($db->quoteName('name'));
            $query      ->from($db->quoteName('#__users'));
            $query      ->where($db->quoteName('id').' = '.$db->quote($id));
            $db         ->setQuery($query);
            $row        = $db->loadAssoc();
            return $row;
        }
        else
        {
            return "Rows Present";
        }
    }
    public function saveUser($data)
    {
        //print_r($data);exit;
        $user           = JFactory::getUser();
        $id             = $user->id;

        $gender         = $data['gender'];$dob      = $data['dob'];
        $tob            = $data['tob'];$pob         = $data['pob'];$astro       = $data['astro'];
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $columns        = array('UserId','gender','dob','tob','pob','astrologer');
        $values         = array($db->quote($id),$db->quote($gender),$db->quote($dob),
                                $db->quote($tob),$db->quote($pob),$db->quote($astro));
        $query
        ->insert($db->quoteName('#__user_extended'))
        ->columns($db->quoteName($columns))
        ->values(implode(',', $values));
        
        // Set the query using our newly populated query object and execute it
        $db             ->setQuery($query);
        $result          = $db->query();
        
        if($result)
        {
            echo "Insertion Successful";
        }
        else
        {
            echo "Failed Insertion.";
        }
    }
}
?>
