<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
            ->post('/replies/1/favorites')
            ->assertRedirect('login');

    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $reply = create('App\Reply');
        // 这里可以有很多路由结构，比如
        // threads/channel/id/replies/id/favorites  有点过于复杂
        // replies/id/favorite                      单数..
        // replies/id/favorites                     这个可以用route model biding 来搞 reply 还是比较好的。
        // favorites/       <-- reply_id in the request  需要手动去弄reply


        // If I post to a "favorite" endpoint
        $this->signIn()
            ->post('/replies/' . $reply->id . '/favorites');
        // It should be recorded in the database.
        //  $this->assertDatabaseHas(...)

        $this->assertCount(1, $reply->favorites);
    }
    /** @test */
    public function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->signIn();
        $reply = create('App\Reply');

        $reply->favorite();

        $this->delete('/replies/' . $reply->id . '/favorites');

        $this->assertCount(0, $reply->favorites);
    }
    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();
        $reply = create('App\Reply');
        try{
            $this->post('/replies/' . $reply->id . '/favorites');
            $this->post('/replies/' . $reply->id . '/favorites');
        } catch (\Exception $e){
            $this->fail('Did not expect to insert the same record twice.');
        }

        $this->assertCount(1, $reply->favorites);

    }


}

