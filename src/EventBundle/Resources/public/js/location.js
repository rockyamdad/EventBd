function locationCreate($url) {

    console.log($url);
    var rajax_obj = new rajax('event_location_add_form',
        {
            action: $url,
            responseType: 'json',
            data :  $('#event_location_add_form').serialize(),
            onComplete: function (list) {

               // $('.eventIdSchedule').val(list.eventId);

                $('#eventbundle_location_locationId').val(list.locationId);
                $('.stepNext').hide();
                if(list.isMultiple)
                {
                    $('.singleSchedule').hide();
                    $('.multipleSchedule').show();
                    $('.single').hide();
                    $('.stepNext').show();
                }
                $('#form_wizard_1').bootstrapWizard('show',2);
            }
        });

    var formLocation = $('#event_location_add_form');
    var error = $('.alert-danger', formLocation);
    var success = $('.alert-success', formLocation);

    formLocation.validate({
        doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            'eventbundle_location[venueName]': {
                required: true
            },
            'eventbundle_location[address]': {
                required: true
            },
            'eventbundle_location[postalCode]': {
                required: true
            }
        },
        messages : {
            'eventbundle_location[venueName]': "Vanue name should not be blank",
            'eventbundle_location[address]': "Address  should not be blank",
            'eventbundle_location[postalCode]': "Postal Code should not be blank"
        },

        invalidHandler: function (event, validator) { //display error alert on form submit
            success.hide();
            error.show();
            Metronic.scrollTo(error, -200);
        },

        highlight: function (element) { // hightlight error inputs
            $(element)
                .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
        },

        unhighlight: function (element) { // revert the change done by hightlight
            $(element)
                .closest('.form-group').removeClass('has-error'); // set error class to the control group
        },

        success: function (label) {
            label
                .addClass('valid') // mark the current input as valid and display OK icon
                .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
        },
        submitHandler: function (form) {
            rajax_obj.post();
        }
    });

}

function initialize() {


    if($('#eventbundle_location_latitude').val()){
        var mapOptions = {

            center: new google.maps.LatLng(
                $('#eventbundle_location_latitude').val(),
                $('#eventbundle_location_longitude').val()),
            zoom: 13
        };
    } else {
        var mapOptions = {
            center: new google.maps.LatLng(23.810332, 90.41251809999994),
            zoom: 13
        };
    }


    map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);

    var input = /** @type {HTMLInputElement} */(
        document.getElementById('eventbundle_location_address'));


    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
    });

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        console.log( place.geometry.location.lat());
        console.log(place.geometry.location.lng());
        document.getElementById("eventbundle_location_latitude").value = place.geometry.location.lat();
        document.getElementById("eventbundle_location_longitude").value = place.geometry.location.lng();

        if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
        }
        marker.setIcon(/** @type {google.maps.Icon} */({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }

        infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);

        infowindow.open(map, marker);
    });

    // Sets a listener on a radio button to change the filter type on Places
}

$('.backButtonLocation').click( function (){
    $('#form_wizard_1').bootstrapWizard('show',1);
});