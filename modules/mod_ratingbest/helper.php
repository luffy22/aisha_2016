<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

define('_JEXEC', 1);

/**
 * Helper for mod_login
 *
 * @package     Joomla.Site
 * @subpackage  mod_login
 * @since       1.5
 */
class modRatingBest
{
    public function getTopRatedId()
    {
	$db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          = "SELECT rating_count, content_id, rating_sum FROM jv_content_rating";
        $db->setQuery($query);
        $result = $db->loadObject();
        print_r($result);
        //echo $count;
        //echo "No Way. It is true!!";
    }
}
