
var formSearch = $('#searchForm');
var error = $('.alert-danger', formSearch);
var success = $('.alert-success', formSearch);

formSearch.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-inline', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: ".ignore",
    rules: {
        'search[location]': {
            required: true
        }
    },
    messages : {
        'search[location]': "Which place event you need"
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
    }




});
function initialize(latlang) {
    var latLang = latlang;
    var mapOptions = {

        center: new google.maps.LatLng(23.810332, 90.41251809999994),
        zoom: 10
    };
    map = new google.maps.Map(document.getElementById('map-canvas-search'),
        mapOptions);
    var bounds = new google.maps.LatLngBounds();
    var markers = [];
    for (var i = 0; i < latLang.length; i++) {
        var data = latLang[i];
        var latLng = new google.maps.LatLng(data.latitude,
            data.longitude);
        var marker = new google.maps.Marker({
            position: latLng
        });
        bounds.extend(latLng);
        map.fitBounds(bounds);
        markers.push(marker);
    }
    var markerCluster = new MarkerClusterer(map, markers);


    var input = /** @type {HTMLInputElement} */(
        document.getElementById('search_location'));


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
