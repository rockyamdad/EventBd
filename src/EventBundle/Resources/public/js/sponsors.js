$("#eventbundle_sponsor_addSponsor").click( function(){
    if(sponsorFormValidation())
    {
        var baseurl = document.location.origin;
        var rajax_obj = new rajax('sponsor_add_form',
            {
                action: Routing.generate('event_sponsor_create'),
                responseType: 'json',
                data :  $('#sponsor_add_form').serialize(),
                onComplete: function (sponsor) {
                    $("#sponsor_add_form")[0].reset();
                    var img = baseurl+"/uploads/"+ sponsor.photo;
                    var html = [];
                    html.push('<td>' + sponsor.name + '</td>');
                    if(sponsor.description == null){
                        html.push('<td>' + "Not Available" + '</td>');
                    }else{
                        html.push('<td>' + jQuery.trim(sponsor.description).substring(0, 20)
                            .split(" ").slice(0, -1).join(" ") + "..." + '</td>');
                    }
                    html.push('<td> <div class="product-item" style="background: none;padding: 0;width: 80px;"><div class="pi-img-wrapper" style="width: 80px;"> <img src="' + img + '" style="height: 50px;width: 80px;" class="img-responsive"> </div> </div> </td>');
                    html.push('<td><input type="button"  id="deleteSponsor" style="width:127px;" value="delete" class="btn red deleteSponsor" rel=' + sponsor.id + ' ></td>');

                html = '<tr>' + html.join('') + '<tr>';
                $('#sponsorTable  > tbody:first').append(html);

                }
            });
        rajax_obj.post();
    }else {
        alert('You forgot to fill something out');
    }
});

$('.deleteSponsor').live("click", function() {

    var sponsorId = $(this).attr('rel');
    var parent = $(this).closest('tr');
    var answer     = confirm("Are you sure you want to delete this Sponsor?");
    if(answer) {
        $.ajax({
            type: "Get",
            url: Routing.generate('event_sponsor_delete', {'id': sponsorId}),
            dateType: 'json',
            success: function (data) {
                parent.remove();
            }
        });
    }
});

$("#eventbundle_event_hasSponsor").change( function(){
    if ($(this).is(':checked')){
        $('.sponsorSection').show();
    } else {
        $('.sponsorSection').hide();
    }
});

function sponsorFormValidation() {

    var name = $.trim($('#eventbundle_sponsor_name').val());
    var logo = $.trim($('#eventbundle_sponsor_file').val());

    if ( name === '' || (logo === '')) {
        return false;
    } else {
        return true;
    }
}
