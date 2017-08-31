<script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>

<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3 chat-box">
            @foreach($messages  as $message)
                <div class="alert alert-info">{{ $message->msg }}</div>
            @endforeach
            <input type="text" class="send form-control">
        </div>
    </div>
</div>
<script>
    $(document).on('keydown', '.send', function (e) {
        var msg = $(this).val();
        if (!msg == '' && e.keyCode == 13 && !e.shiftKey) {
            $.ajax({
                url: '/create',
                data: {_token: '{{ csrf_token() }}', msg: msg},
                type: 'post',
                success: function () {

                }
            });
        }
    });

    function Chat() {
        $.ajax({
            url: '/ajax',
            data: {},
            success: function (data) {
                if (data.msg == null) return;
                $('.chat-box').append('<div class="alert alert-info">' + data.msg + '</div>');
                $('.send').empty();
            }
        });
    }
    setInterval(Chat, 1000);
</script>