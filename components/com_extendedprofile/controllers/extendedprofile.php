<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
class ExtendedProfileControllerExtendedProfile extends ExtendedProfileController
{
    public function registerAstro()
    {
        if(isset($_POST['submit_profile'])&&(isset($_POST['astro_confirm'])=="yes"))
        {
            if(empty($_POST['profile_pic']))
            {
                header('Location:'.JUri::base().'preference?upload_pic=none');
            }
            else
            {
                echo "He/She is an Astrologer";
            }
        }
        else if(isset($_POST['submit_profile'])&&(isset($_POST['astro_confirm'])=="no")){
                echo "He/She is not an Astrologer";
        }
    }
}
