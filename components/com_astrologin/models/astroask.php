<?php

defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class AstrologinModelAstroask extends JModelItem
{
public function askQuestions($details)
{

    $token              = uniqid('token_');
    $name               = ucfirst($details['name']);
    $email              = $details['email'];
    $gender             = ucfirst($details['gender']);
    $explain            = $details['explain'];
    $dob                = $details['dob'];
    $tob                = $details['tob'];
    $pob                = $details['pob'];
    $fees               = $details['fees'];
    
    $paytype            = $details['pay_type'];
    //echo $paytype;exit;
    $user_loc           = $details['user_loc'];
    $user_curr          = $details['user_curr'];
    $user_curr_full     = $details['user_curr_full'];
    $choice             = $details['choice'];
    $opt1               = $details['opt1'];
    $ques1              = $details['ques1'];
    $ques_det1          = $details['ques_det1'];
    $opt2               = $details['opt2'];
    $ques2              = $details['ques2'];
    $ques_det2          = $details['ques_det2'];
    $opt3               = $details['opt3'];
    $ques3              = $details['ques3'];
    $ques_det3          = $details['ques_det3'];


    $db             = JFactory::getDbo();  // Get db connection
    $query          = $db->getQuery(true);
    $query1         = $db->getQuery(true);
    $columns        = array('UniqueID','name','email','gender', 'dob', 'tob', 
                            'pob','fees','choice','explain_choice','payment_type',
                            'user_location','user_currency','user_curr_full',
                            'ques_topic1','ques_1','ques_1_explain',
                            'ques_topic2','ques_2','ques_2_explain',
                            'ques_topic3','ques_3','ques_3_explain'
                            );
    $values         = array(
                            $db->quote($token), $db->quote($name), $db->quote($email),
                            $db->quote($gender), $db->quote($dob), $db->quote($tob),$db->quote($pob),$db->quote($fees),
                            $db->quote($choice),$db->quote($explain),$db->quote($paytype),
                            $db->quote($user_loc),$db->quote($user_curr),$db->quote($user_curr_full),
                            $db->quote($opt1), $db->quote($ques1), $db->quote($ques_det1),
                            $db->quote($opt2), $db->quote($ques2), $db->quote($ques_det2),
                            $db->quote($opt3), $db->quote($ques3), $db->quote($ques_det3),
                            );
    $column1        =  array('UniqueID', 'status');
    $values1         = array($db->quote($token), $db->quote('Pending'));
    // Prepare the insert query
    $query    ->insert($db->quoteName('#__questions'))
                    ->columns($db->quoteName($columns))
                    ->values(implode(',', $values));
    // Set the query using our newly populated query object and execute it
    $db             ->setQuery($query);
    $result          = $db->query();
    // sending notification email via function
    if($result)
    {
       
        $query              ->select($db->quoteName(array('UniqueID','name','email',
                                    'gender','dob','pob','tob','fees','choice', 'explain_choice',
                                    'payment_type','user_currency','user_curr_full','user_location',
                                    'ques_topic1','ques_1','ques_1_explain',
                                    'ques_topic2','ques_2','ques_2_explain',
                                    'ques_topic3','ques_3','ques_3_explain')))
                            ->from($db->quoteName('#__questions'))
                            ->where($db->quoteName('UniqueID').'='.$db->quote($token));
       $db                  ->setQuery($query);
       $row                 = $db->loadAssoc();
       $details             = array(
                                        'token'=>$row['UniqueID'],'name'=>$row['name'],'email'=>$row['email'],'payment_type'=>$row['payment_type'],
                                        'gender'=>$row['gender'],'dob'=>$row['dob'],'pob'=>$row['pob'],'tob'=>$row['tob'], 'location'=>$row['user_location'],
                                        'fees'=>$row['fees'],'choice'=>$row['choice'],'explain'=>$row['explain_choice'],
                                        'user_curr'=>$row['user_currency'],'user_curr_full'=>$row['user_curr_full'],
                                        'ques_topic_1'=>$row['ques_topic1'],'ques_1'=>$row['ques_1'],'ques_1_explain'=>$row['ques_1_explain'],
                                        'ques_topic_2'=>$row['ques_topic2'],'ques_2'=>$row['ques_2'],'ques_2_explain'=>$row['ques_2_explain'],
                                        'ques_topic_3'=>$row['ques_topic3'],'ques_3'=>$row['ques_3'],'ques_3_explain'=>$row['ques_3_explain']
                                  );
       //print_r($details);exit;
       if($details['location']=="IN"&& $paytype == "ccavenue")
       {
            header('Location:'.JUri::base().'ccavenue/nonseam/ccavenue_payment.php?token='.$details['token'].'&name='.$details['name'].'&email='.$details['email'].'&curr='.$details['user_curr'].'&fees='.$details['fees']); 
       }
       if($details['location']=="IN"&& $paytype == "paytm")
       {
            header('Location:'.JUri::base().'PaytmKit/TxnTest.php?token='.$details['token'].'&email='.$details['email'].'&fees='.$details['fees']); 
       }
       else
       {
            header('Location:'.JUri::base().'vendor/paypal.php?token='.$details['token'].'&name='.$details['name'].'&email='.$details['email'].'&curr='.$details['user_curr'].'&fees='.$details['fees']); 
       }
    }
    else
    {
        $app        ->redirect('index.php?option=com_astrologin&view=astroask&failure=fail'); 
    }
}
// paypal authorize Order
public function authorizePayment($details)
{
    $paypal_id      = $details['paypal_id'];
    $auth_id       = $details['auth_id'];
    $token          = $details['token'];
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    // Fields to update.
    $fields = array(
        $db->quoteName('paypal_id').'='.$db->quote($paypal_id),
        $db->quoteName('authorize_id').'='.$db->quote($auth_id),
        $db->quoteName('status').'='.$db->quote('Authorized'));
    // Conditions for which records should be updated.
    $conditions = array(
        $db->quoteName('UniqueID').'='.$db->quote($token)
    );
    $query->update($db->quoteName('#__paypal_info'))->set($fields)->where($conditions);
 
    $db->setQuery($query);
 
    $result = $db->execute();
        $query      ->clear();
        $query              ->select($db->quoteName(array('a.UniqueID','a.name','a.email',
                                    'a.gender','a.dob','a.pob','a.tob','a.fees','a.choice','a.explain_choice',
                                    'a.user_currency','a.user_curr_full',
                                    'a.ques_topic1','a.ques_1','a.ques_1_explain',
                                    'a.ques_topic2','a.ques_2','a.ques_2_explain',
                                    'a.ques_topic3','a.ques_3','a.ques_3_explain','b.paypal_id','b.status')))
                            ->from($db->quoteName('#__questions','a'))
                              ->join('INNER', $db->quoteName('#__paypal_info', 'b') . ' ON (' . $db->quoteName('a.UniqueID').' = '.$db->quoteName('b.UniqueID') . ')')
                            ->where($db->quoteName('b.paypal_id').'='.$db->quote($paypal_id));
       $db                  ->setQuery($query);
       $details                 = $db->loadAssoc();
       $fees                = $details['fees'];
       $choice              = $details['choice'];

        $bcc                = 'kopnite@gmail.com';
        $subject            = "Ask AstroIsha Quesion Token No: ".$details['UniqueID'];
        $ques_topic1        = $details['ques_topic1'];
        $ques_1             = $details['ques_1'];
        $ques_explain1      = $details['ques_1_explain'];
        $ques_topic2        = $details['ques_topic2'];
        $ques_2             = $details['ques_2'];
        $ques_explain2      = $details['ques_2_explain'];
        $ques_topic3        = $details['ques_topic3'];
        $ques_3             = $details['ques_3'];
        $ques_explain3      = $details['ques_3_explain'];
        
        $body               = "Dear ".$details['name'].",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>This is to confirm that your question form has been received. Also your payment of ".$fees." ".$details['user_currency']."(".$details['user_curr_full'].")".
                                " has been authorized. We would process your query and give a detailed answer with logical solution to your questions in 7 Working Days. Your money would only be deducted once we have finished the report and mailed it to you.<br/><br/>";
        $body               .= "Your Details are as below.<br/><br/>";
        $body               .= "Name: ".$details['name']."<br/>";
        $body               .= "Email: ".$details['email']."<br/>";
        $body               .= "Gender: ".$details['gender']."<br/>";
        $body               .= "Date Of Birth: ".$details['dob']."<br/>";
        $body               .= "Time Of Birth: ".$details['tob']."<br/>";
        $body               .= "Place Of Birth: ".$details['pob']."<br/>";
        $body               .= "Token Number: ".$details['UniqueID']."<br/>";
        $body               .= "Payment ID: ".$details['paypal_id']."<br/>";
        $body               .= "Status: ".$details['status']."<br/>";
        $body               .= "Number Of Questions: ".$details['choice']."<br/>";
        $body               .= "Explanation (Detail/Short): ".ucfirst($details['explain_choice'])."<br/><br/>";
        if($details['explain_choice'] == 'short')
        {
            for($i=0;$i<$choice;$i++)
            {
                $j              = $i+1;
                $body               .= "<strong>Question ".$j.":</strong><br/>";
                $body               .= "Topic: ".${"ques_topic".$j}."<br/>";
                $body               .= "Question: ".${"ques_".$j}."<br/><br/>";
            }
        }
        else if($details['explain_choice']== 'detail')
        {
            for($i=0;$i<$choice;$i++)
            {
                $j=$i+1;
                $body               .= "<strong>Question ".$j.":</strong><br/>";
                $body               .= "Topic: ".${"ques_topic".$j}."<br/>";
                $body               .= "Question: ".${"ques_".$j}."<br/>";
                $body               .= "Background: ".${"ques_explain".$j}."<br/><br/>";
            }
        }
    $body               .= "<br/><div style='align:right'>Admin At Astro Isha,<br/>Rohan Desai</div>";        
    $mailer             = JFactory::getMailer();
    $config             = JFactory::getConfig();
    $sender             = array( 
                                    $config->get( 'mailfrom' ),
                                    $config->get( 'fromname' ) 
                                );

    $mailer             ->setSender($sender);
    $mailer             ->addRecipient($details['email']);
    $mailer             ->addBCC($bcc, 'Rohan Desai');
    $mailer             ->setSubject($subject);
    $mailer             ->isHTML(true);
    $mailer             ->Encoding = 'base64';
    $mailer             ->setBody($body);

    $send = $mailer->Send();
    if ( $send !== true ) {
        echo 'Error sending email: ' . $send->__toString();
    } else {
        $app                =&JFactory::getApplication();
        $app                ->redirect('index.php?option=com_astrologin&view=quesconfirm&payment=paypal'); 
    }

}
public function failPayment($details)
{
    $token          = $details['token'];
    $fail_id        = $details['fail_id'];
    $db         = JFactory::getDbo();
    $query      = $db->getQuery(true);
     $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    // Fields to update.
    $fields = array(
        $db->quoteName('authorize_id') . ' = ' . $db->quote('no'),
        $db->quoteName('paypal_id').'='.$db->quote($fail_id),
        $db->quoteName('status').'='.$db->quote('Cancelled'));
    // Conditions for which records should be updated.
    $conditions = array(
        $db->quoteName('UniqueID').'='.$db->quote($token)
    );
    $query->update($db->quoteName('#__paypal_info'))->set($fields)->where($conditions);
 
    $db->setQuery($query);
 
    $result = $db->execute();
    if($result)
    {
        $query      ->clear();
        $query              ->select($db->quoteName(array('UniqueID','name','email')))
                            ->from($db->quoteName('#__questions'))
                            ->where($db->quoteName('UniqueID').'='.$db->quote($token));
        $db                  ->setQuery($query);
        $details                 = $db->loadAssoc();

        $bcc                = 'kopnite@gmail.com';
        $subject            = "Astro Isha Failed Transaction ID: ".$details['UniqueID'];
            
        $body               = "Dear ".$details['name'].",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>Your order with Astro Isha was cancelled. Please try again if you wish your query to be resolved by Astro Isha. If you 
                               do not wish paid consultation please ignore this email.<br/><br/>";
        
        $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";        
        $mailer             = JFactory::getMailer();
        $config             = JFactory::getConfig();
        $sender             = array( 
                                    $config->get( 'mailfrom' ),
                                    $config->get( 'fromname' ) 
                                );

        $mailer             ->setSender($sender);
        $mailer             ->addRecipient($details['email']);
        $mailer             ->addBCC($bcc, 'Rohan Desai');
        $mailer             ->setSubject($subject);
        $mailer             ->isHTML(true);
        $mailer             ->Encoding = 'base64';
        $mailer             ->setBody($body);

        $send = $mailer->Send();
        if ( $send !== true ) {
            echo 'Error sending email: ' . $send->__toString();
        } else {
            $app                =&JFactory::getApplication();
            $app                ->redirect('index.php?option=com_astrologin&view=quesconfirm&payment=false');
            
    }
}
}
public function confirmCCPayment($details)
{
    $token              = $details['token'];
    $trackid            = $details['trackid'];
    $bankref            = $details['bankref'];
    $status             = $details['status'];
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    // Fields to update.
    $fields = array(
        $db->quoteName('ccavenue_track_id') . ' = ' . $db->quote($trackid),
        $db->quoteName('ccavenue_bank_ref_no') . ' = ' . $db->quote($bankref),
        $db->quoteName('ccavenue_confirm') . ' = ' . $db->quote('yes'),
        $db->quoteName('ccavenue_status') . ' = ' . $db->quote($status)
        );
    // Conditions for which records should be updated.
    $conditions = array(
        $db->quoteName('UniqueID').'='.$db->quote($token)
    );
    $query->update($db->quoteName('#__questions'))->set($fields)->where($conditions);
 
    $db->setQuery($query);
 
    $result = $db->execute();
    if($result)
    {
        $query      ->clear();
        $query              ->select($db->quoteName(array('UniqueID','name','email',
                                    'gender','dob','pob','tob','fees','choice','explain_choice',
                                    'user_currency','user_curr_full','ccavenue_track_id', 'ccavenue_bank_ref_no',
                                    "ccavenue_confirm","ccavenue_status",
                                    'ques_topic1','ques_1','ques_1_explain',
                                    'ques_topic2','ques_2','ques_2_explain',
                                    'ques_topic3','ques_3','ques_3_explain')))
                            ->from($db->quoteName('#__questions'))
                            ->where($db->quoteName('UniqueID').'='.$db->quote($token));
       $db                  ->setQuery($query);
       $details                 = $db->loadAssoc();
    }
    $name               = $details['name'];
    $gender             = $details['gender'];
    $dob                = $details['dob'];
    $fees               = $details['fees'];
    $pob                = $details['pob'];
    $tob                = $details['tob'];
    $choice             = $details['choice'];
    $explain            = $details['explain_choice'];
    $to                 = $details['email'];
    $bcc                = 'kopnite@gmail.com';
    $subject            = "Ask AstroIsha Quesion Token No: ".$details['UniqueID'];
    $ques_topic1        = $details['ques_topic1'];
    $ques_1             = $details['ques_1'];
    $ques_explain1      = $details['ques_1_explain'];
    $ques_topic2        = $details['ques_topic2'];
    $ques_2             = $details['ques_2'];
    $ques_explain2      = $details['ques_2_explain'];
    $ques_topic3        = $details['ques_topic3'];
    $ques_3             = $details['ques_3'];
    $ques_explain3      = $details['ques_3_explain'];
    if($details['ccavenue_status']=='Success')
    {
    if($explain=="detail")
    {
        $body               = "Dear ".$details['name'].",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>This is to confirm that your question form has been received. Also your payment of ".$fees." ".$details['user_currency']."(".$details['user_curr_full'].")".
                                " has been authorized. We would process your query and give a detailed answer with logical solution to your questions in 7 Working Days. Your money would only be debited from your Account once we have finished the report and mailed it to you.<br/><br/>";
        $body               .= "Your Details are as below.<br/><br/>";
        $body               .= "Name: ".$name."<br/>";
        $body               .= "Email: ".$to."<br/>";
        $body               .= "Gender: ".$gender."<br/>";
        $body               .= "Date Of Birth: ".$dob."<br/>";
        $body               .= "Time Of Birth: ".$tob."<br/>";
        $body               .= "Place Of Birth: ".$pob."<br/>";
        $body               .= "Token Number: ".$details['UniqueID']."<br/>";
        $body               .= "Track ID: ".$details['ccavenue_track_id']."<br/>";
        $body               .= "Bank Reference Number: ".$details['ccavenue_bank_ref_no']."<br/>";
        $body               .= "Number Of Questions: ".$choice."<br/>";
        $body               .= "Explanation (Detail/Short): ".$explain."<br/><br/>";
        for($i=0;$i<$choice;$i++)
        {
            $j=$i+1;

            $body               .= "<strong>Question ".$j.":</strong><br/>";
            $body               .= "Topic: ".${"ques_topic".$j}."<br/>";
            $body               .= "Question: ".${"ques_".$j}."<br/>";
            $body               .= "Background: ".${"ques_explain".$j}."<br/><br/>";
        }
            $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
    }
    else if($explain=="short")
    {

        $body               = "Dear ".$details['name'].",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>This is to confirm that your question form has been received. Also your payment of ".$fees." ".$details['user_currency']."(".$details['user_curr_full'].")".
                                " has been authorized. We would process your query and give a detailed answer with logical solution to your questions in 7 Working Days. Your money would only be debited from your Account once we have finished the report and mailed it to you.<br/><br/>";
        $body               .= "Your Details are as below.<br/><br/>";
        $body               .= "Name: ".$name."<br/>";
        $body               .= "Email: ".$to."<br/>";
        $body               .= "Gender: ".$gender."<br/>";
        $body               .= "Date Of Birth: ".$dob."<br/>";
        $body               .= "Time Of Birth: ".$tob."<br/>";
        $body               .= "Place Of Birth: ".$pob."<br/>";
        $body               .= "Token Number: ".$details['UniqueID']."<br/>";
        $body               .= "Track ID: ".$details['ccavenue_track_id']."<br/>";
        $body               .= "Bank Reference Number: ".$details['ccavenue_bank_ref_no']."<br/>";
        $body               .= "Number Of Questions: ".$choice."<br/>";
        $body               .= "Explanation (Detail/Short): ".$explain."<br/><br/>";
        for($i=0;$i<$choice;$i++)
        {
            $j              = $i+1;
            $body               .= "<strong>Question ".$j.":</strong><br/>";
            $body               .= "Topic: ".${"ques_topic".$j}."<br/>";
            $body               .= "Question: ".${"ques_".$j}."<br/><br/>";
        }
            $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
    }
}
else if($details['ccavenue_status']=='Invalid'||
        $details['ccavenue_status']=='Aborted'||
        $details['ccavenue_status']=='Failure')
{
    $body               = "Dear ".$details['name'].",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>We were unable to process your request due to Cancellation Of Order. Please try again to make payment.";
    $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
}
else
{
    $body               = "Dear ".$details['name'].",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>Something went wrong and we are unable to process your Order. Please try again later.";
    $body               .= "<br/><div style='align:right'>Your Sincerely,<br/>Admin(Rohan Desai)</div>";
}
    $mailer             = JFactory::getMailer();
    $config             = JFactory::getConfig();
    $sender             = array( 
                                    $config->get( 'mailfrom' ),
                                    $config->get( 'fromname' ) 
                                );
    $mailer             ->setSender($sender);
    $mailer             ->addRecipient($details['email']);
    $mailer             ->addBCC($bcc, 'Rohan Desai');
    $mailer             ->setSubject($subject);
    $mailer             ->isHTML(true);
    $mailer             ->Encoding = 'base64';
    $mailer             ->setBody($body);

    $send = $mailer->Send();
    if ( $send !== true ) {
        echo 'Error sending email: ' . $send->__toString();
    }
    else if($details['ccavenue_status']=='Invalid'||
        $details['ccavenue_status']=='Aborted')
    {
        $app                =&JFactory::getApplication();
        $app                ->redirect('index.php?option=com_astrologin&view=quesconfirm&payment_success=false');
    }
    else {
        $app                =&JFactory::getApplication();
        $app                ->redirect('index.php?option=com_astrologin&view=quesconfirm&payment=ccavenue'); 
    }
}
    
}
?>
