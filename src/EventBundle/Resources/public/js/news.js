function newsListing(){
    var newsList = JSON.parse($('#newsList').val());

    for (var k in newsList) {
        if (newsList.hasOwnProperty(k)) {
            var html = [];
            html.push('<td>' + newsList[k].title + '</td>');
            html.push('<td>' + newsList[k].type + '</td>');
            html.push('<td>' + newsList[k].details + '</td>');
            html.push('<td><input type="button"  id="deleteNews" style="width:119px;" value="delete" class="btn red deleteNews" rel=' + newsList[k].id + ' ></td>');
            html = '<tr>' + html.join('') + '<tr>';
            $('#newsTable  > tbody:first').append(html);
        }
    }
}
newsListing();

$("#eventbundle_news_addNews").click( function(){
    if(newsFormValidation())
    {
        $.ajax({
            type: "POST",
            url : Routing.generate('event_news_create'),
            data :  $('#news_add_form').serialize(),
            success:function(news)
            {
                $("#news_add_form")[0].reset();
                var html = [];
                html.push('<td>' + news.title + '</td>');
                html.push('<td>' + news.type + '</td>');
                if(news.details == null){
                    html.push('<td>' + "Not Available" + '</td>');
                }else{
                    html.push('<td>' + jQuery.trim(news.details).substring(0, 20)
                        .split(" ").slice(0, -1).join(" ") + "..." + '</td>');
                }
                html.push('<td><input type="button"  id="deleteNews" style="width:127px;" value="delete" class="btn red deleteNews" rel=' + news.id + ' ></td>');
                html = '<tr>' + html.join('') + '<tr>';
                $('#newsTable  > tbody:first').append(html);
            }
        });
    }else {
        alert('You forgot to fill something out');
    }
});

$('.deleteNews').live("click", function() {

    var newsId = $(this).attr('rel');
    var parent = $(this).closest('tr');
    var answer     = confirm("Are you sure you want to delete this News?");
    if(answer) {
        $.ajax({
            type: "Get",
            url: Routing.generate('event_news_delete', {'id': newsId}),
            dateType: 'json',
            success: function (data) {
                parent.remove();
            }
        });
    }
});

$("#eventbundle_event_hasNews").change( function(){
    if ($(this).is(':checked')){
        $('.newsSection').show();
    } else {
        $('.newsSection').hide();
    }
});

function newsFormValidation() {

    var title = $.trim($('#eventbundle_news_title').val());
    var type = $.trim($('#eventbundle_news_type').val());

    if ( title === '' || (type === '')) {
        return false;
    } else {
        return true;
    }
}