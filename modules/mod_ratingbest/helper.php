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
    public function getArticle_CatId($array)
    {

       $count       = count($array[id]);
       //$newarray    = array();
       for($i=0;$i<$count;$i++)
       {
            $id     = $array[id][$i];
            $db     = JFactory::getDbo();  // Get db connection
            $query  = $db->getQuery(true);
            $query  = "SELECT jv_content.id AS article_id, jv_content.alias AS article_alias,
                        jv_content.title, jv_categories.id AS cat_id, jv_categories.alias AS cat_alias
                        FROM jv_content INNER JOIN jv_categories ON jv_content.catid = jv_categories.id WHERE jv_content.id = '$id'";
           $db->setQuery($query);
           $result = $db->loadObjectList();
           foreach($result as $rating)
           {
            $newarray[]    = array("rating"=>$array[stars][$i],"article_id"=>$rating->article_id,
                                "cat_id"=>$rating->cat_id,"cat_alias"=>$rating->cat_alias,
                                "article_alias"=>$rating->article_alias,"title"=>$rating->title);    
           }
       }
       ?>
<h3>Most Rated</h3>
<div class="row">
    <div class="col-xs-12 col-md-8"><strong>Title</strong></div>
    <div class="col-md-1"><strong>Rating</strong></div>
</div>
      <?php 
       for($j=0;$j<count($newarray);$j++)
       {
           $data_slug       = $newarray[$j]['article_id'].':'.$newarray[$j]['article_alias'];
           $data_catslug    = $newarray[$j]['cat_id'].':'.$newarray[$j]['cat_alias'];
           $data_title      = $newarray[$j]['title'];
           $data_rating     = $newarray[$j]['rating'];
           $data_link       = JRoute::_(ContentHelperRoute::getArticleRoute($data_slug, $data_catslug));
       ?>
<div class="row">   
           <div class="col-xs-12 col-md-8">
            <a href="<?php echo $data_link; ?>"><?php echo $data_title ?></a>
            </div>
           <div class="col-md-1">
                <?php echo $data_rating; ?>
           </div>
       </div>
       <?php
       }
       /*foreach($result as $value)
       {
           echo $value->article_id."<br/>";
           echo $value->cat_id."<br/>";
           echo $value->title."<br/>";
           echo $value->cat_alias."<br/>";
           echo $value->article_alias."<br/>";
       }*/
    }
    public function getTopRatedId()
    {
	$db             = JFactory::getDbo();  // Get db connection
        $query          = $db->getQuery(true);
        $query          = "SELECT rating_count, content_id, rating_sum FROM jv_content_rating ORDER BY rating_count DESC, content_id DESC LIMIT 10";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        foreach($result as $rating)
        {
            $rating_stars[]     = (int)$rating->rating_sum/(int)$rating->rating_count."<br/>";
            $rating_id[]        = $rating->content_id;
        }
        $array                  = array("id"=>$rating_id,"stars"=>$rating_stars);
        self::getArticle_CatId($array);
    }
    
}
