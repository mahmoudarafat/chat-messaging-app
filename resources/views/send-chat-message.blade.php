{{-- {!! Form::open(['route' => 'user.createMessage', 'class' => 'form', 'id' => 'messageFormSender']) !!}--}}
{!! Form::hidden('chat_id', $chat->id, ['id' => 'thisChatID']) !!}
<div class="form-group">
    <input type="text" class="form-control" placeholder="type a message..."
           name="message_text" id='message_text'>
</div>
{{--{!! Form::close() !!}--}}