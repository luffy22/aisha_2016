<script type="text/javascript">
window.onscroll = function(ev) {
    if((window.location.pathname ==  window.location.origin)&&(window.innerHeight + window.scrollY) >= document.body.scrollHeight) {
	var lastid      = $('.panel').last().attr("id");
	var request = $.ajax({
	 url: "index.php?option=com_ajax&module=allarticles&format=raw&method=MoreArticles",
	data: "b_rashi="+lastid,
	dataType: "text"
	});
	request.done(function(msg)
	{
		alert(msg);
	});
	request.fail(function()
	{
		alert("Fail to get data");
	});
}
</script>	
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
    $data->slug             = $data->article_id.':'.$data->article_alias;
    $data->catslug          = $data->cat_id.':'.$data->cat_alias;
    $data->link             = JRoute::_(ContentHelperRoute::getArticleRoute($data->slug, $data->catslug));
    $data->catlink          = JRoute::_(ContentHelperRoute::getCategoryRoute($data->cat_id, $data->language));
?>
<div class="panel panel-primary" id="panel_<?php echo $data->article_id; ?>">
<div class="panel-heading"><h3 class="panel-title"><a href="<?php echo $data->link ?>" title="<?php echo $data->title;?>"><?php echo $data->title; ?></a></h3></div>
<div class="panel-body">
<p class="text-right"><strong>Hits: <?php echo $data->hits; ?></strong></p>
<p><?php echo strip_tags(trim($data->article_text))."..."; ?><a href="<?php echo $data->link ?>" title="Click on link to read whole article">Read More</a></p>
<div class="panel-footer">
    <p class="text-left">Category: <a href="<?php echo $data->catlink; ?>" title="Browse through Category: <?php echo $data->cat_title; ?> articles"><?php echo $data->cat_title; ?></a></p>
</div>
</div>
</div>
<?php
}
?>
</div>
