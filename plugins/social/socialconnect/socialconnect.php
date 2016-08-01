<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgContentSocialConnect extends JPlugin
{
	protected $autoloadLanguage = true;
	
	public function onContentAfterTitle($context, &$article, &$params, $page =0)
	{
?>
		<div class="fb-like" data-layout="box_count" data-action="like" data-size="small" data-show-faces="true" data-share="true"></div>
<?php	
	}
}
?>
