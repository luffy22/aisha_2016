$(window).scroll(function(){
if ($(window).scrollTop() == $(document).height() - $(window).height()){
load_other_articles();
}
}); 
function load_other_articles()
{
     var id = $('#scroll_article').children().last().attr('id');
    var request = $.ajax({
    url: "?option=com_ajax&module=allarticles&method=getMoreArticles&format=json",
    data: "lastid="+id,
    dataType: "text"
    });
    request.done(function(data)
    {
        //var json    = data;
        //var obj     = $.parseJSON(json);
       alert(data); 
       //$('#scroll_article').append(data);
    }
    );
}