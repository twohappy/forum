@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                {{-- thread start--}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="level">
                            <span class="flex">
                                <a href="{{route('profile',$thread->creator) }}">{{$thread->creator->name}}</a> posted:
                                {{$thread->title}}
                            </span>

                            @can('update',$thread)
                                <form method="POST" action="{{ $thread->path() }}">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button type="submit" class="btn btn-link">Delete Thread</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    <div class="panel-body">
                        {{$thread->body}}
                    </div>
                </div>
                {{--thread end && replies start--}}
                @foreach($replies as $reply)
                    @include('threads.reply')
                @endforeach

                {{ $replies->links() }}
                {{--replies end--}}
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
