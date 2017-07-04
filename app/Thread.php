<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $guarded = [];
    protected $with = ['creator','channel'];
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builer) {
            $builer->withCount('replies');
        });

//        eloquent 的 events
        static::deleting(function($thread){
           $thread->replies()->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
//        return '/threads/' . $this->channel->slug. '/' . $this->id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function creator()
    {
        // laravel 自动去threads里面寻找默认的creator_id字段，所以需要指定外键 ‘user_id’
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
