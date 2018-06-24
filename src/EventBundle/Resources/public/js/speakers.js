function speakerListing(){
    var baseurl = document.location.origin;
    var speakerList = JSON.parse($('#speakerList').val());

    for (var k in speakerList) {
        if (speakerList.hasOwnProperty(k)) {

            var img = baseurl+"/uploads/speakerPhoto/"+ speakerList[k].path;
            var html = [];
            html.push('<td>' + speakerList[k].firstName + '</td>');
            html.push('<td>' + speakerList[k].lastName + '</td>');
            html.push('<td>' + speakerList[k].description + '</td>');
            html.push('<td> <div class="product-item" style="background: none;padding: 0;width: 80px;"><div class="pi-img-wrapper" style="width: 80px;"> <img src="' + img + '" style="height: 50px;width: 80px;" class="img-responsive"> </div> </div> </td>');
            html.push('<td><input type="button"  id="deleteSpeaker" style="width:119px;" value="delete" class="btn red deleteSpeaker" rel=' + speakerList[k].id + ' ></td>');
            html = '<tr>' + html.join('') + '<tr>';
            $('#speakerTable  > tbody:first').append(html);

        }
    }
}

speakerListing();

$("#eventbundle_speaker_addSpeaker").click( function(){
    if(speakerFormValidation())
    {
        var baseurl = document.location.origin;
        var rajax_obj = new rajax('speaker_add_form',
            {
                action: Routing.generate('event_speaker_create'),
                responseType: 'json',
                data :  $('#speaker_add_form').serialize(),
                onComplete: function (speaker) {
                    $("#speaker_add_form")[0].reset();
                    var img = baseurl+"/uploads/speakerPhoto/"+ speaker.photo;
                    var html = [];
                    html.push('<td>' + speaker.firstName + '</td>');
                    html.push('<td>' + speaker.lastName + '</td>');
                    if( speaker.description == null){
                        html.push('<td>' + "Not Available" + '</td>');
                    }else{
                        html.push('<td>' + jQuery.trim(speaker.description).substring(0, 20)
                            .split(" ").slice(0, -1).join(" ") + "..." + '</td>');
                    }
                    if( speaker.photo == null){
                        html.push('<td>' + "Not Available" + '</td>');
                    }else{
                        html.push('<td> <div class="product-item" style="background: none;padding: 0;width: 80px;"><div class="pi-img-wrapper" style="width: 80px;"> <img src="' + img + '" style="height: 50px;width: 80px;" class="img-responsive"> </div> </div> </td>');
                    }
                    html.push('<td><input type="button"  id="deleteSpeaker" style="width:119px;" value="delete" class="btn red deleteSpeaker" rel=' + speaker.id + ' ></td>');

                    html = '<tr>' + html.join('') + '<tr>';
                    $('#speakerTable  > tbody:first').append(html);

                }
            });
    }else {
        alert('You forgot to fill something out');
    }

    rajax_obj.post();
});

$('.deleteSpeaker').live("click", function() {

    var speakerId = $(this).attr('rel');
    var parent = $(this).closest('tr');
    var answer     = confirm("Are you sure you want to delete this Speaker?");
    if(answer) {
        $.ajax({
            type: "Get",
            url: Routing.generate('event_speaker_delete', {'id': speakerId}),
            dateType: 'json',
            success: function (data) {
                parent.remove();
            }
        });
    }
});

$("#eventbundle_event_hasSpeaker").change( function(){
    if ($(this).is(':checked')){
        $('.speakerSection').show();
    } else {
        $('.speakerSection').hide();
    }
});

function speakerFormValidation() {

    var firstName = $.trim($('#eventbundle_speaker_firstName').val());
    var lastName = $.trim($('#eventbundle_speaker_lastName').val());

    if ( firstName === '' || (lastName === '')) {
        return false;
    } else {
        return true;
    }
}