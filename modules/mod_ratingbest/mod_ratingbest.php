<?php
 // no direct access
defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';


//$toprated 	= modTopContentHelper::gettoprated();
$toprating	= modRatingBestHelper::getTopRatedContent();
$layout           = $params->get('layout', 'default');
//$toprecent      = modTopContentHelper::getRecentTop();
require JModuleHelper::getLayoutPath('mod_ratingbest', $layout);

