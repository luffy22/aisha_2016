<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_archive
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Helper for mod_allarticles
 */
jimport('joomla.html.pagination');
class ModAllarticlesHelper
{
	public function showArticles()
	{
            $db             = JFactory::getDbo();  // Get db connection
            $query          = $db->getQuery(true);
            $mainframe =JFactory::getApplication();
 
            $limit = $mainframe->getUserStateFromRequest( "global.list.limit", 'limit', $mainframe->getCfg('list_limit'), 'uint');
            $limitstart = $mainframe->getUserStateFromRequest( "$limit.limitstart", 'limitstart', 0 );
            /*$query 		->select($db->quoteName(array('id','alias','asset_id','title','introtext','catid', 'hits')))
                                ->from($db->quoteName('#__content'))
                                 ->order('hits DESC'.' LIMIT 5');*/
            $query          = "SELECT SQL_CALC_FOUND_ROWS jv_content.id AS article_id, jv_content.alias as article_alias,
                            jv_content.asset_id AS article_assetid,jv_content.title, LEFT(jv_content.introtext,1000) AS article_text,
                            jv_content.hits, jv_categories.alias AS cat_alias, jv_content.catid FROM jv_content INNER JOIN jv_categories
                            ON jv_content.catid = jv_categories.id ORDER BY jv_content.id DESC"; 
            $db->setQuery($query, $limitstart, $limit);
  
            // Load the results as a list of stdClass objects (see later for more options on retrieving data).
            $results        = $db->loadAssocList();
           if(empty($results))
           {
               return "Error Fetching Article Data.";
           }
           else
           {
               
               $query1       = "SELECT FOUND_ROWS();";
               $db->setQuery($query1);
               $data     = $db->loadResult();
               $pag         = new JPagination($data,$limitstart,$limit);
                echo $pag->getListFooter();
               $i=1;
               foreach($results as $data)
               {
                    $data['slug'] = $data['article_id'].':'.$data['article_alias'];
                    $data['catslug'] = $data['catid'].':'.$data['cat_alias'];
                    $data['link'] = JRoute::_(ContentHelperRoute::getArticleRoute($data['slug'], $data['catslug']));
                ?>

                <p><strong><a href="<?php echo $data['link'] ?>" title="<?php echo $data['title'];?>"><?php echo $data['title']; ?></a></strong></p>
                <p><?php echo strip_tags(trim($data['article_text'])); ?></p>
               <?php
               }
              echo $pag->getListFooter();
           }
	}
}
