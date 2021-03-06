<li class="media @if ( ! $loop->last) border-bottom @endif">
    <div class="media-left">
        <a href="{{ route('users.show', $notification->data['user_id']) }}">
            <img class="media-object img-thumbnail mr-3" alt="{{ $notification->data['user_name'] }}"
                 src="{{ $notification->data['user_avatar'] }}" style="width:48px;height:48px;"/>
        </a>
    </div>

    <div class="media-body">
        <div class="media-heading mt-0 mb-1 text-secondary">
            用户
            <a href="{{ route('users.show', $notification->data['user_id']) }}">{{ $notification->data['user_name'] }}</a>
            关注了您 !
            <span class="meta float-right" title="{{ $notification->created_at }}">
                <i class="far fa-clock"></i>
                {{ $notification->created_at->diffForHumans() }}
            </span>
            <div class="reply-content">
                <p>关注通知</p>
            </div>
        </div>
    </div>
</li>
