<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_can_subscribe_to_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions');

        $thread->addReply([
            'user_id' => auth()->id(),
            'body'  => 'some reply'
        ]);

//        auth()->user()->notifications;

    }
}
