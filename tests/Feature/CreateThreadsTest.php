<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_can_not_see_create_thread_page()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('login');

        $this->post('/threads')
            ->assertRedirect('login');

    }

    /** @test */
    function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        $this->signIn();

        // When we hit the endpoint to create a new thread
//        $thread = factory('App\Thread')->raw();
//
//        $this->post('/threads',$thread);
        $thread = make('App\Thread');

        $response = $this->post('/threads', $thread->toArray());

        // Then, when we visit the thread page
        $this->get($response->headers->get('location'))
            // We should see the new thread
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel',2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function authorized_user_can_delete_threads()
    {
        $this->signIn();
        $thread = create('App\Thread',['user_id' => auth()->id()]);
        $reply = create('App\Reply',['thread_id' => $thread->id]);
        $response = $this->json('DELETE',$thread->path());
        $response->assertStatus(204 );
        $this->assertDatabaseMissing('threads',['id' => $thread->id]);
        $this->assertDatabaseMissing('replies',['id' => $reply->id]);
    }

    /** @test */
    function unauthenticated_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');
        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);

    }

    // this is not a test but a helper function
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        $response = $this->post('/threads', $thread->toArray());

        return $response;
    }


}
