<?php
defined('_JEXEC') or die;
//echo "<pre>";
//echo print_r($topview);
//echo "</pre>";
?>
<div class="spacer"></div>
<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Most Read</a></li>
</ul>
<div class="spacer"></div>
<div id="topcontent-1">
<?php
foreach($topview as $data)
{
    $data->slug = $data->article_id.':'.$data->article_alias;
    $data->catslug = $data->catid.':'.$data->cat_alias;
    $data->link = JRoute::_(ContentHelperRoute::getArticleRoute($data->slug, $data->catslug));
?>
<h3><?php echo trim($data->title); ?></h3> 
<div>
    <div><strong>Hits: <?php echo $data->hits; ?></strong></div>
<p>
<?php
        
        echo substr(strip_tags(trim($data->introtext)), 1, 1000);
        echo "....";
?>
    <a href="<?php echo $data->link; ?>" title="<?php echo $data->title; ?>">Read More</a>
</p>    
</div>
<?php
}
?>
</div>
<div class="spacer"></div>