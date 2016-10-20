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
        $query          ->select($db->quoteName(array('UserId','membership','img_1','img_1_id',
                                     'addr_1','addr_2', 'city','state','country',
                                    'postcode','phone','mobile','whatsapp','website', 'info','profile_status')));
        $query          ->from($db->quoteName('#__user_astrologer'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);
        $astro          = $db->loadAssoc();
        return $astro;
    }
    public function saveUser($data)
    {
        //print_r($data);exit;
        $user           = JFactory::getUser();
        $id             = $user->id;
        $membership     = $data['membership'];   // astrologer membership type free/paid
        $amount         = $data['amount'];$curr = $data['currency'];$country=  $data['country'];
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select(array('UserId','membership'));
        $query          ->from($db->quoteName('#__user_astrologer'));
        $query          ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);$db->execute();
        $row            = $db->getNumRows();    
        $app            = JFactory::getApplication();
        
        if($row > 0)
        {
                $link       = JURI::base().'dashboard?data=double';
                $msg        = "Data Already Exists..";
                $app        ->redirect($link,$msg);
        }
        else
        {
            $query          ->clear();
            if($membership=='paid')
            {
                $membership     = "unpaid";
                $columns        = array('UserId','membership');
                $values         = array($db->quote($id),$db->quote($membership));
            }
            else
            {
                $columns        = array('UserId','membership');
                $values         = array($db->quote($id),$db->quote($membership));
            }
            $query
            ->insert($db->quoteName('#__user_astrologer'))
            ->columns($db->quoteName($columns))
            ->values(implode(',', $values));
            // Set the query using our newly populated query object and execute it
            $db             ->setQuery($query);
            $result          = $db->query();
            if($membership=='unpaid')
            {
                $query      ->clear();
                $columns    = array('UserId','amount','currency','location');
                $values     = array($db->quote($id),$db->quote($amount),$db->quote($curr),$db->quote($country));
                $query
                    ->insert($db->quoteName('#__user_finance'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
                $db             ->setQuery($query);$db->query();
            }
            if($result)
            {
                if($membership=='unpaid')
                {
                    $token              = uniqid('token_');
                    $query  ->clear();
                    $query          ->select(array('a.id','a.name','a.email','b.currency','b.amount'));
                    $query          ->from($db->quoteName('#__users','a'));
                    $query          ->join('INNER', $db->quoteName('#__user_finance','b'). ' ON (' . $db->quoteName('a.id').' = '.$db->quoteName('b.UserId') . ')');
                    $query          ->where($db->quoteName('id').' = '.$db->quote($id));
                    $db             ->setQuery($query);
                    $result         = $db->loadAssoc();
                    //print_r($result);exit;
                    if($data['pay_type'] == 'online'&& $data['currency']=='INR')
                    {
                        $link   = JUri::base().'ccavenue/nonseam/ccavenue_astrologer.php?id='.$id.'&token='.$token.'&name='.$result['name'].'&email='.$result['email'].'&curr='.$result['currency'].'&amount='.$result['amount']; 
                        $app->redirect($link);
                    }
                    else if($data['pay_type'] == 'online'&& $data['currency']!=='INR')
                    {
                        $link   = JUri::base().'vendor/paypal_astro.php?id='.$id.'&token='.$token.'&name='.$result['name'].'&email='.$result['email'].'&curr='.$curr.'&amount='.$amount;
                        $app->redirect($link);
                    }
                    else
                    {
                        echo "Not Online Payment";
                    }
                }
                else
                {
                    $link = JURI::base().'dashboard';
                    $msg = 'Successfully added Details'; 
                    $app->redirect($link, $msg, $msgType='message');
                }
            }
            else
            {
                $link = JURI::base().'dashboard?data=fail';
                $msg = 'Unable to add details'; 
                $app->redirect($link, $msg, $msgType='message');
            }
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
        //print_r($details);exit;
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
            $fields         = array(
                                $db->quoteName('img_1').'='.$db->quote($img_name),
                                $db->quoteName('img_1_id').'='.$db->quote($img_id),
                                $db->quoteName('addr_1').'='.$db->quote($addr1),
                                $db->quoteName('addr_2').'='.$db->quote($addr2),
                                $db->quoteName('city').'='.$db->quote($city),
                                $db->quoteName('state').'='.$db->quote($state),
                                $db->quoteName('country').'='.$db->quote($country),
                                $db->quoteName('postcode').'='.$db->quote($pcode),
                                $db->quoteName('phone').'='.$db->quote($phone),
                                $db->quoteName('mobile').'='.$db->quote($mobile),
                                $db->quoteName('whatsapp').'='.$db->quote($whatsapp),
                                $db->quoteName('website').'='.$db->quote($website),
                                $db->quoteName('info').'='.$db->quote($info),
                                );
            $conditions = array(
                                    $db->quoteName('UserId') . ' = '.$db->quote($id)
                                );
            $query->update($db->quoteName('#__user_astrologer'))->set($fields)->where($conditions);

            $db->setQuery($query);
            $result          = $db->query();
            $app = JFactory::getApplication();
            $link=  JURI::base().'dashboard';
            if($result)
            {
                $msg = "Data Added Successfully..";
                
            }
            else
            {
               $msg  = "Failed to add data...";
            }
        }
        else
        {
            $msg  = "Failed to add data...";
        }
        $app->redirect($link,$msg);
    }
    public function updateAstro($details)
    {
       //print_r($data);exit;
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $id             = $details['id'];$img_name      = $details['img_name'];
        $img_id         = $uniq_name;    $addr1         = $details['addr1'];
        $addr2          = $details['addr2'];$city       = $details['city'];
        $state          = $details['state'];$country    = $details['country'];
        $pcode          = $details['pcode'];$phone      = $details['phone'];
        $mobile         = $details['mobile'];$whatsapp  = $details['whatsapp'];
        $website        = $details['website'];$info     = $details['info'];$status  = 'visible';
        $fields         = array(
                                $db->quoteName('addr_1').'='.$db->quote($addr1),
                                $db->quoteName('addr_2').'='.$db->quote($addr2),
                                $db->quoteName('city').'='.$db->quote($city),
                                $db->quoteName('state').'='.$db->quote($state),
                                $db->quoteName('country').'='.$db->quote($country),
                                $db->quoteName('postcode').'='.$db->quote($pcode),
                                $db->quoteName('phone').'='.$db->quote($phone),
                                $db->quoteName('mobile').'='.$db->quote($mobile),
                                $db->quoteName('whatsapp').'='.$db->quote($whatsapp),
                                $db->quoteName('website').'='.$db->quote($website),
                                $db->quoteName('info').'='.$db->quote($info),
                                );
        $conditions = array(
                                $db->quoteName('UserId') . ' = '.$db->quote($id)
                            );
        $query->update($db->quoteName('#__user_astrologer'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
        $app            = JFactory::getApplication();
        $link           = JURI::base().'dashboard?profile_update=success';
        $msg            = "Profile Updated Successfully... ";
        $app            ->redirect($link, $msg);
       
    }
}
?>
