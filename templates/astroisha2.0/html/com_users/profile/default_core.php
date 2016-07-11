<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="table-responsive">
<h3><?php echo JText::_('COM_USERS_PROFILE_CORE_LEGEND'); ?></h3>
<table class="table table-bordered table-hover">
    <tr>
	<th><?php echo JText::_('COM_USERS_PROFILE_NAME_LABEL'); ?></th>
        <td><?php echo $this->data->name; ?></td>
    </tr>
    <tr>
	<th><?php echo JText::_('COM_USERS_PROFILE_USERNAME_LABEL'); ?></th>
        <td><?php echo htmlspecialchars($this->data->username); ?></td>
    </tr>
    <tr>
	<th><?php echo JText::_('COM_USERS_PROFILE_REGISTERED_DATE_LABEL'); ?></th>
        <td><?php echo JHtml::_('date', $this->data->registerDate); ?></td>
    </tr>
    <tr>
	<th><?php echo JText::_('COM_USERS_PROFILE_LAST_VISITED_DATE_LABEL'); ?></th>
        <?php if ($this->data->lastvisitDate != '0000-00-00 00:00:00'){?>
			<td>
				<?php echo JHtml::_('date', $this->data->lastvisitDate); ?>
			</td>
		<?php }
		else
		{?>
			<td>
				<?php echo JText::_('COM_USERS_PROFILE_NEVER_VISITED'); ?>
			</td>
		<?php } ?></tr>

