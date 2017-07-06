<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *  Persist a new reply.
     *
     * @param $channel_id
     * @param Thread $thread
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channel_id, Thread $thread)
    {
        $this->validate(request(),[
            'body' => 'required'
        ]);

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if(request()->expectsJson()){
            return $reply->load('owner');
        }

        return back()->with('flash','回复成功~');
    }

    public function update(Reply $reply)
    {
        $this->authorize('update',$reply);

//        $reply->update(['body' => request('body')]);
        $reply->update(request(['body']));
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update',$reply);

        $reply->delete();

        if(request()->expectsJson()){
            return response(['status' => 'Reply deleted!']);
        }

        return back();
    }
}
