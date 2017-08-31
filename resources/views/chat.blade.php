@extends('messenger')

@section('chat')
    <!--Chat Messages in Right-->
    <div class="tab-content scrollbar-wrapper wrapper scrollbar-outer" id="chat-scroll">

        <div class="msg_body" id="msg_body">
            @foreach($messages as $message)
                @if($message->sender == Auth::user()->id)
                    <div class="row">
                        <div class="col-sm-2 hidden-xs">
                            <img class="img img-responsive img-thumbnail chat-image"
                                 src="{{ Auth::user()->getProfile() }}">
                        </div>
                        <div class="col-sm-10">
                            <div class="msg_a">{{ $message->message_text }}</div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="msg_b">{{ $message->message_text }}</div>
                        </div>
                        <div class="col-sm-2 hidden-xs">
                            <img class="img img-responsive img-thumbnail chat-image"
                                 src="{{ $friend->getProfile() }}">
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="send-message">
        @include('send-chat-message')
    </div>
@endsection