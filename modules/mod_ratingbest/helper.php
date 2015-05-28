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
class modRatingBestHelper
{
    public function getTopRatedContent()
    {
	$db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          = "SELECT jv_content_rating.content_id, jv_content_rating.rating_sum,
                                jv_content_rating.rating_count,
                                jv_content.id AS article_id, jv_content.alias AS article_alias,
                                jv_content.title, jv_categories.id AS cat_id, jv_categories.alias AS cat_alias
                                FROM jv_content_rating INNER JOIN jv_content ON jv_content_rating.content_id = jv_content.id
                                INNER JOIN jv_categories ON jv_content.catid = jv_categories.id ORDER BY jv_content_rating.rating_count DESC LIMIT 10";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }
    
}
