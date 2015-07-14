<?php

defined('_JEXEC') or die;  // No direct Access
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
class AstrologinModelAstroask extends JModelItem
{
    public function askQuestions($details)
    {
        $token      = uniqid('token_');
        $name       = ucfirst($details['name']);
        $email      = $details['email'];
        $gender     = ucfirst($details['gender']);
        $explain    = $details['explain'];
        $dob        = $details['dob'];
        $tob        = $details['tob'];
        $pob        = $details['pob'];
        $choice     = $details['choice'];
        $opt1       = $details['opt1'];
        $ques1      = $details['ques1'];
        $ques_det1  = $details['ques_det1'];
        $opt2       = $details['opt2'];
        $ques2      = $details['ques2'];
        $ques_det2  = $details['ques_det2'];
        $opt3       = $details['opt3'];
        $ques3      = $details['ques3'];
        $ques_det3  = $details['ques_det3'];
        
        
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);

        $columns        = array('UniqueID','name','email','gender', 'dob', 'tob', 'pob','choice','explain_choice',
                                'ques_topic1','ques_1','ques_1_explain',
                                'ques_topic2','ques_2','ques_2_explain',
                                'ques_topic3','ques_3','ques_3_explain'
                                );
        $values         = array(
                                $db->quote($token), $db->quote($name), $db->quote($email),
                                $db->quote($gender), $db->quote($dob), $db->quote($tob),
                                $db->quote($pob), $db->quote($choice),$db->quote($explain), 
                                $db->quote($opt1), $db->quote($ques1), $db->quote($ques_det1),
                                $db->quote($opt2), $db->quote($ques2), $db->quote($ques_det2),
                                $db->quote($opt3), $db->quote($ques3), $db->quote($ques_det3),
                                );
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
            $query              ->clear();
            $query              ->select($db->quoteName(array('UniqueID','name','email',
                                        'gender','dob','pob','tob', 'choice', 'explain_choice',
                                        'ques_topic1','ques_1','ques_1_explain',
                                        'ques_topic2','ques_2','ques_2_explain',
                                        'ques_topic3','ques_3','ques_3_explain')))
                                ->from($db->quoteName('#__questions'))
                                ->where($db->quoteName('UniqueID').'='.$db->quote($token));
           $db                  ->setQuery($query);
           $row                 = $db->loadAssoc();
           $details             = array(
                                            'token'=>$row['UniqueID'],'name'=>$row['name'],'email'=>$row['email'],
                                            'gender'=>$row['gender'],'dob'=>$row['dob'],'tob'=>$row['tob'],
                                            'pob'=>$row['pob'],'choice'=>$row['choice'],'explain'=>$row['explain_choice'],
                                            'ques_topic_1'=>$row['ques_topic1'],'ques_1'=>$row['ques_1'],'ques_1_explain'=>$row['ques_1_explain'],
                                            'ques_topic_2'=>$row['ques_topic2'],'ques_2'=>$row['ques_2'],'ques_2_explain'=>$row['ques_2_explain'],
                                            'ques_topic_3'=>$row['ques_topic3'],'ques_3'=>$row['ques_3'],'ques_3_explain'=>$row['ques_3_explain']
                                        );
           $this->sendConfirmMail($details);

        }
        else
        {
            $app        ->redirect('index.php?option=com_astrologin&view=astroask&failure=fail'); 
        }
    }
    protected function sendConfirmMail($details)
    {
        $name               = $details['name'];
        $gender             = $details['gender'];
        $dob                = $details['dob'];
        $pob                = $details['pob'];
        $tob                = $details['tob'];
        $choice             = $details['choice'];
        $explain            = $details['explain'];
        $to                 = $details['email'];
        $bcc                = 'kopnite@gmail.com';
        $subject            = "Ask AstroIsha Quesion Token No: ".$details['token'];
        $ques_topic1        = $details['ques_topic_1'];
        $ques_1             = $details['ques_1'];
        $ques_explain1      = $details['ques_1_explain'];
        $ques_topic2        = $details['ques_topic_2'];
        $ques_2             = $details['ques_2'];
        $ques_explain2      = $details['ques_2_explain'];
        $ques_topic3        = $details['ques_topic_3'];
        $ques_3             = $details['ques_3'];
        $ques_explain3      = $details['ques_3_explain'];
        
        if($explain=="detail")
        {
            $money  = 300;      // for detailed questions
            $body               = "Dear ".$name.",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>This is to confirm that your question form has been received. Once your payment of<html>&nbsp</html>".($money*$choice)."<html>&#8377;</html> is received we would process your query and give a detailed answer with logical solution to your questions in 7 Working Days.<html>&nbsp;</html>
                                    Once you transfer the money please reply back to this email with confirmation of payment. If payment is not received in 10 Working Days then that would be termed as Cancellation of Order.<br/><br/>";
            $body               .= "Your Details are as below.<br/><br/>";
            $body               .= "Name: ".$name."<br/>";
            $body               .= "Email: ".$to."<br/>";
            $body               .= "Gender: ".$gender."<br/>";
            $body               .= "Date Of Birth: ".$dob."<br/>";
            $body               .= "Time Of Birth: ".$tob."<br/>";
            $body               .= "Place Of Birth: ".$pob."<br/>";
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
            $money              = 100;      // for short questions
            $body               = "Dear ".$name.",<br/>"."<html>&nbsp;&nbsp;&nbsp;</html>This is to confirm that your question form has been received. Once your payment of<html>&nbsp;</html>".($money*$choice)."<html>&#8377;</html> is received we would process your query and give answers to your questions in 7 Working Days.<html>&nbsp;</html>
                                    Once you transfer the money please reply back to this email with confirmation of payment. If payment is not received in 10 Working Days then that would be termed as Cancellation of Order.<br/><br/>";
            $body               .= "Your Details are as below.<br/>";
            $body               .= "Name: ".$name."<br/>";
            $body               .= "Email: ".$to."<br/>";
            $body               .= "Gender: ".$gender."<br/>";
            $body               .= "Date Of Birth: ".$dob."<br/>";
            $body               .= "Time Of Birth: ".$tob."<br/>";
            $body               .= "Place Of Birth: ".$pob."<br/>";
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
       
        $mailer             = JFactory::getMailer();
        $config             = JFactory::getConfig();
        $sender             = array( 
                                        $config->get( 'mailfrom' ),
                                        $config->get( 'fromname' ) 
                                    );
 
        $mailer             ->setSender($sender);
        $mailer             ->addRecipient($to);
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
            $app                ->redirect('index.php?option=com_astrologin&view=quesconfirm'); 
        }
    }
}
?>
