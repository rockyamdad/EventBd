
function ticketListing(){
    var ticketList = JSON.parse($('#ticketList').val());

    for (var k in ticketList) {
        if (ticketList.hasOwnProperty(k)) {
            var html = [];
            html.push('<td>' + ticketList[k].ticketName + '</td>');
            html.push('<td>' + ticketList[k].ticketType + '</td>');
            html.push('<td>' + ticketList[k].quantity + '</td>');
            html.push('<td>' + ticketList[k].price + '</td>');
            html.push('<td>' + ticketList[k].tax + '</td>');
            html.push('<td>' + ticketList[k].maximumTicketBuy + '</td>');
            html.push('<td><input type="button"  id="deleteTicketDetail" style="width:127px;" value="delete" class="btn red deleteTicket" rel=' + ticketList[k].ticketId + ' ></td>');
            html = '<tr>' + html.join('') + '<tr>';
            $('#ticketTable  > tbody:first').append(html);

        }
    }


}

ticketListing();
var requestRunning = false;
$("#eventbundle_ticket_addTicket").live("click", function () {
    if (requestRunning) { // don't do anything if an AJAX request is pending
        return;
    }
if(ticketFormValidation()){
    requestRunning = true;
    $.ajax({
        type: "POST",
        url : Routing.generate('add_ticket_create'),
        data :  $('#event_ticket_add_form').serialize(),
        dataType:'json',
        success:function(ticketDetail)
        {
            $("#event_ticket_add_form")[0].reset();
            $('#eventbundle_ticket_price').prop('readonly', false);
            $('#eventbundle_ticket_tax').prop('readonly', false);
            $('#ticketId').val(ticketDetail.ticketId);

            var html = [];
            html.push('<td>' + ticketDetail.ticketName + '</td>');
            html.push('<td>' + ticketDetail.ticketType + '</td>');
            html.push('<td>' + ticketDetail.quantity + '</td>');
            if( ticketDetail.price == null){
                html.push('<td>' + "Not Available" + '</td>');
            }else{
                html.push('<td>' + ticketDetail.price + '</td>');
            }
            if( ticketDetail.tax == null){
                html.push('<td>' + "Not Available" + '</td>');
            }else{
                html.push('<td>' + ticketDetail.tax + '</td>');
            }
            if( ticketDetail.maximumTicketBuy == null){
                html.push('<td>' + "Not Available" + '</td>');
            }else{
                html.push('<td>' + ticketDetail.maximumTicketBuy + '</td>');
            }
            html.push('<td><input type="button"  id="deleteTicketDetail" style="width:127px;" value="delete" class="btn red deleteTicket" rel=' + ticketDetail.ticketId + ' ></td>');

            html = '<tr>' + html.join('') + '<tr>';
            $('#ticketTable  > tbody:first').append(html);

        },
        complete: function() {
            requestRunning = false;
        }
    });
    return false;
} else {
    alert('You forgot to fill something out');
}
});
$('.deleteTicket').live("click", function() {

    var ticketId = $(this).attr('rel');
    var parent = $(this).closest('tr');
    var answer     = confirm("Are you sure you want to delete this ticket?");
    if(answer) {
        $.ajax({
            type: "Get",
            url: Routing.generate('event_ticket_delete', {'id': ticketId}),
            dateType: 'json',
            success: function (data) {
                parent.remove();
            }
        });
    }
});
$('#eventbundle_ticket_save').click(function (){

    var rowCount = $('#ticketTable tbody tr').length;
    if(rowCount > 2 ){
        $('#form_wizard_1').bootstrapWizard('show',4);
    } else{
        alert('At least one ticket add to event.');
    }
    $('#eventbundle_event_hasSpeaker').trigger('change');
    $('#eventbundle_event_hasSponsor').trigger('change');
    $('#eventbundle_event_hasNews').trigger('change');
});

$('#eventbundle_ticket_ticketCategory').change( function (){
    var ticket_category = $('#eventbundle_ticket_ticketCategory :selected').text();
    if(ticket_category =='free' || ticket_category =='Free'){
        $('#eventbundle_ticket_price').prop('readonly', true);
        $('#eventbundle_ticket_tax').prop('readonly', true);
    } else{
        $('#eventbundle_ticket_price').prop('readonly', false);
        $('#eventbundle_ticket_tax').prop('readonly', false);
    }
});

function ticketFormValidation() {

        var name = $.trim($('#eventbundle_ticket_name').val());
        var type = $.trim($('#eventbundle_ticket_ticketCategory').val());
        var quantity = $.trim($('#eventbundle_ticket_quantity').val());
        var price = $.trim($('#eventbundle_ticket_price').val());
        var tax = $.trim($('#eventbundle_ticket_tax').val());

        if (type === '' || (name === '') || (quantity === '')) {
            return false;
        } else {
            return true;
        }
}
$('.backButtonTicket').click( function (){
    $('#form_wizard_1').bootstrapWizard('show',3);
});