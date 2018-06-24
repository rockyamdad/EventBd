
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
                var span = el.closest('tr').find('.user-status > span');
                span
                    .html(ucfirst(data.status))
                    .removeClass("label-success")
                    .removeClass("label-danger")
                    .addClass("label-" + labelClass[data.status]);

                    el
                        .attr('href', "/users/statusChange/" + data.status + "/" + data.id)

            }
        });
    });
/*$('body').on('click', "#userbundle_user_save", function(){

    $.ajax({
        type: "POST",
        url : Routing.generate('user_save'),
        data :  $('#form-create-user').serialize(),
        success:function()
        {
            ("#event".trigger())
           
        }
    });

});*/

var form = $('#form-create-user');
var error = $('.alert-danger', form);
var success = $('.alert-success', form);

form.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-inline', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: ".ignore",
    rules: {
        'userbundle_user[firstname]': {
            required: true
        },
        'userbundle_user[lastname]': {
            required: true
        },
        'userbundle_user[gender]': {
            required: true
        },
        'userbundle_user[email]': {
            required: true,
            email: true,
            remote: {
                url: Routing.generate('email_duplicacy_check'),
                data: {
                    'user_id': function () {
                        return $('#user_id') ? $('#user_id').val() : "";
                    }
                },
                type: 'post'
            }
        },
        'userbundle_user[plainPassword][first]': {
            minlength: 6
        },

        'userbundle_user[plainPassword][second]': {
            equalTo: '#userbundle_user_plainPassword_first'
        }
    },
    messages : {
        'userbundle_user[email]': {
            remote: jQuery.validator.format('The email address "{0}" is already registered!')
        }
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
        success.show();
        error.hide();
        var postData = form.serializeArray();
        var formURL = form.attr("action");
        $.ajax( {
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data) {

                    $(".fancybox-desktop").html(data);
            }
        });
        return false;
    }
});


var formPassword = $('#password-form');
var error2 = $('.alert-danger', formPassword);
var success2 = $('.alert-success', formPassword);

formPassword.validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-inline', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: ".ignore",
    rules: {
        'fos_user_change_password_form[plainPassword][first]': {
            minlength: 6
        },

        'fos_user_change_password_form[plainPassword][second]': {
            equalTo: '#fos_user_change_password_form_plainPassword_first'
        }
    },
    messages: {
        'fos_user_change_password_form[plainPassword][second]': {
            equalTo: "Password does not match."
        }
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

$("#login").live("click", function(event) {
    event.preventDefault();
    var formLogin = $('#login-form');
    var postData = formLogin.serializeArray();
    var formURL = formLogin.attr("action");
    var url      = window.location.href;

    $.ajax( {
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data) {
            console.log($(data).find('.alert-danger').length);
           // console.log((data));
           if($(data).find('.alert-danger').length == 1)
            {
                $(".login-page-error").html(data);

            }else{
                location.href = url;
            }



        }
    });
    return false;

});