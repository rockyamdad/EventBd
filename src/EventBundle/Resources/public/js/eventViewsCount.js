function eventViewsCount(){
    var url = $(location).attr('href');
    var id = url.substring(url.lastIndexOf('/') + 1);
    $.ajax({
        type: "POST",
        url : Routing.generate('event_views_calculate',{'id':id}),
        success:function()
        {
        }
    });

}
eventViewsCount();

