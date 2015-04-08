<?php
defined('_JEXEC') or die;
//echo "<pre>";
//echo print_r($topview);
//echo "</pre>";
?>
<div class="spacer"></div>
<div>
<div role="tabpanel"><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
<li class="active" role="presentation"><a href="#topcontent-1" role="tab" data-toggle="tab">Most Read</a></li>
<li role="presentation"><a href="#topcontent-2" role="tab" data-toggle="tab">Recent Popular</a></li>
</ul>
</div>
<!-- Tab panes -->
<div class="tab-content">
    <div class="spacer"></div>
<div id="topcontent-1" class="tab-pane active" role="tabpanel">
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
<div id="topcontent-2" class="tab-pane" role="tabpanel">
<?php
foreach($toprecent as $data)
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
</div>
</div>
<div class="spacer"></div>