@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#">{{$thread->creator->name}}</a> posted:
                        {{$thread->title}}
                    </div>
                    <div class="panel-body">
                        {{$thread->body}}
                    </div>
                </div>

                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{ $replies->links() }}

                {{--回复表单，应该只有登录用户可以看到--}}
                @if (auth()->check() )
                    <form method="POST" action="{{ $thread->path() . '/replies' }}">
                        {{csrf_field()}}
                        <div class="form-group">
                            <textarea name="body" id="body" class="form-control" placeholder="Have something to say?"
                                      rows="5"></textarea>
                        </div>
                        <button class="btn btn-default" type="submit">Post</button>
                    </form>
                @else
                    <p class="text-center">Please <a href="{{route('login')}}">Sign In</a> to participate in this
                        discussion. </p>
                @endif
            </div>

            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        This thread was published {{$thread->created_at->diffforHumans()}}
                        by <a href="#">{{ $thread->creator->name }}</a>, and currently
                        has {{ $thread->replies_count }} {{ str_plural('comment',$thread->replies_count) }}.
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
