<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_archive
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
define('_JEXEC', 1); 
/**
 * Helper for mod_allarticles
 */
class modAllarticlesHelper
{
	public function showArticles()
	{
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);
            $query          = "SELECT jv_content.id AS article_id, jv_content.alias as article_alias,
                            jv_content.asset_id AS article_assetid,jv_content.title, LEFT(jv_content.introtext,1000) AS article_text,
                            jv_content.hits, jv_categories.alias AS cat_alias, jv_categories.title as cat_title, jv_content.catid FROM jv_content INNER JOIN jv_categories
                            ON jv_content.catid = jv_categories.id ORDER BY jv_content.id DESC LIMIT 10"; 
            $db->setQuery($query);
  
            // Load the results as a list of stdClass objects (see later for more options on retrieving data).
           $results        = $db->loadAssocList();
           return $results;
	}
        public function getMoreArticlesAjax()
        {
            if(isset($_GET['lastid']))
            {
                $id             = str_replace("panel_","",$_GET['lastid']);
                $db             = JFactory::getDbo();  // Get db connection
                $query          = $db->getQuery(true);
                $query          = "SELECT jv_content.id AS article_id, jv_content.alias as article_alias,
                                    jv_content.asset_id AS article_assetid,jv_content.title, LEFT(jv_content.introtext,1000) AS article_text,
                                    jv_content.hits, jv_categories.alias AS cat_alias, jv_categories.title as cat_title, jv_content.catid FROM jv_content 
                                    INNER JOIN jv_categories ON jv_content.catid = jv_categories.id 
                                    WHERE jv_content.id < '".$id."' ORDER BY jv_content.id DESC LIMIT 1"; 
                $db->setQuery($query);
                $item           = array();
                // Load the results as a list of stdClass objects (see later for more options on retrieving data).
                $results        = $db->loadAssoc();
                $results        = json_encode($results);
                
            }
            else
            {
                $data = "Fail to load Data";
                $results   = json_encode($data);
            }
            echo $results;
        }
}
