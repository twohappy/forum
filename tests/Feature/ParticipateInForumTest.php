<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Give we have a authenticated user
        $this->be($user = create('App\User'));

        // And an existing thread
        $thread = create('App\Thread');

        // When the user adds a reply to the thread
        // in memory
        $reply = make('App\Reply');
        $this->post($thread->path() . '/replies', $reply->toArray());

        // Then their reply should be visible on the page.
        $this->get($thread->path())
            ->assertSee($reply->body);
    }

    /** @test */
    function unauthenticated_users_may_not_add_replies()
    {
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect('login');
    }

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_users_can_not_delete_replies()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->delete("replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()->delete("replies/{$reply->id}")
            ->assertStatus(403);

    }

    /** @test */
    function authorized_users_can_delete_replies()
    {
        $this->signIn();
        $reply = create('App\Reply',['user_id' => auth()->id()]);


        $this->delete("replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
    }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create('App\Reply',['user_id' => auth()->id()]);

        $updatedReply = 'You"ve been changed, shabi';

        $this->patch("/replies/{$reply->id}",['body' => $updatedReply] );

        $this->assertDatabaseHas('replies',['id' => $reply->id, 'body' => $updatedReply]);
    }

    /** @test */
    function unauthorized_users_can_not_update_replies()
    {
        $this->withExceptionHandling();
        $reply = create('App\Reply');
        $this->patch("replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()->patch("replies/{$reply->id}")
            ->assertStatus(403);

    }
}
