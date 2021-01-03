<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function listOfPostsCanBeFetched()
    {
        Post::factory(10)
            ->has(Comment::factory(10), 'comments')
            ->create();

        $response = $this->get(route('posts.index'));

        $response->assertJsonCount(3, 'data'); //for first page
        $response->assertStatus(200);
        $this->assertEquals($response['meta']['total'], '10');
    }

    /** @test */
    public function postCanBeCreated()
    {
        $data = [
            'title' => 'Test title',
            'content' => 'Test content'
        ];

        $response = $this->post(route('posts.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('posts', $data);
    }

    /** @test */
    public function postCanBeFetched()
    {
        Post::factory(10)
            ->has(Comment::factory(10), 'comments')
            ->create();

        $response = $this->get(route('posts.show', ['post_id' => 1]));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'content',
                'created_at',
                'updated_at',
                'comments'
            ]
        ]);
    }

    /** @test */
    public function postCanBeUpdated()
    {
        Post::factory(1)
            ->has(Comment::factory(10), 'comments')
            ->create();

        $route = route('posts.update', ['post_id' => 1]);

        $updateData = [
            'title' => 'updated title',
            'content' => 'updated content',
        ];

        $response = $this->patch($route, $updateData);

        $response->assertStatus(200);
        $this->assertEquals($response['data']['title'], 'updated title');
        $this->assertEquals($response['data']['content'], 'updated content');
    }

    /** @test */
    public function postCanBeDeleted()
    {
        Post::factory(1)
            ->has(Comment::factory(10), 'comments')
            ->create();

        $route = route('posts.destroy', ['post_id' => 1]);

        $response = $this->delete($route);

        $this->assertDeleted('posts', ['id' => 1]);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function titleIsRequired()
    {
        $data = [
            'content' => 'Test content'
        ];

        $response = $this->post(route('posts.store'), $data);

        $response->assertSessionHasErrors('title');

        $this->assertDatabaseMissing('posts', $data);
    }

    /** @test */
    public function contentIsRequired()
    {
        $data = [
            'title' => 'Test title'
        ];

        $response = $this->post(route('posts.store'), $data);

        $response->assertSessionHasErrors('content');

        $this->assertDatabaseMissing('posts', $data);
    }
}
