<?php
defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
//Import filesystem libraries. Perhaps not necessary, but does not hurt
jimport('joomla.filesystem.file');
class ExtendedProfileModelFinance extends JModelItem
{
    public function getData()
    {
        $user = JFactory::getUser();
        $id   = $user->id;$name = $user->name;       
        // get the data
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          ->select($db->quoteName(array('acc_holder_name','acc_number','acc_bank_name',
                                        'acc_bank_addr','acc_iban','acc_swift_code','acc_ifsc',
                                        'acc_paypalid')))
                        ->from($db->quoteName('#__user_finance'))
                        ->where($db->quoteName('UserId').' = '.$db->quote($id));
        $db             ->setQuery($query);
        $result         = $db->loadAssoc();
        return $result;
        
    }
    public function saveDetails($details)
    {
        //print_r($details);exit;
        $acc_name           = $details['acc_name'];$acc_number              = $details['acc_number'];
        $acc_bank_name      = $details['acc_bank_name'];$acc_bank_addr      = $details['acc_bank_addr'];
        $acc_iban           = $details['acc_iban'];$acc_swift               = $details['acc_swift'];
        $acc_ifsc           = $details['acc_ifsc'];$acc_paypal              = $details['acc_paypal'];
        $user           = JFactory::getUser();
        $id             = $user->id;

        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        
        $fields         = array($db->quoteName('acc_holder_name').' = '.$db->quote($acc_name),
                                $db->quoteName('acc_number').' = '.$db->quote($acc_number),
                                $db->quoteName('acc_bank_name').' = '.$db->quote($acc_bank_name),
                                $db->quoteName('acc_bank_addr').' = '.$db->quote($acc_bank_addr),
                                $db->quoteName('acc_iban').' = '.$db->quote($acc_iban),
                                $db->quoteName('acc_swift_code').' = '.$db->quote($acc_swift),
                                $db->quoteName('acc_iban').' = '.$db->quote($acc_iban),
                                $db->quoteName('acc_ifsc').' = '.$db->quote($acc_ifsc),
                                $db->quoteName('acc_paypalid').' = '.$db->quote($acc_paypal));
        $conditions     = array($db->quoteName('UserId').' = '.$db->quote($id));
        
        
        // Set the query using our newly populated query object and execute it
        $query->update($db->quoteName('#__user_finance'))->set($fields)->where($conditions);
        $db->setQuery($query);
 
        $result = $db->execute();

        if($result)
        {
            $app = JFactory::getApplication(); 
            $link = JURI::base().'dashboard?data=success';
            $msg = 'Successfully added Financial Details'; 
            $app->redirect($link, $msg, $msgType='message');
        }
        else
        {
            $app = JFactory::getApplication(); 
            $link = JURI::base().'dashboard?data=fail';
            $msg = 'Unable to add financial details'; 
            $app->redirect($link, $msg, $msgType='message');
        }
    }
}
