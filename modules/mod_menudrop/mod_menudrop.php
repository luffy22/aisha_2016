<?php
///defined('_JEXEC') or die;

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$list		= ModMenuDropHelper::getList($params);
$base           = ModMenuDropHelper::getBase($params);
require JModuleHelper::getLayoutPath('mod_menudrop', $params->get('layout', 'default'));


