/**
 * Created by mahmoud on 29/08/17.
 */

/*
 create script to create a new message
 save it in the database
 and finally append it in the browser
 All Done using AJAX POST request.
 */
$(document).on('keydown', '#message_text', function (e) {
    var msg = $(this).val();
    if (!msg == '' && e.keyCode == 13 && !e.shiftKey) {
        $.ajax({
            url: '/createMessage',
            data: {_token: '{{ csrf_token() }}', message_text: msg, chat_id: $('#thisChatID').val()},
            type: 'post',
            success: function (data) {
                $('#msg_body').append('<div class="row">' +
                    '<div class="col-sm-2 hidden-xs">' +
                    '<img class="img img-responsive img-thumbnail chat-image"' +
                    ' src="' + data.message.user_profile + '">' +
                    '</div>' +
                    '<div class="col-sm-10">' +
                    '<div class="msg_a">' + data.message.message_text + '</div>' +
                    '</div>' +
                    '</div>');
                $('#message_text').val('');
            }
        });
    }
});

/*
 Script to deliver the created message to the receiver
 */
function Chat() {
    $.ajax({
        url: '/retrieveMessage',
        data: {chat_id: $('#thisChatID').val()},
        success: function (data) {
            if (data.message == null) return;
            $('#msg_body').append('<div class="row">' +
                '<div class="col-sm-2 hidden-xs">' +
                '<img class="img img-responsive img-thumbnail chat-image"' +
                ' src="' + data.message.user_profile + '">' +
                '</div>' +
                '<div class="col-sm-10">' +
                '<div class="msg_a">' + data.message.message_text + '</div>' +
                '</div>' +
                '</div>');
            $('#message_text').val('');
        }
    });
}

/*
 interval Script to check the server for updates if a new delivered message
 */
setInterval(Chat, 1000);

/*
 script to update the page every minute to get the status of the user
 if he is online or not.
 */
function refresh() {
    $.get("/chat_users", function (data) {
        $('#chat_users').empty().append(data);
    });
}
/*
 interval Script to check for updates if user status changed.
 */
setInterval(refresh, 1000);
