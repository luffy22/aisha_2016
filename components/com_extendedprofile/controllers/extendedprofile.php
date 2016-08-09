<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
class ExtendedProfileControllerExtendedProfile extends ExtendedProfileController
{
    public function registerAstro()
    {
        if(isset($_POST['submit_profile']))
        {
            if($_POST['astro_confirm']=="no")
            {
                $gender     = $_POST['gender_profile']; $dob    = $_POST['dob_profile'];
                $tob        = strtotime($_POST['tob_profile_hr'].":".$_POST['tob_profile_min'].":".$_POST['tob_profile_sec']);
                $pob        = $_POST['pob_profile'];$astro = "no";
                $user_details   = array(
                                        'gender'=>$gender,'dob'=>$dob,'pob'=>$pob,
                                    'tob'=>$tob,'astro'=>$astro
                                    );
                $model          = $this->getModel('extendedprofile');  // Add the array to model
                $data           = $model->saveUser($user_details);
            }
            else if($_POST['astro_confirm']=="yes")
            {
                if(!isset($_POST['condition_profile']))
                {
                    header('Location: '.JURi::base().'preference?terms=no');
                }
                else
                {
                    echo "He/She is an Astrologer";
                }
            }
        }
        
    }
    public function updateUser()
    {
        //if(isset($_POST['update_profile']))
        //{
            //echo "calls";exit;
            if($_POST['astro_confirm']=="no")
            {
                $gender     = $_POST['gender_profile']; $dob    = $_POST['dob_profile']; $id = $_POST['profile_id'];
                $tob        = strtotime($_POST['tob_profile_hr'].":".$_POST['tob_profile_min'].":".$_POST['tob_profile_sec']);
                $pob        = $_POST['pob_profile'];$astro = "no";
                $user_details   = array(
                                        'gender'=>$gender,'dob'=>$dob,'pob'=>$pob,
                                    'tob'=>$tob,'astro'=>$astro, 'userid'=>$id
                                    );
                $model          = $this->getModel('extendedprofile');  // Add the array to model
                $data           = $model->updateUser($user_details);
            }
            else if($_POST['astro_confirm']=="yes")
            {
                if(!isset($_POST['condition_profile']))
                {
                    header('Location: '.JURi::base().'preference?terms=no');
                }
                else
                {
                    echo "He/She is an Astrologer";
                }
            }
       // }
    }
}
