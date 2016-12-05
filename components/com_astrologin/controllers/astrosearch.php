<?php
defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/controller.php';
class AstrologinControllerAstrosearch extends AstroLoginController
{
    public function getDetails()
    {
        if(isset($_POST['profile_full']))
        {
            $url        = $_POST['current_url'];
            $user_id    = $_POST['user_id'];
            $details    = array(
                                    "url"   =>$url, "user_id"=>$user_id
                                );
           print_r($details);exit;
            $model          = $this->getModel('astrosearch');  // Add the array to model
            $data           = $model->getDetails($details);
        }
    }
}
?>
