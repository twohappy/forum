<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ChannelTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_channel_consists_of_threads()
    {
//        测试关系的几种办法：
//        1、测试这个关系是否返回一个collection
//        2、搞一个channel，搞一个thread，fetch channel里面所有的thread，测试是否搞的那个thread出现在这个collection里面
        $channel = create('App\Channel');
        $thread = create('App\Thread',['channel_id' => $channel->id]);
        $this->assertTrue($channel->threads->contains($thread));
    }
}
