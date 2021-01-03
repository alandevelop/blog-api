<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use App\Models\Comment;


class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function listOfCommentsCanBeFetched()
    {
        Post::factory(10)
            ->has(Comment::factory(10), 'comments')
            ->create();

        $response = $this->get(route('posts.comments.index', ['post_id' => 1]));

        $response->assertJsonCount(5, 'data'); //for first page
        $response->assertStatus(200);
        $this->assertEquals($response['meta']['total'], '10');
    }

    /** @test */
    public function commentCanBeCreated()
    {
        Post::factory(1)
            ->create();

        $data = [
            'email' => 'test@test.com',
            'content' => 'Test content'
        ];

        $response = $this->post(route('posts.comments.store', ['post_id' => 1]), $data);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('comments', $data);
    }

    /** @test */
    public function commentCanBeFetched()
    {
        Post::factory(10)
            ->has(Comment::factory(10), 'comments')
            ->create();

        $response = $this->get(route('posts.comments.show', ['post_id' => 1, 'comment_id' => 1]));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'content',
                'created_at',
                'updated_at',
            ]
        ]);
    }

    /** @test */
    public function commentCanBeUpdated()
    {
        Post::factory(10)
            ->has(Comment::factory(10), 'comments')
            ->create();

        $route = route('posts.comments.update', ['post_id' => 1, 'comment_id' => 1]);

        $data = [
            'email' => 'test@test.com',
            'content' => 'Test content'
        ];

        $response = $this->patch($route, $data);

        $response->assertStatus(200);
        $this->assertEquals($response['data']['email'], 'test@test.com');
        $this->assertEquals($response['data']['content'], 'Test content');
    }

    /** @test */
    public function commentCanBeDeleted()
    {
        Post::factory(10)
            ->has(Comment::factory(10), 'comments')
            ->create();

        $route = route('posts.comments.destroy', ['post_id' => 1, 'comment_id' => 1]);

        $response = $this->delete($route);

        $this->assertDeleted('comments', ['id' => 1]);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function emailIsRequired()
    {
        Post::factory(1)
            ->create();

        $data = [
            'content' => 'Test content'
        ];

        $response = $this->post(route('posts.comments.store', ['post_id' => 1]), $data);

        $response->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('comments', $data);
    }

    /** @test */
    public function contentIsRequired()
    {
        Post::factory(1)
            ->create();

        $data = [
            'email' => 'test@test.com'
        ];

        $response = $this->post(route('posts.comments.store', ['post_id' => 1]), $data);

        $response->assertSessionHasErrors('content');

        $this->assertDatabaseMissing('comments', $data);
    }


}
