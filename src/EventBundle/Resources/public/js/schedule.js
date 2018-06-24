$(".singleScheduleCreateBtn").click(function () {
    $.ajax({
        type: "POST",
        url: Routing.generate('event_schedule_create'),
        data: $('#event_schedule_add_form').serialize(),
        success: function (schedule) {
           // $('#eventbundle_ticket_eventId').val(schedule.eventId);
            $('#singleSchedule').val(schedule.scheduleId);
            $('#form_wizard_1').bootstrapWizard('show', 3);
        }
    });
});
var requestRunning = false;
$(".scheduleMultipleCreateBtn").click(function () {
    if (requestRunning) { // don't do anything if an AJAX request is pending
        return;
    }
    var frequency = $("#eventbundle_schedule_master_frequency").val();
    requestRunning = true;
    $.ajax({
        type: "POST",
        url: Routing.generate('event_schedule_multiple_create'),
        data: $('#event_schedule_multiple_form').serialize(),
        success: function (schedule) {
           // $('#eventbundle_ticket_eventId').val(schedule.eventId);
            $('#multipleScheduleId').val(schedule.scheduleId);

            if(schedule.day == 0)
            {
                $('.singleSchedule').hide();
                alert("Sorry We couldn't found any day between this dates");
                return false;
            }
            $("option:selected").removeAttr("selected");
            if(schedule.hasSchedule == 0){
                var html =[];
                html.push('<tr class="series-row-head" style="background-color:white">'+
                    '<th width="75" class="report__cell--selected series-cell-dates">'+
                    '<span class="text-body-large text-body--significant " id="dateCount"> ' + schedule.day +" Dates "+ '</span></th>'+
                    '<th class="report__cell--selected">'+
                    '<p class="l-block-1 text-body--significant"> ' + schedule.frequency + " "+schedule.startTime + " - "+ schedule.endTime +'</p>'+
                    '<p class="text-body-small text-body--faint">'+"Starting "+'<span class="startDate">'+ schedule.startDate+'</span>'+" through "+'<span class="endDate">'+ schedule.endDate +'</span></p>'+
                    '</th>'+
                    '<th class="report__cell--selected">'+
                    '<div class="series-card-tools">' +
                    '<input type="button" value="Detail"  id="detailsMultipleSchedule"   class="btn btn-info  fa fa-edit detailsMultipleSchedule" rel=' + schedule.scheduleId + ' >' +
                    '<input type="button" value="delete"   id="deleteMultipleSchedule"   class="btn btn-danger fa fa-trash-o deleteMultipleSchedule" rel=' + schedule.scheduleId + ' >' +
                    ' </div>'+
                    '</th>'+
                    '</tr>');
                $('#scheduleTable  > tbody:first').append(html);
            }
            $('#dateCount').text(schedule.day +' Dates');
            $('.endDate').text(schedule.endDate);
            $('.startDate').text(schedule.startDate);
            $('.singleSchedule').hide();
            $('.single').hide();
            $('.stepNext').show();
            $('#event_schedule_multiple_form').trigger('reset');
            $("#eventbundle_schedule_master_frequency").select2('val', frequency);
            $('.daysOfWeekselect2')[0].sumo.reload();
            $('.daysOfMonthselect2')[0].sumo.reload();


        },
        complete: function() {
            requestRunning = false;
        }
    });
    return false;
});
$(".detailsMultipleSchedule").live("click", function() {
    var multipleScheduleId = $(this).attr('rel');

    $( ".detailsMultipleSchedule" ).removeClass( "detailsMultipleSchedule" ).addClass( "detailsMultipleScheduleHide" );

    $.ajax({
        type: "POST",
        url: Routing.generate('event_schedule_multiple_detail', {'id': multipleScheduleId}),
        success: function (scheduleDetail) {
            $.each(scheduleDetail,function(key,value){

                var html =[];
                html.push('<tr class="series-row-head">'+
                    '<td class="report__cell--selected">'+
                    '<p class="l-block-1 text-body--significant"> ' + value.date + " ("+value.startTime + " - "+ value.endTime +")"+'</p>'+
                    '</td>'+
                    '<td class="report__cell--selected">'+
                    '<div class="series-card-tools">' +
                    '<input type="button" value="delete"   id="deleteScheduleDetail"   class="btn btn-danger fa fa-trash-o deleteScheduleDetail" rel=' + value.id + ' >' +
                    ' </div>'+
                    '</td>'+
                    '</tr>');

                html = '<tr>' + html.join('') + '<tr>';
                $('#scheduleDetailsTable  > tbody:first').append(html);

            });

        }
    });
});

$(".detailsMultipleScheduleHide").live("click",function (){
    $( ".detailsMultipleScheduleHide" ).removeClass( "detailsMultipleScheduleHide" ).addClass( "detailsMultipleSchedule" );
    $('#scheduleDetailsTable tbody').empty();
});

$('.deleteMultipleSchedule').live("click", function() {

    var multipleScheduleId = $(this).attr('rel');
    var parent = $(this).closest('tr');
    var answer     = confirm("Are you sure you want to delete this Schedule?");
    if(answer) {
        $.ajax({
            type: "Get",
            url: Routing.generate('event_multiple_schedule_delete', {'id': multipleScheduleId}),
            dateType: 'json',
            success: function (data) {
                parent.remove();
                $('#scheduleDetailsTable tbody').empty();
                $('.multipleSchedule').show();
            }
        });
    }
});
$('.deleteScheduleDetail').live("click", function() {

    var detailScheduleId = $(this).attr('rel');
    var parent = $(this).closest('tr');
    var answer     = confirm("Are you sure you want to delete this Schedule?");
    if(answer) {
        $.ajax({
            type: "Get",
            url: Routing.generate('event_detail_schedule_delete', {'id': detailScheduleId}),
            dateType: 'json',
            success: function (data) {
                var rowCount = $('#scheduleDetailsTable .series-row-head').length;

                $('#dateCount').text(data.dates +' Dates');
                $('.endDate').text(data.lastScheduleDetail);

                if(rowCount == 1){
                    $('#scheduleTable tbody').empty();
                    $('.multipleSchedule').show();
                }
                parent.remove();
            }
        });
    }
});

$(".multiple").click( function(){
    $('.multipleSchedule').show();
    $('.singleSchedule').hide();
    $('.stepNext').show();
});
$(".single").click( function(){
    $('.multipleSchedule').hide();
    $('.singleSchedule').show();
    $('.stepNext').hide();
});

$("#eventbundle_schedule_master_frequency").click( function(){
    var value = $(this).val()
    if(value == 'Weekly' ){

        $('.daysOfWeekSection').show();
        $('.daysOfMonthSection').hide();
    }else if(value == 'Monthly')
    {

        $('.daysOfWeekSection').hide();
        $('.daysOfMonthSection').show();
    }else
    {

        $('.daysOfWeekSection').hide();
        $('.daysOfMonthSection').hide();
    }
    var answer = confirm("Are you sure you want to  create " +value+ " event?");
   /* swal({   title: "Are you sure?",   text: "You will not be able to recover this imaginary file!",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){   swal("Deleted!", "Your imaginary file has been deleted.", "success"); });*/
    if(answer){
        $("#eventbundle_schedule_master_frequency").attr('readonly','readonly');
    }

});

$('.scheduleFinish').click(function (){

        var rowCount = $('#scheduleTable tbody tr').length;
        if(rowCount > 0 ){
            $('#form_wizard_1').bootstrapWizard('show',3);
        } else{
            alert('You have to set Schedule');
        }

});
$('.backButtonSchedule').click( function (){
    $('#form_wizard_1').bootstrapWizard('show',2);
});