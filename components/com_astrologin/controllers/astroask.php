<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
class AstrologinControllerAstroask extends AstroLoginController
{
    public function askQuestions()
    {
        if(isset($_POST['ques_submit']))
        {
            $name           = $_POST['ques_name'];
            $email          = $_POST['ques_email'];
            $gender         = $_POST['ques_gender'];
            $dob            = $_POST['ques_dob'];
            $pob            = $_POST['ques_pob'];
            $tob            = $_POST['lagna_hr'].":".$_POST['lagna_min'].":".$_POST['lagna_sec']." ".$_POST['lagna_time'];
            $choice         = $_POST['ques_choice'];
            $explain        = $_POST['ques_explain'];
            if($explain == "short"&&$choice=="1")
            {
                $details    = array("name"=>$name,"email"=>$email,"gender"=>$gender,
                                    "dob"=>$dob,"pob"=>$pob, "tob"=>$tob, "choice"=>$choice);
                $model          = &$this->getModel('process');  // Add the array to model
                $model->registerUser($user_details);
            }
            else if($explain=="short"&&$choice=="2")
            {
                
            }
        }
        $model          = $this->getModel('Astroask', 'AstroLoginModel');
    }
}
?>
