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
        $thread = create('App\Thread');

        $this->post('/threads', $thread->toArray());

        // Then, when we visit the thread page
        $response = $this->get($thread->path());

        // We should see the new thread
        $response->assertSee($thread->title)
            ->assertSee($thread->body);

    }
}
