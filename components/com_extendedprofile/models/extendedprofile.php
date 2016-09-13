<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
//Import filesystem libraries. Perhaps not necessary, but does not hurt
jimport('joomla.filesystem.file');
class ExtendedProfileModelExtendedProfile extends JModelItem
{
    public function redirectLink($url)
    {
        header('Location: '.$url);
    }
    public function getData()
    {
        $user = JFactory::getUser();
        $id   = $user->id;$name = $user->name;       
        // get the data
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array('UserId','membership')));
        $query          ->from($db->quoteName('#__user_astro'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);
        $astro          = $db->loadAssoc();
        $membership     = $astro['membership'];
        $db->execute();
        $row            = $db->getNumRows();//echo $row;exit;
        // if there are rows present fetch related data
        if($row > 0 && $membership =="free")
        {
            $query      ->clear();
            $query      ->select($db->quoteName(array('UserId')))
                        ->from($db->quoteName('#__user_astrologer'))
                        ->where($db->quoteName('UserId').' = '.$db->quote($id));
            $db         ->setQuery($query);
            $db         ->execute();unset($row);
            $row        = $db->getNumRows();
            if($row > 0)
            {
                $query      ->clear();
                $query      ->select($db->quoteName(array('UserId','img_1','img_1_id',
                                     'addr_1','addr_2', 'city','state','country',
                                    'postcode','phone','mobile','whatsapp','website', 'info','profile_status')))
                            ->from($db->quoteName('#__user_astrologer'))
                            ->where($db->quoteName('UserId').' = '.$db->quote($id));
                $db             ->setQuery($query);
                $astro          = $db->loadAssoc();
                return $astro;
            }
        }
        
        else  // if data and rows are absent fetch only name
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
        $membership     = $data['membership'];   // astrologer membership type free/paid
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);

        $columns        = array('UserId','membership');
        $values         = array($db->quote($id),$db->quote($membership));
        
        $query
        ->insert($db->quoteName('#__user_astro'))
        ->columns($db->quoteName($columns))
        ->values(implode(',', $values));
        
        // Set the query using our newly populated query object and execute it
        $db             ->setQuery($query);
        $result          = $db->query();
        
        if($result)
        {
            $app = JFactory::getApplication(); 
            $link = JURI::base().'dashboard';
            $msg = 'Successfully added Details'; 
            $app->redirect($link, $msg, $msgType='message');
        }
        else
        {
            $url         = JURI::base().'preference?data=fail';
            $this->redirectLink($url);
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
    public function saveAstro($details)
    {
        print_r($details);
        $ext            = JFile::getExt($details['img_name']);
        $uniq_name      = 'img_'.date('Y-m-d-H-i-s').'_'.uniqid().".".$ext;
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $src            = $details['tmp_name']; //echo $src."<br/>";
        $dest           = JPATH_BASE.DS."images". DS ."profiles".DS.$uniq_name;
        //echo $dest;exit;
        $id             = $details['id'];$img_name      = $details['img_name'];
        $img_id         = $uniq_name;    $addr1         = $details['addr1'];
        $addr2          = $details['addr2'];$city       = $details['city'];
        $state          = $details['state'];$country    = $details['country'];
        $pcode          = $details['pcode'];$phone      = $details['phone'];
        $mobile         = $details['mobile'];$whatsapp  = $details['whatsapp'];
        $website        = $details['website'];$info     = $details['info'];$status  = 'visible';
        $upload         = JFile::upload($src, $dest);
        if($upload)
        {
            $columns        = array('UserId','img_1','img_1_id','addr_1','addr_2','city',
                                    'state','country','postcode','phone','mobile','whatsapp',
                                    'website','info','profile_status');
            $values         = array($db->quote($id),$db->quote($img_name),$db->quote($img_id),
                                    $db->quote($addr1),$db->quote($addr2),$db->quote($city),
                                    $db->quote($state),$db->quote($country),$db->quote($pcode),
                                    $db->quote($phone),$db->quote($mobile),$db->quote($whatsapp),
                                    $db->quote($website),$db->quote($info),$db->quote($status));
            $query
            ->insert($db->quoteName('#__user_astrologer'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
            
            $db             ->setQuery($query);
            $result          = $db->query();

            if($result)
            {
                $this->getData();
            }
            else
            {
                echo "Failed Insertion.";
            }
        }
        else
        {
            echo "Failure";
        }
    }
}
?>
