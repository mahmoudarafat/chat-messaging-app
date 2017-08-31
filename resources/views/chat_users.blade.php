<ul class="list-unstyled">
    @foreach( $friends as $myFriend )
        <li>
            <a href="{{ route("chat", [$myFriend->id]) }}">
                <div class="row">
                    <div class="col-sm-3">
                        <img src="{{ $myFriend->getProfile() }}"
                             class="img-responsive chat-image">
                    </div>
                    <div class="col-sm-9">
                        {{ ucfirst($myFriend->username)}} | @if($myFriend->isOnline()) Online @else Offline @endif
                    </div>
                </div>
            </a><br>
        </li>
    @endforeach
</ul><!--Contact List in Left End-->