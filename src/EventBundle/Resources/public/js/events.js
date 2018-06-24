function validationInit() {
    google.maps.event.addDomListener(window, 'load', initialize);

    var rajax_obj = new rajax('event_add_form',
        {
            action: Routing.generate('event_create'),
            responseType: 'json',
            data :  $('#event_add_form').serialize(),
            onComplete: function (list) {
                $('#eventbundle_location_eventId').val(list);
                //$('#eventbundle_location_locationId').val(list);
                $('.eventIdSchedule').val(list);
                $('#eventbundle_event_id').val(list);
                $('#eventbundle_ticket_eventId').val(list);
                $('#eventbundle_sponsor_event').val(list);
                $('#eventbundle_news_event').val(list);
                $('#eventbundle_speaker_event').val(list);
                $(".backButtonEvent").attr('rel', list);
                $('#form_wizard_1').bootstrapWizard('show',1);
                $('#form_wizard_1').find('.button-previous').show();
                initialize();
                google.maps.event.trigger(map,'resize')
            }
        });
    var formBasic = $('#event_add_form');
    var error = $('.alert-danger', formBasic);
    var success = $('.alert-success', formBasic);
    formBasic.validate({
        doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
        errorElement: 'span', //default input error message container
        errorClass: 'help-block help-block-error', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            'eventbundle_event[name]': {
                required: true
            },
            'eventbundle_event[contact]': {
                required: true
            },
            'eventbundle_event[privacy]': {
                required: true
            },
            'eventbundle_event[group]': {
                required: true
            }
        },
        messages : {
            'eventbundle_event[name]': "Event name should not be null",
            'eventbundle_event[contact]': "Contact should not be null",
            'eventbundle_event[privacy]':"Should be select privacy",
            'eventbundle_event[group]':"Should be select event type"
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
            return false;
        }
    });
}

function ucfirst(str) {

    str += '';
    var f = str.charAt(0)
        .toUpperCase();
    return f + str.substr(1);
}

var labelClass = {
    'Deactivate' : 'danger',
    'Activate' : 'success'
};


$('body').on('click', ".changeStatus", function (e) {
    e.preventDefault();
    var el = $(this);
    $.ajax({
        url: el.attr('href'),
        dateType: 'json',
        success: function (data) {
            var span = el.closest('tr').find('.event-status > span');
            span
                .html(ucfirst(data.status))
                .removeClass("label-success")
                .removeClass("label-danger")
                .addClass("label-" + labelClass[data.status]);

            el
                .attr('href', "/event/statusChange/" + data.status + "/" + data.id)

        }
    });
});

$('.backButtonEvent').click( function (){
    $('#form_wizard_1').bootstrapWizard('show',0);
});

var url = document.location.toString();
if (url.match('#')) {
    $('#eventWizard a[href=#'+url.split('#')[1]+']').tab('show') ;
}

// Change hash for page-reload
$('#eventWizard a').on('shown.bs.tab', function (e) {
    window.location.hash = e.target.hash;
})

$(".ticket-select").live("click", function() {
    var parent = $(this).closest('tr');
    $this = $(this);
     var output = $this.find('option:selected').attr('rel');
    if(output)
    {
        parent.find('.price-ticket-buy').val(output);
    }else{
        parent.find('.price-ticket-buy').val(0);
    }

});

$('.add-ticket-buy').live("click", function() {
        var parent = $(this).closest('tr');
        var ticket = parent.find('option:selected').val();

        if(ticket == 'Select Ticket') {
            alert('You forgot to select ticket');
            return false;
        }
        var path = $(this).attr('rel');
        var name = $(this).attr('data-ref');
        var id = $(this).val();
        var data =[
            parent.find('.schedule-date').text(),
            parent.find('.schedule-time').text(),
            parent.find('option:selected').text(),
            parent.find('.price-ticket-buy').val(),
            parent.find('.quantity').val(),
            path,
            name,
            id,
            parent.find('.schedule-date').attr('rel'),
            parent.find('option:selected').val()
        ];

        var answer = confirm("Are you sure you want to add it On cart?");
        if(answer) {
            $.ajax({
                type: "POST",
                dateType: 'json',
                url: Routing.generate('event_shopping_cart'),
                data: {'data':data},
                success: function (dataa) {
                    $('.top-cart-info-count').text(dataa.quantity);
                    $('.top-cart-info-valuee').text(dataa.price);
                    $("#event-add-msg").show().delay(5000).fadeOut();
                    data ={};
                }
            });
        }
});
$('.delete').click( function (){
    var parent = $(this).closest('tr');
    var index = $(this).attr('data-ref');
    /*var total = $(this).closest('tr').find('.totalPrice').text();*/
    $.ajax({
        type: "POST",
        url: Routing.generate('shopping_cart_event_delete'),
        data: {'data':index},
        success: function (data) {
            $('.top-cart-info-count').text(data.quantity);
            $('.top-cart-info-valuee').text(data.price);
            $('.grandPrice').text(data.price);
            parent.remove();
            if(data.quantity == 0)
            {
                $('.cart').append('<tr><td colspan="8" class="red-message">There is No Products In Cart</td></tr>');
                $('.checkoutButton').hide();
            }
        }
    });

});
var formDocument = $('#document_form');
var error2 = $('.alert-danger', formDocument);
var success2 = $('.alert-success', formDocument);

formDocument.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-inline', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: ".ignore",
    rules: {
        'document[title]': {
            required:true,
            maxlength:13
        },
        'document[file]': {
            required:true

        },
        'document[description]': {
            required:true

        }
    },
    messages : {
        'document[file]': "Choose a Event Photo"
    },


    invalidHandler: function (event, validator) { //display error alert on form submit
        success2.hide();
        error2.show();
        Metronic.scrollTo(error2, -200);
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

function cartFormValidation() {
    var parent = $(this).closest('tr');
    var ticket =  parent.find('option:selected').val();
    alert(ticket);

    if((ticket === '')) {
        return false;
    } else {
        return true;
    }
}





