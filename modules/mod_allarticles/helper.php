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
    public function MoreArticlesAjax()
    {
        echo "calls";exit;
        /*if(isset($_GET['lastid']))
        {
            $id                 = str_replace("panel_","",$_GET['lastid']);
            $results            = $this->getArticleList($id);
            $alldata            = array();
            foreach($results as $data)
            {
                $data->slug             = $data->article_id.':'.$data->article_alias;
                $data->catslug          = $data->cat_id.':'.$data->cat_alias;
                $data->link             = JRoute::_(ContentHelperRoute::getArticleRoute($data->slug, $data->catslug));
                $data->catlink          = JRoute::_(ContentHelperRoute::getCategoryRoute($data->cat_id, $data->language));
                $newdata                = array("art_link"=>$data->link,"cat_link"=>$data->catlink,
                                                "art_title"=>$data->title,"cat_title"=>$data->cat_title,
                                                "art_hits"=>$data->hits,"art_id"=>$data->article_id);
                array_push($alldata, $newdata);
            }
            return $alldata;
        }*/
    }
    public function showArticles()
    {
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          = "SELECT jv_content.id AS article_id, jv_content.alias as article_alias,
                            jv_content.title as title, jv_content.language as language,
                            LEFT(jv_content.introtext,1000) AS article_text,
                            jv_content.hits AS hits, jv_categories.alias AS cat_alias, jv_categories.title as cat_title, jv_content.catid AS cat_id FROM jv_content INNER JOIN jv_categories
                            ON jv_content.catid = jv_categories.id ORDER BY jv_content.id DESC LIMIT 10"; 
        $db->setQuery($query);

        // Load the results as a list of stdClass objects (see later for more options on retrieving data).
       $results        = $db->loadObjectList();
       return $results;
    }
    public function getArticleList($key)
    {
        $db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          = "SELECT jv_content.id AS article_id, jv_content.alias as article_alias,
                            jv_content.title as title, jv_content.language as language,
                            LEFT(jv_content.introtext,1000) AS article_text,
                            jv_content.hits AS hits, jv_categories.alias AS cat_alias, jv_categories.title as cat_title, jv_content.catid AS cat_id FROM jv_content INNER JOIN jv_categories
                            ON jv_content.catid = jv_categories.id WHERE id<".$key." ORDER BY jv_content.id DESC LIMIT 10"; 
        $db->setQuery($query);
               // Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results        = $db->loadObjectList();
        return $results;
    }  
}
