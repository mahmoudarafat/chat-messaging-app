<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<style>
    .chat-image {
        display: block;
        width: 40px;
        height: 40px;
        margin: 0;
        position: relative;
    }

    .msg_a {
        position: relative;
        background: #FDE4CE;
        padding: 10px;
        min-height: 10px;
        margin-bottom: 5px;
        margin-right: 10px;
        border-radius: 5px;
    }

    .msg_a:before {
        content: "";
        position: absolute;
        width: 0px;
        height: 0px;
        border: 10px solid;
        border-color: transparent #FDE4CE transparent transparent;
        left: -20px;
        top: 7px;
    }

    .msg_b {
        background: #EEF2E7;
        padding: 10px;
        min-height: 15px;
        margin-bottom: 5px;
        position: relative;
        margin-left: 10px;
        border-radius: 5px;
    }

    .msg_b:after {
        content: "";
        position: absolute;
        width: 0px;
        height: 0px;
        border: 10px solid;
        border-color: transparent transparent transparent #EEF2E7;
        right: -20px;
        top: 7px;
    }

</style>
<div id="page-contents">
    <div class="container">
        {{--<div class="row">--}}
        <div class="col-md-10 col-md-offset-1">

            {{--page header--}}
            <div class="row">
                <div class="col-md-10">
                    <h2 class="page-header">Messenger</h2>
                </div>
                <div class="col-md-2 pull-right">
                    <br>
                    <h4><a href="{{ route('logout') }}" class="btn btn-default"
                           onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
                            Logout
                        </a></h4>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>


            <div class="chat-room">
                <div class="row">
                    <div class="col-md-4" style="background: #eee; padding-top: 10px;">
                        {{--friends list view--}}
                        <section id="chat_users">
                            @include('chat_users')
                        </section>

                    </div>
                    <div class="col-md-7">
                        {{--chat content view--}}
                        <section id="chat-content">
                            @yield('chat')
                        </section>

                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>
    </div>
</div>


<script>
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
                $('#msg_body').append('<div class="row">'+
                        '<div class="col-sm-10">'+
                        '<div class="msg_b">'+data.message.message_text+'</div>'+
                        '</div>'+
                        '<div class="col-sm-2 hidden-xs">'+
                        '<img class="img img-responsive img-thumbnail chat-image" src="'+data.message.user_profile+'">'+
                        '</div>'+
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

</script>

