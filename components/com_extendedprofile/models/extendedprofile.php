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
        
        $query          ->select($db->quoteName(array('UserId','astrologer')));
        $query          ->from($db->quoteName('#__user_extended'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);
        $astro          = $db->loadAssoc();
        $astro          = $astro['astrologer'];
        
        $db->execute();
        $row            = $db->getNumRows();
        if($row !== "0")
        {
            
            $query      ->clear();
            if($astro == "yes")
            {
                return "calls";
            }
            else 
            {
                $query      ->select($db->quoteName(array('a.name','b.UserId', 'b.gender', 
                                       'b.dob','b.tob','b.pob','b.astrologer')))
                            ->from($db->quoteName('#__users','a'))
                            ->join('INNER', $db->quoteName('#__user_extended', 'b') .' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserId') . ')')
                            ->where($db->quoteName('b.UserId').' = '.$db->quote($id));
                $db         ->setQuery($query);
                $result     = $db->loadAssoc();
            } 
            return $result;
        }
        else
        {
            $query      ->clear();
            $query      ->select($db->quoteName(array('name')));
            $query      ->from($db->quoteName('#__users'));
            $query      ->where($db->quoteName('id').' = '.$db->quote($id));
            $db         ->setQuery($query);
            $result     = $db->loadAssoc();
            return $result;
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
    public function updateUser($data)
    {
        //print_r($data);exit;
        $userid         = $data['userid'];
        //echo $userid;exit;
        $gender         = $data['gender'];$dob      = $data['dob'];
        $tob            = $data['tob'];$pob         = $data['pob'];$astro       = $data['astro'];
        // Get db connection
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
 
        $fields         = array($db->quoteName('gender').' = '.$db->quote($gender),
                                $db->quoteName('dob').' = '.$db->quote($dob),
                                $db->quoteName('tob').' = '.$db->quote($tob),
                                $db->quoteName('pob').' = '.$db->quote($pob),
                                $db->quoteName('astrologer').' = '.$db->quote($astro));
        $conditions     = array($db->quoteName('UserId').' = '.$db->quote($userid));
        
        $query->update($db->quoteName('#__user_extended'))->set($fields)->where($conditions);
        $db->setQuery($query);
 
        $result = $db->execute();
        return $result;
    }
}
?>