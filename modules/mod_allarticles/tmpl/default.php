<?php
JHtml::_('behavior.keepalive');
//print_r($allarticles);exit;
 $i = 0;
?>
<h3>Recent Articles</h3>
<div class="scroll_article" id="scroll_article">
<?php
foreach($allarticles as $data)
{  
    $data['slug'] = $data['article_id'].':'.$data['article_alias'];
    $data['catslug'] = $data['catid'].':'.$data['cat_alias'];
    $data['link'] = JRoute::_(ContentHelperRoute::getArticleRoute($data['slug'], $data['catslug']));
    $data['catlink']    = JRoute::_(ContentHelperRoute::getCategoryRoute($data['catid'], $language));
?>
<div class="panel panel-primary" id="panel_<?php echo $data['article_id']; ?>">
<div class="panel-heading"><h3 class="panel-title"><a href="<?php echo $data['link'] ?>" title="<?php echo $data['title'];?>"><?php echo $data['title']; ?></a></h3></div>
<div class="panel-body">
<p class="text-right"><strong>Hits: <?php echo $data['hits']; ?></strong></p>
<p><?php echo strip_tags(trim($data['article_text'])); ?></p>
<div class="panel-footer">
    <p class="text-left">Category: <a href="<?php echo $data['catlink']; ?>"><?php echo $data['cat_title'] ?></a></p>
</div>
</div>
</div>
<?php
}
?>
</div>