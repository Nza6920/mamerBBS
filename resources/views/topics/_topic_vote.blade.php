<div class="vote-box">
    <div class="buttons">
        @if(! Auth::user()->hasVoted($topic))
            <form action="{{ route('topics.vote.up', $topic->id) }}" method="POST">
                {{ csrf_field() }}
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-thumbs-o-up"></i>
                    <span>点个赞</span>
                </button>
            </form>
        @else
            <form action="{{ route('topics.vote.cancel', $topic->id) }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-thumbs-up"></i>
                    <span>已点赞</span>
                </button>
            </form>
        @endIf
    </div>
    <div class="voted-users">
        @foreach ($users as $user => $avatar)
            <a href="{{ route('users.show', $user) }}">
                <img width="45px" height="45px" src="{{ $avatar }}">
            </a>
        @endforeach
    </div>
</div>
